<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/*
@ wtyczka systemowa: podkategoria generowana za pomocą jQuery jako pole zależne
*
* 2011-11-01
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-05-12 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
* http://blog.piotrnalepa.pl/2011/08/03/jqueryzalezne-listy-rozwijane-z-wykorzystaniem-jquery/
*/


class Podkategoria
{ 
 private $error = '';
 
 private $id_kat = '';
 
 private $test = false;
 
 /*
 @
 */
 
 function __construct()
 { 
	/*
 	$select[0] = _CONPATH;	
   echo json_encode($select);
	exit(); */
 	
  if(!empty($_GET['id'])) 
  {	
	
   $this->id_kat = $_GET['id'];
	
  }
  else
	$this->error .= 'Brak id kategorii!';		
	
	
  if(!$this->error)
  {		
   require_once _CONPATH.'config_sql'._EX;	
	
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
	
	
   C::loadConfig($c);		//-załadowanie konfiguracji
   unset($c);
	
  /*		
	Test::showNow(false, true);	//-komunikat systemowy o funkcjach i zmiennych globanych	
   
	Test::traceShow();
	
	C::showConfig(); */
	
   $r = new Db;			//-zainicjowanie klasy DB
	
   $this->podkategoria();
  }
	
		
  if($this->error) $this->Error();	
 }
 
 /*
 @
 */
 
 /*
 @
 */
 
 private function podkategoria()
 {
  $tab = 'SELECT * FROM '.C::get('tab_kateg2').' WHERE ka2_kat1 = '.$this->id_kat;
 
  if($tab = DB::myQuery($tab))
   while($ta = mysql_fetch_assoc($tab))
	{
	 $select[$ta['ka2_id']] = $ta['ka2_nazwa'];
	}	
 
 
  if($select)
   echo json_encode($select);	
  else
  {	
   $select[0] = 'brak';	
   echo json_encode($select);
  }	
	
  unset($tab, $ta, $select);	
 }
 
 /*
 @
 */

 private function Error()
 {
 
  if($this->error)
  {
   $select[0] = $this->error;	
   echo json_encode($select);
	
  }	
 
  /*
  if($this->error)	
  {
  
   $error = date("Y-m-d h:i:s", time()).'-> error w ./sort.php | ';
	
	$error .= "\n".$this->error;
	
   $error .= "\n";

	if(!file_exists('akcja_sort.php'))
	 $error = '<? exit() ?>' . "\n" . $error;
	
   $h = fopen('akcja_sort.php', 'a');

   fputs($h, $error);

   fclose($h);

   unset($h, $error);
	
  }	 */
 }	
	
 
 /*
 @
 */
 
 function __destruct() 
 {
  unset($r);
 }
 
}
?>