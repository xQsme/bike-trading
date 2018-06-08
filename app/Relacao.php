<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Relacao extends Model
{
    use Notifiable;

    public function tip()
    {
        return $this->belongsTo('App\Tipo', 'tipo', 'id');
    }
}
