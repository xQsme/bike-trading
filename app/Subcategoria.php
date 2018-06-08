<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subcategoria extends Model
{
    use Notifiable;

    public function categ()
    {
        return $this->belongsTo(Categoria::class, 'categoria', 'id');
    }
}
