
<nav class="navbar navbar-expand-lg navbar-light bg-faded header-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" data-toggle="collapse" class="navbar-toggle d-lg-none float-left"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><span class="d-lg-none navbar-right navbar-collapse-toggle"><a aria-controls="navbarSupportedContent" href="javascript:;" class="open-navbar-container black"><i class="ft-more-vertical"></i></a></span>
            <form role="search" class="navbar-form navbar-right mt-1">
                <div class="position-relative has-icon-right"></div>
            </form>
        </div>
        <div class="navbar-container">
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    @if(Auth::user()->type == 'A')
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Super-Admin' }}</i></b></li>
                    @elseif(Auth::user()->type == 'M')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Manager ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'R')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Responsable Opérations ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'S')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Agent Superviseur ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'E')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Chef d\'Equipe ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'P')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Agent de Paie ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'C')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Responsable Commercial ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'F')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Agent de Facturation ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @elseif(Auth::user()->type == 'T')
                        @php
                            $entite = DB::table('entites')
                                ->where('entites.EnNumero',Auth::user()->entite)
                                ->first();
                        @endphp
                        <li class="nav-item mr-2 d-none d-lg-block"><b><i>{{ Auth::user()->noms.' '.Auth::user()->prenoms.', Agent Contrôleur ' }} <span style="text-transform:lowercase;">{{ $entite->EnNom }}</span></i></b></li>
                    @else

                    @endif
                    <li class="nav-item mr-2 d-none d-lg-block"><a id="navbar-fullscreen" href="javascript:;" class="nav-link apptogglefullscreen"><i class="ft-maximize font-medium-3 blue-grey darken-4"></i>
                        <p class="d-none">Plein écran</p></a>
                    </li>

                    <li class="dropdown nav-item"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-user font-medium-3 blue-grey darken-4"></i>
                        <p class="d-none">Paramétrage utilisateur</p></a>
                        <div ngbdropdownmenu="" aria-labelledby="dropdownBasic3" class="dropdown-menu text-left dropdown-menu-right">
                            <a href="#" class="dropdown-item py-1"><i class="ft-user-minus mr-2"></i><span>Mon profil</span></a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item"><i class="ft-power mr-2"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"></i>
                                <span>Se deconnecter</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>

