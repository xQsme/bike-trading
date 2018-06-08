<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Distrito extends Model
{
    use Notifiable;

    protected $fillable = [
        'nome', 'categoria', 'subcategoria', 'preco', 'descricao'
    ];
}
