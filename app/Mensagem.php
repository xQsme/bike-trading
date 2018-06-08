<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Mensagem extends Model
{
    use Notifiable;

    protected $fillable = [
        'mensagem'
    ];
    
}
