@extends('layout.app')

@section('title', 'Liste des villes')

@section('content')

@include('layout.alert')

<style>
    #zone-warning {
        margin-top: 5px;
        transition: opacity 0.3s ease;
    }
    .mode-superv-radio:disabled + label {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .custom-control-input:disabled ~ .custom-control-label {
        cursor: not-allowed;
    }
</style>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des villes</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une ville</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste des villes</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Responsable</th>
                                    <th>Nb. Clients</th>
                                    <th>Nb. Sites</th>
                                    <th>Nb. Zones</th>
                                    <th>Activer Zone ?</th>
                                    <th>Tous les sites ?</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($villes as $ville)
                                    <tr>
                                        <td>{{ $ville->ViCode }}</td>
                                        <td>{{ $ville->ViNom }}</td>
                                        <td>{{ $ville->PeNom.' '.$ville->PePrenom }}</td>

                                        @php
                                            $count_clients = DB::table('clients')
                                                ->where('clients.ClVilleSiege',$ville->ViNumero)
                                                ->where('clients.ClEntite',Auth::user()->entite)
                                                ->count();

                                            $count_sites_clients = DB::table('sites_client')
                                                ->where('sites_client.ScVille',$ville->ViNumero)
                                                ->where('sites_client.ScEntite',Auth::user()->entite)
                                                ->count();

                                            $count_zones = DB::table('zones')
                                                ->where('zones.ZoVille',$ville->ViNumero)
                                                ->where('zones.ZoEntite',Auth::user()->entite)
                                                ->count();
                                        @endphp

                                        <td>{{ $count_clients }}</td>
                                        <td>{{ $count_sites_clients }}</td>
                                        <td>{{ $count_zones }}</td>

                                        @if($ville->ViIsZoneOn == 'Y')
                                            <td>Oui</td>
                                        @else
                                            <td>Non</td>
                                        @endif

                                        @if($ville->ViIsAllSiteOn == 'Y')
                                            <td>Oui</td>
                                        @else
                                            <td>Non</td>
                                        @endif

                                        @if($ville->ViIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $ville->ViNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$ville->ViNumero}}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($ville->ViIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $ville->ViNumero }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $ville->ViNumero }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="deleteItem('{{ $ville->ViNumero }}')" class="danger p-0" id="delete">
                                                <i class="ft-trash-2 font-medium-3 mr-2"></i>
                                            </a>
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

            <form method="POST" action="{{ route('villes.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE VILLE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ViCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" style="text-transform: uppercase;" id="ViCode" class="form-control" name="ViCode" value="{{ old('ViCode') }}" placeholder="Code" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ViNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="ViNom" class="form-control" name="ViNom" value="{{ old('ViNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ViRespOper">Responsable </label>
                               <select class="e1 form-control" id="ViRespOper" name="ViRespOper">
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

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Activer Zone ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="ViIsZoneOn" class="custom-control-input zone-toggle" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="ViIsZoneOn" class="custom-control-input zone-toggle" value="N"/>
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
                                <label>Tous les sites ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="ViIsAllSiteOn" class="custom-control-input" value="N" checked/>
                                        <label class="custom-control-label" for="customRadioInline3">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="ViIsAllSiteOn" class="custom-control-input" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline4">Oui</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline5" name="ViIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline5">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline6" name="ViIsOn" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline6">Inactif</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-success" id="submit-btn">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
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
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE VILLE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ViCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" style="text-transform: uppercase;" id="ViCode" class="form-control ViCode" name="ViCode" value="{{ old('ViCode') }}" placeholder="Code" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ViNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="ViNom" class="form-control ViNom" name="ViNom" value="{{ old('ViNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ViRespOper">Responsable </label>
                               <select class="form-control ViRespOper" id="ViRespOper" name="ViRespOper">
                                    <option selected value="">Selectionner...</option>
                                    @foreach($personnels_1 as $personnel_1)
                                        <option value="{{ $personnel_1->PeNumero }}">{{ $personnel_1->PeNom.' '.$personnel_1->PePrenom }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Activer Zone ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline7" name="ViIsZoneOn" class="custom-control-input ViIsZoneOn_Y zone-toggle" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline7">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline8" name="ViIsZoneOn" class="custom-control-input ViIsZoneOn_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline8">Non</label>
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
                                <label>Tous les sites ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline9" name="ViIsAllSiteOn" class="custom-control-input ViIsAllSiteOn_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline9">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline10" name="ViIsAllSiteOn" class="custom-control-input ViIsAllSiteOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline10">Oui</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline11" name="ViIsOn" class="custom-control-input ViIsOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline11">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline12" name="ViIsOn" class="custom-control-input ViIsOn_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline12">Inactif</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-info" id="submit-btn">Modifier <i class="fa fa-thumbs-o-up position-right"></i></button>
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
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS VILLE</h5>
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
                                        <th scope="row">Code</th>
                                        <td id="code"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nom</th>
                                        <td id="nom"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Responsable</th>
                                        <td id="respo"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Activer Zone</th>
                                        <td id="statut_zone"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Tous les sites ?</th>
                                        <td id="all_sites"></td>
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

        tippy('#delete', {
            content: "Supprimer",
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
            $(".e1").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(ViNumero) {
            $.ajax({
                url: '{{ route("ville.show", "") }}/' + ViNumero,
                type: 'GET',
                success: function(data){

                    $('#code').html(data.ViCode)
                    $('#nom').html(data.ViNom)
                    $('#respo').html(data.PeNom+' '+data.PePrenom)

                    if((data.ViIsZoneOn) == 'Y')
                    {
                        $('#statut_zone').text('Oui');
                    }
                    else
                    {
                        $('#statut_zone').text('Non');
                    }

                    if((data.ViIsAllSiteOn) == 'Y')
                    {
                        $('#all_sites').text('Oui');
                    }
                    else
                    {
                        $('#all_sites').text('Non');
                    }

                    if((data.ViIsOn) == 'Y')
                    {
                        $('#statut').text('Actif');
                    }
                    else
                    {
                        $('#statut').text('Inactif');
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
        function editItem(ViNumero) {
            $.ajax({
                url: '{{ route("ville.edit", "") }}/' + ViNumero,
                type: 'GET',
                success: function(data){

                    var ViNumero=data.ViNumero;

                    $(".form-edit").attr('action', '{{ route("ville.update", "") }}/' + ViNumero);
                    var form=$(".form-edit").attr('action');

                    $(".ViCode").val(data.ViCode);
                    $(".ViNom").val(data.ViNom);
                    $(".ViRespOper").val(data.ViRespOper);

                    if((data.ViIsZoneOn) =='Y')
                    {
                        $('.ViIsZoneOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ViIsZoneOn_N').attr('checked',true);
                    }

                    if((data.ViIsAllSiteOn) =='Y')
                    {
                        $('.ViIsAllSiteOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ViIsAllSiteOn_N').attr('checked',true);
                    }

                    if((data.ViIsOn) =='Y')
                    {
                        $('.ViIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ViIsOn_N').attr('checked',true);
                    }

                    $('#edit').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>



    <script type="text/javascript">
        function desactiveItem(ViNumero) {
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
                    url: '{{ route("ville.desactiver", "") }}/' + ViNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Ville désactivée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('villes.index') }}";
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
        function activeItem(ViNumero) {
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
                    url: '{{ route("ville.activer", "") }}/' + ViNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Ville activée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('villes.index') }}";
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
        function deleteItem(ViNumero) {
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
                    url: '{{ route("ville.delete", "") }}/' + ViNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Ville supprimée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('villes.index') }}";
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

    <script>
        function imprimerAvecFiltres() {
            // Construire l'URL avec tous les paramètres
            let url = "{{ route('ville.imprimer') }}";
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection

