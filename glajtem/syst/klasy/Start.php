<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* Głowna klasa projektu  v:3.42
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2016-07-04 : aktualizacja stopki
* 2016-05-24 : nowy mechanizm skoku do wskazanej tresci
* 2016-05-10 : strona systemowa przypinanai zdjęć z istniejących już publikacji
* 2014-03-24
* 2013-10-14
*
* private function sessionSet()					//-inicjowanie zmiennych zapamiętywanych w sesji
* private function beforesession()				//-akcje przed zainicjowaniem sesji
* private function toStart()						//-gówna klasa
* $this->aftersession()								//-akcje na zmiennych po zainicjowaniu sesji
* $this->confFromDb()								//-odczytanei konfiguracji z bazy
* private function nagHTML()						//-przygotowanie nagłówka na stronę
* protected function nowyBox()					//-petla po box'ach serwisu
* private function wtyczka(& $l)					//-załadowanie wtyczki jako klasy lub inkludowanego pliku
* private function klasaPolaVip($klasa, $b, $t, $p)	//-oznaczenie pola do publikacji i/lub zablokowanego
* protected function tablesList()				//-lista tabel dla aktualnej bazy danych i prefixu serwisu
*
*/

class Start
{

 private $polahtml = array(); 	//-tablica kontenerów

 private $m  = array(); 			//-tablica kontenerów
 private $m2 = array(); 			//-tablica kontenerów

 private $mmysql = ''; 				//-uchwyt do połaczenia MySQL

 private $jo = false; 				//-znacznik admina
 private $ja = false; 				//-znacznik vip'a

 private $ak = '';					//-akcja = podstrona
 private $op = '';					//-opcja = parametry podstrony
 private $back = '';					//-link powrotny dla odnośników administarcyjnych

 /**
 *
 *
 */

 public function __construct()
 {
  Test::start('czas_razem');
  Test::trace(__METHOD__);

  Test::trace(__METHOD__ .' VIP', $this->jo);

  $this->beforesession();

  //assert(false);

  $this->toStart();
 }

 /**
 *
 *
 *
 */

 private function sessionSet()
 {
  $_SESSION['session_id'] = session_id();

  if(!isset($_SESSION['admin']['stat'])) $_SESSION['admin']['stat'] = false;

  if(!isset($_SESSION['admin']['status'])) $_SESSION['admin']['status'] = false;

  if(!isset($_SESSION['test_err'])) $_SESSION['test_err'] = false;

  if(!isset($_SESSION['test_trace'])) $_SESSION['test_trace'] = false;

  if(!isset($_SESSION['us_zalog'])) $_SESSION['us_zalog'] = false;

  //if(!isset($_SESSION['id-err']) $_SESSION['id-err'] = false;  // dlaczego to wywala cały skrypt

  //if(!isset($_SESSION['lang']) $_SESSION['lang'] = C::get('lang'); // dlaczego to też wywala cały skrypt

 }

 /*
 @ - dołączenie konfiguracji serwisu
 * - dołączenie konfiguracji MySql
 * - adres ip urzytkownika
 * - wykrycie localhosta na podstawie adresu ip = 127...
 * - domyślne przyłączenie bazy danych ( lokalna = robocza, testowa i internetowa = docelowa)
 * - odbiór danych przesłanych w adresie metodą GET
 * - dodanie ustalonych parametrów do konfiguracji
 */

 private function beforesession()
 {

  define('_TEST', '_test'); //-uzywane w Afilo.php ?

  /*	 TO TRZEBA ZROBIĆ INACZEJ
  if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
  {
	ob_start('ob_gzhandler');
  }
  else
	ob_start(); //-??? kompresja ??? Webhosting */

  require_once _CONPATH.'config_def'._EX;
  require_once _CONPATH.'config_sql'._EX;

  $c['ja'] = false;
  $c['jo'] = false;

  $c['javascript'] = '';
  $c['javascript_top'] = '';
  $c['javascript_down'] = '';
  $c['javascript_end'] = '';			// add 2013-03-02 - dla działań asynchronicznych

  //-zainicjowanie zmiennych doodających dynamicznie js

  if(!isset($c['start_side'])) $c['start_side'] = false;

  $this->polahtml = $c['polahtml'];

  if(!$c['adres_strony'])
   $c['www'] = $_SERVER['HTTP_HOST'];												//-automatyczny adres jeśli nie ma sztywnego
  else
   $c['www'] = $c['adres_strony'];													//-automatyczny adres jeśli nie ma sztywnego

  $c = array_merge($c, C::localSet($c['set_remote'], $c['fotyPath']));  // zwraca : ['ip'], [db_local], ['localhost'], ['fotyPathAbs']

  if($c['ja']) $c['jo'] = true;

  if(isset($_GET['akcja']))															//-ustalenie strony startowej serwisu
  {
   $c['akcja'] = C::odbDane($_GET['akcja']);

	if($c['akcja'] === 'start' && $c['start_side'])
	 $c['akcja'] = $c['start_side'];
  }
  else
  {
   if($c['start_side'])
	 $c['akcja'] = $c['start_side'];
	else
	 $c['akcja'] = 'start';
  }

  if(isset($_REQUEST['op'])) $c['opcja'] = urlencode(C::odbDane($_REQUEST['op'])); else $c['opcja'] = '';

  $this->ak = $c['akcja'];
  $this->op = $c['opcja'];

  $this->back = $this->ak;
  if($this->op) $this->back = $this->op.'+'.$this->back;

  Test::trace(__METHOD__ .' c ', $c['ja']);

  C::loadConfig($c);																		//-załadowanie konfiguracji

  //Test::trace(false, '$c', $c);

  unset($c);
 }

