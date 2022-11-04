<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/**
*
* klasa : skalowanie zdjęć w formacie jpg, gif i png z prubą utrzymania przeźroczystości :: v.1.5
*
* 2012-12-11 : dodano warunek kadrowania tylko dla "m_ " i  tylko bez "L_" (dla dk i maxmodels, gdzie galeria jest typu Buczma)
*
* skalowanie plików graficznych i nakładanie tekstowego znaku wodnego, jako pojedyńczego napisu
*
* skalowanie wykonane dla tabel z max. 10 polami plikowymi, aktualnie stosuje osobną tabelę na zdjęcia, co oznacza
* że skrypt można uprościć do operacji wykonywanej tylko dla pojedyńczego pliku graficznego.
*
* -- 2012-05-17 -> naprawa błędu polegająca na skalowaniu w górę dla najwyższego formatu
*
* -- 2012-05-15 -> forsowanie skalowania jako pion lub poziom, parametr V or H
* -- 2012-05-10 ->
* -- 2011-04-27
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-03-17 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*
*
*/


class FotoSkalMark
{
 const ERROR = '
		<p>Przepraszamy ale niestety podczas skalowania plików wystąpiły błędy uniemożliwiające wykonanie zaplanowanych operacji, proszę skontaktować się z administratorem strony.</p>';
 const ERROR_TEXT = '
		<p>Przepraszamy ale parametry zabezpieczenia grafiki tekstem są niewłaściwe i naniesienie tekstu na grafikę jest niemożliwe. W celu naprawy funkcji proszę skontaktować się z administratorem strony.</p>';
 const KOM_ERROR_SKAL = '
		<p class=\'error\'>Skalowanie pliku się nie powiodło! Jeśli problem bedzie się powtarzał prosimy o kontakt z administratorem serwisu</p>';
 const KOM_OK_SKAL = '
		<p>Skalowanie wykonane prawidłowo.</p>';

 /**
 *
 *
 */

 private $f = array(); 					//-tablica zdjęć do obróbki
 private $s = array();					//-parametry skalowania -> szerokość x wysokość
 private $p = array();					//-tablica parametrów dla pliku odczytana z definicji znaczników tabeli mySQL

 private $kadr = array();				//-tablica wskaźników kadrowania miniatur
 private $pkadr = array();				//-tablica parametrów kadrowania miniatur

 private $w = ''; 		 				//-wynik działania klasy

 private $ja = '';						//-znacznik VIP'a
 private $error = array(); 			//-tablica błędów skalowania
 private $error_text = array(); 		//-tablica błędów zabezpieczenia textem


 /**
 *
 * odbiór danych
 *
 * foty - tablica zdjęć do obróbki
 * fskal - tablica indywidualnych parametrów skalowania, poza definicją w def-tab
 * param - tablica parametrów utworzona na podstawie definicji znaczników dla tabeli MySQL
 * kadr - tablica wskaźników kadrowania
 * pkadr - tablica parametrów kadrowania
 *
 */


 function __construct($foty, $fskal = null, $param, $kadr, $pkadr)
 {


  $this->ja = C::get('ja');

  if($fskal) $this->s = $fskal;			//-tablica tablic 2 elementowych - ma priorytet przed tablica parametrów :: działa tylko dla 'm_'

  if($param)
	$this->p = $param;						//-tablica parametrów dla pliku
  else
   S::ErrorAdmin('Brak obowiązkowej tablicy parametrów w '.__METHOD__.':'.__LINE__);

  if($kadr) $this->kadr = $kadr;			//-tablica znaczników kadrowania - wartość true blokuje kasowanie oryginału i skalowanie,
  													//-musi być tutaj bo klasa obsługuje tablicę wszystkich zdjęć
													//-> [nr pola] = 1 dla kadrowania 0 dla braku kadrowania


  if($pkadr) $this->pkadr = $pkadr;		//-tablica parametrów kadrowania	ze skryptu kadrowania


  if($foty)										//-tablica plików
  {
	$this->f = $foty;

	$this->skalFoto();
  }
  else
   $this->w = '
		<p>Nie ma żadnych plików do obróbki</p>';

 }

 /**
 *
 * komunikaty błędów działania klasy
 *
 */

 public function outFoto()
 {
   if($this->error)
	 if($this->ja)
	  $this->w = '
	  <p class=\'error\'>'.implode('</p>
	  <p>', $this->error).'</p>'.$this->w;					//-dla metody skalującej
	 else
	  $this->w .= FotoSkalMark::ERROR;

	if($this->error_text)
	 if($this->ja)
	  $this->w = '
	  <p class=\'error\'>'.implode('</p>
	  <p>', $this->error_text).'</p>'.$this->w;			//-dla metody realizującej nakładanie tekstowego znaku wodnego
	 else
	  $this->w .= FotoSkalMark::ERROR_TEXT;

 	return $this->w;
 }

 /**
 *
 * skalowanie zdjęć w petli dla wszystkich pól plikowych
 * this->f :: tablica zdjęć
 *
 */

 private function skalFoto()
 {

  foreach($this->f as $k => $v) 									  						//-pętla po wszystkich polach plików graficznych
  {

	if($v[0] && !$this->kadr[$v[0]])														//-tylko dla plików które zostały załadowane i nie będą kadrowane!
	{


	 $isOn = substr($k, -1);		//- isOn to numer pola plikowego pobrany z nazwy = fotX gdzie X to numer pola

	 if($this->s[$isOn])				//- jeśli w tablicy ze skalowaniem ekstra s.a. parametry dla danego pola to następuje podmiana
	 {										//- parametrów skalowania dla miniatury z prefixem 'm_'

	  if($this->s[$isOn][0]>50) $this->p[$v[0]]['th']['m_'][0] = $this->s[$isOn][0];
	  if($this->s[$isOn][1]>50) $this->p[$v[0]]['th']['m_'][1] = $this->s[$isOn][1];

	 }
    unset($isOn);

	 $this->skalEachFoto($v);
	}

  }
 }

 /**
 * warunek kadrowania wyłacznie miniatury "m_"
 * 2012-12-11
 */

 private function only_m($k)
 {
  if($k == 'm_') return true; else return false;
 }

 /**
 * warunek kadrowania wszystkich miniatur poza największym formatem "L_"
 * 2012-12-11
 */

 private function not_L($k)
 {
  if($k != 'L_') return true; else return false;
 }

 /**
 *
 * skalowanie dla pojedyńczego pliku
 *
 */

 private function skalEachFoto($name)
 {
  //exit('JEST SKAL');
  /*
   $name[0] - numer pliku i informacja, że został załadowany = znajduje się w katalogu tmp_foty
   $name[1] - nazwa pliku

   parametry do transformacji : $this->p;

	$this->p[$name[0]][0]
	$this->p[$name[0]]['ext']
	$this->p[$name[0]]['path']
	$this->p[$name[0]]['th']

	$this->p[$name[0]]['th'] [0] - x
	$this->p[$name[0]]['th'] [1] - y
	$this->p[$name[0]]['th'] [2] - jakość
	$this->p[$name[0]]['th'] [3] - czcionka (nr czcionki) i położenie znaku wodnego
	$this->p[$name[0]]['th'] [4] - format docelowy
	$this->p[$name[0]]['th'] [5] - preferencja formatu: H-Horizontal, V-vertical ( sprawia że zdjęcie pionowe jest traktowane jak poziome i odwrotnie)

	// 2013-06-07
	$this->p[$name[0]]['th'] [6] - kadrowane czy nie kadrowane

	katalog źródła = $sPath
   katalog docelowy = $dPath
  */

  if(C::get('only_m_kadr', false)) $warKadr = 'only_m'; else $warKadr = 'not_L';

  $sPath = C::get('tmpPath_foty');
  $dPath = C::get('fotyPath');

  $size = getimagesize($sPath.$name[1]);					//-rozmiar oryginału

  foreach($this->p[$name[0]]['th'] as $k => $v)
  {
   $par_skal[] = $k.'->'.implode('->', $v);
  }

  if($this->ja) $this->w .= '
	<p class=\'ja\'> nazwa pliku = '.$name[1].' | katalog docelowy = '.$this->p[$name[0]]['path'].' | parametry transformacji = '.implode(' ; ', $par_skal).'</p>
	<p class=\'ja\'>rozmiar oryginału: '.$size[0].' x '.$size[1].' px</p>';

  unset($par_skal, $k, $v);

  $p['i'] = $name[0];
  $p['so_path'] = $sPath;
  $p['de_path'] = $this->p[$name[0]]['path'];											//-pełna ścieżka katalogu docelowego
  $p['de_name'] = $name[1];

  //Test::stopPointTab('tablica przed skalowaniem', $this->p );

  foreach($this->p[$name[0]]['th'] as $k => $v)
  {
   $skal = true;																					//-wskaźnik umożliwiający skasowanie oryginału z katalogu tymczasowego

	$p['de_prefix'] = $k;

	//ponieważ znak wodny jest zintegrowany ze skalowaniem (co może powinno być rozdzielone) to jeśli oryginał jest troche mniejszy od rozmiaru
	//docelowego, powodowało to, że znak wodny nie był wykonywany

	 $this->w .= '
	<p class=\'ja\'>skaluje plik '.$k.$name[1].'</p>'; 								//-przedrostek+nazwa pliku

	 //-przygotowanie parametrów transformacji

	 if(!$v[4])
	  $p['do_ext'] = substr($name[1], -3);													//-jeśli nie ma zadanego formatu docelowego to format oryginału
	 else
	  $p['do_ext']	= $v[4];																		//-zmiana formatu np. z jpg na gif itp.

	 if(strlen($p['do_ext']) != 3 && strlen($p['do_ext']) != 4)
	  $this->error[] = 'Niewłaściwy pramater do_ext -> patrz def_tab';

	 if(!$v[2]) 																					//-domyślna jakość ->kompresja jpg = maksymalna jakość
  	 {
      if($p['do_ext'] == 'jpg') $p['komp'] = 100;										//-kompresja przy zapisie dla jpg
      if($p['do_ext'] == 'png') $p['komp'] = 0;											//-jakość przy zapisie dla png
  	 }
	 else
	 {
	  $p['komp'] = $v[2];

	  if(!is_numeric($p['komp']))
	   $this->error[] = 'Niewłaściwy pramater kompresji pliku -> patrz def_tab';

	  if($p['do_ext'] == 'jpg' && $p['komp'] < 30 ) $p['komp'] = 30;
	  if($p['do_ext'] == 'png' && $p['komp'] > 7 ) $p['komp'] = 7;
	 }

	 if($v[3])
	 {
	  if(is_numeric($v[3]) && $v[3] < 1000 && $v[3] > 100)
	   $p['utext'] = $v[3];
	  else
	   $this->error_text[] = 'Niewłaściwy pramater tekstowego znaku wodnego -> patrz def_tab';

	  if(!C::get('textOnFoto')) $this->error_text[] = 'Brak definicji tekstu na znak wodny -> patrz configuracja';
	  if(!C::get('fontOnFoto')) $this->error_text[] = 'Brak definicji czcionki na znak wodny -> patrz configuracja';
	 }

	 //if(!C::get('skal_dok'))																	//-jeśli skalowanie proporcjonalne

	 /*
	 $this->pkadr:[0] = x1
	 $this->pkadr:[1] = y1
	 $this->pkadr:[2] = x2
	 $this->pkadr:[3] = y2
	 $this->pkadr:[4] = szer_kadru
	 $this->pkadr:[5] = wys_kadru
	 $this->pkadr:[6] = szer_kadrowana
	 $this->pkadr:[7] = wys_kadrowana
	 */

	  if($this->pkadr[$p['i']] && $this->pkadr[$p['i']][4] && $this->pkadr[$p['i']][5])
	  {

		$wsp_kadr_x = $this->pkadr[$p['i']][6] / $this->pkadr[$p['i']][4];
	  	$wsp_kadr_y = $this->pkadr[$p['i']][7] / $this->pkadr[$p['i']][5];

	  }

	  if($wsp_kadr_x > 4 || $wsp_kadr_y > 4)	$this->error = 'Zbyt mały kadr';

	  $p['dx'] = 0;	//-współrzędne x i y górnego rogu kadru na pliku źródłowym
	  $p['dy'] = 0;


	  switch($v[5])
	  {
		case 'H': //-wymuszenie poziome

		 $typFoto = 'H';

		 if($wsp_kadr_y && $this->$warKadr($k)) //$k == 'm_'
		  $this->thumbsFormat($p, $v, $size, $wsp_kadr_x, $wsp_kadr_y, $typFoto);
		 else
		  $this->largeFormat($p, $v, $size, $typFoto);

		break;

		case 'V': //-wymuszenie pionowe

		 $typFoto = 'V';

		 if($wsp_kadr_x && $this->$warKadr($k)) //$k == 'm_'										//-miniatury
		  $this->thumbsFormat($p, $v, $size, $wsp_kadr_x, $wsp_kadr_y, $typFoto);
		 else																										//-rozmiar max.
		  $this->largeFormat($p, $v, $size, $typFoto);


		break;

		default:

	   //-podział na zdjęcia pionowe i poziome
      if($size[0] < $size[1])									  							   			//-zdjęcie pionowe
	   {
       $typFoto = 'V';

	    if($wsp_kadr_x && $this->$warKadr($k))  //$k == 'm_'	 									//-miniatury
		  $this->thumbsFormat($p, $v, $size, $wsp_kadr_x, $wsp_kadr_y, $typFoto);
		 else																										//-rozmiar max.
		  $this->largeFormat($p, $v, $size, $typFoto);

	   }
      else																										//-dla zdjęcia poziomego -> trzyma zadaną szerokość
	   {
       $typFoto = 'H';

		 if($wsp_kadr_y && $this->$warKadr($k)) //$k == 'm_'
		  $this->thumbsFormat($p, $v, $size, $wsp_kadr_x, $wsp_kadr_y, $typFoto);
		 else
		  $this->largeFormat($p, $v, $size, $typFoto);

	   }

	  } //-end switch


    if(!$this->error)
	 {
     if($typFoto === 'V')
	  {
		if($size[1] > $p['do_wys'])
		 $typFoto = true;
		else
		 $typFoto = false;
	  }
	  elseif($typFoto === 'H')
	  {
	   if($size[0] > $p['do_szer'])
		 $typFoto = true;
		else
		 $typFoto = false;

	  }
	  else
	   S::ErrorAdmin('ERROR: brak parametru V or H w '.__METHOD__.':'.__LINE__);


	  if($this->ja) $this->w .= '
	<p class=\'ja\'>skalowanie pliku do formatu: '.$p['do_szer'].' x '.$p['do_wys'].'px i zapis z przedrostkiem: '.$k.'</p>'; //-przedrostek+nazwa pliku

	  if($typFoto) //-jeśli skalowanie to skalowanie
	  {
	   if($this->pomFoto($p, $size))
	    $this->w .= FotoSkalMark::KOM_OK_SKAL;
	   else
	   {
	    $this->w .= FotoSkalMark::KOM_ERROR_SKAL;
	    $skal = false;
	   }
     }
	  else //-jeśli oryginał jest za mały to przekopiowanie oryginału
	  {

	   //exit('copy:'.$p['so_path'].$name[1].' -> '.$p['de_path'].$p['de_name']);

		copy($p['so_path'].$name[1], $p['de_path'].$p['de_name']);
	  }

	 }
	 else
	  $skal = false;

  }

  //-warunek ok. jeśli choc jedna miniatura nieudana to zostawić plik źródłowy

  //echo '<p>nr pola dla zdjęaia :: '.$name[0].'</p>';
  //C::test($this->kadr);
  //C::test($this->pkadr, true);

  if($skal && !$this->kadr[$name[0]]) unlink($p['so_path'].$p['de_name']); 										//-kasujemy plik źródłowy

  unset($p, $typFoto);
 }


 /**
 *
 *
 *
 */

 private function thumbsFormat(& $p, $v, $size, $wsp_kadr_x, $wsp_kadr_y, $format)
 {

   if($format === 'V')
	{
 		 $p['ddo_szer'] = (int)($v[0] * $wsp_kadr_x);									//-opisane dla zdjecia poziomego
		 $p['ddo_wys'] = (int)($size[1] * $p['ddo_szer']/$size[0]);

   }
	elseif($format === 'H')
	{
 		 $p['ddo_wys'] = (int)($v[1] * $wsp_kadr_y);										//-rozmiary miniatury z zapasem na kadrowanie
		 //-wysokość skorygowana = wysokość docelowa * wsp_kadrowania_wys

		 $p['ddo_szer'] = (int)($size[0] * $p['ddo_wys']/$size[1]);
		 //-szerokość skorygowana = szerokość proporcjonalna do skorygowanej wysokości
	}
	else
	 S::ErrorAdmin('ERROR: parametr tylko V lub H on'.__METHOD__.':'.__LINE__);


  $p['dx'] = (int)($size[0] * $this->pkadr[$p['i']][0] / $this->pkadr[$p['i']][6]); //-pozycja górnego lewego rogu kadru
  $p['dy'] = (int)($size[1] * $this->pkadr[$p['i']][1] / $this->pkadr[$p['i']][7]);


  $p['do_szer'] = (int)$v[0];															//-rozmiary docelowe miniatury
  $p['do_wys'] = (int)$v[1];

 }

 /**
 *
 *
 *
 */

 private function largeFormat(& $p, $v, $size, $format)
 {
   if($format === 'V')
	{

 	 $p['do_wys'] = $v[1];																	//-zdjęcia pionowe -> trzyma zadaną wysokość
	 $p['do_szer'] = (int)($size[0] * $v[1]/$size[1]);

   }
	elseif($format === 'H')
	{

 	 $p['do_szer'] = (int)$v[0];
	 $p['do_wys']  = (int)($size[1] * $v[0]/$size[0]);								//-zdjęcia poziome -> trzyma zadaną szerokość

	}
	else
	 S::ErrorAdmin('ERROR: parametr tylko V lub H on'.__METHOD__.':'.__LINE__);

	$p['ddo_szer'] = (int)$p['do_szer'];
	$p['ddo_wys']  = (int)$p['do_wys'];

 }

 /**
 *
 *
 */

 private function pomFoto($p, $size)
 {
  //-$size = array(szer_oryg, wys_oryg);
  //-$p = array(pozostałe parametry);

  $fo = imagecreatetruecolor($p['do_szer'], $p['do_wys']); 			  								//-uchwyt do nowego pustego obrazka o zadanych wymiarach

  switch($p['do_ext'])																							//-uchwyt do obrabianego pliku
  {
   case 'jpg':
	 $im = imagecreatefromjpeg($p['so_path'].$p['de_name']);
	break;

   case 'gif':
		$tr =ImageColorAllocate($fo, 255, 255, 255);														//-utrzymanie przeźroczystego tła
		imagefill($fo,0,0,$tr);
 		ImageColorTransparent($fo, $tr);
		unset($tr);

		$im = imagecreatefromgif($p['so_path'].$p['de_name']);
	break;

   case 'png':
		imagealphablending($fo, false);																		//-utrzymanie przeźroczystego tła
 		imagesavealpha($fo, true);
		$im = imagecreatefrompng($p['so_path'].$p['de_name']);
  }

  if(!$im)
  {
	$this->error_text[] = 'Błąd imagecreatefrom w '.__CLASS__.':'.__LINE__;
   return false;
  }

  /*
  echo '<p>do_szer = '.$p['do_szer'].'</p>';
  echo '<p>do_wys = '.$p['do_wys'].'</p>';
  echo '<p>size[0] = '.$size[0].'</p>';
  echo '<p>size[1] = '.$size[1].'</p>';
  if($this->pkadr[$p['i']]) C::test($this->pkadr[$p['i']], true);	 */

  $ok = @imagecopyresampled($fo, $im, 0, 0, $p['dx'], $p['dy'], $p['ddo_szer'], $p['ddo_wys'], $size[0], $size[1]); 	//-skalowanie

  //$ok = @imagecopyresampled($fo, $im, 0, 0, 0, 0, $p['do_szer'], $p['do_wys'], $size[0], $size[1]); 	//-skalowanie

  if(!$ok)
  {
	$this->error_text[] = 'Błąd imagecopyresampled w '.__CLASS__.'::'.__LINE__;
   return false;
  }

  if(!$this->error_text && $p['de_prefix'] === 'L_') 										//-jeśli znak wodny (textowy)
  {
   if(C::get('fontOnFoto') && C::get('textOnFoto'))
	{
    $this->w .= '
		<p>Nanoszę tekstowy znak wodny. -> '.C::get('textOnFoto').' czcionką : '.basename(C::get('fontOnFoto')).' : '.$p['utext'].'</p>';

    $fo = $this->znakWodnyText($fo, $p['do_szer'], $p['do_wys'], $p['utext'], $im);
	}
	else
	{
	 if(!C::get('fontOnFoto')) $this->x .= '<p>Brak zdefiniowanej czcionki na znak wodny</p>';
	 if(!C::get('textOnFoto')) $this->x .= '<p>Brak zdefiniowanego tekstu na znak wodny</p>';
	}
  }

  if($ok)
  {
   if($p['de_prefix'] === 'L_') $p['de_prefix'] = '';

   switch($p['do_ext'])																											//-zapisanie wynikowego zdjęcia do pliku
   {
  	 case 'jpg':
	 	$ok = imagejpeg($fo, $p['de_path'].$p['de_prefix'].$p['de_name'], $p['komp']);
	 break;

    case 'gif':
	 	$ok = imagegif($fo, $p['de_path'].$p['de_prefix'].$p['de_name']);
	 break;

    case 'png':
	 	$ok = imagepng($fo, $p['de_path'].$p['de_prefix'].$p['de_name'], $p['komp']);
   }
  }
  imagedestroy($fo);																								//-zwolnienie zasobów

  unset($fo, $im);

  return $ok;

 }

 /**
 *
 * dodanie tekstowego znaku wodnego
 * pojedyńczy tekst zdefiniowany w config_def
 * w ustalonym za pomocą znacznika obszarze grafiki
 *
 */

 private function znakWodnyText($h, $sze, $wys, $ust, $im)				//-tekstowy znak wodny
 {

  /*
    [uchwyt do pliku][szerokość przeskalowana][wysokość przeskalowana]
	 [tekst] - opcje tekstu i numer czcionki

    $fota = imagecreatefromjpeg($pat.$fowe); 		//-uchwyt do zdjęcia -> zakładamy że już jest i jest to parametr przekazany $h
  */

  //echo getcwd() . "\n";  exit('TEST');				//-określa aktualny katalog

  list($cr, $cg, $cb, $cc) = C::get('color'); 		//array(0, 0, 0, 55);				//-kolor teksty na znak wodny

  list($ccr, $ccg, $ccb, $ccc) = C::get('cien'); 	//array(124, 124, 124, 65);			//-kolor cienia pod tekst na znak wodny

  $color = imagecolorallocatealpha($h, $cr, $cg, $cb, $cc);
  $cien  = imagecolorallocatealpha($h, $ccr, $ccg, $ccb, $ccc);

  unset($cr, $cg, $cb, $cc, $ccr, $ccg, $ccb, $ccc);

  //array imagefttext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $font_file , string $text [, array //$extrainfo ] )

  //imagettftext($fota, $h_text, $obrot, $x+2, $y+2, $cien, $czciona, $tekst);
  //imagettftext($fota, $h_text, $obrot, $x+3, $y+3, $cien, $czciona, $tekst);
  //imagettftext($fota, $h_text, $obrot, $x, $y, $color, $czciona, $tekst);

  $wys_text = $wys * (1.5/strlen(C::get('textOnFoto')));

  //$wys_text = $wys * 0.2;

  $wysText = $wys_text;	//-zapamietanie na wypadek tekstu pod kątem

  $r = floor(($ust-(floor($ust/100)*100))/10);

  switch($r)
  {
   case 1: $r = 0;
	break;

	case 2: $r = 45;
	break;

	case 3: $r = 90;
	break;
  }

  //$bbox = imagettfbbox($wys_text, 45, $font, 'Powered by PHP ' . phpversion());

  //Test::set('fontOnFoto', C::get('fontOnFoto'));
  //Test::set('textOnFoto', C::get('textOnFoto'));

  $sze_text = $sze + 1;

  while($sze_text > $sze)
  {
   $bbox = imagettfbbox($wys_text, $r, C::get('fontOnFoto'), C::get('textOnFoto'));

   $sze_text = abs($bbox[4]-$bbox[0]);
   $wys_text = abs($bbox[7]-$bbox[3]);

	$wys_text--;
  }

  $p = floor($ust/100);

  switch($p)
  {
   case 1: //left down

	 $x_text = 5+$kor;
  	 $y_text = $wys - 10;

	break;

	case 2: //left up

	 $x_text = 5 + $kor;
  	 $y_text = 10 + $wys_text + $kor;

	break;

	case 3: //right down

	 $x_text = $sze - 5 - $sze_text - $kor;
	 $y_text = $wys - 10;

	break;

	case 4: //right op

	 $x_text = $sze - 5 - $sze_text - $kor;
	 $y_text = 10 + $wys_text + $kor;

	break;

	case 5: //center

	 $x_text = ($sze/2) - ($sze_text/2);
	 $y_text = ($wys/2) + ($wys_text/2) + $kor;

	break;

  }

  //EXIT('ZNAK WODNY:'.$sze.' x '.$wys.' -> '.$ust.':: r = '.$r.' :: p = '.$p);
  //imagettftext($h, $wysText, $r, $x_text+2, $y_text+2, $cien, C::get('fontOnFoto'), C::get('textOnFoto'));		//-cień pod tekstem
  //imagettftext($h, $wysText, $r, $x_text, $y_text, $color, C::get('fontOnFoto'), C::get('textOnFoto'));			//-tekst

  imagettftext($h, $wys_text, $r, $x_text+2, $y_text+2, $cien, C::get('fontOnFoto'), C::get('textOnFoto'));		//-cień pod tekstem
  imagettftext($h, $wys_text, $r, $x_text, $y_text, $color, C::get('fontOnFoto'), C::get('textOnFoto'));			//-tekst

  unset($x_text, $y_text, $sze, $wys, $cien, $color, $wys_text, $ust, $r, $p, $wysText);

  return $h;
 }

 /*
 @
 */

 function __destruct()
 {
  unset($f, $w, $s);
 }

}
?>
