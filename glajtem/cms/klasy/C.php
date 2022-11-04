<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* v:2.2
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2013-02-13 -> poprawki Notice
* 2013-02-04 -> poprawki kompilatora, ostrzżenia
*
* 2012-12-04 : korektaDoMenu()
* 2012-11-21 : doodano metody walidacji logowania
* 2011-10-13 : poprawka metody get(), która powodowała zapętlanie się dla metod testowych z klasy Test.php
*
* -> 2011-06-02
*
* public static function korektaDoMenu($t)  							// aaa = ą itd.
* public static function korektaDoMenu2($t) 							// ??
* public static function walidEmail($t) 								// walidacja formatu e-mail
* public static function walidPassword($t)							// walidacja pola password
* public static function localSet($remote, $fotyPath)				// ustawienia zmiennych lokalnych: ip, db_local, localhost, fotyPathAbs
* public static function set($sSpaceName = '', $mValue = 0)		// ustawienie pojedyńczej nowej zmiennej w tablicy knfiguracji
* public static function change($sSpaceName, $mValue = null)	// zmiana wskazanej zmiennej w tablicy konfiguracji
* public static function add($sSpaceName = '', $mValue = null) // dodanie do tablicy konfiguracji dodatkowej zmiennej i jej wartości ( może być tablicą )
* public static function loadConfig($config)							// dołączenie do tablicy configuracji - dodatkowej grupy danych konfiguracyjnych
* public static function get($sSpaceName = null, $req = true)	// pobiera element z tablicy konfiguracji
* public static function myGoto($adres)								// skok wewnętrzy do wskazanego adresu
* public static function odbDane($p)									// wstepne filtrowanie odbieranych danych metodą GET i POST
* public static function debug($e, $s, $gdzie = '')				// wyświelanie błędów
* public static function ifTest()										// testuje czy włączony jest adres tesowy w config_def
* public static function error($kom, $op = false)					// wewnetrzna strona błędu krytycznego
* public static function plikError($name = 'logs/log_error_c_php', $t = 'error')  // zapis błedów do pliku
* private static function zmienne($tablica)							// wyświetla tabelę zmiennych globalnych dla serwisu
* public static function showConfig()									// wyświetla tablice konfiguracji serwisu
* public static function komunSys()										// wyświetla zmienne systemowe i globalne dla testów
* public static function oknoAdm()										// wyświetla okno administracyjne na front side
* public static function substrText($t, $a, $b)						// zabezpiecza przed dzieleniem znaków 2 bajowych
* public static function infoBox($typ = '', $t)						// komunikat w CMS'ie
*
@ klasa kontenera konfiguracji, oraz :
* debugowania akcji try
* strony błędów dla administartora
* skoków bezpośrednich do strony, lub skryptu
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-12-27 ------------ UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class C
{
 private static $c = array(); 		//-tablica configuracji serwisu

 public static $glob = array();		//-tablica do testów zmiennych globalnych


 public static function korektaDoMenu($t)
 {

  $to = array
  (
	'/_/', '/aaa/', '/AAA/', '/ccc/', '/CCC/', '/eee/', '/EEE/', '/lll/', '/LLL/', '/nnn/', '/NNN/', '/ooo/', '/OOO/', '/sss/', '/SSS/', '/zzz/', '/ZZZ/', '/xxx/', '/XXX/'
  );

  $na = array(' ', 'ą', 'Ą', 'ć', 'Ć', 'ę', 'Ę', 'ł', 'Ł', 'ń', 'Ń', 'ó', 'Ó', 'ś', 'Ś', 'ż', 'Ż', 'ź', 'Ź');

  $t = preg_replace($to, $na, $t);

  unset($to, $na);

  return $t;
 }

 /**
 *
 *
 *
 */

 public static function korektaDoMenu2($t)
 {
  $t = explode('_', $t);
  array_shift($t);
  array_shift($t);
  $t = implode('_', $t);
  return self::korektaDoMenu($t);
 }

 /**
 *
 * walidacja pola e-mail dla logowania
 * walidacja poprawności adresu e-mail
 * 2012-11-21
 *
 */

 public static function walidEmail($t)
 {

   if(!$t)
	 return 'Brak adresu e-mail!';
   elseif(!preg_match('/^[0-9a-z_.-]+@([0-9a-z-]+(\.)+)+[a-z]{2,4}$/', $t))
	{
	 return 'Podany adres e-mail jest nieprawidłowy!';
	}
	else
	 return false;

 }

 /**
 *
 * walidacja pola password dla logowania
 * 2012-11-21
 *
 */

 public static function walidPassword($t)
 {

    if(!$t)
	  return 'Proszę wpisać hasło.';										//-walidacja poprawności hasła
    else
    {

     if(!preg_match('/^[0-9a-zA-Z_.\-\!@#%&*+=?]+$/', $t))
		$ttp[] = 'Hasło może zawierać tylko litery bez polskich znaków, cyfry, znaki z grupy .!@#%&*+=?_ razem max.20 znaków!';

	  $ttpp = '';

	  if(!preg_match('/[0-9]+/', $t)) $ttpp = '0';
	  if(!preg_match('/[a-z]+/', $t)) $ttpp .= 'a';
	  if(!preg_match('/[A-Z]+/', $t)) $ttpp .= 'A';

	  if(!preg_match('/[_.\-\!@#%&*+=?]+/', $t)) $ttpp .= '#';

	  if($ttpp)
	  {
		$ttp[] = 'Hasło musi zawierać co najmniej jedną małą i dużą literę, cyfrę i znak z grupy  .!@#%&*+=?_';
      unset($ttpp);
	  }

     if(strlen($t)<6) $ttp[] = 'Hasło musi się składać z conajmniej 6 znaków!';

     if(strlen($t)>20) $ttp[] = 'Hasło nie może mieć więcej niż 20 znaków.';


	  if($ttp) return implode('<br>', $ttp);

    }
 }

 /**
 *
 * ustawienia zmiennych lokalnych
 *
 */

 public static function localSet($remote, $fotyPath)
 {
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))											//-prawdziwe ip - bez funkcji ippraw()
  {
   $d['ip'] = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
   //$d['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
   $d['ip'] = $_SERVER['REMOTE_ADDR'];

  $d['ip_server'] = $_SERVER['SERVER_ADDR'];

  if(!preg_match('/^127\./', $d['ip_server']) || $remote)
   $d['db_local'] = false;
  else
   $d['db_local'] = true;


  if(!preg_match('/^127\./', $d['ip_server']))
   $d['localtest'] = false;
  else
   $d['localtest'] = true;


  if(!preg_match('/^127\.0\./', $d['ip_server']))
  {
   $d['localhost'] = false;
	$d['fotyPathAbs'] =  'http://'._HOST.substr($fotyPath, 1);	 //-bezwzgledny adres katalogu z plikami
  }
  else
  {
   $d['localhost'] = true;
	$d['fotyPathAbs'] =  'http://'._HOST.'/'.basename(_DOCROOT).substr($fotyPath, 1);
  }

  Test::trace(__METHOD__, $d);

  return $d;
 }

 /**
 *
 * ustawienie pojedynczej wartości w tablicy konfiguracyjnej
 *
 */

 public static function set($sSpaceName = '', $mValue = 0)
 {
  if(!$sSpaceName) return;

  try
  {

   if(!isset(self::$c[$sSpaceName]))
   {
    self::$c[$sSpaceName] = $mValue;
   }
   else
    throw new ConfigException('Duplicat name '.$sSpaceName.' at $c::set method'.__METHOD__.'->'.__LINE__);

  }
  catch(ConfigException $e)
  {
  	self::debug($e, 2);
  }

 }

 /**
 *
 * zmiana wskazanej wartości
 *
 */

 public static function change($sSpaceName = '', $mValue = null)
 {
  if(!$sSpaceName) return;

  if($mValue != null)
	self::$c[$sSpaceName] = $mValue;
  else
   self::debug('Wartość zmiennej "'.$sSpaceName.'" jest NULL!::'.__METHOD__.' -> '.__LINE__, 2);

 }

 /**
 *
 * dodanie do tablicy dodatkowej wartości v.2012-03-30
 * poprawione 2013-02-13
 *
 */

 public static function add($sSpaceName = '', $mValue = null)
 {
  if(!$sSpaceName) return;

  try
  {

	if($mValue != null)
	{

	  if(!is_array(self::$c[$sSpaceName]))
      self::$c[$sSpaceName] .= $mValue;
	  else
	   self::$c[$sSpaceName][] = $mValue;

	}
	else
	 throw new ConfigException('No value to set on '.$sSpaceName.'  at $c::set method '.__METHOD__.' in line -> '.__LINE__);

  }
  catch(ConfigException $e)
  {
  	self::debug($e, 2);
  }
 }

 /**
 *
 * dołączenie do tablicy configuracji - dodatkowej grupy danych konfiguracyjnych
 * 2013-01-04 : poprawki obsługi błędów
 *
 */

 public static function loadConfig($config)
 {
  try
  {

   if(isset($config) && is_array($config))
    self::$c = array_merge(self::$c, $config);
   else
	 throw new ConfigException('ERROR $config -> '. $config);

  }
  catch(ConfigException $e)
  {
  	self::debug($e, 2);
  }
 }

 /**
 *
 * UWAGA! 	wiesza się jeśli brakuje ['jo']
 * 			jeśli metoda może być wywołana gdy wartość 'jo' nie jest jeszcze ustalona
 *				to należy wywoływać ją z parametrem q=false, dotyczy to głównie metod testowych klasy Test.php
 */

 public static function get($sSpaceName = null, $req = true)
 {

  try
  {
   if($sSpaceName != null)
   {
    if(array_key_exists($sSpaceName, self::$c))
     return self::$c[$sSpaceName];
    else
     if($req && self::$c['jo']) 													//-pokazuje błąd braku nazwy elementu tablicy ale tylko dla admina
      throw new ConfigException('Wrong name ' . $sSpaceName . ' at get method');
	  else
	   return false;																	//-dla req = false -> zwraca false jeśli zmiennej nie ma w tablicy
   }
   else
    throw new ConfigException('Empty name at Con::get method'); 		//-błąd wywyołania metody z pustym parametrem nazwy

  }
  catch(ConfigException $e)
  {
  	self::debug($e, 2);
  }
 }

 /**
 *
 *  skok do adresu
 *
 */

 public static function myGoto($adres)
 {
   Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'trace', $adres);

	$adres = explode('.', $adres, 2);

	if($adres[1])
	{
	 if(in_array($adres[1],  array('php','html', 'smsl', 'cmsl')))		//-dozwolone rozszerzenia
	 {
	  S::myHeaderLocation($adres[0].'.'.$adres[1]);
	  //header('location: '.$adres[0].'.'.$adres[1]);
	 }
	 else
	  S::ErrorAdmin('niedozwolony parametr ['.$adres[1].'] w klasie :'.__CLASS__ .' -> goto()');
	}
	else
	 S::myHeaderLocation($adres[0].'html');
	 //header('location :'.$adres[0].'html');

 }

 /**
 *
 * 2021-01-09 : modyfikacje do wersji PHP 7.xx
 * odbiera dane przesyłane metodą GET i POST - likwidując niebezpieczne znaki
 *
 */

 public static function odbDane($p)
 {
  $p = trim($p); 															// usuwa zbędne spacje z przodu i z tyłu
  //if(get_magic_quotes_gpc())
  $p = stripslashes($p); 			// usuwa ukośniki
  return htmlspecialchars($p, ENT_QUOTES); 						// dezaktywuje znaki HTML
 }

 /**
 *
 * debugowanie obsługa wyjątków
 *
 * 2012-11-07 : poprawki
 */

 public static function debug($e, $s, $gdzie = '')
 {
   //[ ][0-komunikat w boxie wtyczki, 1-komunikat na stronie 'komunikatu', 2-echo i exit (natychmiastowy komunikat z przerwaniem skryptu)]

  if(is_object($e))
  {
   $x = '<p>e is object!</p>';

   if(self::$c['jo'] || self::ifTest() || $s === 'cron')
   {
 	 if(stristr($e->getTraceAsString(), '#')) $y = explode('#', $e->getTraceAsString());

 	 if(is_array($y))
 	 {
	  $x .= '<p>e is array</p>';

	  $trase = '';

  	  foreach($y as $wart)
      $trase .= '
	    '.$wart.'<br />';
 	 }
 	 else
	 {
     $trase = '
	   ??'.$e->getTraceAsString().'<br />';
     $x .= '<p>e is not array</p>';
	 }

 	 $x .= '
		<p class=\'adm_error\'>
		'.$e->getMessage().'<br />
		'.$e->getFile().'<br />
		linia: '.$e->getLine().'<br />'.$trase.'
		Kod: '.$e->getCode().'
		</p>';

 	 unset($trase, $y, $z);
   }

  }
  else
   $x = '<p>e is no object!</p>'.$e;

  switch($s)
  {
   case 1:
     $_SESSION['komunikat'][]= '
		<p class=\'error\'>'._KOM_AWARI.'</p>';

     if(self::$c['jo']) $_SESSION['komunikat'][]= $x;

     unset($x);

     self::myGoto(self::$c('notice'));
	  exit('STOP');

   break;

   case 2:

	  self::error($x);	//-metoda ta ma już konieczne zabezpieczenia dla admina i adresu testowego

   break;

	case 'cron': case 'TEST':

	 return $x;

	break;

	case 'log':
    if(self::$c['jo'])
	  self::error($x);
	 else
	  return strip_tags(preg_replace('/<br \/>/', "\n", $x));

	break;

   default:

	 if(!self::$c['jo'] && !self::ifTest())
	  return '<p class=\'error\'>'._KOM_AWARI.'</p>';
	 else
	  return '<p class=\'error\'>'._KOM_AWARI.'</p>'.$x;

  }
 }

 /**
 *
 * testuje czy włączony jest adres tesowy w config_def
 * public bo w Start.php i CmsStart.php
 *
 */

 public static function ifTest()
 {
  /*
  if(self::$c['ja'])
   if(self::$c['ip_test'] === self::$c['ip'] || ( self::$c['localhost'] && self::$c['ip_test']))
    return true; */

  if(self::$c['localtest'])
  {
	if(self::$c['ip_test'])
	 return true;
  }
  else
   if(self::$c['ip_test'] === self::$c['ip'])
    return true;

  return false;
 }

 /**
 *
 * wewnętrzna strona błędu dla komunkatów gdy brak prawidłowej konfiguracji tabel ( licencja i styl )
 *
 */

 public static function error($kom, $op = false)
 {
  if(self::$c['jo'] || $_SESSION['admin_zalog'] || self::ifTest())
  {
   self::$c['error'] = true;

	$kom = '<p class=\'adm_error_kom adm_error\'>'.$kom.'</p>';

   echo '
	<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Strict//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\'>
	<html xmlns=\'http://www.w3.org/1999/xhtml\' lang=\'pl\' xml:lang=\'pl\'>
	<head>
	<meta http-equiv=\'content-type\' content=\'text/html; charset=UTF-8\' />
	<title>ERROR!</title>
	<meta http-equiv=\'Content-Language\' content=\'pl\' />
	<meta name=\'Author\' content=\'projekt.etvn.pl &amp; aleproste.pl Dariusz Golczewski\' />
	<link type=\'text/css\' rel=\'stylesheet\' href=\'./cms/styl/admin.css\' media=\'screen\' />
	</head>
	<body>

	<div id=\'adm_side_error\'>
	 <h1 class=\'adm_error\'>ERROR:</h1>
	'.$kom.'
	</div>';

	self::oknoAdm();

	//Test::showNow(false, true);																	//-komunikat systemowy o funkcjach i zmiennych globanych
	//self::showConfig();
	//self::komunSys();

	if(C::ifTest())
   {
    echo Test::testShow();
   }

   echo '
	</body>
	</html>';
   exit('<p class=\'adm_end\'>END PAGE ERROR</p>');
  }
  else
   self::myGoto('cms/stop.php');
 }

 /** C::plikError()
 *
 * zapis błędów do pliku
 * metoda zdublowana w klasie S
 *
 */

 public static function plikError($name = 'logs/log_error_c_php', $t = 'error')
 {
  if(!file_exists('./'.$name.'.php')) $z = '<? exit; ?>';

  if(is_array($t))
	$t = implode("\n::", $t);

  $t = $z."\n".date("Y-m-d H:i:s", time()).' | '.$t."\n";

  $h = fopen('./'.$name.'.php', 'a');

  fputs($h, $t);

  fclose($h);

  unset($h, $t, $z);

 }

 /**
 *
 * wyświetla tabelę zmiennych globalnych dla serwisu
 *
 */

 private static function zmienne($tablica)
 {
    $i = 0;

    foreach((array)$tablica as $klucz=>$wartosc)
    {
     if ($klucz=='GLOBALS') continue;

     if (is_array($wartosc) || is_object($wartosc))
	  {
	   $xw = self::zmienne($wartosc);
	   $i += $xw;
	  }
     else
	  {
	   $xw = strlen($wartosc);
	   $i += $xw;
	  }

	  self::$glob[$klucz] = $xw;
    }

   return 0 + $i;
 }

 /**
 *
 * wyświetla tablice konfiguracji serwisu
 *
 */

 public static function showConfig()
 {
  if(!defined('_MY_TEST')) return;
  if(_MY_TEST) return;

  if(!self::$c)
  {
 	return '<p>tablica lub zmienna nie isnieje! w '.__METHOD__.' in line -> '.__LINE__.'</p>';
  }
  else
  {
   ob_start();

	Test::pre(self::$c);

   $variable = ob_get_contents();

   ob_end_clean();
  }

  if($variable)
  {
   return '
	<style type="text/css">
	 #adm_config_show {margin: 1em; padding: 1em; border: 1px solid #30F;}
	</style>
	<div id=\'adm_config_show\'>
	 <p class=\'adm_test_tem\'>### KONFIGURACJA ###</p>
	 '.$variable.'
	</div>';

  }
  else
   return;
 }

 /**
 *
 * wyświetla zmienne systemowe i globalne dla testów
 *
 */

 public static function komunSys()
 {
  if(!defined('_MY_TEST')) return;
  if(_MY_TEST) return;

   ob_start();

   $arr = get_defined_functions();

	Test::pre($arr['user']);

   unset($arr);

   $sum =  ' :: ZMIENNE RAZEM = '.self::zmienne($GLOBALS);

	arsort(self::$glob);

	Test::pre(self::$glob);

	$variable = ob_get_contents();

   ob_end_clean();

   if($variable)
   {
    return '
	  <style type="text/css">
	   #adm_test_tracer {margin: 1em; padding: 1em; border: 1px solid #C33;}
	   .col4 {background: #C33;}
	  </style>
	  <div id=\'adm_test_tracer\'>
	   <p class=\'adm_test_tem col4\'>### SYSTEM INFO ### '.$sum.'</p>
	   '.$variable.'
	  </div>';
    }
    else
     return;
 }

 /**
 *
 * okno admina w widoku usera
 *
 * poprawione 2012-01-07 -> 2013-02-15
 *
 */

 public static function oknoAdm()
 {
  echo '
	<div id=\'ko_adm\'>
	 <p><a class=\'admpan_logout\' href=\'logout.cmsl\' title=\'wylogowanie ze strony administartora\'>LOGOUT</a>';

  if(self::$c['admin_stat'] == 10 || self::$c['ja'])
 	echo '
	 <a href=\'cms.smsl\' title=\'VIP\'>VIP</a>';

  if(self::$c['admin_stat'] == 9 || self::$c['jo'])
   echo '
	 <a href=\''.md5('9'._ZM_KOD).'+cms.smsl\' title=\'Główny Administartor Serwisu\'>ADMIN</a>';
  else
	if(self::$c['admin_stat'] < 10)
	 echo '
	 <a href=\''.md5(self::$c['admin_stat']._ZM_KOD).'+cms.smsl\' title=\'Administartor Serwisu\'>ADMIN</a>';

  echo '</p>';

  if(self::$c['admin_stat'] == 10 || self::$c['ja'])															//-tylko dla VIP'a
  {
 	 echo '
	 <p>------ VIP -------</p>
	 <p>NOWE POLA</p>
	 <p>';

	 if(self::$c['boxy'])
	 {
     $li = count(self::$c['boxy']);

     for($i=0; $li > $i; $i++)
      echo '
	<a href=\''.S::linkCode(array(self::$c['tab_pola'],0,'formu','stat.10.loka.'.self::$c['boxy'][$i].'.str.'.self::$c['akcja']), self::$c['akcja']).'.htmlc\'
		title=\'nowe pole w boxie\'>'.self::$c['boxy'][$i].'</a>';
    }

 	 echo '
	 </p>
	 <p>-----------------</p>

	 <p><a href=\''.S::linkCode(array(self::$c['tab_boxy'],0,'formu','stat.10.stro.'.self::$c['akcja'], self::$c['akcja'])).'.htmlc\' title=\'nowy box\'>NOWY BOX</a></p>';

  unset($i, $li);
  }

  echo '
	</div>';
 }

 /**
 *
 * zabezpiecza przed dzieleniem znaków 2 bajowych
 *
 */

 public static function substrText($t, $a, $b)
 {
  if(ord(substr($t, ($b-1), -1)) > 156)
   return substr($t, $a, ($b-1));
  else
   return substr($t, $a, $b);
 }

 /**
 *
 * komunikat w CMS'ie, dla do mysql
 *
 */

 public static function infoBox($typ = '', $t)
 {
  if(!$t) return;

  return '
  <div >
  	<div class=\'adm_box '.$typ.'\'>'.$t.'
	</div>
  </div>';
 }

} //-END CLASS

class ConfigException extends Exception {}