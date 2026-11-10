@extends('layout.app')

@section('title', 'Document avenant contrat')

@section('content')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Document avenant contrat</b></h4>
                        <h4 class="card-title">
                            <b>Client : </b>{{$avenant_contrat->ClDesign }},
                            <b>Réf : </b>{{ $avenant_contrat->AcRefAven }},
                            <b>Type : </b>{{ $avenant_contrat->AcType == 'C' ? 'Contrat':'Avenant' }},
                            <b>Date effet : </b>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateEffet)) }},
                            @if($avenant_contrat->AcDateFin != null)
                                <b>Date fin : </b>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateFin)) }},
                            @else
                                <b>Date fin : </b>,
                            @endif
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="row">
                                <div class="col-12">
                                    @if(isset($avenant_contrat->AcChemDocContrat) && !empty($avenant_contrat->AcChemDocContrat))
                                        @php
                                            $filePath = url('assets/documents/contrats/'.$avenant_contrat->AcChemDocContrat);
                                            $fileExtension = strtolower(pathinfo($avenant_contrat->AcChemDocContrat, PATHINFO_EXTENSION));
                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']);
                                            $isPdf = $fileExtension == 'pdf';
                                        @endphp

                                        @if($isImage)
                                            <!-- Affichage direct des images -->
                                            <div style="text-align: center; background: #f5f5f5; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                                                <img src="{{ $filePath }}" alt="Document" style="max-width: 100%; max-height: 700px;">
                                            </div>
                                        @else
                                            <!-- Affichage dans un iframe -->
                                            @if(!in_array($fileExtension, ['docx', 'doc']))
                                                <div style="width: 100%; height: 700px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;">
                                                    <iframe
                                                        src="{{ $filePath }}"
                                                        style="width: 100%; height: 100%; border: none;"
                                                        frameborder="0">
                                                    </iframe>
                                                </div>
                                            @endif


                                            <!-- Si c'est un docx, afficher une info supplémentaire -->
                                            @if(in_array($fileExtension, ['docx', 'doc']))
                                                <div class="alert alert-danger mt-3">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <strong>Attention :</strong> Les fichiers Word (.docx) peuvent ne pas s'afficher correctement dans le navigateur.
                                                    Téléchargez le fichier pour une meilleure visualisation.
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Boutons d'action -->
                                        <div class="mt-3">
                                            <a href="{{ $filePath }}"
                                               class="btn btn-primary"
                                               target="_blank">
                                                <i class="fa fa-external-link"></i> Ouvrir dans un nouvel onglet
                                            </a>
                                            <a href="{{ $filePath }}"
                                               class="btn btn-success"
                                               download>
                                                <i class="fa fa-download"></i> Télécharger
                                            </a>
                                        </div>

                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fa fa-warning"></i> Aucun fichier n'est attaché à cet avenant contrat.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