 /**
 *
 * dodanie ustawien konfiguracyjnych na podstawie sesji
 *
 *
 */

 private function aftersession()
 {
  $c['ja'] = 0;
  $c['jo'] = 0;

  if(!$_SESSION['admin']['status'])
  {

   if(C::get('localhost'))
   {
    $c['ja'] = C::get('set_ja');
    $c['jo'] = C::get('set_jo');
   }
	else
	{
	 if(C::get('ip') === C::get('ip_vip')) $c['ja'] = true;	//-TO TYLKO PODCZAS TESTÓW!! NIEBEZPIECZNE !!!
    if(C::get('ip') === C::get('ip_adm')) $c['jo'] = true;	//-TO TYLKO PODCZAS TESTÓW!! NIEBEZPIECZNE !!!
	}

   if(C::get('localtest'))			//-do lokalnych testów, gdy adresy 127.1.x.x symuluja adresy zewnętrzne
   {
    $c['ja'] = C::get('set_ja');
    $c['jo'] = C::get('set_jo');
   }

  }
  else
  {
   if($_SESSION['admin']['status'] == 10) $c['ja'] = 1;
   if($_SESSION['admin']['status'] == 9)  $c['jo'] = 1;
  }

  if($c['jo']) $_SESSION['admin']['status'] = 9;				//-wskaźniki skrócone do tablicy systemowej - konfiguracji

  if($c['ja'])																//-kolejność istotna !!
  {
  	$c['jo'] = 1;
   $_SESSION['admin']['status'] = 10;
  }

  if(isset($_SESSION['admin']['status']))
	$c['admin_stat'] = $_SESSION['admin']['status'];
  else
   $c['admin_stat'] = false;


  $this->ja = $c['ja'];
  $this->jo = $c['jo'];

  if($this->ja) $this->jo =  true;

  if($c)
  {
  	C::loadConfig($c);												//-dodanie do tablicy systemowej - konfiguracji
   unset($c);
  }

	Test::trace('admin status', $_SESSION['admin']['status']);
 }

 /**
 *
 * - start sesji
 * - kasowanie linków powrotnych
 * - zabezpieczenei sesji przed przejęciem
 * - token usera :: ??
 * - aftersession() ->
 * - confFromDb() -> dołączenie konfiguracji z tabel MySQL
 * - wylogowanie po przekroczeniu ustalonego czasu
 * - zapisanie do sesji adresów powrotnych
 * - wywołanie klasy generujących boxy
 * - przłączenie wybranego szkieletu strony + dodanie stopki
 * - ustalenie powrotnego linku ????
 * - złożenie strony ::
 * 	-> dodanie nagłówka
 *		-> zerwanie połączenia MySQL
 *		-> dodanie strony
 *		-> dodanie dolnej reklamy
 *		-> ddanie* pola administratora !! -> przenieść metodę do Start !!!
 *		-> komunikaty systemowe
 *		-> przewinięcie strony* do wskazanej treści
 *		-> końcowe znaczniki html
 *
 */

 private function toStart()
 {
  //S::langSet($this->ak);  // 2013-02-14

  require_once _CONPATH.'wersja'._EX;												//-wersja językowa komunikatów ogólnych

  if($this->ak === 'logout')
  {
	S::userLogOut();
  }
  else
  {
   session_start();

	$this->sessionSet();
  }

  unset($_SESSION['back']);		// back2 używane w Admin.pbp i CmsStart.php

  //unset($_SESSION['back'], $_SESSION['back2']); //- 2016-05-29

  if(isset($_SESSION['user_token']))					//-zabezpieczenie przejęcia sesji
  {
   if($_SESSION['user_token'] != $_SERVER['HTTP_USER_AGENT'])
   {
	 session_unset();
    session_destroy();

	 session_start(); 										//-nowa sesja
    $this->sessionSet();

	 S::ErrorAdmin('Próba przejęcia sesji została zarejestrowana w '.__METHOD__.' -> line: '.__LINE__);
   }
  }
  else
   $_SESSION['user_token'] = $_SERVER['HTTP_USER_AGENT'];

  /* 2012-12-07
  if (!isset($_SESSION['token']))
   $_SESSION['token'] = sha1(uniqid(rand(), true)); */

  $this->aftersession();															//-dodanie ustawien konfiguracyjnych do testów lokalnych

  $this->mmysql = new Db;															//-połaczenie z bazą MySQL

  $tmp_tab = $this->tablesList();												//-test czy wszystkie tabele zostały zalożone

  if(count(array_diff(C::get('wykaz_tabel'), $tmp_tab)) > 0 )
   C::MyGoto('mysql.cmsl');														//-jeśli nie to skok do zarządzania bazą danych
  else
   unset($tmp_tab);


  $this->confFromDb(); 																//-odczyt i dodanie ustawień konfiguracyjnych zapisancyh w bazie

  if(!isset($c['con_awar']))														//-tu kiedys był licznik odwiedzin
  {
    //list($c['con_awar'], $c['error']) = $this->liczOdw();
	 //$this->enterCont();  //-odczyt licznika wejść do serwisu
  }
  else
   if(!$this->jo)
	 C::MyGoto('stop'._EX);


  if(C::get('jo') || $_SESSION['us_zalog']) require_once _CMSPATH.'config/wersja_cms'._EX;		//-wersje językowe komunikatów dla Admina

  if(isset($_SESSION['czas'])) 													//-automatyczne wylogowanie przy bezczynności  && !$c['ja']
  {
   if($_SESSION['czas'] + (60 * C::get('logout_time')) < time())
   {
    unset($_SESSION['czas']);

	 S::userLogOut('timeout');
   }
   else
    $_SESSION['czas'] = time();
  }

  //z tego całego kodu wykorzytywane jest $_SESSION['linkMem'] w S.php :: langSet($podstr)

  if(isset($_SESSION['linkSideBack']))												// $_SESSION['linkSideBack'] tylko TU !! 4 x
  {
   if($_SESSION['linkSideBack'] != $this->ak.'.html') 						//-wskaźnik akcji w ramach tej samej strony
    $_SESSION['linkSideBack'] = false; 											//- kasowanie powrotu przy zmianie strony

   if(!$_SESSION['linkSideBack'])
   {
    if($this->op)
	  $op_temp = $this->op . '+';														//- utrzymanie aktywnego linku powrotnego po przeładowaniu strony
	 else
	  $op_temp = '';

	 if(isset($_SESSION['linkBack']))
     if($_SESSION['linkBack'] != $op_temp . $this->ak.'.html') 				//-jeśli atualna strona jest inna od poprzedniej
      $_SESSION['linkMem'] = $_SESSION['linkBack'];								//-to nowy do pamieci wpisywana jest poprzednia strona

    unset($op_temp);
   }
  }
  else
   $_SESSION['linkSideBack'] = false;


  if($_SESSION['admin']['status']) 														//-jeśli administrator to link powrotny dla CMS'a
  {
   if(C::get('opcja'))
    $_SESSION['back'] = C::get('opcja').'+'.$this->ak.'.html';
   else
    $_SESSION['back'] = $this->ak.'.html';
  }


  $whtml = $this->nowyBox(); 															//$this->polahtml, $this->ak


  if(!$whtml) $whtml = 'html';	  													//-wartość domyślna szkieletu HTML --- ($whtml=='')

  if(file_exists(_APATH.'szablony/'.$whtml._EX))								//-jeśli istnieje szablon, to dołączenie szablonu i stopki
  {
	//-zawartość stopki

	$stopka  = '&copy; '.html_entity_decode(trim(C::get('con_stop')));

   if(!C::get('stopka_obca', false))
	 $stopka .= ' '._STOPKA.' <a class=\'mend\' href=\'http://'._AUTORLINK.'\' title=\''._AUTOR.'\'>'._AUTORNAME.'</a>';




   if($this->ja) $stopka .= ' :: PHP v.'.PHP_VERSION.'
 <div class=\'ed edR edTr\'>
  <a href=\''.S::linkCode(array(C::get('tab_config'), C::get('con_id'),'edycja','',$this->back)).'.htmlc\' title=\'\'>edytuj stopke</a>
 </div>
 <span id=\'wc3\'>
  <a href=\'http://validator.w3.org/check?uri=referer\'><img src=\'cms/skin/html.gif\' alt=\'Valid XHTML 1.0 Strict\' /></a>
  <a href=\'http://jigsaw.w3.org/css-validator/check?uri=referer\'><img src=\'cms/skin/css.gif\' alt=\'Valid CSS!\' /></a>
 </span>';

   Test::trace('link edytuj stopkę', array('stopka', C::get('tab_config'), C::get('con_id'),'edycja',0,1));

	require_once 'hidden/config_def_menu'._EX;			//-dodanei menu dla serwisu

	require_once _APATH.'szablony/'.$whtml._EX;			//-dodanie szablonu
  }
  else
   S::ErrorAdmin('szkielet HTML strony ['.$whtml.'] nie istnieje w '.__METHOD__.' -> line: '.__LINE__);

  //ErrorAdmin($komunikatAdmin, $komunikatUser = '', $adres = _KOMUNIKAT_SITE, $skok = false)


  if(C::get('opcja')) $c_tmp = C::get('opcja').'+'; else $c_tmp = '';

  //$_SESSION['linkBack'] = $c_tmp .$this->ak.'.html';	 // wyłączone 2013-02-14						//-linki powrotne - stndard

  unset($whtml, $c_tmp, $c);

  $this->nagHTML();

  unset($this->mmysql);		//-tu i tak aby zewnętrzne reklamy nie wstrzymywały generowania strony i przedłużały połączenie MySQL

  echo $strona;

  unset($strona, $m);

  echo '
	<div id=\'reklamad\'></div>';

  /*
  if(isset($_SESSION['id-err']) && $_SESSION['id-err'])									//-przesuwa stronę do kontenera, który wywołał akcję
  {																										//-lub do komunikatu błędu
	echo '<script>location=\'#'.$_SESSION['id-err'].'\'</script>';						//-UWAGA!! w tym samym cyklu !!
   unset($_SESSION['id-err']);
  } */

  echo JS::jsEnd();

  if($this->jo) C::oknoAdm();

  echo Test::testShow();

  echo '
</body>
'.JS::jsEndOut().'
</html>';

 //echo JS::jsEndOut();	 //-add 2013-03-02 do akcji asynchronicznych

  if(isset($_SESSION['id-err']) && $_SESSION['id-err'])								//-przesuwa stronę do kontenera, który wywołał akcję
  {																									//-lub do komunikatu błędu
   echo '<script>location=\'#'.$_SESSION['id-err'].'\'</script>';					//-UWAGA!! w tym samym cyklu !!
   unset($_SESSION['id-err']);
  }
  else																								//-2016-05-24 -> nowy mechanizm skoku
   if($skok = C::get('skok'))
   {
    echo '<script>location=\'#'.$skok.'\'</script>';
	 unset($skok);
   }

 }

