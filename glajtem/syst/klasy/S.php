<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* kontener metod dla wtyczek : v.1.41
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2015-03-14 : zmiana uprawnień w niektórych klasach tylko dla admina = 10
*
* 2013-02-12 : poprawki notis
*
* 2012-11-07 : poprawki i czyszczenie
*
* 2012-10-17 : z funkcji menu usunięto parametr login który był powielony z parametrem logowanie w config_def
* 2012-05-31 : poprawiona metoda automatycznej strony komunikatu
*
* 2012-01-22 : poprawiono metodę -> menu :: funkcję .active dla linków bezpośrednich z przedrostkiem SEO
*
* 2012-01-07 : dodano nową metodę -> pozFotoNew (operującą na galerii zdjęć)
*
* 2011-11-23 : zmiana separatora dla menu z ; na ^
*					doodano metode menu2 - generującą menu wielopoziomowe (spectra-nieruchomosci.pl)

* 2011-10-12 : poprawiono metodę -> pozFoto()
*
* public static function reklama($size)
* public static function pozFotoNew($f, $pat, $alt, $title, $name, $tfd)
*
* public static  function komunikat($text, $adres = 'komunikat') -> nowa 2011-09-29
* public static  function BBclear($t)
* public static  function formZaja($t)
* public static  function formText($t)
* private static function bbcode($t)
* private static function lista_ul($matches)
* private static function lista_ol($matches)
* private static function kod($matches)
* public static  function kdata($d, $c)
* public static  function pozFoto($tb, $pat, $alt, $tfd, $tta)
* public static  function klasaPola($b, $t, $p)
* public static  function menu($m, $login = true, $anim = false)		-> modyfikacja 2011-10-21 : doodano 6-ty parametr, zdjecie w pole <a><img /></a>
* public static  function seoLink($p1, $id, $typ)
* public static  function FB_Like($opis = false)
* public static  function ggoto($adres)
* public static  function Error($komunikat)
* public static  function tend()
*
*
*
*/

class S
{
 /**
 *
 * przekierowanie header-location z testem
 *
 */

 public static function myHeaderLocation($t = false)
 {
  Test::trace('linkback', $t);

  if(!$t) return 0;

	if(basename($t) === 'stop.php')
	 Test::testShow();  		// przeładowanie do kolejnej strony ( tylko komunikat, który nie ma dostępu do skryptu )
   else
    Test::testShow(true);

  header('location:'.$t);  // powrót ze skokiem do kotwicy
  exit;

 }

 /**
 *
 * kodowanie danych przesyłanych w 'opcji' adresu
 *
 */

 public static function linkCode($link)
 {
  if(is_array($link)) $link = implode(',', $link);

  $_SESSION['lllink'] = time();

  return substr(base64_encode($_SESSION['lllink']),0,-2).base64_encode($link);

 }

 /**
 *
 * dekodowanie danych przesyłanych w 'opcji' adresu
 *
 */


 public static function linkDecode($link)
 {

   $link = preg_replace('/%3D/', '=', $link);

   $link = substr($link, strlen( substr(base64_encode($_SESSION['lllink']),0,-2)));

   $link = base64_decode($link);

   return $link;

 }

 /*
 *
 * porcjowanie listy rekordów na stronie
 *
 * - il_zd => ilość wszystkich zdjeć (rekordów)
 * - il_zdNaEkr => ilość zdjęć (rekordów) na jednej stronie
 * - link => adres podstrony bez końcówki , sama 'akcja'
 */

 public static function ekranLink($tab, $war, $link, $ekran, $ilReNaEkr = false, $ident = '', $separator = '_')
 {

  $il_Re = self::ileRekord($tab, $war);								//-ilość rekordów

  if(!$ilReNaEkr) $ilReNaEkr = C::get('IlRekNaEkr');

  if($ilReNaEkr)
	$il_ekr = $il_Re / $ilReNaEkr;										//-ilość ekranów = ilość linków
  else
   $il_ekr = 1;


  if($adres = C::get('opcja'))
  {
   $adres = explode('+', $adres);
   $param = array_pop($adres);
   $adres = implode('+', $adres);
  }
  else
   $adres = $link;

  if(isset($param))
  {
   $param = explode($separator, $param);
   $adres .= $param[0].$separator;
  }
  else
	$adres .= $separator;



  if($ident)
  {
	if(isset($_SESSION['ident_porc'][$ident])) $ekran = $_SESSION['ident_porc'][$ident];

   $ident = $separator.$ident;
  }


  if($il_ekr > 1)
  {
   for($i=0; $il_ekr > $i; $i++)
   {

    $zajLink = $adres.$i.$ident.'+'.$link.'.html';

    if($ekran == $i)
	  $wt[] = '<b class=\'ekrany\'>'.($i+1).'</b>';
	 else
     $wt[] = '<a class=\'ekrany\' href=\''.$zajLink.'\' >'.($i+1).'</a>';

   }

   $wt = implode('', $wt);

   unset($i, $adres, $param, $separator, $link, $il_Re);

   return '
	<div class=\'ekranyLinki\' >'.$wt.'</div>';
  }
  return false;
 }

 /**
 *
 * podaje ilość rekordów w tabeli, przy podanym warunku
 * 2012-11-06
 *
 */

 public static function ileRekord($tab, $war = '')
 {
  if($war) $war = ' WHERE '.$war;

  //exit('war = '.$war);

  if($tab = Db::myQuery('SELECT count(*) FROM '.$tab.$war))
  {
   if($tb = mysqli_fetch_row($tab))
   {
    unset($tab, $war);

    return $tb[0];
   }
   else
    return 0;
  }
  else
   return 0;
 }

 /**
 *
 * warunek dla porcjowania linków
 *
 * 2012-11-06 -> 2013-03-13
 *
 */

 public static function limit($ekran, $IlRekNaEkr = false, $ident = false)
 {
  if(isset($_SESSION['ident_porc'][$ident]))
	$ekran = $_SESSION['ident_porc'][$ident];
  else
   $ekran = 0;

  if(!$IlRekNaEkr) $IlRekNaEkr = C::get('IlRekNaEkr');

  if($IlRekNaEkr)
	return ' LIMIT '.($ekran * $IlRekNaEkr).', '.$IlRekNaEkr;
  else
   return '';
 }

 /** : syst/S.php
 *
 * zajawka wiekszego tekstu :: 2011-03-25
 *
 */

 public static function zaja($limit, $t)
 {
  //-limit = limit znaków
  if(strlen($t) > $limit)  								//-jeśli tekst źródłowy jest dłuższy od limitu
  {

	$tmp = substr($t, 0, $limit);							//-przycinamy tekst dozadanego limitu

	$tmp = substr($tmp, 0 , strrpos($tmp, ' '));		//-przycinamy po raz kolejny do ostatniej spacji

	while(substr($tmp, -2, -1) == ' ')					//-jeśli przed ostatni znak to spacja, przycinamy jeszcze o 2 znaki
	{
	 $tmp = substr($tmp, 0, -2);							//-powtarzamy aż do skutku dla np. dlatego i w takich ...
	}

   return $tmp.' ...';
  }
  else
   return $t;
 }

 /**
 *
 * identyfikacja czy rekord o podanym id istnieje w zadanej tabeli
 *
 */

