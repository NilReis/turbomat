<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservedItem extends Model
{
    use HasFactory;

    protected $table = 'reserved_items';
    protected $fillable = ['nome', 'chapa_item_id']; // Adicione todos os campos que você deseja permitir a atribuição em massa


    // Definindo o relacionamento com ChapaItem
    public function chapaItems()
    {
        return $this->hasMany(ChapaItem::class, 'reserved_item_id', 'id');
    }
}
