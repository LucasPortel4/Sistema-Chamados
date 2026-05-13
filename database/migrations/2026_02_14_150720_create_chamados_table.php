<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente');
            $table->string('ramal', 5);
            $table->string('area_usuario')->nullable();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('status')->default('aguardando_analise');
            $table->string('anexo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};