 public function isRekordExists($tab, $id)
 {
  try
  {
   Db::MyQuery('SELECT * FROM '.$tab.' WHERE '.$id.' LIMIT 1');

   if(mysql_affected_rows()>0)
	 return 1;
   else
    return 0;
  }
  catch(Exception $e)
  {
   C::debug($e, 2);
  }

 }

 /**
 *
 * adres strony dla wtyczek FB
 *
 *
 */

 public static function urlSide()
 {
  $a[0] = C::get('opcja');
  $a[1] = C::get('akcja');

  if($a[0]) $a[0] .= '+';

  $a = C::get('www').'/'.implode('', $a).'.html';

  return 'http://'.$a;
 }

 /**
 *
 * aytomatyczna tablica słów i faraz tworzonych z keywords
 *
 * $seog = S::seoKeyAuto(); //alt z keywords
 * $altg = $seog[0][rand(0, $seog[1])];
 *
 */

 public static function seoKeyAuto()
 {

   $seo = C::get('con_keyw', false);

   if($seo)
   {
    $seo = explode(',', preg_replace('/, | ,/', ',', $seo));
	 $countKey = count($seo);

	 return array($seo, $countKey);
   }
   else
	 return false;

 }

 /**
 *
 * automatyczny path do zdjęć w formacie bezwzglednym dla FB
 *
 */

 public static function pathImg()
 {
  /*
  if(C::get('localhost'))
  {
	return C::get('fotyPath');
  }
  else
   return 'http://'.$_SERVER['HTTP_HOST'].preg_replace('/\./', '', C::get('fotyPath')); */


  //-2013-03-14
  if(C::get('localhost'))
  {
	return 'http://'._HOST.'/'.basename(_DOCROOT).substr(C::get('fotyPath'), 1);
  }
  else
  {
	return 'http://'._HOST.substr(C::get('fotyPath'), 1);
  }
 }

 /**
 *
 * wydzielenie z tekstu właściwej wersji językowej, na podstawie znaczników
 * UWAGA! w tej wersji tylko 2 języki, polski i 1 obcy!
 * 2012-12-04
 * 2012-04-22
 *
 */

 public static function langVersion($t)
 {

  $t = explode('^^', $t); //-ten łącznik trzeba przemyśleć !!!

  if(isset($_SESSION['lang']))
  {
   switch($_SESSION['lang'])
   {
    case 'DE' : case 'EN':
	  if($t[1])
	   return $t[1];
	  else
	   return $t[0];		//-jeśli nie ma wersji, to wersja domyślna
	 break;

	 default:
	  if($t[0])
	   return $t[0];
	  else
	   return false;
   }
  }
  else
   return $t[0];
 }

 /**
 *
 * ustawienie znacznika wersji językowej
 * 2012-12-04 -> dodany EN
 */

 public static function langSet($podstr)
 {

  function backSet()
  {

	if($_SESSION['linkMem'])
	{
	 return substr($_SESSION['linkMem'], 0, -5);
	}
	else
	 return 'start';
  }



  if($podstr === 'pl' && $_SESSION['lang'] !== 'PL')
  {
   $_SESSION['lang'] = 'PL';

	C::change('lang', $_SESSION['lang']);

	self::ggoto(backSet().'.html');

  }
  elseif($podstr === 'de' && $_SESSION['lang'] !== 'DE')
  {
  	$_SESSION['lang'] = 'DE';

	C::change('lang', $_SESSION['lang']);

	self::ggoto(backSet().'.html');

  }
  elseif($podstr === 'en' && $_SESSION['lang'] !== 'EN')
  {

   $_SESSION['lang'] = 'EN';

	C::change('lang', $_SESSION['lang']);

	self::ggoto(backSet().'.html');

  }

  /*
  else
  {
	if(isset($_SESSION['lang'])) C::change('lang', $_SESSION['lang']);
  } */

 }

 /**
 *
 * menu wyboru wersji językowej
 *
 *
 */

 public static function langWyb($w = false)
 {

  if(!$w)
  {
  if($_SESSION['lang'] === 'DE')
   return '
  <li><a href=\'./pl.html\' title=\'po polsku\'><img src=\'./skin/pl.png\' alt=\'PL\' /></a></li>';
  else
   return '
  <li><a href=\'./de.html\' title=\'in deutscher\'><img src=\'./skin/de.png\' alt=\'DE\' /></a></li>';
  }
  else
  {
   if($_SESSION['lang'] === 'DE')
    return '
	<li><a href=\'./pl.html\' title=\'po polsku\'><img src=\'./skin/pl.png\' alt=\'PL\' /></a></li>
	<li><span title=\'in deutscher\'><img src=\'./skin/de.png\' alt=\'DE\' /></span></li>';
	else
	 return '
	<li><span title=\'po polsku\'><img src=\'./skin/pl.png\' alt=\'PL\' /></span></li>
	<li><a href=\'./de.html\' title=\'in deutscher\'><img src=\'./skin/de.png\' alt=\'DE\' /></a></li>';
  }
 }

 /**
 *
 * dekodowanie id z ostatniego członu opcji adresu
 *
 */

 public static function thisId()
 {
  $id = C::get('opcja', false);

  if($id)
	return substr(end(explode('+', $id)), 3, -3);
  else
   return false;

 }

 /**
 *
 * kodowanie numerów id w adresach
 * 2012-01-10 dodano '+'
 *
 */

 public static function k($id, $ekran = '')
 {
  if($ekran > 0)
	$ekran = '_'.$ekran;
  else
   $ekran = '';

  return rand(100,999).$id.rand(100,999).$ekran.'+';
 }

 /**
 *
 *
 */

 public static function k2($id)
 {
  return rand(100,999).$id.rand(100,999);
 }

 /**
 *
 * dekodowanie numeru id dla publikacji
 *
 */

 public static function idEncode()
 {
  $id = C::get('opcja', false);

  if(!$id) return false;

  $id = end(explode('+', $id));

  $id = explode('_', $id);

  if($id[1] && is_numeric($id[1]))
   $id[1] = '_'.$id[1].'+';
  else
   $id[1] = '';

  if(!is_numeric($id[0]))
   return false;
  elseif($id[0] < 1000000)
   return false;
  else
   return array(substr($id[0], 3, -3), $id[1]);

 }

 /**
 *
 * losowa reklama według rozmiaru (podobna metoda w Start.php została usunięta)
 * 2012-11-07
 *
 */

