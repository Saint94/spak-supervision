@if (Auth::user()->type == 'A')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" style="top:200px;height:300px" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Entités-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-folder"></i><span data-i18n="" class="menu-title">Entités</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('entites.create') }}"><a href="{{ route('entites.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('entites.index') }}"><a href="{{ route('entites.index') }}" class="menu-item">Liste</a></li>
                </ul>
            </li>
            <!--Entités-->

            <!--Users-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Utilisateurs</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('users.create') }}"><a href="{{ route('users.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('users.index') }}"><a href="{{ route('users.index') }}" class="menu-item">Liste</a></li>
                </ul>
            </li>
            <!--Users-->

        </ul>
    </div>
@elseif (Auth::user()->type == 'M')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Paramètres-->
            <li class="has-sub nav-item"><a href="#"><i class="ft-settings"></i><span data-i18n="" class="menu-title">Paramètres</span></a>
                <ul class="menu-content">

                    <li class="nav-item {{ set_active_route('secteurs-activite.index') }}"><a href="{{ route('secteurs-activite.index') }}">
                        <span data-i18n="" class="menu-title">Secteurs d'activité</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('villes.index') }}"><a href="{{ route('villes.index') }}">
                        <span data-i18n="" class="menu-title">Villes</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('zones.index') }}"><a href="{{ route('zones.index') }}">
                        <span data-i18n="" class="menu-title">Zones</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('quartiers.index') }}"><a href="{{ route('quartiers.index') }}">
                        <span data-i18n="" class="menu-title">Quartiers</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('fonctions.index') }}"><a href="{{ route('fonctions.index') }}">
                        <span data-i18n="" class="menu-title">Fonctions</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('types-sanction.index') }}"><a href="{{ route('types-sanction.index') }}">
                        <span data-i18n="" class="menu-title">Sanctions et Obs.</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('mois-paie.index') }}"><a href="{{ route('mois-paie.index') }}">
                        <span data-i18n="" class="menu-title">Mois de paie</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('modes-paiement.index') }}"><a href="{{ route('modes-paiement.index') }}">
                        <span data-i18n="" class="menu-title">Modes de paiement</span></a>
                    </li>
                </ul>
            </li>
            <!--Paramètres-->

            <!--Clients-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Clients</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('clients.create') }}"><a href="{{ route('clients.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('clients.index') }}"><a href="{{ route('clients.index') }}" class="menu-item">Liste</a></li>
                    <li class="{{ set_active_route('contacts-client.index') }}"><a href="{{ route('contacts-client.index') }}" class="menu-item">Contacts client</a></li>
                    <li class="{{ set_active_route('sites-client.index') }}"><a href="{{ route('sites-client.index') }}" class="menu-item">Sites client</a></li>
                    <li class="{{ set_active_route('avenants-contrat.create') }} {{ set_active_route('avenants-contrat.index') }}"><a href="{{ route('avenants-contrat.index') }}" class="menu-item">Avenants contrat</a></li>
                    <li class="{{ set_active_route('factures-clients.index') }}"><a href="{{ route('factures-clients.index') }}" class="menu-item">Factures clients</a></li>
                    <li class="{{ set_active_route('reglements-clients.index') }}"><a href="{{ route('reglements-clients.index') }}" class="menu-item">Règlements clients</a></li>
                    <li class="{{ set_active_route('suivi-clients.create') }} {{ set_active_route('suivi-clients.index') }}"><a href="{{ route('suivi-clients.index') }}" class="menu-item">Suivi Clientèle</a></li>
                    <li class="{{ set_active_route('messages-generique.index') }}"><a href="{{ route('messages-generique.index') }}" class="menu-item">Messages générés</a></li>
                    <li class="{{ set_active_route('enquetes-clients.index') }}"><a href="{{ route('enquetes-clients.index') }}" class="menu-item">Enquêtes clients</a></li>
                </ul>
            </li>
            <!--Clients-->

            <!--Personnels-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-user"></i><span data-i18n="" class="menu-title">Personnel</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('personnels.create') }}"><a href="{{ route('personnels.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('personnels.index') }}"><a href="{{ route('personnels.index') }}" class="menu-item">Liste</a></li>
                    <li class="{{ set_active_route('carrieres.index') }}"><a href="{{ route('carrieres.index') }}" class="menu-item">Carrières</a></li>
                    <li class="{{ set_active_route('personnels.controleurs') }}"><a href="{{ route('personnels.controleurs') }}" class="menu-item">Contrôleurs</a></li>
                    <li class="{{ set_active_route('personnels.superviseur') }}"><a href="{{ route('personnels.superviseur') }}" class="menu-item">Superviseurs</a></li>
                    <li class="{{ set_active_route('personnels.commando') }}"><a href="{{ route('personnels.commando') }}" class="menu-item">Commandos</a></li>
                    <li class="{{ set_active_route('personnels.chef_equipe') }}"><a href="{{ route('personnels.chef_equipe') }}" class="menu-item">Chefs d'Equipe</a></li>
                    <li class="{{ set_active_route('personnels.maitre_chien') }}"><a href="{{ route('personnels.maitre_chien') }}" class="menu-item">Maitres chien</a></li>
                    <li class="{{ set_active_route('personnels.agent_fixe') }}"><a href="{{ route('personnels.agent_fixe') }}" class="menu-item">Agents Fixe</a></li>
                    <li class="{{ set_active_route('personnels.rondier') }}"><a href="{{ route('personnels.rondier') }}" class="menu-item">Rondiers</a></li>
                    <li class="{{ set_active_route('personnels.stand_by') }}"><a href="{{ route('personnels.stand_by') }}" class="menu-item">Stand Bye</a></li>
                    <li class="{{ set_active_route('personnels.postulants') }}"><a href="{{ route('personnels.postulant') }}" class="menu-item">Postulants</a></li>
                </ul>
            </li>
            <!--Personnel-->

            <!--Logistique-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-shopping-cart"></i><span data-i18n="" class="menu-title">Logistique</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('articles.index') }}"><a href="{{ route('articles.index') }}" class="menu-item">Articles</a></li>
                    <li class="{{ set_active_route('fournisseurs.index') }}"><a href="{{ route('fournisseurs.index') }}" class="menu-item">Fournisseurs</a></li>
                    <li class="{{ set_active_route('documents-stock.index') }}"><a href="{{ route('documents-stock.index') }}" class="menu-item">Documents stock</a></li>
                </ul>
            </li>
            <!--Logistique-->

            <!--Opérations-->
            <li class="has-sub nav-item"><a href="#"><i class="ft-edit"></i><span data-i18n="" class="menu-title">Opérations</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ set_active_route('poster-vigiles.index') }}"><a href="{{ route('poster-vigiles.index') }}">
                        <span data-i18n="" class="menu-title">Postings des vigiles</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('sites-superviseur.index') }}"><a href="{{ route('sites-superviseur.index') }}">
                        <span data-i18n="" class="menu-title">Sites supervisés</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('supervision-sites.index') }}"><a href="{{ route('supervision-sites.index') }}">
                        <span data-i18n="" class="menu-title">Ronde des contrôleurs</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('ronde-operations.index') }}"><a href="{{ route('ronde-operations.index') }}">
                        <span data-i18n="" class="menu-title">Rondes de opérations</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('pointages-sanctions.index') }}"><a href="{{ route('pointages-sanctions.index') }}">
                        <span data-i18n="" class="menu-title">Sanctions et Obs.</span></a>
                    </li>
                </ul>
            </li>
            <!--Opérations-->

            <!--Users-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Utilisateurs</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('users.create') }}"><a href="{{ route('users.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('users.index') }}"><a href="{{ route('users.index') }}" class="menu-item">Liste</a></li>
                </ul>
            </li>
            <!--Users-->


            <!--Actions utilisateurs-->
            {{--<li class="nav-item {{ set_active_route('supervision-sites.index') }}"><a href="{{ route('supervision-sites.index') }}">
                <i class="ft-download"></i><span data-i18n="" class="menu-title">Actions Utilisateurs</span></a>
            </li>--}}
            <!--Actions utilisateurs-->

        </ul>
    </div>
@elseif (Auth::user()->type == 'R')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Paramètres-->
            <li class="has-sub nav-item"><a href="#"><i class="ft-settings"></i><span data-i18n="" class="menu-title">Paramètres</span></a>
                <ul class="menu-content">

                    <li class="nav-item {{ set_active_route('secteurs-activite.index') }}"><a href="{{ route('secteurs-activite.index') }}">
                        <span data-i18n="" class="menu-title">Secteurs d'activité</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('villes.index') }}"><a href="{{ route('villes.index') }}">
                        <span data-i18n="" class="menu-title">Villes</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('zones.index') }}"><a href="{{ route('zones.index') }}">
                        <span data-i18n="" class="menu-title">Zones</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('quartiers.index') }}"><a href="{{ route('quartiers.index') }}">
                        <span data-i18n="" class="menu-title">Quartiers</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('fonctions.index') }}"><a href="{{ route('fonctions.index') }}">
                        <span data-i18n="" class="menu-title">Fonctions</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('types-sanction.index') }}"><a href="{{ route('types-sanction.index') }}">
                        <span data-i18n="" class="menu-title">Sanctions et Obs.</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('mois-paie.index') }}"><a href="{{ route('mois-paie.index') }}">
                        <span data-i18n="" class="menu-title">Mois de paie</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('modes-paiement.index') }}"><a href="{{ route('modes-paiement.index') }}">
                        <span data-i18n="" class="menu-title">Modes de paiement</span></a>
                    </li>
                </ul>
            </li>
            <!--Paramètres-->

            <!--Personnels-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-user"></i><span data-i18n="" class="menu-title">Personnel</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('personnels.create') }}"><a href="{{ route('personnels.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('personnels.index') }}"><a href="{{ route('personnels.index') }}" class="menu-item">Liste</a></li>
                    <li class="{{ set_active_route('carrieres.index') }}"><a href="{{ route('carrieres.index') }}" class="menu-item">Carrières</a></li>
                    <li class="{{ set_active_route('personnels.controleurs') }}"><a href="{{ route('personnels.controleurs') }}" class="menu-item">Contrôleurs</a></li>
                    <li class="{{ set_active_route('personnels.superviseur') }}"><a href="{{ route('personnels.superviseur') }}" class="menu-item">Superviseurs</a></li>
                    <li class="{{ set_active_route('personnels.commando') }}"><a href="{{ route('personnels.commando') }}" class="menu-item">Commandos</a></li>
                    <li class="{{ set_active_route('personnels.chef_equipe') }}"><a href="{{ route('personnels.chef_equipe') }}" class="menu-item">Chefs d'Equipe</a></li>
                    <li class="{{ set_active_route('personnels.maitre_chien') }}"><a href="{{ route('personnels.maitre_chien') }}" class="menu-item">Maitres chien</a></li>
                    <li class="{{ set_active_route('personnels.agent_fixe') }}"><a href="{{ route('personnels.agent_fixe') }}" class="menu-item">Agents Fixe</a></li>
                    <li class="{{ set_active_route('personnels.rondier') }}"><a href="{{ route('personnels.rondier') }}" class="menu-item">Rondiers</a></li>
                    <li class="{{ set_active_route('personnels.stand_by') }}"><a href="{{ route('personnels.stand_by') }}" class="menu-item">Stand Bye</a></li>
                    <li class="{{ set_active_route('personnels.postulants') }}"><a href="{{ route('personnels.postulant') }}" class="menu-item">Postulants</a></li>
                </ul>
            </li>
            <!--Personnel-->

            <!--Opérations-->
            <li class="has-sub nav-item"><a href="#"><i class="ft-edit"></i><span data-i18n="" class="menu-title">Opérations</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ set_active_route('poster-vigiles.index') }}"><a href="{{ route('poster-vigiles.index') }}">
                        <span data-i18n="" class="menu-title">Postings des vigiles</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('sites-superviseur.index') }}"><a href="{{ route('sites-superviseur.index') }}">
                        <span data-i18n="" class="menu-title">Sites supervisés</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('pointages-sanctions.index') }}"><a href="{{ route('pointages-sanctions.index') }}">
                        <span data-i18n="" class="menu-title">Sanctions et Obs.</span></a>
                    </li>
                </ul>
            </li>
            <!--Opérations-->

        </ul>
    </div>
