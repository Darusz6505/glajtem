<?php defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/**
 *
 * Klasa testów - v:2.02
 *
 * 2013-06-07 :: poprawiony błąd dla śledzenia z przeładowaniem
 * 2013-02-22

 * public static function start($name)
 * public static function stop($name)
 * public static function get($name, $decimals = 4)
 * public static function timeShow($name = TRUE)
 * public static function tracer($file, $method, $function, $line, $name = null, $value = null, $show = false, $stop = false)
 * private static function traceShow($opc = false)
 * public static function errorSet($errno, $errstr, $errfile, $errline, $errcontext)
 * private static function errorShow
 * private static function constShow()
 * public static function pre2($tab)
 * public static function pre($tab)
 * private static function logZap($t, $typ = false, $name = './logs/log_test', $ext = 'php')
 * public static function testShow($op = false)
 * private static function trigger_my_error($level)
 * public static function trace($name ='default_name', $val = '')


 *
 * Simple benchmarking.
 *
 * $Id: Benchmark.php 2897 2008-06-25 08:02:08Z armen $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @license    http://kohanaphp.com/license.html

 * pomiar czasu dział zawsze gdy tylko defined('_MY_TEST') == true
 * zapisywanie błędów działa zawsze, z ograniczeniem wielkości pliku log_test

 * link do loga wyświetlany jest tylko w trybie test czyli gdy ip_test == ip
 *
 *
 *
*/

final class Test
{

 private static $marks;	// Benchmark timestamps
 private static $trace = array();	//-tablica trakera
 private static $error = array();	//-tablica błędów dla php

 /** cms/klasy/Test.php
 *
 * Set a benchmark start point.
 *
 * @param   string  benchmark name
 * @return  void
 */


 public static function start($name)
 {
  if(!defined('_MY_TIME')) return;
  if(_MY_TIME) return;

   //if(!C::ifTest()) return;  - Tu nie można, bo jeszcze nie istenieje ip_test -> config nie jest załadowany !!!

		if ( ! isset(self::$marks[$name]))
		{
			self::$marks[$name] = array
			(
				'start'        => microtime(TRUE),
				'stop'         => FALSE,
				'memory_start' => function_exists('memory_get_usage') ? memory_get_usage() : 0,
				'memory_stop'  => FALSE
			);
		}
 }

 /** cms/klasy/Test.php
 *
 * Set a benchmark stop point.
 *
 * @param   string  benchmark name
 * @return  void
 *
 */

 public static function stop($name)
 {
  if(!defined('_MY_TIME')) return;
  if(_MY_TIME) return;

		if (isset(self::$marks[$name]) AND self::$marks[$name]['stop'] === FALSE)
		{
			self::$marks[$name]['stop'] = microtime(TRUE);
			self::$marks[$name]['memory_stop'] = function_exists('memory_get_usage') ? memory_get_usage() : 0;
		}
 }

 /** cms/klasy/Test.php
 *
 * Get the elapsed time between a start and stop.
 *
 * @param   string   benchmark name, TRUE for all
 * @param   integer  number of decimal places to count to
 * @return  array
 */

 public static function get($name, $decimals = 4)
 {
  if(!defined('_MY_TIME')) return;
  if(_MY_TIME) return;

		if ($name === TRUE)
		{
		 $times = array();
		 $names = array_keys(self::$marks);

		 foreach ($names as $name)
		 {
		  $times[$name] = self::get($name, $decimals);  // Get each mark recursively
		 }

		 return $times;  // Return the array
		}

		if ( ! isset(self::$marks[$name]))
			return FALSE;

		if (self::$marks[$name]['stop'] === FALSE)
		{
			self::stop($name); // Stop the benchmark to prevent mis-matched results
		}

		// Return a string version of the time between the start and stop points
		// Properly reading a float requires using number_format or sprintf

		return array
		(
			'time'   => number_format(self::$marks[$name]['stop'] - self::$marks[$name]['start'], $decimals),
			'memory' => (self::$marks[$name]['memory_stop'] - self::$marks[$name]['memory_start'])
		);
 }

 /**
 *
 *
 *
 */

 public static function timeShow($name = TRUE)
 {
  if(!defined('_MY_TIME')) return;
  if(_MY_TIME) return;

  $variable = '';

  foreach(self::get($name) as $k => $w)
  {

	$variable .= '<p>[<b>'.$k.'</b>] <span ></span> time = <i>'.$w['time'].'</i> <span ></span> memory = <i>'.$w['memory'].'</i></p>';

  }

  if($variable)
  {

   return '
	<div id=\'adm_test_time\' class=\'tab\'>
	 <p class=\'adm_test_tem col0\'>### CZAS WYKONYWANIA: ###</p>
	 '.$variable.'
	</div>';
  }
  else
   return;

 }

