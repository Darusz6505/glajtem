<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa dedykowana : testy na pliku IGC
*
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-03-14 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

/* !!!!!!!

takie parametry jak wyświetlanie danych, wyświetlanie mapy, umieścić jako checkboxy w tabeli danych adresowych

*/


class Igc
{

 const KATALOG = 'igc';		                           //- katalog z plikami igc

 /**
 *
 *
 */

 private $katalog = 'noszenia';										//-katalog dla plików igc

 private $w = ''; 												//-wynik działania klasy
 private $jo = false;											//-znacznik admina
 private $pliki = array();										//-tablica plików
 private $tab_lot = '';											//-traki

 /**
 *
 *
 */

 function __construct()
 {
  $this->jo = C::get('jo');									//-znacznik admina

 }

 /**
 *
 * wywołanie funkcji prywatnej
 *
 */

 public function igc_test()
 {
  $this->igc_test_pr();
 }

 /**
 *
 * analizowanie traków pobranych z lików igc, załadowanych do katalogu $this->katalog
 *
 */

 private function igc_test_pr()
 {
  $this->w = $this->pliki_igc();								// odczytanie plików igc z ustalonego katalogu

  if($jo) $this->w .= $this->info_igc();

  $this->w .= $this->konwersja_igc();

  if($this->tab_lot)												// jeśli jest tablica z lotami, to utworzenie danych dla map.js
  {

   $loty = implode(',', $this->tab_lot);

      $ww = '
		<div id="map" style=""></div>
		<script>
		var flightPoints = ['.$loty.'
		];</script>';
  }

  $this->w = $ww . $this->w;									// komunikaty dodatkowe
 }

 /**
 *
 * odczytanie plików do analizy
 *
 */

 private function pliki_igc()
 {

   if(!is_dir($path) && defined($path))
   {
    $w = C::infoBox('adm_errbox', 'Brak katalogu z plikami IGC : '.$path);
   }
   else
	{

	 $path = $this->katalog;

    $dirs = glob($path.'/*.igc');

	 $lf = 0;

	 foreach($dirs as $dir)
	 {
     if(file_exists($dir))
	  {
	   ++$lf;

		$this->pliki[$lf] = array(basename($dir), filesize($dir));
	  }
	 }

	 if($lf) ksort($this->pliki);

    unset($dirs, $dir, $wt, $lf, $path);
   }

 }

 /**
 *
 * konwersja plików igc na tablicę współrzędnych
 *
 */

 private function konwersja_igc()
 {

  $path = $this->katalog;

  if($this->pliki)
  {

	foreach($this->pliki as $war)
	{

	 $w .= $this->analiza_igc($path, $war[0]);

	}

  }
  else
   $w = C::infoBox('adm_errbox', 'Brak plików w katalogu: '.$path);

  unset($path);

  return $w;
 }


 /**
 *
 * lista plików wczytanych do analizy
 *
 */

 private function info_igc()
 {

  if($this->pliki)
  {

   $lf = 0;
	$ww = '';

	foreach($this->pliki as $war)
	{
	 ++$lf;

	 $wt[$lf] = '
	 <li>
	  <b> '.$lf.'.</b>['.$war[0].'('.$war[1].')]
	 </li>';

	}

   $w = '
	 <p>Dostepne pliki IGC</p>
	 <ul id=\'igc_lista\'>'.implode(' ', $wt).'
	 </ul>
	 <br />';

  }
  else
   $w = C::infoBox('adm_errbox', 'Brak plików w katalogu lotów: '.$path);

  unset($wt);

  return $w;
 }

 /**
 *
 * analiza dla formatu współrzędnych : DDMMSS = st min.xxxxxx
 *
 */

