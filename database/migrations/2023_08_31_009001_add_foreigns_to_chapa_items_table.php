<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToChapaItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chapa_items', function (Blueprint $table) {
            $table
                ->foreign('chapa_id')
                ->references('id')
                ->on('chapas')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapa_items', function (Blueprint $table) {
            $table->dropForeign(['chapa_id']);
        });
    }
}
