<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/*
*
* obsługa połączenia i zapytań MySQL :: v.1.1
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
* 2012-04-07
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-11-04 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


class Db
{

 private $db_config = array();

 private $link;

 private $error = '';
 private $set = false;

 public function __construct($set_error = false)
 {
  Test::start('DB_conect');
  Test::trace(__METHOD__);

  if($set_error) $this->set = true;

  if(is_resource($this->link))
   return $this->link;

  if(C::get('db_local_conect') && C::get('db_local'))
   $this->db_config = C::get('db_local_conect');
  else
	if(C::get('db_remote_conect') && !C::get('db_local'))
	 $this->db_config = C::get('db_remote_conect');
   else
	 $this->error = 'no config to mysql connect! in '.__CLASS__.' -> '.__LINE__;

  if(!$this->error)
  {
   extract($this->db_config);

   $host = isset($host) ? $host : $socket;
   $port = isset($port) ? ':'.$port : '';

   try
   {
    //if(!$this->link = mysqli_connect($host.$port, $user, $pass, TRUE))
	 if(!$this->link = mysqli_connect($host, $user, $pass, $database))
	  throw new Exception('no connect with dbase host : '.$host.$port);
	 else
    {

	  if(!mysqli_select_db($this->link, $database))
	   throw new Exception('no connect with dbase select : '.$database);
	  else
	  {
	   C::set('akt_baza', $database);
		C::set('akt_conect', $this->link);

		Test::stop('DB_conect');

	   return $this->charSet($character_set);
	  }
	 }


   }
   catch(Exception $e)
   {
    C::set('akt_baza', false);
    Test::stop('DB_conect');

	 if($this->set)
	  $this->error = strip_tags(C::debug($e, 'cron'));
	 else
     C::debug($e, 2);

   }
  }
  else
   $this->myError();
 }

 /**
 *
 *
 */

 public function myError()
 {

  if($this->error)
  {

   Test::trace(__METHOD__, 'DB - error', $this->error);

   if(!$this->set)
	{
 	 if(C::get('jo'))
	  exit('X:ERROR: '.$this->error);
	 else
	  C::myGoto('cms/stop.php');
   }
	else
	 return strip_tags('Y:'.$this->error);
  }
  else
   return false;

 }

 /**
 *
 *
 */

 private function charSet($character)
 {
  $li = $this->link;

  try
  {
   if(!mysqli_query($li, "SET CHARSET ".$character))
    throw new Exception(mysqli_error());
	else
	 return true;
  }
  catch(Exception $e)
  {
	C::debug($e, 2);
  }
 }

 /*
 @
 *
 */

 public static function myQuery($q)
 {
  //Test::trace(__METHOD__, $q); //-pokazuje wszystkie zapytania do bazy

  $li = C::get('akt_conect');

  Test::start('DB myQuery');

  if($q)
  {
	$tab = mysqli_query($li, $q);

   if($tab)
	{

	 if($id = mysqli_insert_id($li))
	  C::add('my_id', $id); // 09-03-2021
	 else
	  C::add('my_id', "0"); // 09-03-2021

	 Test::stop('DB myQuery');

	 return $tab;
	}
	else
	{
	 //Test::trace(__METHOD__, $q);
	 Test::stop('DB myQuery');
	 throw new Exception(mysqli_error().'<br> --> '.$q);
   }
  }
 }


 /*
 @
 */

 public function __destruct()
 {
  is_resource($this->link) and mysqli_close($this->link);
 }

}