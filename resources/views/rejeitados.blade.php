@extends('master')
@section('content')
	<div class="col-xs-8 col-xs-offset-2 perfil-div">
		<div class="panel panel-default">
            <div class="panel-heading">Anuncios Rejeitados</div>
            <div class="panel-body">
            	@foreach($expired as $item)
	            	<div class="anuncio col-xs-12" style="background-color:rgba(250,128,114,0.5);">
	            	    <div class="col-xs-4 perfil-anuncio">
		            	    <a href="{{route('anuncio', $item)}}">
		            			<img class="foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
	            			</a>
	            		</div>
	            		<div class="col-xs-8 perfil-ad">
		            		<h4>{{$item->nome}}</h4>
		            		<h3>{{$item->preco}}€</h3>
		            		<h4>{{$item->user->dist->nome}}</h4>
	            			<a class="btn btn-danger" href="{{route('remover', $item)}}">Remover</a>
	            		</div>
	        		</div>
            	@endforeach
            	@foreach($updated as $item)
	            	<div class="anuncio col-xs-12" style="background-color:rgba(144,238,144,0.5);">
	            	    <div class="col-xs-4 perfil-anuncio">
		            	    <a href="{{route('anuncio', $item)}}">
		            			<img class="foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
	            			</a>
	            		</div>
	            		<div class="col-xs-8 perfil-ad">
		            		<h4>{{$item->nome}}</h4>
		            		<h3>{{$item->preco}}€</h3>
		            		<h4>{{$item->user->dist->nome}}</h4>
	            			<a class="btn btn-success" href="{{route('aprovar', $item)}}">Aprovar</a>
	            			<a class="btn btn-danger" href="{{route('remover', $item)}}">Remover</a>
	            		</div>
	        		</div>
            	@endforeach
            	@foreach($others as $item)
	            	<div class="anuncio col-xs-12"">
	            	    <div class="col-xs-4 perfil-anuncio">
		            	    <a href="{{route('anuncio', $item)}}">
		            			<img class="foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
	            			</a>
	            		</div>
	            		<div class="col-xs-8 perfil-ad">
		            		<h4>{{$item->nome}}</h4>
		            		@if($item->preco)
		            			<h3>{{$item->preco}}€</h3>
		            		@endif
		            		<h4>{{$item->user->dist->nome}}</h4>
		            		<h4>Anuncio em tempo de revisão</h4>
	            		</div>
	        		</div>
            	@endforeach
            </div>
        </div>	
	</div>
@endsection