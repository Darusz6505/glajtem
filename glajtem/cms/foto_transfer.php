<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/**
* wtyczka serwisowa dedykowana :: przeniesienie zdjeć z bloków treści do tabeli zdjeć
*
* 2012-02-01
*
*
*/

if(!C::get('jo')) exit();

/*
$tab = 'SELECT * FROM '.C::get('tab_fota');

if($tab = DB::myQuery($tab))
{
 

} */

try
{

$tab = 'SELECT * FROM '.C::get('tab_teksty');

if($tab = DB::myQuery($tab))
 while($ta = mysql_fetch_assoc($tab))
 {
  
	
  for($i=0; $i<6; $i++)	
  {
		
   if($ta['tr_fot'.$i]) $zap[] = 'fo_fot0 = \''.$ta['tr_fot'.$i].'\'';
   if($ta['tr_poz'.$i]) $zap[] = 'fo_poz0 = \''.$ta['tr_poz'.$i].'\'';
   if($ta['tr_opf'.$i]) $zap[] = 'fo_opf0 = \''.$ta['tr_opf'.$i].'\'';			
 
   if($ta['tr_fot'.$i]) 
	{ 
	 $zap[] = 'fo_idte = '.$ta['tr_id'];
 
    $zap = 'INSERT INTO '.C::get('tab_fota').' SET '.implode(', ', $zap);
 
    $wt .= '<p>'.$zap.'</p>';
	
	 if(!DB::myQuery($zap))
     $wt .= '<p style=\'color: red;\'>ERROR</p>';
	 else
	  $wt .=	'<p style=\'color: blue;\'>OK</p>';
	
	}
	unset($zap);
  }
 
 
 
 
 
 

 }
}
catch(Exception $e)
{
 $fx .= C::debug($e, 0);
}



$fx .= $wt;

$fx .= '<p>END OF Foto Transfer</p>';

?>