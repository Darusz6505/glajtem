/*
* układanie elementów na stronie metodą złap i upuść
*
*
*/

    $j(function() {
      $j("#sortable").sortable({
        'handle': $j('.galerie'),
        'items': 'div.galerie',
        'forcePlaceholderSize': true,
        stop: function(event, ul) {
		    	
			 var rel = $j('#sortable').attr('rel');
			 
          var data_sort = [];
 
          $j('#sortable div.galerie').each(
            function(index) {
              data_sort[index] = $j(this).attr('id');
					
            }
          );
			 
			 //-komunikaty w oknie informacyjnym
			 $j("#sort_info").html('');
			 $j("#sort_info").append('<button id=\'start_sort\'>Zapisz aktualny układ</button>');

		//-ajax dopiero tutaj aby kazda zmaina pozycji elementu nie powodowała sorotowania tabeli
		$j("#start_sort").click(function () {
			 
			 data_sort.push(rel);
			 
          $j.ajax(
          {
              url: 'sort.php',
              type: 'POST',
              dataType: 'json',
              async: false,
              data: {"sort": data_sort},
 				  beforeSend: function(html){
               	$j("#sort_info").html('Sortowanie w toku ...');         
              },
				  success: function(html){	
              		$j("#sort_info").html('posortowane ok!'); 
				  },
				  error:	function(html){	
              		$j("#sort_info").html('coś poszło źle!');         
              },
				  complete:   function(html){	
              		$j("#sort_info").append(' :: akcja zakończona.');
              }
			
            }
          );
			 
		});
 	
        }
      });
 
      $j("#sortable").disableSelection();
		
    });