<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/** v.1.1
*
* wersje językowe dla akcji Administratora i CMS'a
*
* 2016-05-10 : nowe definicje
* 2012-12-13 : poprawki Notis
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2012-12-05 ------------ UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class L
{

 public static function co($arg)
 {

  $kom = array
  (
	'pole_wymagane' => 'Pole obowiązkowe, musi zostać wypełnione!'


  );

  if(isset($kom[$arg]))
   return $kom[$arg];
  else
   return 'ND';

 }

 /*
 *
 *
 *
 */

 public static function k($arg)
 {

  /*
  if(!isset(C::get('lang', false)))
   $lang = 'xxx';									//-jeśli nie ustawiony to domyślny
  else
   $lang = C::get('lang');		 */

  $lang = 'xx';

  switch($lang)
  {
	case 'DE':

	 $ko = array(
		'kasT'				=> 'Kasowanie publikacji',
		'kas'					=> 'Naprawdę chcesz skasować ? Akcja jest nieodrwacalna!',
		'kasB'				=> 'Kasowanie bloku treści',
		'kasB1'				=> 'Kasowanie bloku treści',
		'edT'					=> 'Edytuj Tytuł',
		'edT1'				=> 'Edytuj tytuł publikacji',
		'edB'					=> 'Edytuj Treść',
		'edB1'				=> 'Edytuj treść bloku publikacji',
		'dodB'				=> 'Dodaj Blok Treści',
		'dodB1'				=> 'Dodaj blok treści',
		'dodfo'				=> 'Dodaj zdjęcia do bloku',
		'dodfg'				=> 'Dodaj zdjęcie z galerii',
		'kasuj'				=> 'Kasuj',
		'edytuj'				=> 'Edytuj',
		'doKadr'				=> 'Zdjęcie czeka na wykadrowanie',
		'doKadr1'			=> 'Zdjęcie należy wykadrować! Kliknij',
		'doKadr2'			=> 'WYKADROWAĆ',
		'kasF'				=> 'Kasuj zdjęcie',
		'edyF'				=> 'edytuj zdjęcie',
		'blokF'				=> 'ZABLOKOWANE',
		'kasF'				=> 'kasuj zdjęcia bloku',
		'kasF1'				=> 'kasuj zdjęcia dla wybranego bloku'
	 );

	break;

   default:

	 $ko = array(
		'kasT'				=> 'Kasowanie publikacji',
		'kas'					=> 'Naprawdę chcesz skasować? Akcja jest nieodrwacalna!',
		'kasB'				=> 'Kasowanie bloku treści',
		'kasB1'				=> 'Kasowanie bloku treści',
		'edT'					=> 'Edytuj Tytuł',
		'edT1'				=> 'Edytuj tytuł publikacji',
		'edB'					=> 'Edytuj Treść',
		'edB1'				=> 'Edytuj treść bloku publikacji',
		'dodB'				=> 'Dodaj Blok Treści',
		'dodB1'				=> 'Dodaj blok treści',
		'dodfo'				=> 'Dodaj zdjęcia do bloku',
		'dodfg'				=> 'Dodaj zdjęcia z galerii',
		'dodpl'				=> 'Dodaj pliki do bloku',
		'kasuj'				=> 'Kasuj',
		'edytuj'				=> 'Edytuj',
		'doKadr'				=> 'Zdjęcie czeka na wykadrowanie',
		'doKadr1'			=> 'Zdjęcie należy wykadrować! Kliknij',
		'doKadr2'			=> 'WYKADROWAĆ',
		'kasF'				=> 'Kasuj zdjęcie',
		'edyF'				=> 'edytuj zdjęcie',
		'blokF'				=> 'ZABLOKOWANE',
		'kasF'				=> 'kasuj zdjęcia bloku',
		'kasF1'				=> 'kasuj zdjęcia dla wybranego bloku'
	 );

  }

  if(isset($ko[$arg]))
   return $ko[$arg];
  else
   return 'ND';
 }


}