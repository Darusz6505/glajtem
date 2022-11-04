/*
* akcje javascript in front side to: aleproste.pl v.1.3
*
* 2012-12-14
*
*/

// document.cookie = "ciacho=test";

$j(function() {

  $j('[title]').tipTip({edgeOffset: 7});

  $j('a:not(#xxx, .xxx), [type=submit]').preLoader();

  $j(window).unload( function () { if($j('#preLoader').length > 0) $j('#preLoader').remove(); });
  //-kasuje preloader

});

