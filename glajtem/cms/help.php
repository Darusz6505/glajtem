<?

  if(isset($_GET['a'])) $plik = C::odbDane($_GET['a']);


  $katalog = 'cms/help/';

  if(!is_dir($katalog))
  {
   $fx .= '<p class=\'error\'>Brak katalogu plików pomocy!</p>';	
  }	
  else
  {
	$dirs = 	glob($katalog.'*.php');
	
	foreach($dirs as $dir)
	{
	 $dir = preg_replace("/&#65533;/", 'ó', $dir);
	
	 $wt[] = '<a href=\''.substr(basename($dir), 0, -4).',help.help\'>'.preg_replace('/_/', ' ', substr(basename($dir), 0, -4)).'</a>'; 
	 
	 //if(count(glob($dir.'/'.$t.'.php')) > 0)
	}  
	 
	if($wt) 
	 $fx .= '
	 <p>Wybierz temat:'.implode(' || ', $wt).'</p>';
	else
	 $fx .= '<p class=\'error\'>Brak archiwum: '.$katalog.'</p>'; 
	 
   unset($dirs, $dir, $wt);

  }	

  if($plik)
  {
	$fx .= '<h3>'.$plik.'</h3>';
	
	
   $plik = $katalog.$plik.'.php';
		
   if(file_exists($plik)) 									//-jeśli istnieje plik do wczytania	
   {		
    $h = fopen($plik, 'rb');
    $plik = fread($h, filesize($plik));
    fclose($h);
   }	
	
	$plik = preg_replace('/\r\n?/', '<br />', substr($plik, 18));	
	
	$fx .= $plik;
  }		

  unset($plik, $katalog)
?>