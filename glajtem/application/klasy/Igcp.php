<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa dedykowana : testy na pliku IGC
*
* 2016-03-13
* 2016-01-17
* 2016-01-06
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-03-14 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
* klasa generuje plik z trakami ( współżędnymi ) wygenerowanymi na podstawie plików igc
*
*/


class Igcp
{

 /**
 *
 *
 */

 private $katalog = 'noszenia';								//-katalog dla plików igc

 private $w = ''; 												//-wynik działania klasy
 private $jo = false;											//-znacznik admina
 private $pliki = array();										//-tablica plików
 private $tab_lot = '';											//-traki
 private $lot = '';												//-dane pojedyńczego lotu
 private $plik_name = 'loty.js';								//-nazwa pliku z przekompilowanymi lotami
 private $plik_kominy = 'kominy.js';						//-nazwa pliku z samymi noszeniami
 private $licz = 0;												//-wskaźnik lotów, jeśli więcej niż 1 = 1
 private $kom = 0;												//-wskaźnik kominów przy analizie więcej niż jednego lotu = 1
 private $n = FALSE;												//-wskaźnik generowania mapy noszeń a nie lotów

 private $lim_h = 100;											//-dolny limit przewyższenia w kominie w m
 private $dok = 8;												//-dokładność obliczeń dla wspórzędnych = ilość miejsc po przecinku

 private $s = array();
 private $k2 = array();

 private $akcja = '';
 private $opcja = '';

 /**
 *
 *
 */

