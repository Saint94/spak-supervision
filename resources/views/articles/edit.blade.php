@extends('layout.app')

@section('title', 'Modifier article')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Modification de l'article</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('articles.update', $article->ArNumero) }}" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                @if($article->ArPhoto)
                                    <div class="card-img">
                                        <img class="card-img-top img-fluid" src="{{ url('assets/img/articles/'.$article->ArPhoto) }}" alt="{{ $article->ArNom }}" style="height:100px;width:150px"/>
                                    </div>
                                @else
                                    <div class="card-img">
                                        <img class="card-img-top img-fluid" src="{{ url('assets/img/default.jpg') }}" alt="{{ $article->ArNom }}" style="height:100px;width:150px">
                                    </div>
                                @endif

                                <div class="form-body">
                                    <hr>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArCode">Code article <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="ArCode" name="ArCode" value="{{ old('ArCode', $article->ArCode) }}" placeholder="Code article" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArNom">Nom ou désignation article <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="ArNom" name="ArNom" value="{{ old('ArNom', $article->ArNom) }}" placeholder="Nom ou désignation article" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArType">Type article <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e1 form-control" id="ArType" name="ArType" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="EPI" {{ $article->ArType == "EPI" ? 'selected="selected"':'' }}>EPI</option>
                                                    <option value="MB" {{ $article->ArType == "MB" ? 'selected="selected"':'' }}>Matériels de bureau</option>
                                                    <option value="PM" {{ $article->ArType == "PM" ? 'selected="selected"':'' }}>Pièces de moto</option>
                                                    <option value="PV" {{ $article->ArType == "PV" ? 'selected="selected"':'' }}>Pièces de véhicules</option>
                                                    <option value="MC" {{ $article->ArType == "MC" ? 'selected="selected"':'' }}>Matériels de chantier</option>
                                                    <option value="CI" {{ $article->ArType == "CI" ? 'selected="selected"':'' }}>Consommables informatiques</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Les couleurs noir et jaune sont t-ils activées ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="ArIsCoulNoirJaunOn" class="custom-control-input" value="Y" {{ $article->ArIsCoulNoirJaunOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="ArIsCoulNoirJaunOn" class="custom-control-input" value="N" {{ $article->ArIsCoulNoirJaunOn=='N'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArPrixAchatCons">Prix d'achat conseillé <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="number form-control" id="ArPrixAchatCons" name="ArPrixAchatCons" value="{{ old('ArPrixAchatCons', $article->ArPrixAchatCons) }}" placeholder="Prix d'achat conseillé" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArPrixAchatMax">Prix maximum à l'achat <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="number form-control" id="ArPrixAchatMax" name="ArPrixAchatMax" value="{{ old('ArPrixAchatMax', $article->ArPrixAchatMax) }}" placeholder="Prix maximum à l'achat" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="afficher_infos_agent_paye">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Cet article est-il payé par l'agent de sécurité ?</label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline3" name="ArIsPayeParAgent" class="custom-control-input ArIsPayeParAgent" value="Y" {{ $article->ArIsPayeParAgent=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline4" name="ArIsPayeParAgent" class="custom-control-input ArIsPayeParAgent" value="N" {{ $article->ArIsPayeParAgent=='N'?'checked="checked"':'' }}/>
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
                                                <label for="ArPrixAgent">Prix payé par l'agent après affectation</label>
                                                <input type="text" class="number form-control" id="ArPrixAgent" name="ArPrixAgent" value="{{ old('ArPrixAgent', $article->ArPrixAgent) }}" placeholder="Prix payé par l'agent après affectation"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArQteStock">Quantité en stock de l'article <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="number form-control" id="ArQteStock" name="ArQteStock" value="{{ old('ArQteStock', $article->ArQteStock) }}" placeholder="Quantité en stock de l'article" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="ArStockSeuil">Seuil pour réapprovisionner <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="number form-control" id="ArStockSeuil" name="ArStockSeuil" value="{{ old('ArStockSeuil', $article->ArStockSeuil) }}" placeholder="Stock minimum" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="AcClient">Fournisseur par défaut</label>
                                                <select class="e2 form-control" id="ArFournDef" name="ArFournDef">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($fournisseurs as $fournisseur)
                                                        <option value="{{ $fournisseur->FoNumero }}" {{ $article->ArFournDef == $fournisseur->FoNumero ? 'selected="selected"':'' }}>{{ $fournisseur->FoNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <input type="file" class="filepond" id="ArPhoto" name="ArPhoto" accept="image/*"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <a href="{{ route('articles.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
                                    <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('add_js')

    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
        });
    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputImage = document.querySelector('input[id="ArPhoto"]');

            // Create a FilePond instance
            FilePond.create(inputImage,{
                labelIdle: `Faites glisser et déposez l'image ou <span class="filepond--label-action">Parcourir</span>`,
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',

                server: {
                    process: 'https://diffapps.diff-itc.net/spak-supervision/upload-image-article',
                    //process: '/upload-image-article',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#ArType").change(function()
            {
                var ArType = $("#ArType").val();

                if(ArType == "EPI") {
                    $("#afficher_infos_agent_paye").show();
                    $(".ArIsPayeParAgent").attr("required",true);

                    $("input[type=radio][name=ArIsPayeParAgent]").click(function()
                    {
                        var ArIsPayeParAgent = getSelectedValue('ArIsPayeParAgent');

                        if (ArIsPayeParAgent == "Y") {
                            $("#ArPrixAgent").attr("required",true);
                        } else {
                            $("#ArPrixAgent").attr("required",false);
                        }
                    });

                    if($("input[name='ArIsPayeParAgent']:checked").val()=='Y')
                    {
                        $("#ArPrixAgent").attr("required",true);
                    }
                    else
                    {
                        $("#ArPrixAgent").attr("required",false);
                    }

                } else {
                    $("#afficher_infos_agent_paye").hide();
                    $(".ArIsPayeParAgent").attr("required",false);
                    $("#ArPrixAgent").attr("required",true);
                }

            });

            if($("#ArType").val() != null) {

                var ArType =  $("#ArType").val();

                if(ArType == "EPI") {
                    $("#afficher_infos_agent_paye").show();
                    $(".ArIsPayeParAgent").attr("required",true);

                    $("input[type=radio][name=ArIsPayeParAgent]").click(function()
                    {
                        var ArIsPayeParAgent = getSelectedValue('ArIsPayeParAgent');

                        if (ArIsPayeParAgent == "Y") {
                            $("#ArPrixAgent").attr("required",true);
                        } else {
                            $("#ArPrixAgent").attr("required",false);
                        }
                    });

                    if($("input[name='ArIsPayeParAgent']:checked").val()=='Y')
                    {
                        $("#ArPrixAgent").attr("required",true);
                    }
                    else
                    {
                        $("#ArPrixAgent").attr("required",false);
                    }

                } else {
                    $("#afficher_infos_agent_paye").hide();
                    $(".ArIsPayeParAgent").attr("required",false);
                    $("#ArPrixAgent").attr("required",true);
                }
            }
        });
    </script>
@endsection

