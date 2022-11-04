<?
defined('_APATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa Afilo :: reklama zewnętrzna z aleproste.pl
*
* z klasy Cron, metody uniwersalne : v.1.0
*
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-11-04 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


class Reklama
{
 private $myconectsql;

 private $rek = '';

 /**
 *
 *
 *
 */

 function __construct($size, $klasa = false, $metoda = false)
 {
  //echo '<p>Reklama</p>';
  //define('REK_TEST', true); //uzależnić to jeszcze od domeny i tylko dla aleproste

  if(defined('REK_TEST') && REK_TEST)
  {
	$c['jo'] = true;
	$c['ip_test'] = '';
  }
  else
   $set = true;

  //echo '<p>admin status = '.$_SESSION['admin']['status'].'</p>';

  $c['fotyPath']  = './foty/';

  require_once _CONPATH.'config_sql'._EX;


  if(!$this->tab = $c['tab_reklama'])
  {
   $this->error[] = 'Brak tablicy '.$c['tab_reklama'];
   return;
  }

  if($_SERVER['HTTP_X_FORWARDED_FOR'])											//-prawdziwe ip - bez funkcji ippraw()
   $c['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else
   $c['ip'] = $_SERVER['REMOTE_ADDR'];


  if(!eregi('^127\.', $c['ip']) || $c['set_remote'])
   $c['db_local'] = false;
  else
   $c['db_local'] = true;

  unset($c['set_remote']);

  if(!eregi('^127\.', $c['ip']))
   $c['localhost'] = false;
  else
   $c['localhost'] = true;

  C::loadConfig($c);																	//-załadowanie konfiguracji
  unset($c);


  $this->myconectsql = new Db($set);											//-zainicjowanie klasy DB



  if($er = $this->myconectsql->myError())
  {
   echo $er;
	return;
  }
  else
   if(!$klasa || !$metoda)
	{
	 echo S::reklama($size) . '^';
	 return;
	}
	else
	{
    $rr = new $klasa;

	 $this->rek = $rr->$metoda($size) . '^';

	 unset($rr);

	}

 }

 function rekAsyn()
 {
 	return $this->rek;
 }

 /**
 *
 *
 *
 */

 function __destruct()
 {
  unset($this->myconectsql);

  if($this->error)
   echo implode('<br/>', $this->error);
 }
}
?>
