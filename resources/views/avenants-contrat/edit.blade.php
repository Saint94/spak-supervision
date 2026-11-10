@extends('layout.app')

@section('title', 'Modifier avenant')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Modification avenant {{ $avenant_contrat->ClDesign }}</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('avenants-contrat.update', $avenant_contrat->AcNumero ) }}" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <input type="hidden" id="EnPrixUMinAgentFixe" value="{{ $entite->EnPrixUMinAgentFixe }}"/>
                                <input type="hidden" id="EnPrixUMinMC" value="{{ $entite->EnPrixUMinMC }}"/>
                                <input type="hidden" id="EnPrixUMinSuperv" value="{{ $entite->EnPrixUMinSuperv }}"/>
                                <input type="hidden" id="EnPrixUMinComdo" value="{{ $entite->EnPrixUMinComdo }}"/>

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                         <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="hidden" class="form-control" id="AcClient" value="{{ $avenant_contrat->AcClient }}"/>
                                                <input type="text" class="form-control" value="{{ $avenant_contrat->ClDesign }}" readonly/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="afficher_last_contrat" style="display:none;">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Référence</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date de saisie</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date effet</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date de fin</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Prix agent</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre d'agents jour</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre d'agents nuit</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Prix maître chien</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre maître chien jour</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre maître chien nuit</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Prix superviseur</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre superviseur jour</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre superviseur nuit</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Prix commando</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre commando jour</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nbre commando nuit</label>
                                                    <input type="text" class="form-control" readonly/>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcDate">Date de saisie <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="date" class="form-control" id="AcDate" name="AcDate" value="{{ $avenant_contrat->AcDate }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6" id="afficher_ref_contrat_edit">
                                            <div class="form-group">
                                                <label for="AcRefAven">Référence <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="AcRefAven" name="AcRefAven" value="{{ $avenant_contrat->AcRefAven }}" placeholder="Référence" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcType">Nature</label>
                                                <input type="text" class="form-control" value="{{ 'Avenant' }}" readonly/>
                                                <input type="hidden" class="form-control" id="AcType" name="AcType" value="{{ 'AV' }}"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcDateEffet">Date effet <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="date" class="form-control" id="AcDateEffet" name="AcDateEffet" value="{{ $avenant_contrat->AcDateEffet }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcDateFin">Date fin</label>
                                                <input type="date" class="form-control" id="AcDateFin" name="AcDateFin" value="{{ $avenant_contrat->AcDateFin }}"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixAgent">Prix par agent <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinAgentFixe) }})</small></label>
                                                <input type="text" class="form-control number" id="AcPrixAgent" name="AcPrixAgent" value="{{ $avenant_contrat->AcPrixAgent }}" placeholder="Prix par agent" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbAgentJr">Nombre d'agents jour</label>
                                                <input type="text" class="form-control number" id="AcNbAgentJr" name="AcNbAgentJr" value="{{ $avenant_contrat->AcNbAgentJr }}" placeholder="Nombre d'agents jour" readonly/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbAgentNu">Nombre d'agents nuit</label>
                                                <input type="text" class="form-control number" id="AcNbAgentNu" name="AcNbAgentNu" value="{{ $avenant_contrat->AcNbAgentNu }}" placeholder="Nombre d'agents nuit" readonly/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixUMC">Prix mensuel pour un maître Chien <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinMC) }})</small></label>
                                                <input type="text" class="form-control number" id="AcPrixUMC" name="AcPrixUMC" value="{{ $avenant_contrat->AcPrixUMC }}" placeholder="Prix mensuel pour un maître Chien" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbreMCJour">Nombre de maîtres chien par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbreMCJour" name="AcNbreMCJour" value="{{ $avenant_contrat->AcNbreMCJour }}" placeholder="Nombre de maîtres chien par jour" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbreMCNuit">Nombre de maîtres chien par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbreMCNuit" name="AcNbreMCNuit" value="{{ $avenant_contrat->AcNbreMCNuit }}" placeholder="Nombre de maîtres chien par nuit" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixUSuperv">Prix mensuel pour un superviseur <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinSuperv) }})</small></label>
                                                <input type="text" class="form-control number" id="AcPrixUSuperv" name="AcPrixUSuperv" value="{{ $avenant_contrat->AcPrixUSuperv }}" placeholder="Prix mensuel pour un superviseur" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbSupervJr">Nombre de superviseurs jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbSupervJr" name="AcNbSupervJr" value="{{ $avenant_contrat->AcNbSupervJr }}" placeholder="Nombre de superviseurs jour" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbSupervNu">Nombre de superviseurs nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbSupervNu" name="AcNbSupervNu" value="{{ $avenant_contrat->AcNbSupervNu }}" placeholder="Nombre de superviseurs nuit" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixUComm">Prix mensuel pour un commando <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinComdo) }})</small></label>
                                                <input type="text" class="form-control number" id="AcPrixUComm" name="AcPrixUComm" value="{{ $avenant_contrat->AcPrixUComm }}" placeholder="Prix mensuel pour un commando" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="AcNbComdoJr">Nombre de commando jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbComdoJr" name="AcNbComdoJr" value="{{ $avenant_contrat->AcNbComdoJr }}" placeholder="Nombre de commando jour" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="AcNbComdoNu">Nombre de commando nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbComdoNu" name="AcNbComdoNu" value="{{ $avenant_contrat->AcNbComdoNu }}" placeholder="Nombre de commando nuit" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbreOperVideo">Nombre des opérateurs vidéo <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbreOperVideo" name="AcNbreOperVideo" value="{{ $avenant_contrat->AcNbreOperVideo }}" placeholder="Nombre des opérateurs vidéo" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixUOperVideo">Prix mensuel pour un opérateur vidéo <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcPrixUOperVideo" name="AcPrixUOperVideo" value="{{ $avenant_contrat->AcPrixUOperVideo }}" placeholder="Prix mensuel pour un opérateur vidéo" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbLocatVidSurv">Nombre de location vidéo surveillance <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbLocatVidSurv" name="AcNbLocatVidSurv" value="{{ $avenant_contrat->AcNbLocatVidSurv }}" placeholder="Nombre de location vidéo surveillance" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixULocVidSurv">Prix mensuel pour une location vidéo surveillance <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcPrixULocVidSurv" name="AcPrixULocVidSurv" value="{{ $avenant_contrat->AcPrixULocVidSurv }}" placeholder="Prix mensuel pour une location vidéo surveillance" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcNbInstVidSurv">Nombre d'installation de vidéo surveillance <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcNbInstVidSurv" name="AcNbInstVidSurv" value="{{ $avenant_contrat->AcNbInstVidSurv }}" placeholder="Nombre d'installation de vidéo surveillance" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcPrixUInstVidSurv">Prix mensuel pour une installation de vidéo surveillance <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="AcPrixUInstVidSurv" name="AcPrixUInstVidSurv" value="{{ $avenant_contrat->AcPrixUInstVidSurv }}" placeholder="Prix mensuel pour une installation de vidéo surveillance" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Statut ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="AcIsOn" class="custom-control-input" value="Y" {{ $avenant_contrat->AcIsOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="AcIsOn" class="custom-control-input" value="N" {{ $avenant_contrat->AcIsOn=='N'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <label for="AcDetails">Détails</label>
                                                <textarea rows="2" class="form-control" id="AcDetails" name="AcDetails" placeholder="Commentaires">{{ $avenant_contrat->AcDetails }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <input type="file" class="filepond" id="AcChemDocContrat" name="AcChemDocContrat" accept="*"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <a href="{{ route('avenants-contrat.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
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
            $("#AcClient").change(function()
            {
                var AcClient =  $("#AcClient").val();

                $.get('{{ route("avenant-contrat.last-contrat-client","") }}/'+AcClient, function(rep) {
                    $("#afficher_last_contrat").show();
                    $("#afficher_last_contrat").html(rep);
                });

                $.get('{{ route("avenant-contrat.ref-contrat-client-edit","") }}/'+AcClient, function(rep) {
                    $("#afficher_ref_contrat_edit").html(rep);
                });
            });

            if($("#AcClient").val() != null) {
                var AcClient =  $("#AcClient").val();

                $.get('{{ route("avenant-contrat.last-contrat-client","") }}/'+AcClient, function(rep) {
                    $("#afficher_last_contrat").show();
                    $("#afficher_last_contrat").html(rep);
                });

                $.get('{{ route("avenant-contrat.ref-contrat-client-edit","") }}/'+AcClient, function(rep) {
                    $("#afficher_ref_contrat_edit").html(rep);
                });
            }
        });
    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputLogo = document.querySelector('input[id="AcChemDocContrat"]');

            // Create a FilePond instance
            FilePond.create(inputLogo,{
                labelIdle: `Faites glisser et déposez le document ou <span class="filepond--label-action">Parcourir</span>`,
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',

                server: {
                    process: 'https://diffapps.diff-itc.net/spak-supervision/upload-doc-contrat',
                    //process: '/upload-doc-contrat',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
        });
    </script>

    <script>
        $(document).ready(function() {
            // Récupération des prix minimum
            var prixMinAgent = parseFloat($("#EnPrixUMinAgentFixe").val()) || 0;
            var prixMinMC = parseFloat($("#EnPrixUMinMC").val()) || 0;
            var prixMinSuperv = parseFloat($("#EnPrixUMinSuperv").val()) || 0;
            var prixMinComdo = parseFloat($("#EnPrixUMinComdo").val()) || 0;

            // Fonction de validation des prix
            function validatePrix(input, minPrix, fieldName) {
                var value = parseFloat(input.val().replace(/\s/g, '')) || 0;
                var errorMsg = `Le prix ${fieldName} (${value.toLocaleString()}) doit être supérieur ou égal au prix minimum (${minPrix.toLocaleString()})`;

                if(value > 0 && value < minPrix) {
                    input[0].setCustomValidity(errorMsg);
                    input.addClass('is-invalid');
                    // Créer ou mettre à jour le message d'erreur
                    var feedback = input.siblings('.invalid-feedback');
                    if (feedback.length === 0) {
                        feedback = $('<div class="invalid-feedback"></div>').insertAfter(input);
                    }
                    feedback.text(errorMsg);
                    return false;
                } else {
                    input[0].setCustomValidity('');
                    input.removeClass('is-invalid');
                    var feedback = input.siblings('.invalid-feedback');
                    if (feedback.length > 0 && feedback.text().includes('prix minimum')) {
                        feedback.remove();
                    }
                    return true;
                }
            }

            // Attacher les événements de validation
            $("#AcPrixAgent").on('input blur', function() {
                validatePrix($(this), prixMinAgent, "par agent");
            });

            $("#AcPrixUMC").on('input blur', function() {
                validatePrix($(this), prixMinMC, "maître chien");
            });

            $("#AcPrixUSuperv").on('input blur', function() {
                validatePrix($(this), prixMinSuperv, "superviseur");
            });

            $("#AcPrixUComm").on('input blur', function() {
                validatePrix($(this), prixMinComdo, "commando");
            });

            // Validation au soumission du formulaire
            $('form').on('submit', function(e) {
                var isValid = true;
                isValid = validatePrix($("#AcPrixAgent"), prixMinAgent, "par agent") && isValid;
                isValid = validatePrix($("#AcPrixUMC"), prixMinMC, "maître chien") && isValid;
                isValid = validatePrix($("#AcPrixUSuperv"), prixMinSuperv, "superviseur") && isValid;
                isValid = validatePrix($("#AcPrixUComm"), prixMinComdo, "commando") && isValid;

                if(!isValid) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
