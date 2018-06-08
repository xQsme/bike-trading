<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="bike bikes bicicleta bicicletas usado usados usadas usada market portugal material ciclismo suspensão suspensões travão travões acessorios transmissão quadro quadros" />
    <title> {{ config('app.name') }} @stack('page_name') </title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">

    <script src="{{asset('/js/jquery.min.js')}}"></script>
    @yield('styles')
    @stack('master_header')
</head>
<body>
<div id="app">
<nav class="navbar navbar-default navbar-static-top nav-upper">
    <div class="container">
        <div class="navbar-header">
            <li style="float:left;"><a href="{{route('home')}}">{{ config('app.name') }}</a></li>
            <li style="float:left;">
                <form action="{{route('pesquisar')}}" method="post" class="form-group" id="form-nav">
                {{ csrf_field() }}
                    <div class="input-group" id="navSearch">
                        <input type="text" class="form-control" id="search" name="search">
                        <a class="input-group-addon" onclick="$(this).closest('form').submit()"><img src="{{asset('/search.png')}}" style="width:20px;"></a>
                    </div>
                </form>
            </li>
            <li class="new-li nav-mobile">{{$data['users']}} <img src="{{asset('/user.png')}}" style="width: 30px;">&nbsp;&nbsp;{{$data['anuncios']}} <img src="{{asset('/ad.png')}}" style="width: 26px;">&nbsp;&nbsp;</li>
            <div id="nothing"></div>
        @if(!Auth::check())
            <li class="nav-mobile login"><a href="{{route('login')}}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        @else
            @can('admin')
                <li class="nav-mobile"><a href="{{route('pendentes')}}">Anuncios Pendentes</a></li>
                <li class="nav-mobile"><a href="{{route('rejeitados')}}">Anuncios Rejeitados</a></li>
            @endif
            <li class="nav-mobile message"><a href="{{route('mensagens')}}"><img 
            @if(Auth::user()->mensagem)
                src="{{asset('/mail2.jpg')}}"
            @else
                src="{{asset('/mail.jpg')}}"
            @endif
            style="width: 30px;"></a>
         </li>
            <li class="nav-mobile"><a href="{{route('criar')}}">Criar Anuncio</a></li>
            <li class="nav-mobile"><a href="{{route('perfil', Auth::user())}}">Perfil</a></li>
            <li class="nav-mobile"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        @endif
        @if(!Auth::check())
            <li class="nav-mobile"><a href="{{route('register')}}"><span class="glyphicon glyphicon-user"></span> Registar</a></li>
        @endif
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#app-navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        </div>
    </div>
</nav>
<nav class="navbar navbar-default navbar-static-top nav-lower">
    <div class="container nav2">
        <div class="navbar-header2">
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-nav">
                @php($first=1)
                @php($categoria=0)
                @php($subcategorias = $data['subcategorias'])
                @foreach($subcategorias as $subcategoria)
                    @if($categoria != $subcategoria->categoria)
                        @php($categoria=$subcategoria->categoria)
                        @php($done=0)
                        @if($first)
                            @php($first=0)
                        @else
                            </ul></li>
                        @endif
                    @endif
                    @if($done==0)
                        @php($done=1)
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="">{{$subcategoria->categ->nome}}
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                    @endif
                    <li><a href="{{route('lista', [$subcategoria->categ->id, $subcategoria->id])}}">{{$subcategoria->nome}}</a></li>
                @endforeach
                </ul></li>
            </ul>
        </div>
    </div>
</nav>
</div>
@if(session('success'))
    @include('shared.success')
@endif
@yield('content')
<footer>
    Bike Market 2017 - <a href="{{route('sobre')}}">Sobre nós</a> / <a href="{{route('condicoes')}}">Condições de serviço</a> / Contacte-nos <a href="mailto:suporte@bike-market.pt">suporte@bike-market.pt</a>
</footer>
</body>
</html>