 public static function reklama($size)
 {
  //echo 'JESTEM::';

  $jo = C::get('jo');

  //if($jo) echo 'JO=OK::';

  if(!$tabx = C::get('tab_reklama', false))
  {
   if($jo)
	 return 'ERROR: brek zdefiniowane nazwy tabeli -> tab_reklama w '.__METHOD__.' line -> '.__LINE__;
   else
  	 return;
  }

  if($jo)
	$do = '
		<a class=\'dodaj\' href=\'art.'.$tabx.',0,formu,rekl_stat.1,edycja.html\' title=\'dodaj zdjęcie\' >dodaj reklamę / baner</a>';
  else
   $do = '';

  try
  {
   $tab = "SELECT * FROM ".$tabx."
			  ORDER BY rand() LIMIT 1";

   //			  WHERE rekl_size = '$size'
	if($tab = Db::myQuery($tab))
   {
    if($ta = mysql_fetch_assoc($tab))
    {

	  if(C::get('jo'))
	  {
	   $li_zaw = '
		<a class=\'ladtre\' href=\'banery.'.$tabx.','.$ta['rekl_id'].',edycja.html\' title=\''._EDYTUJ_TRESC.'\'></a>';


	  }
	  else
	   $li_zaw = '';

	  if(!$_SESSION['admin']['status'] && !$jo)
	  {
      $tab = "UPDATE ".$tabx." SET rekl_open = rekl_open+1 WHERE rekl_id = '{$ta['rekl_id']}'";

	   Db::myQuery($tab);
	  }

	 for($i=0; $i<10; $i++)
	 {
	  $tag = explode('|', strip_tags($ta['rekl_alt'.$i]));

	  if($tag[1]) $tag[1] = ' rel=\''.$tag[1].'\'';

	  if($ta['rekl_fot'.$i]) $wt .= '
	 <img id=\'rekl'.$i.'\' src=\''.C::get('fotyPath').$ta['rekl_fot'.$i].'\' alt=\''.$tag[0].'\''.$tag[1].' />';
    }

	 $ta['rekl_kod'] = explode('|', strip_tags($ta['rekl_kod']));

	 if(count($ta['rekl_kod']) > 1 )
	  return '
		<div class=\'baner\'>
		 <a href=\''.trim($ta['rekl_kod'][0]).'\' title=\''.trim($ta['rekl_kod'][1]).'\' target=\'_blank\'>'.$wt.'
		 </a>
		</div>'.$do;
	 else
	  return '
	  <div class=\'baner\'>'.html_entity_decode($ta['rekl_kod'][0]).$wt.'
	  </div>'.$do;


     if($wt) return $li_zaw.$wt;

    }
	 else
	  return $do;
   }


  }
  catch(Exception $e)
  {
   return C::debug($e, 0);
  }

 }

 /**
 *
 * czyści tekst z BBkodów
 *
 * 2016-06-11
 * 2011-03-24 -> 2011-05-01 -> 2012-01-06
 *
 */

 public static function BBclear($t)
 {

  $t = html_entity_decode($t);

  if(!C::get('jo'))
   $t = preg_replace('#(%%)(.*?)(%%)+?#si', '', $t);		//-wycina komentarze zawarte pomiedzy znakami %%...%%
  else
	$t = preg_replace('/%%(.*?)%%/si', '%%\\1 %%<br />', $t);

  $t = preg_replace("#\[br\]#si", ' ', $t);

  $t = preg_replace("#\[[a-z0-9 ]{1,3}\]#si", '', $t);

  $t = preg_replace("#\[/[a-z0-9 ]{1,3}\]#si", '', $t);

  $t = preg_replace("#\[tabela\]#si", '', $t);				//-tabele : 2012-08-17
  $t = preg_replace("#\[/tabela\]#si", '', $t);

  $t = preg_replace('/\r|\n/si', ' ', $t);

  return $t;
 }

 /**
 *
 * 2016-06-11
 *
 */

 public static function formZaja($t)
 {

  $t = strip_tags($t);

  $t = preg_replace("#\[yt1\](.+)\|+(.+)\|+(.+)(\[/yt1\])?#i", '<p class=\'zobfilm\'>Zobacz Film</p>', $t);

  $t = preg_replace("#\[yt2\](.+)\|+(.+)\|+(.+)(\[/yt2\])?#i", '<p class=\'zobfilm\'>Zobacz Film</p>', $t);

  $t = preg_replace("#\[br\]#si", ' <br />', $t);

  $t = preg_replace("#\[url\](.+)\|+(.+)\|+(.*?)\[/url\]#si", '\\2', $t);


  $t = preg_replace_callback("#\[var\](.*?)\[/var\]#si","S::vario",$t);

  $t = self::BBclear($t);

  $t = preg_replace('/ +((http(s?):\/\/)|[^="\/](www\.))([\S]+\.)([a-zA-Z]+)(\/*?)([\S]*)/i', "<a class='lde' href='http$3://$4$5$6$7' title='kliknij aby przejść na stronę $4$5$6 \npoleca ".C::get('www')."'>$4$5$6</a>", $t);

  $t = preg_replace('#\[url=(.+) +(.+) +\+(.*?)\]#si', '<a class=\'url\' href=\'\\1\' title=\'\\3\'>\\2</a>', $t);		//-adresy url

  $t = preg_replace('/ \)/', '&nbsp;)', $t);					//-nawias zamykający ma się trzymać osttaniego wyrazu

  $t = preg_replace('/\s(\S)\s+/', ' $1&nbsp;', $t);

  $t = preg_replace('/\s(po|na|za|do|Do|od|Sp\.)\s+/', ' $1&nbsp;', $t);		//-wyrazy z listy poprzedzone spacją i zakończone spacją łączy spacją niełamliwą

  $t = preg_replace('/ \./', '&nbsp;.', $t);

  return $t;
 }

 /**
 *
 * 2016-06-11
 * formatowanie tekstów
 *
 */

 public static function formText($t)
 {

  if(!C::get('jo'))
   $t = preg_replace('/%%.*?%%/', '', $t);											//-wycina komentarze zawarte pomiedzy znakami %%...%%


  $t = preg_replace('/ +([^ .]+)-+ +/', '\\1', $t);								//-scala łamanie tekstu zajawki myślnikiem, do pełych tekstów

  $t = preg_replace('#_WWW#si', '[p]'.C::get('www').'[/p]', $t);

  $t = self::bbcode($t);
/*
  $t = preg_replace('/ +((http(s?):\/\/)|[^="\/](www\.))([\S]+\.)([a-zA-Z]+)(\/*?)([\S]*)/i', "<a class='lde' href='http$3://$4$5$6$7' title='kliknij aby przejść na stronę $4$5$6 \npoleca ".C::get('www')."'>$4$5$6</a>", $t); */

  $t = preg_replace('/\s(\S)\s+/', ' $1&nbsp;', $t);							  	//-usuwa gęby

  $t = preg_replace('/\s(\)&nbsp;|\]&nbsp;|\}&nbsp;)/', '&nbsp;$1 ', $t);	//-łączy nawias zamykający

  $t = preg_replace('/\s(po|na|za|do|Do|od|Sp\.)\s+/', ' $1&nbsp;', $t);


  $t = preg_replace('/!!!!/', '_', $t);
  return $t;
 }

 /**
 *
 * 2016-06-11
 *
 */

