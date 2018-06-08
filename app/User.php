<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'phone', 'email', 'password', 'distrito', 'descricao'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function verified()
    {
        $this->verified = 1;
        $this->email_token = null;
        $this->save();
    }

    public function dist()
    {
        return $this->hasOne('App\Distrito', 'id', 'distrito');
    }

    public function memberFor()
    {
        $diff=$this->created_at->diff(Carbon::now());
        $diffYears=$diff->format('%y');
        $diffMonths=$diff->format('%m');
        $diffDays=$diff->format('%d');
        $string='Membro há ';
        if($diffYears != 0){
            $string=$string.$diffYears.' ano';
            if($diffYears > 1){
                $string=$string.'s';
            }
        }
        if($diffYears != 0 && $diffMonths != 0){
            $string=$string.', ';
        }
        if($diffMonths != 0){
            $string=$string.$diffMonths;
            if($diffMonths == 1){
                $string=$string.' mês';
            }else{
                $string=$string.' meses';
            }
        }
        if(($diffMonths != 0 && $diffDays != 0) || ($diffYears != 0 && $diffDays != 0)){
            $string=$string.', ';
        }
        if($diffDays != 0){
            $string=$string.$diffDays.' dia';
            if($diffDays > 1){
                $string=$string.'s';
            }
        }else{
        	$string="Membro desde hoje";
        }

        return $string;
    }

    public function averageRating()
    {
        $average = Comentario::where('user', $this->id)->where('rating', '!=', 0)->sum('rating');
        $count = Comentario::where('user', $this->id)->where('rating', '!=', 0)->count();
        if ($count == 0) {
            return 0;
        } else {
            return round($average/$count);
        }
    }
}