 /** cms/klasy/Test.php
 *
 * nowe sledzenie skryptu
 * 2012-11-19 -> jest już nowsze ( to wychodzi ze stosowania )
 *
 * na koncu nazwa wartości i wartość
 *
 */

 public static function tracer($file, $method, $function, $line, $name = null, $value = null, $show = false, $stop = false)
 {
  if(!$show)
  {
   if(!defined('_MY_TEST')) return;
   if(_MY_TEST) return;
  }

   if(substr($file , 0, 9) === 'My trace:')
	 $file = '<u>My trace:</u> <i>'.basename($file).'</i>';
	else
    $file = '<i>'.basename($file).'</i>';


   if($method) $file .= ' -> <i>'.$method.'</i>';

   //if($function && $function != $method) $file .= ' (function)->'.$function;

   $file .= ' on line <i>'.$line.'</i>';


	if($name != null)
	 $name = ' -> tracking point : <i>'.$name.'</i>';
	else
	 $name = ' -> tracking point : <i>NULL</i>';


	if(is_array($value))
	{
    $name .= ' is Array';

	 self::$trace[] = array
	 (
	  'trace' => $file,
	  'name'  => $name,
	  'value' => $value
	 );

	}
	else
	{
	 if(is_bool($value))
	 {
	  if($value) $value = 'true'; else $value = 'false';
	 }

	 if($value != null)
	  $file .= $name.' = '.$value;
    else
	  $file .= $name.' = <i>NULL</i>';

    self::$trace[] = $file;
   }

	if($show)
	{
	 self::traceShow(true);

	 if($stop) exit('<br>END TRACER');
	}

 }

 /** cms/klasy/Test.php
 *
 * wyświetla znaczniki śledzenia wykonywania się kodu
 * $op = false -> nowa wersja / true = poprzednia
 *
 * działą tylko w trybie testowym
 *
 */

 private static function traceShow($opc = false)
 {

  if(!$opc)
  {
   if(!defined('_MY_TEST')) return;
   if(_MY_TEST) return;
  }

   ob_start();

    echo '
		 <div class=\'pre\'>';

		 foreach(self::$trace as $k => $v)
		 {
		  if(is_array($v))
		  {
			echo '<p>Trace -> <b>'.$k.'</b> -> '.$v['trace'].$v['name'].'</p>';

			if(is_array($v['value']))
			{
			 echo '<pre>';
			 print_r($v['value']);
			 echo '</pre>';
			}
			else
			 echo '<p>Trace -> <b>'.$k.'</b> -> '.$v['value'].'</p>';
		  }
		  else
		   echo '
			<p>Trace -> <b>'.$k.'</b> -> '.$v.'</p>';
       }
	 echo '
		 </div>';

	$variable = ob_get_contents();
	ob_end_clean();


	if(!$opc)
	{

    if($variable)
    {

     return '
		<div id=\'adm_test_tracer\'>
		 <p class=\'adm_test_tem col2\'>### TRACER ###</p>
		 '.$variable.'
		</div>';


    }
    else
     return;
   }
	else
	 echo $variable;
 }

 /**
 *
 * zapis błędów php do tablicy, błędów
 *
 */

 public static function errorSet($errno, $errstr, $errfile, $errline, $errcontext)
 {

 	 self::$error[] = array
	 (
	  'errno'   => $errno,
	  'errstr'  => $errstr,
	  'errfile' => $errfile,
	  'errline' => $errline,
	  'errcontext' => $errcontext
	 );

 }

 /**
 *
 * pobranie zawartości tablicy błędów do zapisu
 * ma działać zawsze
 *
 */

 private static function errorShow()
 {
   $p = array();

   foreach(self::$error as $k => $v)
	{
	 if(isset(self::$error['errcontext'])) $errcontext = '<br> ->'.self::$error['errcontext']; else $errcontext = '';


	 $p[] = $v['errno'].' : <b>'.$v['errstr'].'</b> in <i>'.basename($v['errfile']).'</i> on line <i>'.$v['errline'].'</i>'.$errcontext;

	 $errcontext = '';
	}

	if($p)
	{
	 return '
	<div id=\'adm_error_show\'>
	 <p class=\'adm_test_tem col1\'>### UWAGA! ###</p>
	 <p>'.implode('</p>
	 <p>', $p).'
	 </p>
	</div>';
	}
	else
	 return;
 }

 /**
 *
 * odczyt stałych do zapisu do loga
 *
 */

 private static function constShow()
 {
  if(!defined('_MY_TEST')) return;
  if(_MY_TEST) return;

  $variable = '';

  $t = get_defined_constants(true);

  foreach($t['internal'] as $k => $w)
   $variable .= '<p>[<b>'.$k.'</b>] <span ></span> <i>'.$w.'</i></p>';

  $variable .= '
  <p> ### USER ###</p>';

  foreach($t['user'] as $k => $w)
   $variable .= '<p>[<b>'.$k.'</b>] <span ></span> <i>'.$w.'</i></p>';

  if($variable)
  {
   return '
	 <div id=\'adm_test_const\' class=\'tab\'>
	  <p class=\'adm_test_tem col5\'>### DEFINED CONSTANT ###</p>
	  '.$variable.'
	 </div>';
  }
   else
    return;

 }

 /**
 *
 * funkcja formatująca - dla pre -> !!!! zamienić na rekurencję tak jak w public static function get
 *
 */

 public static function pre2($tab)
 {
  if(is_array($tab))
  {
   echo '
	<div class=\'pre\'>';

   foreach($tab as $k => $w)
	{
    echo '<p>['.$k.'] <b>=</b> <b>'.$w.'</b></p>';
	}
	echo '
	</div>';
  }
  else
   echo '
	<div class=\'pre\'>'.$tab.'
	</div>';
 }

 /**
 *
 * funkcja wyświetlająca formatowane tablice i obiekty -> !!! zamienić na rekurencję tak jak w public static function get
 *
 */

 public static function pre($tab)
 {
  if(is_array($tab))
  {
   echo '
	<div class=\'pre\'>';

   foreach($tab as $k => $w)
	{
	 if(is_array($w))
	  self::pre2($w);
	 else
     echo '<p>['.$k.'] <b>=</b> <b>'.$w.'</b></p>';
	}
	echo '
	</div>';
  }
  else
   echo '
	<div class=\'pre\'>'.$tab.'
	</div>';
 }

 /**
 *
 * zapis pliku log_test
 * typ ==  false to tryb testowy, każdy przebieg tworzy nowy a kasuje stary plik
 * typ == true zapisuje w trybie dopisywania, z ograniczeniem rozmiaru tworzonego pliku
 */

