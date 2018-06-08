<?php

namespace App\Http\Controllers;

use App\User;
use App\Mensagem;
use App\Categoria;
use App\Subcategoria;
use App\Item;
use App\Grupo;
use App\Tipo;
use App\Relacao;
use App\Foto;
use App\Distrito;
use App\Comentario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('can:admin');
    }

        public function aprovar(Item $item)
    {
        $item->aprovado=1;
        $item->save();
        $user=User::where('id', $item->dono)->first();
        $user->mensagem=1;
        $user->save();
        $mensagem = new Mensagem();
        $mensagem->user = $item->dono;
        $mensagem->mensagem='Anuncio <a href="'.route('anuncio', $item).'">'.$item->nome.'</a> aprovado.';
        $mensagem->timestamps = false;
        $mensagem->save();
        return back();
    }

    public function rejeitar(Request $request, Item $item)
    {
        $user=User::where('id', $item->dono)->first();
        $user->mensagem=1;
        $user->save();
        $item->aprovado=-1;
        $item->updated=0;
        $item->updated_until=Carbon::now()->addDays(7);
        $item->save();
        $mensagem = new Mensagem();
        $mensagem->user = $item->dono;
        $mensagem->mensagem='Anuncio <a href="'.route('anuncio', $item).'">'.$item->nome.'</a> rejeitado por "'.$request->razao .'", tem uma semana para alterar o seu anuncio, caso contrario este será eliminado.';
        $mensagem->timestamps = false;
        $mensagem->save();
        return back();
    }

    public function remover(Item $item)
    {
        $user=User::where('id', $item->dono)->first();
        $user->total--;
        $user->save();
        $relacoes = Relacao::where('item', $item->id)->get();
        foreach($relacoes as $relacao){
            $relacao->delete();
        }
        $fotos = Foto::where('item', $item->id)->get();
        foreach($fotos as $foto){
            Storage::delete('public/fotos/' . $foto->foto);
            $foto->delete();
        }
        $item->delete();

        return back();
    }

        public function pendentes()
    {
        $items=Item::where('aprovado', 0)->get();
        return view('pendentes', compact('items'));
    }

        public function rejeitados()
    {
        $expired=Item::where('aprovado', -1)->where('updated', 0)->where('updated_until', '<', Carbon::now())->get();
        $updated=Item::where('aprovado', -1)->where('updated', 1)->get();
        $others=Item::where('aprovado', -1)->where('updated', 0)->where('updated_until', '>=', Carbon::now())->get();
        return view('rejeitados', compact('expired', 'updated', 'others'));
    }

    public function removerComentario(Comentario $comentario){
        $comentario->delete();
        return back();
    }

}
