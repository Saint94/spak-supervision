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
                            <h3 class="font-large-1 mb-0">{{ $count_clients }}</h3>
                            <span>CLIENTS</span>
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
                            <h3 class="font-large-1 mb-0">{{ $count_sites }}</h3>
                            <span>SITES</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-users font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart1" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-blackberry">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_clients_suspendus }}</h3>
                            <span>CLIENTS SUSPENDUS</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="ft-user-x font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart2" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-content">
                <div class="card-body pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">{{ $count_contrats_avenants }}</h3>
                            <span>CONTRATS & AVENANTS</span>
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
                            <h3 class="font-large-1 mb-0">{{ $count_contrats_non_couverts }}</h3>
                            <span>CONTRATS NON COUVERTS</span>
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

</div>
@endsection
