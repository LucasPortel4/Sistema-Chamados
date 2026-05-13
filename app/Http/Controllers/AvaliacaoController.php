<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Chamado;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    private const SESSION_CHAMADOS_KEY = 'meus_chamados_ids';

    public function index()
    {
        $avaliacoes = Avaliacao::query()
            ->with('chamado:id,nome_cliente,titulo,ramal,status')
            ->latest()
            ->paginate(10);

        $media = (float) Avaliacao::avg('nota');
        $total = Avaliacao::count();

        return view('publico.avaliacoes', compact('avaliacoes', 'media', 'total'));
    }

    public function create(Request $request, int $id)
    {
        $chamado = $this->findCompletoOwnedChamado($request, $id);

        if ($chamado->avaliacao) {
            return redirect()
                ->route('chamado.status', $chamado->id)
                ->with('success', 'Este chamado ja foi avaliado. Obrigado pelo feedback!');
        }

        return view('publico.avaliar', compact('chamado'));
    }

    public function store(Request $request, int $id)
    {
        $chamado = $this->findCompletoOwnedChamado($request, $id);

        if ($chamado->avaliacao) {
            return redirect()
                ->route('chamado.status', $chamado->id)
                ->with('success', 'Este chamado ja foi avaliado. Obrigado pelo feedback!');
        }

        $validated = $request->validate([
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:2000',
        ]);

        $chamado->avaliacao()->create($validated);

        return redirect()
            ->route('avaliacao.index')
            ->with('success', 'Avaliacao enviada com sucesso. Obrigado!');
    }

    private function findCompletoOwnedChamado(Request $request, int $id): Chamado
    {
        $ids = collect($request->session()->get(self::SESSION_CHAMADOS_KEY, []))
            ->filter(fn ($sessionId) => is_numeric($sessionId))
            ->map(fn ($sessionId) => (int) $sessionId)
            ->all();

        abort_unless(in_array($id, $ids, true), 403, 'Este chamado nao pertence a esta sessao.');

        $chamado = Chamado::query()
            ->with('avaliacao')
            ->findOrFail($id);

        abort_unless($chamado->status === 'completo', 403, 'Este chamado ainda nao esta completo.');

        return $chamado;
    }
}