 private function analiza_igc($path, $name = '??')
 {

  if($jo)
	$w = '
   <br />
   <p>Analiza pliku IGC : '.$name.'</p>';


	if(file_exists($path.'/'.$name)) 										//-jeśli istnieje plik do wczytania
   {
    $nazpl = $path.'/'.$name;													//-odczytanie pliku
    $h = fopen($nazpl, 'rb');
    $tr = fread($h, filesize($nazpl));
    fclose($h);
   }
	else
	{
	 $w .= '<p>Plik nie został zbaleziony!</p>';
    return $w;
	}

   if($tr)
	{
	 $tr = preg_replace('/\r|\n/si', '|', $tr);
	 $tigc = explode('|', $tr);

	 $li = 0;

	 foreach($tigc as $war)
	 {
	  if(substr($war, 0, 1) == 'B')
	  {
	   $li++;

		$tim = substr($war, 1, 6);
		$tim1 = substr($tim, 0, 2);
		$tim2 = substr($tim, 2, 2);
		$tim3 = substr($tim, 4, 2);

		$al1 = substr($war, 7, 7);			//-szerokość geograficzna xx-xx.xxxx'
		$al1a = substr($al1, 0, 2);
		$al1b = substr($al1, 2, 2);
		$al1c = substr($al1, 4, 3);

		$al1d = $al1a.'.'.substr(round(((($al1b.'.'.$al1c)*1)/60),10), 2, 10);

		$al2 = substr($war, 15, 8);		//-długość geograficzna xx-xx.xxxx'
		$al2a = substr($al2, 0, 3);
		$al2b = substr($al2, 3, 2);
		$al2c = substr($al2, 5, 3);

		if(substr($al2a, 0, 1) == '0')
		 $al2a = substr($al2a, 1);

		$al2d = $al2a.'.'.substr(round(((($al2b.'.'.$al2c)*1)/60),10), 2, 10);

		$al3 = substr($war, 25, 5);		//-wysokość barometryczna
		$al4 = substr($war, 30, 5);		//-wysokość GPS


		//$w .= '<p>'.round(abs($al1d -$al1dp),8).' -> '.round(abs($al2d - $al2dp),8).'</p>';

		if($al1dp)
		{
		 //$analx[] = round(abs($al1d -$al1dp),8);
		 $analx = round(abs($al1d -$al1dp),8);
		}

		if($al2dp)
		{
		 //$analy[] = round(abs($al2d - $al2dp),8);
		 $analy = round(abs($al2d - $al2dp),8);
		}

		if($analx < 0.01 && $analy < 0.1) // filtr dla 'rozjechanych' założenie że pierwszy punkt jest prawidłowy
		{

		 $tt[$li] = '{"t":"'.$tim1.':'.$tim2.':'.$tim3.'","lat":"'.$al1d.'","lon":"'.$al2d.'"}';

		 $td[$li] = array($al1d, $al2d, $al3, $al4, $tim1, $tim2, $tim3);
		 //$td[$li] = array(x, y, z-baro, z-gps, h, m, s ) czas UTC

		 $al1dp = $al1d;
		 $al2dp = $al2d;
		}

	  }
	  else
	   if($war)
		{
		 //if(substr($war, 0, 1) == 'H')
		 // $w .= $war.'<br />';


	   }


	 }

	 //"pilot":"Marcin Gorayski<br \/>\r\nDudek Coden<br \/>\r\nM.3 . . . . . 131,93km",

	 $info = explode('.', $name);

	 $ttt = implode(',', $tt);

    $this->tab_lot[] = '
	  {"lastPosition":false,
		"photo":"",
		"type":"flight",
		"pilotsCount":4,
		"start":{"lat":"'.$td[1][0].'","lon":"'.$td[1][1].'"},
		"end":{"lat":"'.$td[$li][0].'","lon":"'.$td[$li][1].'"},
		"pilot":"'.preg_replace('/_/', '<br />\r\n', $info[0]).'",
		"points":['.$ttt.']}';

   }
	else
	 $w .= '<p>Plik jest pusty !</p>';

	 //$w .= '<p>'.max($analx).' -> '.max($analy).'</p>';

  return $w;
 }

 /**
 *
 *
 *
 */

 public function wynik()
 {
  return $this->w;
 }

 /**
 *
 *
 */

 function __destruct()
 {
  unset($this->w);
 }
}
?>