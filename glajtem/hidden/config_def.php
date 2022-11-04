<?
defined('_CONPATH') or header('HTTP/1.1 404 File Not Found');

/**
* test
* 2016-05-24 : nowe przewinięcie do wskazanej treści
* 2013-02-12
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

define('_1MB', 1048576); 									//-dla plików
define('_ZM_KOD', 'vyfhd43-glajtem-34dsd4e');			//-kod dla serwisu

define('_DEBUG', _ZM_KOD);									//-włącza testy

define('_ZONE', 'Europe/Warsaw');						//-strefa czasowa

$c['skok'] = '';												//-przewinięcie strony do wskazanej treści

$c['nonet'] = true;

$c['mail_test'] = 1;											//-do lokalnego testowania komunikatów po wysłaniu mmaila 1= ok. 2= false false = wyłączone testowanie

$c['lang'] = 'PL';											//-podstawowa wersja językowa dla skryptu

$c['IlRekNaEkr'] = 400;										//-porcjowanie publikacji

$c['set_ja'] = 1;
$c['set_jo'] = 1;

$c['ip_vip'] =  '';					 		//-> tylko do testów !!
$c['ip_adm'] =  '';					 		//-> tylko do testów !!
$c['ip_test'] = '78.8.139.17';					 		//-> tylko do testów !! lokalnie wystarczy dowolna wartość, w sieci musi być ip

$c['doz_tab'] = array('glajtem_03_piloci', 'glajtem_04_loty_dpl');

$c['noFilesFile'] = false;									//-> do przenoszenia plików  Cms.php (540)
$c['only_m_kadr'] = true;									//-> kadrowana jest wyłącznie miniatura z prefiksem m_

$c['fb'] = false;

$c['plusGoogle'] = true;									//-> odnośnik +1 Googl
$c['plusHTML'] = '
itemscope itemtype=\'http://schema.org/LocalBusiness\'';

$c['set_remote'] = 0;										//-wart=1 wymusza podpięcie lokalnego serwisu na zewnętrzną bazę MySQL

$c['adres_strony'] = 'glajtem.pl';

$c['maxsize_file_upload'] =  _1MB * 2;

$c['tmpPath_foty'] 	=  './tmp_foty/';					//-katalog na tymczasowe pliki graficzne (przed obróbką)
$c['thubs_X'] = 170;
$c['thubs_Y'] = 170;

$c['fotyPath']  = './foty/';								//-katalog ze zdjęciami

$c['opinie_cenzura'] = 0;

$c['java'] = array
(
 'kadrowanie' => true										//-kadrowanie ustawione jako domyślne
);

$c['textOnFoto'] =  '';										//-text na grafiki

$c['fontOnFoto'] =  _CMSPATH.'fonty/STENCIL.TTF';	//-czcionka tekstu na gtrafiki

date_default_timezone_set(_ZONE);

$c['datetime_teraz'] = date('Y-m-d H:i:s', time());

$c['login_max'] = 6;											//-to s.a. parametry dla CMS'a :: przenieść je trzeba !!!

$c['login_blok'] = 20;

$c['kod_cap'] = false;										//-zabezpieczenie graficzne formularzy

$c['logowanie'] = false;

$c['login_menu'] = array( 'login.html' => 'login', 'logout.html' => 'logout');

$c['logout_time'] = 20;										//-w minutach

define('_CIA_SERW', $c['adres_strony']);				//-nazwa ciacha dla serwisu
define('_CIA_ADM',  $c['adres_strony'].'.admin');	//-nazwa ciacha dla admina
define('_CIA_VIP',  $c['adres_strony'].'.vip');		//-nazwa ciacha dla właściciela=użytkownika serwisu

$c['polahtml'] = array
(
	'left' 	=> '',
	'mein' 	=> '',
	'right'	=> '',
	'fb' 		=> ''
);

$c['komunikat'] = $c['add_photo'] = $c['reklama_org'] = 'mein';

/*
$c['add_photo'] = 'mein';
$c['reklama_org'] = 'mein';
*/

define('_KOMUNIKAT', 'komunikat');
?>