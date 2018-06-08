<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Comentario extends Model
{
    use Notifiable;

    protected $fillable = [
    ];

    public function owner()
    {
        return $this->belongsTo('App\User', 'dono', 'id');
    }

}
