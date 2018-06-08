@extends('master')
@section('content')
	<div class="col-xs-8 col-xs-offset-2 perfil-div">
		<div class="panel panel-default">
            <div class="panel-heading">Anuncios Pendentes</div>
            <div class="panel-body">
            	@foreach($items as $item)
		            	<div class="anuncio col-xs-12" data-category="@foreach($item->relacaos as $relacao){{$relacao->tipo}} @endforeach {{$item->user->dist->id}}">
		            	    <div class="col-xs-4 perfil-anuncio">
			            	    <a href="{{route('anuncio', $item)}}">
			            			<img class="foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
		            			</a>
		            		</div>
		            		<div class="col-xs-8 perfil-ad">
			            		<h4>{{$item->cat->nome}} - {{$item->subcat->nome}}</h4>
			            		<h4>{{$item->nome}}</h4>
			            		@if($item->preco)
			            			<h3>{{$item->preco}}€</h3>
			            		@endif
			            		<h4>{{$item->user->dist->nome}}</h4>
		            			<a class="btn btn-success" href="{{route('aprovar', $item)}}">Aprovar</a>
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
		            		</div>
		        		</div>
            	@endforeach
            </div>
        </div>	
	</div>
@endsection
