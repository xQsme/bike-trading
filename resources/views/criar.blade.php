@extends('master')  
@push('master_header')
<script src="{{asset('/js/Sortable.min.js')}}"></script>
<script type='text/javascript'>
    var categories = {!! $jsonCats !!};
	var subcats = {!! $jsonSubCats !!};
	var grupos = {!! $jsonGrupos !!};
	var types = {!! $jsonTypes !!};
  	function loadCategories(){
        var select = document.getElementById("categoriesSelect");
        select.onchange = updateSubCats;
        for(var i = 0; i < categories.length; i++){
          select.options[i] = new Option(categories[i].val,categories[i].id);          
        }
  	}
  	function updateSubCats(){
        var catSelect = document.getElementById("categoriesSelect");
        var catid = catSelect.value;
        var subcatSelect = document.getElementById("subcatsSelect");
        subcatSelect.onchange = updateTypes;
        subcatSelect.options.length = 0;
        for(var i = 0; i < subcats[catid].length; i++){
          subcatSelect.options[i] = new Option(subcats[catid][i].val,subcats[catid][i].id);
	  	}
	  	updateTypes();
  	}
  	function updateTypes(){
        var catSelect = document.getElementById("categoriesSelect");
        var catid = catSelect.value;
        var subcatSelect = document.getElementById("subcatsSelect");
        var subcatid = subcatSelect.value;
        var parent = document.getElementById("tipos");
        parent.innerHTML = "";
        if(catid == 1 && subcatid == 12){
        	document.getElementById("inputPreco").required=false;
        	document.getElementById("inputPreco").value='';
        	document.getElementById("form-preco").style.display='none';
        	return;
        }else{
        	document.getElementById("inputPreco").required=true;
        	document.getElementById("form-preco").style.display='block';
        }
        var k = 0;
        for(var j = 0; j < grupos[catid][subcatid].length; j++){
        	var grupoid = grupos[catid][subcatid][j].id;
        	var grupo = document.createElement('div');
        	grupo.className = "col-xs-12 grupo";
        	parent.appendChild(grupo);
			var label = document.createElement('label');
			label.appendChild(document.createTextNode(grupos[catid][subcatid][j].val));
			label.className = "col-md-4 control-label"
	     	grupo.appendChild(label);
	        for(var i = 0; i < types[catid][subcatid][grupoid].length; i++){
	        	k++;
	         	var container = document.createElement('div');
	         	if(i==0)
	         		container.className = "col-md-6";
	         	else
	         		container.className = "col-md-6 col-md-offset-4";
	         	grupo.appendChild(container);

	          	var checkbox = document.createElement('input');
	  			checkbox.type = "checkbox";
				checkbox.name = k;
				checkbox.value = types[catid][subcatid][grupoid][i].id;
				checkbox.id = types[catid][subcatid][grupoid][i].id;
				checkbox.required = false;

				var label = document.createElement('label');
				label.htmlFor = types[catid][subcatid][grupoid][i].id;
				label.appendChild(document.createTextNode(types[catid][subcatid][grupoid][i].val));

				container.appendChild(checkbox);
				container.appendChild(label);
		  	}
	  	}
	  	var hidden = document.createElement('input');
	  	hidden.type = "hidden";
	  	hidden.name = "quantidade";
	  	hidden.value = k;
	  	parent.appendChild(hidden);
  	}