 /**
 *
 * - dołączenie do tablicy konfiguracji danych z tabeli MySQL
 *
 */

 private function confFromDb()
 {
  Test::start(__METHOD__);

  $c = array();

  try
  {
   $tab = 'SELECT * FROM '.C::get('tab_vip').' ORDER BY vi_id LIMIT 1';

   if($tab = Db::myQuery($tab))
   {

    if(is_array($tab = mysqli_fetch_assoc($tab)))
     $c = array_merge($c, $tab); 																//-dodanie do tablicy c wyników kwerendy

	 C::set('vip_id', $tab['vi_id']);
   }

	$tab = 'SELECT * FROM '.C::get('tab_config').' WHERE con_stat > 0 LIMIT 1';

   if($tab = Db::myQuery($tab))
   {
    if(is_array($tab = mysqli_fetch_assoc($tab)))
     $c = array_merge($c, array_pad($tab, count($tab), false));						//-doodanie do tablicy c wyników kwerendy
   }

	//-specjalnie dla menspower.ch - aby telefony w szablonie były aktualne z tymi w tabeli bazy danych

	$tab = 'SELECT own_imie,own_nzwi,own_kodp,own_mias,own_ulic,own_tel1,own_tel2,own_ma1 FROM '.C::get('tab_owner').' ORDER BY own_id DESC LIMIT 1';

   if($tab = Db::myQuery($tab))
   {
    if(is_array($tab = mysqli_fetch_assoc($tab)))
    $c = array_merge($c, $tab); 																	//-doodanie do tablicy c wyników kwerendy
   }

  }
  catch(Exception $e)
  {
   C::debug($e, 2);
  }


  if(C::get('start_side', false))
   $_SESSION['back'] = C::get('start_side');
  else
   $_SESSION['back'] = 'start.html';  															//-na wypadek wystąpienia błędów



  if(!isset($c['con_styl'])) $c['con_styl'] = '';

  // Tu niemożna AdminError bo nie ma stylu dla tej strony !!!

  if(!$c['con_styl']) 											//-test tablicy configuracji
  {

	C::error(
	'Styl dla serwisu nie jest ustawiony!<br />
	<a class=\'ad_config\' href=\''.S::linkCode(array(C::get('tab_config'),0,'formu','con_stat.1',$this->back)).'.htmlc\'
		title=\'ustaw konfigurację dla serwisu\'>ustaw konfigurację dla serwisu</a>'
	);

  }

  if(!$c['vi_tyt'])																					//-test tablicy vipa
  {
   if($_SESSION['admin']['status'] > 9)
	{

	 C::error(
	 'Brak ustawień licencyjnych!<br />
	 <a class=\'ad_config\' href=\''.S::linkCode(array(C::get('tab_vip'),0,'formu','vi_tyt.vip', $this->back)).'.htmlc\'
	 	title=\'ustaw licencję dla serwisu\'>ustaw licencję</a>'
	 );

	}
 	else
    C::error('Brak ustawień licencyjnych!', 1);
  }

  C::loadConfig($c);		//-załadowanie konfiguracji
  unset($c);

  Test::stop(__METHOD__);
 }

 /**
 *
 * nagłowek HTML
 * 2012-10-22 : dodano styl user.css dla zalogowanych userów
 *
 * to można by przenieść do C.php !!
 *
 */

