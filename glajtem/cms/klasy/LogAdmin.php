<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa logowania do stroy administratora : v.1.2
*
* 2021-01-13 : modyfikacje do wersji PHP 7.xx
*
* 2012-12-30 - poprawki linków dla reklam własnych
* 2012-11-19 - poprawki drobne
* 2012-05-22 - nowa reklama pod formularzem logowania
* 2012-02-16 - hasło może się składać z dowlnych znaków
* 2011-10-13 - poprawki
* 2011-05-18
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2010-06-14 ---UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

class LogAdmin extends Common
{
 const KOM_DANE_WYSLANE = '
 <h1>Przeładowanie strony jest zablokowane!</h1>
 <p>Akcję należy wykonać za pomocą odnośnika poniżej.</p>';

 const PASS_LEN_MAX = 20;		//-długość hasła max
 const PASS_LEN_MIN = 6;		//-długość hasła min
 const LOGIN_LEN_MAX = 50;		//-długość słowa login

 private $w = array();			//-tablica walidacji
 private $f = array();			//-tablica fomularzy

 private $x = '';       		//-wynik działanai klasy

 function __construct()
 {

  $this->walidacja();

 }

 /**
 *
 *  walidacja formularza logowania do CMS'a
 * 1. walidacja negatywna -> powrót do formularza
 * 2. walidacja pozytywna -> procedura logowania
 *
 * 2012-11-21 : modyfikacje
 *
 */

 private function walidacja()
 {

  if($_POST['mail'] || $_POST['haslo']) 	  																//-walidacja tylko jeśli są dane do walidacji
  {
   $this->f['mail'] = substr(C::odbDane($_POST['mail']), 0, LogAdmin::LOGIN_LEN_MAX);		//-odbiór i ograniczenie długości


	if($err = C::walidEmail($this->f['mail']))
	 $this->w['error_mail'] = $err;


   $this->f['has'] = substr(C::odbDane($_POST['haslo']), 0, LogAdmin::PASS_LEN_MAX+1);		//-odbiór i ograniczenie długości

	if($err = C::walidPassword($this->f['has']))															//-walidacja hasła
	 $this->w['error_haslo'] = $err;


   if(!$this->f['has'])
	 $this->w['error_haslo'] = 'Proszę wpisać hasło.';													//-walidacja poprawności hasła


   if($this->w) $this->w['ko_aut_error'] = 'Błędy w formularzu!';


   if($this->w)
	 $this->formularz();
   else
   {
	 if(isset($_POST['send']) && $_SESSION['sended'] == $_POST['send']) 						//-zabezpieczenie przed przeładowaniem strony
	 {
     $this->x = LogAdmin::KOM_DANE_WYSLANE;
	  $this->html();
	 }
    else
    {
     $_SESSION['sended'] = $_POST['send'];

     $this->logowanie();
    }
   }
  }
  else
   $this->formularz();
 }

 /**
 *
 *
 *
 */

 private function formularz()
 {

  if($this->w)
  {
   foreach($this->w as $kl => $wart)
	 $this->w[$kl] = '<p class=\'er-wal\'>'.$wart.'</p>';  		//-do komunikatów walidacji dołącza kod HTML

	unset($kl, $wart);
  }

  $this->f['has'] = '';

  $this->x .= '
    <form id=\'qzaloguj\' class=\'form_kl_logon\' action=\'\' method=\'post\'>'.$this->w['ko_aut_error'].'
	  <input type=\'hidden\' name=\'send\' value=\''.md5(time()).'\'>
	  <div >
      <label>e-mail</label>
      <input type=\'text\' name=\'mail\' value=\''.$this->f['mail'].'\' />'.$this->w['error_mail'].'
     </div>
      <div >
      <label>hasło</label>
      <input type=\'password\' name=\'haslo\' value=\''.$this->f['has'].'\' />'.$this->w['error_haslo'].'
     </div>
     <input type=\'submit\' value=\'zaloguj &#8658;\' title=\'zaloguj się\' />
    </form>';

  $this->html();
 }

 /**
 *
 * procedura logowania
 * 1. dołączenie konfiguracji dla serwisu i CMS'a
 * 2. połączenie z bazą danych
 * 3. skasowanie nie aktualnch blokad logowania
 * 4. sprawdzenie czy jest blokada dla danego ip
 *
 */

 private function logowanie()
 {
  require_once _CONPATH.'config_def'._EX;
  require_once _CONPATH.'config_sql'._EX;

  $c = array_merge($c, C::localSet($c['set_remote'], $c['fotyPath']));

  C::loadConfig($c);																							//-załadowanie konfiguracji
  unset($c);

  $mysql = new Db;	  																						//-połączenie z bazą danych

  try
  {

	DB::myQuery("DELETE FROM ".C::get('tab_log_blok')."  WHERE blok_czas<DATE_SUB(NOW(),INTERVAL ".C::get('login_blok')." MINUTE)");
	//skasowanie nie aktualnych blokad logowania

	$ip = ip2long($_SERVER['REMOTE_ADDR']); 															//-pobieram IP jako liczbę

	$tab = "SELECT blok_licznik FROM ".C::get('tab_log_blok')." WHERE blok_ip='$ip'"; 	//-sprawdza czy ip jest notowane

   if($tab = DB::myQuery($tab))
    if($ta = mysqli_fetch_row($tab)) 																	// jeżeli ip jest notowane
    {
     if($ta[0] >= C::get('login_max')) 																// sprawdza limit do blokady
	   $blokada = true; 																						// zakłada blokadę jeśli limit został osiągnięty
    }
    else 																										//-jeżeli ip nie jest notowane
	  $nowy = true;																							//-ustawia znacznik, że to nowe ip


	if(!$blokada)																								//-jeśli nie ma blokady procedura logowania
	{

	 if(!file_exists('log.php')) $t = "<? defined('_CMS') or exit('SORY'); ?>\n"; 		//-założenie nowego pliku chistorii logowania

	 $h=fopen('log.php', 'a');
	 fputs($h, C::get('datetime_teraz').':'.$this->f['mail'].':'.$this->f['has'].':'.$blokada."\n");
    fclose($h);

	 unset($h);

	 //-próba logowania

	 $tab = "SELECT admin_pass FROM ".C::get('tab_admini')." WHERE admin_logi='{$this->f['mail']}'"; 	//-odczytanie rekordu usera

	 if($tab = DB::myQuery($tab))
     if($ta = mysqli_fetch_row($tab)) 																		//jest taki login
	  {

	   $this->f['has'] = $this->passDekodCms($ta[0], $this->f['has']); 							//-hasło do porównaia z tym w bazie

      $tab = "SELECT * FROM ".C::get('tab_admini')." WHERE admin_logi='{$this->f['mail']}' AND admin_pass='{$this->f['has']}'";

		if($tab = DB::myQuery($tab))
		{
       if($ta = mysqli_fetch_assoc($tab))																	//jest taki rekord
		 {

	     $_SESSION['admin_zalog'] = $ta['admin_logi'];												  	//-login
	     $_SESSION['admin']['status']  = $ta['admin_stat'];												  	//-status użytkownika
	     $_SESSION['admin_start'] = time();															  	//-tylko czas startu
	     $_SESSION['admin_idse']  = md5($_SESSION['admin_start']);								  	//-identyfikator logowania ??? do czego to ?

		  //-nowe zmienne sesyjne jako tablica
		  $_SESSION['admin']['login'] = 	$ta['admin_logi'];
		  $_SESSION['admin']['status'] = $ta['admin_stat'];
		  $_SESSION['admin']['start'] = time();
		  $_SESSION['admin']['idse'] = md5($_SESSION['admin']['start']);

        DB::myQuery("UPDATE ".C::get('tab_admini')."
		  						SET admin_nrlg=admin_nrlg+1, admin_dalo=NOW(), admin_ipus='".C::get('ip')."', admin_koak='{$_SESSION['admin_idse']}'
								WHERE admin_logi='{$ta['admin_logi']}'");

		  //-zerowanie licznika blokady logowania

		  if(!$nowy) DB::myQuery("UPDATE ".C::get('tab_log_blok')." SET blok_licznik=0 WHERE blok_ip='$ip'");

		  unset($tab, $ta, $ip);

		  C::myGoto('cms'._EX);														//-skok do skryptu lub strony : bez rozszerzenia automatycznie dodaje .html

	    }
	    else 																										//-nie ma takiego hasła z tym loginem w bazie
	    {
	     $this->w['ko_aut_error'] = 'Podany login lub/i hasło są nieprawidłowe!';

		  $this->addBlok($nowy, $ip);																			//-zakłada blokadę lub zwieksza licznik blokady o 1

	     $this->formularz();
	    }
		}
	  }
	  else 																											//-nie ma takiego loginu w bazie
	  {
	   $this->w['ko_aut_error'] = 'Podany login lub/i hasło są nieprawidłowe!!';

		$this->addBlok($nowy, $ip);																			//-zakłada blokadę lub zwieksza licznik blokady o 1

	   $this->formularz();
	  }

	 }
	 else
	 {
	  $this->x .= '
	  <h1>Logowanie aktualnie zablokowane!</h1>
	  <p><img src="./cms/skin/close.png" alt=\'zablokowane\'></p>
	  <p>Przekroczony limit nieporawnych logowań!</p>
	  <p>Ponowne logowanie dla tego Administratora możliwe za '.(C::get('login_blok')+1).' minut</p>';
	  $this->html();
	 }

  }
  catch(Exception $e)
  {

   $this->x .=  C::debug($e, $this->debugOpcja);

	if($this->debugOpcja === 'TEST')
	 $this->html();
	else
	 C::myGoto('stop.php');

  }

 }

 /*
 @ zwiększenie licznika blokad
 */

 private function addBlok($nowy, $ip)
 {
  if($this->debugOpcja) Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__);

   if(!$nowy)
    DB::myQuery("UPDATE ".C::get('tab_log_blok')." SET blok_licznik = blok_licznik+1 WHERE blok_ip='$ip'");
   else
    DB::myQuery("INSERT INTO ".C::get('tab_log_blok')." SET blok_czas=NOW(), blok_ip='$ip'");

 }

