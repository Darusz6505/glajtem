<?
/**
* incjacja zmiennych i stałych :: v.2.7
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
* 2013-03-09
* 2013-02-20 : doodana nowa obsługa błędów i śledzenia
* 2013-02-16
*
*
*
*/

 define('_MY_TEST', false);						// true - wyłanacza wszystkie znaczniki poza czasem i błędami
 define('_MY_TIME', false);						// true - włancza znaczniki czasu dla trybu normalnego
 define('_MY_TR', false);							// true - wyłącza nowe znaczniki trace

 error_reporting(E_ALL); // & E_NOTICE);  	// dodanie powoduje wyłaczenie raportowania błedów krytycznych, w efekcie strona jest pusta
 //error_reporting(-1);

 ini_set('display_errors', 1);

 //require_once './cms/klasy/Test.php';	// nie jest potrzebne gdyż odowłanie następuje po function __autoload($nazwa_klasy)

 function myError($level, $errstr, $errfile, $errline, $errcontext)
 {

  if($level === E_USER_ERROR || $level === E_USER_WARNING || $level === E_USER_NOTICE) // -Handle user errors, warnings, and notices ourself
  {
	 if(is_string($errstr)) $errstr = unserialize($errstr);

	 //echo '<p>x :: '.$errstr.'</p>';

	 if(!isset($errstr['args'][1])) $errstr['args'][1] = null;
	 if(!isset($errstr['args'][0])) $errstr['args'][0] = null;

	 //public static function tracer($file, $method, $function, $line, $name = null, $value = null, $stop = false)

	 Test::tracer('My trace: '.$errstr['file'], '', '', $errstr['line'], $errstr['args'][0], $errstr['args'][1]);

    return(true); 		//And prevent the PHP error handler from continuing
  }
  else
  {
   Test::errorSet($level, $errstr, $errfile, $errline, $errcontext);
  }

  return(true);
 }

 set_error_handler('myError');

 /**
 *
 *
 */

 define('_EX', '.php');

 $starr_starr = 'syst';
 $starr_config = 'hidden';
 $starr_cms = 'cms';
 $starr_application = 'application';

 if(defined('_ROT_PREF'))
 {
  $starr_starr = _ROT_PREF . $starr_starr;
  $starr_config = _ROT_PREF . $starr_config;
  $starr_cms = _ROT_PREF . $starr_cms;
  $starr_application = _ROT_PREF . $starr_application;
 }

 define('_HOST', $_SERVER['HTTP_HOST']);

 // Define the front controller name and docroot
 define('_DOCROOT', str_replace('\\', '/', getcwd().DIRECTORY_SEPARATOR));  //-modify 2013-03-02
 define('_STARR',  basename(__FILE__));

 // If the front controller is a symlink, change to the real docroot
 is_link(_STARR) and chdir(dirname(realpath(__FILE__)));

 define('_APATH', 	str_replace('\\', '/', realpath($starr_application)).'/');
 define('_CONPATH', 	str_replace('\\', '/', realpath($starr_config)).'/');
 define('_CMSPATH', 	str_replace('\\', '/', realpath($starr_cms)).'/');
 define('_SYSPATH', str_replace('\\', '/', realpath($starr_starr)).'/');

 /*
 echo '<br>APATH = '._APATH;
 echo '<br>CONPATH = '._CONPATH;
 echo '<br>CMSPATH = '._CMSPATH;
 echo '<br>_SYSPATH = '._SYSPATH;
 */
 //exit;

 // Clean up
 //unset($starr_config, $starr_cms, $starr_application, $starr_starr);

 /*
 function __autoload($nazwa_klasy) 												//-automatycznie dołanacza niezbędne klasy
 {
  if(file_exists(_SYSPATH.'klasy/'.$nazwa_klasy._EX))
   require_once(_SYSPATH.'klasy/'.$nazwa_klasy._EX);
  else
   if(file_exists(_APATH.'wtyczki/'.$nazwa_klasy._EX))
    require_once(_APATH.'wtyczki/'.$nazwa_klasy._EX);
	else
	 if(file_exists(_APATH.'klasy/'.$nazwa_klasy._EX))
     require_once(_APATH.'klasy/'.$nazwa_klasy._EX);
	 else
     if(file_exists(_CMSPATH.'klasy/'.$nazwa_klasy._EX))
      require_once(_CMSPATH.'klasy/'.$nazwa_klasy._EX);
     else
	   C::error('You try an unknown class join name: [ '.$nazwa_klasy.' ] in '.__FILE__.' on line '.__LINE__);
 } */

 function nazwa_kl($nazwa_klasy)
 {
  if(file_exists(_SYSPATH.'klasy/'.$nazwa_klasy._EX))
   require_once(_SYSPATH.'klasy/'.$nazwa_klasy._EX);
  else
   if(file_exists(_APATH.'wtyczki/'.$nazwa_klasy._EX))
    require_once(_APATH.'wtyczki/'.$nazwa_klasy._EX);
	else
	 if(file_exists(_APATH.'klasy/'.$nazwa_klasy._EX))
     require_once(_APATH.'klasy/'.$nazwa_klasy._EX);
	 else
     if(file_exists(_CMSPATH.'klasy/'.$nazwa_klasy._EX))
      require_once(_CMSPATH.'klasy/'.$nazwa_klasy._EX);
     else
	   C::error('You try an unknown class join name: [ '.$nazwa_klasy.' ] in '.__FILE__.' on line '.__LINE__);

 }

 spl_autoload_register('nazwa_kl');



 /*
 *
 *
 */

 if(defined('_MY_TEST'))
 if(!_MY_TEST)
 {
  assert_options(ASSERT_ACTIVE, 1);
  assert_options(ASSERT_WARNING, 0);
  assert_options(ASSERT_QUIET_EVAL, 1);

  function my_assert_handler($file, $line, $code, $desc = null)
  {
   //Test::errorSet($errno, $errstr, $errfile, $errline, $errcontext);

	Test::errorSet(0, 'Assert -> [ '.$code.' ]', $file, $line, null);

	/*
	echo "<hr>Assertion Failed:
        File '$file'<br />
        Line '$line'<br />
        Code '$code'<br /><hr />"; */

		  /*
		  echo '<pre>'; ;
    		print_r(debug_backtrace());
	 	  echo '</pre>';

    if($desc) {
        echo ": $desc";
    }
    echo "\n"; */
  }

  assert_options(ASSERT_CALLBACK, 'my_assert_handler');
 }

 //assert('2 < 1');
 //if($b) $b = 1;
?>