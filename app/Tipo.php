<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Tipo extends Model
{
    use Notifiable;

    public function group()
    {
        return $this->belongsTo('App\Grupo', 'grupo', 'id');
    }
}
