<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/*
@ dodatki w javacript dla CMS'a
*
* 2011-08-31
*
*
*
*/


class JS_CMS
{

 public static function jsHead()
 {

  //$sInside = C::get('javascript', false);		//-skrypty dołanczane przez wtyczki

  return '
	<script type="text/javascript" src="./js/jq.js"></script>
	<script type="text/javascript">var $j = jQuery.noConflict(); </script>
	<script type="text/javascript" src="./cms/js/moje.common.js"></script>

	<script type="text/javascript" src="./cms/js/jquery.autogrow.js"></script>

	<link type=\'text/css\' rel=\'stylesheet\' href=\'./cms/styl/tipTip.css\'  media=\'screen\' />
	<script type="text/javascript" src="./js/jquery.tipTip.js"></script>

	<link type=\'text/css\' rel=\'stylesheet\' href=\'./cms/styl/preLoader.css\'  media=\'screen\' />
	<script type="text/javascript" src="./cms/js/preLoader.js"></script>

	<link type=\'text/css\' rel=\'stylesheet\' href=\'cms/styl/imgareaselect-default.css\'  media=\'screen\' />
	<script type="text/javascript" src="./cms/js/jquery.imgareaselect.pack.js"></script>

	<link type="text/css" rel="stylesheet" href="./cms/styl/imgzoom.css" />
	<script type="text/javascript" src="./cms/js/jquery.imgzoom.pack.js"></script>

	<script type="text/javascript" src="./cms/js/jquery.nowaWart.select.js"></script>

	<script type=\'text/javascript\' src=\'./cms/js/podkategorie.js\'></script>

   <link type=\'text/css\' rel=\'stylesheet\' href=\'./cms/styl/datePicker.css\' media=\'screen\' />
	<script type="text/javascript" src="./cms/js/datePicker.js"></script>
	<script type="text/javascript" src="./cms/js/moje.cms.js"></script>';
 }

 /*
 @
 */

 public static function jsBody()
 {
  return;
 }

 /*
 @
 */

 public static function jsEnd()
 {

  return self::V_ofset();

 }

 /*
 @
 */

 private static function V_ofset()
 {

  return '
 <script type="text/javascript">


function idz() {
  var t = 0;
  var y = 0;

  if(window.pageYOffset)
   t = window.pageYOffset;
  else
  {
   t = document.body.scrollTop;

   if(t == 0) t = document.documentElement.scrollTop;
  }

  y = t-100;

  window.scrollTo(0,y);
}

setTimeout(\'idz()\',100);

</script>';

 }

}


?>