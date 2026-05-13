<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class Chamado extends Model
{
    use HasFactory;

    public const DASHBOARD_STATS_CACHE_KEY = 'dashboard:stats';
    public const DASHBOARD_STATS_CACHE_TTL_SECONDS = 30;

    public const PALAVRAS_PRIORITARIAS = [
        'internet',
        'rede',
        'impressora',
        'servidor',
        'sistema',
        'email',
        'vpn',
        'wifi',
        'wi-fi',
        'switch',
        'roteador',
        'router',
        'firewall',
        'outlook',
        'erp',
        'travando',
        'sem acesso',
        'sem conexao',
        'lento',
        'urgente',
        'critico',
    ];

    protected $fillable = [
        'nome_cliente',
        'ramal',
        'area_usuario',
        'titulo',
        'descricao',
        'status',
        'anexo'
    ];

    // Relacionamento com historico
    public function historico()
    {
        return $this->hasMany(StatusHistorico::class)
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class);
    }

    public function scopePrioritarios(Builder $query): Builder
    {
        return $query->where(function ($query) {
            foreach (self::PALAVRAS_PRIORITARIAS as $palavra) {
                $like = '%' . mb_strtolower($palavra, 'UTF-8') . '%';
                $query->orWhereRaw('LOWER(titulo) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(descricao) LIKE ?', [$like]);
            }
        });
    }

    public static function textoEhPrioritario(?string $texto): bool
    {
        if ($texto === null || trim($texto) === '') {
            return false;
        }

        $textoNormalizado = mb_strtolower($texto, 'UTF-8');

        foreach (self::PALAVRAS_PRIORITARIAS as $palavra) {
            if (mb_strpos($textoNormalizado, mb_strtolower($palavra, 'UTF-8')) !== false) {
                return true;
            }
        }

        return false;
    }

    public function isPrioritario(): bool
    {
        return self::textoEhPrioritario($this->titulo . ' ' . $this->descricao);
    }

    public static function clearDashboardStatsCache(): void
    {
        Cache::forget(self::DASHBOARD_STATS_CACHE_KEY);
    }

    public static function getDashboardStats(): array
    {
        return Cache::remember(
            self::DASHBOARD_STATS_CACHE_KEY,
            now()->addSeconds(self::DASHBOARD_STATS_CACHE_TTL_SECONDS),
            static function (): array {
                $stats = static::query()
                    ->selectRaw('
                        COUNT(*) as total,
                        SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as aguardando,
                        SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as em_analise,
                        SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as a_caminho,
                        SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completos
                    ', [
                        'aguardando_analise',
                        'em_analise',
                        'a_caminho',
                        'completo',
                    ])
                    ->first();

                return [
                    'total' => (int) ($stats->total ?? 0),
                    'aguardando' => (int) ($stats->aguardando ?? 0),
                    'em_analise' => (int) ($stats->em_analise ?? 0),
                    'a_caminho' => (int) ($stats->a_caminho ?? 0),
                    'completos' => (int) ($stats->completos ?? 0),
                ];
            }
        );
    }

    // Labels para os status
    public static function getStatusLabel($status)
    {
        return match($status) {
            'aguardando_analise' => 'Aguardando Análise',
            'em_analise' => 'Em Analise',
            'a_caminho' => 'A Caminho do Chamado',
            'completo' => 'Chamado Completo',
            default => $status
        };
    }
}
