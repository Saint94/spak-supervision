@extends('layout.app')

@section('title', 'Nouvel utilisateur')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Création d'un nouvel utilisateur</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('users.store') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="noms">Noms <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" style="text-transform: uppercase;" class="form-control" id="noms" name="noms" value="{{ old('noms') }}" placeholder="Noms" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="prenoms">Prénoms</label>
                                                <input type="text" class="form-control" id="prenoms" name="prenoms" value="{{ old('prenoms') }}" placeholder="Prénoms"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="fonction">Fonction</label>
                                                <input type="text" class="form-control" id="fonction" name="fonction" value="{{ old('fonction') }}" placeholder="Fonction"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="tel1">N° de téléphone 1</label>
                                                <input type="text" class="form-control" id="tel1" name="tel1" value="{{ old('tel1') }}" placeholder="N° de téléphone 1"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="tel2">N° de téléphone 2</label>
                                                <input type="text" class="form-control" id="tel2" name="tel2" value="{{ old('tel2') }}" placeholder="N° de téléphone 2"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="username">Nom d'utilisateur <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Nom d'utilisateur" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="password">Mot de passe <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="password" name="password" value="{{ $mypassword }}" placeholder="Mot de passe" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="type">Selectionner le profil <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e1 form-control" id="type" name="type" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="M">Super Manager</option>
                                                    <option value="R">Responsable Opérations</option>
                                                    <option value="S">Agent Superviseur</option>
                                                    <option value="E">Chef d'Equipe</option>
                                                    <option value="P">Agent de Paie</option>
                                                    <option value="C">Responsable Commercial</option>
                                                    <option value="F">Agent de Facturation</option>
                                                    <option value="T">Agent Contrôleur</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6" id="afficher_personnels">
                                            <div class="form-group">
                                                <label for="personnel">Selectionner le personnel</label>
                                                <select class="e2 form-control" id="personnel" name="personnel">
                                                    <option selected value="">Selectionner...</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        @if(Auth::user()->type == 'A')
                                            <div class="col-xl-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="entite">Entité <abbr style="color: #bd4147;">(*)</abbr></label>
                                                    <select class="e2 form-control" id="entite" name="entite" required>
                                                        @foreach($entites as $entite)
                                                            <option value="{{ $entite->EnNumero }}">{{ $entite->EnNom }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Ce champ est obligatoire.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @php
                                                $entite = DB::table('entites')
                                                    ->where('entites.EnNumero',Auth::user()->entite)
                                                    ->first();
                                            @endphp
                                            <div class="col-xl-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="entite">Entité <abbr style="color: #bd4147;">(*)</abbr></label>
                                                    <input type="text" class="form-control" value="{{ $entite->EnNom }}" readonly/>
                                                    <input type="hidden" class="form-control" id="entite" name="entite" value="{{ $entite->EnNumero }}"/>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Modifier le mot de passe à la prochaine connexion ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="reset_password" class="custom-control-input" value="Y" checked/>
                                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="reset_password" class="custom-control-input" value="N"/>
                                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('users.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
                                    <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('add_js')
    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#type").change(function()
            {
                var type =  $("#type").val();

                $.get('{{ route("user.show_personnels_users","") }}/'+type, function(rep) {
                    $("#afficher_personnels").html(rep);
                });
            });

            if($("#type").val() != null) {
                
                var type =  $("#type").val();

                $.get('{{ route("user.show_personnels_users","") }}/'+type, function(rep) {
                    $("#afficher_personnels").html(rep);
                });
            }
        });
    </script>
@endsection
