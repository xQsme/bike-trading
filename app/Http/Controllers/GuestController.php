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

class GuestController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $items = Item::where('aprovado', 1)->where('horizontal', 1)->orderByRaw('RAND()')->take(4)->get();
        return view('welcome', compact('items'));
    }

    public function lista($categoria, $subcategoria)
    {
        $ordem=1;
        $items = Item::where('categoria', $categoria)->where('subcategoria', $subcategoria)->orderBy('updated_at', 'desc')->get();
        $temp = Grupo::where('categoria', $categoria)->where('subcategoria', $subcategoria);
        $grupos = $temp->get();
        $ids=$temp->pluck('id')->toArray();
        $tipos = Tipo::whereIn('grupo', $ids)->get();
    	$cat=Categoria::where('id', $categoria)->first();
    	$sub=Subcategoria::where('id', $subcategoria)->first();
        $distritos = Distrito::all();
        return view('lista', compact('items', 'ordem', 'grupos', 'tipos', 'cat', 'sub', 'distritos'));
    }

        public function listaOrdenada(Request $request, $categoria, $subcategoria)
    {
        $ordem=$request->order;
        switch($ordem){
            case 1: 
                $order='items.updated_at';
                $asc='desc';
            break;
            case 2: 
                $order='items.preco';
                $asc='asc';
            break;
            case 3: 
                $order='items.preco';
                $asc='desc'; 
        }
        $items = Item::where('categoria', $categoria)->where('subcategoria', $subcategoria)->orderBy($order, $asc)->get();
        $temp = Grupo::where('categoria', $categoria)->where('subcategoria', $subcategoria);
        $grupos = $temp->get();
        $ids=$temp->pluck('id')->toArray();
        $tipos = Tipo::whereIn('grupo', $ids)->get();
        $cat=Categoria::where('id', $categoria)->first();
        $sub=Subcategoria::where('id', $subcategoria)->first();
        $distritos = Distrito::all();
        return view('lista', compact('items', 'ordem', 'grupos', 'tipos', 'cat', 'sub', 'distritos'));
    }

    public function anuncio(Item $item)
    {
        if($item->aprovado != 1 && (!Auth::check() || (Auth::user()->id != $item->dono && Auth::user()->admin != 1)))
            return index();
        $relacaos = Relacao::where('item', $item->id)->get();
        $item->views++;
        $item->save();
        return view('anuncio', compact('item', 'relacaos'));
    }

    public function pesquisar(Request $request){
        if ($request->has('order')){
            $ordem=$request->order;
            switch($request->order){
                case 1: 
                    $order='items.updated_at';
                    $asc='desc';
                break;
                case 2: 
                    $order='items.preco';
                    $asc='asc';
                break;
                case 3: 
                    $order='items.preco';
                    $asc='desc'; 
            }
        }else{
            $ordem=1;
            $order='items.updated_at';
            $asc='desc';
        }
        $pesquisa=$request->search;
        $items = Item::select('items.*')->leftJoin('users', 'users.id', '=', 'items.dono')->leftJoin('distritos', 'distritos.id', '=', 'users.distrito')->where('distritos.nome' , 'LIKE', '%'.$request->search.'%')->orWhere('items.nome', 'LIKE', '%'.$request->search.'%')->orWhere('items.descricao', 'LIKE', '%'.$request->search.'%')->orWhere('users.name', 'LIKE', '%'.$request->search.'%')->orWhere('users.username', 'LIKE', '%'.$request->search.'%')->orderBy($order, $asc)->get();
        $users = User::select('users.*')->leftJoin('distritos', 'distritos.id', '=', 'users.distrito')->where('distritos.nome' , 'LIKE', '%'.$request->search.'%')->orWhere('users.name', 'LIKE', '%'.$request->search.'%')->orWhere('users.username', 'LIKE', '%'.$request->search.'%')->orderBy('users.created_at')->get();
        $grupos=Categoria::all();
        $tipos=Subcategoria::all();
        $distritos = Distrito::all();
        return view('pesquisa', compact('items', 'users', 'ordem', 'grupos', 'tipos', 'distritos', 'pesquisa'));
    }

    public function perfil(User $user)
    {
        $items = Item::where('dono', $user->id)->orderby('updated_at', 'desc')->get();
        $comments = Comentario::where('user', $user->id)->get();
        return view('perfil', compact('user', 'items', 'comments'));
    }

    public function sobre(){
        return view('sobre');
    }

    public function condicoes(){
        return view('condicoes');
    }

}