 private function nagHTML()
 {
  Test::start(__METHOD__);

  $styl = $fb = '';

  $sCss = C::get('adcss', false);

  $fb = C::get('fb', false);

  if($fb) $fb = '
  xmlns:fb="http://www.facebook.com/2008/fbml"
  itemscope itemtype=\'http://schema.org/LocalBusiness\'';

  if($_SESSION['admin']['status'] || C::get('jo') || C::get('ja')) $styl = '
 <link type=\'text/css\' rel=\'stylesheet\' href=\'./cms/styl/admin.css\' media=\'screen\' />';

  if(isset($_SESSION['kl_zalog'])) $styl .= '
 <link type=\'text/css\' rel=\'stylesheet\' href=\'./application/user.css\' media=\'screen\' />';


 /*
  $styl .= '
  	<link rel="stylesheet" href=\'./application/'.C::get('con_styl').'.css\' media="screen and (min-device-width: 799px)" type="text/css" />
	<link type="text/css" rel="stylesheet" media="only screen and (max-device-width: 800px)" href=\'./application/h_'.C::get('con_styl').'.css\' />
	<!-- nowsze androidy -->
	<link rel="stylesheet" media="screen and (-webkit-device-pixel-ratio:0.75)" href=\'./application/h_'.C::get('con_styl').'.css\' />
	<link rel="stylesheet" href=\'./application/h_'.C::get('con_styl').'.css\' media="handheld" type="text/css" />
   <meta name="viewport" content="width=480; user-scalable=0;" />'; */


//  <link type=\'text/css\' rel=\'stylesheet\' href=\'./application/'.C::get('con_styl').'.css\' media=\'screen\' />
//  <link type=\'text/css\' rel=\'stylesheet\' href=\'./application/h_'.C::get('con_styl').'.css\' media=\'max-device-width: 440px\' /> ';

 //<link type=\'text/css\' rel=\'stylesheet\' href=\'./application/h_'.C::get('con_styl').'.css\' media=\'handheld\' />';

  /*
  if(!C::get('jo')) 							//-zamienić na kontrolę po statusie ????
  {
 	if($_COOKIE["ciacho"]=="test") 		//-jeśli działa JavaScript i cookie's - to dodatkowe style eliminujace negatywne elementy
 	{
  	 $styl .= '
 <!--[if !IE]>
 <link type=\'text/css\' rel=\'stylesheet\' href=\'./style/'.C::get('con_styl').'_js.css\' media=\'screen\' />
 <![endif]-->';
 	}
 	else
 	{
    $styl .= '
 <link type=\'text/css\' rel=\'stylesheet\' href=\'./style/'.C::get('con_styl').'no_js.css\' media=\'screen\' />';
 	}
  }

  $styl .= '
 <!--[if IE]>
 <link type=\'text/css\' rel=\'stylesheet\' href=\'./style/'.C::get('con_styl').'_ie.css\' media=\'screen\' />
 <![endif]-->'; */

  $tmp_lang = C::get('lang');

  echo '<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Strict//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\'>
<html xmlns=\'http://www.w3.org/1999/xhtml\'
	xml:lang=\''.$tmp_lang.'\''.$fb.'
	lang=\''.$tmp_lang.'\'>
<head>
<meta http-equiv=\'content-type\' content=\'text/html; charset=UTF-8\' />
<title>'.C::get('seo', false).C::get('con_nazw', false).' </title>
<meta name=\'Keywords\' content=\''.C::get('con_keyw', false).'\' />
<meta name =\'description\' content=\''.C::get('con_desk', false).'\' />

<meta http-equiv=\'Reply-to\' content=\''.preg_replace('/@/', '(at)', C::get('con_mail_admin', false)).'\' />
<meta http-equiv=\'Content-Language\' content=\''.$tmp_lang.'\' />
<meta name=\'Author\' content=\''._AUTOR2.'\' />
<meta name=\'Robots\' content=\'INDEX, FOLLOW\' />

'.$styl.$sCss.JS::jsHead().'
<link rel=\'icon\' type=\'image/png\' href=\'skin/icon.png\'>

</head>
<body'.JS::jsBody().'>';

  unset($styl, $tmp_lang, $sCss);

  Test::stop(__METHOD__);
 }

 /**
 *
 * pętle bo kontenerach, boxach i polach
 *
 * &$polahtml
 *
 */

