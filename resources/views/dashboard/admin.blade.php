@extends('layout.app')

@section('title', 'Tableau de bord')

@section('content')

<div class="row">

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-green-tea">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_entites }}</h3>
                            <span>ENTITÉS</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-file-text font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-blackberry">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_managers }}</h3>
                            <span>SUPER MANAGERS</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart2" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-blackberry">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_responsables_operation }}</h3>
                            <span>RESPONSABLES OPÉRATIONS</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart3" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_agents_superviseur }}</h3>
                            <span>AGENTS SUPERVISEUR</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart4" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-green-tea">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_chefs_equipe }}</h3>
                            <span>CHEF D'EQUIPE</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart5" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-blackberry">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_agents_paie }}</h3>
                            <span>AGENTS PAIE</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart6" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_responsables_com }}</h3>
                            <span>RESPONSABLES COMMERCIALES</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart7" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_agents_fact }}</h3>
                            <span>AGENTS FACTURATION</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart8" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-large-1 mb-0">{{ $nbre_postes_visites_jour->total ?? 0 }}</h4>
                            <span>POSTES VISITÉS JOUR</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart17" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-large-1 mb-0">{{ $nbre_postes_visites_nuit->total ?? 0 }}</h4>
                            <span>POSTES VISITÉS NUIT</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart18" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-large-1 mb-0">{{ $nbre_postes_non_couverts_jour }}</h4>
                            <span>POSTES NON COUVERTS JOUR</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart9" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-large-1 mb-0">{{ $nbre_postes_non_couverts_nuit }}</h4>
                            <span>POSTES NON COUVERTS NUIT</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart10" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-large-1 mb-0">{{ $nbre_postes_non_supervises_jour }}</h4>
                            <span>POSTES NON VISITÉS JOUR</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart11" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-large-1 mb-0">{{ $nbre_postes_non_supervises_nuit }}</h4>
                            <span>POSTES NON VISITÉS NUIT</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart12" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

</div>

@endsection
