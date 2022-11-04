<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/*
* wersje językowe komunikatów dla Admina
*
*
*
*
*/


switch(C::get('lang'))
{

 case 'DE':

  define('_DODAJ_PUBL', 'Hinzuf&#252;gen nouer Publikation');

  define('_DODAJ_TRESC_DO_PUBL', 'Hinzuf&#252;gen von Inhalten zu Ihrer Publikation');

  //- klasy/Cms.php

  define('_SEND_BREAK', 'Die Aktion gestoppt, aktualisieren Sie die Browser-Fenster ist verboten!');

  define('_GUZIK_FORM', 'senden');

  define('_ERROR_FORM', 'Fehler in der Form!');

  //define('_POLE_WYMAGANE', 'Pflichtfeld!');

  define('_WAL_C', 'erlaubt nur Zahlen und Komma, Minus oder Punkt');

  define('_WAL_I', 'erlaubt nur Zahlen');

  define('_WAL_E', 'Ungültige E-Mail');

  define('_WAL_PASSCONF', 'Passwörter müssen identisch sein');

  define('_WALUTA', 'EUR');


 break;

 default:

  //- klasy/Cms.php



  define('_GUZIK_FORM', 'wyślij');

  define('_ERROR_FORM', 'Wskazane pola muszą być poprawione!');

  //define('_POLE_WYMAGANE', 'Pole obowiązkowe, musi zostać wypełnione!');

  define('_WAL_C', 'w tym polu mogą być tylko cyfry, przecinek, minus i kropka');

  define('_WAL_I', 'w tym polu mogą być tylko cyfry');

  define('_WAL_E', 'to nie jest poprawny adres e-mail');

  define('_WAL_PASSCONF', 'hasła nie są identyczne!');

  define('_WALUTA', 'Zł');

  define('_KO_EMPTY2', 'pusty kontener / brak wtyczki');

  /*
  define('_WAL_', '');

  define('_WAL_', '');

  define('_WAL_', '');

  define('_WAL_', ''); */

  ##################
 /*
  define('_DODAJ_PUBL', 'dodaj publikację');										//-nazwa i title odnośnika Admina dla publikacji

  define('_DODAJ_TRESC_DO_PUBL', 'dodaj blok treści'); 						//-title, odnośnika

  define('_KO_EMPTY2', 'Zawartość kontenera - PUSTA!');						//-komunikat pustego kontenera :: $kol

  define('_EDYTUJ_TEMAT_PUBL', 'Edytuj temat publikacji');
  define('_EDYTUJ_TRESC', 'Edytuj blok treści'); */


}
?>
