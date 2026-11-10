@extends('layout.app')

@section('title', 'Liste des fonctions')

@section('content')

@include('layout.alert')


<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des fonctions</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une fonction</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste des fonctions</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fonctions as $fonction)
                                    <tr>
                                        <td>{{ $fonction->FnCode }}</td>
                                        <td>{{ $fonction->FnNom }}</td>

                                        @if($fonction->FnIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $fonction->FnNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$fonction->FnNumero}}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($fonction->FnIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $fonction->FnNumero }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $fonction->FnNumero }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="deleteItem('{{ $fonction->FnNumero }}')" class="danger p-0" id="delete">
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

            <form method="POST" action="{{ route('fonctions.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE FONCTION</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="FnCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="FnCode" class="form-control" name="FnCode" value="{{ old('FnCode') }}" placeholder="Code" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="FnNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="FnNom" class="form-control" name="FnNom" value="{{ old('FnNom') }}" placeholder="Nom" required/>
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
                                        <input type="radio" id="customRadioInline5" name="FnIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline5">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline6" name="FnIsOn" class="custom-control-input" value="N"/>
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
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">

            <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE FONCTION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="FnCode">Code <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="FnCode" class="form-control FnCode" name="FnCode" value="{{ old('FnCode') }}" placeholder="Code" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="FnNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="FnNom" class="form-control FnNom" name="FnNom" value="{{ old('FnNom') }}" placeholder="Nom" required/>
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
                                        <input type="radio" id="customRadioInline11" name="FnIsOn" class="custom-control-input FnIsOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline11">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline12" name="FnIsOn" class="custom-control-input FnIsOn_N" value="N"/>
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
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS SECTEUR D'ACTIVITÉ</h5>
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
        function showItem(FnNumero) {
            $.ajax({
                url: '{{ route("fonction.show", "") }}/' + FnNumero,
                type: 'GET',
                success: function(data){

                    $('#code').html(data.FnCode)
                    $('#nom').html(data.FnNom)

                    if((data.FnIsOn) == 'Y')
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
        function editItem(FnNumero) {
            $.ajax({
                url: '{{ route("fonction.edit", "") }}/' + FnNumero,
                type: 'GET',
                success: function(data){

                    var FnNumero=data.FnNumero;

                    $(".form-edit").attr('action', '{{ route("fonction.update", "") }}/' + FnNumero);
                    var form=$(".form-edit").attr('action');

                    $(".FnCode").val(data.FnCode);
                    $(".FnNom").val(data.FnNom);
                    
                    if((data.FnIsOn) =='Y')
                    {
                        $('.FnIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.FnIsOn_N').attr('checked',true);
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
        function desactiveItem(FnNumero) {
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
                    url: '{{ route("fonction.desactiver", "") }}/' + FnNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Fonction désactivée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('fonctions.index') }}";
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
        function activeItem(FnNumero) {
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
                    url: '{{ route("fonction.activer", "") }}/' + FnNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Fonction activée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('fonctions.index') }}";
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
        function deleteItem(FnNumero) {
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
                    url: '{{ route("fonction.delete", "") }}/' + FnNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Fonction supprimée avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('fonctions.index') }}";
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
            let url = "{{ route('fonction.imprimer') }}";
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection

