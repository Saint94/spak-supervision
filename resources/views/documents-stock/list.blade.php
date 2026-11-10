@extends('layout.app')

@section('title', 'Liste des documents de stock')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des documents de stock</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">

                        <a href="{{ route('document-stock.create_entree') }}" class="btn btn-success mb-2"><i class="ft-log-in"></i>&nbsp;Nouveau document d'entrée</a>
                        <a href="{{ route('document-stock.create_sortie') }}" class="btn btn-danger mb-2"><i class="ft-log-out"></i>&nbsp;Nouveau document de sortie</a>

                        <form action="{{ route('document-stock.filtre') }}" method="POST" id="filter_form">
                            @csrf

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Du</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" max="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Au</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="DsNature">Nature du document</label>
                                        <select id="DsNature" name="DsNature" class="e1 form-control @error('DsNature') is-invalid @enderror">
                                            <option selected="selected" value="all">Tous</option>
                                            <option value="E" {{ old('DsNature') == 'E' ? 'selected' : '' }}>Entrée</option>
                                            <option value="S" {{ old('DsNature') == 'S' ? 'selected' : '' }}>Sortie</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
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
                                    <th>N°</th>
                                    <th>Date</th>
                                    <th>Nature</th>
                                    <th>Type</th>
                                    <th>Fournisseur/Bénéficiaire</th>
                                    <th>Réf document</th>
                                    <th>Montant HT</th>
                                    <th>Montant TTC</th>
                                    <th>Signature Agent</th>
                                    <th>Signature Gestionnaire</th>
                                    <th>Signature Responsable Op.</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="history">
                                @foreach ($documents_stocks as $key => $document)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{ date('d/m/Y', strtotime($document->DsDate)) }}</td>

                                        @if($document->DsNature == 'E')
                                            <td><span class="badge light badge-success">Entrée</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Sortie</span></td>
                                        @endif

                                        <td>
                                            @if($document->DsTypeDest == 'EPI')
                                                EPI
                                            @elseif($document->DsTypeDest == 'MB')
                                                Matériels de bureau
                                            @elseif($document->DsTypeDest == 'PM')
                                                Pièces de moto
                                            @elseif($document->DsTypeDest == 'PV')
                                                Pièces de véhicules
                                            @elseif($document->DsTypeDest == 'MC')
                                                Matériels de chantier
                                            @elseif($document->DsTypeDest == 'CI')
                                                Consommables informatiques
                                            @else

                                            @endif
                                        </td>

                                        <td>{{ $document->FoNom  }}</td>
                                        <td>{{ $document->DsRefer }}</td>
                                        <td>{{ currency_xaf($document->DsMontantHT) }}</td>
                                        <td>{{ currency_xaf($document->DsMontantTTC) }}</td>

                                        @if($document->DsIsAgentSigne == 'Y')
                                            <td><span class="badge light badge-success">Oui</span></td>
                                        @elseif($document->DsIsAgentSigne == 'N')
                                            <td><span class="badge light badge-danger">Non</span></td>
                                        @else
                                            <td>-</span></td>
                                        @endif

                                        @if($document->DsIsGestSigne == 'Y')
                                            <td><span class="badge light badge-success">Oui</span></td>
                                        @elseif($document->DsIsGestSigne == 'N')
                                            <td><span class="badge light badge-danger">Non</span></td>
                                        @else
                                            <td>-</span></td>
                                        @endif

                                        @if($document->DsIsRespOperSigne == 'Y')
                                            <td><span class="badge light badge-success">Oui</span></td>
                                        @elseif($document->DsIsRespOperSigne == 'N')
                                            <td><span class="badge light badge-danger">Non</span></td>
                                        @else
                                            <td>-</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $document->DsNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            @if($document->DsNature == 'E')
                                                <a href="{{ route('document-stock.edit_entree', crypter($document->DsNumero)) }}" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('document-stock.edit_sortie', crypter($document->DsNumero)) }}" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('document-stock.articles',crypter($document->DsNumero)) }}" class="success p-0" id="articles">
                                                <i class="ft-share-2 font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="{{ route('document-stock.pdf',crypter($document->DsNumero)) }}" class="danger p-0" id="imprimer" target="_blank">
                                                <i class="ft-file-text font-medium-3 mr-2"></i>
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

