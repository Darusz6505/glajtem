<?
defined('_CONPATH') or header('HTTP/1.1 404 File Not Found');

/**
* konfiguracja bazt MySQL i tabel dla aplikacji :: glajtem.pl
*
* 2016-05-24 -> zdjęcia dopięte z innych publikacji - nowa funkcjonalność
* 2015-01-03 -> dpl 2016 nowa tabela
* 2014-04-20
* 2013-09-09
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

$c['db_local_conect'] =  array
 (
  'type'     => 'mysql',
  'user'     => 'root',
  'pass'     => 'aleproste',
  'host'     => 'localhost',
  'port'     => FALSE,
  'socket'   => FALSE,
  'database' => 'starr_test',
  'character_set' => 'latin1'
);


$c['db_remote_conect'] =  array
 (
  'type'     => 'mysql',
  'user'     => '09465912_0000001',
  'pass'     => '4:vqMJ%dvaGa',
  'host'     => 'sql.elmont.home.pl',
  'port'     => FALSE,
  'socket'   => FALSE,
  'database' => '09465912_0000001',
  'character_set' => 'latin2'
);


$c['db_prefix'] = 'glajtem_';

/*
* UWAGA! :: tylko małe litery !!!!
* nazwy tabel też tylko małe litery !!!!
* kodowanie polskich znaków przez zdyblowanie litery łacińskiej np. aa = ą, AA = Ą itd.
*/


$c['wykaz_tabel'][] = $c['tab_reklama'] =			$c['db_prefix'].'02_reklama';						//->banery reklamowe

$c['wykaz_tabel'][] = $c['tab_piloci'] =			$c['db_prefix'].'03_piloci';						//->piloci DLP
$c['wykaz_tabel'][] = $c['tab_piloci18'] =		$c['db_prefix'].'06_piloci18'; 					//->piloci DLP
$c['wykaz_tabel'][] = $c['tab_piloci19'] =		$c['db_prefix'].'08_piloci19'; 					//->piloci DLP
$c['wykaz_tabel'][] = $c['tab_piloci20'] =		$c['db_prefix'].'09_piloci20'; 					//->piloci DLP

$c['wykaz_tabel'][] = $c['tab_dpl'] =				$c['db_prefix'].'04_loty_dpl';  					//->loty DLP 2015
$c['wykaz_tabel'][] = $c['tab_dpl18'] =		   $c['db_prefix'].'05_loty_dpl18';     			//->loty DLP 2016
$c['wykaz_tabel'][] = $c['tab_dpl19'] =		   $c['db_prefix'].'07_loty_dpl19';     			//->loty DLP 2019


$c['wykaz_tabel'][] = $c['tab_publikacje'] =		$c['db_prefix'].'10_tematy';						//-> kontenery publikacji

$c['wykaz_tabel'][] = $c['tab_teksty'] = 	  	  	$c['db_prefix'].'11_teksty';						//-> zawartość kontenerów publikacji
$c['wykaz_tabel'][] = $c['tab_fota'] =			  	$c['db_prefix'].'12_zdjeeecia';					//-> zdjęcia 1
$c['wykaz_tabel'][] = $c['tab_fotb'] =			  	$c['db_prefix'].'12_zdjeeecia_bloga';			//-> zdjęcia 2
$c['wykaz_tabel'][] = $c['tab_fots'] =			  	$c['db_prefix'].'13_zdjeeecia_bloga';			//-> zdjęcia 3 - galeria startowa
$c['wykaz_tabel'][] = $c['tab_pliki'] =			$c['db_prefix'].'14_instrukcje';					//-> pliki

$c['wykaz_tabel'][] = $c['tab_fotx'] =			  	$c['db_prefix'].'19_zdjeeecia_dopieeete';	   //-> zdjęcia dopięte z innych publikacji

$c['wykaz_tabel'][] = $c['tab_testy'] =			$c['db_prefix'].'20_testy';						//-> testy
$c['wykaz_tabel'][] = $c['tab_test3'] =			$c['db_prefix'].'21_instr_szybowce';			//-> testy
$c['wykaz_tabel'][] = $c['tab_test2'] =			$c['db_prefix'].'22_testy_szybowce';			//-> testy

$c['wykaz_tabel'][] = $c['tab_definicje'] =		$c['db_prefix'].'30_definicje';			 		//-> tabela definicji np. teksty dla poczty

//-x dla tabel systemowych obowiązkowe !!! (pozostało dla kompatybilności wstecznej )
//-dla tabel sortowanych zamiast x numer z zakreu 90 do 99

$c['wykaz_tabel'][] = $c['tab_owner'] =			$c['db_prefix'].'60_wlllasssciciel_serwisu';		//-> configuracja
$c['wykaz_tabel'][] = $c['tab_config'] =			$c['db_prefix'].'61_ustawienia_serwisu';			//-> configuracja

$c['wykaz_tabel'][] = $c['tab_admini'] =			$c['db_prefix'].'89_admins'; 							//-> dane administtatorów serwisu

$c['wykaz_tabel'][] = $c['tab_boxy'] =				$c['db_prefix'].'91_boxy';								//-> tabela lokalizacji kontenerów
$c['wykaz_tabel'][] = $c['tab_pola'] =				$c['db_prefix'].'92_pola';								//-> tabela definicji pól w kontenerach
//$c['tab_licznik'] = 	  	$c['db_prefix'].'99_licznik';							//-> tabela licznika odwiedzin !!! numer musi być stały !!!
$c['wykaz_tabel'][] = $c['tab_vip'] =				$c['db_prefix'].'94_vip';								//-> ustwienia (konfiguracja) serwisu
$c['wykaz_tabel'][] = $c['tab_tab'] =				$c['db_prefix'].'95_tab';								//-> tabela z parametrami tabel
$c['wykaz_tabel'][] = $c['tab_log_blok'] =		$c['db_prefix'].'96_blokady';							//-> blokady logowania

?>