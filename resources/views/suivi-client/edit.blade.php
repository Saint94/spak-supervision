@extends('layout.app')

@section('title', 'Modifier évènement')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Modification évènement {{ $evenement->EvTitre }}</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('suivi-clients.update', $evenement->EvNumero ) }}" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EvClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e1 form-control" id="EvClient" name="EvClient" required>
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($clients as $client)
                                                        <option value="{{ $client->ClNumero }}" {{ $evenement->EvClient == $client->ClNumero ? 'selected="selected"':'' }}>{{ $client->ClDesign }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EvType">Selectionner le type d'évènement <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e2 form-control" id="EvType" name="EvType" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="EP" {{ $evenement->EvClient == 'EP' ? 'selected="selected"':'' }}>Échéance de Paiement</option>
                                                    <option value="QS" {{ $evenement->EvClient == 'QS' ? 'selected="selected"':'' }}>Qualité de Service</option>
                                                    <option value="RD" {{ $evenement->EvClient == 'RD' ? 'selected="selected"':'' }}>Rendez-vous</option>
                                                    <option value="PR" {{ $evenement->EvClient == 'PR' ? 'selected="selected"':'' }}>Prospect</option>
                                                    <option value="EV" {{ $evenement->EvClient == 'EV' ? 'selected="selected"':'' }}>Événement</option>
                                                    <option value="AU" {{ $evenement->EvClient == 'AU' ? 'selected="selected"':'' }}>Autre</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EvTitre">Titre évènement <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EvTitre" name="EvTitre" value="{{ $evenement->EvTitre }}" placeholder="Titre évènement" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvDate">Date évènement <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="date" class="form-control" id="EvDate" name="EvDate" value="{{ date('Y-m-d', strtotime($evenement->EvDate)) }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvHeure">Heure évènement <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="time" class="form-control" id="EvHeure" name="EvHeure" value="{{ date('H:i', strtotime($evenement->EvHeure)) }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvDateHRappel">Date de rappel <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="date" class="form-control" id="EvDateHRappel" name="EvDateHRappel" value="{{ date('Y-m-d', strtotime($evenement->EvDateHRappel)) }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvHRappel">Heure de rappel <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="time" class="form-control" id="EvHRappel" name="EvHRappel" value="{{ date('H:i', strtotime($evenement->EvDateHRappel)) }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EvDateFinRappel">Date de fin de rappel <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="date" class="form-control" id="EvDateFinRappel" name="EvDateFinRappel" value="{{ date('Y-m-d', strtotime($evenement->EvDateFinRappel)) }}" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EvFreqRapAprXJr">Fréquence de rappel en jours <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="number" class="form-control number" id="EvFreqRapAprXJr" name="EvFreqRapAprXJr" value="{{ $evenement->EvFreqRapAprXJr }}" placeholder="Fréquence de rappel en jours" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvNbRapByJr">Nbre de rappels par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="number" class="form-control number" id="EvNbRapByJr" name="EvNbRapByJr" value="{{ $evenement->EvNbRapByJr }}" placeholder="Nbre de rappels par jour" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvTmpEcartRappels">Rappeler toutes les:</label>
                                                <input type="text" class="form-control" id="EvTmpEcartRappels" name="EvTmpEcartRappels" value="{{ $evenement->EvTmpEcartRappels }}" placeholder="Rappeler toutes les" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvDateHNextRapp">Date du prochain rappel</label>
                                                <input type="text" class="form-control" id="EvDateHNextRapp" name="EvDateHNextRapp" value="{{  date('Y-m-d', strtotime($evenement->EvDateHNextRapp)) }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EvHNextRapp">Heure du prochain rappel</label>
                                                <input type="text" class="form-control" id="EvHNextRapp" name="EvHNextRapp" value="{{  date('H:i', strtotime($evenement->EvDateHNextRapp)) }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EvTitreRapp">Titre du rappel <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EvTitreRapp" name="EvTitreRapp" value="{{ $evenement->EvTitreRapp }}" placeholder="Titre du rappel" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <label for="EvMessageRapp">Détails</label>
                                                <textarea rows="2" class="form-control" id="EvMessageRapp" name="EvMessageRapp" placeholder="Message de rappel">{{ $evenement->EvMessageRapp }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Un bip sonore est-il actif pour le rappel ?<abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="EvIsSonOn" class="custom-control-input" value="Y" {{ $evenement->EvIsSonOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="EvIsSonOn" class="custom-control-input" value="N" {{ $evenement->EvIsSonOn=='N'?'checked="checked"':'' }}/>
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
                                                <label>Ce rappel est-il destiné à un seul utilisateur ?</label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline3" name="EvIsOnly1User" class="custom-control-input" value="Y" {{ $evenement->EvIsOnly1User=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline4" name="EvIsOnly1User" class="custom-control-input" value="N" {{ $evenement->EvIsOnly1User=='N'?'checked="checked"':'' }}/>
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
                                        <div class="col-xl-6 col-md-6" id="afficher_user">
                                            <div class="form-group">
                                                <label for="EvUserRapp">Utilisateur concerné <abbr id="afficher_obligatoire_user" style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e3 form-control" id="EvUserRapp" name="EvUserRapp">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $evenement->EvUserRapp == $user->id ? 'selected="selected"':'' }}>{{ $user->noms.' '.$user->prenoms }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Statut ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline5" name="EvIsOn" class="custom-control-input" value="Y" {{ $evenement->EvIsOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline5">Actif</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline6" name="EvIsOn" class="custom-control-input" value="N" {{ $evenement->EvIsOn=='N'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline6">Inactif</label>
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
                                                <input type="file" class="filepond" id="EvChemRapport" name="EvChemRapport" accept="*"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <a href="{{ route('suivi-clients.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
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

            // Récupération des éléments
            const evDate = document.getElementById('EvDate');
            const evDateHRappel = document.getElementById('EvDateHRappel');
            const evDateFinRappel = document.getElementById('EvDateFinRappel');
            const evFreqRapAprXJr = document.getElementById('EvFreqRapAprXJr');
            const evNbRapByJr = document.getElementById('EvNbRapByJr');
            const evDateHNextRapp = document.getElementById('EvDateHNextRapp');
            const evHNextRapp = document.getElementById('EvHNextRapp');

            // Fonction pour formater la date en YYYY-MM-DD
            function formatDate(date) {
                let d = new Date(date);
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                let year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return day + '/' + month + '/' + year;
            }

            // Fonction pour calculer le prochain rappel
            function calculateNextReminder() {
                const freqJours = parseInt(evFreqRapAprXJr.value);
                const nbRapParJour = parseInt(evNbRapByJr.value);
                const dateRappel = new Date(evDateHRappel.value);
                const heureRappel = document.getElementById('EvHRappel').value;
                const dateFinRappel = new Date(evDateFinRappel.value);

                if (isNaN(freqJours) || isNaN(nbRapParJour) || !evDateHRappel.value || !heureRappel || !evDateFinRappel.value) {
                    return;
                }

                // Ici vous pouvez implémenter la logique de calcul du prochain rappel
                // Cette logique dépend de votre business logic
                // Exemple simple : le prochain rappel est la date de rappel + fréquence
                let nextDate = new Date(dateRappel);
                nextDate.setDate(nextDate.getDate() + freqJours);

                if (nextDate <= dateFinRappel) {
                    evDateHNextRapp.value = formatDate(nextDate);
                    evHNextRapp.value = heureRappel;
                } else {
                    evDateHNextRapp.value = '';
                    evHNextRapp.value = '';
                }
            }

            // Contrôle: Date de rappel <= Date événement
            evDateHRappel.addEventListener('change', function() {
                const rappelDate = new Date(this.value);
                const eventDate = new Date(evDate.value);

                if (rappelDate > eventDate) {
                    alert('La date de rappel doit être inférieure ou égale à la date de l\'événement');
                    this.value = evDate.value;
                }

                // Mettre à jour l'attribut max de la date de fin de rappel
                evDateFinRappel.min = this.value;

                // Recalculer le prochain rappel
                calculateNextReminder();
            });

            // Contrôle: Date événement - mettre à jour le max de la date de rappel
            evDate.addEventListener('change', function() {
                evDateHRappel.max = this.value;

                // Vérifier si la date de rappel actuelle est toujours valide
                const rappelDate = new Date(evDateHRappel.value);
                const eventDate = new Date(this.value);

                if (rappelDate > eventDate) {
                    evDateHRappel.value = this.value;
                }

                calculateNextReminder();
            });

            // Contrôle: Date fin de rappel >= Date de rappel
            evDateFinRappel.addEventListener('change', function() {
                const finRappelDate = new Date(this.value);
                const rappelDate = new Date(evDateHRappel.value);

                if (finRappelDate < rappelDate) {
                    alert('La date de fin de rappel doit être supérieure ou égale à la date de rappel');
                    this.value = evDateHRappel.value;
                }

                // Mettre à jour l'attribut min
                this.min = evDateHRappel.value;

                calculateNextReminder();
            });

            // Écouter les changements sur les champs de fréquence
            evFreqRapAprXJr.addEventListener('input', calculateNextReminder);
            evNbRapByJr.addEventListener('input', calculateNextReminder);

            // Écouter le changement d'heure de rappel
            document.getElementById('EvHRappel').addEventListener('change', calculateNextReminder);

            // Initialiser les attributs min/max au chargement
            evDateHRappel.max = evDate.value;
            evDateHRappel.min = evDate.value; // Optionnel: la date de rappel ne devrait pas être dans le futur lointain
            evDateFinRappel.min = evDateHRappel.value;

            // Initialiser le calcul du prochain rappel si des valeurs existent
            if (evFreqRapAprXJr.value && evNbRapByJr.value) {
                calculateNextReminder();
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $("input[type=radio][name=EvIsOnly1User]").click(function()
            {
                if($(this).val() == "Y")
                {
                    $("#afficher_user").show();
                    $("#afficher_obligatoire_user").show();
                    $("#EvUserRapp").attr("required",true);
                }
                else
                {
                    $("#afficher_user").hide();
                    $("#afficher_obligatoire_user").hide();
                    $("#EvUserRapp").attr("required",false);
                }
            });

            if($("input[name='EvIsOnly1User']:checked").val() == "Y")
            {
                $("#afficher_user").show();
                $("#afficher_obligatoire_user").show();
                $("#EvUserRapp").attr("required",true);
            }
            else
            {
                $("#afficher_user").hide();
                $("#afficher_obligatoire_user").hide();
                $("#EvUserRapp").attr("required",false);
            }
        });
    </script>

    <script>
        // Script séparé pour le calcul de l'intervalle
        (function() {
            'use strict';

            // Attendre que le DOM soit complètement chargé
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM chargé - calcul intervalle');

                const freqInput = document.getElementById('EvFreqRapAprXJr');
                const nbRapInput = document.getElementById('EvNbRapByJr');
                const resultInput = document.getElementById('EvTmpEcartRappels');

                if (!freqInput || !nbRapInput || !resultInput) {
                    console.error('Éléments non trouvés!');
                    return;
                }

                console.log('Éléments trouvés:', {
                    freq: freqInput,
                    nbRap: nbRapInput,
                    result: resultInput
                });

                function updateInterval() {
                    console.log('Update interval appelé');

                    const freq = parseInt(freqInput.value) || 0;
                    const nbRap = parseInt(nbRapInput.value) || 0;

                    console.log('Valeurs:', { freq, nbRap });

                    if (freq <= 0 || nbRap <= 0) {
                        resultInput.value = '00:00';
                        console.log('Intervalle = 00:00 (valeurs invalides)');
                        return;
                    }

                    // Calcul: (fréquence en jours × 24h) / nombre de rappels par jour
                    const totalHeures = (freq * 24) / nbRap;
                    const heures = Math.floor(totalHeures);
                    const minutes = Math.round((totalHeures - heures) * 60);

                    const result = String(heures).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
                    resultInput.value = result;
                    console.log('Intervalle calculé:', result);
                }

                // Ajouter les écouteurs
                freqInput.addEventListener('input', updateInterval);
                freqInput.addEventListener('change', updateInterval);
                nbRapInput.addEventListener('input', updateInterval);
                nbRapInput.addEventListener('change', updateInterval);

                // Calcul initial
                console.log('Calcul initial');
                updateInterval();

                // Forcer une mise à jour après 200ms (pour être sûr)
                setTimeout(updateInterval, 200);
            });
        })();
    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputLogo = document.querySelector('input[id="EvChemRapport"]');

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
                    process: 'https://diffapps.diff-itc.net/spak-supervision/upload-doc-evenement',
                    //process: '/upload-doc-evenement',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
        });
    </script>

@endsection
