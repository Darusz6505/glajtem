<?
/**
@ incjator skryptu łączącego polea select, kategorie i podkategorie
*
* - 2011-09-18
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/

 //require_once '../hidden/start.php';

 require_once './start_cms_ajax.php';
 
/*
 if(DEFINED('_CMSPATH'))
 {
 	$select[0] = _CMSPATH;	
   echo json_encode($select);
	exit();
 
 
 }
 else
 {
 	$select[0] = 'NIE';	
   echo json_encode($select);
	exit();
 }  */
 

 $r = new Podkategoria();
 unset($r);
?>