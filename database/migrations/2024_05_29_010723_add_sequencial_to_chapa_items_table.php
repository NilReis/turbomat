<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSequencialToChapaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapa_items', function (Blueprint $table) {
            $table->integer('sequencial')->after('chapa_id'); // Adicionando o campo sequencial
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapa_items', function (Blueprint $table) {
            $table->dropColumn('sequencial');
        });
    }
}
