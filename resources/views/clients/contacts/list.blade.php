@extends('layout.app')

@section('title', 'Liste des contacts client')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des contacts client</b></h4>
                        <h4 class="card-title">
                            <b>Code Client : </b>{{ $client->ClCode }},
                            <b>Noms Client : </b>{{ $client->ClDesign }},
                            <b>Tél Client : </b>{{ $client->ClTel1 }},
                            <b>Email Client : </b>{{ $client->ClEmail }},
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un contact client</button>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Site client</th>
                                        <th>Nom</th>
                                        <th>Fonction</th>
                                        <th>Tél 1</th>
                                        <th>Tél 1</th>
                                        <th>Email</th>
                                        <th>Statut</th>
                                        <th>Date Fin</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts_client as $contact_client)
                                        <tr>
                                            <td>{{ $contact_client->ScNomSite }}</td>
                                            <td>{{ $contact_client->CcNom }}</td>
                                            <td>{{ $contact_client->CcFonction }}</td>
                                            <td>{{ $contact_client->CcTel1 }}</td>
                                            <td>{{ $contact_client->CcTel2 }}</td>
                                            <td>{{ $contact_client->CcEMail }}</td>

                                            @if($contact_client->CcIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Suspendu</span></td>
                                            @endif

                                            @if($contact_client->CcDateFin != null)
                                                <td>{{ date('d/m/Y',strtotime($contact_client->CcDateFin)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{ $contact_client->CcNumero  }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$contact_client->CcNumero }}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>


                                                @if($contact_client->CcIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{ $contact_client->CcNumero  }}')" class="danger p-0" id="desactiver">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{ $contact_client->CcNumero  }}')" class="success p-0" id="activer">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{ $contact_client->CcNumero }}')" class="danger p-0" id="delete">
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

                <form method="POST" action="{{ route('client.store_contact_client') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN CONTACT CLIENT</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcNom">Nom du client</label>
                                    <input type="text" class="form-control" value="{{ $client->ClDesign }}" readonly/>
                                    <input type="hidden" id="CcClient" class="form-control" name="CcClient" value="{{ $client->ClNumero }}"/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcSiteContact">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e1 form-control" id="CcSiteContact" name="CcSiteContact" required>
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
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcNom">Nom du contact client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="CcNom" class="form-control" name="CcNom" value="{{ old('CcNom') }}" placeholder="Nom du contact client" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcFonction">Fonction du contact client</label>
                                    <input type="text" id="CcFonction" class="form-control" name="CcFonction" value="{{ old('CcFonction') }}" placeholder="Fonction du contact client"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcTel1">N° de téléphone 1</label>
                                    <input type="text" class="form-control" id="CcTel1" name="CcTel1" value="{{ old('CcTel1') }}" placeholder="N° de téléphone 1"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcTel2">N° de téléphone 2</label>
                                    <input type="text" class="form-control" id="CcTel2" name="CcTel2" value="{{ old('CcTel2') }}" placeholder="N° de téléphone 2"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcEMail">Email</label>
                                    <input type="text" class="form-control" id="CcEMail" name="CcEMail" value="{{ old('CcEMail') }}" placeholder="Email"/>
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
                                            <input type="radio" id="customRadioInline1" name="CcIsOn" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="CcIsOn" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Non</label>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN CONTACT CLIENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                           <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcNom">Nom du client</label>
                                    <input type="text" class="form-control" value="{{ $client->ClDesign }}" readonly/>
                                    <input type="hidden" id="CcClient" class="form-control" name="CcClient" value="{{ $client->ClNumero }}"/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcSiteContact">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e2 form-control CcSiteContact" id="CcSiteContact" name="CcSiteContact" required>
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
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcNom">Nom du contact client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="CcNom" class="form-control CcNom" name="CcNom" value="{{ old('CcNom') }}" placeholder="Nom du contact client" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcFonction">Fonction du contact client</label>
                                    <input type="text" id="CcFonction" class="form-control CcFonction" name="CcFonction" value="{{ old('CcFonction') }}" placeholder="Fonction du contact client"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcTel1">N° de téléphone 1</label>
                                    <input type="text" class="form-control CcTel1" id="CcTel1" name="CcTel1" value="{{ old('CcTel1') }}" placeholder="N° de téléphone 1"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcTel2">N° de téléphone 2</label>
                                    <input type="text" class="form-control CcTel2" id="CcTel2" name="CcTel2" value="{{ old('CcTel2') }}" placeholder="N° de téléphone 2"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CcEMail">Email</label>
                                    <input type="text" class="form-control CcEMail" id="CcEMail" name="CcEMail" value="{{ old('CcEMail') }}" placeholder="Email"/>
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
                                            <input type="radio" id="customRadioInline3" name="CcIsOn" class="custom-control-input CcIsOn_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="CcIsOn" class="custom-control-input CcIsOn_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline4">Non</label>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS CONTACT CLIENT</h5>
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
                                            <th scope="row">Client</th>
                                            <td id="nom_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Site client</th>
                                            <td id="nom_site_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nom</th>
                                            <td id="nom"></td>
                                        </tr>
                                        
                                        <tr>
                                            <th scope="row">Fonction</th>
                                            <td id="fonction"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Tél 1</th>
                                            <td id="tel1"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Tél 2</th>
                                            <td id="tel2"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Email</th>
                                            <td id="email"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Staut</th>
                                            <td id="statut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de fin</th>
                                            <td id="date_fin"></td>
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
            content: "Suspendre",
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
        function showItem(CcNumero) {
            $.ajax({
                url: '{{ route("contact-client.show", "") }}/' + CcNumero,
                type: 'GET',
                success: function(data){

                    $('#nom_client').html(data.ClDesign)
                    $('#nom_site_client').html(data.ScNomSite)
                    $('#nom').html(data.CcNom)
                    $('#fonction').html(data.CcFonction)
                    $('#tel1').html(data.CcTel1)
                    $('#tel2').html(data.CcTel2)
                    $('#email').html(data.CcEMail)
                    $('#date_fin').html(data.CcDateFin)

                    if((data.CcIsOn) == 'Y')
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
        function editItem(CcNumero) {
            $.ajax({
                url: '{{ route("client.edit_contact_client", "") }}/' + CcNumero,
                type: 'GET',
                success: function(data){

                    var CcNumero=data.CcNumero;

                    $(".form-edit").attr('action', '{{ route("client.update_contact_client", "") }}/' + CcNumero);
                    var form=$(".form-edit").attr('action');

                    $(".CcClient").val(data.CcClient);
                    $(".CcSiteContact").val(data.CcSiteContact);
                    $(".CcNom").val(data.CcNom);
                    $(".CcFonction").val(data.CcFonction);
                    $(".CcTel1").val(data.CcTel1);
                    $('.CcTel2').val(data.CcTel2)
                    $('.CcEMail').val($('#CcEMail').val())
                    $('.CcDateFin').val($('#CcDateFin').val())

                    if((data.CcIsOn) =='Y')
                    {
                        $('.CcIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.CcIsOn_N').attr('checked',true);
                    }
                    
                    $(".e2").select2({
                        width: '100%',
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
        function desactiveItem(CcNumero) {
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
                    url: '{{ route("client.desactiver_contact_client", "") }}/' + CcNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Contact client désactivé avec succès !','','success')
                        window.location.reload();
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

    <script type="text/javascript">
        function activeItem(CcNumero) {
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
                    url: '{{ route("client.activer_contact_client", "") }}/' + CcNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Contact client activé avec succès !','','success')
                        window.location.reload();
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

    <script type="text/javascript">
        function deleteItem(CcNumero) {
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
                    url: '{{ route("client.delete_contact_client", "") }}/' + CcNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Contact client supprimé avec succès !','','success')
                        window.location.reload();
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
