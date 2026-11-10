@extends('layout.app')

@section('title', 'Liste des modes de paiement')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des modes de paiement</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un mode de paiement</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste des modes de paiement</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modes_paiement as $mode_paiement)
                                    <tr>
                                        <td>{{ $mode_paiement->MpNom }}</td>
                                     
                                        @if($mode_paiement->MpIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $mode_paiement->MpNumero  }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$mode_paiement->MpNumero }}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($mode_paiement->MpIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $mode_paiement->MpNumero  }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $mode_paiement->MpNumero  }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="deleteItem('{{ $mode_paiement->MpNumero  }}')" class="danger p-0" id="delete">
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
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('modes-paiement.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN MODE DE PAIEMENT</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="MpNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="MpNom" class="form-control" name="MpNom" value="{{ old('MpNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="MpIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="MpIsOn" class="custom-control-input" value="N"/>
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
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">

            <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN MODE DE PAIEMENT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="MpNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="MpNom" class="form-control MpNom" name="MpNom" value="{{ old('MpNom') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="MpIsOn" class="custom-control-input MpIsOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="MpIsOn" class="custom-control-input MpIsOn_N" value="N"/>
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
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS MODE DE PAIEMENT</h5>
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


    <script type="text/javascript">
        function showItem(MpNumero) {
            $.ajax({
                url: '{{ route("mode-paiement.show", "") }}/' + MpNumero ,
                type: 'GET',
                success: function(data){

                    $('#nom').html(data.MpNom)

                    if((data.MpIsOn) == 'Y')
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
        function editItem(MpNumero) {
            $.ajax({
                url: '{{ route("mode-paiement.edit", "") }}/' + MpNumero ,
                type: 'GET',
                success: function(data){

                    var MpNumero =data.MpNumero ;

                    $(".form-edit").attr('action', '{{ route("mode-paiement.update", "") }}/' + MpNumero );
                    var form=$(".form-edit").attr('action');

                    $(".MpNom").val(data.MpNom);

                    if((data.MpIsOn) =='Y')
                    {
                        $('.MpIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.MpIsOn_N').attr('checked',true);
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
        function desactiveItem(MpNumero ) {
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
                    url: '{{ route("mode-paiement.desactiver", "") }}/' + MpNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Mode de paiement désactivé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('modes-paiement.index') }}";
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
        function activeItem(MpNumero ) {
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
                    url: '{{ route("mode-paiement.activer", "") }}/' + MpNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Mode de paiement activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('modes-paiement.index') }}";
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
        function deleteItem(MpNumero ) {
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
                    url: '{{ route("mode-paiement.delete", "") }}/' + MpNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Mode de paiement supprimé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('modes-paiement.index') }}";
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
            let url = "{{ route('mode-paiement.imprimer') }}";
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection

