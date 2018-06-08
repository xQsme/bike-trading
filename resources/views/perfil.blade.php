@extends('master')
@section('content')
	<div class="col-xs-8 col-xs-offset-2 perfil-div">
		<div class="panel panel-default">
            <div class="panel-heading">Perfil de {{$user->name}}</div>
            <div class="panel-body">
	            <div class="perfil">
		            <div class="col-xs-4 div-foto-perfil" style="margin-bottom: 15px;">
					    @if(is_null($user->foto))
					        <img class="foto-perfil" src="/profile.jpg" alt="Image failed to load">
					    @else
					        <img class="foto-perfil" src="{{asset('/storage/fotos/'.$user->foto)}}" alt="">
					    @endif
			    	</div>
			    	<div class="col-xs-8 perfil-info" style="padding-left:0">
			    		@if(Auth::check() && Auth::user()->id == $user->id)
							<a class="btn btn-warning" href="{{route('editarPerfil')}}" style="float:right;">Editar Perfil</a>
						@endif
						<ul>
							<li>Username: {{$user->username}}</li>
							<li>{{$user->memberFor()}}</li>
							<li>Total de anuncios: {{$user->total}}</li>
							@if($user->averageRating() != 0)
								<li>Satisfação Média:
							        @for($i=0; $i < $user->averageRating(); $i++)
							            <img src="/star.png" style="width:24px;height:24px;">
							        @endfor
							    </li>
						    @endif
							<li>E-mail: <a href="mailto:{{$user->email}}">{{$user->email}}</a></li>
							@if(!is_null($user->distrito))
							    <li>Distrito: {{$user->dist->nome}}</li>
							@endif
							@if(!is_null($user->phone))
							    <li>Telemovel: {{$user->phone}}</li>
							@endif
							@if($user->descricao != null)
							<br>
							    <p style="white-space: pre-wrap; word-wrap: break-word;">{{$user->descricao}}<p>
							@endif
						</ul>

					</div>
	            </div>
	            <div class="col-xs-12">
		           	<ul class="nav nav-tabs">
					    <li @if(!Auth::check() || (Auth::check() && Auth::user()->id != $user->id))
				    		class="active"
				    	@endif><a data-toggle="tab" href="#home">Feedback</a></li>
					    <li @if(Auth::check() && Auth::user()->id == $user->id)
				    		class="active"
				    	@endif><a data-toggle="tab" href="#menu1">Anuncios</a></li>
					</ul>
					<div class="tab-content">
					    <div id="home" class="tab-pane fade in 
					    @if(!Auth::check() || (Auth::check() && Auth::user()->id != $user->id))
					    	active
					    @endif">
					    @php($count=0)
                    @foreach($comments as $comment)
                        @php(++$count)
                        <div class="comment">
                            <a href="{{ route('perfil', $comment->user_id) }}">
                                @if(!$comment->owner->foto)
                                    <img class="comment-picture" src="/profile.jpg" alt="">
                                @else
                                    <img class="comment-picture" src="{{asset('/storage/fotos/'.$comment->owner->foto)}}" alt="">
                                @endif
                            </a>
                            <p class="first"><a href="{{route('perfil', $comment->owner)}}">{{$comment->owner->name}}</a> @for($i=0; $i<$comment->rating; $i++) <img src="/star.png" style="width:24px;height:24px;"> @endfor &nbsp&nbsp&nbsp&nbsp{{$comment->created_at}}</p>
                            <p style="white-space: pre-wrap; word-wrap: break-word;">{{$comment->comentario}}</p>
                            @can('admin') 
                                <a class="btn btn-xs btn-danger button-block" href="{{ route('removerComentario', $comment) }}">Remover</a>
                            @endcan
                            @if(Auth::check() && Auth::user()->id == $user->id)
                            	@if($comment->resposta == null)
                                	<a class="btn btn-xs btn-primary button-reply" href='javascript:showReply({{$count}})' id="buttonReply{{$count}}">Responder</a>
                                @endif
                                <form action="{{route('responder', ['user' => $user, 'comentario' => $comment])}}" method="post" class="form-group" id="reply-form{{$count}}" style="display:none;">
                                    {{ csrf_field() }}
                                    <div class="form-group" id="reply" style="margin-left: 10px; margin-right: 10px;">
                                        <label for="inputReply">Resposta</label>
                                        <textarea maxlength="450" class="form-control" name="reply" id="inputReply" required></textarea>
                                    </div>
                                    <div class="form-group" id="submitReply">
                                        <button type="submit" class="btn btn-xs btn-primary side-offset">Responder</button>
                                        <a class="btn btn-xs btn-default" href='javascript:hideReply({{$count}})' id="cancelReply{{$count}}">Cancelar</a>
                                    </div>
                                </form>
                            @endif
                            @if($comment->resposta != null)
                                <div class="reply">
                                        @if(is_null($user->foto))
                                            <img class="comment-picture" src="/profile.jpg" alt="Image failed to load">
                                        @else
                                            <img class="comment-picture" src="{{asset('/storage/fotos/'.$user->foto)}}" alt="Image failed to load">
                                        @endif
                                    </a>
                                    <p class="first">{{$user->name}}</a> &nbsp&nbsp&nbsp&nbsp{{$comment->updated_at}}</p>
                                    <p style="white-space: pre-wrap; word-wrap: break-word;"style="white-space: pre-wrap; word-wrap: break-word;">{{$comment->resposta}}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <br>
                    @if($count)
                        <br>
                    @endif
                    @if(Auth::check() && $user->id != Auth::user()->id)
	                    <a class="btn btn-primary" href='javascript:showComment()' id="buttonComment">Comentar</a>
	                    <form action="{{route('comment', $user)}}" method="post" class="form-group" id="comment-form" style="display:none">
	                        {{ csrf_field() }}
	                        <div class="form-group" id="comment">
	                            <label for="inputComment">Comentario <span id="star" class="c-rating"></span></label>
	                            <textarea maxlength="450" class="form-control" name="comment" id="inputComment" required></textarea>
	                            <input type="number" style="display:none" name="rating" id="inputRating" value="0">
	                        </div>
	                        <script src="/js/rating.js"></script>
	                        <script>
	                            var el = document.querySelector('#star');
	                            var input = document.querySelector('#inputRating');
	                            var currentRating = 0;
	                            var maxRating = 5;
	                            var callback = function (rating) {
	                                input.value=rating;
	                            };
	                            var myRating = rating(el, currentRating, maxRating, callback);
	                        </script>
	                        <div class="form-group" id="submitComment">
	                            <button type="submit" class="btn btn-primary side-offset">Comentar</button>
	                            <a class="btn btn-default" href='javascript:hideComment()' id="cancelComment">Cancelar</a>
	                        </div>
	                    </form>
                    @endif
					    </div>
					    <div id="menu1" class="tab-pane fade in 
					    @if(Auth::check() && Auth::user()->id == $user->id)
					    	active
					    @endif">
					    <br>
					    	@foreach($items as $item)
					        	@if($item->aprovado == 1 || (!$item->expired() && Auth::check() && Auth::user()->id == $user->id))
					            	<div class="anuncio col-xs-12"
					            	@if($item->aprovado == -1 && $item->updated == 0)
					            		style="background-color:rgba(250,128,114,0.5);"
					            	@endif>
					            	    <div class="col-xs-4 perfil-anuncio" style="text-align:center;">
						            	    <a href="{{route('anuncio', $item)}}">
						            			<img class="foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
					            			</a>
					            		</div>
					            		<div class="col-xs-4 perfil-ad">
						            		<h4>{{$item->nome}} 
						            			@if(Auth::check() && (Auth::user()->id == $item->dono || Auth::user()->admin))
						            				({{$item->views}} visualizaç@if($item->views == 1)ão)@elseões)@endif
						            			@endif
						            		</h4>
						            		@if($item->preco)
						            			<h2>{{$item->preco}}€</h2>
						            		@endif
						            		<h4>{{$item->user->dist->nome}}</h4>
						            		@if($item->aprovado == 0 || ($item->aprovado == -1 && $item->updated))
						            			<h4>A aguardar aprovação</h4>
						            		@endif
						            		@if($item->aprovado == -1 && !$item->updated)
						            			<h4>Anuncio rejeitado</h4>
						            		@endif
					            		</div>
					            		<ul class="col-xs-3 rel" style="margin-top:10px;">
					            			@php($relacaos=$item->relacaos)
											@foreach($relacaos as $relacao)
												<li>{{$relacao->tip->group->nome}} - {{$relacao->tip->nome}}</li>
											@endforeach
										</ul>
										@if(Auth::check() && Auth::user()->id == $user->id)
					            				<a class="btn btn-danger btn-apagar btn-perfil apagar" onclick="return confirm('Tem a certeza que quer apagar o anuncio?')" href="{{route('apagar', $item)}}">Apagar</a>
					            				<a class="btn btn-warning btn-edit btn-perfil editar" href="{{route('editar', $item)}}">Editar</a>
					            				@if($item->bump())
					            					<a class="btn btn-success btn-bump btn-perfil bump" href="{{route('bump', $item)}}">Bump</a>
					            				@endif
					            			@endif
					        		</div>
					        	@endif
					    	@endforeach
	  					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>function showComment(){
        $('#comment-form').show();
        $('#buttonComment').hide();
        $('#buttonRefuse').hide();
        $('#buttonComplete').hide();
    }
	</script>
	<script>function hideComment(){
	        $('#comment-form').hide();
	        $('#buttonComment').show();
	        $('#buttonRefuse').show();
	        $('#buttonComplete').show();
	    }
	</script>
	<script>function showReply(count){
	        $('#reply-form' + count).show();
	        $('#buttonReply' + count).hide();
	    }
	</script>
	<script>function hideReply(count){
	        $('#reply-form' + count).hide();
	        $('#buttonReply' + count).show();
	    }
	</script>
@endsection
