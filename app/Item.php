<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Item extends Model
{
    use Notifiable;

    protected $fillable = [
        'nome', 'categoria', 'subcategoria', 'preco', 'descricao'
    ];

    public function fotos()
    {
        return $this->hasMany('App\Foto', 'item', 'id');
    }

    public function relacaos()
    {
        return $this->hasMany('App\Relacao', 'item', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'dono', 'id');
    }

    public function cat()
    {
        return $this->belongsTo('App\Categoria', 'categoria', 'id');
    }

    public function subcat()
    {
        return $this->belongsTo('App\Subcategoria', 'subcategoria', 'id');
    }

    public function expired()
    {
        if($this->updated_until != null && $this->updated_until < Carbon::now() &&  !$this->updated){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function bump()
    {
        return $this->updated_at->addDays(1) < Carbon::now() && $this->aprovado == 1;
    }
}
