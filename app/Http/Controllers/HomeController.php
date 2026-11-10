<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $start_date=date('Y-m-01');
        $end_date=date('Y-m-d');

        switch (Auth::user()->type)
        {
            case 'A':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_entites = DB::table('entites')->count();

                    $count_managers = DB::table('users')
                        ->where('users.type','M')
                        ->count();

                    $count_responsables_operation = DB::table('users')
                        ->where('users.type','R')
                        ->count();

                    $count_agents_superviseur = DB::table('users')
                        ->where('users.type','S')
                        ->count();

                    $count_chefs_equipe = DB::table('users')
                        ->where('users.type','E')
                        ->count();

                    $count_agents_paie = DB::table('users')
                        ->where('users.type','P')
                        ->count();

                    $count_responsables_com = DB::table('users')
                        ->where('users.type','C')
                        ->count();

                    $count_agents_fact = DB::table('users')
                        ->where('users.type','F')
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.admin',compact(
                        'count_entites','count_managers','count_responsables_operation','count_agents_superviseur',
                        'count_chefs_equipe','count_agents_paie','count_responsables_com','count_agents_fact',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));
                        break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'M':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_clients = DB::table('clients')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_sites =DB::table('sites_client')
                        ->where('sites_client.ScEntite',Auth::user()->entite)
                        ->count();

                    $count_clients_suspendus = DB::table('clients')
                        ->where('clients.ClIsOn','N')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_avenants = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_non_couverts = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_controleurs = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['CT'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_superviseurs = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.manager',compact(
                        'count_clients','count_sites','count_clients_suspendus',
                        'count_contrats_avenants','count_contrats_non_couverts',
                        'count_controleurs',
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'R':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_clients = DB::table('clients')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_sites =DB::table('sites_client')
                        ->where('sites_client.ScEntite',Auth::user()->entite)
                        ->count();

                    $count_clients_suspendus = DB::table('clients')
                        ->where('clients.ClIsOn','N')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_avenants = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_non_couverts = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_controleurs = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['CT'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_superviseurs = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.operation',compact(
                        'count_clients','count_sites','count_clients_suspendus',
                        'count_contrats_avenants','count_contrats_non_couverts',
                        'count_controleurs',
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }


            case 'S':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_superviseurs = DB::table('personnels')
                        ->WhereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.superviseur',compact(
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'E':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_clients = DB::table('clients')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_sites =DB::table('sites_client')
                        ->where('sites_client.ScEntite',Auth::user()->entite)
                        ->count();

                    $count_clients_suspendus = DB::table('clients')
                        ->where('clients.ClIsOn','N')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_avenants = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_non_couverts = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_superviseurs = DB::table('personnels')
                        ->WhereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.manager',compact(
                        'count_clients','count_sites','count_clients_suspendus',
                        'count_contrats_avenants','count_contrats_non_couverts',
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'P':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_clients = DB::table('clients')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_sites =DB::table('sites_client')
                        ->where('sites_client.ScEntite',Auth::user()->entite)
                        ->count();

                    $count_clients_suspendus = DB::table('clients')
                        ->where('clients.ClIsOn','N')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_avenants = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_non_couverts = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_superviseurs = DB::table('personnels')
                        ->WhereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.manager',compact(
                        'count_clients','count_sites','count_clients_suspendus',
                        'count_contrats_avenants','count_contrats_non_couverts',
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'C':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_clients = DB::table('clients')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_sites =DB::table('sites_client')
                        ->where('sites_client.ScEntite',Auth::user()->entite)
                        ->count();

                    $count_clients_suspendus = DB::table('clients')
                        ->where('clients.ClIsOn','N')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_avenants = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_non_couverts = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                   

                    return view('dashboard.commerciale',compact(
                        'count_clients','count_sites','count_clients_suspendus',
                        'count_contrats_avenants','count_contrats_non_couverts'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'F':

                if(Auth::user()->reset_password == 'N')
                {
                    $count_clients = DB::table('clients')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_sites =DB::table('sites_client')
                        ->where('sites_client.ScEntite',Auth::user()->entite)
                        ->count();

                    $count_clients_suspendus = DB::table('clients')
                        ->where('clients.ClIsOn','N')
                        ->where('clients.ClEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_avenants = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_contrats_non_couverts = DB::table('avenants_contrat')
                        ->where('avenants_contrat.AcEntite',Auth::user()->entite)
                        ->count();

                    $count_superviseurs = DB::table('personnels')
                        ->WhereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.manager',compact(
                        'count_clients','count_sites','count_clients_suspendus',
                        'count_contrats_avenants','count_contrats_non_couverts',
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            case 'T':
                if(Auth::user()->reset_password == 'N')
                {
                    $count_superviseurs = DB::table('personnels')
                        ->WhereIn('personnels.PeStatut',['SG','SA'])
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_chefs_equipe = DB::table('personnels')
                        ->where('personnels.PeStatut','E')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_agents_fixe = DB::table('personnels')
                        ->where('personnels.PeStatut','F')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_maitres_chien = DB::table('personnels')
                        ->where('personnels.PeStatut','C')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_rondiers = DB::table('personnels')
                        ->where('personnels.PeStatut','R')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $count_stand_bye = DB::table('personnels')
                        ->where('personnels.PeStatut','B')
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_jour = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','J')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_couverts_nuit = DB::table('personnels')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->whereNotIn('personnels.PeNumero', function($query) {
                            $query->select('sites_oper_superv.SoAgentPgme')
                                ->from('sites_oper_superv')
                                ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                                ->where('operations_superv.OsFaction','N')
                                ->where('sites_oper_superv.SoIsPresent','N')
                                ->where('sites_oper_superv.SoEntite',Auth::user()->entite);})
                        ->where('personnels.PeEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_agents_remplaces_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->WhereNotNull('sites_oper_superv.SoNomPrenAgent')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_non_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->count();

                    $nbre_postes_visites_jour = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','J')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    $nbre_postes_visites_nuit = DB::table('sites_oper_superv')
                        ->join('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                        ->join('personnels', 'personnels.PeNumero', '=', 'operations_superv.OsSuperviseur')
                        ->whereIn('personnels.PeStatut',['SG','SA','CT'])
                        ->where('operations_superv.OsFaction','N')
                        ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
                        ->select('sites_oper_superv.SoSite', DB::raw('count(*) as total'))
                        ->groupBy('sites_oper_superv.SoSite')
                        ->first();

                    return view('dashboard.controleur',compact(
                        'count_superviseurs','count_chefs_equipe','count_agents_fixe',
                        'count_maitres_chien','count_rondiers','count_stand_bye',
                        'nbre_postes_non_couverts_jour','nbre_postes_non_couverts_nuit',
                        'nbre_agents_remplaces_jour','nbre_agents_remplaces_nuit',
                        'nbre_postes_non_visites_jour','nbre_postes_non_visites_nuit',
                        'nbre_postes_visites_jour','nbre_postes_visites_nuit'
                    ));

                    break;
                }
                else
                {
                    return redirect()->route('reset-password.index');
                }

            default:
                return view('auth.login');
                break;
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtre_dashboard_admin($start_date,$end_date,$entite)
    {

        $mt_commandes = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->sum('CcMontantTotal');

        $mt_encaissements = DB::table('encaissement_commande')
            ->join('journee_vente','journee_vente.JvNumero','=','encaissement_commande.EcJourneeVte')
            ->where('encaissement_commande.EcEntite',$entite)
            ->whereDate('encaissement_commande.EcDate','>=',$start_date)
            ->whereDate('encaissement_commande.EcDate','<=',$end_date)
            ->sum('EcMtTotalPaye');

        $mt_annule = DB::table('plat_commande')
            ->join('commande_client','commande_client.CcNumero','=','plat_commande.PcCommande')
            ->select(DB::raw('SUM(plat_commande.PcQteAnnulee*plat_commande.PcPrixUnit) as total'))
            ->where('plat_commande.PcIsAnnule','Y')
            ->where('commande_client.CcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->first();

        $mt_depenses = DB::table('depense_jour')
            ->join('journee_vente','journee_vente.JvNumero','=','depense_jour.DjJournee')
            ->where('depense_jour.DjEntite',$entite)
            ->whereDate('depense_jour.DjDate','>=',$start_date)
            ->whereDate('depense_jour.DjDate','<=',$end_date)
            ->sum('DjMontant');

        $commandes_encours = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[1,2,3])
            ->where('commande_client.CcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->count();

        $commandes_servies = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[4,5])
            ->where('commande_client.CcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->count();

        $commandes_annulees = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcIsCmdeAnnule','Y')
            ->where('commande_client.CcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->count();

        $clients_commandes = DB::table('client')
            ->join('commande_client','commande_client.CcClient','=','client.ClNumero')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->count();

        $plats_populaires_5 = DB::table('plat_commande')
            ->join('commande_client','commande_client.CcNumero','=','plat_commande.PcCommande')
            ->join('repas','repas.ReNumero','=','plat_commande.PcPlatID')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->select('ReNom',DB::raw("(sum(PcQteServie)) as qte_totale"))
            ->where('plat_commande.PcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->whereNull('plat_commande.PcBoissonID')
            //->having(DB::raw("(sum(PcQteServie))"),'>=',10)
            ->groupBy('ReNom')
            ->orderBy(DB::raw("(sum(PcQteServie))"),'desc')
            ->get();

        $boissons_consommees_5 = DB::table('plat_commande')
            ->join('commande_client','commande_client.CcNumero','=','plat_commande.PcCommande')
            ->join('boisson','boisson.BoNumero','=','plat_commande.PcBoissonID')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->select('BoNom',DB::raw("(sum(PcQteServie)) as qte_totale"))
            ->where('plat_commande.PcEntite',$entite)
            ->whereDate('commande_client.CcDate','>=',$start_date)
            ->whereDate('commande_client.CcDate','<=',$end_date)
            ->whereNull('plat_commande.PcPlatID')
            //->having(DB::raw("(sum(PcQteServie))"),'>=',10)
            ->groupBy('BoNom')
            ->orderBy(DB::raw("(sum(PcQteServie))"),'desc')
            ->get();

        $count_repas = DB::table('repas')
            ->where('repas.ReEntite',$entite)
            ->count();

        $count_boissons = DB::table('boisson')
            ->where('boisson.BoEntite',$entite)
            ->count();

        $count_espaces_restaurant = DB::table('espace_restaurant')
            ->where('espace_restaurant.ErEntite',$entite)
            ->count();

        $count_tables_clients = DB::table('table_client')
            ->where('table_client.TcEntite',$entite)
            ->count();

        $str='
        <div class="col-xl-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card income-bx">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-3 col-lg-3 col-6">
                                    <div class="line position-relative">
                                        <p class="font-w500 mb-0">TOTAL COMMANDES</p>
                                        <h2 class="mb-0 text-info">'.(currency_xaf($mt_commandes) ?? 0).'</h2>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-6">
                                    <div class="line position-relative">
                                        <p class="font-w500 mb-0">TOTAL ENCAISSEMENTS</p>
                                        <h2 class="mb-0 text-success">'.(currency_xaf($mt_encaissements) ?? 0).'</h2>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-6">
                                    <div class="line position-relative">
                                        <p class="font-w500 mb-0">TOTAL ANNUL&eacute;S</p>
                                        <h2 class="mb-0 text-primary">'.(currency_xaf($mt_annule->total) ?? 0).'</h2>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-6">
                                    <div class="position-relative">
                                        <p class="font-w500 mb-0">TOTAL D&Eacute;PENSES</p>
                                        <h2 class="mb-0 text-danger">'.(currency_xaf($mt_depenses) ?? 0).'</h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-cart4"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">REPAS</p>
                                <h4 class="mb-0">'.($count_repas ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-cup-straw"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">BOISSONS</p>
                                <h4 class="mb-0">'.($count_boissons ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-building"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">ESPACES RESTAURANTS</p>
                                <h4 class="mb-0">'.($count_espaces_restaurant ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-file-spreadsheet"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">TABLES</p>
                                <h4 class="mb-0">'.($count_tables_clients ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.26295 22.2499C7.87445 22.2499 7.49046 22.1006 7.19946 21.8096L2.68971 17.2999C2.10246 16.7134 2.10246 15.7609 2.68971 15.1744C3.27696 14.5871 4.22796 14.5871 4.81521 15.1744L8.44145 18.8006L19.3952 11.2819C20.0792 10.8124 21.0152 10.9864 21.4847 11.6704C21.955 12.3551 21.781 13.2904 21.0962 13.7606L9.11346 21.9859C8.85471 22.1629 8.55845 22.2499 8.26295 22.2499Z" fill="#FC8019"/>
                                    <path d="M8.26295 13.982C7.87445 13.982 7.49046 13.8328 7.19946 13.5418L2.68971 9.03203C2.10246 8.44479 2.10246 7.49304 2.68971 6.90654C3.27696 6.31929 4.22796 6.31929 4.81521 6.90654L8.44145 10.5328L19.3952 3.01404C20.0792 2.54454 21.0152 2.71854 21.4847 3.40254C21.955 4.08729 21.781 5.02254 21.0962 5.49279L9.11346 13.7188C8.85471 13.8958 8.55845 13.982 8.26295 13.982Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes en cours</p>
                                    <h4 class="cate-title mb-0">'.($commandes_encours ?? 0).'</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 18.5C4.05 18.5 0 14.45 0 9.5C0 4.55 4.05 0.5 9 0.5C13.95 0.5 18 4.55 18 9.5C18 14.45 13.95 18.5 9 18.5ZM9 2.375C5.1 2.375 1.875 5.6 1.875 9.5C1.875 13.4 5.1 16.625 9 16.625C12.9 16.625 16.125 13.4 16.125 9.5C16.125 5.6 12.9 2.375 9 2.375Z" fill="#FC8019"/>
                                    <path d="M7.57496 13.0251C7.34996 13.0251 7.04996 12.9501 6.89996 12.7251L5.02496 10.8501C4.64996 10.4751 4.64996 9.87515 5.02496 9.50015C5.39996 9.12515 5.99996 9.12515 6.37496 9.50015L7.57496 10.7001L11.625 6.65015C12 6.27515 12.6 6.27515 12.975 6.65015C13.35 7.02515 13.35 7.62515 12.975 8.00015L8.24996 12.7251C8.02496 12.9501 7.79996 13.0251 7.57496 13.0251Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes servies</p>
                                    <h4 class="cate-title mb-0">'.($commandes_servies ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.1156 12.8117C19.1156 13.1545 19.0927 13.4973 19.047 13.8383C19.0576 13.7609 19.0681 13.6818 19.0787 13.6045C18.9873 14.2795 18.808 14.9404 18.5425 15.568C18.5724 15.4977 18.6023 15.4274 18.6304 15.3588C18.3738 15.9652 18.0398 16.5348 17.639 17.0568C17.6847 16.9971 17.7304 16.9391 17.7761 16.8793C17.3718 17.4031 16.9007 17.8725 16.3787 18.2768C16.4384 18.2311 16.4964 18.1854 16.5562 18.1397C16.0341 18.5404 15.4646 18.8744 14.8582 19.1311C14.9285 19.1012 14.9988 19.0713 15.0673 19.0432C14.4398 19.3068 13.7789 19.4879 13.1039 19.5793C13.1812 19.5688 13.2603 19.5582 13.3377 19.5477C12.6574 19.6373 11.9666 19.6373 11.2845 19.5477C11.3619 19.5582 11.441 19.5688 11.5183 19.5793C10.8433 19.4879 10.1824 19.3086 9.55484 19.0432C9.62516 19.0731 9.69547 19.1029 9.76402 19.1311C9.15758 18.8744 8.58805 18.5404 8.06598 18.1397C8.12574 18.1854 8.18375 18.2311 8.24351 18.2768C7.71969 17.8725 7.25035 17.4014 6.84605 16.8793C6.89176 16.9391 6.93746 16.9971 6.98316 17.0568C6.58238 16.5348 6.2484 15.9652 5.99176 15.3588C6.02164 15.4291 6.05152 15.4994 6.07965 15.568C5.81597 14.9404 5.63492 14.2795 5.54351 13.6045C5.55406 13.6818 5.56461 13.7609 5.57515 13.8383C5.48551 13.158 5.48551 12.4672 5.57515 11.7852C5.56461 11.8625 5.55406 11.9416 5.54351 12.019C5.63492 11.344 5.81422 10.683 6.07965 10.0555C6.04976 10.1258 6.01988 10.1961 5.99176 10.2647C6.2484 9.65821 6.58238 9.08868 6.98316 8.56661C6.93746 8.62637 6.89176 8.68438 6.84605 8.74415C7.25035 8.22032 7.72144 7.75098 8.24351 7.34669C8.18375 7.39239 8.12574 7.43809 8.06598 7.48379C8.58805 7.08301 9.15758 6.74903 9.76402 6.49239C9.69371 6.52227 9.6234 6.55215 9.55484 6.58028C10.1824 6.31661 10.8433 6.13555 11.5183 6.04415C11.441 6.05469 11.3619 6.06524 11.2845 6.07579C11.9648 5.98614 12.6556 5.98614 13.3377 6.07579C13.2603 6.06524 13.1812 6.05469 13.1039 6.04415C13.7789 6.13555 14.4398 6.31485 15.0673 6.58028C14.997 6.5504 14.9267 6.52051 14.8582 6.49239C15.4646 6.74903 16.0341 7.08301 16.5562 7.48379C16.4964 7.43809 16.4384 7.39239 16.3787 7.34669C16.9025 7.75098 17.3718 8.22208 17.7761 8.74415C17.7304 8.68438 17.6847 8.62637 17.639 8.56661C18.0398 9.08868 18.3738 9.65821 18.6304 10.2647C18.6005 10.1943 18.5707 10.124 18.5425 10.0555C18.8062 10.683 18.9873 11.344 19.0787 12.019C19.0681 11.9416 19.0576 11.8625 19.047 11.7852C19.0927 12.1262 19.1156 12.469 19.1156 12.8117C19.1156 13.0367 19.214 13.274 19.3722 13.434C19.5252 13.5869 19.7748 13.7012 19.9945 13.6906C20.2212 13.6801 20.4568 13.6063 20.6168 13.434C20.775 13.2617 20.8752 13.0508 20.8734 12.8117C20.8716 11.9328 20.7398 11.0398 20.4673 10.2031C20.2037 9.39102 19.8257 8.60704 19.3283 7.91094C19.0646 7.54004 18.7781 7.18321 18.4617 6.85626C18.1435 6.52754 17.7972 6.23751 17.4334 5.95977C16.7548 5.43946 15.9972 5.04747 15.1974 4.75215C14.3748 4.44981 13.4941 4.29161 12.6205 4.25645C11.7345 4.22129 10.8275 4.33028 9.97496 4.57286C9.15758 4.80489 8.35953 5.16348 7.64937 5.63106C6.94625 6.09336 6.29762 6.65411 5.76148 7.30274C5.46969 7.65782 5.19371 8.02872 4.95816 8.42247C4.72086 8.81797 4.52926 9.23458 4.35347 9.66172C4.02125 10.4721 3.84019 11.3352 3.7734 12.207C3.70484 13.0912 3.78922 13.9982 3.99664 14.8596C4.19879 15.691 4.53629 16.5049 4.98101 17.2361C5.41695 17.9533 5.96363 18.6213 6.59117 19.1785C7.22398 19.7393 7.93238 20.2227 8.70406 20.5707C9.12594 20.7623 9.56012 20.9311 10.0066 21.0559C10.4636 21.1842 10.9312 21.2615 11.4023 21.316C12.2935 21.4215 13.2005 21.3617 14.0777 21.1842C14.9162 21.0137 15.7371 20.699 16.4841 20.2824C17.2171 19.8729 17.9009 19.3455 18.481 18.7373C19.0664 18.1256 19.5726 17.4225 19.9488 16.6649C20.3337 15.8914 20.6168 15.0652 20.7468 14.2109C20.8171 13.7451 20.8664 13.2793 20.8664 12.8082C20.8664 12.5832 20.7679 12.3459 20.6097 12.1859C20.4568 12.033 20.2072 11.9188 19.9875 11.9293C19.7607 11.9398 19.5252 12.0137 19.3652 12.1859C19.2158 12.3617 19.1156 12.5727 19.1156 12.8117Z" fill="#FC8019"/>
                                    <path d="M12.3128 18.2328C12.5378 18.2328 12.7751 18.1344 12.935 17.9762C13.0879 17.8233 13.2022 17.5737 13.1917 17.3539C13.1811 17.1272 13.1073 16.8916 12.935 16.7317C12.7628 16.5735 12.5518 16.475 12.3128 16.475C12.0878 16.475 11.8505 16.5735 11.6905 16.7317C11.5376 16.8846 11.4233 17.1342 11.4339 17.3539C11.4444 17.5807 11.5182 17.8162 11.6905 17.9762C11.8628 18.1344 12.0755 18.2328 12.3128 18.2328ZM10.6077 10.8518C10.6077 10.7375 10.6147 10.625 10.6305 10.5108C10.62 10.5881 10.6094 10.6672 10.5989 10.7446C10.6305 10.5178 10.6903 10.2963 10.7782 10.0854C10.7483 10.1557 10.7184 10.226 10.6903 10.2946C10.7782 10.0889 10.8907 9.89554 11.026 9.718C10.9803 9.77776 10.9346 9.83577 10.8889 9.89554C11.026 9.71975 11.1825 9.56331 11.3583 9.4262C11.2985 9.4719 11.2405 9.51761 11.1807 9.56331C11.3583 9.42796 11.5516 9.31546 11.7573 9.22757C11.687 9.25745 11.6167 9.28733 11.5481 9.31546C11.759 9.22757 11.9805 9.1678 12.2073 9.13616C12.1299 9.14671 12.0508 9.15725 11.9735 9.1678C12.2003 9.13968 12.4288 9.13968 12.6555 9.1678C12.5782 9.15725 12.4991 9.14671 12.4217 9.13616C12.6485 9.1678 12.87 9.22581 13.0827 9.3137C13.0124 9.28382 12.9421 9.25393 12.8735 9.22581C13.0792 9.3137 13.2725 9.4262 13.4501 9.56155C13.3903 9.51585 13.3323 9.47014 13.2725 9.42444C13.4483 9.56155 13.6047 9.718 13.7419 9.89378C13.6961 9.83401 13.6504 9.776 13.6047 9.71624C13.7401 9.89378 13.8526 10.0871 13.9405 10.2928C13.9106 10.2225 13.8807 10.1522 13.8526 10.0836C13.9405 10.2963 14.0002 10.516 14.0301 10.7446C14.0196 10.6672 14.009 10.5881 13.9985 10.5108C14.0266 10.7375 14.0266 10.966 13.9985 11.191C14.009 11.1137 14.0196 11.0346 14.0301 10.9573C13.9985 11.184 13.9387 11.4055 13.8508 11.6182C13.8807 11.5479 13.9106 11.4776 13.9387 11.409C13.8508 11.6147 13.7383 11.808 13.6012 11.9873C13.6469 11.9276 13.6926 11.8696 13.7383 11.8098C13.6012 11.9856 13.4448 12.142 13.269 12.2791C13.3288 12.2334 13.3868 12.1877 13.4465 12.142C13.2708 12.2756 13.0809 12.3846 12.8805 12.4742C12.6239 12.5885 12.3831 12.7678 12.1844 12.9647C11.7239 13.4235 11.4673 14.0405 11.4374 14.6873C11.4321 14.8157 11.4356 14.944 11.4356 15.0723C11.4356 15.2973 11.534 15.5346 11.6923 15.6946C11.8452 15.8475 12.0948 15.9617 12.3145 15.9512C12.5413 15.9407 12.7768 15.8668 12.9368 15.6946C13.095 15.5223 13.1934 15.3114 13.1934 15.0723C13.1934 14.893 13.1864 14.7137 13.2092 14.5362C13.1987 14.6135 13.1881 14.6926 13.1776 14.77C13.1987 14.6293 13.2356 14.494 13.2901 14.3621C13.2602 14.4325 13.2303 14.5028 13.2022 14.5713C13.2602 14.4342 13.3358 14.3076 13.4272 14.1881C13.3815 14.2479 13.3358 14.3059 13.2901 14.3657C13.378 14.2549 13.4782 14.1565 13.5889 14.0686C13.5292 14.1143 13.4711 14.16 13.4114 14.2057C13.5819 14.0756 13.7735 14 13.9616 13.9016C14.1954 13.7785 14.4098 13.6168 14.6102 13.4463C14.9635 13.1457 15.2624 12.7467 15.4487 12.3213C15.5577 12.0717 15.6561 11.8239 15.7053 11.5549C15.7545 11.2842 15.7897 11.0065 15.7756 10.7305C15.751 10.1961 15.6157 9.69339 15.3696 9.22054C14.9319 8.3803 14.1057 7.73694 13.1917 7.50139C12.6626 7.36604 12.1299 7.36429 11.5956 7.46624C11.3407 7.5137 11.1069 7.60862 10.8678 7.71057C10.6903 7.78616 10.5216 7.87932 10.3651 7.99182C9.91862 8.31175 9.53015 8.70901 9.26999 9.19944C8.99929 9.70921 8.85163 10.2735 8.84988 10.8535C8.84812 11.0785 8.94831 11.3158 9.10652 11.4758C9.25945 11.6287 9.50905 11.743 9.72878 11.7325C10.2069 11.7096 10.6059 11.3457 10.6077 10.8518Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes annulées</p>
                                    <h4 class="cate-title mb-0">'.($commandes_annulees ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.00002 0.5C7.21998 0.5 5.47992 1.02784 3.99988 2.01678C2.51983 3.00571 1.36628 4.41132 0.685089 6.05586C0.00389956 7.7004 -0.174331 9.51 0.172937 11.2558C0.520204 13.0017 1.37737 14.6053 2.63605 15.864C3.89472 17.1227 5.49837 17.9798 7.2442 18.3271C8.99004 18.6744 10.7996 18.4961 12.4442 17.8149C14.0887 17.1338 15.4943 15.9802 16.4833 14.5002C17.4722 13.0201 18 11.2801 18 9.50002C18 8.31812 17.7672 7.14779 17.3149 6.05586C16.8627 4.96393 16.1997 3.97177 15.364 3.13604C14.5283 2.30032 13.5361 1.63738 12.4442 1.18509C11.3522 0.732792 10.1819 0.5 9.00002 0.5ZM9.00002 16.7C7.57599 16.7 6.18394 16.2778 4.99991 15.4866C3.81587 14.6955 2.89303 13.571 2.34808 12.2553C1.80312 10.9397 1.66054 9.49203 1.93835 8.09536C2.21617 6.6987 2.9019 5.41578 3.90884 4.40884C4.91578 3.4019 6.1987 2.71616 7.59537 2.43835C8.99203 2.16054 10.4397 2.30312 11.7553 2.84807C13.071 3.39302 14.1955 4.31587 14.9866 5.4999C15.7778 6.68394 16.2 8.07599 16.2 9.50002C16.2 11.4096 15.4415 13.2409 14.0912 14.5912C12.7409 15.9415 10.9096 16.7 9.00002 16.7Z" fill="#FC8019"/>
                                    <path d="M9.0002 5C8.7615 5 8.53259 5.09482 8.3638 5.2636C8.19502 5.43239 8.1002 5.66131 8.1002 5.9V10.4C8.1002 10.6387 8.19502 10.8676 8.3638 11.0364C8.53259 11.2052 8.7615 11.3 9.0002 11.3C9.2389 11.3 9.46781 11.2052 9.6366 11.0364C9.80538 10.8676 9.9002 10.6387 9.9002 10.4V5.9C9.9002 5.66131 9.80538 5.43239 9.6366 5.2636C9.46781 5.09482 9.2389 5 9.0002 5ZM9.6392 12.479L9.5042 12.362L9.3422 12.281L9.1712 12.2C9.02587 12.1722 8.87593 12.1806 8.73465 12.2246C8.59336 12.2685 8.46509 12.3467 8.3612 12.452C8.28024 12.5349 8.21607 12.6327 8.1722 12.74C8.12033 12.8527 8.09567 12.976 8.1002 13.1C8.10119 13.3361 8.19493 13.5624 8.3612 13.73C8.44843 13.8122 8.54882 13.8791 8.6582 13.928C8.76593 13.9756 8.88242 14.0002 9.0002 14.0002C9.11798 14.0002 9.23447 13.9756 9.3422 13.928C9.45158 13.8791 9.55197 13.8122 9.6392 13.73C9.80547 13.5624 9.89921 13.3361 9.9002 13.1C9.89997 12.9794 9.87548 12.86 9.8282 12.749C9.78236 12.6481 9.71828 12.5566 9.6392 12.479Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Nombre de clients</p>
                                    <h4 class="cate-title mb-0">'.($clients_commandes ?? 0).'</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';

                if(count($plats_populaires_5) > 0)
                {
                    $str.='<div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="cate-title">Plats populaires</h4>
                            </div>';

                            foreach($plats_populaires_5 as $plat):
                                $str.='<div class="d-flex justify-content-between mb-2">
                                    <span><svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="16" height="16" rx="4" fill="var(--primary)"/>
                                    </svg>
                                    '.($plat->ReNom).'</span>
                                    <h6>'.($plat->qte_totale ?? 0).'</h6>
                                </div>';
                            endforeach;

                    $str.='</div>
                        </div>
                    </div>';
                }


                if(count($boissons_consommees_5) > 0)
                {
                    $str.='<div class="col-xl-12 col-sm-6">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="cate-title">Boissons les plus consomées</h4>
                                </div>';

                                foreach($boissons_consommees_5 as $boisson):
                                    $str.='<div class="d-flex justify-content-between mb-2">
                                        <span><svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="16" height="16" rx="4" fill="#1FBF75"/>
                                        </svg>
                                        '.($boisson->BoNom).'</span>
                                        <h6>'.($boisson->qte_totale ?? 0).'</h6>
                                    </div>';
                                endforeach;

                            $str.='</div>
                        </div>
                    </div>';
                }

            $str.='</div>
        </div>';

        $str.='';
        echo $str;
    }






    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtre_dashboard_vente($user,$entite)
    {
        $jour_ouvert = DB::table('journee_vente')
            ->where('journee_vente.JvEntite',$entite)
            ->whereNull('journee_vente.JvDateFerm')
            ->orWhereNull('journee_vente.JvUserFerm')
            ->orderBy('journee_vente.JvNumero','desc')
            ->first();

        $mt_commandes = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcUserCree',$user)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->sum('CcMontantTotal');

        $commandes_encours = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[1,2,3])
            ->where('commande_client.CcUserCree',$user)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $commandes_servies = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[4,5])
            ->where('commande_client.CcUserCree',$user)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $commandes_annulees = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcIsCmdeAnnule','Y')
            ->where('commande_client.CcUserCree',$user)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $clients_commandes = DB::table('client')
            ->join('commande_client','commande_client.CcClient','=','client.ClNumero')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcUserCree',$user)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $plats_populaires_5 = DB::table('plat_commande')
            ->join('commande_client','commande_client.CcNumero','=','plat_commande.PcCommande')
            ->join('repas','repas.ReNumero','=','plat_commande.PcPlatID')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->select('ReNom',DB::raw("(sum(PcQteServie)) as qte_totale"))
            ->where('plat_commande.PcEntite',$entite)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->whereNull('plat_commande.PcBoissonID')
            //->having(DB::raw("(sum(PcQteServie))"),'>=',10)
            ->groupBy('ReNom')
            ->orderBy(DB::raw("(sum(PcQteServie))"),'desc')
            ->get();

        $boissons_consommees_5 = DB::table('plat_commande')
            ->join('commande_client','commande_client.CcNumero','=','plat_commande.PcCommande')
            ->join('boisson','boisson.BoNumero','=','plat_commande.PcBoissonID')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->select('BoNom',DB::raw("(sum(PcQteServie)) as qte_totale"))
            ->where('plat_commande.PcEntite',$entite)
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->whereNull('plat_commande.PcPlatID')
            //->having(DB::raw("(sum(PcQteServie))"),'>=',10)
            ->groupBy('BoNom')
            ->orderBy(DB::raw("(sum(PcQteServie))"),'desc')
            ->get();

        $count_repas = DB::table('repas')
            ->where('repas.ReEntite',$entite)
            ->count();

        $count_boissons = DB::table('boisson')
            ->where('boisson.BoEntite',$entite)
            ->count();

        $count_espaces_restaurant = DB::table('espace_restaurant')
            ->where('espace_restaurant.ErEntite',$entite)
            ->count();

        $count_tables_clients = DB::table('table_client')
            ->where('table_client.TcEntite',$entite)
            ->count();

        $str='
        <div class="col-xl-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card income-bx">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-12">
                                    <div class="position-relative">
                                        <p class="font-w500 mb-0">TOTAL COMMANDES</p>
                                        <h2 class="mb-0 text-info">'.(currency_xaf($mt_commandes) ?? 0).'</h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-cart4"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">REPAS</p>
                                <h4 class="mb-0">'.($count_repas ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-cup-straw"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">BOISSONS</p>
                                <h4 class="mb-0">'.($count_boissons ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-building"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">ESPACES RESTAURANTS</p>
                                <h4 class="mb-0">'.($count_espaces_restaurant ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="bi bi-file-spreadsheet"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">TABLES</p>
                                <h4 class="mb-0">'.($count_tables_clients ?? 0).'</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.26295 22.2499C7.87445 22.2499 7.49046 22.1006 7.19946 21.8096L2.68971 17.2999C2.10246 16.7134 2.10246 15.7609 2.68971 15.1744C3.27696 14.5871 4.22796 14.5871 4.81521 15.1744L8.44145 18.8006L19.3952 11.2819C20.0792 10.8124 21.0152 10.9864 21.4847 11.6704C21.955 12.3551 21.781 13.2904 21.0962 13.7606L9.11346 21.9859C8.85471 22.1629 8.55845 22.2499 8.26295 22.2499Z" fill="#FC8019"/>
                                    <path d="M8.26295 13.982C7.87445 13.982 7.49046 13.8328 7.19946 13.5418L2.68971 9.03203C2.10246 8.44479 2.10246 7.49304 2.68971 6.90654C3.27696 6.31929 4.22796 6.31929 4.81521 6.90654L8.44145 10.5328L19.3952 3.01404C20.0792 2.54454 21.0152 2.71854 21.4847 3.40254C21.955 4.08729 21.781 5.02254 21.0962 5.49279L9.11346 13.7188C8.85471 13.8958 8.55845 13.982 8.26295 13.982Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes en cours</p>
                                    <h4 class="cate-title mb-0">'.($commandes_encours ?? 0).'</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 18.5C4.05 18.5 0 14.45 0 9.5C0 4.55 4.05 0.5 9 0.5C13.95 0.5 18 4.55 18 9.5C18 14.45 13.95 18.5 9 18.5ZM9 2.375C5.1 2.375 1.875 5.6 1.875 9.5C1.875 13.4 5.1 16.625 9 16.625C12.9 16.625 16.125 13.4 16.125 9.5C16.125 5.6 12.9 2.375 9 2.375Z" fill="#FC8019"/>
                                    <path d="M7.57496 13.0251C7.34996 13.0251 7.04996 12.9501 6.89996 12.7251L5.02496 10.8501C4.64996 10.4751 4.64996 9.87515 5.02496 9.50015C5.39996 9.12515 5.99996 9.12515 6.37496 9.50015L7.57496 10.7001L11.625 6.65015C12 6.27515 12.6 6.27515 12.975 6.65015C13.35 7.02515 13.35 7.62515 12.975 8.00015L8.24996 12.7251C8.02496 12.9501 7.79996 13.0251 7.57496 13.0251Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes servies</p>
                                    <h4 class="cate-title mb-0">'.($commandes_servies ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.1156 12.8117C19.1156 13.1545 19.0927 13.4973 19.047 13.8383C19.0576 13.7609 19.0681 13.6818 19.0787 13.6045C18.9873 14.2795 18.808 14.9404 18.5425 15.568C18.5724 15.4977 18.6023 15.4274 18.6304 15.3588C18.3738 15.9652 18.0398 16.5348 17.639 17.0568C17.6847 16.9971 17.7304 16.9391 17.7761 16.8793C17.3718 17.4031 16.9007 17.8725 16.3787 18.2768C16.4384 18.2311 16.4964 18.1854 16.5562 18.1397C16.0341 18.5404 15.4646 18.8744 14.8582 19.1311C14.9285 19.1012 14.9988 19.0713 15.0673 19.0432C14.4398 19.3068 13.7789 19.4879 13.1039 19.5793C13.1812 19.5688 13.2603 19.5582 13.3377 19.5477C12.6574 19.6373 11.9666 19.6373 11.2845 19.5477C11.3619 19.5582 11.441 19.5688 11.5183 19.5793C10.8433 19.4879 10.1824 19.3086 9.55484 19.0432C9.62516 19.0731 9.69547 19.1029 9.76402 19.1311C9.15758 18.8744 8.58805 18.5404 8.06598 18.1397C8.12574 18.1854 8.18375 18.2311 8.24351 18.2768C7.71969 17.8725 7.25035 17.4014 6.84605 16.8793C6.89176 16.9391 6.93746 16.9971 6.98316 17.0568C6.58238 16.5348 6.2484 15.9652 5.99176 15.3588C6.02164 15.4291 6.05152 15.4994 6.07965 15.568C5.81597 14.9404 5.63492 14.2795 5.54351 13.6045C5.55406 13.6818 5.56461 13.7609 5.57515 13.8383C5.48551 13.158 5.48551 12.4672 5.57515 11.7852C5.56461 11.8625 5.55406 11.9416 5.54351 12.019C5.63492 11.344 5.81422 10.683 6.07965 10.0555C6.04976 10.1258 6.01988 10.1961 5.99176 10.2647C6.2484 9.65821 6.58238 9.08868 6.98316 8.56661C6.93746 8.62637 6.89176 8.68438 6.84605 8.74415C7.25035 8.22032 7.72144 7.75098 8.24351 7.34669C8.18375 7.39239 8.12574 7.43809 8.06598 7.48379C8.58805 7.08301 9.15758 6.74903 9.76402 6.49239C9.69371 6.52227 9.6234 6.55215 9.55484 6.58028C10.1824 6.31661 10.8433 6.13555 11.5183 6.04415C11.441 6.05469 11.3619 6.06524 11.2845 6.07579C11.9648 5.98614 12.6556 5.98614 13.3377 6.07579C13.2603 6.06524 13.1812 6.05469 13.1039 6.04415C13.7789 6.13555 14.4398 6.31485 15.0673 6.58028C14.997 6.5504 14.9267 6.52051 14.8582 6.49239C15.4646 6.74903 16.0341 7.08301 16.5562 7.48379C16.4964 7.43809 16.4384 7.39239 16.3787 7.34669C16.9025 7.75098 17.3718 8.22208 17.7761 8.74415C17.7304 8.68438 17.6847 8.62637 17.639 8.56661C18.0398 9.08868 18.3738 9.65821 18.6304 10.2647C18.6005 10.1943 18.5707 10.124 18.5425 10.0555C18.8062 10.683 18.9873 11.344 19.0787 12.019C19.0681 11.9416 19.0576 11.8625 19.047 11.7852C19.0927 12.1262 19.1156 12.469 19.1156 12.8117C19.1156 13.0367 19.214 13.274 19.3722 13.434C19.5252 13.5869 19.7748 13.7012 19.9945 13.6906C20.2212 13.6801 20.4568 13.6063 20.6168 13.434C20.775 13.2617 20.8752 13.0508 20.8734 12.8117C20.8716 11.9328 20.7398 11.0398 20.4673 10.2031C20.2037 9.39102 19.8257 8.60704 19.3283 7.91094C19.0646 7.54004 18.7781 7.18321 18.4617 6.85626C18.1435 6.52754 17.7972 6.23751 17.4334 5.95977C16.7548 5.43946 15.9972 5.04747 15.1974 4.75215C14.3748 4.44981 13.4941 4.29161 12.6205 4.25645C11.7345 4.22129 10.8275 4.33028 9.97496 4.57286C9.15758 4.80489 8.35953 5.16348 7.64937 5.63106C6.94625 6.09336 6.29762 6.65411 5.76148 7.30274C5.46969 7.65782 5.19371 8.02872 4.95816 8.42247C4.72086 8.81797 4.52926 9.23458 4.35347 9.66172C4.02125 10.4721 3.84019 11.3352 3.7734 12.207C3.70484 13.0912 3.78922 13.9982 3.99664 14.8596C4.19879 15.691 4.53629 16.5049 4.98101 17.2361C5.41695 17.9533 5.96363 18.6213 6.59117 19.1785C7.22398 19.7393 7.93238 20.2227 8.70406 20.5707C9.12594 20.7623 9.56012 20.9311 10.0066 21.0559C10.4636 21.1842 10.9312 21.2615 11.4023 21.316C12.2935 21.4215 13.2005 21.3617 14.0777 21.1842C14.9162 21.0137 15.7371 20.699 16.4841 20.2824C17.2171 19.8729 17.9009 19.3455 18.481 18.7373C19.0664 18.1256 19.5726 17.4225 19.9488 16.6649C20.3337 15.8914 20.6168 15.0652 20.7468 14.2109C20.8171 13.7451 20.8664 13.2793 20.8664 12.8082C20.8664 12.5832 20.7679 12.3459 20.6097 12.1859C20.4568 12.033 20.2072 11.9188 19.9875 11.9293C19.7607 11.9398 19.5252 12.0137 19.3652 12.1859C19.2158 12.3617 19.1156 12.5727 19.1156 12.8117Z" fill="#FC8019"/>
                                    <path d="M12.3128 18.2328C12.5378 18.2328 12.7751 18.1344 12.935 17.9762C13.0879 17.8233 13.2022 17.5737 13.1917 17.3539C13.1811 17.1272 13.1073 16.8916 12.935 16.7317C12.7628 16.5735 12.5518 16.475 12.3128 16.475C12.0878 16.475 11.8505 16.5735 11.6905 16.7317C11.5376 16.8846 11.4233 17.1342 11.4339 17.3539C11.4444 17.5807 11.5182 17.8162 11.6905 17.9762C11.8628 18.1344 12.0755 18.2328 12.3128 18.2328ZM10.6077 10.8518C10.6077 10.7375 10.6147 10.625 10.6305 10.5108C10.62 10.5881 10.6094 10.6672 10.5989 10.7446C10.6305 10.5178 10.6903 10.2963 10.7782 10.0854C10.7483 10.1557 10.7184 10.226 10.6903 10.2946C10.7782 10.0889 10.8907 9.89554 11.026 9.718C10.9803 9.77776 10.9346 9.83577 10.8889 9.89554C11.026 9.71975 11.1825 9.56331 11.3583 9.4262C11.2985 9.4719 11.2405 9.51761 11.1807 9.56331C11.3583 9.42796 11.5516 9.31546 11.7573 9.22757C11.687 9.25745 11.6167 9.28733 11.5481 9.31546C11.759 9.22757 11.9805 9.1678 12.2073 9.13616C12.1299 9.14671 12.0508 9.15725 11.9735 9.1678C12.2003 9.13968 12.4288 9.13968 12.6555 9.1678C12.5782 9.15725 12.4991 9.14671 12.4217 9.13616C12.6485 9.1678 12.87 9.22581 13.0827 9.3137C13.0124 9.28382 12.9421 9.25393 12.8735 9.22581C13.0792 9.3137 13.2725 9.4262 13.4501 9.56155C13.3903 9.51585 13.3323 9.47014 13.2725 9.42444C13.4483 9.56155 13.6047 9.718 13.7419 9.89378C13.6961 9.83401 13.6504 9.776 13.6047 9.71624C13.7401 9.89378 13.8526 10.0871 13.9405 10.2928C13.9106 10.2225 13.8807 10.1522 13.8526 10.0836C13.9405 10.2963 14.0002 10.516 14.0301 10.7446C14.0196 10.6672 14.009 10.5881 13.9985 10.5108C14.0266 10.7375 14.0266 10.966 13.9985 11.191C14.009 11.1137 14.0196 11.0346 14.0301 10.9573C13.9985 11.184 13.9387 11.4055 13.8508 11.6182C13.8807 11.5479 13.9106 11.4776 13.9387 11.409C13.8508 11.6147 13.7383 11.808 13.6012 11.9873C13.6469 11.9276 13.6926 11.8696 13.7383 11.8098C13.6012 11.9856 13.4448 12.142 13.269 12.2791C13.3288 12.2334 13.3868 12.1877 13.4465 12.142C13.2708 12.2756 13.0809 12.3846 12.8805 12.4742C12.6239 12.5885 12.3831 12.7678 12.1844 12.9647C11.7239 13.4235 11.4673 14.0405 11.4374 14.6873C11.4321 14.8157 11.4356 14.944 11.4356 15.0723C11.4356 15.2973 11.534 15.5346 11.6923 15.6946C11.8452 15.8475 12.0948 15.9617 12.3145 15.9512C12.5413 15.9407 12.7768 15.8668 12.9368 15.6946C13.095 15.5223 13.1934 15.3114 13.1934 15.0723C13.1934 14.893 13.1864 14.7137 13.2092 14.5362C13.1987 14.6135 13.1881 14.6926 13.1776 14.77C13.1987 14.6293 13.2356 14.494 13.2901 14.3621C13.2602 14.4325 13.2303 14.5028 13.2022 14.5713C13.2602 14.4342 13.3358 14.3076 13.4272 14.1881C13.3815 14.2479 13.3358 14.3059 13.2901 14.3657C13.378 14.2549 13.4782 14.1565 13.5889 14.0686C13.5292 14.1143 13.4711 14.16 13.4114 14.2057C13.5819 14.0756 13.7735 14 13.9616 13.9016C14.1954 13.7785 14.4098 13.6168 14.6102 13.4463C14.9635 13.1457 15.2624 12.7467 15.4487 12.3213C15.5577 12.0717 15.6561 11.8239 15.7053 11.5549C15.7545 11.2842 15.7897 11.0065 15.7756 10.7305C15.751 10.1961 15.6157 9.69339 15.3696 9.22054C14.9319 8.3803 14.1057 7.73694 13.1917 7.50139C12.6626 7.36604 12.1299 7.36429 11.5956 7.46624C11.3407 7.5137 11.1069 7.60862 10.8678 7.71057C10.6903 7.78616 10.5216 7.87932 10.3651 7.99182C9.91862 8.31175 9.53015 8.70901 9.26999 9.19944C8.99929 9.70921 8.85163 10.2735 8.84988 10.8535C8.84812 11.0785 8.94831 11.3158 9.10652 11.4758C9.25945 11.6287 9.50905 11.743 9.72878 11.7325C10.2069 11.7096 10.6059 11.3457 10.6077 10.8518Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes annulées</p>
                                    <h4 class="cate-title mb-0">'.($commandes_annulees ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.00002 0.5C7.21998 0.5 5.47992 1.02784 3.99988 2.01678C2.51983 3.00571 1.36628 4.41132 0.685089 6.05586C0.00389956 7.7004 -0.174331 9.51 0.172937 11.2558C0.520204 13.0017 1.37737 14.6053 2.63605 15.864C3.89472 17.1227 5.49837 17.9798 7.2442 18.3271C8.99004 18.6744 10.7996 18.4961 12.4442 17.8149C14.0887 17.1338 15.4943 15.9802 16.4833 14.5002C17.4722 13.0201 18 11.2801 18 9.50002C18 8.31812 17.7672 7.14779 17.3149 6.05586C16.8627 4.96393 16.1997 3.97177 15.364 3.13604C14.5283 2.30032 13.5361 1.63738 12.4442 1.18509C11.3522 0.732792 10.1819 0.5 9.00002 0.5ZM9.00002 16.7C7.57599 16.7 6.18394 16.2778 4.99991 15.4866C3.81587 14.6955 2.89303 13.571 2.34808 12.2553C1.80312 10.9397 1.66054 9.49203 1.93835 8.09536C2.21617 6.6987 2.9019 5.41578 3.90884 4.40884C4.91578 3.4019 6.1987 2.71616 7.59537 2.43835C8.99203 2.16054 10.4397 2.30312 11.7553 2.84807C13.071 3.39302 14.1955 4.31587 14.9866 5.4999C15.7778 6.68394 16.2 8.07599 16.2 9.50002C16.2 11.4096 15.4415 13.2409 14.0912 14.5912C12.7409 15.9415 10.9096 16.7 9.00002 16.7Z" fill="#FC8019"/>
                                    <path d="M9.0002 5C8.7615 5 8.53259 5.09482 8.3638 5.2636C8.19502 5.43239 8.1002 5.66131 8.1002 5.9V10.4C8.1002 10.6387 8.19502 10.8676 8.3638 11.0364C8.53259 11.2052 8.7615 11.3 9.0002 11.3C9.2389 11.3 9.46781 11.2052 9.6366 11.0364C9.80538 10.8676 9.9002 10.6387 9.9002 10.4V5.9C9.9002 5.66131 9.80538 5.43239 9.6366 5.2636C9.46781 5.09482 9.2389 5 9.0002 5ZM9.6392 12.479L9.5042 12.362L9.3422 12.281L9.1712 12.2C9.02587 12.1722 8.87593 12.1806 8.73465 12.2246C8.59336 12.2685 8.46509 12.3467 8.3612 12.452C8.28024 12.5349 8.21607 12.6327 8.1722 12.74C8.12033 12.8527 8.09567 12.976 8.1002 13.1C8.10119 13.3361 8.19493 13.5624 8.3612 13.73C8.44843 13.8122 8.54882 13.8791 8.6582 13.928C8.76593 13.9756 8.88242 14.0002 9.0002 14.0002C9.11798 14.0002 9.23447 13.9756 9.3422 13.928C9.45158 13.8791 9.55197 13.8122 9.6392 13.73C9.80547 13.5624 9.89921 13.3361 9.9002 13.1C9.89997 12.9794 9.87548 12.86 9.8282 12.749C9.78236 12.6481 9.71828 12.5566 9.6392 12.479Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Nombre de clients</p>
                                    <h4 class="cate-title mb-0">'.($clients_commandes ?? 0).'</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';

                if(count($plats_populaires_5) > 0)
                {
                    $str.='<div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="cate-title">Plats populaires</h4>
                            </div>';

                            foreach($plats_populaires_5 as $plat):
                                $str.='<div class="d-flex justify-content-between mb-2">
                                    <span><svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="16" height="16" rx="4" fill="var(--primary)"/>
                                    </svg>
                                    '.($plat->ReNom).'</span>
                                    <h6>'.($plat->qte_totale ?? 0).'</h6>
                                </div>';
                            endforeach;

                    $str.='</div>
                        </div>
                    </div>';
                }


                if(count($boissons_consommees_5) > 0)
                {
                    $str.='<div class="col-xl-12 col-sm-6">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="cate-title">Boissons les plus consomées</h4>
                                </div>';

                                foreach($boissons_consommees_5 as $boisson):
                                    $str.='<div class="d-flex justify-content-between mb-2">
                                        <span><svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="16" height="16" rx="4" fill="#1FBF75"/>
                                        </svg>
                                        '.($boisson->BoNom).'</span>
                                        <h6>'.($boisson->qte_totale ?? 0).'</h6>
                                    </div>';
                                endforeach;

                            $str.='</div>
                        </div>
                    </div>';
                }

            $str.='</div>
        </div>';

        $str.='';
        echo $str;
    }



     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtre_dashboard_caisse($user,$entite)
    {
        $jour_ouvert = DB::table('journee_vente')
            ->where('journee_vente.JvEntite',$entite)
            ->whereNull('journee_vente.JvDateFerm')
            ->orWhereNull('journee_vente.JvUserFerm')
            ->orderBy('journee_vente.JvNumero','desc')
            ->first();

        $mt_commandes = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->sum('CcMontantTotal');

        $mt_encaissements = DB::table('encaissement_commande')
            ->join('journee_vente','journee_vente.JvNumero','=','encaissement_commande.EcJourneeVte')
            ->where('encaissement_commande.EcJourneeVte',$jour_ouvert->JvNumero)
            ->where('encaissement_commande.EcUserCree',$user)
            ->where('encaissement_commande.EcEntite',$entite)
            ->sum('EcMtTotalPaye');

        $commandes_encours = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[1,2,3])
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $commandes_servies = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[4,5])
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $commandes_annulees = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcIsCmdeAnnule','Y')
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $clients_commandes = DB::table('client')
            ->join('commande_client','commande_client.CcClient','=','client.ClNumero')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $str='
        <div class="col-xl-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card income-bx">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-6">
                                    <div class="line position-relative">
                                        <p class="font-w500 mb-0">TOTAL COMMANDES</p>
                                        <h2 class="mb-0 text-info">'.(currency_xaf($mt_commandes) ?? 0).'</h2>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-6">
                                    <div class="position-relative">
                                        <p class="font-w500 mb-0">TOTAL ENCAISSEMENTS</p>
                                        <h2 class="mb-0 text-success">'.(currency_xaf($mt_encaissements) ?? 0).'</h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.26295 22.2499C7.87445 22.2499 7.49046 22.1006 7.19946 21.8096L2.68971 17.2999C2.10246 16.7134 2.10246 15.7609 2.68971 15.1744C3.27696 14.5871 4.22796 14.5871 4.81521 15.1744L8.44145 18.8006L19.3952 11.2819C20.0792 10.8124 21.0152 10.9864 21.4847 11.6704C21.955 12.3551 21.781 13.2904 21.0962 13.7606L9.11346 21.9859C8.85471 22.1629 8.55845 22.2499 8.26295 22.2499Z" fill="#FC8019"/>
                                    <path d="M8.26295 13.982C7.87445 13.982 7.49046 13.8328 7.19946 13.5418L2.68971 9.03203C2.10246 8.44479 2.10246 7.49304 2.68971 6.90654C3.27696 6.31929 4.22796 6.31929 4.81521 6.90654L8.44145 10.5328L19.3952 3.01404C20.0792 2.54454 21.0152 2.71854 21.4847 3.40254C21.955 4.08729 21.781 5.02254 21.0962 5.49279L9.11346 13.7188C8.85471 13.8958 8.55845 13.982 8.26295 13.982Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes en cours</p>
                                    <h4 class="cate-title mb-0">'.($commandes_encours ?? 0).'</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 18.5C4.05 18.5 0 14.45 0 9.5C0 4.55 4.05 0.5 9 0.5C13.95 0.5 18 4.55 18 9.5C18 14.45 13.95 18.5 9 18.5ZM9 2.375C5.1 2.375 1.875 5.6 1.875 9.5C1.875 13.4 5.1 16.625 9 16.625C12.9 16.625 16.125 13.4 16.125 9.5C16.125 5.6 12.9 2.375 9 2.375Z" fill="#FC8019"/>
                                    <path d="M7.57496 13.0251C7.34996 13.0251 7.04996 12.9501 6.89996 12.7251L5.02496 10.8501C4.64996 10.4751 4.64996 9.87515 5.02496 9.50015C5.39996 9.12515 5.99996 9.12515 6.37496 9.50015L7.57496 10.7001L11.625 6.65015C12 6.27515 12.6 6.27515 12.975 6.65015C13.35 7.02515 13.35 7.62515 12.975 8.00015L8.24996 12.7251C8.02496 12.9501 7.79996 13.0251 7.57496 13.0251Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes servies</p>
                                    <h4 class="cate-title mb-0">'.($commandes_servies ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.1156 12.8117C19.1156 13.1545 19.0927 13.4973 19.047 13.8383C19.0576 13.7609 19.0681 13.6818 19.0787 13.6045C18.9873 14.2795 18.808 14.9404 18.5425 15.568C18.5724 15.4977 18.6023 15.4274 18.6304 15.3588C18.3738 15.9652 18.0398 16.5348 17.639 17.0568C17.6847 16.9971 17.7304 16.9391 17.7761 16.8793C17.3718 17.4031 16.9007 17.8725 16.3787 18.2768C16.4384 18.2311 16.4964 18.1854 16.5562 18.1397C16.0341 18.5404 15.4646 18.8744 14.8582 19.1311C14.9285 19.1012 14.9988 19.0713 15.0673 19.0432C14.4398 19.3068 13.7789 19.4879 13.1039 19.5793C13.1812 19.5688 13.2603 19.5582 13.3377 19.5477C12.6574 19.6373 11.9666 19.6373 11.2845 19.5477C11.3619 19.5582 11.441 19.5688 11.5183 19.5793C10.8433 19.4879 10.1824 19.3086 9.55484 19.0432C9.62516 19.0731 9.69547 19.1029 9.76402 19.1311C9.15758 18.8744 8.58805 18.5404 8.06598 18.1397C8.12574 18.1854 8.18375 18.2311 8.24351 18.2768C7.71969 17.8725 7.25035 17.4014 6.84605 16.8793C6.89176 16.9391 6.93746 16.9971 6.98316 17.0568C6.58238 16.5348 6.2484 15.9652 5.99176 15.3588C6.02164 15.4291 6.05152 15.4994 6.07965 15.568C5.81597 14.9404 5.63492 14.2795 5.54351 13.6045C5.55406 13.6818 5.56461 13.7609 5.57515 13.8383C5.48551 13.158 5.48551 12.4672 5.57515 11.7852C5.56461 11.8625 5.55406 11.9416 5.54351 12.019C5.63492 11.344 5.81422 10.683 6.07965 10.0555C6.04976 10.1258 6.01988 10.1961 5.99176 10.2647C6.2484 9.65821 6.58238 9.08868 6.98316 8.56661C6.93746 8.62637 6.89176 8.68438 6.84605 8.74415C7.25035 8.22032 7.72144 7.75098 8.24351 7.34669C8.18375 7.39239 8.12574 7.43809 8.06598 7.48379C8.58805 7.08301 9.15758 6.74903 9.76402 6.49239C9.69371 6.52227 9.6234 6.55215 9.55484 6.58028C10.1824 6.31661 10.8433 6.13555 11.5183 6.04415C11.441 6.05469 11.3619 6.06524 11.2845 6.07579C11.9648 5.98614 12.6556 5.98614 13.3377 6.07579C13.2603 6.06524 13.1812 6.05469 13.1039 6.04415C13.7789 6.13555 14.4398 6.31485 15.0673 6.58028C14.997 6.5504 14.9267 6.52051 14.8582 6.49239C15.4646 6.74903 16.0341 7.08301 16.5562 7.48379C16.4964 7.43809 16.4384 7.39239 16.3787 7.34669C16.9025 7.75098 17.3718 8.22208 17.7761 8.74415C17.7304 8.68438 17.6847 8.62637 17.639 8.56661C18.0398 9.08868 18.3738 9.65821 18.6304 10.2647C18.6005 10.1943 18.5707 10.124 18.5425 10.0555C18.8062 10.683 18.9873 11.344 19.0787 12.019C19.0681 11.9416 19.0576 11.8625 19.047 11.7852C19.0927 12.1262 19.1156 12.469 19.1156 12.8117C19.1156 13.0367 19.214 13.274 19.3722 13.434C19.5252 13.5869 19.7748 13.7012 19.9945 13.6906C20.2212 13.6801 20.4568 13.6063 20.6168 13.434C20.775 13.2617 20.8752 13.0508 20.8734 12.8117C20.8716 11.9328 20.7398 11.0398 20.4673 10.2031C20.2037 9.39102 19.8257 8.60704 19.3283 7.91094C19.0646 7.54004 18.7781 7.18321 18.4617 6.85626C18.1435 6.52754 17.7972 6.23751 17.4334 5.95977C16.7548 5.43946 15.9972 5.04747 15.1974 4.75215C14.3748 4.44981 13.4941 4.29161 12.6205 4.25645C11.7345 4.22129 10.8275 4.33028 9.97496 4.57286C9.15758 4.80489 8.35953 5.16348 7.64937 5.63106C6.94625 6.09336 6.29762 6.65411 5.76148 7.30274C5.46969 7.65782 5.19371 8.02872 4.95816 8.42247C4.72086 8.81797 4.52926 9.23458 4.35347 9.66172C4.02125 10.4721 3.84019 11.3352 3.7734 12.207C3.70484 13.0912 3.78922 13.9982 3.99664 14.8596C4.19879 15.691 4.53629 16.5049 4.98101 17.2361C5.41695 17.9533 5.96363 18.6213 6.59117 19.1785C7.22398 19.7393 7.93238 20.2227 8.70406 20.5707C9.12594 20.7623 9.56012 20.9311 10.0066 21.0559C10.4636 21.1842 10.9312 21.2615 11.4023 21.316C12.2935 21.4215 13.2005 21.3617 14.0777 21.1842C14.9162 21.0137 15.7371 20.699 16.4841 20.2824C17.2171 19.8729 17.9009 19.3455 18.481 18.7373C19.0664 18.1256 19.5726 17.4225 19.9488 16.6649C20.3337 15.8914 20.6168 15.0652 20.7468 14.2109C20.8171 13.7451 20.8664 13.2793 20.8664 12.8082C20.8664 12.5832 20.7679 12.3459 20.6097 12.1859C20.4568 12.033 20.2072 11.9188 19.9875 11.9293C19.7607 11.9398 19.5252 12.0137 19.3652 12.1859C19.2158 12.3617 19.1156 12.5727 19.1156 12.8117Z" fill="#FC8019"/>
                                    <path d="M12.3128 18.2328C12.5378 18.2328 12.7751 18.1344 12.935 17.9762C13.0879 17.8233 13.2022 17.5737 13.1917 17.3539C13.1811 17.1272 13.1073 16.8916 12.935 16.7317C12.7628 16.5735 12.5518 16.475 12.3128 16.475C12.0878 16.475 11.8505 16.5735 11.6905 16.7317C11.5376 16.8846 11.4233 17.1342 11.4339 17.3539C11.4444 17.5807 11.5182 17.8162 11.6905 17.9762C11.8628 18.1344 12.0755 18.2328 12.3128 18.2328ZM10.6077 10.8518C10.6077 10.7375 10.6147 10.625 10.6305 10.5108C10.62 10.5881 10.6094 10.6672 10.5989 10.7446C10.6305 10.5178 10.6903 10.2963 10.7782 10.0854C10.7483 10.1557 10.7184 10.226 10.6903 10.2946C10.7782 10.0889 10.8907 9.89554 11.026 9.718C10.9803 9.77776 10.9346 9.83577 10.8889 9.89554C11.026 9.71975 11.1825 9.56331 11.3583 9.4262C11.2985 9.4719 11.2405 9.51761 11.1807 9.56331C11.3583 9.42796 11.5516 9.31546 11.7573 9.22757C11.687 9.25745 11.6167 9.28733 11.5481 9.31546C11.759 9.22757 11.9805 9.1678 12.2073 9.13616C12.1299 9.14671 12.0508 9.15725 11.9735 9.1678C12.2003 9.13968 12.4288 9.13968 12.6555 9.1678C12.5782 9.15725 12.4991 9.14671 12.4217 9.13616C12.6485 9.1678 12.87 9.22581 13.0827 9.3137C13.0124 9.28382 12.9421 9.25393 12.8735 9.22581C13.0792 9.3137 13.2725 9.4262 13.4501 9.56155C13.3903 9.51585 13.3323 9.47014 13.2725 9.42444C13.4483 9.56155 13.6047 9.718 13.7419 9.89378C13.6961 9.83401 13.6504 9.776 13.6047 9.71624C13.7401 9.89378 13.8526 10.0871 13.9405 10.2928C13.9106 10.2225 13.8807 10.1522 13.8526 10.0836C13.9405 10.2963 14.0002 10.516 14.0301 10.7446C14.0196 10.6672 14.009 10.5881 13.9985 10.5108C14.0266 10.7375 14.0266 10.966 13.9985 11.191C14.009 11.1137 14.0196 11.0346 14.0301 10.9573C13.9985 11.184 13.9387 11.4055 13.8508 11.6182C13.8807 11.5479 13.9106 11.4776 13.9387 11.409C13.8508 11.6147 13.7383 11.808 13.6012 11.9873C13.6469 11.9276 13.6926 11.8696 13.7383 11.8098C13.6012 11.9856 13.4448 12.142 13.269 12.2791C13.3288 12.2334 13.3868 12.1877 13.4465 12.142C13.2708 12.2756 13.0809 12.3846 12.8805 12.4742C12.6239 12.5885 12.3831 12.7678 12.1844 12.9647C11.7239 13.4235 11.4673 14.0405 11.4374 14.6873C11.4321 14.8157 11.4356 14.944 11.4356 15.0723C11.4356 15.2973 11.534 15.5346 11.6923 15.6946C11.8452 15.8475 12.0948 15.9617 12.3145 15.9512C12.5413 15.9407 12.7768 15.8668 12.9368 15.6946C13.095 15.5223 13.1934 15.3114 13.1934 15.0723C13.1934 14.893 13.1864 14.7137 13.2092 14.5362C13.1987 14.6135 13.1881 14.6926 13.1776 14.77C13.1987 14.6293 13.2356 14.494 13.2901 14.3621C13.2602 14.4325 13.2303 14.5028 13.2022 14.5713C13.2602 14.4342 13.3358 14.3076 13.4272 14.1881C13.3815 14.2479 13.3358 14.3059 13.2901 14.3657C13.378 14.2549 13.4782 14.1565 13.5889 14.0686C13.5292 14.1143 13.4711 14.16 13.4114 14.2057C13.5819 14.0756 13.7735 14 13.9616 13.9016C14.1954 13.7785 14.4098 13.6168 14.6102 13.4463C14.9635 13.1457 15.2624 12.7467 15.4487 12.3213C15.5577 12.0717 15.6561 11.8239 15.7053 11.5549C15.7545 11.2842 15.7897 11.0065 15.7756 10.7305C15.751 10.1961 15.6157 9.69339 15.3696 9.22054C14.9319 8.3803 14.1057 7.73694 13.1917 7.50139C12.6626 7.36604 12.1299 7.36429 11.5956 7.46624C11.3407 7.5137 11.1069 7.60862 10.8678 7.71057C10.6903 7.78616 10.5216 7.87932 10.3651 7.99182C9.91862 8.31175 9.53015 8.70901 9.26999 9.19944C8.99929 9.70921 8.85163 10.2735 8.84988 10.8535C8.84812 11.0785 8.94831 11.3158 9.10652 11.4758C9.25945 11.6287 9.50905 11.743 9.72878 11.7325C10.2069 11.7096 10.6059 11.3457 10.6077 10.8518Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes annulées</p>
                                    <h4 class="cate-title mb-0">'.($commandes_annulees ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.00002 0.5C7.21998 0.5 5.47992 1.02784 3.99988 2.01678C2.51983 3.00571 1.36628 4.41132 0.685089 6.05586C0.00389956 7.7004 -0.174331 9.51 0.172937 11.2558C0.520204 13.0017 1.37737 14.6053 2.63605 15.864C3.89472 17.1227 5.49837 17.9798 7.2442 18.3271C8.99004 18.6744 10.7996 18.4961 12.4442 17.8149C14.0887 17.1338 15.4943 15.9802 16.4833 14.5002C17.4722 13.0201 18 11.2801 18 9.50002C18 8.31812 17.7672 7.14779 17.3149 6.05586C16.8627 4.96393 16.1997 3.97177 15.364 3.13604C14.5283 2.30032 13.5361 1.63738 12.4442 1.18509C11.3522 0.732792 10.1819 0.5 9.00002 0.5ZM9.00002 16.7C7.57599 16.7 6.18394 16.2778 4.99991 15.4866C3.81587 14.6955 2.89303 13.571 2.34808 12.2553C1.80312 10.9397 1.66054 9.49203 1.93835 8.09536C2.21617 6.6987 2.9019 5.41578 3.90884 4.40884C4.91578 3.4019 6.1987 2.71616 7.59537 2.43835C8.99203 2.16054 10.4397 2.30312 11.7553 2.84807C13.071 3.39302 14.1955 4.31587 14.9866 5.4999C15.7778 6.68394 16.2 8.07599 16.2 9.50002C16.2 11.4096 15.4415 13.2409 14.0912 14.5912C12.7409 15.9415 10.9096 16.7 9.00002 16.7Z" fill="#FC8019"/>
                                    <path d="M9.0002 5C8.7615 5 8.53259 5.09482 8.3638 5.2636C8.19502 5.43239 8.1002 5.66131 8.1002 5.9V10.4C8.1002 10.6387 8.19502 10.8676 8.3638 11.0364C8.53259 11.2052 8.7615 11.3 9.0002 11.3C9.2389 11.3 9.46781 11.2052 9.6366 11.0364C9.80538 10.8676 9.9002 10.6387 9.9002 10.4V5.9C9.9002 5.66131 9.80538 5.43239 9.6366 5.2636C9.46781 5.09482 9.2389 5 9.0002 5ZM9.6392 12.479L9.5042 12.362L9.3422 12.281L9.1712 12.2C9.02587 12.1722 8.87593 12.1806 8.73465 12.2246C8.59336 12.2685 8.46509 12.3467 8.3612 12.452C8.28024 12.5349 8.21607 12.6327 8.1722 12.74C8.12033 12.8527 8.09567 12.976 8.1002 13.1C8.10119 13.3361 8.19493 13.5624 8.3612 13.73C8.44843 13.8122 8.54882 13.8791 8.6582 13.928C8.76593 13.9756 8.88242 14.0002 9.0002 14.0002C9.11798 14.0002 9.23447 13.9756 9.3422 13.928C9.45158 13.8791 9.55197 13.8122 9.6392 13.73C9.80547 13.5624 9.89921 13.3361 9.9002 13.1C9.89997 12.9794 9.87548 12.86 9.8282 12.749C9.78236 12.6481 9.71828 12.5566 9.6392 12.479Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Nombre de clients</p>
                                    <h4 class="cate-title mb-0">'.($clients_commandes ?? 0).'</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';

            $str.='</div>
        </div>';

        $str.='';
        echo $str;
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtre_dashboard_cuisine($entite)
    {
        $jour_ouvert = DB::table('journee_vente')
            ->where('journee_vente.JvEntite',$entite)
            ->whereNull('journee_vente.JvDateFerm')
            ->orWhereNull('journee_vente.JvUserFerm')
            ->orderBy('journee_vente.JvNumero','desc')
            ->first();

        $mt_commandes = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->sum('CcMontantTotal');

        $cuisiniers = DB::table('users')
            ->where('users.entite',$entite)
            ->where('users.role','N')
            ->count();

        $commandes_encours = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[1,2,3])
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $commandes_servies = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->whereIn('commande_client.CcEtatCmdeClient',[4,5])
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $commandes_annulees = DB::table('commande_client')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcIsCmdeAnnule','Y')
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();

        $clients_commandes = DB::table('client')
            ->join('commande_client','commande_client.CcClient','=','client.ClNumero')
            ->join('journee_vente','journee_vente.JvNumero','=','commande_client.CcJourneeVte')
            ->where('commande_client.CcJourneeVte',$jour_ouvert->JvNumero)
            ->where('commande_client.CcEntite',$entite)
            ->count();


        $str='
        <div class="col-xl-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card income-bx">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-6">
                                    <div class="position-relative">
                                        <p class="font-w500 mb-0">TOTAL CUISINIERS</p>
                                        <h2 class="mb-0 text-success">'.($cuisiniers ?? 0).'</h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.26295 22.2499C7.87445 22.2499 7.49046 22.1006 7.19946 21.8096L2.68971 17.2999C2.10246 16.7134 2.10246 15.7609 2.68971 15.1744C3.27696 14.5871 4.22796 14.5871 4.81521 15.1744L8.44145 18.8006L19.3952 11.2819C20.0792 10.8124 21.0152 10.9864 21.4847 11.6704C21.955 12.3551 21.781 13.2904 21.0962 13.7606L9.11346 21.9859C8.85471 22.1629 8.55845 22.2499 8.26295 22.2499Z" fill="#FC8019"/>
                                    <path d="M8.26295 13.982C7.87445 13.982 7.49046 13.8328 7.19946 13.5418L2.68971 9.03203C2.10246 8.44479 2.10246 7.49304 2.68971 6.90654C3.27696 6.31929 4.22796 6.31929 4.81521 6.90654L8.44145 10.5328L19.3952 3.01404C20.0792 2.54454 21.0152 2.71854 21.4847 3.40254C21.955 4.08729 21.781 5.02254 21.0962 5.49279L9.11346 13.7188C8.85471 13.8958 8.55845 13.982 8.26295 13.982Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes en cours</p>
                                    <h4 class="cate-title mb-0">'.($commandes_encours ?? 0).'</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 18.5C4.05 18.5 0 14.45 0 9.5C0 4.55 4.05 0.5 9 0.5C13.95 0.5 18 4.55 18 9.5C18 14.45 13.95 18.5 9 18.5ZM9 2.375C5.1 2.375 1.875 5.6 1.875 9.5C1.875 13.4 5.1 16.625 9 16.625C12.9 16.625 16.125 13.4 16.125 9.5C16.125 5.6 12.9 2.375 9 2.375Z" fill="#FC8019"/>
                                    <path d="M7.57496 13.0251C7.34996 13.0251 7.04996 12.9501 6.89996 12.7251L5.02496 10.8501C4.64996 10.4751 4.64996 9.87515 5.02496 9.50015C5.39996 9.12515 5.99996 9.12515 6.37496 9.50015L7.57496 10.7001L11.625 6.65015C12 6.27515 12.6 6.27515 12.975 6.65015C13.35 7.02515 13.35 7.62515 12.975 8.00015L8.24996 12.7251C8.02496 12.9501 7.79996 13.0251 7.57496 13.0251Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes servies</p>
                                    <h4 class="cate-title mb-0">'.($commandes_servies ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.1156 12.8117C19.1156 13.1545 19.0927 13.4973 19.047 13.8383C19.0576 13.7609 19.0681 13.6818 19.0787 13.6045C18.9873 14.2795 18.808 14.9404 18.5425 15.568C18.5724 15.4977 18.6023 15.4274 18.6304 15.3588C18.3738 15.9652 18.0398 16.5348 17.639 17.0568C17.6847 16.9971 17.7304 16.9391 17.7761 16.8793C17.3718 17.4031 16.9007 17.8725 16.3787 18.2768C16.4384 18.2311 16.4964 18.1854 16.5562 18.1397C16.0341 18.5404 15.4646 18.8744 14.8582 19.1311C14.9285 19.1012 14.9988 19.0713 15.0673 19.0432C14.4398 19.3068 13.7789 19.4879 13.1039 19.5793C13.1812 19.5688 13.2603 19.5582 13.3377 19.5477C12.6574 19.6373 11.9666 19.6373 11.2845 19.5477C11.3619 19.5582 11.441 19.5688 11.5183 19.5793C10.8433 19.4879 10.1824 19.3086 9.55484 19.0432C9.62516 19.0731 9.69547 19.1029 9.76402 19.1311C9.15758 18.8744 8.58805 18.5404 8.06598 18.1397C8.12574 18.1854 8.18375 18.2311 8.24351 18.2768C7.71969 17.8725 7.25035 17.4014 6.84605 16.8793C6.89176 16.9391 6.93746 16.9971 6.98316 17.0568C6.58238 16.5348 6.2484 15.9652 5.99176 15.3588C6.02164 15.4291 6.05152 15.4994 6.07965 15.568C5.81597 14.9404 5.63492 14.2795 5.54351 13.6045C5.55406 13.6818 5.56461 13.7609 5.57515 13.8383C5.48551 13.158 5.48551 12.4672 5.57515 11.7852C5.56461 11.8625 5.55406 11.9416 5.54351 12.019C5.63492 11.344 5.81422 10.683 6.07965 10.0555C6.04976 10.1258 6.01988 10.1961 5.99176 10.2647C6.2484 9.65821 6.58238 9.08868 6.98316 8.56661C6.93746 8.62637 6.89176 8.68438 6.84605 8.74415C7.25035 8.22032 7.72144 7.75098 8.24351 7.34669C8.18375 7.39239 8.12574 7.43809 8.06598 7.48379C8.58805 7.08301 9.15758 6.74903 9.76402 6.49239C9.69371 6.52227 9.6234 6.55215 9.55484 6.58028C10.1824 6.31661 10.8433 6.13555 11.5183 6.04415C11.441 6.05469 11.3619 6.06524 11.2845 6.07579C11.9648 5.98614 12.6556 5.98614 13.3377 6.07579C13.2603 6.06524 13.1812 6.05469 13.1039 6.04415C13.7789 6.13555 14.4398 6.31485 15.0673 6.58028C14.997 6.5504 14.9267 6.52051 14.8582 6.49239C15.4646 6.74903 16.0341 7.08301 16.5562 7.48379C16.4964 7.43809 16.4384 7.39239 16.3787 7.34669C16.9025 7.75098 17.3718 8.22208 17.7761 8.74415C17.7304 8.68438 17.6847 8.62637 17.639 8.56661C18.0398 9.08868 18.3738 9.65821 18.6304 10.2647C18.6005 10.1943 18.5707 10.124 18.5425 10.0555C18.8062 10.683 18.9873 11.344 19.0787 12.019C19.0681 11.9416 19.0576 11.8625 19.047 11.7852C19.0927 12.1262 19.1156 12.469 19.1156 12.8117C19.1156 13.0367 19.214 13.274 19.3722 13.434C19.5252 13.5869 19.7748 13.7012 19.9945 13.6906C20.2212 13.6801 20.4568 13.6063 20.6168 13.434C20.775 13.2617 20.8752 13.0508 20.8734 12.8117C20.8716 11.9328 20.7398 11.0398 20.4673 10.2031C20.2037 9.39102 19.8257 8.60704 19.3283 7.91094C19.0646 7.54004 18.7781 7.18321 18.4617 6.85626C18.1435 6.52754 17.7972 6.23751 17.4334 5.95977C16.7548 5.43946 15.9972 5.04747 15.1974 4.75215C14.3748 4.44981 13.4941 4.29161 12.6205 4.25645C11.7345 4.22129 10.8275 4.33028 9.97496 4.57286C9.15758 4.80489 8.35953 5.16348 7.64937 5.63106C6.94625 6.09336 6.29762 6.65411 5.76148 7.30274C5.46969 7.65782 5.19371 8.02872 4.95816 8.42247C4.72086 8.81797 4.52926 9.23458 4.35347 9.66172C4.02125 10.4721 3.84019 11.3352 3.7734 12.207C3.70484 13.0912 3.78922 13.9982 3.99664 14.8596C4.19879 15.691 4.53629 16.5049 4.98101 17.2361C5.41695 17.9533 5.96363 18.6213 6.59117 19.1785C7.22398 19.7393 7.93238 20.2227 8.70406 20.5707C9.12594 20.7623 9.56012 20.9311 10.0066 21.0559C10.4636 21.1842 10.9312 21.2615 11.4023 21.316C12.2935 21.4215 13.2005 21.3617 14.0777 21.1842C14.9162 21.0137 15.7371 20.699 16.4841 20.2824C17.2171 19.8729 17.9009 19.3455 18.481 18.7373C19.0664 18.1256 19.5726 17.4225 19.9488 16.6649C20.3337 15.8914 20.6168 15.0652 20.7468 14.2109C20.8171 13.7451 20.8664 13.2793 20.8664 12.8082C20.8664 12.5832 20.7679 12.3459 20.6097 12.1859C20.4568 12.033 20.2072 11.9188 19.9875 11.9293C19.7607 11.9398 19.5252 12.0137 19.3652 12.1859C19.2158 12.3617 19.1156 12.5727 19.1156 12.8117Z" fill="#FC8019"/>
                                    <path d="M12.3128 18.2328C12.5378 18.2328 12.7751 18.1344 12.935 17.9762C13.0879 17.8233 13.2022 17.5737 13.1917 17.3539C13.1811 17.1272 13.1073 16.8916 12.935 16.7317C12.7628 16.5735 12.5518 16.475 12.3128 16.475C12.0878 16.475 11.8505 16.5735 11.6905 16.7317C11.5376 16.8846 11.4233 17.1342 11.4339 17.3539C11.4444 17.5807 11.5182 17.8162 11.6905 17.9762C11.8628 18.1344 12.0755 18.2328 12.3128 18.2328ZM10.6077 10.8518C10.6077 10.7375 10.6147 10.625 10.6305 10.5108C10.62 10.5881 10.6094 10.6672 10.5989 10.7446C10.6305 10.5178 10.6903 10.2963 10.7782 10.0854C10.7483 10.1557 10.7184 10.226 10.6903 10.2946C10.7782 10.0889 10.8907 9.89554 11.026 9.718C10.9803 9.77776 10.9346 9.83577 10.8889 9.89554C11.026 9.71975 11.1825 9.56331 11.3583 9.4262C11.2985 9.4719 11.2405 9.51761 11.1807 9.56331C11.3583 9.42796 11.5516 9.31546 11.7573 9.22757C11.687 9.25745 11.6167 9.28733 11.5481 9.31546C11.759 9.22757 11.9805 9.1678 12.2073 9.13616C12.1299 9.14671 12.0508 9.15725 11.9735 9.1678C12.2003 9.13968 12.4288 9.13968 12.6555 9.1678C12.5782 9.15725 12.4991 9.14671 12.4217 9.13616C12.6485 9.1678 12.87 9.22581 13.0827 9.3137C13.0124 9.28382 12.9421 9.25393 12.8735 9.22581C13.0792 9.3137 13.2725 9.4262 13.4501 9.56155C13.3903 9.51585 13.3323 9.47014 13.2725 9.42444C13.4483 9.56155 13.6047 9.718 13.7419 9.89378C13.6961 9.83401 13.6504 9.776 13.6047 9.71624C13.7401 9.89378 13.8526 10.0871 13.9405 10.2928C13.9106 10.2225 13.8807 10.1522 13.8526 10.0836C13.9405 10.2963 14.0002 10.516 14.0301 10.7446C14.0196 10.6672 14.009 10.5881 13.9985 10.5108C14.0266 10.7375 14.0266 10.966 13.9985 11.191C14.009 11.1137 14.0196 11.0346 14.0301 10.9573C13.9985 11.184 13.9387 11.4055 13.8508 11.6182C13.8807 11.5479 13.9106 11.4776 13.9387 11.409C13.8508 11.6147 13.7383 11.808 13.6012 11.9873C13.6469 11.9276 13.6926 11.8696 13.7383 11.8098C13.6012 11.9856 13.4448 12.142 13.269 12.2791C13.3288 12.2334 13.3868 12.1877 13.4465 12.142C13.2708 12.2756 13.0809 12.3846 12.8805 12.4742C12.6239 12.5885 12.3831 12.7678 12.1844 12.9647C11.7239 13.4235 11.4673 14.0405 11.4374 14.6873C11.4321 14.8157 11.4356 14.944 11.4356 15.0723C11.4356 15.2973 11.534 15.5346 11.6923 15.6946C11.8452 15.8475 12.0948 15.9617 12.3145 15.9512C12.5413 15.9407 12.7768 15.8668 12.9368 15.6946C13.095 15.5223 13.1934 15.3114 13.1934 15.0723C13.1934 14.893 13.1864 14.7137 13.2092 14.5362C13.1987 14.6135 13.1881 14.6926 13.1776 14.77C13.1987 14.6293 13.2356 14.494 13.2901 14.3621C13.2602 14.4325 13.2303 14.5028 13.2022 14.5713C13.2602 14.4342 13.3358 14.3076 13.4272 14.1881C13.3815 14.2479 13.3358 14.3059 13.2901 14.3657C13.378 14.2549 13.4782 14.1565 13.5889 14.0686C13.5292 14.1143 13.4711 14.16 13.4114 14.2057C13.5819 14.0756 13.7735 14 13.9616 13.9016C14.1954 13.7785 14.4098 13.6168 14.6102 13.4463C14.9635 13.1457 15.2624 12.7467 15.4487 12.3213C15.5577 12.0717 15.6561 11.8239 15.7053 11.5549C15.7545 11.2842 15.7897 11.0065 15.7756 10.7305C15.751 10.1961 15.6157 9.69339 15.3696 9.22054C14.9319 8.3803 14.1057 7.73694 13.1917 7.50139C12.6626 7.36604 12.1299 7.36429 11.5956 7.46624C11.3407 7.5137 11.1069 7.60862 10.8678 7.71057C10.6903 7.78616 10.5216 7.87932 10.3651 7.99182C9.91862 8.31175 9.53015 8.70901 9.26999 9.19944C8.99929 9.70921 8.85163 10.2735 8.84988 10.8535C8.84812 11.0785 8.94831 11.3158 9.10652 11.4758C9.25945 11.6287 9.50905 11.743 9.72878 11.7325C10.2069 11.7096 10.6059 11.3457 10.6077 10.8518Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Commandes annulées</p>
                                    <h4 class="cate-title mb-0">'.($commandes_annulees ?? 0).'</h4>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-bx style-3 me-3">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.00002 0.5C7.21998 0.5 5.47992 1.02784 3.99988 2.01678C2.51983 3.00571 1.36628 4.41132 0.685089 6.05586C0.00389956 7.7004 -0.174331 9.51 0.172937 11.2558C0.520204 13.0017 1.37737 14.6053 2.63605 15.864C3.89472 17.1227 5.49837 17.9798 7.2442 18.3271C8.99004 18.6744 10.7996 18.4961 12.4442 17.8149C14.0887 17.1338 15.4943 15.9802 16.4833 14.5002C17.4722 13.0201 18 11.2801 18 9.50002C18 8.31812 17.7672 7.14779 17.3149 6.05586C16.8627 4.96393 16.1997 3.97177 15.364 3.13604C14.5283 2.30032 13.5361 1.63738 12.4442 1.18509C11.3522 0.732792 10.1819 0.5 9.00002 0.5ZM9.00002 16.7C7.57599 16.7 6.18394 16.2778 4.99991 15.4866C3.81587 14.6955 2.89303 13.571 2.34808 12.2553C1.80312 10.9397 1.66054 9.49203 1.93835 8.09536C2.21617 6.6987 2.9019 5.41578 3.90884 4.40884C4.91578 3.4019 6.1987 2.71616 7.59537 2.43835C8.99203 2.16054 10.4397 2.30312 11.7553 2.84807C13.071 3.39302 14.1955 4.31587 14.9866 5.4999C15.7778 6.68394 16.2 8.07599 16.2 9.50002C16.2 11.4096 15.4415 13.2409 14.0912 14.5912C12.7409 15.9415 10.9096 16.7 9.00002 16.7Z" fill="#FC8019"/>
                                    <path d="M9.0002 5C8.7615 5 8.53259 5.09482 8.3638 5.2636C8.19502 5.43239 8.1002 5.66131 8.1002 5.9V10.4C8.1002 10.6387 8.19502 10.8676 8.3638 11.0364C8.53259 11.2052 8.7615 11.3 9.0002 11.3C9.2389 11.3 9.46781 11.2052 9.6366 11.0364C9.80538 10.8676 9.9002 10.6387 9.9002 10.4V5.9C9.9002 5.66131 9.80538 5.43239 9.6366 5.2636C9.46781 5.09482 9.2389 5 9.0002 5ZM9.6392 12.479L9.5042 12.362L9.3422 12.281L9.1712 12.2C9.02587 12.1722 8.87593 12.1806 8.73465 12.2246C8.59336 12.2685 8.46509 12.3467 8.3612 12.452C8.28024 12.5349 8.21607 12.6327 8.1722 12.74C8.12033 12.8527 8.09567 12.976 8.1002 13.1C8.10119 13.3361 8.19493 13.5624 8.3612 13.73C8.44843 13.8122 8.54882 13.8791 8.6582 13.928C8.76593 13.9756 8.88242 14.0002 9.0002 14.0002C9.11798 14.0002 9.23447 13.9756 9.3422 13.928C9.45158 13.8791 9.55197 13.8122 9.6392 13.73C9.80547 13.5624 9.89921 13.3361 9.9002 13.1C9.89997 12.9794 9.87548 12.86 9.8282 12.749C9.78236 12.6481 9.71828 12.5566 9.6392 12.479Z" fill="#FC8019"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-w500 mb-0">Nombre de clients</p>
                                    <h4 class="cate-title mb-0">'.($clients_commandes ?? 0).'</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';

            $str.='</div>
        </div>';

        $str.='';
        echo $str;
    }

}