 protected function nowyBox()
 {
  //$this->polahtml, $this->ak
  //-strony wtyczek systemowych (automatyczne)
  // _KOMUNIKAT okresla stronę w zależności od wersji językowej

  Test::start(__METHOD__);

  if(!defined('_KOMUNIKAT_SITE'))
	C::error('Brak definicji nazwy strony dla komunikatów in '.__METHOD__.' on line -> '.__LINE__);
  else
   if($this->ak === _KOMUNIKAT_SITE)		//-wartość "komunikat" - musi być ustawiona w config_def.php, po definicji box'ów
   {

    if(file_exists('./application/szablony/html_serwis.php'))
	  $whtml = 'html_serwis';
    else
	  C::error('Brak szablonu : html_serwis');

   if(isset($_SESSION['komunikat']))
    if($_SESSION['komunikat'] != '')
    {
     if(C::get('komunikat')) //-określa gdzie w szkielecie wyświetlany jest komunikat
	   $this->polahtml[C::get('komunikat')] = '
	<div id=\'komunikat\'>'.$_SESSION['komunikat'].'
	</div>';

	  unset($_SESSION['komunikat']);
    }
    else
    {
     if(C::get('komunikat'))
      $this->polahtml[C::get('komunikat')] = '
	<div id=\'komunikat\'>'._KOMUNIKAT_OUT.'
	</div>';

	 }
   }


  if($this->ak == 'add_photo' && C::get('add_photo'))
  {
   $r = new W_AddPhoto;

	$r->addPhoto();

   $this->polahtml[C::get('add_photo')] = $r->wynik();

	$whtml = 'html_addfoto';		//-podmienia szablon strony z domyślnego na wskazany
  }

  // 2016-05-10 : przypinanie zdjęć do nowych publikacji z publikacji już istniejących

  if($this->ak == 'add_photo_gal')
  {
   $r = new W_AddPhotoGal;

	$r->addPhotoGal();

   $this->polahtml[C::get('add_photo')] = $r->wynik();

	$whtml = 'html_addfoto';		//-podmienia szablon strony z domyślnego na wskazany
  }

  /**
  if($this->ak == 'allegro' && C::get('jo'))
  {
	$r = new Allegro;

	$this->polahtml[C::get('box_allegro')] = $r->wynik();

	$whtml = 'html_allegro';		//-podmienia szablon strony z domyślnego na wskazany


	if($menu = $r->menu()) $this->m['allegro'] = $menu;	//-menu dla akcji Allegro

	unset($menu);

  } */

  // 2013-06-12 :: strona zarządzania reklamami

  if($this->ak == 'reklama_org')
   if($this->jo)
   {

    $r = new Rotgryf; //??????????

    $this->polahtml[C::get('reklama_org')] = $r->reklamaOrg();

	 $whtml = 'html_serwis';		//-podmienia szablon strony z domyślnego na wskazany

   }
	else
	 S::ErrorAdmin('Strona dostępna wyłącznie dla administratora serwisu!');


  $tpl = 1;

  foreach($this->polahtml as $klhtml => $wart)										//-pętla po zdefiniowanych w config_def polach szkieletu
  {
	Test::start('polahtml='.$tpl);

   $kol = '';
	if(!isset($whtml)) $whtml = '';

   if(!$this->jo)
	 $war = 'AND blok=0 AND dapu < \''.C::get('datetime_teraz').'\'';			//-warunek blokady i czasu :: wspólny
   else
	 $war = '';

	try
	{
	 //-tylko te boxy, które należą do kontenera szkieletu i podstrony, warunek podstrony niżej w if

	 $lok = 'SELECT * FROM '.C::get('tab_boxy').'
	 			WHERE loka=\''.$klhtml.'\' AND ( stro = \''.$this->ak.'\' OR stro = \'all\')'.$war.'
				ORDER BY stat DESC';

    $tpll = 1;
	 if($lok = Db::myQuery($lok))
     while($l = mysqli_fetch_assoc($lok)) 																//-pętla po kontenerach, jeśli są
     {
	   Test::start('kontener='.$tpll);

	   //if($l['stro'] === 'all' || $l['stro'] === $this->ak)										//-warunek podstrony, w ten sposób ze względu na 'all'
   	//{
		 $za_kon = '';
		 $tytkon = '';

		 if($whtml == '' && $l['stro'] != 'all') $whtml = $l['kate'];							//-ustawienie szablonu

		 if($this->ja)																							//-edycja lokacji tylko dla VIP'a
	 	 {

	  	  $li_ako = '
		<div class=\'ed edBox edL\'>
		 <a href=\''.S::linkCode(array(C::get('tab_boxy'), $l['id'],'edycja','',$this->back)).'.htmlc\'
		 title=\'edytuj box:['.$l['nazw'].']
		 class={'.$l['klko'].'}
		 id={'.$l['idko'].'}
		 wt={'.$l['wtyk'].'}\'>edytuj Box-x</a>
		</div>';


	     $ldtre[] = $l['nazw'];																			//-do mechanizmu dodawania pól do kontenerów
	    }
		 else
		  $li_ako = '';



		 $wtykkon = $this->wtyczka($l);																	//-wtyczki dla boxów

	    //- koniec akcji dla kontenera -------------------------------------------------------------------------------

       /* 2012-12-07
		 if(C::get('rob') < 5)																				//-licznik odsłon pola
		 {
	  	  $pola = "UPDATE ".C::get('tab_pola')." SET kli0=kli0+1 WHERE loka='{$l['nazw']}'";

		  DB::myQuery($pola);
		 } */


	 	 $pol = 'SELECT * FROM '.C::get('tab_pola').'
		 			WHERE loka=\''.$l['nazw'].'\' '.$war.'
					ORDER BY stat DESC';   																	//-tylko te pola, które należą do box'u i podstrony,

																													//-warunek podstrony niżej w if


		 $za_pol = '';

       $tplll = 1;
		 if($pol = Db::myQuery($pol))
	  	  while($p = mysqli_fetch_assoc($pol))											  				//-pętla po polach kontenera
	  	  {
			Test::start('pole='.$tplll);
			$za_pola = '';

			$fo = array_pad(array(), 4, '');																//-tu dodać zdjęcia !!!!

		   //if($p['str'] === $this->ak || $p['str'] ==='all')									//-warunek podstrony, w ten sposób ze względu na 'all'
			//if($p['loka'] === $l['nazw'])

			if($p['str'] === $this->ak || $p['str'] ==='all')									//-warunek podstrony, w ten sposób ze względu na 'all'
			{

		    if($this->jo)
			 {

				  $li_atm = '
					<div class=\'ed edBox edR\'>
		 			 <a href=\''.S::linkCode(array(C::get('tab_pola'), $p['id'],'edycja', '', $this->back)).'.htmlc\'
		 				title=\'edytuj bole:['.S::BBclear($p['tyt']).']
		 				class={'.$p['typ'].'}
		 				id={'.$p['cssid'].'}
		 				wt={'.$p['wtyk'].'}\'>edytuj Pole-x</a>
					</div>';

			 }
			 else
			  $li_atm = '';


			 if(!$p['czwi'] && $p['tyt'])																		//-czy tytuł pola ma być wyświetlany?
	   	 {
	        if($p['h2'])
			   $typo = '
	<h2 class=\'tyt_box\'>'.S::formText($p['tyt']).'</h2>';											//-czy tytuł ma być h2 czy h3?
			  else
		      $typo = '
	<h3 class=\'tyt_pole\'>'.S::formText($p['tyt']).'</h3>';
	       }
			 else
			  $typo = '';


		    if($p['form'] && $p['tres'])																		//-formatowanie pola tekstowego
			 {
			  $p['tres'] = '
	<blockquote>'.S::formText($p['tres']).'
	</blockquote>';
			 }
		    else
			  $p['tres'] = '
			'.html_entity_decode($p['tres']);	 															//-czysty kod, który wykona się w przeglądarce


			 $wtykPola = $this->wtyczka($p);																	//-zwracana wartość -null kasuje pole

			 if($p['cssid'])
			  $cssIdPola = ' id=\''.$p['cssid'].'\'';									//-id pola
			 else
			  $cssIdPola = '';

			  if($this->jo) $kotwap = '
			  <div id=\'admpol'.md5($p['id']).'\'></div>';


			 if(trim($typo.$p['tres'].$wtykPola.$fo) && $wtykPola != '-null')
			  $za_pol .= '
				<div'.$cssIdPola.$this->klasaPolaVip($p['typ'], $p['blok'], $p['dapu'], 'klpo').'>'.$kotwap.$li_atm.$typo.$fo[0].$fo[1].$fo[2].$p['tres'].$fo[3].$wtykPola.'</div>';	//-zawartość kontenera
			 else
			  if($this->ja && !$l['wtyk']) $za_pol .= '
			  <div'.$cssIdPola.$this->klasaPolaVip($p['typ'], $p['blok'], $p['dapu'], 'klpo').'>
			 	<p class=\'adm_error\'>'.$kotwap.$li_atm.'info dla Admina! - puste pole in '.__METHOD__.' on line -> '.__LINE__.'</p>
			  </div>';


		    unset($typo, $fo, $wtykPola);
			}
			Test::stop('pole='.$tplll++);
		  }

		  $za_kon .= $za_pol;

		  unset($pol, $p, $tplll, $za_pol, $cssIdPola, $li_atm);

		 //-------------------------------------------------------------------------------------------------------------

		 if($this->jo && !$za_kon && !$l['wtyk']) $za_kon = '
		 <p class=\'adm_error\'>'._KO_EMPTY2.'</p>';													//-brak treści! komunikat dla Admina!

		 if($this->jo)
		  $kotwa = '
		 <div id=\'admbox'.md5($l['id']).'\'></div>';												//-kotwica dla linków powrotnych 2013-06-27

		 //- HTML -

		 if($l['idko'])
		  $l['idko'] = ' id=\''.$l['idko'].'\'';														//-id kontenera
		 else
		  $l['idko'] = '';

		 if($tytkon.$wtykkon.$za_kon) $kol .= '
	<div'.$l['idko'].$this->klasaPolaVip($l['klko'], $l['blok'], $l['dapu'], 'klko').'>'.$kotwa.$li_ako.$tytkon.$za_kon.$wtykkon.'
	</div>';
    	 else
	     if($this->ja) $kol .= '
	<div'.$l['idko'].$this->klasaPolaVip($l['klko'], $l['blok'], $l['dapu'], 'klko').'>'.$li_ako.'
 	 <p class=\'adm_error\'>Kolumna - PUSTA!</p>
	</div>';

		 unset($za_kon, $tytkon, $wtykkon, $blok_kont, $li_ako, $li_atm);			//-kasuje zawartość kontenera przed generowaniem następnego
		//}

		unset($l);

		Test::stop('kontener='.$tpll++);
	  }

	}
  	catch(Exception $e)
  	{
	 C::debug($e, 2);
  	}

	unset($lok, $war, $tpll, $tplll);

	$this->polahtml[$klhtml] .= $kol;

	unset($kol);

	Test::stop('polahtml='.$tpl++);
  }
  unset($tpl);

  if(isset($ldtre)) 						//-do pola administratora :: mechanizm dodowania box'ów i pól
  {
   $c['boxy'] = $ldtre; 				//-tablica boxów
	unset($ldtre);

	C::loadConfig($c);					//2013-02-04
  }
  else
  {
   $c['boxy'] = ''; 						//-tablica boxów jak nie ma jeszcze tabel
	C::loadConfig($c);					//2013-02-05
  }

  Test::stop(__METHOD__);
  return $whtml;
 }

 /**
 *
 * wtyczka do kontenera lub pola
 * w postaci inkludowanego pliku lub tworzonego obiektu wskazanej klasy
 *
 *
 */

 private function wtyczka(& $l)
 {
  Test::start(__METHOD__);

  if($l['wtyk'])																				//-jeśli jest wtyczka
  {

   if(!isset($l['stro']))
    if(isset($l['str']))																	//-dla strony
	  $l['stro'] = & $l['str'];															//-sprawia że wtyczki operujące na danych z box'a otrzymają
	 																								//-automatyczne przeniesienie dla pola

   if(!isset($l['nazw']))
    if(isset($l['kate']))
	  $l['nazw'] = & $l['kate'];															//-analogicznie dla nazwy pox'a lub pola

   $fw = substr($l['wtyk'], 0, 1); 														//-wtyczki pisane z dużej litery to klasy

   if($fw === strtoupper($fw))															//-jeśli nazwa wtyczki z duzej litery to wtyczką jest klasa
   {
    $rr = new $l['wtyk']();

    $metod = strtolower(substr($l['wtyk'], 2, 1)).substr($l['wtyk'],3);

    $rr->$metod();																			//-warunki klasy która może być wtyczką: musi istnieć metoda

  	 unset($fw, $metod, $l); 																// o takiej samej nazwie jak klasa, w celu umożliwienia dodania parametrów

	 Test::stop(__METHOD__);
    return $rr->wynik();																	// wynik zawsze generuje metoda o nazwie wynik()
																									// nazwa klasy = W_nazwa.php
    																								// nazwa metody = nazwa
   }
   else																							//-inaczej wtyczką jest includowany plik
   {
    if(file_exists(_APATH.'wtyczki/'.$l['wtyk']._EX))
    {

     //UWAGA! require_once _APATH.'wtyczki/'.$l['wtyk']._EX;
	  //powodowało , że nie wykonywały się na stronie więcej niż raz te same wtyczki !!!

	  require _APATH.'wtyczki/'.$l['wtyk']._EX;

	  unset($l);

	  Test::stop(__METHOD__);
     return $wtyk;
    }
    else
     if(C::get('jo')) return '
		<p class=\'error\'>brakuje wtyczki! -> syst/wtyczki/'.$l['wtyk'].'</p>';
	}

  }
  Test::stop(__METHOD__);
 }

