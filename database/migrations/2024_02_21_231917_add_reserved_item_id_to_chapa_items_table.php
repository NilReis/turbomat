<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservedItemIdToChapaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapa_items', function (Blueprint $table) {
            $table->unsignedBigInteger('reserved_item_id')->nullable();
            $table->foreign('reserved_item_id')->references('id')->on('reserved_items')->onDelete('set null');
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
            $table->unsignedBigInteger('reserved_item_id')->nullable();
            $table->foreign('reserved_item_id')->references('id')->on('reserved_items')->onDelete('set null');
        });
    }
}
