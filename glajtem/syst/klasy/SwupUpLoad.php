<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* klasas AJAX'owa
*
* 2013-02-08 ; poprawki i poprawki dla stat.pl ( statystyki doklejane automatycznie przez home.pl - można wyłączyć)
*
* klasa odpowiedzialna za:
*
* Error() - tworzy plik błedów dla tej klasy
*
* 2011-09-17
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-05-12 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class SwupUpLoad
{

 private $error = '';

 private $trace = '';		//-komunikaty testowe

 /**
 *
 *
 */

 function __construct()
 {
  error_reporting(E_ALL & ~E_NOTICE);

  //ob_end_clean();				//-czyści bufor wyjścia bo komunikacja odbywa się za pomocą echo, i każdy niekontrolowany znak spowoduje problemy

  if(isset($_POST["PHPSESSID"]))
  {
	/*
	require_once './hidden/start.php';
	require_once _CONPATH.'config_def'._EX;
	require_once 'S'._EX;

   $r = new S;
	$sid = explode(',', S::linkDecode2($_POST["PHPSESSID"]));
	*/

	$sid = explode(':', $_POST["PHPSESSID"]);

   session_id($sid[0]);

	//$this->trace .= "\n".__FILE__.'-> line: '.__LINE__.' sid[0] = '.$sid[0];
	//$this->trace .= "\n".__FILE__.'-> line: '.__LINE__.' sid[1] = '.$sid[1];

  }
  else
  {
	$this->error .= "\n" . ' - BRAK PRZEKAZNIA ID SESJI!';
	return;
	exit;
  }

  // $sid[0]	= numer sesji
  // $sid[1]	= token zabezpieczający

  session_start();

  $this->trace .= "\n" . __FILE__.'-> line: '.__LINE__.' session_id = '.session_id();

  //$this->error .= "\n" . ' - TEST :: '.session_id().' :: '.$_SESSION['admin']['status']; return;

  if($_SESSION['admin']['status'] < 9) 								//- ograniczenie tylko dla administratora
  {
  	$this->error .= "\n" . ' - Nie masz uprawnień do wykonania tej akcji!';
	return;
  }
  else
  {

	$path = false;

   require _CONPATH.'config_def'._EX;							//-koniecznie tu, przed sprawdzeniem sid[2]

   $path = $c['tmpPath_foty'];

	unset($c);

	if($sid[1] !== md5(_ZM_KOD))											//-dodatkowy token zabezpieczający
	{
	 $this->error .= "\n" . ' - Wykryta próba obejścia zabezpieczeń!';
	 return;
	}

	if(!$path) 															//-kontola defincji katalogu tymczasowego na pliki
	{
    $this->error .= "\n".'Brak definicji katalogu tymczasowego dla plików!';
 	 return;
	}

	$this->upLoad($path);											//-zapis plików w katalogu tymczasowym
  }
 }

 /**
 * - testuje plik przed wczytaniem i jeśli jest poprawny, zapisuje go na dysku w katalogu tymczasowym pod nową nazwą
 * - dodając przedrostek x-
 * - jeśli wszytko
 */

 private function upLoad($path)
 {
	// Check the upload
	if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error'] != 0)
	{
	 $this->error .= "\n" . 'Błąd wczytwania danych dla pliku!';
	 return;
	}
   else
	{

	 list($t0, $t1) = microtime();

	 $fileName = 'x-'.md5($t1 + $t0 + rand()*10000000). '.jpg';
	 //-x_ z tego powodu, że wszystkie ładowane zdjęcia są ładowane do tego samego katalogu


	 if(!move_uploaded_file($_FILES['Filedata']['tmp_name'], $path . $fileName))
	 {
	  $this->error .= "\n" . 'Błąd zapisu dla pliku w '.__CLASS__.' line-> '.__LINE__;
	  return;
	 }
	 else
	  if(!chmod($path . $fileName , 0777))
	  {
	   $this->error .= "\n" . 'Nie można ustawić praw do pliku w '.__CLASS__.' line-> '.__LINE__;
		return;
	  }
	  else
	  {

	   if(!$this->error)
		{

			$file_id = basename($fileName);

			// aby plik był identyfikowalny prze javaScript, tutaj identyfikatorem jest nazwa pliku

			ob_end_clean();

			//$file_id = S::linkCode2(array($file_id, 'sesja'));

			$this->trace .= "\n" . __FILE__.'-> line: '.__LINE__.' $file_id = '.$file_id;

			echo 'FILEID:'. $file_id .'#';						// Return the file id to the script !!! tu następuje przejkazanie parametru $file_id

			unset($file_id);

		}
      else
		 return;

	  }
   }
  }

 /**
 *
 * zapisuje błędy do pliku błędów dla tego skryptu
 *
 */

 private function Error($file_name = './logs/akcja_upload_error')
 {
  if($this->error)
  {
   $this->error = date("d-m-Y h:i:s", time()).'-> Errors in ' . __FILE__ . "\n" . $this->error;

	if(!file_exists($file_name . '.php'))
	 $this->error = '<? exit() ?>' . "\n" . $this->error;

   $h = fopen($file_name . '.php', 'a');

   fputs($h, $this->error);
   fclose($h);
   unset($h);
  }
  return;
 }

 /**
 *
 * funkcja - zapisuje do pliku ustawione znaczniki kontrolne, tylko  w trybie testowym
 * nie jest to historia tylko wynik ostatniego działania
 *
 */

 private function traceShow($t, $name = './logs/swupupload_trace')
 {
	 $z = '';

    if(is_array($t)) $t = implode("\n::", $t);

    $t = $z."\n".date("Y-m-d H:i:s", time()).' | '.$t."\n";

    $h = fopen($name.'.txt', 'w');

    fputs($h, $t);

    fclose($h);

    unset($h, $t, $z);
 }

 /**
 *
 * - destruktor
 *
 */

 function __destruct()
 {
  unset($r);

  if(_DEBUG === _ZM_KOD)
  {
   if($this->error) $this->Error();

   if($this->trace) $this->traceShow($this->trace);    			//-zapisuje znaczniki kontrolne, w trybie testowym
  }

  ob_end_clean();																//-czyści bufor wyjścia przed echo
  if($this->error !== '') echo 'ERROR: '.$this->error;			//-zwraca komunikaty błędów

 }

}
?>
