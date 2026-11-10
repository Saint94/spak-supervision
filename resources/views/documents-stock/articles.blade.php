@extends('layout.app')

@section('title', 'Liste des articles')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des articles</b></h4>
                    <h4 class="card-title">
                        <b>Date : </b>{{ date('d/m/Y',strtotime($document_stock->DsDate)) }},
                        <b>Référence : </b>{{ $document_stock->DsRefer }},
                        <b>Montant HT </b>{{ $document_stock->DsMontantHT }},
                        <b>Montant TTC </b>{{ $document_stock->DsMontantTTC }}
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">

                        <a href="{{ route('documents-stock.index') }}" class="btn btn-primary mb-2"><i class="ft-list"></i>&nbsp;Liste des documents</a>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Prix max</th>
                                    <th>Prix d'achat</th>
                                    <th>Qté stock</th>
                                    <th>Qté mvt</th>
                                    <th>Nouveau stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>{{ $article->ArNom }}</td>
                                        <td>{{ $article->ArType }}</td>
                                        <td>{{ $article->ArPrixAchatMax }}</td>
                                        <td>{{ $article->ArPrixAchatCons }}</td>
                                        <td>{{ $article->ArQteStock }}</td>
                                        <td>{{ $article->MsQuantite }}</td>
                                        <td>{{ $article->MsQteApres }}</td>
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


@endsection

