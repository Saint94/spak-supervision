@extends('layout.app')

@section('title', 'Nouveau client')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Création d'un nouveau client</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('clients.store') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" style="text-transform: uppercase;" class="form-control" id="ClCode" name="ClCode" value="{{ old('ClCode') }}" placeholder="Code" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClDesign">Désignation <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="ClDesign" name="ClDesign" value="{{ old('ClDesign') }}" placeholder="Désignation" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClEmail">Email</label>
                                                <input type="text" class="form-control" id="ClEmail" name="ClEmail" value="{{ old('ClEmail') }}" placeholder="Email"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClVilleSiege">Ville (Siège) <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e1 form-control" id="ClVilleSiege" name="ClVilleSiege" required>
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($villes as $ville)
                                                        <option value="{{ $ville->ViNumero }}">{{ $ville->ViNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3" id="afficher_liste_quartiers">
                                            <div class="form-group">
                                                <label for="ClQuartier">Quartier</label>
                                                <select class="e2 form-control" id="ClQuartier" name="ClQuartier">
                                                    <option selected value="">Selectionner...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClSectActivite">Secteurs d'activité <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e3 form-control" id="ClSectActivite" name="ClSectActivite" required>
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($secteurs_activites as $secteur_activite)
                                                        <option value="{{ $secteur_activite->SaNumero }}">{{ $secteur_activite->SaNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClDateDebut">Date début</label>
                                                <input type="date" class="form-control" id="ClDateDebut" name="ClDateDebut" value="{{ old('ClDateDebut') }}"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClTel1">N° de téléphone 1</label>
                                                <input type="text" class="form-control" id="ClTel1" name="ClTel1" value="{{ old('ClTel1') }}" placeholder="N° de téléphone 1"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClTel2">N° de téléphone 2</label>
                                                <input type="text" class="form-control" id="ClTel2" name="ClTel2" value="{{ old('ClTel2') }}" placeholder="N° de téléphone 2"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClNbreSites">Nombre de sites</label>
                                                <input type="text" class="form-control" placeholder="Nombre de sites" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbAgentJour">Nombre d'agents jour</label>
                                                <input type="text" class="form-control" placeholder="Nombre d'agents jour" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbAgentNuit">Nombre d'agents nuit</label>
                                                <input type="text" class="form-control" placeholder="Nombre d'agents nuit" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbVigileJr">Nombre de vigiles jour</label>
                                                <input type="text" class="form-control" placeholder="Nombre de vigiles jour" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbVigileNu">Nombre de vigiles nuit</label>
                                                <input type="text" class="form-control" placeholder="Nombre de vigiles nuit" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbMeChienJr">Nombre de maîtres chien jour</label>
                                                <input type="text" class="form-control" placeholder="Nombre de maîtres chien jour" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbMeChienNu">Nombre de maîtres chien nuit</label>
                                                <input type="text" class="form-control" placeholder="Nombre de maîtres chien nuit" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbCmdoJr">Nombre de commandos jour</label>
                                                <input type="text" class="form-control" placeholder="Nombre de commandos jour" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbCmdoNu">Nombre de commandos nuit</label>
                                                <input type="text" class="form-control" placeholder="Nombre de commandos nuit" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbSupervJr">Nombre de superviseurs jour</label>
                                                <input type="text" class="form-control" placeholder="Nombre de superviseurs jour" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ClNbSupervNu">Nombre de superviseurs nuit</label>
                                                <input type="text" class="form-control" placeholder="Nombre de superviseurs nuit" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Statut ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="ClIsOn" class="custom-control-input" value="Y" checked/>
                                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="ClIsOn" class="custom-control-input" value="N"/>
                                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>TVA ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline3" name="ClIsTVAOn" class="custom-control-input" value="Y" checked/>
                                                        <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline4" name="ClIsTVAOn" class="custom-control-input" value="N"/>
                                                        <label class="custom-control-label" for="customRadioInline4">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClContactlNom">Nom du contact par défaut </label>
                                                <input type="text" class="form-control" id="ClContactlNom" name="ClContactlNom" value="{{ old('ClContactlNom') }}" placeholder="Nom du contact par défaut"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ClModePaiem">Mode de paiement</label>
                                                <select class="e4 form-control" id="ClModePaiem" name="ClModePaiem">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($modes_paiement as $mode_paiement)
                                                        <option value="{{ $mode_paiement->MpNumero }}">{{ $mode_paiement->MpNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(Auth::user()->type == 'F')
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="ClTypeReglem">Type de reglèment </label>
                                                    <input type="text" class="form-control" id="ClTypeReglem" name="ClTypeReglem" value="{{ old('ClTypeReglem') }}" placeholder="Type de reglèment"/>
                                                    <div class="invalid-feedback">
                                                        Ce champ est obligatoire.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="ClJourReglem">Jour de reglèment </label>
                                                    <input type="date" class="form-control" id="ClJourReglem" name="ClJourReglem" value="{{ old('ClJourReglem') }}"/>
                                                    <div class="invalid-feedback">
                                                        Ce champ est obligatoire.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <label for="ClDetails">Détails</label>
                                                <textarea rows="2" class="form-control" id="ClDetails" name="ClDetails" placeholder="Commentaires"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('clients.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
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
            $(".e4").select2();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#ClVilleSiege").change(function()
            {
                var ClVilleSiege =  $("#ClVilleSiege").val();

                $.get('{{ route("clients.show_liste_quartiers","") }}/'+ClVilleSiege, function(rep) {
                    $("#afficher_liste_quartiers").html(rep);
                });
            });

            if($("#ClVilleSiege").val() != null) {
                var ClVilleSiege =  $("#ClVilleSiege").val();

                $.get('{{ route("clients.show_liste_quartiers","") }}/'+ClVilleSiege, function(rep) {
                    $("#afficher_liste_quartiers").html(rep);
                });
            }
        });
    </script>

@endsection
