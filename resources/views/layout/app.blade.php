<!DOCTYPE html>
<html lang="fr" class="loading">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="C'est un projet né de la décision de l'administration des impôts de taxes de 15% leurs activités génératrices de revenus, tel les crédits ou location de chaise, salle.">
    <meta name="keywords" content="facturation, articles, stocks, inventaire, web app">
    <meta name="author" content="Saint VIDILA">
    <title>Spak Management | @yield('title')</title>

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    @include('layout.partials._css')
    @yield('add_css')
    <livewire:styles/>
  </head>

  <body data-col="2-columns" class=" 2-columns ">
    <div class="wrapper nav-collapsed menu-collapsed">
        <div data-active-color="white" data-background-color="purple-bliss" data-image="{{ url('assets/img/sidebar-bg/01.jpg') }}" class="app-sidebar">

            @if(Auth::user()->type == 'A')
                <div class="sidebar-header">
                    <div class="logo clearfix"><a href="" class="logo-text float-left">
                    <div class="logo-img"><img height="50px" width="40px" src="{{ url('assets/img/logo-diff-itc.png') }}"/></div><span class="text align-middle uppercase-first-letter"><b>{{ 'DIFF-ITC' }}</b></span></a><a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block"><i data-toggle="collapsed" class="toggle-icon ft-toggle-left"></i></a><a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none"><i class="ft-x"></i></a></div>
                </div>
            @else
                @php
                    $entite = DB::table('entites')
                        ->where('entites.EnNumero','=',Auth::user()->entite)
                        ->first();
                @endphp

                @if($entite->EnChemLogo == NULL)
                    <div class="sidebar-header">
                        <div class="logo clearfix"><a href="" class="logo-text float-left">
                        <div class="logo-img"><img height="50px" width="40px" src="{{ url('assets/img/default.jpg') }}"/></div><span class="text align-middle" style="text-transform:capitalize"><b>{{ $entite->EnAbrge }}</b></span></a><a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block"><i data-toggle="collapsed" class="toggle-icon ft-toggle-left"></i></a><a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none"><i class="ft-x"></i></a></div>
                    </div>
                @else
                    <div class="sidebar-header">
                        <div class="logo clearfix"><a href="" class="logo-text float-left">
                        <div class="logo-img"><img height="50px" width="40px" src="{{ url('assets/img/entites/'.$entite->EnChemLogo) }}"/></div><span class="text align-middle" style="text-transform:capitalize"><b>{{ $entite->EnAbrge }}</b></span></a><a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block"><i data-toggle="collapsed" class="toggle-icon ft-toggle-left"></i></a><a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none"><i class="ft-x"></i></a></div>
                    </div>
                @endif
            @endif


        <div class="sidebar-content">
            @include('layout.partials._navbar')
        </div>
        <div class="sidebar-background"></div>

        </div>

        @include('layout.partials._header')

        <div class="main-panel">
            <div class="main-content">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
            @include('layout.partials._footer')
        </div>
    </div>

    @include('layout.partials._js')
    @yield('js')
    @yield('add_js')
	<livewire:scripts />
	@stack('add_js_livewire')
  </body>
</html>
