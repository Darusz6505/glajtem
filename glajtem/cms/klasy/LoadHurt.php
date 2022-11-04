<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/* 
@ Hurtowe ładowanie plików do wskazanej tabeli
* 2010-11-15 -> 2011-03-04 -> 2011-05-10

* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2008-09-12-- UTF-8
* skrypt nie jest darmowy!!
* aby legalnie wykorzystywać skrypt należy posiadać wykupioną licencję lub sgodę autora
*
* dane wejściowe: C::get('akt_baza')
*/

class LoadHurt
{

 private $fx = ''; 																		//-wizualny wynik dzialania klasy
 private $fz = ''; 
	
 private $a = '';																			//-akcja
 private $t = '';																			//-wybrana tabela
 private $id = '';																		//-tablica wartości predefiniowanych
 
 private $path = '';																		//-path do katalogu z plikami
 
 private $f = array();																	//-tablica plików
 
 function __construct()
 {

  $this->fx = 'FX';
  $this->fz = 'FZ';	
	
  $this->path = C::get('tmpPath_foty');	
	
  if(isset($_POST['id'])) $this->id = C::odbDane($_POST['id']);
	
  if(isset($_POST['t'])) $this->t = C::odbDane($_POST['t']);	
	
	
  if(isset($_POST['fil']))	
  {
   $this->f = $_POST['fil'];
		 
	/*	 
   foreach($this->f as $k => $w)
    $this->fx .= '
	 <p>->'.$k.'->'.$w.'</p>';
	 
	 $this->fx .= '<p>id : '.$this->id.'</p>';
	 $this->fx .= '<p>t : '.$this->t.'</p>'; */
	 
	 //$_SESSION['load_Hurt']['file'] 	= $files;								//-zapamietanie danych w sesji
	 //$_SESSION['load_Hurt']['t'] 		= $this->t;
	 //$_SESSION['load_Hurt']['id'] 		= $this->id;
	 
	 $this->newRekord(); 
  }
  else	
	$this->fileList();
		
 }		
 
 /*
 @
 */
 
 private function fileList()
 {
 
      if(is_dir($this->path))
		{
        $files = scandir($this->path);
			
        $bad = array('.', '..','Thumbs.db');
			
        $files = array_diff($files, $bad);
		  
			
		  if(is_array($files) && count($files) > 1)
		  {	
			
			sort($files);
			
		   foreach($files as $k => $w)
			{
			 $wt .= '
			 <label class=\'hurt\' for=\'pl'.$k.'\'>
			 <input type=\'checkbox\' name="fil['.$w.']" id=\'pl'.$k.'\'/>
			 <img src=\'./cms/tmp_foto_cms_tmp.php?id='.$w.'-'.session_id().'-200-200-k-t\' />
			 </label>';
			}
			//<label for=\'pl'.$k.'\'>'.$w.'
			
			$this->fx .= '
<script language="javascript" type="text/javascript">

function mirror() {
  d=document.order;
  for (i=0;i<d.elements.length;i++) {
	 if (d.elements[i].type==\'checkbox\') {
      if (d.elements[i].checked!=true) d.elements[i].checked=true;
        else d.elements[i].checked=false;
    }
  }
}

function zaznacz() {
  tak *=-1;
  d=document.order;
  for (i=0;i<d.elements.length;i++) {
	 if (d.elements[i].type==\'checkbox\') {
      if (tak==1) d.elements[i].checked=true;
        else d.elements[i].checked=false;
    }
  }
}

tak=-1;
</script>

			<form name=\'order\' action=\'\' method=\'post\'>
			 '.$wt.'
			 <input type=\'hidden\' name=\'t\' value=\''.$this->t.'\'>
			 <input type=\'hidden\' name=\'id\' value=\''.$this->id.'\'>
			 <p style=\'clear: both; text-align: center;\'>  
			  <input type=\'button\' value=\'zaznacz/odznacz wszystko\' onclick=\'zaznacz()\' />
  			  <input type=\'button\' value=\'odwróć wszystko\' onclick=\'mirror()\' />
			  <input type=\'submit\' name=\'loadHurt\' value=\'load\' />
			 </p>
			</form>';
			
			
		  }	
		  else
		   $this->fx .= 'Brak plików do załadowania';	
			
					
      }
 
 }
 
 /*
 @ - dodanie nowego rekordu
 * - dodanie do tabeli rekordów z nazwą pliku
 * :: zmiana nazwy pliku
 * :: dodanie rekordu do tabeli
 */
 
 private function newRekord()
 {
  try
  {		 
	list($t0, $t1) = microtime();															//-parametr dla nowej unikalnej nazwy pliku
	
	$time = C::get('datetime_teraz');
  
	$this->id = explode('.', $this->id);
	
   $licz = count($this->id);
	 
	for($i=0; $licz > $i; $i = $i+2)
	{
	 $query[] = $this->id[$i]. '='. $this->id[$i+1];
	}
	 
	unset($i, $licz);
	 
	$query[] = 'tr_dapu = \''.$time.'\'';
	$query[] = 'tr_dado = \''.$time.'\'';
	$query[] = 'tr_blok = 1';
	
	$query = implode(', ',$query);
	 
	ksort($this->f); 
	
	//C::test($this->f, true);
	
	foreach($this->f as $k => $w)
	{
	
	 (float)$t1++;
	 (float)$t2++;
	
	 $newName = uniqid($t1 + $t0).'.'.strtolower(substr($k,-3));
	 
	 $this->fx .= '<p>'.$newName.'</p>';
	 
	 $qu = $query.', tr_fot0 = \''.$newName.'\'';
	 
    $this->fx .= '<p>qu = '.$qu.'</p>';
	 

	 if(!rename($this->path.$k, $this->path.$newName))
	  throw new Exception('Zmiana nazwy pliku nie powiodła się! : '.$this->path.$k.'->'.$this->path.$newName);
		
	 if(!mysql_query("INSERT INTO $this->t SET $qu"))
	  throw new Exception(mysql_error());
	 
	}
	unset($qu, $guery, $t1, $t2);
	
  }	
  catch(Exception $e)
  {
   C::debug($e, 2);	
  }	
 
 }

 
 /*
 @
 */
 
 public function w()
 {
   $this->fz = '<p>Load - Hurt</p>'.$this->fz;
 
 	return array($this->fx, $this->fz);
 }
 
 /*
 @
 */
 
 function __destruct() 
 {

 }

}
?>