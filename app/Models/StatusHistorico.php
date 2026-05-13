<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistorico extends Model
{
    use HasFactory;

    protected $table = 'status_historico';

    protected $fillable = [
        'chamado_id',
        'status_anterior',
        'status_novo',
        'observacao'
    ];

    // Relacionamento com chamado
    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}