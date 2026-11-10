@extends('layout.app')

@section('title', 'Modifier fournisseur')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Modification du fournisseur</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('fournisseurs.update', $fournisseur->FoNumero) }}" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-body">
                                    <hr>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoCode">Code fournisseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="FoCode" name="FoCode" value="{{ old('FoCode', $fournisseur->FoCode) }}" placeholder="Code ou abrégé du fournisseur" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoNom">Nom du fournisseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="FoNom" name="FoNom" value="{{ old('FoNom', $fournisseur->FoNom) }}" placeholder="Nom ou désignation du fournisseur" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoTel1">Téléphone 1</label>
                                                <input type="text" class="form-control" id="FoTel1" name="FoTel1" value="{{ old('FoTel1', $fournisseur->FoTel1) }}" placeholder="Numéro de téléphone 1"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoTel2">Téléphone 2</label>
                                                <input type="text" class="form-control" id="FoTel2" name="FoTel2" value="{{ old('FoTel2', $fournisseur->FoTel2) }}" placeholder="Numéro de téléphone 2"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoAdresse">Adresse</label>
                                                <input type="text" class="form-control" id="FoAdresse" name="FoAdresse" value="{{ old('FoAdresse', $fournisseur->FoAdresse) }}" placeholder="Adresse ou localisation du fournisseur"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoEmail">Email</label>
                                                <input type="email" class="form-control" id="FoEmail" name="FoEmail" value="{{ old('FoEmail', $fournisseur->FoEmail) }}" placeholder="Adresse e-mail du fournisseur"/>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoVille">Ville</label>
                                                <input type="text" class="form-control" id="FoVille" name="FoVille" value="{{ old('FoVille', $fournisseur->FoVille) }}" placeholder="Ville du fournisseur"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="FoPays">Pays</label>
                                                <input type="text" class="form-control" id="FoPays" name="FoPays" value="{{ old('FoPays', $fournisseur->FoPays) }}" placeholder="Code ISO du pays"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>TVA appliquée ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="FoIsTVAOnOui" name="FoIsTVAOn" class="custom-control-input" value="Y" {{ $fournisseur->FoIsTVAOn == 'Y' ? 'checked="checked"' : '' }}/>
                                                        <label class="custom-control-label" for="FoIsTVAOnOui">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="FoIsTVAOnNon" name="FoIsTVAOn" class="custom-control-input" value="N" {{ $fournisseur->FoIsTVAOn == 'N' ? 'checked="checked"' : '' }}/>
                                                        <label class="custom-control-label" for="FoIsTVAOnNon">Non</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Fournisseur actif ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="FoIsOnOui" name="FoIsOn" class="custom-control-input" value="Y" {{ $fournisseur->FoIsOn == 'Y' ? 'checked="checked"' : '' }}/>
                                                        <label class="custom-control-label" for="FoIsOnOui">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="FoIsOnNon" name="FoIsOn" class="custom-control-input" value="N" {{ $fournisseur->FoIsOn == 'N' ? 'checked="checked"' : '' }}/>
                                                        <label class="custom-control-label" for="FoIsOnNon">Non</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <a href="{{ route('fournisseurs.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
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
        });
    </script>

@endsection
