<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserved_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapa_item_id');
            $table->string('nome'); // Adicionando o campo nome
            $table->foreign('chapa_item_id')->references('id')->on('chapa_items')->onDelete('cascade');
            $table->timestamps();
            // Adicione quaisquer outros campos necessários aqui
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserved_items');
    }
}
