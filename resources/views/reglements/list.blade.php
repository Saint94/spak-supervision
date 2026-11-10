@extends('layout.app')

@section('title', 'Liste des reglèments factures')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des reglèments factures</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un reglèment client</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste Reglèments Factures</button>

                        <form action="{{ route('reglements-clients.filtre') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Période du</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" max="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Au</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="numero_client">Clients</label>
                                        <select name="numero_client" class="form-control e1">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($clients_f as $client_f)
                                                <option value="{{ $client_f->ClNumero }}">{{ $client_f->ClDesign }}</option>
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
                                    <th>Crée par</th>
                                    <th>Client</th>
                                    <th>Num Facture</th>
                                    <th>Date</th>
                                    <th>Mt Solde à payer</th>
                                    <th>Mt payé</th>
                                    <th>Mode Règlement</th>
                                    <th>Réf Règlement</th>
                                    <th>Mt Solde après Règlement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reglements as $reglement)
                                    <tr>
                                        <td>{{ $reglement->noms.' '.$reglement->prenoms }}</td>
                                        <td>{{ $reglement->ClDesign }}</td>
                                        <td>{{ $reglement->FcNumFacture }}</td>

                                        @if($reglement->RfDate != null)
                                            <td>{{ date('d/m/Y',strtotime($reglement->RfDate)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ currency_xaf($reglement->RfMtAPayer) }}</td>
                                        <td>{{ currency_xaf($reglement->RfMtPaye) }}</td>

                                        @if($reglement->RfModeRegl == 'E')
                                            <td>Espèces</td>
                                        @elseif($reglement->RfModeRegl == 'C')
                                            <td>Chèque</td>
                                        @elseif($reglement->RfModeRegl == 'V')
                                            <td>Virement</td>
                                        @elseif($reglement->RfModeRegl == 'O')
                                            <td>Orange Money</td>
                                        @elseif($reglement->RfModeRegl == 'V')
                                            <td>MTN MoMo</td>
                                        @else
                                            <td></td>
                                        @endif


                                        <td>{{ $reglement->RfReferReglem }}</td>

                                        <td>{{ currency_xaf($reglement->RfNewSolde) }}</td>

                                        <td style="text-align:center;">
                                            <a onclick="showItem('{{ $reglement->RfNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$reglement->RfNumero }}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
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

            <form method="POST" action="{{ route('reglements-clients.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN REGLÈMENT CLIENT</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="FcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e2 form-control" id="FcClient" name="FcClient" required>
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

                        <div class="col-xl-6 col-md-6" id="afficher_factures_non_reglees">
                            <div class="form-group">
                                <label for="RfFacture">Factures non réglées <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e3 form-control" id="RfFacture" name="RfFacture" required>
                                    <option selected value="">Selectionner...</option>
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
                                <label for="RfDate">Date reglèment client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="RfDate" name="RfDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6" id="afficher_info_facture">
                            <div class="form-group">
                                <label for="">Mt Solde à payer de la Facture</label>
                                <input type="text" class="form-control" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="RfMtPaye">Montant Payé <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="RfMtPaye" name="RfMtPaye" value="{{ old('RfMtPaye') }}" placeholder="Montant Payé" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="RfModeRegl">Mode de Règlement <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e4 form-control" id="RfModeRegl" name="RfModeRegl" required>
                                    <option selected value="">Selectionner...</option>
                                    <option value="E">Espèces</option>
                                    <option value="C">Chèque</option>
                                    <option value="V">Virement</option>
                                    <option value="O">Orange Money</option>
                                    <option value="M">MTN MoMo</option>
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
                                <label for="RfReferReglem">Référence du Règlement</label>
                                <input type="text" class="form-control" id="RfReferReglem" name="RfReferReglem" value="{{ old('RfReferReglem') }}" placeholder="Référence du Règlement"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="RfNewSolde">Montant Solde après Règlement</label>
                                <input type="text" class="form-control" id="RfNewSoldeXAF" readonly/>
                                <input type="hidden" class="form-control" id="RfNewSolde" name="RfNewSolde"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="RfDetails">Notes du Règlement</label>
                                <textarea rows="2" class="form-control" id="RfDetails" name="RfDetails" placeholder="Commentaires"></textarea>
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
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">

            <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN REGLÈMENT CLIENT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="FcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e5 form-control FcClient" id="FcClient" name="FcClient" required>
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

                        <div class="col-xl-6 col-md-6" id="afficher_factures_non_reglees_edit">
                            <div class="form-group">
                                <label for="RfFacture">Factures non réglées <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e6 form-control RfFacture" id="RfFacture" name="RfFacture" required>
                                    <option selected value="">Selectionner...</option>
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
                                <label for="RfDate">Date reglèment client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control RfDate" id="RfDate" name="RfDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6" id="afficher_info_facture_edit">
                            <div class="form-group">
                                <label for="">Mt Solde à payer de la Facture</label>
                                <input type="text" class="form-control RfMtAPayer" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="RfMtPaye">Montant Payé <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control RfMtPaye" id="RfMtPaye" name="RfMtPaye" value="{{ old('RfMtPaye') }}" placeholder="Montant Payé" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="RfModeRegl">Mode de Règlement <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e7 form-control RfModeRegl" id="RfModeRegl" name="RfModeRegl" required>
                                    <option selected value="">Selectionner...</option>
                                    <option value="E">Espèces</option>
                                    <option value="C">Chèque</option>
                                    <option value="V">Virement</option>
                                    <option value="O">Orange Money</option>
                                    <option value="M">MTN MoMo</option>
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
                                <label for="RfReferReglem">Référence du Règlement</label>
                                <input type="text" class="form-control RfReferReglem" id="RfReferReglem" name="RfReferReglem" value="{{ old('RfReferReglem') }}" placeholder="Référence du Règlement"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="RfNewSolde">Montant Solde après Règlement</label>
                                <input type="text" class="form-control RfNewSolde" id="RfNewSoldeXAF" readonly/>
                                <input type="hidden" class="form-control RfNewSolde" id="RfNewSolde" name="RfNewSolde"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="RfDetails">Notes du Règlement</label>
                                <textarea rows="2" class="form-control RfDetails" id="RfDetails" name="RfDetails" placeholder="Commentaires"></textarea>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS REGLÈMENT CLIENT</h5>
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
                                        <td id="client_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Num Facture</th>
                                        <td id="num_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date Reglèment</th>
                                        <td id="date_reglement"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant Solde à payer</th>
                                        <td id="mt_solde_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant payé</th>
                                        <td id="mt_paye"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mode Reglèment</th>
                                        <td id="mode_reglement"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Référence Reglèment</th>
                                        <td id="ref_reglement"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nouveau Solde</th>
                                        <td id="new_solde"></td>
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

    <script type="text/javascript">
        function remove_space(val){
            val=val.split(" ");
            val=val.join("");
            return val;
        }
        function is_number(char){
            var array=new Array("0","1","2","3","4","5","6","7","8","9",".",",","-","+");
            var trouve=false;
            var k=0;
            while (k<13 && trouve==false){
                if(char==array[k])
                trouve=true;
                k++;
            }
            return trouve;
        }

        function Check_number(nombre){
            trouve=false;
            i=0;
            if(nombre.length!=0){
                trouve=true;
                while(i<nombre.length && trouve==true){
                if(!is_number(nombre[i]))
                    trouve=false;
                i++;
                }
            }
            return trouve;
        }

        function reverseString(str){
            str = str.split(""); //convert 'jQuery' to array
            str = str.reverse(); //reverse 'jQuery' order
            str = str.join(""); //then join the reverse order values together
            return str;
        }

        function montant_xaf(str){
            str=str.toString();
            str=reverseString(str);
            var size=str.length;
            montant="";
            j=0;
            if(size!=0){
                for(i=0;i<size;i++){
                    j++;
                    montant+=str[i];
                    if(j<size && (i+1)%3==0){
                        montant+=" ";
                    }
                }
                    montant=reverseString(montant);
                }
            return montant;
        }
    </script>

    <script>
        tippy('#view', {
            content: "Consulter",
            placement: 'top',
        });

        tippy('#editor', {
            content: "Modifier",
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

            $(".e2,.e3,.e4").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(RfNumero) {
            $.ajax({
                url: '{{ route("reglement-client.show", "") }}/' + RfNumero,
                type: 'GET',
                success: function(data){

                    $('#num_facture').html(data.RfFacture)
                    $('#client_facture').html(data.ClDesign)
                    $('#date_reglement').html(data.RfDate)
                    $('#mt_solde_facture').html(data.RfMtAPayer)
                    $('#mt_paye').html(data.RfMtPaye)
                    $('#ref_reglement').html(data.RfReferReglem)
                    $('#new_solde').html(data.RfNewSolde)

                    if((data.RfModeRegl) == 'E')
                    {
                        $('#mode_reglement').text('Espèces');
                    }
                    else if((data.RfModeRegl) == 'C')
                    {
                        $('#mode_reglement').text('Chèque');
                    }
                    else if((data.RfModeRegl) == 'V')
                    {
                        $('#mode_reglement').text('Virement');
                    }
                    else if((data.RfModeRegl) == 'O')
                    {
                        $('#mode_reglement').text('Orange Money');
                    }
                    else if((data.RfModeRegl) == 'M')
                    {
                        $('#mode_reglement').text('MTN MoMo');
                    }
                    else
                    {
                        $('#mode_reglement').text('');
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
        function editItem(RfNumero) {
            $.ajax({
                url: '{{ route("reglement-client.edit", "") }}/' + RfNumero,
                type: 'GET',
                success: function(data){

                    var RfNumero=data.RfNumero;

                    $(".form-edit").attr('action', '{{ route("reglement-client.update", "") }}/' + RfNumero);
                    var form=$(".form-edit").attr('action');

                    $(".FcClient").val(data.FcClient);
                    $(".RfDate").val(data.RfDate);
                    $(".RfMtAPayer").val(data.RfMtAPayer);
                    $(".RfMtPaye").val(data.RfMtPaye);
                    $(".RfModeRegl").val(data.RfModeRegl);
                    $(".RfReferReglem").val(data.RfReferReglem);
                    $(".RfNewSolde").val(data.RfNewSolde);
                    $(".RfDetails").val(data.RfDetails);


                    $(".FcClient").change(function()
                    {
                        var FcClient =  $(".FcClient").val();

                        $.get('{{ route("reglement-client.show_factures_client_edit","") }}/'+FcClient, function(rep) {
                            $("#afficher_factures_non_reglees_edit").html(rep);

                            $(".RfFacture").val(data.RfFacture);

                            $(".RfFacture").change(function()
                            {
                                var RfFacture =  $(".RfFacture").val();

                                $.get('{{ route("reglement-client.show_info_facture_edit","") }}/'+RfFacture, function(rep) {
                                    $("#afficher_info_facture_edit").html(rep);

                                    var RfMtAPayer = $(".RfMtAPayer").val();

                                    $(".RfMtPaye").keyup(function(){

                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    });

                                    if($(".RfMtPaye").val() != '')
                                    {
                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    }
                                });
                            });

                            if($(".RfFacture").val() != null) {
                                var RfFacture =  $(".RfFacture").val();

                                $.get('{{ route("reglement-client.show_info_facture_edit","") }}/'+RfFacture, function(rep) {
                                    $("#afficher_info_facture_edit").html(rep);

                                    var RfMtAPayer = $(".RfMtAPayer").val();

                                    $(".RfMtPaye").keyup(function(){

                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    });

                                    if($(".RfMtPaye").val() != '')
                                    {
                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    }
                                });
                            }

                        });
                    });

                    if($(".FcClient").val() != null) {
                        var FcClient =  $(".FcClient").val();

                        $.get('{{ route("reglement-client.show_factures_client_edit","") }}/'+FcClient, function(rep) {
                            $("#afficher_factures_non_reglees_edit").html(rep);

                            $(".RfFacture").val(data.RfFacture);

                            $(".RfFacture").change(function()
                            {
                                var RfFacture =  $(".RfFacture").val();

                                $.get('{{ route("reglement-client.show_info_facture_edit","") }}/'+RfFacture, function(rep) {
                                    $("#afficher_info_facture_edit").html(rep);

                                    var RfMtAPayer = $(".RfMtAPayer").val();

                                    $(".RfMtPaye").keyup(function(){

                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    });

                                    if($(".RfMtPaye").val() != '')
                                    {
                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    }
                                });
                            });

                            if($(".RfFacture").val() != null) {
                                var RfFacture =  $(".RfFacture").val();

                                $.get('{{ route("reglement-client.show_info_facture","") }}/'+RfFacture, function(rep) {
                                    $("#afficher_info_facture").html(rep);

                                    var RfMtAPayer = $(".RfMtAPayer").val();

                                    $(".RfMtPaye").keyup(function(){

                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    });

                                    if($(".RfMtPaye").val() != '')
                                    {
                                        var RfMtPaye = $(".RfMtPaye").val();

                                        if(RfMtPaye == '')
                                        {
                                            $('.RfNewSolde').val(RfMtAPayer);
                                            $('.RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                        }
                                        else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                        else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                        {
                                            $('.RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                            $('.RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                        }
                                        else
                                        {
                                            $('.RfNewSolde').val(0);
                                            $('.RfNewSoldeXAF').val(montant_xaf(0));
                                        }
                                    }
                                });
                            }
                        });
                    }


                    $(".e5").select2({
                        width: '100%',
                    });

                    $(".e6").select2({
                        width: '100%',
                    });

                    $(".e7").select2({
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
        $(document).ready(function() {
            $("#FcClient").change(function()
            {
                var FcClient =  $("#FcClient").val();

                $.get('{{ route("reglement-client.show_factures_client","") }}/'+FcClient, function(rep) {
                    $("#afficher_factures_non_reglees").html(rep);

                    $("#RfFacture").change(function()
                    {
                        var RfFacture =  $("#RfFacture").val();

                        $.get('{{ route("reglement-client.show_info_facture","") }}/'+RfFacture, function(rep) {
                            $("#afficher_info_facture").html(rep);

                            var RfMtAPayer = $("#RfMtAPayer").val();

                            $("#RfMtPaye").keyup(function(){

                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            });

                            if($("#RfMtPaye").val() != '')
                            {
                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            }
                        });
                    });

                    if($("#RfFacture").val() != null) {
                        var RfFacture =  $("#RfFacture").val();

                        $.get('{{ route("reglement-client.show_info_facture","") }}/'+RfFacture, function(rep) {
                            $("#afficher_info_facture").html(rep);

                            var RfMtAPayer = $("#RfMtAPayer").val();

                            $("#RfMtPaye").keyup(function(){

                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            });

                            if($("#RfMtPaye").val() != '')
                            {
                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            }
                        });
                    }

                });
            });

            if($("#FcClient").val() != null) {
                var FcClient =  $("#FcClient").val();

                $.get('{{ route("reglement-client.show_factures_client","") }}/'+FcClient, function(rep) {
                    $("#afficher_factures_non_reglees").html(rep);

                    $("#RfFacture").change(function()
                    {
                        var RfFacture =  $("#RfFacture").val();

                        $.get('{{ route("reglement-client.show_info_facture","") }}/'+RfFacture, function(rep) {
                            $("#afficher_info_facture").html(rep);

                            var RfMtAPayer = $("#RfMtAPayer").val();

                            $("#RfMtPaye").keyup(function(){

                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            });

                            if($("#RfMtPaye").val() != '')
                            {
                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            }
                        });
                    });

                    if($("#RfFacture").val() != null) {
                        var RfFacture =  $("#RfFacture").val();

                        $.get('{{ route("reglement-client.show_info_facture","") }}/'+RfFacture, function(rep) {
                            $("#afficher_info_facture").html(rep);

                            var RfMtAPayer = $("#RfMtAPayer").val();

                            $("#RfMtPaye").keyup(function(){

                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            });

                            if($("#RfMtPaye").val() != '')
                            {
                                var RfMtPaye = $("#RfMtPaye").val();

                                if(RfMtPaye == '')
                                {
                                    $('#RfNewSolde').val(RfMtAPayer);
                                    $('#RfNewSoldeXAF').val(montant_xaf(RfMtAPayer));
                                }
                                else if(parseFloat(RfMtPaye) == parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                                else if(parseFloat(RfMtPaye) < parseFloat(RfMtAPayer))
                                {
                                    $('#RfNewSolde').val(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye));
                                    $('#RfNewSoldeXAF').val(montant_xaf(parseFloat(RfMtAPayer) - parseFloat(RfMtPaye)));
                                }
                                else
                                {
                                    $('#RfNewSolde').val(0);
                                    $('#RfNewSoldeXAF').val(montant_xaf(0));
                                }
                            }
                        });
                    }
                });
            }
        });
    </script>

    <script>
        function imprimerAvecFiltres() {
            // Récupérer tous les valeurs des filtres
            const startDate = document.getElementById('start_date')?.value || '{{ date("Y-m-01") }}';
            const endDate = document.getElementById('end_date')?.value || '{{ date("Y-m-d") }}';
            const numero_client = document.getElementById('numero_client')?.value;

            // Construire l'URL avec tous les paramètres
            let url = "{{ route('poster-vigile.imprimer') }}?";
            url += `start_date=${encodeURIComponent(startDate)}`;
            url += `&end_date=${encodeURIComponent(endDate)}`;
            url += `&numero_client=${encodeURIComponent(numero_client)}`;
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection

