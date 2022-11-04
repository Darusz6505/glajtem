<?
/**
@ procedury startowe dla cms.php i admin.php 
*
* 2011-05-25
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
* 1. ustalenie ścieżek dostepu do katalogów
* 2. wywołanie klasy startowej
*/

 session_start();	

 $starr_config = 'hidden';
 $starr_cms = 'cms';
 $application = 'application';  																//-potrzebne do wygenerowania listy szablonów wformularzu

 // Define the front controller name and docroot
 define('_DOCROOT', getcwd().DIRECTORY_SEPARATOR);
 define('_STARR',  basename(__FILE__));

 // If the front controller is a symlink, change to the real docroot
 is_link(_STARR) and chdir(dirname(realpath(__FILE__)));

 define('_CONPATH', str_replace('\\', '/', realpath($starr_config)).'/');
 define('_CMSPATH', '.'.str_replace('\\', '/', realpath($starr_cms)).'/');
 define('_APATH',   str_replace('\\', '/', realpath($application)).'/');

 // Clean up
 unset($starr_config, $starr_cms);
	
 define('_EX', '.php');	


 function __autoload($nazwa_klasy) 															//-automatycznie dołanacza niezbędne klasy
 {
  if(file_exists(_SYSPATH.'klasy/'.$nazwa_klasy._EX))
   require_once(_SYSPATH.'klasy/'.$nazwa_klasy._EX);
  else
   if(file_exists(_CMSPATH.'klasy/'.$nazwa_klasy._EX))
    require_once(_CMSPATH.'klasy/'.$nazwa_klasy._EX);
   else
    exit('You try join an unknown class! : '.$nazwa_klasy);		
 }
 
?>

