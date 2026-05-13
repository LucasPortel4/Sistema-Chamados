<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'chamado_id',
        'nota',
        'comentario',
    ];

    protected $casts = [
        'nota' => 'integer',
    ];

    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}
