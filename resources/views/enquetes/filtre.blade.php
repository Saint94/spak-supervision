@extends('layout.app')

@section('title', 'Liste des enquêtes clients')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des enquêtes clients</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une enquête client</button>

                            <form action="{{ route('enquetes-clients.filtre') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="">Période du</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="">Au</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="numero_client">Clients</label>
                                            <select name="numero_client" class="form-control e1">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($clients_f as $client_f)
                                                    <option value="{{ $client_f->ClNumero }}" {{ $client_f->ClNumero == $numero_client ? 'selected' : '' }}>{{ $client_f->ClDesign }}</option>
                                                @endforeach
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
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Type</th>
                                        <th>Niveau satisfaction</th>
                                        <th>Date rappel</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enquetes_client as $enquete_client)
                                        <tr>
                                            <td>{{ $enquete_client->ClDesign }}</td>

                                            @if($enquete_client->EcDate != null)
                                                <td>{{ date('d/m/Y',strtotime($enquete_client->EcDate)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $enquete_client->EcHeure }}</td>

                                            @if($enquete_client->EcType == 'CQ')
                                                <td>Contrôlé Qualité</td>
                                            @elseif($enquete_client->EcType == 'IS')
                                                <td>Indice de Satisfaction</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $enquete_client->EcNivSatisf }}</td>

                                            @if($enquete_client->EcDateRapp != null)
                                                <td>{{ date('d/m/Y',strtotime($enquete_client->EcDateRapp)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{ $enquete_client->EcNumero }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$enquete_client->EcNumero }}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                <a onclick="deleteItem('{{ $enquete_client->EcNumero  }}')" class="danger p-0" id="delete">
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

                <form method="POST" action="{{ route('enquetes-clients.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE ENQUÊTE CLIENT</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e2 form-control" id="EcClient" name="EcClient" required>
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

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="EcDate">Date <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="EcDate" name="EcDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="EcHeure">Heure <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control" id="EcHeure" name="EcHeure" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcType">Type <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e3 form-control" id="EcType" name="EcType" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="CQ">Contrôlé Qualité</option>
                                        <option value="IS">Indice de Satisfaction</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcNivSatisf">Niveau de satisfaction(1 à 10) <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="number form-control" id="EcNivSatisf" name="EcNivSatisf" value="{{ old('EcNivSatisf') }}" placeholder="Niveau de satisfaction(1 à 10)" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcDateRapp">Date de rappel<abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="EcDateRapp" name="EcDateRapp" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="EcRemarques">Remarques ou problèmes constatés <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <textarea rows="2" class="form-control" id="EcRemarques" name="EcRemarques" placeholder="Remarques ou problèmes constatés"></textarea>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <input type="file" class="filepond" id="EcChemDocEnq" name="EcChemDocEnq" accept="*"/>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE ENQUÊTE CLIENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e4 form-control EcClient" id="EcClient" name="EcClient" required>
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

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="EcDate">Date <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control EcDate" id="EcDate" name="EcDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="EcHeure">Heure <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control EcHeure" id="EcHeure" name="EcHeure" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcType">Type <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e5 form-control EcType" id="EcType" name="EcType" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="CQ">Contrôlé Qualité</option>
                                        <option value="IS">Indice de Satisfaction</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcNivSatisf">Niveau de satisfaction(1 à 10) <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="number form-control EcNivSatisf" id="EcNivSatisf" name="EcNivSatisf" value="{{ old('EcNivSatisf') }}" placeholder="Niveau de satisfaction(1 à 10)" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="EcDateRapp">Date de rappel<abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control EcDateRapp" id="EcDateRapp" name="EcDateRapp" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="EcRemarques">Remarques ou problèmes constatés <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <textarea rows="2" class="form-control EcRemarques" id="EcRemarques" name="EcRemarques" placeholder="Remarques ou problèmes constatés"></textarea>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <input type="file" class="filepond" id="EcChemDocEnqEdit" name="EcChemDocEnq" accept="*"/>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS ENQUÊTE CLIENT</h5>
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
                                            <th scope="row">Date</th>
                                            <td id="date"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Heure</th>
                                            <td id="heure"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Type</th>
                                            <td id="type"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Niveau satisfaction</th>
                                            <td id="niveau"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date rappel</th>
                                            <td id="date_rappel"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Remarques</th>
                                            <td id="reamrques"></td>
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
            $(".e1").select2();

            $(".e2,.e3").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(EcNumero) {
            $.ajax({
                url: '{{ route("enquete-client.show", "") }}/' + EcNumero ,
                type: 'GET',
                success: function(data){

                    $('#nom_client').html(data.ClDesign)
                    $('#date').html(data.EcDate)
                    $('#heure').html(data.EcHeure)
                    $('#niveau').html(data.EcNivSatisf)
                    $('#remarques').html(data.EcRemarques)
                    $('#date_rappel').html(data.EcDateRapp)

                    if((data.EcType) == 'CQ')
                    {
                        $('#type').text('Contrôlé Qualité');
                    }
                    else if((data.EcType) == 'IS')
                    {
                        $('#type').text('Indice de Satisfaction');
                    }
                    else
                    {
                        $('#type').text('');
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
        function editItem(EcNumero) {
            $.ajax({
                url: '{{ route("enquete-client.edit", "") }}/' + EcNumero,
                type: 'GET',
                success: function(data){

                    var EcNumero =data.EcNumero ;

                    $(".form-edit").attr('action', '{{ route("enquete-client.update", "") }}/' + EcNumero );
                    var form=$(".form-edit").attr('action');

                    $(".EcClient").val(data.EcClient);
                    $(".EcDate").val(data.EcDate);
                    $(".EcHeure").val(data.EcHeure);
                    $(".EcType").val(data.EcType);
                    $(".EcNivSatisf").val(data.EcNivSatisf);
                    $(".EcRemarques").val(data.EcRemarques);
                    $(".EcDateRapp").val(data.EcDateRapp);

                    $(".e4").select2({
                        width: '100%',
                    });

                    $(".e5").select2({
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
        function deleteItem(EcNumero ) {
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
                    url: '{{ route("enquete-client.supprimer", "") }}/' + EcNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Enquête client supprimée avec succès !',
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
            const inputImage = document.querySelector('input[id="EcChemDocEnq"]');

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
                //process: '/upload-doc-enquete',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            },
        });
    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputImageEdit = document.querySelector('input[id="EcChemDocEnqEdit"]');

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
                //process: '/upload-doc-enquete',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            },
        });
    </script>

@endsection
