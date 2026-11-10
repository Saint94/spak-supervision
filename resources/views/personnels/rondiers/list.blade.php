@extends('layout.app')

@section('title', 'Liste des repos donnés du rondier')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des repos donnés au rondier : </b>{{ $rondier->PeNom.' '.$rondier->PePrenom }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Donner un nouveau repos</button>

                            <form action="{{ route('rondiers.filtre') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="">Période du</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-01', strtotime('-2 months')) }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="">Au</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="actif_repos">Actif</label>
                                            <select id="actif_repos" name="actif_repos" class="form-control e7">
                                                <option selected="selected" value="all">Tous</option>
                                                <option value="Y">Oui</option>
                                                <option value="N">Non</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="">Afficher</label><br>
                                            <button type="submit" class="btn btn-danger"><i class="fa ft-filter"></i> Afficher</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Personnel</th>
                                        <th>Site client</th>
                                        <th>Jour repos</th>
                                        <th>Date début</th>
                                        <th>Statut</th>
                                        <th>Date de fin</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jours_repos as $jour_repos)
                                        <tr>
                                            <td>{{ $jour_repos->PeNom.' '.$jour_repos->PePrenom }}</td>
                                            <td>{{ $jour_repos->ScNomSite }}</td>

                                            @if($jour_repos->RtJourRepos == '1')
                                                <td>Lundi</td>
                                            @elseif($jour_repos->RtJourRepos == '2')
                                                <td>Mardi</td>
                                            @elseif($jour_repos->RtJourRepos == '3')
                                                <td>Mercredi</td>
                                            @elseif($jour_repos->RtJourRepos == '4')
                                                <td>Jeudi</td>
                                            @elseif($jour_repos->RtJourRepos == '5')
                                                <td>Vendredi</td>
                                            @elseif($jour_repos->RtJourRepos == '6')
                                                <td>Samedi</td>
                                            @elseif($jour_repos->RtJourRepos == '7')
                                                <td>Dimanche</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($jour_repos->RtDateDebut != null)
                                                <td>{{ date('d/m/Y',strtotime($jour_repos->RtDateDebut)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($jour_repos->RtIsOn == 'Y')
                                                <td><span class="badge badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge badge-danger">Inactif</span></td>
                                            @endif

                                            @if($jour_repos->RtDateFin != null)
                                                <td>{{ date('d/m/Y',strtotime($jour_repos->RtDateFin)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{ $jour_repos->RtNumero  }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$jour_repos->RtNumero }}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($jour_repos->RtIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{ $jour_repos->RtNumero }}')" class="danger p-0" id="desactiver">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{ $jour_repos->RtNumero }}')" class="success p-0" id="activer">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
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

                <form method="POST" action="{{ route('rondiers.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">DONNER UN NOUVEAU REPOS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" class="form-control" name="RtRondier" value="{{ $rondier->PeNumero }}"/>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaPoste">Rondier</label>
                                    <input type="text" class="form-control" value="{{ $rondier->PeNom.' '.$rondier->PePrenom }}" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RtPersonnel">Personnel bénéficiaire <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e1 form-control" id="RtPersonnel" name="RtPersonnel" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($personnels as $personnel)
                                            <option value="{{ $personnel->PeNumero }}">{{ $personnel->PeNom.' '.$personnel->PePrenom }}</option>
                                        @endforeach
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
                                    <label for="RtSiteClient">Site client concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e2 form-control" id="RtSiteClient" name="RtSiteClient" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($sites_client as $site_client)
                                            <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RtJourRepos">Selectionner le jour de repos <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e3 form-control" id="RtJourRepos" name="RtJourRepos" required>
                                        <option selected value="">Selectionner...</option>
                                        {{-- Affichage des jours disponibles --}}
                                        @foreach($jours_disponibles as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('RtJourRepos', $rondier->jour_repos ?? '') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
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
                                    <label for="RtDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" id="RtDateDebut" class="form-control" name="RtDateDebut" value="{{ date('Y-m-d') }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Cette programmation est-elle active ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="RtIsOn" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="RtIsOn" class="custom-control-input" value="N"/>
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
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RtDateFin">Date de fin</label>
                                    <input type="date" id="RtDateFin" class="form-control" name="RtDateFin" value="{{ old('RtDateFin') }}"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN REPOS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaPoste">Rondier</label>
                                    <input type="text" class="form-control" value="{{ $rondier->PeNom.' '.$rondier->PePrenom }}" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RtPersonnel">Personnel bénéficiaire <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e4 form-control RtPersonnel" id="RtPersonnel" name="RtPersonnel" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($personnels as $personnel)
                                            <option value="{{ $personnel->PeNumero }}">{{ $personnel->PeNom.' '.$personnel->PePrenom }}</option>
                                        @endforeach
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
                                    <label for="RtSiteClient">Site client concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e5 form-control RtSiteClient" id="RtSiteClient" name="RtSiteClient" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($sites_client as $site_client)
                                            <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RtJourRepos">Selectionner le jour de repos <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e6 form-control RtJourRepos" id="RtJourRepos" name="RtJourRepos" required>
                                        <option selected value="">Selectionner...</option>
                                        {{-- Affichage des jours disponibles --}}
                                        @foreach($jours_disponibles as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('RtJourRepos', $rondier->jour_repos ?? '') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
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
                                    <label for="RtDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" id="RtDateDebut" class="form-control RtDateDebut" name="RtDateDebut" value="{{ date('Y-m-d') }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Cette programmation est-elle active ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="RtIsOn" class="custom-control-input RtIsOn_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="RtIsOn" class="custom-control-input RtIsOn_N" value="N"/>
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
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RtDateFin">Date de fin</label>
                                    <input type="date" id="RtDateFin" class="form-control RtDateFin" name="RtDateFin" value="{{ old('RtDateFin') }}"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS REPOS RONDIER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Rondier</th>
                                            <td>{{ $rondier->PeNom.' '.$rondier->PePrenom }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Personnel bénéficiaire</th>
                                            <td id="personnel_beneficiaire"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Site client</th>
                                            <td id="site_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Jour repos</th>
                                            <td id="jour_repos"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Date de début</th>
                                            <td id="date_debut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Actif</th>
                                            <td id="actif"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de fin</th>
                                            <td id="date_fin"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date et Heure de fin</th>
                                            <td id="date_heure_fin"></td>
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

        tippy('#activer', {
            content: "Activer",
            placement: 'top',
        });

        tippy('#desactiver', {
            content: "Désactiver",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'bottom',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create,#edit,#xlarge").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1,.e2,.e3").select2({
                width: '100%',
                dropdownParent: $("#create")
            });

            $(".e7").select2();
        });
    </script>

    <script type="text/javascript">
        function showItem(RtNumero) {
            $.ajax({
                url: '{{ route("rondier.show", "") }}/' + RtNumero,
                type: 'GET',
                success: function(data){

                    $('#personnel_beneficiaire').html(data.PeNom+' '+data.PePrenom)
                    $('#site_client').html(data.ScNomSite)
                    $('#date_debut').html(data.RtDateDebut)
                    $('#date_fin').html(data.RtDateFin)
                    $('#date_heure_fin').html(data.RtDateHFin)

                    if((data.RtJourRepos) == '1')
                    {
                        $('#jour_repos').text('Lundi');
                    }
                    else if((data.RtJourRepos) == '2')
                    {
                        $('#jour_repos').text('Mardi');
                    }
                    else if((data.RtJourRepos) == '3')
                    {
                        $('#jour_repos').text('Mercredi');
                    }
                    else if((data.RtJourRepos) == '4')
                    {
                        $('#jour_repos').text('Jeudi');
                    }
                    else if((data.RtJourRepos) == '5')
                    {
                        $('#jour_repos').text('Vendredi');
                    }
                    else if((data.RtJourRepos) == '6')
                    {
                        $('#jour_repos').text('Samedi');
                    }
                    else if((data.RtJourRepos) == '7')
                    {
                        $('#jour_repos').text('Dimanche');
                    }
                    else
                    {
                        $('#jour_repos').text('');
                    }

                    if((data.RtIsOn) == 'Y')
                    {
                        $('#actif').text('Oui');
                    }
                    else
                    {
                        $('#actif').text('Non');
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
        function editItem(RtNumero) {
            $.ajax({
                url: '{{ route("rondier.edit", "") }}/' + RtNumero,
                type: 'GET',
                success: function(data){

                    var RtNumero=data.RtNumero;

                    $(".form-edit").attr('action', '{{ route("rondier.update", "") }}/' + RtNumero);
                    var form=$(".form-edit").attr('action');

                    $(".RtPersonnel").val(data.RtPersonnel);
                    $(".RtSiteClient").val(data.RtSiteClient);
                    $(".RtJourRepos").val(data.RtJourRepos);
                    $('.RtDateDebut').val(data.RtDateDebut);
                    $('.RtDateFin').val(data.RtDateFin);

                    if((data.RtIsOn) =='Y')
                    {
                        $('.RtIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.RtIsOn_N').attr('checked',true);
                    }

                    $(".e4,.e5,.e6").select2({
                        width: '100%'
                    });

                    $('#edit').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function desactiveItem(RtNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver cet élément",
                text: "Confirmez-vous la désactivation de cet élément ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Désactiver !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("rondier.desactiver", "") }}/' + RtNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Repos désactivé avec succès !',
                            '',
                            'success'
                        )
                        window.location.reload(0);
                    }
                });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Annulé',
                    '',
                    'error'
                    )
                }
            });
        };
    </script>

    <script type="text/javascript">
        function activeItem(RtNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer cet élément",
                text: "Confirmez-vous l'activation de cet élément ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Activer !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("rondier.activer", "") }}/' + RtNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Repos activé avec succès !',
                            '',
                            'success'
                        )
                        window.location.reload(0);
                    }
                });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Annulé',
                    '',
                    'error'
                    )
                }
            });

        };
    </script>

    <script type="text/javascript">
        function deleteItem(RtNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Supprimer cet élément",
                text: "Confirmez-vous la suppression de cet élément?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Supprimer !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("rondier.delete", "") }}/' + RtNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Repos supprimé avec succès !','','success')
                        window.location.reload(0);
                    }
                });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire('Annulé','','error')
                }
            });
        };
    </script>

@endsection

