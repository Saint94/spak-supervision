<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AvenantContratController;
use App\Http\Controllers\CarriereController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactClientController;
use App\Http\Controllers\ContratSiteController;
use App\Http\Controllers\DocumentStockController;
use App\Http\Controllers\EntiteController;
use App\Http\Controllers\FactureClientController;

use App\Http\Controllers\FirstLoginController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\CartEntreeStockController;
use App\Http\Controllers\EnqueteClientController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageGeneriqueController;
use App\Http\Controllers\ModePaiementController;
use App\Http\Controllers\MoisPaieController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PosterVigileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuartierController;
use App\Http\Controllers\SiteClientController;
use App\Http\Controllers\SiteSuperviseurController;
use App\Http\Controllers\SupervisionSiteController;
use App\Http\Controllers\UploadTemporaryLogoEntiteController;
use App\Http\Controllers\VilleController;
use App\Http\Controllers\SecteurActiviteController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\OperationSiteController;
use App\Http\Controllers\PointageSanctionController;
use App\Http\Controllers\ReglementFactureController;
use App\Http\Controllers\RondeOperationController;
use App\Http\Controllers\RondierController;
use App\Http\Controllers\TypeSanctionController;
use App\Http\Controllers\SiteAgentSupCtrlController;
use App\Http\Controllers\UploadTemporaryDocContratController;
use App\Http\Controllers\UploadTemporaryDocEnqueteController;
use App\Http\Controllers\UploadTemporaryDocEvenementController;
use App\Http\Controllers\UploadTemporaryDocMessageController;
use App\Http\Controllers\UploadTemporaryImageArticleController;
use App\Http\Controllers\SiteRondeOperationController;
use App\Http\Controllers\UploadTemporaryDocPlanActionController;
use App\Http\Controllers\UploadTemporaryDocPlanActionEditController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {return view('auth.login');})->name('/');
    Route::match(['get','post'],'logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('/change-password', function () {
        return view('first_connexion.index');
    })->name('/change-password');
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/reset-password', [FirstLoginController::class, 'index'])->name('reset-password.index');
    Route::post('changer-password', [FirstLoginController::class, 'store'])->name('changer-password.store');
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::resource('entites',EntiteController::class);
    Route::post('/upload-logo-entite', UploadTemporaryLogoEntiteController::class);
    Route::get('supprimer-entite/{EnNumero}', [EntiteController::class, 'delete'])->name('entite.supprimer');
    Route::get('show-entite/{EnNumero}',[EntiteController::class,'show'])->name('entite.show');

    Route::resource('users',UserController::class);
    Route::get('activer-user/{id}', [UserController::class, 'activer'])->name('user.activer');
    Route::get('desactiver-user/{id}', [UserController::class, 'desactiver'])->name('user.desactiver');
    Route::get('supprimer-user/{id}', [UserController::class, 'delete'])->name('user.supprimer');
    Route::get('show-user/{id}',[UserController::class,'show'])->name('user.show');

    Route::get('show-personnels-users/{type}',[UserController::class,'show_personnels_users'])->name('user.show_personnels_users');

    Route::resource('secteurs-activite',SecteurActiviteController::class);
    Route::get('edit-secteur-activite/{ViNumero}',[SecteurActiviteController::class, 'edit'])->name('secteur-activite.edit');
    Route::put('update-secteur-activite/{ViNumero}',[SecteurActiviteController::class,'update'])->name('secteur-activite.update');
    Route::get('activer-secteur-activite/{ViNumero}', [SecteurActiviteController::class, 'activer'])->name('secteur-activite.activer');
    Route::get('desactiver-secteur-activite/{ViNumero}', [SecteurActiviteController::class, 'desactiver'])->name('secteur-activite.desactiver');
    Route::get('supprimer-secteur-activite/{ViNumero}', [SecteurActiviteController::class, 'delete'])->name('secteur-activite.delete');
    Route::get('show-secteur-activite/{ViNumero}',[SecteurActiviteController::class,'show'])->name('secteur-activite.show');
    Route::get('/secteur-activite/imprimer', [SecteurActiviteController::class,'generatePDF'])->name('secteur-activite.imprimer');


    Route::resource('villes',VilleController::class);
    Route::get('edit-ville/{ViNumero}',[VilleController::class, 'edit'])->name('ville.edit');
    Route::put('update-ville/{ViNumero}',[VilleController::class,'update'])->name('ville.update');
    Route::get('activer-ville/{ViNumero}', [VilleController::class, 'activer'])->name('ville.activer');
    Route::get('desactiver-ville/{ViNumero}', [VilleController::class, 'desactiver'])->name('ville.desactiver');
    Route::get('supprimer-ville/{ViNumero}', [VilleController::class, 'delete'])->name('ville.delete');
    Route::get('show-ville/{ViNumero}',[VilleController::class,'show'])->name('ville.show');
    Route::get('/ville/imprimer', [VilleController::class,'generatePDF'])->name('ville.imprimer');

    Route::resource('zones',ZoneController::class);
    Route::get('edit-zone/{ZoNumero}',[ZoneController::class, 'edit'])->name('zone.edit');
    Route::put('update-zone/{ZoNumero}',[ZoneController::class,'update'])->name('zone.update');
    Route::get('activer-zone/{ZoNumero}', [ZoneController::class, 'activer'])->name('zone.activer');
    Route::get('desactiver-zone/{ZoNumero}', [ZoneController::class, 'desactiver'])->name('zone.desactiver');
    Route::get('supprimer-zone/{ZoNumero}', [ZoneController::class, 'delete'])->name('zone.delete');
    Route::get('show-zone/{ZoNumero}',[ZoneController::class,'show'])->name('zone.show');
    Route::get('/zone/imprimer', [ZoneController::class,'generatePDF'])->name('zone.imprimer');

    Route::resource('quartiers',QuartierController::class);
    Route::get('edit-quartier/{QuNumero}',[QuartierController::class, 'edit'])->name('quartier.edit');
    Route::put('update-quartier/{QuNumero}',[QuartierController::class,'update'])->name('quartier.update');
    Route::get('activer-quartier/{QuNumero}', [QuartierController::class, 'activer'])->name('quartier.activer');
    Route::get('desactiver-quartier/{QuNumero}', [QuartierController::class, 'desactiver'])->name('quartier.desactiver');
    Route::get('supprimer-quartier/{QuNumero}', [QuartierController::class, 'delete'])->name('quartier.delete');
    Route::get('show-quartier/{QuNumero}',[QuartierController::class,'show'])->name('quartier.show');
    Route::get('show-zones-quartier/{QuVille}',[QuartierController::class,'show_zones'])->name('quartier.show_zones');
    Route::get('/quartier/imprimer', [QuartierController::class,'generatePDF'])->name('quartier.imprimer');

    Route::resource('fonctions',FonctionController::class);
    Route::get('edit-fonction/{FnNumero}',[FonctionController::class, 'edit'])->name('fonction.edit');
    Route::put('update-fonction/{FnNumero}',[FonctionController::class,'update'])->name('fonction.update');
    Route::get('activer-fonction/{FnNumero}', [FonctionController::class, 'activer'])->name('fonction.activer');
    Route::get('desactiver-fonction/{FnNumero}', [FonctionController::class, 'desactiver'])->name('fonction.desactiver');
    Route::get('supprimer-fonction/{FnNumero}', [FonctionController::class, 'delete'])->name('fonction.delete');
    Route::get('show-fonction/{FnNumero}',[FonctionController::class,'show'])->name('fonction.show');
    Route::get('/fonction/imprimer', [FonctionController::class,'generatePDF'])->name('fonction.imprimer');

    Route::resource('types-sanction',TypeSanctionController::class);
    Route::get('edit-type-sanction/{SoNumero}',[TypeSanctionController::class, 'edit'])->name('type-sanction.edit');
    Route::put('update-type-sanction/{SoNumero}',[TypeSanctionController::class,'update'])->name('type-sanction.update');
    Route::get('activer-type-sanction/{SoNumero}', [TypeSanctionController::class, 'activer'])->name('type-sanction.activer');
    Route::get('desactiver-type-sanction/{SoNumero}', [TypeSanctionController::class, 'desactiver'])->name('type-sanction.desactiver');
    Route::get('supprimer-type-sanction/{SoNumero}', [TypeSanctionController::class, 'delete'])->name('type-sanction.delete');
    Route::get('show-type-sanction/{SoNumero}',[TypeSanctionController::class,'show'])->name('type-sanction.show');

    Route::resource('mois-paie',MoisPaieController::class);
    Route::get('edit-m-paie/{MpNumero}',[MoisPaieController::class, 'edit'])->name('m-paie.edit');
    Route::put('update-m-paie/{MpNumero}',[MoisPaieController::class,'update'])->name('m-paie.update');
    Route::get('supprimer-m-paie/{MpNumero}', [MoisPaieController::class, 'delete'])->name('type-sanction.delete');
    Route::get('show-m-paie/{MpNumero}',[MoisPaieController::class,'show'])->name('m-paie.show');

    Route::resource('modes-paiement',ModePaiementController::class);
    Route::get('edit-mode-paiement/{MpNumero}',[ModePaiementController::class, 'edit'])->name('mode-paiement.edit');
    Route::put('update-mode-paiement/{MpNumero}',[ModePaiementController::class,'update'])->name('mode-paiement.update');
    Route::get('activer-mode-paiement/{MpNumero}', [ModePaiementController::class, 'activer'])->name('mode-paiement.activer');
    Route::get('desactiver-mode-paiement/{MpNumero}', [ModePaiementController::class, 'desactiver'])->name('mode-paiement.desactiver');
    Route::get('supprimer-mode-paiement/{MpNumero}', [ModePaiementController::class, 'delete'])->name('mode-paiement.delete');
    Route::get('show-mode-paiement/{MpNumero}',[ModePaiementController::class,'show'])->name('mode-paiement.show');
    Route::get('/mode-paiement/imprimer', [ModePaiementController::class,'generatePDF'])->name('mode-paiement.imprimer');


    Route::resource('clients',ClientController::class);
    Route::get('activer-client/{ClNumero}', [ClientController::class, 'activer'])->name('client.activer');
    Route::get('desactiver-client/{ClNumero}', [ClientController::class, 'desactiver'])->name('client.desactiver');
    Route::get('supprimer-client/{ClNumero}', [ClientController::class, 'delete'])->name('client.supprimer');
    Route::get('show-client/{ClNumero}',[ClientController::class,'show'])->name('client.show');
    Route::post('/import-client', [ClientController::class, 'import'])->name('client.import');
    Route::match(['get','post'],'/filtre-clients', [ClientController::class,'filtre'])->name('clients.filtre');
    Route::get('show-liste-quartiers/{ClVilleSiege}',[ClientController::class,'show_quartiers'])->name('clients.show_liste_quartiers');

    Route::get('client/{ClNumero}/agents', [ClientController::class, 'agents'])->name('client.agents');
    Route::get('client/{ClNumero}/sites', [ClientController::class, 'sites'])->name('client.sites');

    Route::get('client/{ClNumero}/contacts', [ClientController::class, 'contacts'])->name('client.contacts');
    Route::post('client-store-contact-client',[ClientController::class, 'store_contact_client'])->name('client.store_contact_client');
    Route::get('client-edit-contact-client/{CcNumero}',[ClientController::class, 'edit_contact_client'])->name('client.edit_contact_client');
    Route::put('client-update-contact-client/{CcNumero}',[ClientController::class,'update_contact_client'])->name('client.update_contact_client');
    Route::get('client-activer-contact-client/{CcNumero}', [ClientController::class, 'activer_contact_client'])->name('client.activer_contact_client');
    Route::get('client-desactiver-contact-client/{CcNumero}', [ClientController::class, 'desactiver_contact_client'])->name('client.desactiver_contact_client');
    Route::get('client-show-contact-client/{CcNumero}',[ClientController::class,'show_contact_client'])->name('client.show_contact_client');
    Route::get('client-supprimer-contact-client/{CcNumero}', [ClientController::class, 'delete_contact_client'])->name('client.delete_contact_client');


    Route::resource('contacts-client',ContactClientController::class);
    Route::get('edit-contact-client/{CcNumero}',[ContactClientController::class, 'edit'])->name('contact-client.edit');
    Route::put('update-contact-client/{CcNumero}',[ContactClientController::class,'update'])->name('contact-client.update');
    Route::get('activer-contact-client/{CcNumero}', [ContactClientController::class, 'activer'])->name('contact-client.activer');
    Route::get('desactiver-contact-client/{CcNumero}', [ContactClientController::class, 'desactiver'])->name('contact-client.desactiver');
    Route::get('show-contact-client/{CcNumero}',[ContactClientController::class,'show'])->name('contact-client.show');
    Route::get('supprimer-contact-client/{CcNumero}', [ContactClientController::class, 'delete'])->name('contact-client.delete');
    Route::get('show-sites-client-contact-client/{CcClient}',[ContactClientController::class,'show_sites_client'])->name('show-sites-client-contact-client.show_sites_client');
    Route::get('show-sites-client-contact-client-edit/{CcClient}',[ContactClientController::class,'show_sites_client_edit'])->name('show-sites-client-contact-client.show_sites_client_edit');

    Route::resource('factures-clients',FactureClientController::class);
    Route::get('edit-facture-client/{FcNumero}',[FactureClientController::class, 'edit'])->name('facture-client.edit');
    Route::put('update-facture-client/{FcNumero}',[FactureClientController::class,'update'])->name('facture-client.update');
    Route::get('show-facture-client/{FcNumero}',[FactureClientController::class,'show'])->name('facture-client.show');
    Route::get('show-info-client-contrat/{FcClient}',[FactureClientController::class,'show_info_client_contrat'])->name('facture-client.show_info_client_contrat');
    Route::get('show-info-client-contrat-edit/{FcClient}',[FactureClientController::class,'show_info_client_contrat_edit'])->name('facture-client.show_info_client_contrat_edit');
    Route::match(['get','post'],'/filtre-factures-clients', [FactureClientController::class,'filtre'])->name('factures-clients.filtre');

    Route::resource('reglements-clients',ReglementFactureController::class);
    Route::get('edit-reglement-client/{RfNumero}',[ReglementFactureController::class, 'edit'])->name('reglement-client.edit');
    Route::put('update-reglement-client/{RfNumero}',[ReglementFactureController::class,'update'])->name('reglement-client.update');
    Route::get('show-reglement-client/{RfNumero}',[ReglementFactureController::class,'show'])->name('reglement-client.show');
    Route::match(['get','post'],'/filtre-reglements-clients', [ReglementFactureController::class,'filtre'])->name('reglements-clients.filtre');
    Route::get('show-factures-client/{FcClient}',[ReglementFactureController::class,'show_factures_client'])->name('reglement-client.show_factures_client');
    Route::get('show-info-facture/{RfFacture}',[ReglementFactureController::class,'show_info_facture'])->name('reglement-client.show_info_facture');

    Route::get('show-factures-client-edit/{FcClient}',[ReglementFactureController::class,'show_factures_client_edit'])->name('reglement-client.show_factures_client_edit');
    Route::get('show-info-facture-edit/{RfFacture}',[ReglementFactureController::class,'show_info_facture_edit'])->name('reglement-client.show_info_facture_edit');

    Route::resource('messages-generique',MessageGeneriqueController::class);
    Route::get('activer-message-generique/{MgNumero}', [MessageGeneriqueController::class, 'activer'])->name('message-generique.activer');
    Route::get('desactiver-message-generique/{MgNumero}', [MessageGeneriqueController::class, 'desactiver'])->name('message-generique.desactiver');
    Route::get('edit-message-generique/{MgNumero}',[MessageGeneriqueController::class, 'edit'])->name('message-generique.edit');
    Route::put('update-message-generique/{MgNumero}',[MessageGeneriqueController::class,'update'])->name('message-generique.update');
    Route::get('show-message-generique/{MgNumero}',[MessageGeneriqueController::class,'show'])->name('message-generique.show');
    Route::get('supprimer-message-generique/{MgNumero}', [MessageGeneriqueController::class, 'delete'])->name('message-generique.supprimer');

    Route::match(['get','post'],'/filtre-messages-generique', [MessageGeneriqueController::class,'filtre'])->name('messages-generique.filtre');
    Route::post('/upload-doc-message', UploadTemporaryDocMessageController::class);

    Route::resource('enquetes-clients',EnqueteClientController::class);
    Route::get('edit-enquete-client/{EcNumero}',[EnqueteClientController::class, 'edit'])->name('enquete-client.edit');
    Route::put('update-enquete-client/{EcNumero}',[EnqueteClientController::class,'update'])->name('enquete-client.update');
    Route::get('show-enquete-client/{EcNumero}',[EnqueteClientController::class,'show'])->name('enquete-client.show');
    Route::get('supprimer-enquete-client/{EcNumero}', [EnqueteClientController::class, 'delete'])->name('enquete-client.supprimer');

    Route::match(['get','post'],'/filtre-enquetes-clients', [EnqueteClientController::class,'filtre'])->name('enquetes-clients.filtre');
    Route::post('/upload-doc-enquete', UploadTemporaryDocEnqueteController::class);

    Route::resource('suivi-clients',EvenementController::class);
    Route::get('activer-suivi-client/{EvNumero}', [EvenementController::class, 'activer'])->name('suivi-client.activer');
    Route::get('desactiver-suivi-client/{EvNumero}', [EvenementController::class, 'desactiver'])->name('suivi-client.desactiver');
    Route::get('supprimer-suivi-client/{EvNumero}', [EvenementController::class, 'delete'])->name('suivi-client.supprimer');
    Route::get('show-suivi-client/{EvNumero}',[EvenementController::class,'show'])->name('suivi-client.show');
    Route::post('/upload-doc-evenement', UploadTemporaryDocEvenementController::class);

    Route::resource('sites-client',SiteClientController::class);
    Route::get('edit-site-client/{ScNumero}',[SiteClientController::class, 'edit'])->name('site-client.edit');
    Route::put('update-site-client/{ScNumero}',[SiteClientController::class,'update'])->name('site-client.update');
    Route::get('activer-site-client/{ScNumero}', [SiteClientController::class, 'activer'])->name('site-client.activer');
    Route::get('desactiver-site-client/{ScNumero}', [SiteClientController::class, 'desactiver'])->name('site-client.desactiver');
    Route::get('supprimer-site-client/{ScNumero}', [SiteClientController::class, 'delete'])->name('site-client.delete');
    Route::get('show-site-client/{ScNumero}',[SiteClientController::class,'show'])->name('site-client.show');
    Route::post('/import-site-client', [SiteClientController::class, 'import'])->name('site-client.import');
    Route::put('site-client/update-gps/{id}', [SiteClientController::class, 'updateGps'])->name('site-client.update-gps');
    Route::match(['get','post'],'/filtre-site-client', [SiteClientController::class,'filtre'])->name('site-client.filtre');

    Route::get('show-zones-site-client/{ScVille}',[SiteClientController::class,'show_zones'])->name('site-client.show_zones');
    Route::get('show-quartiers/{ScZone}',[SiteClientController::class,'show_quartiers'])->name('site-client.show_quartiers');

    Route::get('show-zones-edit-site-client/{ScVille}', [SiteClientController::class,'show_zones_edit'])->name('site-client.show_zones_edit');
    Route::get('show-quartiers-edit-site-client/{ScZone}', [SiteClientController::class,'show_quartiers_edit'])->name('site-client.show_quartiers_edit');

    Route::resource('avenants-contrat',AvenantContratController::class);
    Route::get('activer-avenant-contrat/{AcNumero}', [AvenantContratController::class, 'activer'])->name('avenant-contrat.activer');
    Route::get('desactiver-avenant-contrat/{AcNumero}', [AvenantContratController::class, 'desactiver'])->name('avenant-contrat.desactiver');
    Route::get('supprimer-avenant-contrat/{AcNumero}', [AvenantContratController::class, 'delete'])->name('avenant-contrat.supprimer');
    Route::get('show-avenant-contrat/{AcNumero}',[AvenantContratController::class,'show'])->name('avenant-contrat.show');
    Route::get('show-sites-client/{AcClient}',[AvenantContratController::class,'show_sites_client'])->name('avenant-contrat.sites-client');
    Route::get('show-last-contrat-client/{AcClient}',[AvenantContratController::class,'show_last_contrat_client'])->name('avenant-contrat.last-contrat-client');
    Route::get('show-ref-contrat-client/{ClNumero}',[AvenantContratController::class,'show_ref_contrat_client'])->name('avenant-contrat.ref-contrat-client');
    Route::get('show-ref-contrat-client-edit/{ClNumero}',[AvenantContratController::class,'show_ref_contrat_client_edit'])->name('avenant-contrat.ref-contrat-client-edit');

    Route::post('/import-avenant-contrat', [AvenantContratController::class, 'import'])->name('avenant-contrat.import');
    Route::post('/upload-doc-contrat', UploadTemporaryDocContratController::class);
    Route::get('avenant-contrat/{AcNumero}/document-contrat', [AvenantContratController::class, 'document'])->name('avenant-contrat.file');
    Route::get('avenant-contrat/{AcNumero}/personnel-contrat', [AvenantContratController::class, 'personnel_contrat'])->name('avenant-contrat.personnel_contrat');
    Route::get('avenant-contrat/{AcNumero}/personnel-contrat', [AvenantContratController::class, 'personnel_contrat'])->name('avenant-contrat.personnel_contrat');
    Route::match(['get','post'],'/filtre-personnel-contrat', [AvenantContratController::class,'filtre_personnel_contrat'])->name('avenant-contrat.filtre_personnel_contrat');
    Route::get('avenant-contrat/{AcNumero}/sites-contrat', [AvenantContratController::class, 'sites_contrat'])->name('avenant-contrat.sites_contrat');
    Route::match(['get','post'],'/filtre-sites-contrat', [AvenantContratController::class,'filtre_sites_contrat'])->name('avenant-contrat.filtre_sites_contrat');

    Route::resource('contrats-site',ContratSiteController::class);
    Route::get('edit-contrat-site/{CsNumero}',[ContratSiteController::class, 'edit'])->name('contrat-site.edit');
    Route::put('update-contrat-site/{CsNumero}',[ContratSiteController::class,'update'])->name('contrat-site.update');
    Route::get('activer-contrat-site/{CsNumero}', [ContratSiteController::class, 'activer'])->name('contrat-site.activer');
    Route::get('desactiver-contrat-site/{CsNumero}', [ContratSiteController::class, 'desactiver'])->name('contrat-site.desactiver');
    Route::get('show-contrat-site/{CsNumero}',[ContratSiteController::class,'show'])->name('contrat-site.show');

    Route::resource('personnels',PersonnelController::class);
    Route::get('activer-personnel/{PeNumero}', [PersonnelController::class, 'activer'])->name('personnel.activer');
    Route::get('desactiver-personnel/{PeNumero}', [PersonnelController::class, 'desactiver'])->name('personnel.desactiver');
    Route::get('supprimer-personnel/{PeNumero}', [PersonnelController::class, 'delete'])->name('personnel.supprimer');
    Route::get('show-personnel/{PeNumero}',[PersonnelController::class,'show'])->name('personnel.show');
    Route::post('/import-personnel', [PersonnelController::class, 'import'])->name('personnel.import');
    Route::get('/personnel/pdf', [PersonnelController::class,'generatePDF'])->name('personnel.pdf');
    Route::match(['get','post'],'/filtre-personnel', [PersonnelController::class,'filtre'])->name('personnel.filtre');
    Route::get('show-quartiers-personnel/{PeVille}',[PersonnelController::class,'show_quartiers'])->name('personnel.show_quartiers');
    Route::get('personnels-controleurs', [PersonnelController::class, 'controleurs'])->name('personnels.controleurs');
    Route::get('personnels-superviseur', [PersonnelController::class, 'superviseur'])->name('personnels.superviseur');
    Route::get('personnels-commandos', [PersonnelController::class, 'commando'])->name('personnels.commando');
    Route::get('personnels-chef-equipe', [PersonnelController::class, 'chef_equipe'])->name('personnels.chef_equipe');
    Route::get('personnels-maitre-chien', [PersonnelController::class, 'maitre_chien'])->name('personnels.maitre_chien');
    Route::get('personnels-agent-fixe', [PersonnelController::class, 'agent_fixe'])->name('personnels.agent_fixe');
    Route::get('personnels-rondier', [PersonnelController::class, 'rondier'])->name('personnels.rondier');
    Route::get('personnels-stand-by', [PersonnelController::class, 'stand_by'])->name('personnels.stand_by');
    Route::get('personnels-postulants', [PersonnelController::class, 'postulants'])->name('personnels.postulant');


    Route::resource('carrieres',CarriereController::class);
    Route::get('edit-carriere/{CaNumero}',[CarriereController::class, 'edit'])->name('carriere.edit');
    Route::put('update-carriere/{CaNumero}',[CarriereController::class,'update'])->name('carriere.update');
    Route::get('supprimer-carriere/{CaNumero}', [CarriereController::class, 'delete'])->name('carriere.delete');
    Route::get('show-carriere/{CaNumero}',[CarriereController::class,'show'])->name('carriere.show');

    Route::get('personnel/{PeNumero}/rondiers', [PersonnelController::class, 'rondiers'])->name('personnels.rondiers');
    Route::resource('rondiers',RondierController::class);
    Route::get('edit-rondier/{RtNumero}',[RondierController::class, 'edit'])->name('rondier.edit');
    Route::put('update-rondier/{RtNumero}',[RondierController::class,'update'])->name('rondier.update');
    Route::get('activer-rondier/{RtNumero}', [RondierController::class, 'activer'])->name('rondier.activer');
    Route::get('desactiver-rondier/{RtNumero}', [RondierController::class, 'desactiver'])->name('rondier.desactiver');
    Route::get('supprimer-rondier/{RtNumero}', [RondierController::class, 'delete'])->name('rondier.delete');
    Route::get('show-rondier/{RtNumero}',[RondierController::class,'show'])->name('rondier.show');
    Route::match(['get','post'],'/filtre-rondiers', [RondierController::class,'filtre'])->name('rondiers.filtre');

    Route::resource('articles',ArticleController::class);
    Route::get('activer-article/{ArNumero}', [ArticleController::class, 'activer'])->name('article.activer');
    Route::get('desactiver-article/{ArNumero}', [ArticleController::class, 'desactiver'])->name('article.desactiver');
    Route::get('supprimer-article/{ArNumero}', [ArticleController::class, 'delete'])->name('article.supprimer');
    Route::get('show-article/{ArNumero}',[ArticleController::class,'show'])->name('article.show');
    Route::post('/upload-image-article', UploadTemporaryImageArticleController::class);

    Route::resource('fournisseurs',FournisseurController::class);
    Route::get('activer-fournisseur/{FoNumero}', [FournisseurController::class, 'activer'])->name('fournisseur.activer');
    Route::get('desactiver-fournisseur/{FoNumero}', [FournisseurController::class, 'desactiver'])->name('fournisseur.desactiver');
    Route::get('supprimer-fournisseur/{FoNumero}', [FournisseurController::class, 'delete'])->name('fournisseur.supprimer');
    Route::get('show-fournisseur/{FoNumero}',[FournisseurController::class,'show'])->name('fournisseur.show');

    Route::resource('documents-stock',DocumentStockController::class);
    Route::get('show-document-stock/{DsNumero}',[DocumentStockController::class,'show'])->name('document-stock.show');
    Route::get('document-entree-stock/{DsNumero}/edit', [DocumentStockController::class, 'edit_entree'])->name('document-stock.edit_entree');
    Route::get('document-sortie-stock/{DsNumero}/edit', [DocumentStockController::class, 'edit_sortie'])->name('document-stock.edit_sortie');
    Route::get('document-stock/{DsNumero}/articles', [DocumentStockController::class, 'articles'])->name('document-stock.articles');
    Route::get('document-entree-stock', [DocumentStockController::class, 'create_entree'])->name('document-stock.create_entree');
    Route::get('document-sortie-stock', [DocumentStockController::class, 'create_sortie'])->name('document-stock.create_sortie');

    Route::post('save-document-entree-stock',[DocumentStockController::class,'storeEntree'])->name('document-stock.store_entree');
    Route::post('save-document-sortie-stock',[DocumentStockController::class,'storeSortie'])->name('document-stock.store_sortie');

    Route::get('document-stock/{DsNumero}/pdf', [DocumentStockController::class, 'pdf'])->name('document-stock.pdf');
    Route::match(['get','post'],'/filtre-documents-stock', [DocumentStockController::class,'filtre'])->name('document-stock.filtre');

    Route::get('show-info-article-stock/{MsArticle}',[DocumentStockController::class,'show_info_article'])->name('document-stock.show_info_article');
    Route::get('show-info-fournisseur/{DsFournBenef}',[DocumentStockController::class,'show_info_fournisseur'])->name('document-stock.show_info_fournisseur');


    // Cart Entrée Stock
    Route::post('save-cart-entree-stock', [CartEntreeStockController::class, 'store'])->name('cart-entree-stock.store');
    Route::delete('/vider-cart-entree-stock', [CartEntreeStockController::class, 'viderPanier'])->name('cart-entree-stock.vider');
    Route::post('supprimer-cart-entree-stock', [CartEntreeStockController::class, 'remove'])->name('cart-entree-stock.supprimer');


    Route::resource('poster-vigiles',PosterVigileController::class);
    Route::get('edit-poster-vigile/{PvNumero}',[PosterVigileController::class, 'edit'])->name('poster-vigile.edit');
    Route::put('update-poster-vigile/{PvNumero}',[PosterVigileController::class,'update'])->name('poster-vigile.update');
    Route::get('activer-poster-vigile/{PvNumero}', [PosterVigileController::class, 'activer'])->name('poster-vigile.activer');
    Route::get('desactiver-poster-vigile/{PvNumero}', [PosterVigileController::class, 'desactiver'])->name('poster-vigile.desactiver');
    Route::get('supprimer-poster-vigile/{PvNumero}', [PosterVigileController::class, 'delete'])->name('poster-vigile.delete');
    Route::get('show-poster-vigile/{PvNumero}',[PosterVigileController::class,'show'])->name('poster-vigile.show');
    Route::match(['get','post'],'/filtre-poster-vigile', [PosterVigileController::class,'filtre'])->name('poster-vigile.filtre');

    Route::get('edit-redeployer-poster-vigile/{PvNumero}',[PosterVigileController::class, 'redeployer_edit'])->name('poster-vigile.redeployer_edit');
    Route::put('update-redeployer-poster-vigile/{PvNumero}',[PosterVigileController::class,'redeployer_update'])->name('poster-vigile.redeployer_update');

    Route::get('fin-deploiement-poster-vigile/{PvNumero}',[PosterVigileController::class, 'fin_deploiement_edit'])->name('poster-vigile.fin_deploiement_edit');
    Route::put('update-fin-deploiement-poster-vigile/{PvNumero}',[PosterVigileController::class,'fin_deploiement_update'])->name('poster-vigile.fin_deploiement_update');
    Route::get('/poster-vigile/imprimer', [PosterVigileController::class,'generatePDF'])->name('poster-vigile.imprimer');

    Route::get('show-profil-vigile/{PvVigile}',[PosterVigileController::class,'show_profil_vigile'])->name('poster-vigile.profil_vigile');
    Route::get('show-profil-vigile-edit/{PvVigile}',[PosterVigileController::class,'show_profil_vigile_edit'])->name('poster-vigile.profil_vigile_edit');

    Route::get('/show-sites-vigile/{PvVigile}',[PosterVigileController::class,'show_sites_vigile'])->name('poster-vigile.show_sites_vigile');
    Route::get('/show-sites-vigile-edit/{PvVigile}',[PosterVigileController::class,'show_sites_vigile_edit'])->name('poster-vigile.show_sites_vigile_edit');

    Route::get('/show-nbre-vigile-mc-contrat/{PvVigile}/{PvSiteClient}',[PosterVigileController::class,'show_nbre_vigile_mc_contrat'])->name('poster-vigile.show_nbre_vigile_mc_contrat');
    Route::get('/show-nbre-vigile-mc-contrat-edit/{PvVigile}/{PvSiteClient}',[PosterVigileController::class,'show_nbre_vigile_mc_contrat_edit'])->name('poster-vigile.show_nbre_vigile_mc_contrat_edit');


    Route::resource('sites-superviseur',SiteSuperviseurController::class);
    Route::get('edit-site-superviseur/{SsNumero}',[SiteSuperviseurController::class, 'edit'])->name('site-superviseur.edit');
    Route::put('update-site-superviseur/{SsNumero}',[SiteSuperviseurController::class,'update'])->name('site-superviseur.update');
    Route::get('activer-site-superviseur/{SsNumero}', [SiteSuperviseurController::class, 'activer'])->name('site-superviseur.activer');
    //Route::get('desactiver-site-superviseur/{SsNumero}', [SiteSuperviseurController::class, 'desactiver'])->name('site-superviseur.desactiver');
    Route::get('supprimer-site-superviseur/{SsNumero}', [SiteSuperviseurController::class, 'delete'])->name('site-superviseur.delete');
    Route::get('show-site-superviseur/{SsNumero}',[SiteSuperviseurController::class,'show'])->name('site-superviseur.show');
    Route::match(['get','post'],'/filtre-site-superviseur', [SiteSuperviseurController::class,'filtre'])->name('site-superviseur.filtre');
    Route::get('sites-superviseur/{SsNumero}/sites-supervises', [SiteSuperviseurController::class, 'sites_supervises'])->name('site-superviseur.sites_supervises');

    Route::resource('sites-supervises',SiteAgentSupCtrlController::class);
    Route::get('edit-site-supervise/{SaNumero}',[SiteAgentSupCtrlController::class, 'edit'])->name('site-supervise.edit');
    Route::put('update-site-supervise/{SaNumero}',[SiteAgentSupCtrlController::class,'update'])->name('site-supervise.update');
    Route::get('show-site-supervise/{SaNumero}',[SiteAgentSupCtrlController::class,'show'])->name('site-supervise.show');
    Route::get('activer-site-supervise/{SaNumero}', [SiteAgentSupCtrlController::class, 'activer'])->name('site-supervise.activer');
    Route::get('desactiver-site-supervise/{SaNumero}', [SiteAgentSupCtrlController::class, 'desactiver'])->name('site-supervise.desactiver');

    Route::get('fin-supervision-site-superviseur/{SsNumero}',[SiteSuperviseurController::class, 'fin_supervision_edit'])->name('site-superviseur.fin_supervision_edit');
    Route::put('update-fin-supervision-site-superviseur/{SsNumero}',[SiteSuperviseurController::class,'fin_supervision_update'])->name('site-superviseur.fin_supervision_update');
    Route::get('/site-superviseur/imprimer', [SiteSuperviseurController::class,'generatePDF'])->name('site-superviseur.imprimer');
    Route::get('/show-sites-zones-superviseur/{PeNumero}',[SiteSuperviseurController::class,'show_sites_zones'])->name('site-superviseur.show_sites_zones');
    Route::get('/show-sites-zones-edit-superviseur/{PeNumero}',[SiteSuperviseurController::class,'show_sites_zones_edit'])->name('site-superviseur.show_sites_zones_edit');

    Route::resource('supervision-sites',SupervisionSiteController::class);
    Route::get('edit-supervision-site/{OsNumero}',[SupervisionSiteController::class, 'edit'])->name('supervision-site.edit');
    Route::put('update-supervision-site/{OsNumero}',[SupervisionSiteController::class,'update'])->name('supervision-site.update');
    Route::get('edit-fermer-supervision-site/{OsNumero}',[SupervisionSiteController::class, 'edit_fermer'])->name('supervision-site.edit-fermer');
    Route::put('update-fermer-supervision-site/{OsNumero}',[SupervisionSiteController::class,'update_fermer'])->name('supervision-site.update-fermer');
    Route::get('show-supervision-site/{OsNumero}',[SupervisionSiteController::class,'show'])->name('supervision-site.show');
    Route::get('show-zones-ville/{OsVille}',[SupervisionSiteController::class,'show_zones_ville'])->name('supervision-site.zones_ville');
    Route::match(['get','post'],'/filtre-supervision-site', [SupervisionSiteController::class,'filtre'])->name('supervision-site.filtre');
    Route::get('/show-ville-superviseur/{OsSuperviseur}',[SupervisionSiteController::class,'show_ville_superviseur'])->name('supervision-site.show_ville_superviseur');

    Route::get('supervision-site/{OsNumero}/sites',[SupervisionSiteController::class, 'sites'])->name('supervision-site.sites');
    Route::get('supervision-site/{OsNumero}/pointage-agents',[SupervisionSiteController::class, 'operations'])->name('supervision-site.pointage-agents');

    Route::post('store-supervision-site-superviseur',[SupervisionSiteController::class, 'store_site_superviseur'])->name('site-superviseur.store_site_superviseur');
    Route::get('edit-supervision-site-superviseur/{SsNumero}',[SupervisionSiteController::class, 'edit_site_superviseur'])->name('site-superviseur.edit_site_superviseur');
    Route::put('update-supervision-site-superviseur/{SsNumero}',[SupervisionSiteController::class,'update_site_superviseur'])->name('site-superviseur.update_site_superviseur');
    Route::get('desactiver-supervision-site-superviseur/{SsNumero}', [SupervisionSiteController::class, 'desactiver_site_superviseur'])->name('site-superviseur.desactiver_site_superviseur');
    Route::get('show-supervision-site-superviseur/{SsNumero}',[SupervisionSiteController::class,'show_site_superviseur'])->name('site-superviseur.show_site_superviseur');
    Route::get('show-avertir-ronde/{OsSuperviseur}/{OsDate}/{OsFaction}',[SupervisionSiteController::class,'show_avertir_ronde'])->name('supervision-site.avertir-ronde');
    Route::get('/supervision-site/imprimer', [SupervisionSiteController::class,'generatePDF'])->name('supervision-site.imprimer');
    Route::get('supervision-site/{OsNumero}/print',[SupervisionSiteController::class, 'print'])->name('supervision-site.print');

    Route::resource('ronde-operations',RondeOperationController::class);
    Route::get('edit-ronde-operation/{RoNumero}',[RondeOperationController::class, 'edit'])->name('ronde-operation.edit');
    Route::put('update-ronde-operation/{RoNumero}',[RondeOperationController::class,'update'])->name('ronde-operation.update');
    Route::get('edit-fermer-ronde-operation/{RoNumero}',[RondeOperationController::class, 'edit_fermer'])->name('ronde-operation.edit-fermer');
    Route::put('update-fermer-ronde-operation/{RoNumero}',[RondeOperationController::class,'update_fermer'])->name('ronde-operation.update-fermer');
    Route::get('show-ronde-operation/{RoNumero}',[RondeOperationController::class,'show'])->name('ronde-operation.show');
    Route::get('/ronde-operation/imprimer', [RondeOperationController::class,'generatePDF'])->name('ronde-operation.imprimer');
    Route::match(['get','post'],'/filtre-ronde-operations', [RondeOperationController::class,'filtre'])->name('ronde-operations.filtre');

    Route::get('ronde-operation/{RoNumero}/sites',[RondeOperationController::class, 'sites'])->name('ronde-operation.sites');

    Route::resource('sites-ronde-oper',SiteRondeOperationController::class);
    Route::get('edit-site-ronde-oper/{SrNumero}',[SiteRondeOperationController::class, 'edit'])->name('site-ronde-oper.edit');
    Route::put('update-ronde-operation/{SrNumero}',[SiteRondeOperationController::class,'update'])->name('site-ronde-oper.update');
    Route::get('show-site-ronde-oper/{SrNumero}',[SiteRondeOperationController::class,'show'])->name('site-ronde-oper.show');
    Route::get('show-sites-client-ronde-oper/{SrClient}',[SiteRondeOperationController::class,'show_sites_client'])->name('site-ronde-oper.show_sites_client');
    Route::get('show-sites-client-ronde-oper-edit/{SrClient}',[SiteRondeOperationController::class,'show_sites_client_edit'])->name('site-ronde-oper.show_sites_client_edit');

    Route::post('/upload-doc-plan-action', UploadTemporaryDocPlanActionController::class);
    Route::post('/upload-doc-plan-action-edit', UploadTemporaryDocPlanActionEditController::class);
    Route::match(['get','post'],'/filtre-site-ronde-oper', [SiteRondeOperationController::class,'filtre'])->name('site-ronde-oper.filtre');


    Route::resource('pointage-agents',OperationSiteController::class);
    Route::get('edit-pointage-agent/{SoNumero}',[OperationSiteController::class, 'edit'])->name('pointage-agent.edit');
    Route::put('update-pointage-agent/{SoNumero}',[OperationSiteController::class,'update'])->name('pointage-agent.update');
    Route::get('supprimer-pointage-agent/{SoNumero}', [OperationSiteController::class, 'delete'])->name('pointage-agent.delete');
    Route::get('show-agent-trouve/{SoAgentPgme}',[OperationSiteController::class,'show_agent_trouve'])->name('pointage-agent.show_agent_trouve');
    Route::get('google-maps-pointage-agent/{SoNumero}', [OperationSiteController::class, 'carte_google_maps'])->name('pointage-agent.google-maps');
    Route::get('show-distance-pointage-site/{SoSite}/{latitude2}/{longitude2}',[OperationSiteController::class,'show_distance_pointage_site'])->name('pointage-agent.show_distance_pointage_site');
    Route::match(['get','post'],'/filtre-pointage-agents', [OperationSiteController::class,'filtre'])->name('pointage-agents.filtre');

    Route::get('/show-sites-zones-pointage-agent/{OsNumero}',[OperationSiteController::class,'show_sites_zones'])->name('pointage-agent.show_sites_zones');
    Route::get('/show-sites-zones-edit-pointage-agent/{OsNumero}',[OperationSiteController::class,'show_sites_zones_edit'])->name('pointage-agent.show_sites_zones_edit');

    Route::get('/show-agents-programmes-pointage-agent/{OsNumero}/{SoSite}',[OperationSiteController::class,'show_agents_programmes'])->name('pointage-agent.show_agents_programmes');
    Route::get('/show-agents-programmes-edit-pointage-agent/{OsNumero}/{SoSite}',[OperationSiteController::class,'show_agents_programmes_edit'])->name('pointage-agent.show_agents_programmes_edit');

    Route::get('/show-agents-trouves1-pointage-agent/{SoAgentPgme}',[OperationSiteController::class,'show_agents_trouves1'])->name('pointage-agent.show_agents_trouves1');
    Route::get('/show-agents-trouves1-edit-pointage-agent/{SoAgentPgme}',[OperationSiteController::class,'show_show_agents_trouves1_edit'])->name('pointage-agent.show_show_agents_trouves1_edit');

    Route::get('/show-agents-trouves2-pointage-agent/{OsNumero}/{SoSite}',[OperationSiteController::class,'show_agents_trouves2'])->name('pointage-agent.show_agents_trouves2');
    Route::get('/show-agents-trouves2-edit-pointage-agent/{OsNumero}/{SoSite}',[OperationSiteController::class,'show_show_agents_trouves2_edit'])->name('pointage-agent.show_show_agents_trouves2_edit');

    Route::resource('pointages-sanctions',PointageSanctionController::class);
    Route::get('edit-pointage-sanction/{PsNumero}',[PointageSanctionController::class, 'edit'])->name('pointage-sanction.edit');
    Route::put('update-pointage-sanction/{PsNumero}',[PointageSanctionController::class,'update'])->name('pointage-sanction.update');
    Route::get('show-pointage-sanction/{PsNumero}',[PointageSanctionController::class,'show'])->name('pointage-sanction.show');
    Route::match(['get','post'],'/filtre-pointages-sanctions', [PointageSanctionController::class,'filtre'])->name('pointages-sanctions.filtre');

    Route::get('/show-mt-defaut-sanction/{PsSanctionObserv}',[PointageSanctionController::class,'show_mt_defaut_sanction'])->name('pointage-sanction.show_mt_defaut_sanction');
    Route::get('/show-mois-paie/{AcDateEffet}',[PointageSanctionController::class,'show_mois_paie'])->name('pointage-sanction.show_mois_paie');
    Route::get('/show-personnels/{type_personnel_pointage}',[PointageSanctionController::class,'show_personnels'])->name('pointage-sanction.show_personnels');

    Route::get('/show-mt-defaut-sanction-edit/{PsSanctionObserv}',[PointageSanctionController::class,'show_mt_defaut_sanction_edit'])->name('pointage-sanction.show_mt_defaut_sanction_edit');
    Route::get('/show-mois-paie-edit/{AcDateEffet}',[PointageSanctionController::class,'show_mois_paie_edit'])->name('pointage-sanction.show_mois_paie_edit');

});
