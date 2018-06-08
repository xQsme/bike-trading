@extends('master')
@section('content')
<img src="/minus.png" style="display:none">
	<div class="form-container col-xs-2" style="padding:0;">
		<ul>
			<div class="col-xs-14">
				<a class="open" onclick="myFunction()" style="margin-top: 10px;">Distrito <img id="Distimg" class="plus" src="/plus.png"></a>
				<form id="Dist" style="display:none">
					@foreach($distritos as $dist)
					    <label class="offset">
							<input type="checkbox" name="dist" value="{{$dist->id}}"/> {{$dist->nome}}</label>
						<br>
					@endforeach
					<script>
						function myFunction() {
						    var x = document.getElementById('Dist');
						    var plus = document.getElementById('Distimg');
						    if (x.style.display === 'none') {
						        x.style.display = 'block';
						        plus.src='/minus.png';
						    } else {
						        x.style.display = 'none';
						        plus.src='/plus.png';
						    }
						} 
					</script>
				</form>
			</div>
			<div id="grupos">
				@foreach($grupos as $grupo)
					<div class="col-xs-14">
					<a class="open" onclick="myFunction{{$grupo->id}}()" style="margin-top: 10px;">{{$grupo->nome}} <img id="{{$grupo->nome}}img" class="plus" src="/plus.png"></a>
						<form id="{{$grupo->id}}" style="display:none">
						@foreach($tipos as $tipo)
							@if($grupo->id == $tipo->categoria)
							    <label class="offset">
				      				<input type="checkbox" name="{{$grupo->id}}" value="{{$tipo->id}}"/> {{$tipo->nome}}</label>
				      			<br>
							@endif
						@endforeach
						</form>
						<script>
							function myFunction{{$grupo->id}}() {
							    var x = document.getElementById('{{$grupo->id}}');
							    var plus = document.getElementById('{{$grupo->nome}}img');
							    if (x.style.display === 'none') {
							        x.style.display = 'block';
							        plus.src='/minus.png';
							    } else {
							        x.style.display = 'none';
							        plus.src='/plus.png';
							    }
							} 
						</script>
					</div>
				@endforeach
			</div>
		</ul>
	</div>
	<div class="col-xs-8 lista">
		<div class="panel panel-default">
            <div class="panel-heading">Pesquisa por: {{$pesquisa}}</div>
            <div class="panel-body">
            	<ul class="nav nav-tabs">
				    <li onclick="showGrupos()" class="active"><a data-toggle="tab" href="#home">Anuncios</a></li>
				    <li><a onclick="hideGrupos()" data-toggle="tab" href="#menu1">Users</a></li>
				    <script>
				    	var checkboxes = document.querySelectorAll ('input[type=checkbox]');
				    	function hideGrupos(){
				    		for (var i = 0; i < checkboxes.length; i++){
				    			if(checkboxes[i].checked){
				    				checkboxes[i].checked=false;
				    			}
				    		}
				    		$(":checkbox:first").change();
				    		document.getElementById("grupos").style.display='none';
				    		document.getElementById("form-order").style.display='none';
				    	}
				    	function showGrupos(){
				    		document.getElementById("form-order").style.display='block';
				    		document.getElementById("grupos").style.display='block';
				    	}
				    </script>
				    <form id="form-order" class="form-group" action="{{route('pesquisar')}}" method="post">
				    	{{ csrf_field() }}
						<select name="order" id="select-cearch" class="form-control" onchange="this.form.submit()">
							<option value="1">Mais Recentes</option>
							<option value="2" @if($ordem == 2)selected @endif>Preço Crescente</option>
							<option value="3" @if($ordem == 3)selected @endif>Preço Decrescente</option>
						</select>
						<input type="text" value="{{$pesquisa}}" name="search" style="display:none;">
					</form>
				</ul>
				<div class="tab-content">
				    <div id="home" class="tab-pane fade in active">
				    	<br>
					    @php($count=0)
		            	@foreach($items as $item)
		            		@if($item->aprovado==1)
		            			@php($count++)
				            	<div class="anuncio col-xs-12" data-category="{{$item->subcategoria}} {{$item->user->dist->id}}">
				            	    <div class="col-xs-3 foto-anuncio" style="text-align:center;">
					            	    <a href="{{route('anuncio', $item)}}">
					            			<img class="foto" src="{{asset('/storage/fotos/'.$item->fotos[0]->foto)}}">
				            			</a>
				            		</div>
				            		<div class="col-xs-6 item-info">
					            		<h4>{{$item->nome}}</h4>
					            		@if($item->preco)
					            			<h2>{{$item->preco}}€</h2>
					            		@endif
					            		<h4>{{$item->user->dist->nome}}</h4>
				            		</div>
				            		<ul class="col-xs-3 item-rel" style="margin-top:10px;">
				            			@php($relacaos=$item->relacaos)
										@foreach($relacaos as $relacao)
											<li>{{$relacao->tip->group->nome}} - {{$relacao->tip->nome}}</li>
										@endforeach
									</ul>
					        	</div>
			        		@endif
		            	@endforeach
		            	@if(!$count)
		            	<div style="text-align:center;">
		            		<p style="font-size:16px;">Não foram encontrados resultados.</p>
		            	</div>
		            	@endif
					</div>
				    <div id="menu1" class="tab-pane fade">
				    	<br>
				    	@php($count=0)
				    	@foreach($users as $user)
				    		@if($user->verified==1)
			            		@php($count++)
				            	<div class="anuncio col-xs-12" data-category=" {{$user->distrito}}">
				            	    <div class="col-xs-3 user-foto" style="text-align:center;">
					            	    <a href="{{route('perfil', $user)}}">
					            			@if(is_null($user->foto))
										        <img class="foto" src="/profile.jpg" alt="Image failed to load">
										    @else
										        <img class="foto" src="{{asset('/storage/fotos/'.$user->foto)}}" alt="">
										    @endif
				            			</a>
				            		</div>
				            		<div class="col-xs-9 item-rel">
					            		<h3>{{$user->name}}</h3>
					            		<h3>{{$user->dist->nome}}</h3>
					            		<h4>{{$user->memberFor()}}</h4>
				            		</div>
				        		</div>
				        	@endif
		            	@endforeach
		            	@if(!$count)
		            	<div style="text-align:center;">
		            		<p style="font-size:16px;">Não foram encontrados resultados.</p>
		            		</div>
		            	@endif
  					</div>
				</div>
            </div>
        </div>	
	</div>
	<script>
		var $filterCheckboxes = $('input[type="checkbox"]');

		$filterCheckboxes.on('change', function() {

		  var selectedFilters = {};

		  $filterCheckboxes.filter(':checked').each(function() {
		  	var $filteredResults = $('.anuncio');

		    if (!selectedFilters.hasOwnProperty(this.name)) {
		      selectedFilters[this.name] = [];
		    }

		    selectedFilters[this.name].push(this.value);

		  });

		  // create a collection containing all of the filterable elements
		  var $filteredResults = $('.anuncio');

		  // loop over the selected filter name -> (array) values pairs
		  $.each(selectedFilters, function(name, filterValues) {

		    // filter each .flower element
		    $filteredResults = $filteredResults.filter(function() {

		      var matched = false,
		        currentFilterValues = $(this).data('category').split(' ');

		      // loop over each category value in the current .flower's data-category
		      $.each(currentFilterValues, function(_, currentFilterValue) {

		        // if the current category exists in the selected filters array
		        // set matched to true, and stop looping. as we're ORing in each
		        // set of filters, we only need to match once

		        if ($.inArray(currentFilterValue, filterValues) != -1) {
		          matched = true;
		          return false;
		        }
		      });

		      // if matched is true the current .flower element is returned
		      return matched;

		    });
		  });

		  $('.anuncio').hide().filter($filteredResults).show();

		});
	</script>
@endsection
