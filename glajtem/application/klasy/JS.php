<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* dodatki w javacript v. 1.3
*
* 2016-03-13
* 2016-01-03
* 2013-03-16 -> public static function jsEndOut() - do ładowanai skryptów za </html>
* 2013-02-13 -> poprawki Notice
* 2012-05-18 -> dodano : $sInsideDown
*
* 2011-11-23 -> 2012-05-10
*
*
*
*/

class JS
{

 private static $akcja;

 public static function jsHead()
 {
  //Test::trace(__FILE__, __CLASS__, __METHOD__, __FUNCTION__, __LINE__);

  self::$akcja = C::Get('akcja');

  $sInside = C::get('javascript', false);						//-skrypty i style dołanczane przez wtyczki
  $sInsideTop = C::get('javascript_top', false);
  $sInsideDown = C::get('javascript_down', false);
  $sCss = C::get('adcss', false);

  if(C::get('jo'))
  {
	$sAdmin = '
	<script type="text/javascript" src="cms/js/cms.js"></script>';
  }
  else
   $sAdmin = '';


 /* bez tego działa lepiej i linkuje miniaturki :)
  else
   $sAdmin = '';

	<link rel="image_src" href="http://'.$_SERVER['HTTP_HOST'].'/skin/mariusz.jpg" />

	<meta property="og:title" 		content="'.C::get('con_nazw', false).' '.C::get('seo', false).'" />
	<meta property="og:type" 		content="website" />
	<meta property="og:url" 		content="http://'.$_SERVER['HTTP_HOST'].'" />
	<meta property="og:image" 		content="http://'.$_SERVER['HTTP_HOST'].'/skin/mariusz.jpg" />
	<meta property="og:site_name"	content="'.C::get('con_desk', false).'" />
	<meta property="fb:admins"		content="168732949822522" />

	<link href=\'http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&subset=latin,latin-ext\' rel=\'stylesheet\' type=\'text/css\'>
   <script src="http://connect.facebook.net/pl_PL/all.js#xfbml=1" type="text/javascript"></script>
	*/
	  /*
	if(!C::get('localhost'))
	{
	 $test = explode('.', $_SERVER['SERVER_NAME']);

	 if(reset($test) != 'test')
	 {
	 }

	 unset($test);
   } */

 $tlos = self::$akcja;

 if($tlos == 'dolnoslaska-liga-paralotniowa')
  $tlo = '<script type="text/javascript" src="./application/js/dlp.start.js"></script>';
 else
  $tlo = '<script type="text/javascript" src="./application/js/glajt.start.js"></script>';

 Test::trace(__METHOD__ .' tlo ', $tlos);

 if(self::$akcja != 'testy'
 	&& self::$akcja != 'toplik'
	&& self::$akcja != 'loty'
	&& self::$akcja != 'tonosi'
	&& self::$akcja != 'noszenia'
	&& self::$akcja != 'igc')
 	$sInside .= '
 	<link rel="stylesheet" href="./application/supersized.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./application/supersized.shutter.css" type="text/css" media="screen" />
  	<script type="text/javascript" src="./application/js/jquery.easing.min.js"></script>
	<script type="text/javascript" src="./application/js/supersized.3.2.6.js"></script>
	<script type="text/javascript" src="./application/js/supersized.shutter.js"></script>
	'.$tlo.'
	<script type=\'text/javascript\' src=\'./application/js/fb.fanpage.js\'></script>';

   unset($tlo);

	$s = self::smartfon().'

	'.$sInsideTop.'
	<script type=\'text/javascript\' src=\'js/jq.js\'></script>
	<script type=\'text/javascript\'>var $j = jQuery.noConflict(); </script>
	<script type=\'text/javascript\' src=\'cms/js/moje.common.js\'></script>

	<link type=\'text/css\' rel=\'stylesheet\' href=\'style/tipTip.css\'  media=\'screen\' />
	<script type=\'text/javascript\' src=\'js/jquery.tipTip.js\'></script>

	<link type=\'text/css\' rel=\'stylesheet\' href=\'style/preLoader.css\'  media=\'screen\' />
	<script type=\'text/javascript\' src=\'js/preLoader.js\'></script>
	<script type=\'text/javascript\' src=\'js/moje.js\'></script>'.$sInside.'
	'.$sAdmin;


	if(!C::get('jo')) $s .= "
 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-28496570-2', 'glajtem.pl');
  ga('send', 'pageview');

 </script>";


  $s .= $sInsideDown;


  $s = '

  <script>
	document.write("<!-- xxxx -->");
  </script>

 <link rel="stylesheet" href=\'./application/'.C::get('con_styl').'.css\' media="screen and (min-device-width: 800px)" type="text/css" />
 <link rel="stylesheet" href=\'./application/h_'.C::get('con_styl').'.css\' media="only screen and (max-device-width: 799px)" type="text/css"/>

 <!-- nowsze androidy -->
 <link rel="stylesheet" href=\'./application/h_'.C::get('con_styl').'.css\' media="screen and (-webkit-device-pixel-ratio:0.75)" type="text/css"/>
 <link rel="stylesheet" href=\'./application/h_'.C::get('con_styl').'.css\' media="handheld" type="text/css" />
 <meta name="viewport" content="width=480, user-scalable=0" />'.$s.$sCss;

  return $s;
 }

