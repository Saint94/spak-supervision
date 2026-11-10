@extends('layout.app')

@section('title', 'Liste des quartiers')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des quartiers</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un quartier</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste des quartiers</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Ville</th>
                                    <th>Zone</th>
                                    <th>Nb. Clients</th>
                                    <th>Nb. Sites</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quartiers as $quartier)
                                    <tr>
                                        <td>{{ $quartier->QuNom }}</td>
                                        <td>{{ $quartier->ViNom }}</td>
                                        <td>{{ $quartier->ZoNom }}</td>

                                        @php
                                            $count_clients = DB::table('clients')
                                                ->join('sites_client','sites_client.ScClient','=','clients.ClNumero')
                                                ->where('sites_client.ScQuartier',$quartier->QuNumero)
                                                ->where('clients.ClEntite',Auth::user()->entite)
                                                ->count();

                                            $count_sites_clients = DB::table('sites_client')
                                                ->where('sites_client.ScQuartier',$quartier->QuNumero)
                                                ->where('sites_client.ScEntite',Auth::user()->entite)
                                                ->count();
                                        @endphp

                                        <td>{{ $count_clients }}</td>
                                        <td>{{ $count_sites_clients }}</td>

                                        @if($quartier->QuIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $quartier->QuNumero  }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$quartier->QuNumero }}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($quartier->QuIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $quartier->QuNumero  }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $quartier->QuNumero  }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="deleteItem('{{ $quartier->QuNumero  }}')" class="danger p-0" id="delete">
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

            <form method="POST" action="{{ route('quartiers.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN QUARTIER</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="QuNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="QuNom" class="form-control" name="QuNom" value="{{ old('QuNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="QuVille">Ville <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e1 form-control" id="QuVille" name="QuVille" required>
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
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6" id="afficher_zones">
                            <div class="form-group">
                                <label for="QuZone">Zone</label>
                               <select class="e2 form-control" id="QuZone" name="QuZone">
                                    <option selected value="">Selectionner...</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="QuIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="QuIsOn" class="custom-control-input" value="N"/>
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
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN QUARTIER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="QuNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="QuNom" class="form-control QuNom" name="QuNom" value="{{ old('QuNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="QuVille">Ville <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="form-control QuVille" id="QuVille" name="QuVille" required>
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
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6" id="afficher_zones">
                            <div class="form-group">
                                <label for="QuZone">Zone</label>
                               <select class="form-control QuZone" id="QuZone" name="QuZone" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($zones_1 as $zone_1)
                                        <option value="{{ $zone_1->ZoNumero }}">{{ $zone_1->ZoNom }}</option>
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
                                        <input type="radio" id="customRadioInline3" name="QuIsOn" class="custom-control-input QuIsOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="QuIsOn" class="custom-control-input QuIsOn_N" value="N"/>
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
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS QUARTIER</h5>
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
                                        <th scope="row">Ville</th>
                                        <td id="ville"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Zone</th>
                                        <td id="zone"></td>
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
            $(".e1,e2").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(QuNumero) {
            $.ajax({
                url: '{{ route("quartier.show", "") }}/' + QuNumero ,
                type: 'GET',
                success: function(data){

                    $('#nom').html(data.QuNom)
                    $('#ville').html(data.ViNom)
                    $('#zone').html(data.ZoNom)

                    if((data.QuIsOn) == 'Y')
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
        function editItem(QuNumero ) {
            $.ajax({
                url: '{{ route("quartier.edit", "") }}/' + QuNumero ,
                type: 'GET',
                success: function(data){

                    var QuNumero =data.QuNumero ;

                    $(".form-edit").attr('action', '{{ route("quartier.update", "") }}/' + QuNumero );
                    var form=$(".form-edit").attr('action');

                    $(".QuNom").val(data.QuNom);
                    $(".QuVille").val(data.QuVille);
                    $(".QuZone").val(data.QuZone);

                    if((data.QuIsOn) =='Y')
                    {
                        $('.QuIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.QuIsOn_N').attr('checked',true);
                    }

                    $(".QuVille").change(function()
                    {
                        var QuVille =  $(".QuVille").val();

                        $.get('{{ route("quartier.show_zones","") }}/'+QuVille, function(rep) {
                            $("#afficher_zones").html(rep);
                        });
                    });

                    if($(".QuVille").val() != null) {
                        var QuVille =  $(".QuVille").val();

                        $.get('{{ route("quartier.show_zones","") }}/'+QuVille, function(rep) {
                            $("#afficher_zones").html(rep);
                        });
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
        function desactiveItem(QuNumero ) {
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
                    url: '{{ route("quartier.desactiver", "") }}/' + QuNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Quartier désactivé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('quartiers.index') }}";
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
        function activeItem(QuNumero ) {
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
                    url: '{{ route("quartier.activer", "") }}/' + QuNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Quartier activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('quartiers.index') }}";
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
        function deleteItem(QuNumero ) {
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
                    url: '{{ route("quartier.delete", "") }}/' + QuNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Quartier supprimé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('quartiers.index') }}";
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
        $(document).ready(function() {
            $("#QuVille").change(function()
            {
                var QuVille =  $("#QuVille").val();

                $.get('{{ route("quartier.show_zones","") }}/'+QuVille, function(rep) {
                    $("#afficher_zones").html(rep);
                });
            });

            if($("#QuVille").val() != null) {
                var QuVille =  $("#QuVille").val();

                $.get('{{ route("quartier.show_zones","") }}/'+QuVille, function(rep) {
                    $("#afficher_zones").html(rep);
                });
            }
        });
    </script>

    <script>
        function imprimerAvecFiltres() {
            // Construire l'URL avec tous les paramètres
            let url = "{{ route('quartier.imprimer') }}";
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection

