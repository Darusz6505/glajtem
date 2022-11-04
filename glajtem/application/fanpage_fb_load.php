<?

 /* zabezpieczenie !*/
 /* pobranie konfiguracji aby uruchomić S:reklama */
 //$reklama = S::reklama('750x100')

 //$reklama = file_get_contents('http://aleproste.pl/afilo.php?afilo=projekt.etvn.pl_750x100');

 if(isset($_GET['fb_size']))
  list($x, $y) = explode('x', $_GET['fb_size']);
 else
 {
  $x = 403;
  $y = 350;
 }

 echo '<iframe src="http://www.facebook.com/plugins/likebox.php?id=773258886053271&amp;width='.$x.'&amp;connections=49&amp;stream=false&amp;header=false&amp;height='.$y.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$x.'px; height:'.$y.'px;" allowTransparency="false"></iframe>';

?>
