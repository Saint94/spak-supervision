@extends('layout.app')

@section('title', 'Liste des carrières')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des carrières</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une carrière</button>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Personnel</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Poste</th>
                                        <th>Ville</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carrieres as $carriere)
                                        <tr>
                                            <td>{{ $carriere->PeNom.' '.$carriere->PePrenom }}</td>
                                            <td>{{ date('d/m/Y', strtotime($carriere->CaDateDebut)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($carriere->CaDateFin)) }}</td>
                                            <td>{{ $carriere->CaPoste }}</td>
                                            <td>{{ $carriere->CaVille }}</td>

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{ $carriere->CaNumero  }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$carriere->CaNumero }}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>


                                                <a onclick="deleteItem('{{ $carriere->CaNumero }}')" class="danger p-0" id="delete">
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

                <form method="POST" action="{{ route('carrieres.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE CARRIÈRE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="CaAgent">Agent concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e1 form-control" id="CaAgent" name="CaAgent" required>
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
                                    <label for="CaDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" id="CaDateDebut" class="form-control" name="CaDateDebut" value="{{ date('Y-m-d') }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaDateFin">Date de fin</label>
                                    <input type="date" id="CaDateFin" class="form-control" name="CaDateFin" value="{{ old('CaDateFin') }}"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaPoste">Poste <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="CaPoste" class="form-control" name="CaPoste" value="{{ old('CaPoste') }}" placeholder="Poste" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaVille">Ville</label>
                                    <input type="text" id="CaVille" class="form-control" name="CaVille" value="{{ old('CaVille') }}" placeholder="Ville"/>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE CARRIÈRE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="CaAgent">Agent concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e2 form-control CaAgent" id="CaAgent" name="CaAgent" required>
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
                                    <label for="CaDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" id="CaDateDebut" class="form-control CaDateDebut" name="CaDateDebut" value="{{ date('Y-m-d') }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaDateFin">Date de fin</label>
                                    <input type="date" id="CaDateFin" class="form-control CaDateFin" name="CaDateFin" value="{{ old('CaDateFin') }}"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaPoste">Poste <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="CaPoste" class="form-control CaPoste" name="CaPoste" value="{{ old('CaPoste') }}" placeholder="Poste" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CaVille">Ville</label>
                                    <input type="text" id="CaVille" class="form-control CaVille" name="CaVille" value="{{ old('CaVille') }}" placeholder="Ville"/>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS CARRIÈRE</h5>
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
                                            <th scope="row">Agent</th>
                                            <td id="agent"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de début</th>
                                            <td id="date_debut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de fin</th>
                                            <td id="date_fin"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Poste</th>
                                            <td id="poste"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Ville</th>
                                            <td id="ville"></td>
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
            $(".e1").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(CaNumero) {
            $.ajax({
                url: '{{ route("carriere.show", "") }}/' + CaNumero,
                type: 'GET',
                success: function(data){

                    $('#agent').html(data.PeNom+' '+data.PePrenom)
                    $('#date_debut').html(data.CaDateDebut)
                    $('#date_fin').html(data.CaDateFin)
                    $('#poste').html(data.CaPoste)
                    $('#ville').html(data.CaVille)

                    $('#xlarge').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function editItem(CaNumero) {
            $.ajax({
                url: '{{ route("carriere.edit", "") }}/' + CaNumero,
                type: 'GET',
                success: function(data){

                    var CaNumero=data.CaNumero;

                    $(".form-edit").attr('action', '{{ route("carriere.update", "") }}/' + CaNumero);
                    var form=$(".form-edit").attr('action');

                    $(".CaAgent").val(data.CaAgent);
                    $(".CaDateDebut").val(data.CaDateDebut);
                    $(".CaDateFin").val(data.CaDateFin);
                    $(".CaPoste").val(data.CaPoste);
                    $('.CaVille').val(data.CaVille)

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
        function deleteItem(CaNumero) {
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
                    url: '{{ route("carriere.delete", "") }}/' + CaNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Carrière supprimée avec succès !','','success')
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

