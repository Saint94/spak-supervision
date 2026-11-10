<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

if(!function_exists('set_active_route'))
    {
        function set_active_route($route)
        {
            return request()->routeIs($route) ? 'active' : '';
        }
    }


//formater montant xaf
if(! function_exists('currency_xaf'))
{
    function currency_xaf($val)
    {
        if($val != 0 && $val != NULL)
        {
            return strrev(wordwrap(strrev($val), 3, ' ', true)).' XAF'; // 12 345 678
        }
        else
        {
            return '0 XAF';
        }
    }
}


//currency_format
if(! function_exists('currency_format'))
{
    function currency_format($number)
    {
        if ($number != 0)
        {
            return number_format($number,2,',',' '); // 123 456,78
        }
        else
        {
            return '0,00';
        }
    }
}

// Définition de la fonction
if(! function_exists('afficherMoisEnLettre'))
{
    function afficherMoisEnLettre($numMois)
    {
        $mois = [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
          ];

          if ($numMois < 1 || $numMois > 12) {
            return "";
          } else {
            return $mois[$numMois - 1];
          }
    }
}


//Formater date en français

if(! function_exists('formater_date_fr'))
{
    function formater_date_fr($date)
    {
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        return strftime('%d/%m/%Y',strtotime($date));
    }
}


//Formater date en français

if(! function_exists('dateToFrench'))
{
    function dateToFrench($date,$format)
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
        //setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        //return strftime('%A %d/%B/%Y',strtotime($date));

    }

}


//Pour le crypatage et décryptage

function array_isomorphe_complexe(){
    $array=range(0, 66);
    $alphabet=range("a", "z");
    $ALPHABET=range("A", "Z");
    $digit=array('0','1','2','3','4','5','6','7', '8','9');
    $array_letters=array_merge(array(" ","_","-","*"),$alphabet,$ALPHABET,$digit);
    $arr['ensemble']=$array;
    $arr['iso']=$array_letters;
    return $arr;
}
function array_isomorphe(){
    $array=range(0, 55);
    $alphabet=range("a", "z");
    $ALPHABET=range("A", "Z");
    $array_letters=array_merge(array("_","-","*"),$alphabet,$ALPHABET);
    $arr['ensemble']=$array;
    $arr['iso']=$array_letters;
    return $arr;
}

//Crypter

if(! function_exists('crypter'))
{
    function crypter($chaine)
    {
        $array_letters=array_isomorphe_complexe()['iso'];
        $array=array_isomorphe_complexe()['ensemble'];
        $char='';
        $j=0;
        $crypt=array();
        $ch=str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
        $ch=str_shuffle(substr($ch, 45));
        $chaine.=$ch;
        for($i=0;$i<strlen($chaine);$i++) {
            foreach ($array_letters as $key => $value) {
                if($chaine[$i]==$value){
                    $mod=intval($key)-(strlen($chaine)+$j);
                    if(intval($mod)<0)
                        $mod=count($array_letters)+intval($mod);
                    if(intval($mod)<0)
                        $mod=count($array_letters)+intval($mod);
                    array_push($crypt, $mod);
                    $j++;
                }
            }
        }
        foreach ($crypt as $k => $v){
            foreach ($array_letters as $key => $value) {
                if($v==$key){
                    $char.="".$value;
                }
            }
        }
        return $char;
    }

}

//Décrypter

if(! function_exists('decrypter'))
{
    function decrypter($chaine)
    {
        $array_letters=array_isomorphe_complexe()['iso'];
        $array=array_isomorphe_complexe()['ensemble'];
        $char='';
        $j=0;
        $crypt=array();
        for($i=0;$i<strlen($chaine);$i++) {
            foreach ($array_letters as $key => $value) {
                if($chaine[$i]==$value){
                    $mod=intval($key)+(strlen($chaine)+$j);
                    if($mod>count($array_letters))
                        $mod=$mod-count($array_letters);
                    if($mod>count($array_letters))
                        $mod=$mod-count($array_letters);
                    if($mod==count($array_letters))
                        $mod=0;
                    array_push($crypt, $mod);
                    $j++;
                }
            }
        }
        foreach ($crypt as $k => $v){
            foreach ($array_letters as $key => $value) {
                if($v==$key){
                    $char.=$value;
                }
            }
        }
        $ch=substr($char, strlen($char)-7);
        $char=str_replace($ch, "", $char);
        return $char;
    }
}


//Fin fonction cryptage et décryptage


?>