 /**
 *
 * oznaczenie znacznika kolorem tła: czerwone = zablokowane,  zielone = przygotowane do publikacji
 *
 * klasa-> dodatkowa klasa
 * b-> warunek dla klasy 'blok' - blokowana
 * t-> warunek dla klasy 'dopu' - do publikacji
 * p-> dodatkowa klasa dla admina
 *
 */

 private function klasaPolaVip($klasa, $b, $t, $p)
 {
  $kl = array();

  if($klasa) $kl[] = $klasa;

  if($b)
   $kl[] = 'blok';		//-klasa pola zablokowanego
  else
   if($t > C::get('datetime_teraz'))
    $kl[] = 'dopu';		//-klasa pola do publikacji

  if($this->ja) $kl[] = $p;

  unset($b, $t, $p, $klasa);

  if($kl)
   return ' class=\''.trim(implode(' ', $kl)).'\'';
  else
   return;
 }

 /**
 *
 *
 *
 */

 protected function tablesList()
 {
  $t = array();

  $pref = C::get('db_prefix');

  if(!$aktb = C::get('akt_baza', false)) return false;

  try
  {
	//-wyklucza wyświetlanie tabel z innych serwisów

	if($tab = DB::myQuery('SHOW TABLES'))
    while($ta = mysqli_fetch_assoc($tab))
     if(substr($ta['Tables_in_'.$aktb], 0, strlen($pref)) == $pref || $pref == '')
	   $t[] = $ta['Tables_in_'.$aktb];

    unset($tab, $ta, $aktb, $pref);

 	 return $t;

  }
  catch(Exception $e)
  {
	C::debug($e, 2);
  }

 }

}
?>