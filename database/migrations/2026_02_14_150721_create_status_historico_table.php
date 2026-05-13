<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamado_id')
                ->constrained('chamados')
                ->onDelete('cascade');
            $table->string('status_anterior');
            $table->string('status_novo');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_historico');
    }
};
