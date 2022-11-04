<?
defined('_CONPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wersje językowe komunikatów dla strony: v.1.2
*
* 2012-02-26 -> dodano _STOPKA
* 2011-12-28 -> rozdzielono komunikaty dla strony i dla administratora
*
* autorem skryptu jest Dariusz Golczewski, aleproste.pl -------- 2011-02-05 --- UTF-8
*
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


switch(C::get('lang'))
{

 case 'DE':
  define('_KOMUNIKAT_SITE', 'notice'); //-nazwa podstrony dla komunikatów

  define('_KOMUNIKAT', 'komunikat');
  define('_KOMUNIKAT_OUT','brak aktywnych komunikatów');

  define('_POWROT', 'zur&#252;ck');
  define('_OPUBLIKOWANE', 'publiziert');

  define('_CZYTAJ_WIECEJ', 'mehr lesen');

  define('_DATA_DODANIA', 'hinzugef&#252;gt');

  define('_KOM_EMPTY_PUBL','Seite in Bearbeitung!<b>......</b>');

  define('_BACK', 'zur&#252;ck');														//-link powrotny dla publikacji publikacji

  define('_STOPKA', 'Design und Implementierung');

  define('_BRAK_KOMUN', 'Keine aktive Kommunikation.');

  define('_UWAGA', 'beachten!');

  define('_SEND_PROBLEM', 'Sorry, aber es gibt ein Problem mit Mailversand.<br />Bitte versuchen Sie es in ein paar Minuten, oder informieren Sie uns &#252;ber dieses Problem.');

  define('_AUTORNAME', 'O lataniu paralotnią od - Dariusz Fly');
  define('_AUTORLINK', 'O lataniu paralotnią od - Dariusz Fly');

 break;

 default:

  define('_KOMUNIKAT_SITE', 'komunikat'); 										//-nazwa podstrony dla komunikatów

  define('_KOMUNIKAT_OUT','brak aktywnych komunikatów');

  define('_POWROT', 'powrót');
  define('_OPUBLIKOWANE', 'opublikowano');

  define('_DATA_DODANIA', 'dodano ');
  define('_CZYTAJ_WIECEJ', 'czytaj dalej');

  define('_UWAGA', 'Uwaga!');

  define('_SEND_PROBLEM', 'Przepraszamy, ale z przyczyn technicznych formularz nie został wysłany.');

  define('_BRAK_KOMUN', 'Brak aktywnych komunikatów.');

  define('_BACK', 'powrót'); 															//-link powrotny dla publikacji publikacji

  define('_KOM_EMPTY_PUBL','Publikacja w trakcie redagowania!<b>Zapraszamy wkrótce.</b>');

  define('_STOPKA', 'Projekt i realizacja');

  define('_USER_DOST_KOM', '
<p class=\'error\'>Dostęp tylko dla zalogowanych użytkowników!</p>');

  define('_AUTOR', 'autor - Dariusz Fly glajtem.pl');										//- w stopce
  define('_AUTOR2', 'O lataniu paralotnią od - Dariusz Fly');	 			//- w sekcji head- author
  define('_AUTORNAME', 'Dariusz Fly');
  define('_AUTORLINK', 'glaitem.pl');



}
?>