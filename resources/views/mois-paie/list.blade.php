@extends('layout.app')

@section('title', 'Liste des mois de paie')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des mois de paie</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un mois de paie</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Statut</th>
                                    <th>Clôturé par</th>
                                    <th>Le</th>
                                    <th>Nbre pers séc début mois</th>
                                    <th>Nbre pers séc fin mois</th>
                                    <th>Nbre pers admin début mois</th>
                                    <th>Nbre pers admin fin mois</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mois_paie as $mois)
                                    <tr>
                                        <td>{{ $mois->MpLibelle }}</td>

                                        @if($mois->MpDateDebut != null)
                                            <td>{{ date('d/m/Y',strtotime($mois->MpDateDebut)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($mois->MpDateFin != null)
                                            <td>{{ date('d/m/Y',strtotime($mois->MpDateFin)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($mois->MpStatut == 'I')
                                            <td><span class="badge light badge-danger">Initialisé</span></td>
                                        @elseif($mois->MpStatut == 'E')
                                            <td><span class="badge light badge-warning">En cours</span></td>
                                        @else
                                            <td><span class="badge light badge-success">Clôturé</span></td>
                                        @endif

                                        <td>{{ $mois->noms.' '.$mois->prenoms }}</td>

                                        @if($mois->MpDateHCloture != null)
                                            <td>{{ date('d/m/Y H:i',strtotime($mois->MpDateHCloture)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ $mois->MpNbPersonSecDeb }}</td>
                                        <td>{{ $mois->MpNbPersonSecFin }}</td>
                                        <td>{{ $mois->MpNbPersonAdmDeb }}</td>
                                        <td>{{ $mois->MpNbPersonAdmFin }}</td>


                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $mois->MpNumero  }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$mois->MpNumero }}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($mois->MpStatut == 'E')
                                                <a onclick="clotureItem('{{ $mois->MpNumero  }}')" class="danger p-0" id="cloturer">
                                                    <i class="ft-lock font-medium-3 mr-2"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- CREATE -->
<div class="modal fade text-left" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('mois-paie.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN MOIS DE PAIE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="MpIdMois">Mois de paie <abbr style="color: #bd4147;">(*)</abbr></label>
                                @if(!$last_mois_paie)
                                    <select class="form-control" id="MpIdMois" name="MpIdMois" required onchange="updateAllFields()">
                                        <option selected value="">--Selectionner--</option>
                                        @for($i = 1; $i <= 12; $i++)

                                            @php
                                                $mois = [
                                                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                                ];
                                            @endphp

                                            <option value="{{ $i }}">{{ $mois[$i] }}</option>
                                        @endfor
                                    </select>
                                @else
                                    <input type="hidden" id="MpIdMois" class="form-control" name="MpIdMois" value="{{ $last_mois_paie->MpIdMois + 1 <= 12 ? $last_mois_paie->MpIdMois + 1 : 1 }}"/>
                                    <input type="text" class="form-control" value="{{ afficherMoisEnLettre($last_mois_paie->MpIdMois + 1 <= 12 ? $last_mois_paie->MpIdMois + 1 : 1) }}" readonly/>
                                @endif
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="MpAnnee">Année du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                @if(!$last_mois_paie)
                                    <input type="text" id="MpAnnee" class="number form-control" name="MpAnnee" value="{{ date('Y') }}" placeholder="Année du mois" required maxlength="4" onchange="updateAllFields()"/>
                                @else
                                    <input type="text" id="MpAnnee" class="form-control" name="MpAnnee" value="{{ $last_mois_paie->MpIdMois == 12 ? $last_mois_paie->MpAnnee + 1 : $last_mois_paie->MpAnnee; }}" placeholder="Année du mois" readonly/>
                                @endif
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpLibelle">Intitulé du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                @if(!$last_mois_paie)
                                    <input type="text" id="MpLibelle" class="form-control" name="MpLibelle" placeholder="Intitulé du mois" readonly/>
                                @else
                                    @php
                                        $moisSuivant = $last_mois_paie->MpIdMois + 1 <= 12 ? $last_mois_paie->MpIdMois + 1 : 1;
                                        $anneeSuivante = $last_mois_paie->MpIdMois == 12 ? $last_mois_paie->MpAnnee + 1 : $last_mois_paie->MpAnnee;
                                        $moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                                            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                    @endphp
                                    <input type="text" id="MpLibelle" class="form-control" name="MpLibelle" value="{{ $moisNoms[$moisSuivant - 1] . ' ' . $anneeSuivante }}" readonly/>
                                @endif
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpDateDebut">Date de début du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                @if(!$last_mois_paie)
                                    <input type="date" id="MpDateDebut" class="form-control" name="MpDateDebut" required onchange="validateAndUpdateDateFin()"/>
                                @else
                                    <input type="date" id="MpDateDebut" class="form-control" name="MpDateDebut" value="{{ \Carbon\Carbon::parse($last_mois_paie->MpDateFin)->addDay()->format('Y-m-d') }}" readonly/>
                                @endif
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpDateFin">Date de fin du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" id="MpDateFin" class="form-control" name="MpDateFin" readonly required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpStatut">Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control" id="MpStatut" name="MpStatut" required>
                                    <option selected value="">--Selectionner--</option>
                                    <option value="I">Initialisé</option>
                                    <option value="E">En cours</option>
                                    <option value="C">Clôturé</option>
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="MpObserv">Observations sur le mois de paie</label>
                                <textarea rows="2" class="form-control" id="MpObserv" name="MpObserv" placeholder="Corps du message"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- CREATE -->

<!-- MODIFIER -->
<div class="modal fade text-left" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN MOIS DE PAIE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="MpIdMois">Mois de paie <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control MpIdMois" id="MpIdMois" name="MpIdMois" required onchange="updateAllFields()">
                                    <option selected value="">--Selectionner--</option>
                                    @for($i = 1; $i <= 12; $i++)

                                        @php
                                            $mois = [
                                                1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                                5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                                9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                            ];
                                        @endphp

                                        <option value="{{ $i }}">{{ $mois[$i] }}</option>
                                    @endfor
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="MpAnnee">Année du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="MpAnnee" class="number form-control MpAnnee" name="MpAnnee" value="{{ date('Y') }}" placeholder="Année du mois" required maxlength="4" onchange="updateAllFields()"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpLibelle">Intitulé du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="MpLibelle" class="form-control MpLibelle" name="MpLibelle" placeholder="Intitulé du mois" readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpDateDebut">Date de début du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" id="MpDateDebut " class="form-control MpDateDebut" name="MpDateDebut" required onchange="validateAndUpdateDateFin()"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpDateFin">Date de fin du mois <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" id="MpDateFin" class="form-control MpDateFin" name="MpDateFin" readonly required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="MpStatut">Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control MpStatut" id="MpStatut" name="MpStatut" required>
                                    <option selected value="">--Selectionner--</option>
                                    <option value="I">Initialisé</option>
                                    <option value="E">En cours</option>
                                    <option value="C">Clôturé</option>
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="MpObserv">Observations sur le mois de paie</label>
                                <textarea rows="2" class="form-control MpObserv" id="MpObserv" name="MpObserv" placeholder="Corps du message"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-info">Modifier <i class="fa fa-thumbs-o-up position-right"></i></button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- MODIFIER -->

<!-- CONSULTER -->
<div class="modal fade text-left" id="xlarge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS MOIS DE PAIE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Nom</th>
                                        <td id="nom"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant par défaut</th>
                                        <td id="montant"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Type</th>
                                        <td id="type"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Type de Personnel</th>
                                        <td id="type_personnel"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Profil</th>
                                        <td id="profil"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Statut</th>
                                        <td id="statut"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ft-x position-left"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- CONSULTER -->

@endsection

@section('add_js')

    <script>
        tippy('#view', {
            content: "Consulter",
            placement: 'top',
        });

        tippy('#editor', {
            content: "Modifier",
            placement: 'bottom',
        });

        tippy('#cloturer', {
            content: "Clôturé le mois",
            placement: 'top',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create,#edit,#xlarge,#cloturer").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        // Fonction principale qui regroupe toutes les mises à jour
        function updateAllFields() {
            updateLibelle();
            resetDateDebut();
        }

        // Mise à jour du libellé
        function updateLibelle() {
            var moisSelect = document.getElementById('MpIdMois');
            var anneeInput = document.getElementById('MpAnnee');
            var libelleInput = document.getElementById('MpLibelle');

            if(moisSelect && anneeInput && libelleInput) {
                var moisValue = moisSelect.value;
                var anneeValue = anneeInput.value;

                var moisEnLettres = [
                    '', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ];

                if (moisValue && anneeValue) {
                    libelleInput.value = moisEnLettres[parseInt(moisValue)] + ' ' + anneeValue;
                } else {
                    libelleInput.value = '';
                }
            }
        }

        // Mise à jour de la date de fin en fonction de la date de début
        function updateDateFin() {
            var dateDebutInput = document.getElementById('MpDateDebut');
            var dateFinInput = document.getElementById('MpDateFin');

            if (dateDebutInput && dateDebutInput.value && dateFinInput) {
                var dateDebut = new Date(dateDebutInput.value);

                // Vérifier que la date est valide
                if (!isNaN(dateDebut.getTime())) {
                    // Calculer la date de fin = jour précédent la date de début du mois suivant
                    var dateFin = new Date(dateDebut.getFullYear(), dateDebut.getMonth() + 1, dateDebut.getDate() - 1);
                    dateFinInput.value = formatDate(dateFin);
                }
            }
        }

        // Réinitialiser la date de début avec le jour suivant la date de fin du mois précédent ou 1er du mois
        function resetDateDebut() {
            var moisSelect = document.getElementById('MpIdMois');
            var anneeInput = document.getElementById('MpAnnee');
            var dateDebutInput = document.getElementById('MpDateDebut');

            @if(!$last_mois_paie)
                if(moisSelect && anneeInput && dateDebutInput) {
                    var moisValue = moisSelect.value;
                    var anneeValue = anneeInput.value;

                    if (moisValue && anneeValue) {
                        // Par défaut, mettre le 1er du mois
                        var dateDebut = new Date(parseInt(anneeValue), parseInt(moisValue) - 1, 1);
                        dateDebutInput.value = formatDate(dateDebut);

                        // Définir les limites du champ date
                        var premierJour = new Date(parseInt(anneeValue), parseInt(moisValue) - 1, 1);
                        var dernierJour = new Date(parseInt(anneeValue), parseInt(moisValue), 0);

                        dateDebutInput.min = formatDate(premierJour);
                        dateDebutInput.max = formatDate(dernierJour);

                        updateDateFin();
                    }
                }
            @endif
        }

        // Valider et mettre à jour la date de fin
        function validateAndUpdateDateFin() {
            var dateDebutInput = document.getElementById('MpDateDebut');
            var dateFinInput = document.getElementById('MpDateFin');
            var moisSelect = document.getElementById('MpIdMois');
            var anneeInput = document.getElementById('MpAnnee');

            if (dateDebutInput && dateDebutInput.value) {
                var dateDebut = new Date(dateDebutInput.value);

                // Calculer la date de fin = jour précédent la date de début du mois suivant
                var dateFin = new Date(dateDebut.getFullYear(), dateDebut.getMonth() + 1, dateDebut.getDate() - 1);
                dateFinInput.value = formatDate(dateFin);

                // Vérifier que la date de fin est dans le mois suivant
                if (moisSelect && anneeInput && moisSelect.value && anneeInput.value) {
                    var moisValue = parseInt(moisSelect.value);
                    var anneeValue = parseInt(anneeInput.value);

                    // Calculer le mois suivant
                    var moisSuivant = moisValue + 1;
                    var anneeSuivante = anneeValue;
                    if (moisSuivant > 12) {
                        moisSuivant = 1;
                        anneeSuivante = anneeValue + 1;
                    }

                    // Vérifier si la date de fin est bien dans le mois suivant
                    var dateFinMois = dateFin.getMonth() + 1;
                    var dateFinAnnee = dateFin.getFullYear();

                    if (dateFinMois !== moisSuivant || dateFinAnnee !== anneeSuivante) {
                        // Afficher un avertissement
                        var moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                                        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

                        var message = 'Attention : La date de fin (' + formatDateAffiche(dateFin) +
                                    ') n\'est pas dans le mois suivant (' + moisNoms[moisSuivant - 1] + ' ' + anneeSuivante + ').\n' +
                                    'La date de fin doit être le jour précédent la date de début du mois suivant.\n\n' +
                                    'Date de début : ' + formatDateAffiche(dateDebut) + '\n' +
                                    'Date de fin calculée : ' + formatDateAffiche(dateFin);

                        alert(message);

                        // Recalculer la date de fin correctement
                        dateFin = new Date(dateDebut.getFullYear(), dateDebut.getMonth() + 1, dateDebut.getDate() - 1);
                        dateFinInput.value = formatDate(dateFin);
                    }
                }
            }
        }

        // Formatage des dates pour l'affichage
        function formatDateAffiche(date) {
            var jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            var moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

            var jourSemaine = jours[date.getDay()];
            var jour = date.getDate();
            var mois = moisNoms[date.getMonth()];
            var annee = date.getFullYear();

            return jourSemaine + ' ' + jour + ' ' + mois + ' ' + annee;
        }

        // Formatage des dates pour les inputs
        function formatDate(date) {
            var annee = date.getFullYear();
            var mois = String(date.getMonth() + 1).padStart(2, '0');
            var jour = String(date.getDate()).padStart(2, '0');
            return annee + '-' + mois + '-' + jour;
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            @if(!$last_mois_paie)
                var moisSelect = document.getElementById('MpIdMois');
                var anneeInput = document.getElementById('MpAnnee');
                var dateDebutInput = document.getElementById('MpDateDebut');

                if (moisSelect && moisSelect.value) {
                    updateAllFields();
                }

                if (anneeInput && anneeInput.value && (!moisSelect || !moisSelect.value)) {
                    var moisActuel = new Date().getMonth() + 1;
                    if (moisSelect) {
                        moisSelect.value = moisActuel;
                        updateAllFields();
                    }
                }

                if (dateDebutInput) {
                    dateDebutInput.addEventListener('change', function() {
                        validateAndUpdateDateFin();
                    });
                }

                if (moisSelect) {
                    moisSelect.addEventListener('change', function() {
                        resetDateDebut();
                    });
                }

                if (anneeInput) {
                    anneeInput.addEventListener('change', function() {
                        resetDateDebut();
                    });
                }
            @else
                setTimeout(function() {
                    updateDateFin();
                }, 100);
            @endif
        });
    </script>

    <script type="text/javascript">
        function showItem(MpNumero) {
            $.ajax({
                url: '{{ route("m-paie.show", "") }}/' + MpNumero ,
                type: 'GET',
                success: function(data){

                    $('#nom').html(data.SoDesign)
                    $('#montant').html(data.SoMontantDef)

                    if((data.SoType) == 'S')
                    {
                        $('#type').text('Sanction');
                    }
                    else
                    {
                        $('#type').text('Observation');
                    }

                    if((data.SoIsOn) == 'Y')
                    {
                        $('#statut').text('Active');
                    }
                    else
                    {
                        $('#statut').text('Inactive');
                    }

                    if((data.SoTypePerson) == 'A')
                    {
                        $('#type_personnel').text('Administration');
                    }
                    else if((data.SoTypePerson) == 'S')
                    {
                        $('#type_personnel').text('Sécurité');
                    }
                    else
                    {
                        $('#type_personnel').text('Tous');
                    }

                    if((data.SoTypeProfil) == 'F')
                    {
                        $('#profil').text('Agent Fixe');
                    }
                    else if((data.SoTypeProfil) == 'R')
                    {
                        $('#profil').text('Rondier');
                    }
                    else if((data.SoTypeProfil) == 'B')
                    {
                        $('#profil').text('Stand Bye');
                    }
                    else if((data.SoTypeProfil) == 'E')
                    {
                        $('#profil').text('Chef d\'Equipe');
                    }
                    else if((data.SoTypeProfil) == 'C')
                    {
                        $('#profil').text('Maître Chien');
                    }
                    else if((data.SoTypeProfil) == 'SG')
                    {
                        $('#profil').text('Superv. Général');
                    }
                    else if((data.SoTypeProfil) == 'SA')
                    {
                        $('#profil').text('Superv. Adjoint');
                    }
                    else if((data.SoTypeProfil) == 'CT')
                    {
                        $('#profil').text('Contrôleur');
                    }
                    else if((data.SoTypeProfil) == 'CM')
                    {
                        $('#profil').text('Commando');
                    }
                    else if((data.SoTypeProfil) == 'PS')
                    {
                        $('#profil').text('Postulant');
                    }
                    else
                    {
                        $('#profil').text('Tous');
                    }

                    $('#xlarge').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function editItem(MpNumero) {
            $.ajax({
                url: '{{ route("m-paie.edit", "") }}/' + MpNumero ,
                type: 'GET',
                success: function(data){

                    var MpNumero =data.MpNumero ;

                    $(".form-edit").attr('action', '{{ route("m-paie.update", "") }}/' + MpNumero );
                    var form=$(".form-edit").attr('action');

                    $(".MpIdMois").val(data.MpIdMois);
                    $(".MpAnnee").val(data.MpAnnee);
                    $(".MpLibelle").val(data.MpLibelle);
                    $(".MpDateDebut").val(data.MpDateDebut);
                    $(".MpDateFin").val(data.MpDateFin);
                    $(".MpStatut").val(data.MpStatut);

                    $('#edit').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


@endsection