 /**
 *
 *
 *
 */

 public static function jsBody()
 {
  if(self::$akcja != 'toplik'
  		&& self::$akcja != 'loty'
		&& self::$akcja != 'tonosi'
		&& self::$akcja != 'noszenia'
		&& self::$akcja != 'igc')
   return ' onload="handleResize()" onresize="handleResize()"';
 }

 /**
 *
 *
 *
 */

 public static function jsEnd()
 {
  return;

  $s = self::V_ofset(500);

  //return $s;

  //if(!C::get('jo'))

  //$s .= '
  //<script type="text/javascript">baner();</script>';

  return $s;
 }

 /**
 *
 * skrypty dołanczane na końcu za </html> do akcji asynchornicznych, np.reklamy, FB fanpage
 *
 */

 public static function jsEndOut()
 {
   return '
	<script type="text/javascript" src="./application/js/jq.fanpage.fb.js"></script>
	'.C::get('jsEndEnd', FALSE);



	//<script type="text/javascript" src="./application/js/flymet2.js"></script>';


	/*
	//document.getElementById("dmf").style.width="8000px";

	setTimeout("location.href='"+strona+"'", 2000);

   */

   /*return '
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
	<script type="text/javascript" src="./application/js/jq.reklama.down.js"></script>
	<script type="text/javascript" src="./application/js/jq.fanpage.fb.js"></script>'; */

	/*
   return '
	<script type="text/javascript" src="./js/jq.reklama.gl.js"></script>
	<script type="text/javascript" src="./js/jq.fanpage.fb.js"></script>
	<script type="text/javascript" src="./js/jq.reklama.js"></script>'; */
 }

 /**
 *
 * przesunięcie strony o stałą wartość w przapadku zafixowanych nagłówków
 *
 */

 private static function V_ofset($y = 100)
 {
  return;
  return '
	<script type="text/javascript">
	<!-- <![CDATA[

	function idz() {
	  if(window.pageYOffset)
	   t = window.pageYOffset;
	  else
	  {
	   t = document.body.scrollTop;

	   if(t == 0) t = document.documentElement.scrollTop;
	  }

	  y = t-'.$y.';

	  window.scrollTo(0,y);
	}

	setTimeout(\'idz()\',100);

	// ]]> -->
	</script>';

 }

 /**
 *
 *
 */

 private static function smartfon()
 {
  //return;

  return '
  <script type="text/javascript">


// Przechwytuje zdarzenie zmiany orientacji:

function handleResize() {



// Określa bieżącą orientację:

var orientation = "unknown";

var swidth = parseInt(screen.width);

var sheight = parseInt(screen.height);


if (swidth > sheight) {
	sorientation = "Landscape";
}
else {
	sorientation = "Portrait";
}

// Dodaje bieżące wymiary ekranu:

var sscreenSize = screen.width + " x " + screen.height;

// Aktualizuje informacje o orientacji dla użytkownika:

document.getElementById("smartfon").innerHTML = sorientation + ", " + sscreenSize;

}
</script>';


 }

}
?>