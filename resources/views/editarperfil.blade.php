@extends('master')

@push('master_header')
<script>
    function showPassword() {
        $('#password-form').show();
        $('#profile-form').hide();
    }
</script>
<script>
    function hidePassword() {
        $('#password-form').hide();
        $('#profile-form').show();
    }
</script>
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Editar Perfil</div>
                    <div class="panel-body">
                        <form action="{{route('updatePerfil')}}" method="post" class="form-group"
                              enctype="multipart/form-data" id="profile-form">
                            <div class="form-group">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label for="name" class="control-label">Nome</label>
                                    <input type="text" pattern=".{2,22}" class="form-control" name="name" id="name" placeholder="Nome"
                                           value="{{old('nome', $user->name)}}" required title="nome entre 2 e 22 caracteres" autofocus>
                                    @if ($errors->has('nome'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="phone" class="control-label">Telemovel</label>
                                    <input min="100000000" max="999999999" type="number" class="form-control" name="phone" id="phone" placeholder="Telemovel"
                                           value="{{old('phone', $user->phone)}}">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="distrito">Distrito</label>
                                    <select name="distrito" id="distrito" class="form-control">
                                        @foreach($distritos as $distrito)
                                            @if(old('distrito_id') != null)
                                                @if(old('distrito_id') == $distrito->id)
                                                    <option selected="selected"
                                                            value="{{$distrito->id}}">{{$distrito->nome}}</option>
                                                @else
                                                    <option value="{{$distrito->id}}">{{$distrito->nome}}</option>
                                                @endif
                                            @else
                                                @if($user->distrito == $distrito->id)
                                                    <option selected="selected"
                                                            value="{{$distrito->id}}">{{$distrito->nome}}</option>
                                                @else
                                                    <option value="{{$distrito->id}}">{{$distrito->nome}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group{{ $errors->has('profile_photo') ? ' has-error' : '' }}">
                                    <label for="profile_photo" class="control-label">Foto de Perfil</label>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo"
                                           accept="image/*">
                                    <output id="list"></output>
                                    <script>
                                      function handleFileSelect(evt) {
                                        document.getElementById('list').innerHTML = "";
                                        var files = evt.target.files;
                                        if(files[0].size > 4000000){
                                            document.getElementById('profile_photo').value = "";
                                            document.getElementById('list').innerHTML = "                                       <strong>Ficheiro com um maximo de 4MB</strong>";
                                            return;
                                        }
                                        for (var i = 0, f; f = files[i]; i++) {

                                          // Only process image files.
                                          if (!f.type.match('image.*')) {
                                            continue;
                                          }
                                          var reader = new FileReader();
                                          // Closure to capture the file information.
                                          reader.onload = (function(theFile) {
                                            return function(e) {
                                              // Render thumbnail.
                                              var span = document.createElement('span');
                                              span.innerHTML = ['<img class="thumb" src="', e.target.result,
                                                                '" title="', escape(theFile.name), '"/>'].join('');
                                              document.getElementById('list').insertBefore(span, null);
                                            };
                                          })(f);
                                          // Read in the image file as a data URL.
                                          reader.readAsDataURL(f);
                                        }
                                      }
                                      document.getElementById('profile_photo').addEventListener('change', handleFileSelect, false);
                                    </script>
                                    @if ($errors->has('profile_photo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('profile_photo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('descricao') ? ' has-error' : '' }}">
                                    <label for="descricao" class="control-label">Descrição</label>
                                    <textarea maxlength="900" rows="10" id="descricao" class="form-control" placeholder="Descrição"
                                              name="descricao">{{old('descricao', $user->descricao)}}</textarea>
                                    @if ($errors->has('descricao'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descricao') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary side-offset">Guardar</button>
                                <a class="btn btn-default" href="{{route('perfil', 'user')}}">Cancelar</a>
                                <a class="btn btn-warning" href='javascript:showPassword()' style="float:right;">Mudar
                                    Password</a>
                            </div>
                        </form>
                        <form action="{{route('updatePassword')}}" method="post" class="form-group"
                              id="password-form" style="display:none">
                            {{method_field('PUT')}}
                            <div class="form-group">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('oldPassword') ? ' has-error' : '' }}">
                                    <label for="inputPassword" class="control-label">Original Password</label>
                                    <input type="password" class="form-control" name="oldPassword" id="inputPassword"
                                           placeholder="Password" required>
                                    @if ($errors->has('oldPassword'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('oldPassword') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('newPassword') ? ' has-error' : '' }}">
                                    <label for="inputNewPassword" class="control-label">New Password</label>
                                    <input type="password" class="form-control" name="newPassword" id="inputNewPassword"
                                           placeholder="Password" required>
                                    @if ($errors->has('newPassword'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('newPassword') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('newPasswordConfirmation') ? ' has-error' : '' }}">
                                    <label for="inputPasswordConfirmation" class="control-label">New Password
                                        Confirmation</label>
                                    <input type="password" class="form-control" name="newPasswordConfirmation"
                                           id="inputPasswordConfirmation" placeholder="Password Confirmation" required>
                                    @if ($errors->has('newPasswordConfirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('newPasswordConfirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary side-offset">Save Password</button>
                                <a class="btn btn-default" href='javascript:hidePassword()'>Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(count($errors))
        @if($errors->has('oldPassword') || $errors->has('newPassword') || $errors->has('newPasswordConfirmation'))
            <script type='text/javascript'>window.onload = showPassword();</script>
        @endif
    @endif
@endsection
