<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');


/*
* wtyczka uniwersalna-> dane kontaktowe / adresowe + formularz kontaktowy :: dla : Ryszard Najmna 2014 ver.1.0
*
* 2014-03-04
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-03-14 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

$r = new DaneAdres();

$wtyk = $r->daneAdres();

$rr = new Form_Kontakt('kontakt', 'Formularz kontaktowy', ' wiadomość...'); //, 'Formularz kontaktowy', ' wiadomość...'

//-[akcja dla formularza=nazwa strony bez .html] */

/*
konstruct sprawdza czy zostały wysłane dane przez formularz
1. jeśli tak to walidacja
2. jeśli nie to wyswietla formularz

*/

   C::add('adcss', '
	<link rel="stylesheet" href="./application/kontakt_20190714.css" type="text/css" media="screen" />');			//-css dlo formatowania zdjęć

$wtyk .= $rr->wynik();

unset($rr);

//$wtyk .= $r->mapa(600, 300);

unset($r);
?>