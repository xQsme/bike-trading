@extends('master')

@section('content')
	<div class="container" style="text-align:center">
		@php($count=0)
		@foreach($items as $item)
		@php($count++)
		@if($count==1)
			<div class="row">
		@endif
			<div class="col-xs-6">
		       	<div class="home">
		    	    <a href="{{route('anuncio', $item)}}">
		    			<img class="home-foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
					</a>
					<p id="home-titulo">{{$item->nome}}</p>
					<p id="home-categoria">{{$item->cat->nome}} - {{$item->subcat->nome}}</p>
					@if($item->preco)
						@php($num=5-strlen($item->preco))
						@php($string='')
						@for($i=0; $i<$num*2.4; ++$i)
							@php($string.='&nbsp;')
						@endfor
						@if($num == 1 || $num == 2)
							@php($string.='&nbsp;')
						@endif
						@php($string.=$item->preco)
						<p id="home-preco">{{$string}}€</p>
					@endif
				</div>
			</div>
		@if($count==2)
			</div>
			@php($count=0)
		@endif
		@endforeach
		@if($count==1)
			</div>
		@endif
	</div>
@endsection