jQuery.fn.fbhover = (function($){

 return function(opcje) {

  var ust = $.extend({
   }, opcje);


  return this.each(function() {

	var hov = true;

   $(this).hover(function(){
    if(hov)
	 {
	  $(this).css({'width':'8px'});
	  hov = false;
	 }

	 $(this).animate({'width': '440px'}, 500, function(){ }).clearQueue();

   }, function(){

	$(this).animate({'width': '8px'}, 500, function(){}).clearQueue();

   });
  });
 };

})(jQuery);

$j(function() {

  $j('.fb').fbhover();

});
