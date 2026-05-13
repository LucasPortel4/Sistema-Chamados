<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use Illuminate\Http\Request;

class ChamadoPublicoController extends Controller
{
    private const SESSION_CHAMADOS_KEY = 'meus_chamados_ids';

    // Exibir formulário
    public function create()
    {
        return view('publico.criar-chamado');
    }

    // Salvar chamado
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'ramal' => 'required|string|max:5|regex:/^[0-9]*$/',
            'area_usuario' => 'nullable|string|max:255',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'anexo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120' // 5MB max
        ]);

        // Upload do anexo se existir
        if ($request->hasFile('anexo')) {
            $validated['anexo'] = $request->file('anexo')->store('anexos', 'public');
        }



        $chamado = Chamado::create($validated);
        $this->addChamadoToSession($request, $chamado->id);
        Chamado::clearDashboardStatsCache();

        // Aqui você pode adicionar notificação por email depois
        // Mail::to('seu@email.com')->send(new NovoChamadoMail($chamado));

        return redirect()->route('chamado.sucesso', $chamado->id)
            ->with('success', 'Chamado criado com sucesso!');
    }

    // Pagina de sucesso
    public function sucesso(Request $request, $id)
    {
        $chamado = $this->findOwnedChamado($request, (int) $id);
        return view('publico.sucesso', compact('chamado'));
    }

    // Consultar status do chamado
    public function consultar(Request $request)
    {
        $ids = $this->getSessionChamadoIds($request);

        $chamados = empty($ids)
            ? collect()
            : Chamado::query()
                ->select(['id', 'titulo', 'status', 'created_at'])
                ->with('avaliacao:id,chamado_id,nota')
                ->whereIn('id', $ids)
                ->orderByDesc('created_at')
                ->get();

        return view('publico.consultar', compact('chamados'));
    }

    public function status(Request $request, $id)
    {
        $chamado = $this->findOwnedChamado($request, (int) $id);
        return view('publico.status', compact('chamado'));
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);

        $ids = $this->getSessionChamadoIds($request);
        $id = (int) $request->id;

        if (!in_array($id, $ids, true)) {
            return back()->with('error', 'Este chamado nao pertence a esta sessao.');
        }

        return redirect()->route('chamado.status', $id);
    }

    private function getSessionChamadoIds(Request $request): array
    {
        $ids = $request->session()->get(self::SESSION_CHAMADOS_KEY, []);

        return collect($ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function addChamadoToSession(Request $request, int $chamadoId): void
    {
        $ids = $this->getSessionChamadoIds($request);

        if (!in_array($chamadoId, $ids, true)) {
            $ids[] = $chamadoId;
        }

        $request->session()->put(self::SESSION_CHAMADOS_KEY, $ids);
    }

    private function findOwnedChamado(Request $request, int $id): Chamado
    {
        $ids = $this->getSessionChamadoIds($request);

        abort_unless(in_array($id, $ids, true), 403, 'Este chamado nao pertence a esta sessao.');

        return Chamado::query()
            ->with([
                'historico:id,chamado_id,status_anterior,status_novo,observacao,created_at',
                'avaliacao:id,chamado_id,nota,comentario,created_at',
            ])
            ->findOrFail($id);
    }
}
