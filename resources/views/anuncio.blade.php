@extends('master')
@push('master_header')
<script src="{{asset('/js/jquery.fancybox.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('/css/jquery.fancybox.min.css')}}">
<script>
!function(a){a.fn.extend({simpleGal:function(b){var c={mainImage:".placeholder"};return b=a.extend(c,b),this.each(function(){var c=a(this).find("a"),d=a(this).siblings().find(b.mainImage);c.on("click",function(b){b.preventDefault();var c=a(this).attr("href");d.attr("src",c)})})}})}(jQuery);
  jQuery(document).ready(function () {
    jQuery('.thumbnails').simpleGal({
      mainImage: '.custom'
    });
  });
  !function(a){a.fn.extend({simple:function(b){var c={mainImage:".placeholder"};return b=a.extend(c,b),this.each(function(){var c=a(this).find("a"),d=a(this).siblings().find(b.mainImage);c.on("click",function(b){b.preventDefault();var c=a(this).attr("href");d.attr("href",c)})})}})}(jQuery);
  jQuery(document).ready(function () {
    jQuery('.thumbnails').simple({
      mainImage: '.main'
    });
  });
  </script>
@endpush
@section('content')
<div class="container">
	<div class="col-xs-10 col-xs-offset-1 anuncio-container">
		<div class="panel panel-default">
            <div class="panel-heading">{{$item->cat->nome}} - {{$item->subcat->nome}}</div>
            <div class="panel-body">
            	<h3 style="text-align:center; margin-top:5px">{{$item->nome}}</h3>
            	<div class="main-image col-xs-10">
                    <a class="main" href="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}" data-fancybox="1"><img src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}" alt="" class="custom"></a>
                </div>
				<div class="thumbnails col-xs-2">
					@foreach($item->fotos as $foto)
						<li><a href="{{asset('/storage/fotos/'.$foto->foto)}}"><img id="square" src="{{asset('/storage/fotos/'.$foto->foto)}}" alt=""></a></li>
					@endforeach
				</div>  
                <script>
					var checkboxes = document.querySelectorAll('#square');
					for (var i = 0, len = checkboxes.length; i < len; i++) {
						checkboxes[i].style.height = checkboxes[i].width + 'px';
					}
                </script>
				<div class="col-xs-6 user-id" style="text-align:right;">
					<h4><a href="{{route('perfil', $item->user)}}">{{$item->user->name}}</a></h4>
					<h4 @if(!$item->user->phone)style="margin-top:16px"@endif><a href="mailto:{{$item->user->email}}">{{$item->user->email}}</a></h4>
					<h4><a href="tel:{{$item->user->phone}}">{{$item->user->phone}}</a></h4>
				</div>
				<div class="col-xs-6 user-id">
                    @if($item->preco)
					   <h3 @if(!$item->user->phone)style="margin-top:10px"@endif>{{$item->preco}}€</h3>
					@else
                       <br>
                    @endif
                    <h4 @if(!$item->user->phone)style="margin-top:0"@endif>{{$item->user->dist->nome}}</h4>
				</div>
				<div class="col-xs-12" style="border: 1px solid #bdbdbdbd; border-radius:5px; background-color: #f5f5f5; padding-top:5px;">
					<ul>
						@foreach($relacaos as $relacao)
						<li>{{$relacao->tip->group->nome}} - {{$relacao->tip->nome}}</li>
						@endforeach
					</ul>
					<h4 style="white-space: pre-wrap; word-wrap: break-word;">{{$item->descricao}}</h4>
					@if(Auth::check() && Auth::user()->admin && !$item->aprovado)
                        <a class="btn btn-success" href="{{route('aprovar', $item)}}">Aprovar</a>
                		<form action="{{route('rejeitar', $item)}}" method="post" class="form-group" id="razao-form" style="display:none">
            				{{ csrf_field() }}
            				<input id="input-razao" name="razao" type="text">
            			</form>
                    @elseif(Auth::check() && Auth::user()->id == $item->dono && $item->aprovado == -1 && !$item->updated)
                        <h4>Anuncio rejeitado <a class="btn btn-success" href="{{route('editar', $item)}}">Editar</a></h4>
                    @elseif(Auth::check() && Auth::user()->admin && $item->aprovado == -1 && $item->updated)
                        <a class="btn btn-success" href="{{route('aprovar', $item)}}">Aprovar</a>
                    @endif
        			@if(Auth::check() && Auth::user()->admin)
                        <form action="{{route('rejeitar', $item)}}" method="post" class="form-group" id="razao-form" style="display:none">
            				{{ csrf_field() }}
            				<input id="input-razao" name="razao" type="text">
            			</form>
                        <a onclick="myFunction()" class="btn btn-warning">Rejeitar</a>
            			<script>
							function myFunction() {
							    var reason = prompt("Razão");
							    if (reason != null) {		        
									document.getElementById('input-razao').value=reason;
									document.getElementById('razao-form').submit();
							    }
							}
						</script>
                        <a class="btn btn-danger" href="{{route('remover', $item)}}">Remover</a>
                    @endif
				</div>
            </div>
        </div>	
	</div>
</div>
@endsection
