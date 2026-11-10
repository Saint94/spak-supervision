@extends('layout.app')

@section('title', 'Liste des sanctions et observations')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des sanctions et observations</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une sanction</button>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Nom</th>
                                    <th>Montant</th>
                                    <th>Type personnel</th>
                                    <th>Profil</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types_sanctions as $type_sanction)
                                    <tr>
                                        @if($type_sanction->SoType == 'S')
                                            <td>Sanction</td>
                                        @elseif($type_sanction->SoType == 'O')
                                            <td>Observation</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ $type_sanction->SoDesign }}</td>
                                        <td>{{ currency_xaf($type_sanction->SoMontantDef) }}</td>

                                        @if($type_sanction->SoTypePerson == 'A')
                                            <td>Administration</td>
                                        @elseif($type_sanction->SoTypePerson == 'S')
                                            <td>Sécurité</td>
                                        @else
                                            <td>Tous</td>
                                        @endif

                                        @if($type_sanction->SoTypeProfil == 'F')
                                            <td>Agent Fixe</td>
                                        @elseif($type_sanction->SoTypeProfil == 'R')
                                            <td>Rondier</td>
                                        @elseif($type_sanction->SoTypeProfil == 'B')
                                            <td>Stand Bye</td>
                                        @elseif($type_sanction->SoTypeProfil == 'E')
                                            <td>Chef d'Equipe</td>
                                        @elseif($type_sanction->SoTypeProfil == 'C')
                                            <td>Maître Chien</td>
                                        @elseif($type_sanction->SoTypeProfil == 'SG')
                                            <td>Superv. Général</td>
                                        @elseif($type_sanction->SoTypeProfil == 'SA')
                                            <td>Superv. Adjoint</td>
                                        @elseif($type_sanction->SoTypeProfil == 'CT')
                                            <td>Contrôleur</td>
                                        @elseif($type_sanction->SoTypeProfil == 'CM')
                                            <td>Commando</td>
                                        @elseif($type_sanction->SoTypeProfil == 'PS')
                                            <td>Postulant</td>
                                        @else
                                            <td>Tous</td>
                                        @endif

                                        @if($type_sanction->SoIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $type_sanction->SoNumero  }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$type_sanction->SoNumero }}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($type_sanction->SoIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $type_sanction->SoNumero  }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $type_sanction->SoNumero  }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="deleteItem('{{ $type_sanction->SoNumero  }}')" class="danger p-0" id="delete">
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

            <form method="POST" action="{{ route('types-sanction.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE SANCTION</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SoType">Type <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e1 form-control" id="SoType" name="SoType" required>
                                    <option selected value="">Selectionner...</option>
                                    <option value="S">Sanction</option>
                                    <option value="O">Observation</option>
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SoDesign">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="SoDesign" class="form-control" name="SoDesign" value="{{ old('SoDesign') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6" id="afficher_mt_defaut">
                            <div class="form-group">
                                <label for="SoMontantDef">Montant par défaut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="SoMontantDef" class="number form-control" name="SoMontantDef" value="{{ old('SoMontantDef') }}" placeholder="Montant par défaut" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SoTypePerson">Type de personnel <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e2 form-control" id="SoTypePerson" name="SoTypePerson" required>
                                    <option selected value="">Selectionner...</option>
                                    <option value="T">Tous</option>
                                    <option value="A">Administration</option>
                                    <option value="S">Sécurité</option>
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
                                <label for="SoTypeProfil">Profil <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e3 form-control" id="SoTypeProfil" name="SoTypeProfil">
                                    <option selected value="">Tous</option>
                                    <option value="F">Agent Fixe</option>
                                    <option value="R">Rondier</option>
                                    <option value="B">Stand Bye</option>
                                    <option value="E">Chef d'Equipe</option>
                                    <option value="C">Maître Chien</option>
                                    <option value="SG">Superv. Général</option>
                                    <option value="SA">Superv. Adjoint</option>
                                    <option value="CM">Commando</option>
                                    <option value="CT">Contrôleur</option>
                                    <option value="PS">Postulant</option>
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
                                        <input type="radio" id="customRadioInline1" name="SoIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="SoIsOn" class="custom-control-input" value="N"/>
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
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE SANCTION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SoType">Type <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e4 form-control SoType" id="SoType" name="SoType" required>
                                    <option selected value="">Selectionner...</option>
                                    <option value="S">Sanction</option>
                                    <option value="O">Observation</option>
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SoDesign">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="SoDesign" class="form-control SoDesign" name="SoDesign" value="{{ old('SoDesign') }}" placeholder="Nom" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xl-6 col-md-6" id="afficher_mt_defaut">
                            <div class="form-group">
                                <label for="SoMontantDef">Montant par défaut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="SoMontantDef" class="number form-control SoMontantDef" name="SoMontantDef" value="{{ old('SoMontantDef') }}" placeholder="Montant par défaut" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SoTypePerson">Type de personnel <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e5 form-control SoTypePerson" id="SoTypePerson" name="SoTypePerson" required>
                                    <option selected value="">Selectionner...</option>
                                    <option value="T">Tous</option>
                                    <option value="A">Administration</option>
                                    <option value="S">Sécurité</option>
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
                                <label for="SoTypeProfil">Profil <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e6 form-control SoTypeProfil" id="SoTypeProfil" name="SoTypeProfil">
                                    <option selected value="">Tous</option>
                                    <option value="F">Agent Fixe</option>
                                    <option value="R">Rondier</option>
                                    <option value="B">Stand Bye</option>
                                    <option value="E">Chef d'Equipe</option>
                                    <option value="C">Maître Chien</option>
                                    <option value="SG">Superv. Général</option>
                                    <option value="SA">Superv. Adjoint</option>
                                    <option value="CM">Commando</option>
                                    <option value="CT">Contrôleur</option>
                                    <option value="PS">Postulant</option>
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
                                        <input type="radio" id="customRadioInline1" name="SoIsOn" class="SoIsOn_Y custom-control-input" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="SoIsOn" class="SoIsOn_N custom-control-input" value="N"/>
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
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS SANCTION</h5>
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
                                        <th scope="row">Type</th>
                                        <td id="type"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nom</th>
                                        <td id="nom"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant par défaut</th>
                                        <td id="montant"></td>
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
            $(".e1,.e2,.e3").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#SoType').on('change', function() {
                const selectedValue = $(this).val();
                const montantInput = $('#SoMontantDef');
                const montantDiv = $('#afficher_mt_defaut');

                if (selectedValue === 'O') {
                    // Observation : readonly et non obligatoire
                    montantInput.prop('readonly', true);
                    montantInput.prop('required', false);
                    montantInput.val('');
                    montantInput.css('background-color', '#e9ecef');

                    // Cacher le message d'erreur
                    montantDiv.find('.invalid-feedback').hide();

                    // Supprimer la classe d'erreur si présente
                    montantInput.removeClass('is-invalid');
                } else {
                    // Sanction : obligatoire
                    montantInput.prop('readonly', false);
                    montantInput.prop('required', true);
                    montantInput.css('background-color', '');
                }
            });

            // État initial
            $('#SoType').trigger('change');
        });
    </script>

    <script type="text/javascript">
        function showItem(SoNumero) {
            $.ajax({
                url: '{{ route("type-sanction.show", "") }}/' + SoNumero ,
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
        function editItem(SoNumero) {
            $.ajax({
                url: '{{ route("type-sanction.edit", "") }}/' + SoNumero ,
                type: 'GET',
                success: function(data){

                    var SoNumero =data.SoNumero ;

                    $(".form-edit").attr('action', '{{ route("type-sanction.update", "") }}/' + SoNumero );
                    var form=$(".form-edit").attr('action');

                    $(".SoDesign").val(data.SoDesign);
                    $(".SoMontantDef").val(data.SoMontantDef);
                    $(".SoType").val(data.SoType);
                    $(".SoTypePerson").val(data.SoTypePerson);
                    $(".SoTypeProfil").val(data.SoTypeProfil);

                    if((data.SoIsOn) =='Y')
                    {
                        $('.SoIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.SoIsOn_N').attr('checked',true);
                    }

                    $(".e4").select2({
                        width: '100%',
                    });

                    $(".e5").select2({
                        width: '100%',
                    });

                    $(".e6").select2({
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
        function desactiveItem(SoNumero) {
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
                    url: '{{ route("type-sanction.desactiver", "") }}/' + SoNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Type de sanction désactivé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('types-sanction.index') }}";
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
        function activeItem(SoNumero) {
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
                    url: '{{ route("type-sanction.activer", "") }}/' + SoNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Type de sanction activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('types-sanction.index') }}";
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
        function deleteItem(SoNumero) {
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
                    url: '{{ route("type-sanction.delete", "") }}/' + SoNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Type de sanction supprimé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('types-sanction.index') }}";
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

@endsection

