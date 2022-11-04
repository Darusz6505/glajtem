<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/* CMS - OBSŁUGA TABEL - VIP :: v.1.1

2010-11-15 -> 2011-03-04 -> 2013-02-05
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2008-09-12-- UTF-8
* skrypt nie jest darmowy!!
* aby legalnie wykorzystywać skrypt należy posiadać wykupioną licencję lub sgodę autora
*/

//require_once _CMSPATH.'config_cms'._EX;


$klasa = 'MojeSQL';

$r = new $klasa();

list($ffx, $ffz) = $r->w();

$fx .= $ffx;
$fz .= $ffz;

//-to jest wytyczk, więc musi nastapić przekazanie parametrów

unset($r, $ffx, $ffz, $klasa);
?>
