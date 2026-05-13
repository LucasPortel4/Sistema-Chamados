<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->index(['created_at', 'id'], 'chamados_created_id_idx');
            $table->index('status', 'chamados_status_idx');
        });

        Schema::table('status_historico', function (Blueprint $table) {
            $table->index(['chamado_id', 'created_at', 'id'], 'status_hist_chamado_created_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('status_historico', function (Blueprint $table) {
            $table->dropIndex('status_hist_chamado_created_id_idx');
        });

        Schema::table('chamados', function (Blueprint $table) {
            $table->dropIndex('chamados_created_id_idx');
            $table->dropIndex('chamados_status_idx');
        });
    }
};
