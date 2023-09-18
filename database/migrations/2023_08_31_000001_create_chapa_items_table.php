<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapaItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chapa_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('largura');
            $table->string('comprimento');
            $table->unsignedBigInteger('chapa_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapa_items');
    }
}