 /*
 @ kod HTML i ciało strony logowania
 */

 private function html()
 {

  echo '<?xml version=\'1.0\' encoding=\'ISO-8859-2\'?>
<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Strict//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\'>
<html xmlns=\'http://www.w3.org/1999/xhtml\' lang=\'pl\' xml:lang=\'pl\'>
<head>
 <meta http-equiv=\'content-type\' content=\'text/html; charset=UTF-8\' />
 <title>'.C::get('cms_tytul', false).'</title>
 <meta http-equiv=\'Reply-to\' content=\''.C::get('cms_reply_to', false).'\' />
 <meta http-equiv=\'Content-Language\' content=\'pl\' />
 <meta name=\'Author\' content="'.C::get('cms_outhor_content', false).'" />
 <meta name=\'Robots\' content=\'none\' />
 <script type="text/javascript" src="./js/jq.js"></script>
 <script type="text/javascript">var $j = jQuery.noConflict(); </script>
 <script type="text/javascript" src="./js/walid.login.js"></script>
 <link type=\'text/css\' rel=\'stylesheet\' href=\'cms/styl/login.css\' />
 <link rel=\'shortcut icon\' href=\'favicon.ico\' />
</head>
<body>'.$this->x.'
 <a class=\'aaut\' href=\'_Admin\'>zaloguj się do CMS</a> |
 <a class=\'aaut\' href=\'start.html\'>przejdź do strony startowej serwisu</a>
 <div id=\'cms_login_stopka\'>
  <p><a href="http://aleproste.pl" title=\'Przejdź na stronę autora projektu\'>CMS made in aleproste.pl</a></p>
 </div>';

 //-reklama ze strony aleproste z kontolą pobrań

 $afilo = @file_get_contents('http://aleproste.pl/afilo.php?afilo='.$_SERVER['HTTP_HOST'].'_750x100');

 if(!$afilo)
  echo @file_get_contents('http://aleproste.pl/afilo.php?afilo=cms.aleproste.pl_750x100');
 else
  echo $afilo;

 unset($afilo);

  /*
  if($this->debugOpcja === 'TEST' && $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
  {
   Test::showNow(false, true);																//-komunikat systemowy o funkcjach i zmiennych globanych
   Test::traceShow();
   C::showConfig();
  } */

  echo '
</body>
</html>';

  exit('THE END');

 }

 /**
 *
 * destruktor
 *
 */

 function __destruct()
 {

 }

}
?>