<!-- Consulter un document de stock -->
<div class="modal fade text-left" id="show_document_stock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title" id="myModalLabel16">INFORMATIONS DOCUMENT DE STOCK</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Date</th>
                                        <td id="date">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nature</th>
                                        <td id="nature">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Type destinataire</th>
                                        <td id="type_dest">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Fournisseur / Bénéficiaire</th>
                                        <td id="fourn_benef">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Référence document</th>
                                        <td id="refer">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Montant HT</th>
                                        <td id="montant_ht">–</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Montant TTC</th>
                                        <td id="montant_ttc">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Signature Agent</th>
                                        <td id="signature_agent">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Signature Gestionnaire</th>
                                        <td id="signature_gestionnaire">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Signature Responsable Op.</th>
                                        <td id="signature_responsable">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Créé par</th>
                                        <td id="cree_par">–</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Date de création</th>
                                        <td id="date_cree">–</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- Consulter un document de stock -->

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

        tippy('#articles', {
            content: "Liste articles",
            placement: 'top',
        });

        tippy('#imprimer', {
            content: "Imprimer",
            placement: 'bottom',
        });
    </script>

    <script type="text/javascript">
        function showItem(DsNumero) {
            $.ajax({
                url: '{{ route("document-stock.show", "") }}/' + DsNumero , // Use the named route here
                type: 'GET',
                success: function(data){
                    // Date
                    $('#date').html(data.DsDate);

                    // Nature du document
                    if(data.DsNature == 'E') {
                        $('#nature').html('<span class="badge light badge-success">Entrée</span>');
                    } else if(data.DsNature == 'S') {
                        $('#nature').html('<span class="badge light badge-danger">Sortie</span>');
                    } else {
                        $('#nature').html('-');
                    }

                    // Type destinataire
                    if(data.DsTypeDest == 'EPI') {
                        $('#type_dest').html('EPI');
                    } else if(data.DsTypeDest == 'MB') {
                        $('#type_dest').html('Matériels de bureau');
                    } else if(data.DsTypeDest == 'PM') {
                        $('#type_dest').html('Pièces de moto');
                    } else if(data.DsTypeDest == 'PV') {
                        $('#type_dest').html('Pièces de véhicules');
                    } else if(data.DsTypeDest == 'MC') {
                        $('#type_dest').html('Matériels de chantier');
                    } else if(data.DsTypeDest == 'CI') {
                        $('#type_dest').html('Consommables informatiques');
                    } else {
                        $('#type_dest').html('-');
                    }

                    // Fournisseur / Bénéficiaire
                    $('#fourn_benef').html(data.DsFournBenef || '-');

                    // Référence document
                    $('#refer').html(data.DsRefer || '-');

                    // Montants
                    if(data.DsMontantHT) {
                        $('#montant_ht').html(new Intl.NumberFormat('fr-FR').format(data.DsMontantHT) + ' FCFA');
                    } else {
                        $('#montant_ht').html('-');
                    }

                    if(data.DsMontantTTC) {
                        $('#montant_ttc').html(new Intl.NumberFormat('fr-FR').format(data.DsMontantTTC) + ' FCFA');
                    } else {
                        $('#montant_ttc').html('-');
                    }

                    // Signatures
                    if(data.DsIsAgentSigne == 'Y') {
                        $('#signature_agent').html('<span class="badge light badge-success">Signé</span>');
                    } else if(data.DsIsAgentSigne == 'N') {
                        $('#signature_agent').html('<span class="badge light badge-danger">Non signé</span>');
                    } else {
                        $('#signature_agent').html('-');
                    }

                    if(data.DsIsGestSigne == 'Y') {
                        $('#signature_gestionnaire').html('<span class="badge light badge-success">Signé</span>');
                    } else if(data.DsIsGestSigne == 'N') {
                        $('#signature_gestionnaire').html('<span class="badge light badge-danger">Non signé</span>');
                    } else {
                        $('#signature_gestionnaire').html('-');
                    }

                    if(data.DsIsRespOperSigne == 'Y') {
                        $('#signature_responsable').html('<span class="badge light badge-success">Signé</span>');
                    } else if(data.DsIsRespOperSigne == 'N') {
                        $('#signature_responsable').html('<span class="badge light badge-danger">Non signé</span>');
                    } else {
                        $('#signature_responsable').html('-');
                    }

                    // Créateur et date de création
                    $('#cree_par').html(data.nom_createur || '-');
                    $('#date_cree').html(data.DsDateHCree ? new Date(data.DsDateHCree).toLocaleString('fr-FR') : '-');

                    $('#show_document_stock').modal('show').fade('slow');
                },
                error: function(){
                    console.log("Something goes wrong");
                    Swal.fire('Erreur', 'Impossible de charger les informations', 'error');
                }
            });
        }
    </script>

    <script>
        $(document).ready(function(){
            $("#show_document_stock").modal({
                show: false,
                backdrop: 'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1").select2();
        });
    </script>
@endsection
