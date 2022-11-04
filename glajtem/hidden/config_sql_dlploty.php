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


//$c['wykaz_tabel'][] = $c['tab_piloci18'] =		$c['db_prefix'].'06_piloci18'; 					//->piloci DLP
$c['wykaz_tabel'][] = $c['tab_dpl18'] =		   $c['db_prefix'].'05_loty_dpl18';     			//->loty DLP 2016
$c['wykaz_tabel'][] = $c['tab_piloci19'] =		$c['db_prefix'].'08_piloci19'; 					//->piloci DLP
$c['wykaz_tabel'][] = $c['tab_piloci20'] =		$c['db_prefix'].'09_piloci20';


?>