 private static function bbcode($t)
 {

  $t = preg_replace_callback("#\[ul\](.*?)\[/ul\]#si", "S::lista_ul", $t);

  $t = preg_replace_callback("#\[ol\](.*?)\[/ol\]#si", "S::lista_ol", $t);

  $t = preg_replace("#\[(podpis)\](.*?)\[/podpis?\]#si",'<p class=\'\\1\'>\\2</p>',$t);

  //$t = preg_replace('#\[url=(.+) +(.+) +\+(.*?)\]#si', '<a class=\'url\' href=\'\\1\' title=\'\\3\'>\\2</a>', $t);		//-adresy url

  $t = preg_replace_callback('#\[url=(.+) +(.+) +\+(.*?)\]#si', "S::url", $t);		//-adresy url

  $t = preg_replace_callback("#\[url\](.*?)\[/url\]#", "S::url3", $t);
  $t = preg_replace_callback("#\[ur\](.*?)\[/ur\]#", "S::url5", $t);
  $t = preg_replace_callback("#\[urd\](.*?)\[/urd\]#", "S::url6", $t);

  //$t = preg_replace_callback("#\[urn\](.*?)\[/urn\]#", "S::url5", $t);	// 2017-02-23

  $t = preg_replace_callback("#\[yt1\](.+)\|+(.+)\|+(.*?)\[/yt1\]#", "S::yt1", $t);
  $t = preg_replace_callback("#\[yt2\](.+)\|+(.+)\|+(.*?)\[/yt2\]#", "S::yt2", $t);
  $t = preg_replace_callback("#\[ifr\](.+)\|+(.+)\|+(.*?)\[/ifr\]#", "S::ifr", $t);	//-pływające ramki z obrazem z innych stron/kamer

  $t = preg_replace('#\[([b-z0-9]{2})\](.*?)\[/\\1?\]#si','<b class=\'\\1\'>\\2</b>',$t); // od b do z

  $t = preg_replace('#\[([b-z0-9]{1})\](.*?)\[/\\1?\]#si','<b class=\'\\1\'>\\2</b>',$t); // od b do z

  $t = preg_replace("#\[a([a-z]?)\](.*?)\[/a?\]#si",'
	 <p class=\'akapit \\1\'>\\2
	 </p>',$t);

  $t = preg_replace("#\[(var)\](.*?)\[/var\]#si",'
	 <p class=\'akapit \\1\'>\\2
	 </p>',$t);

  $t = preg_replace('/^>\r?\n/', '<br />', $t);								//-musi być po ul i ul, które koduja po znaku nowego wiersza

  $t = preg_replace_callback("#\[kod\](.*?)\[/kod\]#si", "S::kod", $t);

  $t = preg_replace_callback("#\[przyklad\](.*?)\[/przyklad\]#si", "S::przyklad", $t);

  $t = preg_replace_callback("#\[tabela\](.*?)\[/tabela\]#si", "S::tabela", $t);	 //-tabele 2012-08-17

  $t = preg_replace("#\[br\]#si", '<br />', $t);

  //$t = preg_replace('/\s(\S)\s+/', ' $1&nbsp;', $t);					//-usuwa gęby
  $t = preg_replace('/\[\]/', '$1&nbsp;', $t);								//-łączy wskazane wyrazy

  return $t;
 }


 /**
 *
 *
 */

 private static function vario($matches)
 {
  $matches[1] = preg_replace('/\|/', '<br/>', $matches[1]);

  return '
	<p>'.$matches[1].'</p>';
 }

 /**
 *
 *
 */

 private static function url($matches)
 {
  $matches[2] = preg_replace('/_/', ' ', $matches[2]);

  return '
	<a class=\'url\' href=\''.$matches[1].'\' title=\''.$matches[3].'\'>'.$matches[2].'</a>';
 }

 /**
 *
 *
 */

 private static function url4($m)
 {
  return ' <a class=\'url2\' href=\''.trim($m[1]).'\' title=\''.trim($m[3]).'\'>'.trim($m[2]).'</a> ';
 }

 /**
 *
 *
 */

 private static function url3($m) // dawniej url3
 {
  return preg_replace_callback("#(.+)\|+(.+)\|+(.+)#si", "S::url4", $m[1]);
 }

 /**
 *
 *
 */

 private static function url5($m)
 {
  $link = trim($m[1]);
  $m = parse_url($link);

  //Test::trace(__METHOD__ .' TABB', $m);

  if($op = $m[path])
   $opk = substr($op, 0, 7).'...';
  else
   $opk = '';

  $m = $m['host'].$opk;
  $target = ' target=\'_blanc\'';

  return ' <a class=\'url2\' href=\''.$link.'\' title=\''.$op.'\' '.$target.'>'.$m.'</a> ';
 }

 private static function url6($m)
 {
  $link = trim($m[1]);
  $m = parse_url($link);

  //Test::trace(__METHOD__ .' TABB', $m);

  if($op = $m[path])
   $opk = substr($op, 0, 7).'...';
  else
   $opk = '';

  $m = $m['host'].$opk;
  $target = ' target=\'_blanc\'';

  return ' <a class=\'url2\' href=\''.$link.'\' title=\''.$op.'\' '.$target.'>'.$link.'</a> ';
 }



 /**
 *
 * m[1] - adres
 * m[2] - tekst
 * m[3] - parametry : szerokość;wyrównanie;opływ
 *  - szerokość wysokość automatycznie wsp. 0,5625
 *  - wyrównanie: left, right, center, float-left, float-right
 *  - left i right: formatowane bez opływania
 *
 */

 private static function form_yt($m)
 {

  $m[1] = trim($m[1]);

  $m[1] = explode('/', $m[1]);

  if(is_array($m[1]))
   $m[1] = array_pop($m[1]);

  $st[0] = 'width: 560px';
  $st[1] = 'height: 315px';
  $st1 = 560;
  $st2 = 315;

  $m[3] = explode(';', trim($m[3]));

  if(!is_array($m[3])) $m[3][0] = $m[3];

  if(is_numeric($m[3][0]))
  {
   $st1 = $m[3][0];
	$st2 = round(($m[3][0] * 0.5625), 0);

	$st[0] = 'width: '.$st1.'px';
	$st[1] = 'height: '.$st2.'px';
  }
  elseif(!$m[3][1])
  {
   $m[3][1] = $m[3][0];
  }

  $war = array(trim($m[3][1]));

  if(array_intersect($war, array('left', 'right', 'center', 'float-left', 'float-right')))
   switch(trim($m[3][1]))
	{

	 case "left" : $st[2] = 'margin-left:0; left:0'; break;
	 case 'right' : $st[2] = 'left: 100%; margin-left: -'.($st1).'px'; break;
	 case 'float-left' : $st[2] = 'float: left'; break;
	 case 'float-right' : $st[2] = 'float: right'; break;

	}

  unlink($war);

  if($st[2] == '') $st[2] = 'margin: 1em auto';

  $st = implode('; ', $st).';';

  return array($m[1], $st1, $st2, $st);
 }

 /**
 * 2016-06-11
 * materiał video z  YoyTube po staremu
 *
 */

 private static function yt1($m)
 {

  list($a, $st1, $st2, $st) = self::form_yt($m);

  return '
  <div class=\'yt1\' style=\''.$st.'\'>
	<object width="'.$st1.'" height="'.$st2.'">
	 <param name="movie" value="//www.youtube.com/v/'.$a.'?version=3&amp;hl=pl_PL&amp;rel=0"></param>
	 <param name="allowFullScreen" value="true"></param>
	 <param name="allowscriptaccess" value="always"></param>
	 <embed src="//www.youtube.com/v/'.$a.'?version=3&amp;hl=pl_PL&amp;rel=0" type="application/x-shockwave-flash" width="'.$st1.'" height="'.$st2.'" allowscriptaccess="always" allowfullscreen="true"></embed>
	</object>
  </div>';

 }


 /**
 * 2016-06-11
 * materiał video z  YoyTube po nowemu
 * 2015-09-27 : formatowanie przez css
 *
 */

 private static function yt2($m)
 {

  list($a, $st1, $st2, $st) = self::form_yt($m);

  return '
   <div class=\'yt2\' style=\''.$st.'\'>
	 <iframe id=\'yt2\' src="//www.youtube.com/embed/'.$a.'" frameborder="0" allowfullscreen></iframe>
   </div>';

 }

 /**
 * 2016-06-11
 * materiał w ramkach ifr - inne strony / obrazy z kamer
 *
 */

 private static function ifr($m)
 {

  //list($a, $st1, $st2, $st) = self::form_yt($m);

  $m[1] = preg_replace('/_/', '!!!!', $m[1]);

  return '
   <div class=\'yt2\' >
	  <iframe src="http:://'.trim($m[1]).'" width="100%" height="450" border="0" frameborder="0" scrolling="no"></iframe>

   </div>';


	//<iframe src="http://webstream1.webcamera.pl/apinew/ramkaresp.php?cam=kopa_cam_6ef73b" width="100%" height="450" border="0" frameborder="0" scrolling="no"></iframe>

 }

 /**
 *
 * tworzenie tabeli z tekstu umieszczonego w [tabela]...[/tabela]
 * 2012-08-17
 * piwerwszy wiersz automatycznie otrzymuje klasę 'heder'
 * nastepnie co drugi otrzymuje klasę 'drugi'
 *
 */

 private static function tabela($matches)
 {
  if(!$matches[1]) return;

  $wiersz = preg_split("/\r\n?/si", $matches[1]);

  //return '{wiersz = } '.$wiersz;

  if(is_array($wiersz))
  {

	foreach($wiersz as $wart)
	{
	 if($wart)
	 {

	  if(substr($wart, 0, 1) == '.')
	   $class = substr($wart, 1);
	  else
     {


	   $w = explode(';', $wart);

	   if(is_array($w))
	   {

	    foreach($w as $wart2)
		 {
		  //$wart2 = preg_replace("#\[urt\](.+)\|+(.+)\|?(.*?)\[/urt\]#si", '<a href=\'\\1\' title=\'\\3\'>\\2</a>', $wart2);

		  $wart2 = preg_replace_callback("#\[urt\](.+)\|+(.+)\|?(.*?)\[/urt\]#si", "S::url2", $wart2);

	     $t .= '
		  <td>'.$wart2.'</td>';
       }


      if($lw == 0)
		 $heder = ' class=\'heder\'';

      if($lw == 2)
		{
		 $heder = ' class=\'drugi\'';
		 $lw = 0;
		}

	    if($t) $tt .= '
		  <tr'.$heder.'>'.$t.'
		  </tr>';

	    unset($t);
	   }
	   else
	    return;

		$lw++;
	  }
	  unset($heder);
	 }
	}

  }
  else
   return;

  if($tt)
  {
	if($class) $class = ' class=\''.$class.'\'';

	return'
		<table'.$class.'>'.$tt.'
		</table>';
  }
  else
   return;

 }

 /**
 *
 *
 */

 private static function przyklad($matches)
 {
  return '
	<div class=\'przyklad\'>'.html_entity_decode($matches[1]).'
	</div>';
 }

 /**
 *
 *
 */

 private static function lista_ul($matches)
 {
  //$matches[1] = preg_replace_callback("#\[url\](.+)\|+(.+)\|+(.*?)\[/url\]#si", "S::url2", $matches[1]);

  $t = '
 <ul class=\'bbcode\'>
 <li>'.preg_replace('/\r?\n/', '</li>
 <li>', trim($matches[1])).'</li>
 </ul>';

  return preg_replace('/\r?\n/', '', $t);
 }

 /**
 *
 *
 */

 private static function lista_ol($matches)
 {

  //$matches[1] = preg_replace_callback("#\[url\](.+)\|+(.+)\|+(.*?)\[/url\]#si", "S::url2", $matches[1]);

  $t = '
 <ol class=\'bbcode\'>
 <li>'.preg_replace('/\r?\n/', '</li>
 <li>', trim($matches[1])).'</li>
 </ol>';

  return preg_replace('/\r?\n/', '', $t);
 }

 /**
 *
 *
 */

 private static function kod($matches)
 {
  //-ważna jest kolejność!!

  $to[] = '#( or | && | and |!=|,|\.|!|={1,3}|:{1,2})#si';
  $na[] = '<b class=\'r\'>\\1</b>';

  $to[] = '/(\$[a-zA-z0-9]*?)(\)|\}|\]|\[)+?/';
  $na[] = '<b class=\'n\'>\\1\\2</b>';

  $to[] = '/\[/';
  $na[] = '<b class=\'r\'>&#91</b>';

  $to[] = '/\]/';
  $na[] = '<b class=\'r\'>&#93</b>';

  $to[] = '/(\{|\}|\(|\))/';
  $na[] = '<b class=\'r\'>\\1</b>';

  $to[] = '/\&amp;/';
  $na[] = '&';

  $to[] = '#(session_unset|session_destroy|echo|preg_replace|endif|if|while|md5|time|onchange|alert|const|defined|define|else|return|die|exit|isset|function|private|public|protected)#si';
  $na[] = '<b class=\'g\'>\\1</b>';

  $matches[1] = preg_replace($to, $na,  $matches[1]);

  return '
<pre class=\'pre_kod\'>
<code>'.trim($matches[1]).'</code>
</pre>';
 }

 /**
 *
 * konwersja daty w formacie rrrr-mm-dd
 *
 */

 public static function kdata($d, $c)
 {

  if($d)
  {
   $da  = explode('-', substr($d,0,10));

   switch($c)
   {
    case 0: return  $da[2].'-'.$da[1].'-'.$da[0];							//-dd-dd-rrrr
    case 1: return  $da[2].'-'.$da[1].'-'.$da[0].' '.substr($d,11); 	//-dd-mm-rrrr-godzina
    case 2: return  $da[2].'-'.$da[1].'-'.substr($da[0],2);				//-dd-dd-dd
   }
  }
  else
   return;
 }

 /**
 *
 * 2012-11-24 : dodany parametr $tabf = tablica zdjęć
 * $f = tablica zdjęć
 * $pat = ścieżka
 * $alt = awaryjny alt do zdjeć
 * $tfd = przedrostek miniatury
 * $tta = przedrostek pola tabeli - do wycofania??
 */

 public static function pozFotoNew($f, $pat, $alt, $title, $name, $tfd, $tabf = 'a', $back)
 {
  //$back = C::get('akcja');

  if(is_array($f))
  {

	$jo = C::get('jo');

   //$jo = C::get('ja');	//-2015-03-14 - proteza praw dostępu

	if($jo)
	{
	 if($tabf == 'a')
	  $tab = C::get('tab_fota');
	 else
	  $tab = $tabf;

	  $tabd = C::get('tab_fotx');									//- tabela zdjęć doklejonych 2016-05-27
	}

	$title = html_entity_decode(strip_tags($title));		//-znaczniki zapasowe
	$name  = html_entity_decode(strip_tags($name));
   $alt   = html_entity_decode(strip_tags($alt));

   $pat_lok = C::get('fotyPath');		//-do testowania czy istnieją wybrane pliki
	$pat_tmp = C::get('tmpPath_foty');	//-katalog tymczasowy z oryginalami wgranymi na serwer;

   $i = 0;
	$sz = 20;

	$fo0 = $fo1 = $fo2 = $fo3 = '';

   while(isset($f[$i])) 											//-pętla po wszystkich znalezionych plikach = zdjęciach
   {
    $no = false;

	 $fblok = $zablo = $title = $tname = '';

    switch($f[$i]['fo_poz0']) 									//-wybór formatu
    {
     case 0: case 3: case 7: $imgc = 'imgl'; break;
	  case 4: $imgc = 'imgll'; break;
     case 1: case 8: $imgc = 'imgs'; 	break;
     case 2: case 5: case 9: $imgc = 'imgr';	break;
	  case 6: $imgc = 'imgrr';
    }

    if($jo)
	  if($f[$i]['fo_blok'])
	  {
	   $fblok = ' class=\'adm_fblok\'';
	   $zablo = '<p class=\'adm_zablok\'>'.L::k('blokF').'</p>';
	  }


	 if($f[$i]['fo_tytu']) $ttitle = html_entity_decode(strip_tags($f[$i]['fo_tytu'])); else $ttitle = $title;


	 $tab_foto_opis = explode('|', html_entity_decode(strip_tags($f[$i]['fo_opf0'])));

	 $tab_foto_opis = array_pad($tab_foto_opis, 2, false);

	 if($tab_foto_opis[0])
	  $tname = $ttitle.' | '.$tab_foto_opis[0];
	 else
	  $tname = $ttitle;

	 $title = $tname;

	 if($tab_foto_opis[1])
	  $talt = $tab_foto_opis[1];
	 else
	  $talt = $tab_foto_opis[0];


    if(file_exists($pat_lok.$tfd.$f[$i]['fo_fot0']) && file_exists($pat_lok.$f[$i]['fo_fot0']))	 	//-jeśli jest miniatura i duży format
	 {
	  $width = getimagesize($pat_lok.$tfd.$f[$i]['fo_fot0']);
	  if($sz < $width[0]) $sz = $width[0];

     $imgcc = '
		<a href=\''.$pat.$f[$i]['fo_fot0'].'\' class=\''.$imgc.'\' rel=\'lightbox[a]\' title=\''.$title.'\' name=\''.$tname.'\'>'.$zablo.'
		 <img'.$fblok.' src=\''.$pat.$tfd.$f[$i]['fo_fot0'].'\' alt=\''.$talt.'\' />
		</a>';
	 }
    else
	 {
	  if(file_exists($pat_lok.$f[$i]['fo_fot0']))					//-jeśli jest tylko "duży" format, bo np. oryginał był za mały do przeskalowania
	  {
	   $width = getimagesize($pat_lok.$tfd.$f[$i]['fo_fot0']);
	   if($sz < $width[0]) $sz = $width[0];

      $imgcc = '
		<span class=\''.$imgc.'\' title=\''.$ttitle.'\'>'.$zablo.'
		 <img'.$fblok.' src=\''.$pat.$tfd.$f[$i]['fo_fot0'].'\' alt=\''.$talt.'\' />
		</span>';
	  }
	  else
	  {
	   if($jo)
		{
		 if(file_exists($pat_tmp.$f[$i]['fo_fot0']))
		 {
		  $imgcc = '
		<div id=\'fot'.md5($f[$i]['fo_id']).'\' class=\'edFoto\'>
		 <div class=\'ed edF Box\'>
		  <a class=\'del\' href=\''.S::linkCode(array($tab,$f[$i]['fo_id'],'kasuj','', $back,'fot')).'.htmlc\' title=\''.L::k('kasF').'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasuj').'-xk</a>
		 </div>
		 <a class=\''.$imgc.'\' href=\''.S::linkCode(array($tab,$f[$i]['fo_id'],'edycja','',$back,'fot')).'.htmlc\' title=\''.L::k('doKadr1').'\'>
		  <p>&nbsp;'.L::k('doKadr2').'&nbsp;</p>'.$zablo.'
		  <img'.$fblok.' src=\'thumbnail.php?id='.$f[$i]['fo_fot0'].':'.session_id().':100:100\' alt=\''.L::k('doKadr').'\' />
		 </a>
		</div>';

		  $no = true;
		 }
		 else
		 {
		  $imgcc = '
		<span class=\''.$imgc.'\'>
		 <p>Zdjęcie nie istnieje w tmp_foto!</p>
		</span>';
		 }

		}
	   else
	    unset($imgc);

	  }
	 }




	 if($imgcc) 							//-wybór bloku
	 {

	  if($jo && !$no)
	   if(isset($f[$i]['fo_blok']))
	   {
		 $imgcc = '
		<div id=\'fot'.md5($f[$i]['fo_id']).'\' class=\'edFoto\'>'.$imgcc.'
		 <div class=\'ed edF\'>
		  <a href=\''.S::linkCode(array($tab,$f[$i]['fo_id'],'edycja','', $back,'fot',)).'.htmlc\' title=\''.L::k('edyF').'\'>'.L::k('edytuj').'</a>
		  <a class=\'del\' href=\''.S::linkCode(array($tab,$f[$i]['fo_id'],'kasuj', '', $back, 'fot')).'.htmlc\'
			title=\''.L::k('kasF').'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasuj').'-xk2</a>
		 </div>
		</div>';
	   }
	   else
	   {
		 $imgcc = '
		<div id=\'fot'.md5($f[$i]['fo_id']).'\' class=\'edFoto adFotod\'>'.$imgcc.'
		 <div class=\'ed edF\'>
		  <a href=\''.S::linkCode(array($tabd,$f[$i]['fo_id'],'edycja','', $back,'fot',)).'.htmlc\' title=\''.L::k('edyF').'\'>'.L::k('edytuj').'</a>
		  <a class=\'del\' href=\''.S::linkCode(array($tabd,$f[$i]['fo_id'],'kasuj', '', $back, 'fot')).'.htmlc\'
			title=\''.L::k('kasF').'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasuj').'-xk2</a>
		 </div>
		</div>';

	   }

     switch($f[$i]['fo_poz0'])
     {
	   case 0: case 1: case 2: $fo0 .= $imgcc; break;
	   case 3: case 4: $fo1 .= $imgcc;  break; //$fo1li++;
	   case 5: case 6: $fo2 .= $imgcc;  break; //$fo2li++;
	   case 7: case 8: case 9: $fo3 .= $imgcc;
     }

	 }

    $i++;
   }

   unset($f, $i, $imgc, $imgcc, $tfd, $pat, $title, $alt, $name, $ttitle, $talt, $tname, $tab, $no);
   //-przydział zdjęć do kontenerów

   if($fo0) $fo0 = '
	<div class=\'foup\'>'.$fo0.'
	</div>';		//-kontener górny

   if($fo1) $fo1 = '
	<div class=\'foleft\' style=\'width:'.$sz.'px;\'>'.$fo1.'
	</div>';		//-kontener lewy, objęty divem nawet dla 1 zdjęcia ze względu na IE : zmiana 2010-12-30

   if($fo2) $fo2 = '
	<div class=\'foright\' style=\'width:'.$sz.'px;\'>'.$fo2.'
	</div>';		//-kontener prawy, objęty divem jeśli więcej niż jedno zdjęcie

   if($fo3) $fo3 = '
	<div class=\'fodown\'>'.$fo3.'
	</div>';		//-kontener dolny

   unset($fo1li, $fo2li, $pat_lok, $sz, $width, $height); //-kasowanie liczników dla pozycji 4,5 i 6,7

   return array($fo0, $fo1, $fo2, $fo3);
  }
 }

 /**
 *
 * klasa oznaczająca kolorem tła element zablokowany i element do publikacji
 *
 */

 public static function klasaPola($b, $t, $p = false) //-ustalenie klasy dla pola :: zablokowane, do publikacji
 {
  //[b=blokada, t=data publikacji, p=[dodatkowa klasa]]

  $klasa = false;

  if($b)
   $klasa = 'blok';							//-klasa pola zablokowanego
  else
   if($t > C::get('datetime_teraz'))
    $klasa = 'dopu';							//-klasa pola do publikacji

  unset($b, $t);

  if($klasa)
  {
   if($p)
    return ' class=\''.$klasa.'\'';
   else
    return ' '.$klasa;
  }
  else
   return;
 }

 /**
 *
 * metoda generuje standardowe menu
 *
 * poprawiono funkcję active dla linków bezpośrednich s przedrostkiem SEO
 *
 * $b = true :: <a>link</a>  ( wyłancza objęcie nazwy linku w znaczniki <b></b> )
 * $b = false :: <a><b>link</b></a>
 *
 */

 public static function menu($m, $anim = false, $b = false)
 {

  $akcja = C::get('akcja');
  $op = C::get('opcja');

  $meni = '';

  if(is_array($m))
  {

   foreach($m as $k => $v)
   {
	 $act = '';
	 $submenu = '';

	 if(is_array($v))
	 {

	  $v = array_pad($v, 2, '');

	  foreach($v as $k3 => $v2)
	  {
		$v2 = explode('^', $v2);

	   $submenu .= '
		<li><a href=\''.$k3.'\' title=\''.$v2[1].'\'>'.$v2[0].'</a></li>';

	  }

	  if($submenu) $submenu = '
	  <ul id=\'submenu\'>
	   <li><span>'.$k.'</span></li>'.$submenu.'
	  </ul>';

	 }
	 else
	 {
     $k3 = explode('+', $k);

	  $k2 = reset($k3);

     if($akcja === substr(array_pop($k3), 0, -5)) $act = 'active';

	  $k3 = explode('+', $op);

	  if(reset($k3) === $k2)
		$act .= ' active2';

	  if($act) $act = 'class=\''.$act.'\''; else $act = '';

	 }


    $v = explode('^', $v);

	 if(is_array($v))
	 {
	  $v = array_pad($v, 6, '');

     if($v[2]) $v[2] = 'target=\''.$v[2].'\' ';

	 if($v[4]) $v[4] = 'id=\''.$v[4].'\' ';

	 if($v[5] && $anim)
	  $v[5] = '<img src=\''.$v[5].'\' alt=\'\'>';
	 else
	  $v[5] = '';

	 if(!$b) $v[0] = '<b>'.$v[0].'</b>';


	 if($submenu)
     $meni .= '
		<li class=\'m1\'>'.$submenu.'</li>';
	 else
     $meni .= '
		<li class=\'m1\'><a '.$v[4].$act.' href=\''.$k.'\' '.$v[2].' title=\''.$v[1].'\' >'.$v[5].$v[0].'</a>'.$v[3].'</li>';
    }

	 unset($submenu, $k3, $v2, $act);
   }


	if(C::get('logowanie'))
	{
	 $log_menu = C::get('login_menu');

	 if(!$_SESSION['us_zalog'])
	 {
     list($zal_adr, $zal) = each($log_menu);

	  $meni .= '
		<li><a href=\''.$zal_adr.'\' title=\'\' ><b>'.$zal.'</b></a></li>';
	 }
	 else
	 {
	  array_shift($log_menu);

	  list($wyl_adr, $wyl) = each($log_menu);

	  $meni .= '
		<li><a href=\''.$wyl_adr.'\' title=\'\' ><b>'.$wyl.'</b></a></li>';

	 }
	 unset($log_menu, $zal_adr, $zal, $wyl_adr, $wyl);
	}

   unset($m, $k, $k2, $v, $act, $keys, $active);

   return $meni;
  }
 }

 /**
 *
 * kasuje kropki ??
 *
 */
 /* do skasowania 2012-11-20
 public static function seoTyt($t, $dl)
 {

  return self::seoTExt($t, $dl);
  //return preg_replace('/\./', '', self::seoTExt($t, $dl));

 } */

 /**
 *
 * seo dla tytułu strony z tytułu publikacji
 * wyczyszczenie tekstu z tagów HTML i ograniczenie do zadanej długości
 *
 */

 public static function seoText($t, $dl)
 {

  $t = html_entity_decode(strip_tags(self::BBclear($t)));

  $t = preg_replace('/"|\\|\'/', ' ', $t);
  $t = preg_replace('/ {2,}/', ' ', $t);

  if(strlen($t) > $dl)
 	return substr($t, 0, ($dl-3)).'...';
  else
   return trim($t);

 }

 /**
 *
 * usunięcie z linków wszystkich niedozwolonych znaków
 * 2012-12-04 : poprawki
 */

 public static function clearUrl($link)
 {

  $link = self::BBclear($link);

  $link = preg_replace('#/#', ' ', $link);
  $link = preg_replace('#\.#', ' ', $link);

  $link = preg_replace('/\]|\[|\\|:|"|\'|,|&|&amp;|\?|;|_|\#|!|%|\*|\-|@/', ' ', $link);

  $link = preg_replace('/ {2,}/', ' ', $link);
  $link = preg_replace('/ /', '+', trim($link));

  return $link;
 }

 /**
 *
 *
 */

 private static function strtolowerpl($in)
 {
  $in = preg_replace(array('/Ą/', '/Ć/', '/Ę/', '/Ł/', '/Ń/', '/Ó/', '/Ś/','/Ź/', '/Ż/'), array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'), $in);
  $in = preg_replace(
	array('/ą/', '/ć/', '/ę/', '/ł/', '/ń/', '/ó/', '/ś/', '/ź/', '/ż/'),
	array('aaa', 'ccc', 'eee', 'lll', 'nnn', 'ooo', 'sss', 'xxx', 'zzz'), $in);

  $in = strtolower($in);
  return preg_replace(
	array('/aaa/', '/ccc/', '/eee/', '/lll/', '/nnn/', '/ooo/', '/sss/', '/xxx/', '/zzz/'),
	array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'), $in);
 }

 static function strtopl($in)
 {
  /*
  $in = preg_replace(array('/Ą/', '/Ć/', '/Ę/', '/Ł/', '/Ń/', '/Ó/', '/Ś/','/Ź/', '/Ż/'), array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'), $in);
  $in = preg_replace(
	array('/ą/', '/ć/', '/ę/', '/ł/', '/ń/', '/ó/', '/ś/', '/ź/', '/ż/'),
	array('aaa', 'ccc', 'eee', 'lll', 'nnn', 'ooo', 'sss', 'xxx', 'zzz'), $in); */

  //$in = strtolower($in);
  return preg_replace(
	array('/aaa/', '/ccc/', '/eee/', '/lll/', '/nnn/', '/ooo/', '/sss/', '/xxx/', '/zzz/', '/AAA/', '/CCC/', '/EEE/', '/LLL/', '/NNN/', '/OOO/', '/SSS/', '/XXX/', '/ZZZ/'), array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż','Ą','Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż'), $in);
 }

 /**
 *
 *
 */

 private static function clearpl($in)
 {
  return preg_replace
  (
  	array('/Ą|ą/', '/Ć|ć/', '/Ę|ę/', '/Ł|ł/', '/Ń|ń/', '/Ó|ó/', '/Ś|ś/','/Ź|ź/', '/Ż|ż/'),
	array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z'),
	$in
  );

 }

 /**
 *
 * zamiana na małe litery według kodowania utf8
 *
 */

 public static function strtolower_utf8($in)
 {
  if(C::get('lang') == 'PL')
	return self::strtolowerpl($in);
  else
   return self::clearpl($in);

  $in = utf8_decode($in);
  $in = strtolower($in);
  $in = utf8_encode($in);

  return $in;
 }

 /**
 *
 *
 *
 */

 public static function seoLink($p1, $id, $ekran = false, $typ = 0)
 {
  // p1-> text do SEO
  // id-> identyfikator do odnośnika
  // typ-> 1=SEO tytułu strony
  // typ-> 0=SEO odnośnika

  $p1 = self::clearUrl($p1);
  $p1 = self::strtolower_utf8($p1);

  if($ekran && is_numeric($ekran)) $ekran = '_'.$ekran;

  return $p1.'+'.rand(111,999).$id.rand(111,999).$ekran.'+';						//-ukrycie id rekordu
 }

 /**
 *
 *
 *
 */

 public static function FB_Like()
 {
  return '
     <div class="fblike" title="polub mnie na FB :)">
	   <fb:like href="'.self::urlSide().'" send="true" layout="button_count" width="450" show_faces="false" action="recommend" font="arial"></fb:like>
	  </div>';

  //-skrypt w nagłówku !!! strony
 }

 /**
 *
 * tendencje wejść: wzrost, bez zmian, spadek
 *
 *
 */

 public static function tend()
 {
  $tend = C::get('vi_tend') - C::get('licznik_tend');

  if($tend == 0)
   $tend = 2;
  else
   if($tend > 0)
	 $tend = 3;
	else
	 $tend = 1;

  switch($tend)
  {
	case '1': return '&dArr;';
	break;

	case '2': return '&hArr;';
	break;

	case '3': return '&uArr;';
	break;

	default: return ' ';
  }
 }

 /**
 *
 * skok do adresu ( jeśli nie podamy rozszerzenia, .html dodawane jest automatycznie)
 *
 * 2011-10-07 -> modyfikacja !!
 *
 * 2012-07-12 -> modyfikacja o możliwość dodania parametrów dla metody GET
 *
 */

 public static function ggoto($adres, $skok = '')
 {

  $adres1 = explode('?', $adres, 2);

  $adres = explode('.', $adres1[0], 2);

  if($adres1[1])
   $adres1 = '?'.$adres1[1];
  else
   $adres1 = '';

  if($skok != '' && substr($skok, 0, 1) != '#' && $skok && !is_array($skok))
   $skok = '#'.$skok;
  else
   $skok = '';

  if($adres[1])
  {
   if(in_array($adres[1],  array('php','html','smsl')))			//-dozwolone rozszerzenia
   {
	 self::myHeaderLocation($adres[0].'.'.$adres[1].$adres1.$skok);

    //header('location: ./'.$adres[0].'.'.$adres[1].$adres1.$skok);
    //exit('END-> '.$adres[0].'.'.$adres[1]);
   }
   else
    self::ErrorAdmin('niedozwolony parametr w :'.__METHOD__ .' -> line : '.__LINE__);
  }
  else
  {
   self::myHeaderLocation($adres[0].'.html'.$skok);

   //header('location: ./'.$adres[0].'.html'.$skok);
   //exit('END-> '.$adres[0].'.html');
  }
 }

 /**
 *
 * wylogowanie usera ze strony
 *
 * 2012-11-07 :: 3 wystąpienia
 */

 public function userLogOut($kom = false)
 {
  session_start();

  if($_SESSION['us_zalog'])
  {

	$_SESSION = array();																	//-Uwaga: to usunie sesję, nie tylko dane sesji
	$_COOKIE = array();

	//-Jeśli pożądane jest zabicie sesji, usuń także ciasteczko sesyjne

	$time = time()-42000;

	if(isset($_COOKIE[session_name()])) setcookie(session_name(), '', $time);

	setcookie(_CIA_ADM, '', $time);													//-usunięcie ciacha dla admin
	setcookie(_CIA_VIP, '', $time);													//-usunięcie ciacha dla vipa

	session_destroy(); 																	//-na koniec zniszcz sesję

	unset($time);

	session_start();

	if($kom)
	{
	 if($kom == 'timeout')
	  self::komunikat('
	<p class=\'out\'>Przekroczony czas bezczynności.</p>', 'komunikat');

   }
	else
	  self::komunikat('
	<p class=\'out\'>Zostałeś wylogowany z serwisu : <b>'.$_SERVER['HTTP_HOST'].'</b><br />You are logout.<br />Sie sind abgemeldet.</p>', 'komunikat');

  }
  elseif($kom)
	self::komunikat('
	<p class=\'out\'>Nikt aktualnie nie jest zalogowany.</p>', 'komunikat');
 }

 /**
 *
 * przerwanie klasy i wysłanie komunikatu $tekst na stronę o adresie $adres
 *
 *
 */

 public static function komunikat($text, $skok = '', $adres = _KOMUNIKAT_SITE)
 {
  $_SESSION['komunikat'] = $text;

  self::ggoto($adres, $skok);
 }

 /** S::ErrorAdmin()
 *
 * Komunikat błędu dla Administratora
 * UWAGA! do zastosowania jedynie we wtyczkach !!
 *
 * W trybie użytkownika zapisuje komunikaty do systemowego pliku błędów i wyświetla stronę błędu
 * W trybie administratora wyświetla komunikat o błędzie
 *
 * 2012-11-29 : poprawki, wersja językowa adresu strony z komunikatem i sam komunikat
 * 2012-10-31 : poprawki
 *
 */

 public static function ErrorAdmin($komunikatAdmin, $komunikatUser = '', $adres = _KOMUNIKAT_SITE, $skok = false)
 {

  if(!defined('_KOMUNIKAT'))
   C::error('Strona dla komunikatów nie jest usawiona!');

  if(!file_exists('./application/szablony/html_serwis'._EX))
   C::error('Brak szablonu dla komunikatów!');

  if(is_array($komunikatAdmin))
  {

	foreach($komunikatAdmin as $k => $w)
	{
	 if(is_array($w))
	 {
	  $kom .= '
	  <pre>'.print_r($w).
	  '</pre>';

	 }
    else
	  $kom .= '
	  <p>'.$w.'</p>';
	}

	$komunikatAdmin = $kom;

	unset($kom, $w, $k);

  }

  $komunikatAdmin .= '
  <p>komunikat ralizuje: '.__METHOD__.' :: line('.__LINE__.')</p>';


  if(C::get('jo'))
  {
	$_SESSION['komunikat'] = '

	<div id=\'komunAdmina\'>
	 <h3>ERROR!</h3>'.$komunikatAdmin.'
	</div>';
  }
  else
  {
   $_SESSION['komunikat'] = '
	<div id=\'problemUser\'>
	 <h3>Ups!</h3>
	 <p>'.$komunikatUser.'</p>'._UPS.'
	</div>';

   self::plikError($komunikatAdmin, './logs/error');
  }

  self::ggoto($adres, $skok);
 }

 /** S::plikError()
 *
 * zapis komunikatów do pliku systemowego
 * używane prze klasę poprzednią
 *
 */

 private static function plikError($t, $name)
 {
  if($name && $t)
  {
   if(!file_exists($name.'.php')) $z = '<? exit; ?>';

	if(is_array($t)) $t = implode("\n", $t);

	$t = preg_replace('/<p>/si', "\n", trim($t));

	$t = strip_tags($t);

	$t = iconv('utf-8', 'windows-1250', $t);

   $t = $z."\n\n".date("Y-m-d H:i:s", time()).'|'.$t;

   $h = fopen($name.'.php', 'a');

   fputs($h, $t);

   fclose($h);

   unset($h, $t, $z);
  }
 }


}
?>