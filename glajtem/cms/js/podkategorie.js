/*
* v.1.0
*
* 2011-11-02 : listy powiązane, podkategorie
*
* Dariusz Golczewski - 2011-11-02
* 
* http://blog.piotrnalepa.pl/2011/08/03/jqueryzalezne-listy-rozwijane-z-wykorzystaniem-jquery/
*/

//select[name=ofe_podk]


jQuery.fn.selectBind = (function($){ 
 
   return function(opcje) { 

    var ust = $.extend({ 
	 	bind: 'select[name=ofe_podk]',
		skrypt: './cms/podkategorie.php?id='
    }, opcje); 
	 
	 
    //przypisanie akcji wywołania dodatkowej listy do zdarzenia typu change
	 
    $(this).live('change', function(){
	 
        var id = $(this).val();
				
			 
        //adres url do pliku PHP z kodem generującym dane w formacie JSON
        var url = ust.skrypt+id;
 
        //jeśli istnieje już select-lista o id: podkategorie, to usuń ją
        if($(ust.bind).length>0)
         $(ust.bind + ' option').remove();
        
        //metoda pobierająca dane JSON z podanego adresu w zmiennej url
        $.getJSON(
            url,
            function(data){
 
                //tworzymy nową, pustą listę select o id: podkategorie i ją dołączamy do formularza
                //select = '<select id="podkategorie"></select>';
                //$('#formularz fieldset').append(select);
					 
                //var lista = $('#podkategorie');
					 
					 var lista = $(ust.bind);
 
                //ukrywamy listę. Potrzebne to będzie do uzyskania animacji pojawienia się elementu na stronie
                //lista.hide();
 
                //generowanie kolejnych opcji listy
                $.each(data, function(key, val){
                    var option = $('<option/>');
                    option.attr('value', key)
                          .html(val)
                          .appendTo(lista);
                });
 
                //animacja pojawienia się elementu na stronie
                //lista.show('scale', 500);
            },
            'json'
        );
    });
	 
	 return this.each(function() {});
	 
  }; 
})(jQuery); 
 