</script>
@endpush
@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Criar Anuncio</div>
	                <div class="panel-body">
	    				<form action="{{route('store')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                        	{{csrf_field()}}
							<div class="form-group">
							    <label for="inputNome" class="col-md-4 control-label">Nome</label>
							    <div class="col-md-6">
								    <div class="input-group">
								    	<input pattern=".{2,45}" type="text" class="form-control" name="nome" id="inputNome"
								           @if(old('nome') != null)
								           value="{{old('nome')}}"
								           @endif
								           required title="nome entre 2 e 45 caracteres" autofocus>
					            	</div>
							    </div>
							</div>
                        	<div class="form-group">
	                        	<label for="categoriesSelect" class="col-md-4 control-label">Categoria</label>
	                        	<div class="col-md-6">
								    <select name="categoria" id='categoriesSelect' class="form-control"></select>
								</div>
							</div>
							<div class="form-group">
								<label for="subcatsSelect" class="col-md-4 control-label">Subcategoria</label>
								<div class=col-md-6>
								    <select name="subcategoria" id='subcatsSelect' class="form-control"></select>
							    </div>
						    </div>
						    <div class="form-group" id="tipos">
						    </div>
							<div class="form-group" id="form-preco">
							    <label for="inputPreco" class="col-md-4 control-label">Preço</label>
							    <div class="col-md-6">
								    <div class="input-group">
								    	<input type="number" max="20000" min="1" class="form-control" name="preco" id="inputPreco"
								           @if(old('preco') != null)
								           	value="{{old('preco')}}"
								           @endif
								           required>
								           <span class="input-group-addon">€</span>
					            	</div>
							    </div>
							</div>
							<div class="form-group">
	                            <label for="fotos" class="col-md-4 control-label">Fotografias</label>
	                            <div class="col-md-6">
	                                <input id="fotos" type="file" name="fotos[]" multiple="multiple" accept="image/*" required/>
	                                <output id="list"></output>
        							<script>
									  function handleFileSelect(evt) {
									  	document.getElementById('list').innerHTML = "";
									    var files = evt.target.files; // FileList object
									    if(files.length > 4){
									    	document.getElementById('fotos').value = "";
									    	document.getElementById('list').innerHTML = "							            <strong>Escolha no maximo 4 ficheiros</strong>";
									    	return;
									    }
									    for (var i = 0, f; f = files[i]; i++){
									    	if(f.size > 4000000){
										    	document.getElementById('fotos').value = "";
	                                            document.getElementById('list').innerHTML = "                                       <strong>Ficheiro com um maximo de 4MB</strong>";
	                                            return;
                                        	}
									    }
									    for (var i = 0, f; f = files[i]; i++) {

									      // Only process image files.
									      if (!f.type.match('image.*')) {
									        continue;
									      }
									      var reader = new FileReader();
									      var x = 0;
									      var letters = "zyxw".split('');
									      // Closure to capture the file information.
									      reader.onload = (function(theFile) {
									        return function(e) {
									          // Render thumbnail.
									          var span = document.createElement('span');
									          span.innerHTML = ['<input name="' + letters[x] + '"type="text" value="'+ letters[x] + '" style="display:none;"><img class="thumb" src="', e.target.result,
									                            '" title="', escape(theFile.name), '"/>'].join('');
									          document.getElementById('list').insertBefore(span, null);
									          	x++;
									        };
									      })(f);
									      // Read in the image file as a data URL.
									      reader.readAsDataURL(f);
									    }
									    Sortable.create(document.getElementById('list'));
									  }
									  document.getElementById('fotos').addEventListener('change', handleFileSelect, false);
									</script>
                                </div>
                            </div>
							<div class="form-group">
							    <label for="inputDescricao" class="col-md-4 control-label">Descrição</label>
							    <div class="col-md-6">
								    @if(old('descricao') != null)
								        @php($temp=old('descricao'))
								    @else
								        @php($temp="")
								    @endif
									<textarea minlength="2" maxlength="1400" rows="10" class="form-control" name="descricao" id="inputDescricao"
									          required>{{$temp}}</textarea>
								</div>
							</div>
                            <div class="form-group">
	                            <div class="col-md-8 col-md-offset-4">
	                                <button type="submit" class="btn btn-primary side-offset" name="ok">Adicionar</button>
	                                <a class="btn btn-default" href="{{route('home')}}">Cancelar</a>
	                            </div>
                            </div>
                        </form>	
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type='text/javascript'>
    	window.onload = loadCategories(),updateSubCats(),updateTypes();
	</script>
@endsection