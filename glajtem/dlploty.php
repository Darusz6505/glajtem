<?

/**
* automat dla DLP 2018 i dalszych
*
* 2018-08-11
* 2018-08-10 - poprawki dla crona
* start: 2018-08-08 (wdrożenie)
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


$cron = false;
$kod = 'fsdfr7rr2fasd'; //-główny kod

//echo '<code>'.substr($kod, 0, 3).'</code>';

if(isset($_GET['kikejrbd78df']))
 if(substr($_GET['kikejrbd78df'], 0, 13) != $kod)
  die('Cron-> Brak uprawnien!');
 else
 {
  $cron = true;
  $day = substr($_GET['kikejrbd78df'], -1);

  if($day >= 0 && $day < 8 && is_numeric($day))
   $_GET['itss78ffH$tr'] = date("Y-m-d", strtotime("-".$day." day"));
  else
   $_GET['itss78ffH$tr'] = date("Y-m-d", strtotime("0 day"));

 }

/*
echo '<code>'.$_GET['itss78ffH$tr'].'</code>';
exit('<br />stop'); */

 if(!$cron)
 {
  /* odblokować po testach !!!! - przenieść do wywołania klasy ??? */
  session_start();

  if(!$_SESSION['admin_zalog'])								//-jeśli jest zalogowany to do cms.php
   die('Brak uprawnien!');
  else
	$_GET['itss78ffH$tr'] .= 'x';

 }

 //echo '<code>Działanie dla Crona</code>';

 require_once 'hidden/start.php';

 $r = new Dlploty18();
 unset($r);

?>