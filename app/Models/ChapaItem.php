<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChapaItem extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['largura', 'comprimento', 'chapa_id', 'quantidade', 'sequencial'];


    protected $searchableFields = ['*'];

    protected $table = 'chapa_items';

    public function chapa()
    {
        return $this->belongsTo(Chapa::class);
    }

        // Definindo o relacionamento inverso com ReservedItem
        public function reservedItem()
        {
            return $this->belongsTo(ReservedItem::class);
        }
}
