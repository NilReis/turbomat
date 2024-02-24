<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveChapaItemIdFromReservedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserved_items', function (Blueprint $table) {
            // Remova esta linha se não houver chave estrangeira para 'chapa_item_id'
            // $table->dropForeign(['chapa_item_id']);
    
            $table->dropColumn('chapa_item_id');
        });
    }
    
    
    
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserved_items', function (Blueprint $table) {
            //
        });
    }
}