@elseif (Auth::user()->type == 'S')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Rondes de supervision des sites-->
            <li class="nav-item {{ set_active_route('supervision-sites.index') }}"><a href="{{ route('supervision-sites.index') }}">
                <i class="ft-download"></i><span data-i18n="" class="menu-title">Rondes de supervision</span></a>
            </li>
            <!--Rondes de supervision des sites-->

        </ul>
    </div>
@elseif (Auth::user()->type == 'E')

@elseif (Auth::user()->type == 'P')

@elseif (Auth::user()->type == 'C')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Clients-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Clients</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('clients.create') }}"><a href="{{ route('clients.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('clients.index') }}"><a href="{{ route('clients.index') }}" class="menu-item">Liste</a></li>
                </ul>
            </li>
            <!--Clients-->

            <!--Contacts clients-->
            <li class="nav-item {{ set_active_route('contacts-client.index') }}"><a href="{{ route('contacts-client.index') }}">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Contacts client</span></a>
            </li>
            <!--Contacts clients-->

             <!--Sites clients-->
            <li class="nav-item {{ set_active_route('sites-client.index') }}"><a href="{{ route('sites-client.index') }}">
                <i class="ft-map-pin"></i><span data-i18n="" class="menu-title">Sites client</span></a>
            </li>
            <!--Sites clients-->

            <!--Avenants contrat-->
            <li class="nav-item {{ set_active_route('avenants-contrat.index') }}"><a href="{{ route('avenants-contrat.index') }}">
                <i class="ft-file-text"></i><span data-i18n="" class="menu-title">Avenants contrat</span></a>
            </li>
            <!--Avenants contrat-->

             <!--Suivi Clientèle-->
            <li class="nav-item {{ set_active_route('suivi-clients.index') }}"><a href="{{ route('suivi-clients.index') }}">
                <i class="ft-user-check"></i><span data-i18n="" class="menu-title">Suivi Clientèle</span></a>
            </li>
             <!--Suivi Clientèle-->

            <!--Messages générés-->
            <li class="nav-item {{ set_active_route('messages-generique.index') }}"><a href="{{ route('messages-generique.index') }}">
                <i class="ft-mail"></i><span data-i18n="" class="menu-title">Messages générés</span></a>
            </li>
            <!--Messages générés-->

            <!--Enquêtes clients-->
            <li class="nav-item {{ set_active_route('enquetes-clients.index') }}"><a href="{{ route('enquetes-clients.index') }}">
                <i class="ft-trending-up"></i><span data-i18n="" class="menu-title">Enquêtes clients</span></a>
            </li>
            <!--Enquêtes clients-->

        </ul>
    </div>
@elseif (Auth::user()->type == 'F')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Paramètres-->
            <li class="has-sub nav-item"><a href="#"><i class="ft-settings"></i><span data-i18n="" class="menu-title">Paramètres</span></a>
                <ul class="menu-content">

                    <li class="nav-item {{ set_active_route('secteurs-activite.index') }}"><a href="{{ route('secteurs-activite.index') }}">
                        <span data-i18n="" class="menu-title">Secteurs d'activité</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('villes.index') }}"><a href="{{ route('villes.index') }}">
                        <span data-i18n="" class="menu-title">Villes</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('zones.index') }}"><a href="{{ route('zones.index') }}">
                        <span data-i18n="" class="menu-title">Zones</span></a>
                    </li>


                    <li class="nav-item {{ set_active_route('quartiers.index') }}"><a href="{{ route('quartiers.index') }}">
                        <span data-i18n="" class="menu-title">Quartiers</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('fonctions.index') }}"><a href="{{ route('fonctions.index') }}">
                        <span data-i18n="" class="menu-title">Fonctions</span></a>
                    </li>


                    <li class="nav-item {{ set_active_route('types-sanction.index') }}"><a href="{{ route('types-sanction.index') }}">
                        <span data-i18n="" class="menu-title">Sanctions et Obs.</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('mois-paie.index') }}"><a href="{{ route('mois-paie.index') }}">
                        <span data-i18n="" class="menu-title">Mois de paie</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('modes-paiement.index') }}"><a href="{{ route('modes-paiement.index') }}">
                        <span data-i18n="" class="menu-title">Modes de paiement</span></a>
                    </li>
                </ul>
            </li>
            <!--Paramètres-->

            <!--Clients-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Clients</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('clients.create') }}"><a href="{{ route('clients.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('clients.index') }}"><a href="{{ route('clients.index') }}" class="menu-item">Liste</a></li>
                    <li class="{{ set_active_route('contacts-client.index') }}"><a href="{{ route('contacts-client.index') }}" class="menu-item">Contacts client</a></li>
                    <li class="{{ set_active_route('sites-client.index') }}"><a href="{{ route('sites-client.index') }}" class="menu-item">Sites client</a></li>
                    <li class="{{ set_active_route('avenants-contrat.create') }} {{ set_active_route('avenants-contrat.index') }}"><a href="{{ route('avenants-contrat.index') }}" class="menu-item">Avenants contrat</a></li>
                    <li class="{{ set_active_route('factures-clients.index') }}"><a href="{{ route('factures-clients.index') }}" class="menu-item">Factures clients</a></li>
                    <li class="{{ set_active_route('reglements-clients.index') }}"><a href="{{ route('reglements-clients.index') }}" class="menu-item">Règlements clients</a></li>
                    <li class="{{ set_active_route('suivi-clients.create') }} {{ set_active_route('suivi-clients.index') }}"><a href="{{ route('suivi-clients.index') }}" class="menu-item">Suivi Clientèle</a></li>
                    <li class="{{ set_active_route('messages-generique.index') }}"><a href="{{ route('messages-generique.index') }}" class="menu-item">Messages générés</a></li>
                    <li class="{{ set_active_route('enquetes-clients.index') }}"><a href="{{ route('enquetes-clients.index') }}" class="menu-item">Enquêtes clients</a></li>
                </ul>
            </li>
            <!--Clients-->

            <!--Personnels-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-user"></i><span data-i18n="" class="menu-title">Personnel</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('personnels.create') }}"><a href="{{ route('personnels.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('personnels.index') }}"><a href="{{ route('personnels.index') }}" class="menu-item">Liste</a></li>
                    <li class="{{ set_active_route('carrieres.index') }}"><a href="{{ route('carrieres.index') }}" class="menu-item">Carrières</a></li>
                    <li class="{{ set_active_route('personnels.controleurs') }}"><a href="{{ route('personnels.controleurs') }}" class="menu-item">Contrôleurs</a></li>
                    <li class="{{ set_active_route('personnels.superviseur') }}"><a href="{{ route('personnels.superviseur') }}" class="menu-item">Superviseurs</a></li>
                    <li class="{{ set_active_route('personnels.commando') }}"><a href="{{ route('personnels.commando') }}" class="menu-item">Commandos</a></li>
                    <li class="{{ set_active_route('personnels.chef_equipe') }}"><a href="{{ route('personnels.chef_equipe') }}" class="menu-item">Chefs d'Equipe</a></li>
                    <li class="{{ set_active_route('personnels.maitre_chien') }}"><a href="{{ route('personnels.maitre_chien') }}" class="menu-item">Maitres chien</a></li>
                    <li class="{{ set_active_route('personnels.agent_fixe') }}"><a href="{{ route('personnels.agent_fixe') }}" class="menu-item">Agents Fixe</a></li>
                    <li class="{{ set_active_route('personnels.rondier') }}"><a href="{{ route('personnels.rondier') }}" class="menu-item">Rondiers</a></li>
                    <li class="{{ set_active_route('personnels.stand_by') }}"><a href="{{ route('personnels.stand_by') }}" class="menu-item">Stand Bye</a></li>
                    <li class="{{ set_active_route('personnels.postulants') }}"><a href="{{ route('personnels.postulant') }}" class="menu-item">Postulants</a></li>
                </ul>
            </li>
            <!--Personnel-->

            <!--Opérations-->
            <li class="has-sub nav-item"><a href="#"><i class="ft-edit"></i><span data-i18n="" class="menu-title">Opérations</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ set_active_route('poster-vigiles.index') }}"><a href="{{ route('poster-vigiles.index') }}">
                        <span data-i18n="" class="menu-title">Postings des vigiles</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('sites-superviseur.index') }}"><a href="{{ route('sites-superviseur.index') }}">
                        <span data-i18n="" class="menu-title">Sites supervisés</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('supervision-sites.index') }}"><a href="{{ route('supervision-sites.index') }}">
                        <span data-i18n="" class="menu-title">Ronde des contrôleurs</span></a>
                    </li>

                    <li class="nav-item {{ set_active_route('pointages-sanctions.index') }}"><a href="{{ route('pointages-sanctions.index') }}">
                        <span data-i18n="" class="menu-title">Sanctions et Obs.</span></a>
                    </li>
                </ul>
            </li>
            <!--Opérations-->

            <!--Users-->
            <li class="has-sub nav-item"><a href="#">
                <i class="ft-users"></i><span data-i18n="" class="menu-title">Utilisateurs</span></a>
                <ul class="menu-content">
                    <li class="{{ set_active_route('users.create') }}"><a href="{{ route('users.create') }}" class="menu-item">Nouveau</a></li>
                    <li class="{{ set_active_route('users.index') }}"><a href="{{ route('users.index') }}" class="menu-item">Liste</a></li>
                </ul>
            </li>
            <!--Users-->


            <!--Actions utilisateurs-->
            {{--<li class="nav-item {{ set_active_route('supervision-sites.index') }}"><a href="{{ route('supervision-sites.index') }}">
                <i class="ft-download"></i><span data-i18n="" class="menu-title">Actions Utilisateurs</span></a>
            </li>--}}
            <!--Actions utilisateurs-->

        </ul>
    </div>
@elseif (Auth::user()->type == 'T')
    <div class="nav-container">
        <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

            <!--Tableau de bord-->
            <li class="nav-item {{ set_active_route('home') }}"><a href="{{ route('home') }}">
                <i class="ft-home"></i><span data-i18n="" class="menu-title">Tableau de bord</span></a>
            </li>
            <!--Tableau de bord-->

            <!--Rondes de supervision des sites-->
            <li class="nav-item {{ set_active_route('supervision-sites.index') }}"><a href="{{ route('supervision-sites.index') }}">
                <i class="ft-download"></i><span data-i18n="" class="menu-title">Rondes de supervision</span></a>
            </li>
            <!--Rondes de supervision des sites-->

        </ul>
    </div>
@else
@endif

