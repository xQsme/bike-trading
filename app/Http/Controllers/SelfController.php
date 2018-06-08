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
use App\Http\Requests\UpdateAnuncioRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SelfController extends BaseController
{
    public function editar(Item $item)
    {
          $results = Categoria::all();

          foreach($results as $result){
            $categories[] = array("id" => $result->id, "val" => $result->nome);
          }

          $results = Subcategoria::all();

          foreach($results as $result){
            $subcats[$result->categoria][] = array("id" => $result->id, "val" => $result->nome);
          }

          $results = Grupo::all();

          foreach($results as $result){
            $grupos[$result->categoria][$result->subcategoria][] = array("id" => $result->id, "val" => $result->nome);
          }

          $results = Tipo::all();
          foreach($results as $result){
            $types[$result->group->categoria][$result->group->subcategoria][$result->grupo][] = array("id" => $result->id, "val" => $result->nome);
          }

          $results = Relacao::where('item', $item->id)->get();
          $relacoes = null;
          foreach($results as $result){
            $relacoes[] = array("id" => $result->id, "val" => $result->tipo);
          }

          $jsonCats = json_encode($categories);
          $jsonSubCats = json_encode($subcats);
          $jsonGrupos = json_encode($grupos);
          $jsonTypes = json_encode($types);
          $jsonRel = json_encode($relacoes);
        return view('editar', compact('jsonCats', 'jsonSubCats', 'jsonGrupos', 'jsonTypes', 'jsonRel', 'item'));
    }

    public function storeEdit(UpdateAnuncioRequest $req, Item $item)
    {
        if ($req->hasFile('fotos')) {
          $x=0;
          $order;
          $check=0;
          foreach($req->all() as $r){
              switch($r){
                  case "z":
                  $order[$x++]=0;
                  $check=1;
                  break;
                  case "y":
                  $order[$x++]=1;
                  $check=1;
                  break;
                  case "x":
                  $order[$x++]=2;
                  $check=1;
                  break;
                  case "w":
                  $order[$x++]=3;
                  $check=1;
                  break;
              }
          }
          if($check == 0)
            return back();

          $fotos=$req->file('fotos');
          list($width, $height) = getimagesize($fotos[$order[0]]);
          if ($width > $height) {
              $item->horizontal=1;
          } else {
              $item->horizontal=0;
          }
        }
        $item->updated=1;
        $item->fill($req->all());
        $item->save();
        $relacoes = Relacao::where('item', $item->id)->get();
        foreach($relacoes as $relacao){
            $relacao->delete();
        }
        for($i=0; $i<$req->quantidade; ++$i){
            if($req->$i != null){
                $relacao = new Relacao();
                $relacao->tipo = $req->$i;
                $relacao->item = $item->id;
                $relacao->timestamps = false;
                $relacao->save();
            }
        }
        if ($req->hasFile('fotos')) {
            $fotos = Foto::where('item', $item->id)->get();
            foreach($fotos as $foto){
                Storage::delete('public/fotos/' . $foto->foto);
                $foto->delete();
            }
            $fotos=$req->file('fotos');
            $newHeight=720;
            foreach($order as $or){
              $i=$fotos[$or];
              list($width, $height) = getimagesize($i);
              $ratio=$width/$height;
              $newWidth=$ratio*$newHeight;
              $image = Image::make($i->getRealPath());
              $image->resize($newWidth, $newHeight);
              $foto = new Foto();
              $foto->timestamps = false;
              $name = str_random(50);
              $extension = explode(".", $i->getClientOriginalName());
              $image->save('storage/fotos/' .$name ."." .end($extension));
              $foto->foto = $name ."." .end($extension);
              $foto->item = $item->id;
              $foto->save();
            }
        }
        $user = Auth::user();
        $items = Item::where('dono', $user->id)->orderby('updated_at', 'desc')->get();
        $comments = Comentario::where('user', $user->id)->get();
        return view('perfil', compact('user', 'items', 'comments'));
    }

    public function apagar(Item $item)
    {
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
        $user=Auth::user();
        $user->total--;
        $user->save();

        return back();
    }

      public function apagarmensagem(Mensagem $mensagem)
    {
        $mensagem->delete();
        return back();
    }

    public function bump(Item $item){
      if(!$item->bump())
        return back();
      $item->updated_at = Carbon::now();
      $item->save();
      return back();
    }

}
