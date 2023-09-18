<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapa extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name'];

    protected $searchableFields = ['*'];

    public function chapaItems()
    {
        return $this->hasMany(ChapaItem::class);
    }
}
