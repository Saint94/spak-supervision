@extends('layout.app')

@section('title', 'Nouvelle entité')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Création d'une nouvelle entité</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('entites.store') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnAbrge">Code ou Abrégé <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnAbrge" name="EnAbrge" value="{{ old('EnAbrge') }}" placeholder="Code ou Abrégé" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EnNom">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnNom" name="EnNom" value="{{ old('EnNom') }}" placeholder="Nom" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="EnNom">Nom complet</label>
                                                <input type="text" class="form-control" id="EnNomComplet" name="EnNomComplet" value="{{ old('EnNomComplet') }}" placeholder="Nom complet"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnBP">Boîte postale</label>
                                                <input type="text" class="form-control number" id="EnBP" name="EnBP" value="{{ old('EnBP') }}" placeholder="Boîte postale" maxlength="5"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnEmail">Email</label>
                                                <input type="email" class="form-control" id="EnEmail" name="EnEmail" value="{{ old('EnEmail') }}" placeholder="Email"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnTel1">N° de téléphone 1</label>
                                                <input type="text" class="form-control" id="EnTel1" name="EnTel1" value="{{ old('EnTel1') }}" placeholder="N° de téléphone 1"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnTel2">N° de téléphone 2</label>
                                                <input type="text" class="form-control" id="EnTel2" name="EnTel2" value="{{ old('EnTel2') }}" placeholder="N° de téléphone 2"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnSiteWeb">Site Web</label>
                                                <input type="text" class="form-control" id="EnSiteWeb" name="EnSiteWeb" value="{{ old('EnSiteWeb') }}" placeholder="Site Web"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnDG">Selectionner le DG</label>
                                                <select class="e1 form-control" id="EnDG" name="EnDG">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($personnels_dg as $dg)
                                                        <option value="{{ $dg->PeNumero }}">{{ $dg->PeNom.' '.$dg->PePrenom }}</option>
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
                                                <label for="EnDT">Selectionner le DT</label>
                                                <select class="e2 form-control" id="EnDT" name="EnDT">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($personnels_dt as $dt)
                                                        <option value="{{ $dt->PeNumero }}">{{ $dt->PeNom.' '.$dt->PePrenom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnRAF">Selectionner le RAF</label>
                                                <select class="e3 form-control" id="EnRAF" name="EnRAF">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($personnels_raf as $raf)
                                                        <option value="{{ $raf->PeNumero }}">{{ $raf->PeNom.' '.$raf->PePrenom }}</option>
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
                                                <label for="EnTxTVA">Taux TVA <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnTxTVA" name="EnTxTVA" value="{{ old('EnTxTVA') }}" placeholder="Taux TVA" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnNbMoisMaxImpClt">Nombre max de Mois impayés tolèrés <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control number" id="EnNbMoisMaxImpClt" name="EnNbMoisMaxImpClt" value="{{ old('EnNbMoisMaxImpClt') }}" placeholder="Nombre max de Mois impayés tolèrés" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnPrixUMinAgentFixe">Prix unitaire minimum agent fixe <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnPrixUMinAgentFixe" name="EnPrixUMinAgentFixe" value="{{ old('EnPrixUMinAgentFixe') }}" placeholder="Prix unitaire minimum agent fixe" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnPrixUMinMC">Prix unitaire minimum d'un maître chien <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnPrixUMinMC" name="EnPrixUMinMC" value="{{ old('EnPrixUMinMC') }}" placeholder="Prix unitaire minimum d'un maître chien" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnPrixUMinSuperv">Prix unitaire minimum d'un superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnPrixUMinSuperv" name="EnPrixUMinSuperv" value="{{ old('EnPrixUMinSuperv') }}" placeholder="Prix unitaire minimum d'un superviseur" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="EnPrixUMinComdo">Prix unitaire minimum d'un commando <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" class="form-control" id="EnPrixUMinComdo" name="EnPrixUMinComdo" value="{{ old('EnPrixUMinComdo') }}" placeholder="Prix unitaire minimum d'un commando" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <input type="file" class="filepond" id="EnChemLogo" name="EnChemLogo" accept="image/*"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group">
                                                <label for="EnDetails">Détails</label>
                                                <textarea rows="2" class="form-control" id="EnDetails" name="EnDetails" placeholder="Commentaires"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <a href="{{ route('entites.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
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
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputLogo = document.querySelector('input[id="EnChemLogo"]');

            // Create a FilePond instance
            FilePond.create(inputLogo,{
                labelIdle: `Faites glisser et déposez le logo ou <span class="filepond--label-action">Parcourir</span>`,
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',

                server: {
                    process: 'https://diffapps.diff-itc.net/spak-supervision/upload-logo-entite',
                    //process: '/upload-logo-entite',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
        });
    </script>
@endsection
