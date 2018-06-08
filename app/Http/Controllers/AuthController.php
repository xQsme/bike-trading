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
use App\Http\Requests\StoreAnuncioRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mensagens()
    {
        $user=Auth::user();
        if($user->mensagem == 1){
            $user->mensagem=0;
            $user->save();
        }
        $items = Mensagem::where('user', Auth::user()->id)->orderby('created_at', 'desc')->get();
        return view('mensagens', compact('items'));
    }

    public function criar()
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

		  $jsonCats = json_encode($categories);
		  $jsonSubCats = json_encode($subcats);
		  $jsonGrupos = json_encode($grupos);
    	  $jsonTypes = json_encode($types);
        return view('criar', compact('jsonCats', 'jsonSubCats', 'jsonGrupos', 'jsonTypes'));
    }

    public function store(StoreAnuncioRequest $req)
    {
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
        $item = new Item();
        $item->fill($req->all());
        $item->dono = Auth::user()->id;
        $fotos=$req->file('fotos');
        list($width, $height) = getimagesize($fotos[$order[0]]);
        if ($width > $height) {
            $item->horizontal=1;
        } else {
            $item->horizontal=0;
        }
        $item->save();
        for($i=0; $i<$req->quantidade; ++$i){
            if($req->$i != null){
                $relacao = new Relacao();
                $relacao->tipo = $req->$i;
                $relacao->item = $item->id;
                $relacao->timestamps = false;
                $relacao->save();
            }
        }
        $newHeight=720;
        foreach($order as $or){
            $i=$fotos[$or];
            list($width, $height) = getimagesize($i);
            $ratio=$width/$height;
            $newWidth=$ratio*$newHeight;
            if(file_exists($i->getRealPath())){
                $image = Image::make($i->getRealPath());
            }
            else{
                $item->delete();
                return back();
            }
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
        $user = Auth::user();
        $user->total++;
        $user->save();
        return redirect()->route('perfil', Auth::user())->with('success', 'Anuncio criado com sucesso');
    }

    public function editarperfil()
    {
        $user = Auth::user();
        $distritos = Distrito::all();
        return view('editarperfil', compact('user', 'distritos'));
    }

    public function updatePerfil(UpdateProfileRequest $req)
    {
        $user=Auth::user();
        $user->fill($req->all());
        if ($req->hasFile('profile_photo')) {
            if($user->foto != null)
                Storage::delete('public/profiles/' . $user->profile_photo);
                    $newHeight=720;
            $i=$req->file('profile_photo');
            list($width, $height) = getimagesize($i);
            $ratio=$width/$height;
            $newHeight=720;
            $newWidth=$ratio*$newHeight;
            $image = Image::make($i->getRealPath());
            $image->resize($newWidth, $newHeight);
            $name = str_random(50);
            $extension = explode(".", $i->getClientOriginalName());
            $image->save('storage/fotos/' .$name ."." .end($extension));
            $user->foto = $name ."." .end($extension);
        }
        $user->save();
        return redirect()->route('perfil', Auth::user())->with('success', 'Perfil atualizado com sucesso');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = bcrypt($request->newPassword);
            $user->save();
            return redirect()->route('perfil', Auth::user())->with('success', 'Password atualizada com sucesso');
        }
        return redirect()->back()->withErrors(array('oldPassword' => 'Password Errada'));
    }

    public function comment(Request $request, User $user){
        $comment = new Comentario();
        $comment->user = $user->id;
        $comment->comentario = $request->comment;
        $comment->rating = $request->rating;
        $comment->dono = Auth::user()->id;
        $comment->save();

        return back();
    }

    public function responder(Request $request, String $whatever, Comentario $comentario){
        $comentario->resposta = $request->reply;
        $comentario->save();

        return back();
    }

}
