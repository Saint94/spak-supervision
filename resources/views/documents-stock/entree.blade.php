@extends('layout.app')

@section('title', 'Mouvements entrées')

@section('content')

<section id="basic-form-layouts">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-form"><b>Création d'un document d'entrée en stock</b></h4>
                </div>
                <div class="card-content">
                    <div class="px-3">
                        <!-- Formulaire des informations du document -->
                        <form method="POST" action="{{ route('document-stock.store_entree') }}" class="form needs-validation" novalidate>
                            @csrf

                            <div class="form-body">
                                <hr>

                                <h4 class="form-section"><i class="ft-server"></i> Articles du document / Entrées</h4>
                                <div class="table-responsive">

                                    <button id="modal_launch" data-toggle="modal" data-target="#add_article" type="button" class="btn btn-primary mb-2">
                                        <i class="ft-plus"></i>&nbsp;Ajouter article
                                    </button>

                                    <table class="table table-striped table-bordered file-export">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Type</th>
                                                <th>Prix max</th>
                                                <th>Prix d'achat</th>
                                                <th>Qté stock</th>
                                                <th>Qté mvt</th>
                                                <th>Nouveau stock</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cart_articles_entree as $item)
                                            <tr>
                                                <td>{{ $item->options->ArNom }}</td>

                                                @if($item->options->ArType == 'EPI')
                                                    <td>EPI</td>
                                                @elseif($item->options->ArType == 'MB')
                                                    <td>Matériels de bureau</td>
                                                @elseif($item->options->ArType == 'PM')
                                                    <td>Pièces de moto</td>
                                                @elseif($item->options->ArType == 'PV')
                                                    <td>Pièces de véhicules</td>
                                                @elseif($item->options->ArType == 'MC')
                                                    <td>Matériels de chantier</td>
                                                @else
                                                    <td>Consommables informatiques</td>
                                                @endif

                                                <td><span style="color:green;"><b><i>Entrée en stock</i></b></span></td>
                                                <td>{{ currency_xaf($item->options->ArPrixAchatMax) }}</td>
                                                <td>{{ currency_xaf($item->options->MsPrixArticle) }}</td>
                                                <td>{{ $item->options->MsQuantite }}</td>
                                                <td>{{ $item->options->MsQteApres }}</td>
                                                <td style="text-align: center;">
                                                    <a href="javascript:void(0);" data-id="{{ $item->rowId }}" class="delete danger p-0" title="Supprimer">
                                                        <i class="ft-trash-2 font-medium-3"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <hr/>

                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label for="DsDate">Date <abbr style="color: #bd4147;">(*)</abbr></label>
                                            <input type="date" id="DsDate" class="form-control" name="DsDate" value="{{ date('Y-m-d') }}" required/>
                                            <div class="invalid-feedback">
                                                Ce champ est obligatoire.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label for="DsFournBenef">Fournisseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                            <select class="e2 form-control" id="DsFournBenef" name="DsFournBenef" required>
                                                <option selected value="">Selectionner...</option>
                                                @foreach($fournisseurs as $fournisseur)
                                                    <option value="{{ $fournisseur->FoNumero }}">{{ $fournisseur->FoNom }}</option>
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
                                            <label for="DsRefer">Numéro de référence <abbr style="color: #bd4147;">(*)</abbr></label>
                                            <input type="text" id="DsRefer" class="form-control" name="DsRefer" value="{{ old('DsRefer') }}" placeholder="Numéro de référence" required/>
                                            <div class="invalid-feedback">
                                                Ce champ est obligatoire.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6" id="fournisseur_associe_tva" style="display: flex; flex-wrap: wrap;">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="DsMontantHT">Montant HT de la facture</label>
                                                <input type="text" class="form-control" placeholder="Montant HT" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="DsMontantTTC">Montant TTC de la facture</label>
                                                <input type="text" class="form-control" placeholder="Montant TTC" readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label>L'agent a t-il signé le document ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                            <div class="input-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="customRadioInline1" name="DsIsAgentSigne" class="custom-control-input" value="Y" checked/>
                                                    <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="customRadioInline2" name="DsIsAgentSigne" class="custom-control-input" value="N"/>
                                                    <label class="custom-control-label" for="customRadioInline2">Non</label>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">
                                                Ce champ est obligatoire.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label>Le gestionnaire a t-il signé le document ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                            <div class="input-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="customRadioInline3" name="DsIsAgentSigne" class="custom-control-input" value="Y" checked/>
                                                    <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="customRadioInline4" name="DsIsGestSigne" class="custom-control-input" value="N"/>
                                                    <label class="custom-control-label" for="customRadioInline4">Non</label>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">
                                                Ce champ est obligatoire.
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label>Le responsable des opérations a t-il signé le document ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                            <div class="input-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="customRadioInline5" name="DsIsRespOperSigne" class="custom-control-input" value="Y" checked/>
                                                    <label class="custom-control-label" for="customRadioInline5">Oui</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="customRadioInline6" name="DsIsRespOperSigne" class="custom-control-input" value="N"/>
                                                    <label class="custom-control-label" for="customRadioInline6">Non</label>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">
                                                Ce champ est obligatoire.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('documents-stock.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
                                    @if(Cart::instance('entree_stock')->count() != 0)
                                        <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                                    @else
                                        <button disabled type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AJOUTER ARTICLE -->
<div class="modal fade text-left" id="add_article" role="dialog" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title" id="myModalLabel8">Ajouter un article</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="form_article" method="POST" action="#" class="form needs-validation" novalidate>
                    @csrf

                    <div class="form-body">
                        <div class="row">
                            <div class="col-xl-8 col-md-8">
                                <div class="form-group">
                                    <label for="MsArticle">Article <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e1 form-control" id="MsArticle" name="MsArticle" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($articles as $article)
                                            <option value="{{ $article->ArNumero }}">{{ $article->ArNom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="afficher_infos_article">
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Code article</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Nom article</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Type article</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Jaune/Noire activée ?</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Prix article conseillé</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Prix max article </label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Qte stock article</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="">Seuil stock article</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MsPrixArticle">Prix article <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="MsPrixArticle" class="form-control" name="MsPrixArticle" value="{{ old('MsPrixArticle') }}" placeholder="Prix article" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MsQuantite">Qte article <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="MsQuantite" class="form-control" name="MsQuantite" value="{{ old('MsQuantite') }}" placeholder="Quantité article" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Prix total</label>
                                    <input type="text" id="prix_total" class="form-control" placeholder="Prix total" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="MsQteApres">Qte après mouvement</label>
                                    <input type="text" id="MsQteApres" class="form-control" name="MsQteApres" value="{{ old('MsQteApres') }}" placeholder="Qte après mouvement" readonly/>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">Ajouter Article <i class="fa fa-thumbs-o-up position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- AJOUTER ARTICLE -->

@endsection

@section('add_js')

    <script>
        $(document).ready(function(){
            $("#add_article").modal({
                show: false,
                backdrop: 'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1").select2({
                width: '100%',
                dropdownParent: $("#add_article")
            });

            $(".e2").select2();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Fonction pour calculer le prix total et la quantité après mouvement
            function calculateTotals() {
                // Récupérer les valeurs
                var prix = parseFloat($("#MsPrixArticle").val()) || 0;
                var quantite = parseFloat($("#MsQuantite").val()) || 0;

                // Récupérer la quantité en stock depuis le champ en lecture seule
                var qteStock = parseFloat($("#qte_stock").val()) || 0;
                var prix_max = parseFloat($("#prix_max").val()) || 0;

                // Vérifier si le prix dépasse le prix_max
                if (prix > prix_max && prix_max > 0) {
                    // Alerter l'utilisateur
                    alert("Le prix saisi (" + prix + ") dépasse le prix maximum autorisé (" + prix_max + "). Veuillez saisir un prix inférieur ou égal à " + prix_max + ".");

                    // Remettre l'ancienne valeur ou la valeur max
                    var oldValue = $("#MsPrixArticle").data('old-value') || prix_max;
                    if (oldValue > prix_max) {
                        oldValue = prix_max;
                    }
                    $("#MsPrixArticle").val(oldValue);
                    prix = oldValue;
                }

                // Stocker la valeur actuelle pour référence future
                $("#MsPrixArticle").data('old-value', prix);

                // Calculer le prix total
                var prixTotal = prix * quantite;
                $("#prix_total").val(prixTotal.toFixed(0));

                // Calculer la quantité après mouvement (stock + quantité vendue/sortie)
                var qteApres = qteStock + quantite;
                $("#MsQteApres").val(qteApres.toFixed(0));
            }

            // Fonction pour valider le prix immédiatement (sans attendre le calcul)
            function validatePrix() {
                var prix = parseFloat($("#MsPrixArticle").val()) || 0;
                var prix_max = parseFloat($("#prix_max").val()) || 0;

                if (prix > prix_max && prix_max > 0) {
                    alert("Le prix ne peut pas dépasser " + prix_max);
                    $("#MsPrixArticle").val("");
                    calculateTotals();
                    return false;
                }
                return true;
            }

            // Chargement des informations de l'article
            $("#MsArticle").change(function() {
                var MsArticle = $("#MsArticle").val();

                if(MsArticle) {
                    $.get('{{ route("document-stock.show_info_article","") }}/'+MsArticle, function(rep) {
                        $("#afficher_infos_article").html(rep);

                        // Après le chargement des infos, attacher les événements de calcul
                        attachCalculationEvents();
                    });
                } else {
                    $("#afficher_infos_article").html('');
                    $("#prix_total").val('');
                    $("#MsQteApres").val('');
                }
            });

            // Fonction pour attacher les événements de calcul
            function attachCalculationEvents() {
                // Calculer lors de la saisie du prix avec validation
                $("#MsPrixArticle").on('keyup change', function() {
                    validatePrix();
                    calculateTotals();
                });

                // Calculer lors de la saisie de la quantité
                $("#MsQuantite").on('keyup change', function() {
                    calculateTotals();

                    // Optionnel: Vérifier si la quantité dépasse le stock
                    var quantite = parseFloat($(this).val()) || 0;
                    var qteStock = parseFloat($("#qte_stock").val()) || 0;
                });

                // Validation au moment de quitter le champ prix
                $("#MsPrixArticle").on('blur', function() {
                    validatePrix();
                });
            }

            // Chargement initial si un article est déjà sélectionné
            if($("#MsArticle").val() != null && $("#MsArticle").val() != "") {
                var MsArticle = $("#MsArticle").val();
                $.get('{{ route("document-stock.show_info_article","") }}/'+MsArticle, function(rep) {
                    $("#afficher_infos_article").html(rep);
                    attachCalculationEvents();
                    calculateTotals(); // Calcul initial
                });
            } else {
                attachCalculationEvents();
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            // Chargement des informations de l'article
            $("#DsFournBenef").change(function() {
                var DsFournBenef = $("#DsFournBenef").val();

                if(DsFournBenef) {
                    $.get('{{ route("document-stock.show_info_fournisseur","") }}/'+DsFournBenef, function(rep) {
                        $("#fournisseur_associe_tva").html(rep);
                    });
                } else {
                    $("#fournisseur_associe_tva").html('');
                }
            });

            // Chargement initial si un article est déjà sélectionné
            if($("#DsFournBenef").val() != null && $("#DsFournBenef").val() != "") {
                var DsFournBenef = $("#DsFournBenef").val();
                $.get('{{ route("document-stock.show_info_fournisseur","") }}/'+DsFournBenef, function(rep) {
                    $("#fournisseur_associe_tva").html(rep);
                });
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("#form_article").on('submit', function(e){
                e.preventDefault();

                var MsArticle = $("#MsArticle").val();
                var MsSensMvm = "E";
                var MsQuantite = $("#MsQuantite").val();
                var MsPrixArticle = $("#MsPrixArticle").val();
                var MsQteApres = $("#MsQteApres").val();
                var _token = $("input[name=_token]").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('cart-entree-stock.store') }}",
                    data: {
                        MsArticle: MsArticle,
                        MsSensMvm: MsSensMvm,
                        MsQuantite: MsQuantite,
                        MsPrixArticle: MsPrixArticle,
                        MsQteApres: MsQteApres,
                        _token : _token
                    },
                    success: function(response) {
                        if(response) {
                            $("#form_article")[0].reset();
                            window.location.reload(0);
                            $("#add_article").modal("hide");
                        }
                    }
                });
            })
        });
    </script>

    <script>
        $(".delete").on("click", function(){
            var rowId = $(this).attr("data-id");
            var _token = $("input[name=_token]").val();

            $.ajax({
                type: "POST",
                url: "{{ route('cart-entree-stock.supprimer') }}",
                data: {
                        rowId: rowId,
                        _token: _token
                    },
                success: function(response){
                    if (response) {
                        $("#form_article")[0].reset();
                        window.location.reload(0);
                        $("#add_article").modal("hide");
                    }
                }
            });
        });
    </script>


@endsection
