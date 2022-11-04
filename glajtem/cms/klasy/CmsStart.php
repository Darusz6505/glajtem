<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* Głowna klasa CMS'a :: procedury startowe strony
*
* 2013-01-04 : poprawiony włącznik $this->test
* 2012-10-30 : poprawiony link powrotny do strony usera
*
* 1. przyłączenie plików konfiguracyjnych: ogólnego i MySQL
* 2. przepisanie konfiguracji do tablicy konfiguracji
* 3. wywołanie metody start()
* 4. start sesji
* 5. akcje po wystartowaniu sesji, extra dostęp dla testów lokalnych
* 6. restrykcja dostępu dla Administartorów
* 7. wywołanie klasy wtyczek
* 8. złożenie strony
* 9. komunikaty testowe
*
*/


class CmsStart
{
 private $c = array(); 							//-tablica systemowa

 private $test = false; 						//-do testów

 /**
 *
 *
 */

 function __construct()
 {
  Test::trace(__METHOD__);
  Test::start(__CLASS__);

  // to powoduje błąd
  //if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start('ob_gzhandler'); else ob_start(); //-??? kompresja ??? Webhosting

  //define('_CZAS_GS_START', microtime());												//-czas start dla pomiaru czasu generowanai strony

  define('_TEST', '_test');

  require_once _CONPATH.'config_def'._EX;
  require_once _CONPATH.'config_sql'._EX;

  //-jeśli jest tabela konfiguracji dla serwisu to dodanie danych z tej tabeli do konfiguracji

  $this->c = $c;

  $this->c['polahtml'] = array_keys($c['polahtml']);								//-do generowania list w formularzach

  unset($c);

  $this->start();																				//-ustawienia przed otwarciem sesji

  session_start();

  $this->aftersession();																	//-ustawienia trybu pracy, dla admina lub vipa

  if(!$_SESSION['admin']['status'] && !$this->c['ja'] && !$this->c['jo'])	//-tylko dla zalogowanych adminów
   header('HTTP/1.1 404 File Not Found');

  $this->startCms();																			//-dalsze procedury ustawiające i testujące, oraz złożenie strony
 }

 /**
 *
 * akcja przed otwarciem sesji
 *
 */

 private function start()
 {
  Test::trace(__METHOD__);

  $this->c = array_merge($this->c, C::localSet($this->c['set_remote'], $this->c['fotyPath']));

  /*	 wyłaczone 2013-02-15
  if($this->c['ip_test'] == $this->c['ip']) $this->test = true;												//-włącznik dla procedur testowych ustawiany w config_def
  if($this->c['ip_test'] && $this->c['localhost']) $this->test = true;
  */

  if($this->c['ip'] === $this->c['ip_vip']) $this->c['ja'] = 1; else  $this->c['ja'] = false;		//-TO TYLKO PODCZAS TESTÓW!! NIEBEZPIECZNE !!!
  if($this->c['ip'] === $this->c['ip_adm']) $this->c['jo'] = 1; else  $this->c['jo'] = false;		//-TO TYLKO PODCZAS TESTÓW!! NIEBEZPIECZNE !!!


  if(isset($_GET['akcja'])) $this->c['akcja'] = C::odbDane($_GET['akcja']); else $this->c['akcja'] = 'start';

  if(isset($_REQUEST['op'])) $this->c['opcja'] = urlencode(C::odbDane($_REQUEST['op'])); else $this->c['opcja'] = '';

 }

 /**
 *
 * akcje po otwarciu sesji
 *
 */

 private function aftersession()  //-ustawia prace w odpowiednim trybie, vipa lub admina
 {
  Test::trace(__METHOD__);

  if(!isset($_SESSION['token']))
   $_SESSION['token'] = sha1(uniqid(rand(), true));			//-token do formularzy


  if(!isset($_SESSION['admin']['status'])) 	$_SESSION['admin']['status'] = false;

  if(!isset($_SESSION['back'])) 					$_SESSION['back'] = false;

  // if(!isset($_SESSION['back2'])) 				$_SESSION['back2'] = false;  //- 2016-05-29

  if(!isset($_SESSION['id-err'])) 				$_SESSION['id-err'] = false;

  if(!isset($_SESSION['id-cms-err'])) 			$_SESSION['id-cms-err'] = false;	//-przesówa stronę do kontenera, który wywołał akcję lub do komunikatu błędu

  if(!isset($_SESSION['admin_stat_tmp'])) 	$_SESSION['admin_stat_tmp'] = false;

  if(!isset($_SESSION['admin_zalog'])) 		$_SESSION['admin_zalog'] = false;

  if(!isset($_SESSION['us_zalog']['goto']))	$_SESSION['us_zalog']['goto'] = false;


  if(!$_SESSION['admin']['status'])									//-akcja dla testów lokalnych
  {

   if($this->c['localhost'])
   {
    $this->c['ja'] = $this->c['set_ja'];
    $this->c['jo'] = $this->c['set_jo'];
   }

   if(!$this->c['ja'] &&  !$this->c['jo'])
   {
    C::myGoto('stop'._EX); //-przerwanie

    exit('Dostęp tylko dla uprawnionych!');
   }

  }
  else
  {

   if($_SESSION['admin']['status'] == 10) $this->c['ja'] = 1; else $this->c['ja'] = false;
   if($_SESSION['admin']['status'] == 9)  $this->c['jo'] = 1; else $this->c['jo'] = false;

  }

  //UWAGA!! zmienna ja MUSI BYĆ USTAWIONA! inaczej nie będą działać metody statyczne mające w warunku self::$c['jo'] lub self::$c['ja']

  if($this->c['ja']) $this->c['jo'] = $this->c['ja'];


  if(!$this->c['ja'] &&  !$this->c['jo'])
  {
    C::myGoto('stop'._EX); //-przerwanie

    exit('Dostęp tylko dla uprawnionych!');
  }


  if($this->c['jo']) $_SESSION['admin']['status'] = 9;		//-wskaźniki skrócone do tablicy systemowej - konfiguracji
  if($this->c['ja']) $_SESSION['admin']['status'] = 10;
 }

