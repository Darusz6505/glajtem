<?
/**
@
*
*
*
*/



function odbDane($p) 													//-###-odbiera dane przesyłane metodą GET i POST - likwidując niebezpieczne znaki ---	
{
 $p = trim($p); 															// usuwa zbędne spacje z przodu i z tyłu ---
 if(get_magic_quotes_gpc()) $p = stripslashes($p); 			// usuwa ukośniki ---
 return htmlspecialchars($p, ENT_QUOTES); 						// dezaktywuje znaki HTML ---
}


if(isset($_REQUEST['month']))  $kal_month = odbDane($_REQUEST['month']); else $kal_month = 0;



function kalendarz($ile) //-ile miesiąc
{ 
  $znacznik = mktime(0, 0, 0, date('m')+$ile, 1);
  $iledni   = date('t', $znacznik);
  $start    = date('w', $znacznik); //dzień
  $miesiac  = date('m', $znacznik);
  $rok      = date('Y', $znacznik);
  $miesiace = array('Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień');
  $dni 		= array('Pn','Wt','Śr','Cz','Pt','So','N');	
	
  $kal  = '
	<div class=\'month\' rel=\''.($ile-1).'\'>&#171;</div>
	<div class=\'nags\'>'.$miesiace[$miesiac-1].' '.$rok.'</div>
	<div class=\'month\' rel=\''.($ile+1).'\'>&#187;</div>';
	

  for($ii=0; $ii<7; $ii++) $kal  .= '<div class=\'ciem\'>'.$dni[$ii].'</div>';		//-nagłówek z dniami tygodnia

	if($start) 
	 $ppp = $start;
	else
	 $ppp = 7; 
	 
	if ($ppp>1) 
	 for($pe=1;$pe<$ppp;$pe++)
	 {
	  if ($pe==6) $bgklass = ' class="sob"';
	  $kal .= '<div'.$bgklass.'></div>';
	  unset($bgklass);
	 }
   else 
	 $pierwszy=true;
  
	for ($i=1;$i<=$iledni;$i++) 
	{
    $dzien = date('w', mktime(0, 0, 0, $miesiac, $i, $rok));
	 
    if ($dzien==0) $bgklass = 'nie';
	 if ($dzien==6) $bgklass = 'sob';

	 $pierwszy = false;
    
	 if (mktime(0, 0, 0, date('m'), date('d'), date('Y'))==mktime(0, 0, 0, $miesiac, $i, $rok))  $bgid = ' dzis';
	 
	 $d = sprintf("%02d", $i);
    	 
	 $kal .= '<div class=\''.$bgklass.$bgid.' day\' rel=\''.$i.'-'.$miesiac.'-'.$rok.'\'>'.$i.'</div>';
	 
	 unset($bgid, $bgklass);
   }	

  $koniec=7-($start+$iledni-1)%7;	//-dopełnienie pól na końcu miesiąca
	
  if($koniec == 7) $koniec=0;	
	
  for($pe=0; $pe<$koniec; $pe++) 
  {
   if ($pe==$koniec-1) $bgklass = ' class=\'nie\'';
	if ($pe==$koniec-2) $bgklass = ' class=\'sob\'';
	
	$kal .= '<div'.$bgklass.'></div>';

	unset($bgklass);
  }
  $kal .= '<b id=\'closeKalendar\'>CLOSE</b>';
  	
  return $kal;
}


echo json_encode('<div class=\'kalendar\'>'.kalendarz($kal_month).'</div>'); 
?>
