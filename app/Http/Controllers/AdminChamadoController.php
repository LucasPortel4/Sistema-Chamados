<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\StatusHistorico;
use Illuminate\Http\Request;

class AdminChamadoController extends Controller
{
    // Listar todos os chamados
    public function index()
    {
        $chamados = Chamado::query()
            ->select(['id', 'nome_cliente', 'ramal', 'titulo', 'descricao', 'status', 'created_at'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.chamados.index', compact('chamados'));
    }

    // Ver detalhes do chamado
    public function show($id)
    {
        $chamado = Chamado::query()
            ->with([
                'historico:id,chamado_id,status_anterior,status_novo,observacao,created_at',
            ])
            ->findOrFail($id);

        return view('admin.chamados.show', compact('chamado'));
    }

    // Atualizar status
    public function atualizarStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:aguardando_analise,em_analise,a_caminho,completo',
            'observacao' => 'nullable|string'
        ]);

        $chamado = Chamado::findOrFail($id);
        $statusAnterior = $chamado->status;

        // Salvar no histórico
        StatusHistorico::create([
            'chamado_id' => $chamado->id,
            'status_anterior' => $statusAnterior,
            'status_novo' => $validated['status'],
            'observacao' => $validated['observacao'] ?? null
        ]);

        // Atualizar chamado
        $chamado->update(['status' => $validated['status']]);
        Chamado::clearDashboardStatsCache();

        // Aqui você pode enviar email/notificação pro cliente
        // Mail::to($chamado->email)->send(new StatusAtualizadoMail($chamado));

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    // Dashboard com estatísticas
    public function dashboard(Request $request)
    {
        $filtroAtual = $request->query('filtro', 'todos');
        if (!in_array($filtroAtual, ['todos', 'prioritarios'], true)) {
            $filtroAtual = 'todos';
        }

        $stats = Chamado::getDashboardStats();

        $chamadosRecentesQuery = Chamado::query();

        if ($filtroAtual === 'prioritarios') {
            $chamadosRecentesQuery->prioritarios();
        }

        $chamadosRecentes = $chamadosRecentesQuery
            ->select(['id', 'nome_cliente', 'titulo', 'descricao', 'status', 'created_at'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chamadosRecentes', 'filtroAtual'));
    }
}