 function __construct()
 {

  $this->jo = C::get('jo');									//-znacznik admina
  $this->akcja = C::get('akcja');
  $this->opcja = C::get('opcja');

   C::add('javascript_down', '
   <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCL0HQrJAK15EaEdTH6L5H4uOh_q4KDfBY&callback=loadIGC" async defer type="text/javascript"></script>
	<script type="text/javascript" src="js/map_fly.js"></script>
   ');

	/*
    C::add('javascript_down', '
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<script src="//maps.googleapis.com/maps/api/js?v=3.exp&signed_in=false"></script>
   <script type="text/javascript" src="js/map.js"></script>'); */
 }

 /**
 *
 * wywołanie funkcji prywatnej
 *
 */

 public function igc_test()
 {

	if($this->opcja == 'xterma')
	{

	 if($this->jo)
	 {
	   $this->n = TRUE;												//-wskaźnik, że dotyczy noszeń

      $this->plik_name = $this->plik_kominy;					//-inna nazwa dla pliku wynikowego z danymi

		$this->igc_test_pr();
    }

	}
	elseif($this->opcja == 'xloty')
	{
	 if($this->jo)
     $this->igc_test_pr();
	}
	elseif($this->opcja == 'terma')
	{
	 C::add('javascript_down', '
   <script type="text/javascript" src="noszenia/kominy.js"></script>');

	 $this->w = '<div id="map" style=""></div>';
	}
	else
	{
	 C::add('javascript_down', '
	<script type="text/javascript" src="noszenia/loty.js"></script>');

	 $this->w = '<div id="map" style=""></div>';
	}

	if($this->jo)
	{

	 $this->w .= '<div class=\'menudown menu width\'>
	  <a class=\'migc\' href="xloty+loty.html">loty generator</a>
	  <a class=\'migc\' href="xterma+loty.html">terma generator</a>
	 </div>';

	}

 }

 /**
 *
 *
 *
 */

 private function igc_test_pr()
 {
  if($this->pliki_igc())											// odczytanie plików igc z ustalonego katalogu
  {
   if($this->jo)
	 $this->info_igc();												//-wyświetlenie listy plików

   $plik = $this->katalog.'/'.$this->plik_name; 			//-skasowanie poprzedniego pliku
   if(file_exists($plik))
    unlink($plik);

   $this->lot = 'var flightPoints = [';						//-nagłówek nowego pliku
   $this->do_pliku('Początek pliku: '.$plik);


   $this->w .= $this->konwersja_igc();							//-operacje na plikach igc

   if($this->tab_lot)												// jeśli jest tablica z lotami, to utworzenie danych dla map.js
   {

    $loty = implode(',', $this->tab_lot);

      $ww = '
		<div id="map" style=""></div>
		<script>
		var flightPoints = ['.$loty.'
		];</script>';
   }


   $this->lot = ']';													//-zakończenie nowego pliku
   $this->do_pliku('Koniec pliku: '.$plik);

   unset($plik);
  }

  $this->w = $ww . $this->w;										// komunikaty dodatkowe
 }

 /**
 *
 * odczytanie listy plików do analizy
 * odczytane nazwy plików znajdują się w tablicy  $this->pliki
 *
 */

 private function pliki_igc()
 {
  $path = $this->katalog;

  if(!is_dir($path) && defined($path))
  {
   $this->w = C::infoBox('adm_errbox', 'Brak katalogu z plikami IGC : '.$path);
   return 0;
  }
  else
  {

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

  if($this->pliki) return 1;
 }

 /**
 *
 * wyświetlenie nazw z listy plików wczytanych do analizy
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
 * operacje na plikach igc znajdujących się w katalogu $this->katalog
 *
 */

 private function konwersja_igc()
 {
  $w = '';

  $path = $this->katalog;

  if($this->pliki)
  {

	foreach($this->pliki as $war)
	{

	 $w .= $this->analiza_igc($path, $war[0]);

	 if($this->lot) $this->do_pliku($war[0]);

	}

  }
  else
   $w = C::infoBox('adm_errbox', 'Brak plików w katalogu: '.$path);

  unset($path);

  return $w;
 }

 /**
 *
 * analiza dla formatu współrzędnych : DDMMSS = st min.xxxxxx
 *
 */

 private function analiza_igc($path, $name = '??')
 {
  $d_time = $ttmp = 0;

  if($this->jo)
	$w = '
   <br />
   <p>Analiza pliku IGC : '.$name.'</p>';


	if(file_exists($path.'/'.$name)) 										//-jeśli istnieje plik do odczytania
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

	 $analx = $analy = $al1dp = $al2dp = $li = 0;

	 foreach($tigc as $war)
	 {
	  if(substr($war, 0, 1) == 'B')
	  {

		$tim = substr($war, 1, 6);
		/*
		$tim1 = substr($tim, 0, 2);
		$tim2 = substr($tim, 2, 2); */
		$tim3 = substr($tim, 4, 2);

		if(!$d_time)
		{
		 if($ttmp) $d_time = $tim3 - $ttmp;

		 $ttmp = $tim3;
		}


		$al1 = substr($war, 7, 7);			//-szerokość geograficzna xx-xx.xxxx'
		$al1a = substr($al1, 0, 2);		//-stopnie
		$al1b = substr($al1, 2, 2);		//-minuty
		$al1c = substr($al1, 4, 3);		//-sekundy

		$al1d = $al1a.'.'.substr(round(((($al1b.'.'.$al1c)*1)/60),$this->dok), 2, $this->dok);

		$al2 = substr($war, 15, 8);		//-długość geograficzna xx-xx.xxxx'
		$al2a = substr($al2, 0, 3);
		$al2b = substr($al2, 3, 2);
		$al2c = substr($al2, 5, 3);

		if(substr($al2a, 0, 1) == '0')
		 $al2a = substr($al2a, 1);

		$al2d = $al2a.'.'.substr(round(((($al2b.'.'.$al2c)*1)/60),$this->dok), 2, $this->dok);

		$al3 = substr($war, 25, 5)*1;		//-wysokość barometryczna
		$al4 = substr($war, 30, 5)*1;		//-wysokość GPS

		if(!$al3) $al3 = $al4;   			// ???

		//$w .= '<p>'.round(abs($al1d -$al1dp),8).' -> '.round(abs($al2d - $al2dp),8).'</p>';

		if($al1dp) $analx = round(abs($al1d - $al1dp),8);

		if($al2dp) $analy = round(abs($al2d - $al2dp),8);

		if($analx < 0.01 && $analy < 0.1) // filtr dla 'rozjechanych' założenie że pierwszy punkt jest prawidłowy
		{
		 //$tt[$li] = '{"t":"'.$tim1.':'.$tim2.':'.$tim3.'","lat":"'.$al1d.'","lon":"'.$al2d.'"}';
		 //$tt[$li] = '{"x":"'.$al1d.'","y":"'.$al2d.'"}';

		  $li++;

		  $tt[$li] = '['.$al1d.','.$al2d.']';

		 //$td[$li] = array($al1d, $al2d, $al3, $al4, $tim1, $tim2, $tim3);
		  $td[$li] = array($al1d, $al2d, $al3);		//-tablica do analizy tracka

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

	 $info = explode('.', $name);
	 $info = preg_replace('/_/', '<br />\r\n', $info[0]);

	 $info = S::strtopl($info);

	 $ttt = implode(',', $tt);

    $this->lot = '
	  {"type":"flight","start":['.$td[1][0].','.$td[1][1].'],"end":['.$td[$li][0].','.$td[$li][1].'],
		"pilot":"'.$info.'",
		"points":['.$ttt.']}';


	 if(C::get('localhost'))
	  $this->tab_lot[] = $this->lot;									//-jeśli analizowane lokalnie to wyświetla tracki


	 if($this->licz > 0) $this->lot = ','.$this->lot;			//-przecinek dla kolejnego pakietu danych
    $this->licz = 1;


	 //		"photo":"",
	 //		"pilotsCount":4,
	 //		"lastPosition":false,
	 //		"pilot":"Marcin Gorayski<br \/>\r\nDudek Coden<br \/>\r\nM.3 . . . . . 131,93km",

	 // Analiza noszeń dla danego lotu

	 if($this->n)
	 {
	  //$this->do_pliku($name); 										//-zapisuje cały lot

	  $this->licz = 0;

	  $w .= $this->kominy($td, $info, $name, $d_time);
	 }

	 unset($td, $ttt, $tt);
   }
	else
	 $w .= '<p>Plik jest pusty !</p>';

  return $w;
 }

 /**
 *
 * łączenie elementów komina, według śladu lotu
 * ( wszystkie punkty śladu)
 *
 */

 private function dod($t, $l, $f)
 {

  $f = $f + $l;

  for($i = $l; $i <= $f; $i++)
  {
	//$tt[$li] = '['.$al1d.','.$al2d.']';

	if($t[$i][0] && $t[$i][1])
	{
    $k[$i] = '['.$t[$i][0].','.$t[$i][1].']';
	}
  }

  return $k;
 }

 /**
 *
 * łączenie elementów komina, według śladu lotu
 * ( tylko punkty kontrolne )
 *
 */

 private function dod2($t, $l)
 {

	if($t[$l][0] && $t[$l][1])
	{
    $k[$l] = '['.$t[$l][0].','.$t[$l][1].']';
	}

  return $k;
 }

 /**
 *
 *
 *
 */

 private function dodd2($t, $l)
 {

	if($t[$l][0] && $t[$l][1])
	{
    $this->k2[$l] = array($t[$l][0], $t[$l][1]);
	}

 }

 /**
 *
 * obliczenia wsp. prostopadłej do odcinka
 *
 */

 private function koord($x1, $y1, $x2, $y2)
 {
   $d = 0.002;
	$prec = 10;

   $a1 = $b1 = $x2;
	$a2 = $b2 = $y2;
	$c1 = $d1 = $x1;
	$c2 = $d2 = $y1;

   $x = round($x2 - $x1, $prec);
	$y = round($y2 - $y1, $prec);

	$xx = round($x * $x, $prec);
	$yy = round($y * $y, $prec);

   $xw = round($d * sqrt(1 - (($xx + $yy) * $xx/(($xx + $yy)*($xx + $yy)))), $prec);
	$yw = round(($d * $x * sqrt($xx + $yy))/($xx + $yy), $prec);


   if($x > 0 && $y > 0) //1
	{
	 $a1 = $x2 - $xw;
	 $b1 = $x2 + $xw;
	 $c1 = $x1 + $xw;
	 $d1 = $x1 - $xw;

    //if($this->jo) $info .= '<br \/>\r\n warian = 1';
	}

   if($x > 0 && $y < 0) //2
	{
	 $a1 = $x2 + $xw;
	 $b1 = $x2 - $xw;
	 $c1 = $x1 - $xw;
	 $d1 = $x1 + $xw;

	 //if($this->jo) $info .= '<br \/>\r\n wariant = 2';
	}

	if($x < 0 && $y < 0) //3
	{
	 $a1 = $x2 + $xw;
	 $b1 = $x2 - $xw;
	 $c1 = $x1 - $xw;
	 $d1 = $x1 + $xw;

	 //if($this->jo) $info .= '<br \/>\r\n wariant = 3';
	}

   if($x < 0 && $y > 0) //4
	{
	 $a1 = $x2 - $xw;
	 $b1 = $x2 + $xw;

	 $c1 = $x1 + $xw;
	 $d1 = $x1 - $xw;

	 //if($this->jo) $info .= '<br \/>\r\n wariant = 4 poprawiony';
	}

   $a2 = $y2 + $yw/2;
	$b2 = $y2 - $yw/2;

	$c2 = $y1 - $yw/2;
	$d2 = $y1 + $yw/2;


  //return array($x, $y, $xw, $yw);

  return array($a1, $a2, $b1, $b2, $c1, $c2, $d1, $d2);
 }

 /**
 * obszarowa prezentacja komina
 *
 * $ttt = $this->pole_kom($t[$start][0], $t[$start][1], $t[$l][0], $t[$l][1]);
 * x1,y1,x2,y2
 *
 *
 */

 private function pole_kom($x1, $y1, $x2, $y2, $n, $j)
 {

	list($a1, $a2, $b1, $b2, $c1, $c2, $d1, $d2) = $this->koord($x1, $y1, $x2, $y2);

	$ttt = '['.$d2.','.$d1.']';
	$ttt .= ',['.$a2.','.$a1.']';
	$ttt .= ',['.$b2.','.$b1.']';
	$ttt .= ',['.$c2.','.$c1.']';

	$this->s[(2*$j-1)] = '['.$d2.','.$d1.']';
	$this->s[(2*$j)] = '['.$a2.','.$a1.']';

   $this->s[(4*$n-($j-1)*2-1)] = '['.$b2.','.$b1.']';
	$this->s[(4*$n-($j-1)*2)] = '['.$c2.','.$c1.']';
/*
	$this->s[($j+1)] = '['.$a2.','.$a1.']';
	$this->s[((2*$n) - $j)] = '['.$b2.','.$b1.']';

 */
  return array($ttt, $info);

  //[ y = (0 -> 90), x = (0 -> 180)] na naszej ćwiartce
 }


 /**
 *
 * analiza kominów dla danego lotu
 *  $t[] = array($al1d, $al2d, $al3, $al4);
 *         array(x, y, z-baro, z-gps, h, m, s ) czas UTC
 *
 */

 private function kominy($t = array(), $info = '??', $name = '??', $dt = 0)
 {
  $f = 10;									//-szerokość filtra w punktach tracka

  $w = $komunikat = '';

  $komin = FALSE;							//-wskaźnik komina

  $k = $this->k2 = array();	 		//-pojedynczy komin

  $l_max = count($t) - $f;				//-długość tablicy - filtr

  $l = 1;									//-wskaźnik tablicy
  $ind_komina = $l_kom = 0;	   	//-licznik kominów, licznik elementów konina
  $start = $stop = 0;

  //Test::trace('tablica t', $t);
  do											//-petla po punktach lotu
  {

	if($t[($l + $f)][2] > $t[$l][2])							//-pierwszy element komina
	{

	 if(!$komin)
	  $start = $l;		   										//-wskaźnik początku komina start

	 $stop = $l + $f;                      				//-aktualny wskaźnik końca komina

	 //$k = array_merge($k, $this->dod($t, $l, $f));	//-wszystkie punkty sladu

	 if($this->jo)
	  $k = array_merge($k, $this->dod2($t, $l));    //-tylko punkty kontrolne

	 $this->dodd2($t, $l);

	 $komin = TRUE;

	 $ind_komina++;												//-indeks komina (z ilu fragmentów się składa)

	}
   else
	{
	 //if($t[($l + $f)][2] < $t[$l][2] && $komin)	//-duszenie

	 if($komin)													//-jeśli poprzednio był komin to ->
	 {
	  //$lh = ($t[$l][2] - $t[$start][2]);				//-wyliczamy przewyższenie
	  $lh = ($t[$stop][2] - $t[$start][2]);			//-wyliczamy przewyższenie

	  if($lh > $this->lim_h)								//-dolny limit przewyższenia w kominie, jeśli większe to zapisujemy komin
	  {
	   $l_kom++;												//-licznik kominów dla danego lotu

		$this->dodd2($t, $stop);							//-dodanei punktu końcowego


		 $k = array_merge($k, $this->dod2($t, $stop));	//-dodanie punktu końcowego


		 $ttt = implode(',', $k);

	    $this->lot = '
	  {"type":"komin","start":['.$t[$start][0].','.$t[$start][1].'],"end":['.$t[$stop][0].','.$t[$stop][1].'],
		"pilot":"'.$info.'<br \/>\r\nnr: '.$l_kom.' dh = '.$lh.' -> ślad -> '.count($k).'",
		"points":['.$ttt.']}';



      if($l_kom > 1)											//-od którego komina start
		{
		 //-pole komina proste ( bez przegięć )
		 //list($ttt, $komunikat) = $this->pole_kom($t[$start][1], $t[$start][0], $t[$l][1], $t[$l][0], 1,  FALSE);
		 //                     						x1,y1,x2,y2

       //-czas noszenia
		 $tno = $ind_komina * $f * $dt/60;
		 $tnom = floor($tno);
		 $tnos = ($tno - $tnom)*60;
		 $tno = $tnom.'m ';
		 if($tnos) $tno .= $tnos.'s';

		 $komunikat = '<br \/>\r\ndh = '.$lh.'<br \/>\r\n śr.nosz. = '.round(($lh/($ind_komina * $f * $dt)),1).'<br \/>\r\nczs nosz. = '.$tno;

		 if($this->jo)
		  $komunikat2 = '<br \/>\r\nnr kom.= '.$l_kom.' -> l.p.kom.='.$ind_komina.'<br \/>\r\n czas zap. punktów ='.$dt.'<br \/>\r\n filtr ='.$f;

		 unset($tno, $tnom, $tnos);


	    $this->s = array();  //-tablica pola noszenia - zerowanie

		 /*
		 Test::trace('ind_komina', $ind_komina);
		 Test::trace('start', $start);
		 Test::trace('stop', $stop);		 */


		 $ppl = $ppx = $pp1 = $pp2 = $pp3 = 0;

		 if($ind_komina > 8)
		 {
		  $ppl = 3;
		 }
		 elseif($ind_komina > 3)
		 {
		  $ppl = 2;
		 }
		 else
		  $ppl = 1;


		 $ppx = $f * floor($ind_komina/$ppl);
		 $pp1 = $start + $ppx;

		 $pp2 = $pp1 + $ppx;
		 $pp3 = $pp2 + $ppx;

		 /*
		 Test::trace('ppx', $ppx);
		 Test::trace('pp1', $pp1);
		 Test::trace('pp2', $pp2);
		 Test::trace('tablica k2', $this->k2);	 */

		 if($ppl == 3)
		 {
		  $this->pole_kom($this->k2[$start][1], $this->k2[$start][0], $this->k2[$pp1][1], $this->k2[$pp1][0], $ppl, 1);

		  $this->pole_kom($this->k2[$pp1][1], $this->k2[$pp1][0], $this->k2[$pp2][1], $this->k2[$pp2][0], $ppl , 2);

		  $this->pole_kom($this->k2[$pp2][1], $this->k2[$pp2][0], $this->k2[$stop][1], $this->k2[$stop][0], $ppl , 3);
		 }
		 elseif($ppl == 2)
		 {
		  $this->pole_kom($this->k2[$start][1], $this->k2[$start][0], $this->k2[$pp1][1], $this->k2[$pp1][0], $ppl, 1);

		  $this->pole_kom($this->k2[$pp1][1], $this->k2[$pp1][0], $this->k2[$stop][1], $this->k2[$stop][0], $ppl , 2);
		 }
  		 elseif($ppl == 1)
		 {
		  $this->pole_kom($this->k2[$start][1], $this->k2[$start][0], $this->k2[$stop][1], $this->k2[$stop][0], $ppl, 1);
		 }

		 /*
		 Test::trace('ind_komina2', $ind_komina);
		 Test::trace('this->s', $this->s); */

		 ksort($this->s);

		 $ttt = implode(',', $this->s);

		 $this->s = array();

       $this->lot .= '
	   ,{"type":"nosi","start":[,],"end":[,],
		"pilot":"'.$info.$komunikat.'<br \/>\r\n'.$komunikat2.'",
		"points":['.$ttt.']}';

		}

		unset($ttt);

	   if(C::get('localhost'))
	    $this->tab_lot[] = $this->lot;


	   if($this->licz > 0 || $this->kom > 0)
	   {
	    $this->lot = ','.$this->lot;
	   }

		$this->kom = 1;										//-wskażnik kolejnego komina

		$this->do_pliku($name, TRUE);						//-dopisanie do pliku

	  }

     $ind_komina = 0;

	  unset($lh, $start, $stop);

	 }

	 $k = $this->k2 = array();

	 $komin = FALSE;
	}

	$l = $l + $f;												//-kolejny punkt pomiaru

  } while($l < $l_max);


  return $w;
 }

 /**
 *
 * zapis lotu do pliku
 *
 *
 */

 private function do_pliku($name = '??', $kom = FALSE)
 {
  if($this->lot) 													//-jeśli istnieją dane do zapisu
  {
   $to = $this->katalog.'/'.$this->plik_name;

	if(file_exists($to))
	{
    if(is_writable($to))
	 {
	  if(!$fp = fopen($to, 'a'))
	  {
	   $this->w .= C::infoBox('adm_errbox', 'Nie można otworzyć pliku:'.$to);
	   return 0;
	  }

	  if(fwrite($fp, $this->lot) === FALSE)
	  {
	   $this->w .= $this->w .= C::infoBox('adm_errbox', 'Nie można dodać danych z pliku: '.$name);
	  }
	  elseif(!$kom)
	   $this->w .= '
	 <p>Dodano do '.$to.' dane z pliku: '.$name.'</p>';

     fclose($fp);
	 }
	 else
	 {
	  $this->w .= C::infoBox('adm_errbox', 'Plik:'.$to.' Nie jest do zapisu!');
	 }

	}
	else
	{

	  if(!$fp = fopen($to, 'a'))
	  {
	   $this->w .= C::infoBox('adm_errbox', 'Nie można otworzyć pliku:'.$to);
	   return 0;
	  }

	  if(fwrite($fp, $this->lot) === FALSE)
	  {
	   $this->w .= C::infoBox('adm_errbox', 'Nie można dodać danych z pliku: '.$name);
	  }
	  else
	   $this->w .= '
	 <p>Dodano do '.$to.' dane z pliku: '.$name.'</p>';

     fclose($fp);

	}

  }
  else
   $this->w = C::infoBox('adm_errbox', 'Brak danych dla lotu: '.$name);

  $this->lot = '';
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