 private static function logZap($t, $typ = false, $name = './logs/log_test', $ext = 'php')
 {

  if(!$t) return;

  	 /*
     session_id($_GET[\''.md5('sdfasd').'\']);
	  session_start();
	  if($_GET[\''.md5('sdfasd').'\'] !== '.$__SESSION['test'].') exit;
	 */

  $z = '
  <?
	if(isset($_GET[\''.md5('sdfasd').'\']))
	{
	 if($_GET[\''.md5('sdfasd').'\'] !== \''.md5('sdsaadsdfasd').'\') exit;
	}
	else exit;
  ?>
  	<style type="text/css">
	 * {font: normal 13px/ 150% Arial; color: #000;}

	 div {margin: 1em; padding: 1em;}

	 .adm_test_tem {margin: 1em; padding: 1em; border-radius: 4px; background: #09C; color: #FFF; font-weight: bold;}

	 #adm_test_tracer {border: 1px solid #C3C;}
	 .col2 {background: #C3C;}

	 #adm_error_show {border: 1px solid red;}
	 .col1 {background: #F00;}

	 u, i, b {font-weight: bold; font-style: normal; text-decoration: normal;}
	 i {color: #30C;}
	 u {color: #F00;}
	 b {color: #666;}

	 #adm_test_time {border: 1px solid #999;}
	 .col0 {background: #999;}
	 .tab p b {display: inline-block; width: 30ex; text-align: right; padding: 0 1em;}
	 .tab p span {display: inline-block; padding: 0 2em;}

	 #adm_test_const {border: 1px solid #C90;}
	 .col5 {background: #C90;}
	</style>';


   if(!is_dir(_DOCROOT.'logs')) 											//-katalog na uploadowane pliki tymczasowe
	 $tmp_dir = mkdir(_DOCROOT.'logs', 0777);


	$t = iconv('utf-8', 'windows-1250', $t);

	if($typ)
	{

	 if(file_exists($name.'.'.$ext))
	 {
	  $size = filesize($name.'.'.$ext);
	 }

	 $h = fopen($name.'.'.$ext, 'w');
	}
   else //Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'logZap_trace -> file exists true |', $name.'.'.$ext, 1);
	{

	 $name .= '2';

	 if(file_exists($name.'.'.$ext))
	 {

	  $size = filesize($name.'.'.$ext);

	  if($size > (1024 * 200) )
	  {
	   $h = fopen($name.'.'.$ext, 'w');
	  }
     else
	  {
	   $z = '';
		$h = fopen($name.'.'.$ext, 'a');
	  }
	 }
	 else
	  $h = fopen($name.'.'.$ext, 'a');

	}

	$t = $z."\n\n".date("Y-m-d H:i:s", time()).' - file size = '.$size.$t;

   fputs($h, $t);

   fclose($h);

   unset($h, $t, $z, $typ, $size);
 }

 /**
 *
 * zbiera informacje testowe, błedy php i zapisuje do pliku
 * wyswietla link do pliko log'a
 *
 * op = false -> pojedyńczy przebieg, op = true -> przebiego podwójny, czyli akcja zakończona ponownym załadowaniem strony
 * op = true -> będzie kolejna porcja danych
 *
 */

 public static function testShow($op = false)
 {
	$ex = C::ifTest(); // testowanie czy tryb testowy

   //Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'testShow op?', $op);
	//if($test_Show1) $test_Show1 = 1;					//-widać ok.

   $ttt = '';
	$err = '';
	$trr = '';
	$tim = '';

   if(!$op)
	{

    if($_SESSION['test_err'])
	 {
	  $err = $_SESSION['test_err'];
	  $_SESSION['test_err'] = '';
	 }

    if($_SESSION['test_trace'] && $ex)									//-tylko  wtrybie testowym
	 {
	  $trr = $_SESSION['test_trace'];
	  $_SESSION['test_trace'] = '';
	 }

	 $err .= self::errorShow($op);

	 if($ex)
	 {
	  $trr .= self::traceShow($op);										//- ślad tracera

	  $ttt .= self::constShow();

	  $ttt .= C::showConfig();

	  $ttt .= C::komunSys();

     if($err)
	  {
	   $styl = 'style=\'background: red;\' ';
	   $komu = ' AND ERROR!';
	  }
	  else
	  {
	   $styl = '';
	   $komu = '';
	  }

     if($ttt || $err )
	   $link = '
		<a id=\'adm_test\' class=\'xxx\' href=\'./logs/log_test.php?'.md5('sdfasd').'='.md5('sdsaadsdfasd').'\' '.$styl.'target=\'_blank\' title=\'zobacz dane testowe'.$komu.'\'> TEST'.$komu.' </a>';
	 }


	 if(defined('_MY_TIME'))
     if($ex || !_MY_TIME)
	  {
	   self::stop('czas_razem');  											//-zatrzymanie globalnego zegara ( czas dla całego skryptu)
	   $tim = self::timeShow();
     }


	 self::logZap($tim.$err.$trr.$ttt, $ex);

    unset($tim, $err, $trr, $ttt, $ex);

	 return $link;
	}
	else // $op == true
	{
	 //Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'testShow op=true', $op);

	 if($err = self::errorShow())
	  $_SESSION['test_err'] .= $err.'
	 <p> ### The end of part ERROR ###</p>';


	 if(($trr = self::traceShow()) && $ex)			//!!!!!!! zapis + nawiasy inaczej dupa !!!
	  $_SESSION['test_trace'] .= $trr.'
	 <p> ### The end of part TRACE ###</p>';		// tylko w trybie testowym

	}

  unset($err, $trr, $ttt);

  return false;
  //if($test_Show) $test_Show = 1;					//-tego błędu nie widać !!
 }

 /**
 *
 * obługa błedów usera w której zaimplementowano nową funkcję śledzenia kodu - trace
 *
 */

 private static function trigger_my_error($level) 			//$name, $message,
 {

    $call = debug_backtrace();									//-Get the caller of the calling function and details about it

	 $call = next($call);

	 if(is_array($call)) $call = serialize($call);			//-Wydzielenie z debug_backtrace() drugiego elementu tablicy

	 trigger_error($call, $level);

	 //if($triggerMyError) $triggerMyError = 1;				//-widać ok.

 }

 /*
 *
 * funkcja śledzenia kodu - trace z 3 parametrami
 * $e = false : musi być false aby metoda została obsłużona, może słuzyć do deaktywowania punktów śledzenia
 * $name = nazwa punktu śledzenia : tylko string
 * $val = testowana wartość : może być tablicą
 *
 */

 public static function trace($name ='default_name', $val = '')
 {
  //if($trace_test) $trace_test = 1;						//-widać ok.

  if(!is_string($name)) return;

  if(!_MY_TR) self::trigger_my_error(E_USER_ERROR); 		//$name, $val, są pobierane z debug_backtrace() w trigger_my_error()
 }

} // End Class Test
?>
