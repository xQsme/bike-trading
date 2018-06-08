@extends('master')
@section('content')
	<div class="col-xs-8 col-xs-offset-2 mensagens">
		<div class="panel panel-default">
            <div class="panel-heading">Mensagens</div>
            <div class="panel-body">
            	@foreach($items as $item)
		            	<div class="anuncio col-xs-12">
		            	<a href="{{route('apagarmensagem', $item)}}"><img src="{{asset('/cross.png')}}" style="width:20px;float:right;"></a>
			            	<h5>{{$item->created_at}}</h5>
			            	<h4 style="word-wrap: break-word;">{!!$item->mensagem!!}</h4>
		        		</div>
            	@endforeach
            </div>
        </div>	
	</div>
@endsection
