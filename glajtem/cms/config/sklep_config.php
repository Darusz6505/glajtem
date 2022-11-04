<?
defined('_SKLEPPATH') or header('HTTP/1.1 404 File Not Found');

/*
@ Głowna klasa projektu 
*
*
*
*
*/


    $m = array
	 (
	  'wyloguj.cmsl'  => 'WYLOGUJ;wylogowanie ze strony administratora;;;1',
	  'sklep+cms.smsl'=> 'SKLEP;obsługa sklepu',
	  $l[1]           => 'DO SERWISU;powrót do serwisu, widok użytkownka'
	 );	
	
 	 $sklep = array
 	 (
 		'sklep+zamowienia.smsl'=>'ZŁOŻONE; lista złożonych zamówień oczekujacych na powiadomienie;;' ,
		'sklep+dorealizacji.smsl'=>'DO REALIZACJI;zamówienia zarejstrowane, przyjęcie do realizacji;;' ,
		'sklep+wrealizacji.smsl'=>'W REALIZACJI;zamówienia w realizacji, zmiana na gotowe;;' ,
		'sklep+zaplata.smsl'=>'ZAPŁATA;przyjecie zaplaty za zamówienie;;' ,
		'sklep+gotowe_odbior.smsl'=>'GOTOWE DO ODBIORU;Zakończenie transakcji;;' ,
		'sklep+gotowe_wysylka.smsl'=>'GOTOWE WYSŁANE;Zakończenie transakcji;;' ,
		'sklep+zrealizowane.smsl'=>'ZREALIZOWANE;podgląd zakończonych zamówień;;' ,
		'sklep+archiwum.smsl'=>'ARCHIWUM;podgląd archiwizowanych zamówień;;' ,
		'sklep+cennik.smsl'=>'CENNIK;cennik towarów;;' ,
		'sklep+promocja.smsl'=>'PROMOCJE;promocje na stronie głównej;;'
 	 );	

?>