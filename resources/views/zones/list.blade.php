@extends('layout.app')

@section('title', 'Liste des zones')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des zones</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une zone</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste des zones</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Ville</th>
                                    <th>Nb. Clients</th>
                                    <th>Nb. Sites</th>
                                    <th>Nb. Quartiers</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zones as $zone)
                                    <tr>
                                        <td>{{ $zone->ZoCode }}</td>
                                        <td>{{ $zone->ZoNom }}</td>
                                        <td>{{ $zone->ViNom }}</td>

                                        @php
                                            $count_clients = DB::table('sites_client')
                                                ->where('sites_client.ScZone', $zone->ZoNumero)
                                                ->where('sites_client.ScEntite', Auth::user()->entite)
                                                ->distinct('sites_client.ScClient')
                                                ->count('sites_client.ScClient');

                                            $count_sites_clients = DB::table('sites_client')
                                                ->where('sites_client.ScZone',$zone->ZoNumero)
                                                ->where('sites_client.ScEntite',Auth::user()->entite)
                                                ->count();

                                            $count_quartiers = DB::table('quartiers')
                                                ->where('quartiers.QuZone',$zone->ZoNumero)
                                                ->where('quartiers.QuEntite',Auth::user()->entite)
                                                ->count();
                                        @endphp

                                        <td>{{ $count_clients }}</td>
                                        <td>{{ $count_sites_clients }}</td>
                                        <td>{{ $count_quartiers }}</td>

                                        @if($zone->ZoIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $zone->ZoNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$zone->ZoNumero}}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($zone->ZoIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $zone->ZoNumero }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $zone->ZoNumero }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="deleteItem('{{ $zone->ZoNumero }}')" class="danger p-0" id="delete">
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

            <form method="POST" action="{{ route('zones.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE ZONE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ZoCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" style="text-transform: uppercase;" id="ZoCode" class="form-control" name="ZoCode" value="{{ old('ZoCode') }}" placeholder="Code" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ZoNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="ZoNom" class="form-control" name="ZoNom" value="{{ old('ZoNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ZoVille">Ville <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e1 form-control" id="ZoVille" name="ZoVille" required>
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

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="ZoIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="ZoIsOn" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline2">Inactif</label>
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
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE ZONE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ZoCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" style="text-transform: uppercase;" id="ZoCode" class="form-control ZoCode" name="ZoCode" value="{{ old('ZoCode') }}" placeholder="Code" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ZoNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="ZoNom" class="form-control ZoNom" name="ZoNom" value="{{ old('ZoNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="ZoVille">Ville <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="form-control ZoVille" id="ZoVille" name="ZoVille" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($villes_1 as $ville_1)
                                        <option value="{{ $ville_1->ViNumero }}">{{ $ville_1->ViNom }}</option>
                                    @endforeach
                                </select>
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
                                        <input type="radio" id="customRadioInline3" name="ZoIsOn" class="custom-control-input ZoIsOn_Y" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="ZoIsOn" class="custom-control-input ZoIsOn_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline4">Inactif</label>
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
                                        <th scope="row">Ville</th>
                                        <td id="ville"></td>
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
        function showItem(ZoNumero) {
            $.ajax({
                url: '{{ route("zone.show", "") }}/' + ZoNumero,
                type: 'GET',
                success: function(data){

                    $('#code').html(data.ZoCode)
                    $('#nom').html(data.ZoNom)
                    $('#ville').html(data.ViNom)

                    if((data.ZoIsOn) == 'Y')
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
        function editItem(ZoNumero) {
            $.ajax({
                url: '{{ route("zone.edit", "") }}/' + ZoNumero,
                type: 'GET',
                success: function(data){

                    var ZoNumero=data.ZoNumero;

                    $(".form-edit").attr('action', '{{ route("zone.update", "") }}/' + ZoNumero);
                    var form=$(".form-edit").attr('action');

                    $(".ZoCode").val(data.ZoCode);
                    $(".ZoNom").val(data.ZoNom);
                    $(".ZoVille").val(data.ZoVille);

                    if((data.ZoIsOn) =='Y')
                    {
                        $('.ZoIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ZoIsOn_N').attr('checked',true);
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
        function desactiveItem(ZoNumero) {
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
                    url: '{{ route("zone.desactiver", "") }}/' + ZoNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Zone désactivée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('zones.index') }}";
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
        function activeItem(ZoNumero) {
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
                    url: '{{ route("zone.activer", "") }}/' + ZoNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Zone activée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('zones.index') }}";
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
        function deleteItem(ZoNumero) {
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
                    url: '{{ route("zone.delete", "") }}/' + ZoNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Zone supprimée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('zones.index') }}";
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
            let url = "{{ route('zone.imprimer') }}";
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>


@endsection

