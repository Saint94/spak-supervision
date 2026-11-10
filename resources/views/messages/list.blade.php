@extends('layout.app')

@section('title', 'Liste des messages générique')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des messages générique</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un message générique</button>

                            <form action="{{ route('messages-generique.filtre') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="">Période du</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-d', strtotime('-3 months')) }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="">Au</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="client_message">Clients</label>
                                            <select name="client_message" class="form-control e5">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="type_message">Type de message</label>
                                            <select class="form-control e6" name="type_message">
                                                <option selected="selected" value="all">Tous</option>
                                                <option value="RS">Relevé simple</option>
                                                <option value="RA">Rappel avertissement</option>
                                                <option value="MD">Mise en demeure</option>
                                                <option value="RC">Résiliation de contrat</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-xl-2 col-md-2">
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
                                        <th>Client</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Date limite</th>
                                        <th>Titre</th>
                                        <th>Message</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages_generique as $message_generique)
                                        <tr>
                                            <td>{{ $message_generique->ClDesign }}</td>

                                            @if($message_generique->MgType == 'RS')
                                                <td>Relevé simple</td>
                                            @elseif($message_generique->MgType == 'RA')
                                                <td>Rappel avertissement</td>
                                            @elseif($message_generique->MgType == 'MD')
                                                <td>Mise en demeure</td>
                                            @elseif($message_generique->MgType == 'RC')
                                                <td>Résiliation de contrat</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($message_generique->MgDate != null)
                                                <td>{{ date('d/m/Y',strtotime($message_generique->MgDate)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ currency_xaf($message_generique->MgMontant) }}</td>

                                            @if($message_generique->MgDateLimite != null)
                                                <td>{{ date('d/m/Y',strtotime($message_generique->MgDateLimite)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $message_generique->MgTitre }}</td>
                                            <td>{{ $message_generique->MgTexteMsg }}</td>

                                            @if($message_generique->MgStatut == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{ $message_generique->MgNumero  }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$message_generique->MgNumero }}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($message_generique->MgStatut == 'Y')
                                                    <a onclick="desactiveItem('{{ $message_generique->MgNumero  }}')" class="danger p-0" id="desactiver">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{ $message_generique->MgNumero  }}')" class="success p-0" id="activer">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{ $message_generique->MgNumero  }}')" class="danger p-0" id="delete">
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

                <form method="POST" action="{{ route('messages-generique.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN MESSAGE GÉNÉRIQUE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e1 form-control" id="MgClient" name="MgClient" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgType">Type <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e2 form-control" id="MgType" name="MgType" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="RS">Relevé simple</option>
                                        <option value="RA">Rappel avertissement</option>
                                        <option value="MD">Mise en demeure</option>
                                        <option value="RC">Résiliation de contrat</option>
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
                                    <label for="MgDate">Date <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="MgDate" name="MgDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgMontant">Montant total <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="number form-control" id="MgMontant" name="MgMontant" value="{{ old('MgMontant') }}" placeholder="Montant total" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgDateLimite">Date limite<abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="MgDateLimite" name="MgDateLimite" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgTitre">Titre du message <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control" id="MgTitre" name="MgTitre" value="{{ old('MgTitre') }}" placeholder="Titre du message" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="MgStatut" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="MgStatut" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Inactif</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgTexteMsg">Corps du message</label>
                                    <textarea rows="2" class="form-control" id="MgTexteMsg" name="MgTexteMsg" placeholder="Corps du message"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <input type="file" class="filepond" id="MgChemDocFile" name="MgChemDocFile" accept="*"/>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN MESSAGE GÉNÉRIQUE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e3 form-control MgClient" id="MgClient" name="MgClient" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgType">Type <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e4 form-control MgType" id="MgType" name="MgType" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="RS">Relevé simple</option>
                                        <option value="RA">Rappel avertissement</option>
                                        <option value="MD">Mise en demeure</option>
                                        <option value="RC">Résiliation de contrat</option>
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
                                    <label for="MgDate">Date <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control MgDate" id="MgDate" name="MgDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgMontant">Montant total <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="number form-control MgMontant" id="MgMontant" name="MgMontant" value="{{ old('MgMontant') }}" placeholder="Montant total" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgDateLimite">Date limite<abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control MgDateLimite" id="MgDateLimite" name="MgDateLimite" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgTitre">Titre du message <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control MgTitre" id="MgTitre" name="MgTitre" value="{{ old('MgTitre') }}" placeholder="Titre du message" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="MgStatut" class="custom-control-input MgStatut_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="MgStatut" class="custom-control-input MgStatut_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Inactif</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MgTexteMsg">Corps du message</label>
                                    <textarea rows="2" class="form-control MgTexteMsg" id="MgTexteMsg" name="MgTexteMsg" placeholder="Corps du message"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <input type="file" class="filepond" id="MgChemDocFileEdit" name="MgChemDocFile" accept="*"/>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS MESSAGE GÉNÉRIQUE</h5>
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
                                            <td id="client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Type</th>
                                            <td id="type"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date</th>
                                            <td id="date"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Montant</th>
                                            <td id="montant"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date limite</th>
                                            <td id="date_limite"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Titre</th>
                                            <td id="titre"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Message</th>
                                            <td id="texte_msg"></td>
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
            $(".e1,.e2").select2({
                width: '100%',
                dropdownParent: $("#create")
            });

            $(".e5,.e6").select2();
        });
    </script>

    <script type="text/javascript">
        function showItem(MgNumero) {
            $.ajax({
                url: '{{ route("message-generique.show", "") }}/' + MgNumero ,
                type: 'GET',
                success: function(data){

                    $('#client').html(data.ClDesign)
                    $('#date').html(data.MgDate)
                    $('#montant').html(data.MgMontant)
                    $('#date_limite').html(data.MgDateLimite)
                    $('#titre').html(data.MgTitre)
                    $('#texte_msg').html(data.MgTexteMsg)

                    if((data.MgType) == 'RS')
                    {
                        $('#type').text('Relevé simple');
                    }
                    else if((data.MgType) == 'RA')
                    {
                        $('#type').text('Rappel avertissement');
                    }
                    else if((data.MgType) == 'MD')
                    {
                        $('#type').text('Mise en demeure');
                    }
                    else if((data.MgType) == 'RC')
                    {
                        $('#type').text('Résiliation de contrat');
                    }
                    else
                    {
                        $('#type').text('');
                    }

                    if((data.MgStatut) == 'Y')
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
        function editItem(MgNumero) {
            $.ajax({
                url: '{{ route("message-generique.edit", "") }}/' + MgNumero,
                type: 'GET',
                success: function(data){

                    var MgNumero =data.MgNumero ;

                    $(".form-edit").attr('action', '{{ route("message-generique.update", "") }}/' + MgNumero );
                    var form=$(".form-edit").attr('action');

                    $(".MgClient").val(data.MgClient);
                    $(".MgType").val(data.MgType);
                    $(".MgDate").val(data.MgDate);
                    $(".MgMontant").val(data.MgMontant);
                    $(".MgDateLimite").val(data.MgDateLimite);
                    $(".MgTitre").val(data.MgTitre);
                    $(".MgTexteMsg").val(data.MgTexteMsg);

                    if((data.MgStatut) =='Y')
                    {
                        $('.MgStatut_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.MgStatut_N').attr('checked',true);
                    }

                    $(".e3").select2({
                        width: '100%',
                    });

                    $(".e4").select2({
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
        function desactiveItem(MgNumero ) {
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
                    url: '{{ route("message-generique.desactiver", "") }}/' + MgNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Message générique désactivé avec succès !',
                            '',
                            'success'
                        )
                    window.location.reload();
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
        function activeItem(MgNumero ) {
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
                    url: '{{ route("message-generique.activer", "") }}/' + MgNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Message générique activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.reload();
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
        function deleteItem(MgNumero ) {
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
                    url: '{{ route("message-generique.supprimer", "") }}/' + MgNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Message générique supprimé avec succès !',
                            '',
                            'success'
                        )
                    window.location.reload();
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
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputImage = document.querySelector('input[id="MgChemDocFile"]');

            // Create a FilePond instance
            FilePond.create(inputImage,{
            labelIdle: `Faites glisser et déposez du document ou <span class="filepond--label-action">Parcourir</span>`,
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',

            server: {
                process: 'https://diffapps.diff-itc.net/spak-supervision/upload-doc-message',
                //process: '/upload-doc-message',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            },
        });
    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputImageEdit = document.querySelector('input[id="MgChemDocFileEdit"]');

            // Create a FilePond instance
            FilePond.create(inputImageEdit,{
            labelIdle: `Faites glisser et déposez du document ou <span class="filepond--label-action">Parcourir</span>`,
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',

            server: {
                process: 'https://diffapps.diff-itc.net/spak-supervision/upload-doc-message',
                //process: '/upload-doc-message',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            },
        });
    </script>

@endsection