 /**
 *
 *
 *
 */

 private function startCms()
 {
  Test::trace(__METHOD__);


  if($_SESSION['back'])																			//-link powrotny do serwisu
   $l[0] = $_SESSION['back'];
  else
   $l[0] = 'start.html';


  // if($_SESSION['back2'])
  //  $l[0] = $_SESSION['back2'];		// 2016-05-29


  if($_SESSION['backlink'])				//- 2016-05-29
  {
	$l[0] = $_SESSION['backlink'];
  }
  // if(substr(trim($l[0]), -5) != '.html') $l[0] .= '.html';							//-test poprawności adresu zwrotnego do widoku usera


  if(!$_SESSION['id-err']) 																		//-to działa dla starych odnośników
  {
   $_SESSION['id-err'] = isset($_GET['lb'])?C::odbDane($_GET['lb']):'';

	//to prawdopodobnie znacznik powrotu przesyłany metodą GET -> lb
  }


  if(!$_SESSION['admin_stat_tmp']) $_SESSION['admin_stat_tmp'] = $_SESSION['admin']['status'];

  //-przechowanie statusy administratora ale po co?, aktualnie wykorzystywany jest jeszcze przez klasę MojeSQL.php

  //-przyłączenie odpowiednich dla danego Administratora pliku konfiguracji i menu

  if($_SESSION['admin_stat_tmp'] == 10 && $this->c['opcja'] != 'sklep')
  {
   require_once './cms/config/vip_config'._EX;  									//-definicja menu dla VIP'a'
  }


  if($_SESSION['admin_stat_tmp'] == 9 && $this->c['opcja'] != 'sklep') 		//-Główny Admin
  {
   require_once './cms/config/admin_config'._EX;									//-definicja menu dla Admina
  }


  /*
  if($_SESSION['admin']['status'] > 0 && $this->c['opcja'] == 'sklep')
  {
   $add_styl = '
	<link type=\'text/css\' rel=\'stylesheet\' href=\'cms_sklep/styl/cms_sklep.css\' />';		//-styl dla wtyczek obsługi sklepu

   require_once './cms/config/sklep_config'._EX;
  } */


  C::loadConfig($this->c);										//-dodanie do tablicy systemowej - konfiguracji
  unset($this->c);

  $sklep = ''; 													// 2013-02-05

  $r = new Admin($m, $sklep);									//-wywołanie klasy Admin.php -> parametry to: (menu główne, menu dodatkowe)

  $this->nagHtml();												//-nagłowek dla strony CMS'a

  echo $r->wynik();												//-zawartość strony

  Test::trace();

  unset($r, $sklep, $m);

  if($_SESSION['id-cms-err'])									//-przesówa stronę do kontenera, który wywołał akcję lub do komunikatu błędu
  {
   echo '<script>location=\'#'.$_SESSION['id-cms-err'].'\'</script>';

   $_SESSION['id-cms-err'] = false;
  }

  echo JS_CMS::jsEnd();
  echo Test::testShow();
  echo '
  </body>
  </html>';
 }


 /**
 *
 * nagłówek strony dla CMS'a
 *
 */

 private function nagHtml()
 {

  echo '
<?xml version=\'1.0\' encoding=\'ISO-8859-2\'?>
<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Strict//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\'>
<html xmlns=\'http://www.w3.org/1999/xhtml\' lang=\'pl\' xml:lang=\'pl\'>
<head>
 <meta http-equiv=\'content-type\' content=\'text/html; charset=UTF-8\' />
 <title></title>
 <meta http-equiv=\'Reply-to\' content=\'admin(ed)aleproste.pl\' />
 <meta http-equiv=\'Content-Language\' content=\'pl\' />
 <meta name=\'Author\' content=\'\' />
 <meta name=\'Robots\' content=\'none\' />
 <link type=\'text/css\' rel=\'stylesheet\' href=\'./cms/styl/admin.css\' media=\'screen\' />
 <link type=\'text/css\' rel=\'stylesheet\' href=\'cms/styl/cms.css\' media=\'screen\' />
 '.JS_CMS::jsHead().'
 <link rel=\'shortcut icon\' href=\'favicon.ico\' />
</head>
<body '.JS_CMS::jsBody().'>';

 }

}
?>