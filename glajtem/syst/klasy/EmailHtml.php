<?
defined('_SYSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

// klasas wysyłania poczty finkcją mail PHP  ------------------- 2010-10-01
// projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2010-09-12 ---UTF-8

class EmailHtml
{
 private $odb_adres = '';				//-adres odbiorcy maila
 private $tytul = '';					//-tytuł (nagłowek) maila
 private $nad_nazwa = ''; 				//-nazwa nadaawcy
 private $nad_adres = '';				//-adres nadawcy
 private $tresc_wia = '';				//-treść tekstowa wiadomości
 private $tresc_wia_html = '';		//-treść html wiadomości
 private $pliki_zal = array();		//-pliki załącznika

 public function mailSend($tyt, $nad_naz, $nad_adr, $tresc_wi, $tresc_html, $pliki_za, $odb_adr)
 {
  //[tytuł][nazwa nadawcy][e-mail nadawcy][treść][pliki][e-mail odbiorcy]
	
  $this->tytul = $tyt;
  $this->nad_nazwa = $nad_naz;
  $this->nad_adres = $nad_adr;
  $this->tresc_wia = $tresc_wi;
  $this->tresc_wia_html = $tresc_html;	
  $this->pliki_zal =	$pliki_za;
  $this->odb_adres = $odb_adr;
	
  return $this->slijEmailHtml();	
 }
 
 //odbiorca, tytul, nad_naz, nadawca, tre_txt, tre_html, pliki
 
 private function slijEmailHtml()
 {
  $this->nad_adres = preg_replace('/\(\+\)/', '@', $this->nad_adres); 	//-naprawa adresu e-mail :: jeśli konieczna	
  $this->odb_adres = preg_replace('/\(\+\)/', '@', $this->odb_adres);	

  srand((double)microtime()*1000000);	
 
  $zn = md5(uniqid(rand()));

  //-definicja nagłówka
	
  //$na  = 'From: '.$this->nad_nazwa.' <'.$this->nad_adres.'>'."\n";
	
  $na  = "From: ".$this->nad_nazwa." <".$this->nad_adres.">\n";	
  $na .= "MIME-Version: 1.0\n";
  $na .= "Content-Type: multipart/alternative;\n";								//-oznacza maila w postaci alternatywnej, czyli text lub html
  $na .= "\tboundary=\"".$zn."\"";	
	
  $tr  = '--'.$zn."\n";
  $tr .= "Content-Type: text/plain;\n";
  $tr .=	"\tcharset=\"utf-8\"\n";
  $tr .= "Content-Transfer-Encoding: 8bit\n\n";
  $tr .= $this->tresc_wia;

  //-dodana wartość HTML
		
  $tr .= "\n\n--".$zn."\n";
  $tr .= "Content-Type: text/html;\n";
  $tr .= "\tcharset=\"utf-8\"\n";
  $tr .= "Content-Transfer-Encoding: 8bit\n\n";
	
  //$tr .= "Content-Transfer-Encoding: quoted-printable";	nie może być w 
  $tr .= $this->tresc_wia_html;
	
  /*
  if($this->pliki_zal) 																	//-jeśli są jakieś pliki
   foreach($this->pliki_zal as $plik) 
   {
	 if(file_exists($plik))
	 {
     $p = explode('|', $plik);
	
     $tr .= "\n\n--".$zn."\n";
     $tr .= 'Content-Type: '.$p[1].";\n";											//-typ mime pliku
     $tr .= 'Content-Disposition: attachment;'."\n";							//-załącznik
		
	  $tr .= 'Content-Disposition: inline	;'."\n";								//-w treści
	  
     $tr .= "\tfilename=\"".$p[2]."\"\n";											//-nazwa pliku
     $tr .= "Content-Transfer-Encoding: base64\n\n";;
	
     $ha = fopen($p[0],'r');															//-źródło pliku
     $da = fread($ha, filesize($p[0]));
     fclose($ha);
  
     $tr .= chunk_split(base64_encode($da));
	 
	 unset($ha, $da, $type);
    }

   } */

  $tr .= "\n\n--".$zn."--\n";			//-zakończenie maila
 
  unset($zn);
	
  //-wysłanie listu, do skutku z ograniczeniem do 10 prób;
  
  if(_LOCALHOST)
  {	
   do
   {
    $send = @mail($this->odb_adres, $this->tytul, $tr, $na);
    $li++;	
   }	
   while(!$send && $li<10);
  }
  else		
   $send = @mail($this->odb_adres, $this->tytul, $tr, $na);

  unset($tr, $na, $li, $pliki);	
	
  if($send)
   return true;
  else
   return false;
 }
 
 function __destruct() 
 {
  unset($odb_adres, $tytul, $nad_nazwa, $nad_adres, $tresc_wia, $pliki_zal);
 }
}
?>