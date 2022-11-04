<?
defined('_APATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/**
*
* formularz kontaktowy :: v.1.5 (PL-DE-EN)
*
* 2012-12-04 -> trzeci język i wersje narodowe walidacji javascript
* 2012-12-02 -> dodany parametr skoko do strony docelowej po prawidłowym wysłaniu formularza
* 2012-12-01 -> dodany parametr do walidacji
* 2012-11-28 -> doodanie parametru tekstu wymuszonego = wstęnie wypełnione pole opisu ( textarea )
*
* ... 2011-05-27 -> 2011-08-22:(korekta tłumaczenia na niemiecki)
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-03-17 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

class Form_Kontakt
{
 private $spam = array('Sex', 'sex', 'http:', ' Metko ');

 protected $f = array();			//-tablica formularzy
 protected $w = array();			//-tablica walidacji

 private static $k = array();		//-tablica komunikatów

 protected $a;							//-akcja dla formularza
 protected $name = ''; 				//-nazwa dla formularza
 protected $zach;						//-wymienny tekst zachęty

 protected $wynik = '';				//-pole zawierające wynik działania metod klasy
 protected $targ = '';				//-adres docelowy po poprawnym przesłaniu wiadomości

 /**
 *
 */

 function __construct($a, $n = '', $z = '', $text = '', $target = '')
 {

  if($text) $this->f['ftext'] = $text;			//-wymuszony tekst do pola textarea / opisu
  if($target) $this->targ = $target;			//-adres docelowy

  self::lang();										//-wersja językowa

  $this->a = $a;										//-akcja

  if($n) self::$k['form_nazwa'] = $n;			//-nazwa dla formularza  // if($n) $this->name = $n;

  if($n) $this->zach = $z;						//-zmienny element tekstu zachęty // if($n) $this->zach = $z;


  if(isset($_POST['send']))						//-jeśli został wysłany formularz to walidacja
   $this->walidacja();
  else
   $this->formularz();     						//-jeśli nie to wyświetla formularz

  unset($a, $n, $z, $text);

 }

 /**
 *
 *
 *
 */

 private static function lang()
 {

  switch(C::get('lang'))
  {

   case 'DE':

   self::$k = array
   (
	 'text_pocz'		=> 'Schreiben Sie hier bitte',
	 'text_kom'			=> 'Bitte f&#252;llen Sie das Formular!',
	 'text_kom0'		=> 'Fehler in der Form, bitte korrigieren und erneut.',
	 'text_kom1'		=> 'Bitte unterschreiben Sie die Nachricht.',
	 'text_kom2'		=> 'Geben Sie Bitte, Ihre E-mail oder Telefon.',
	 'text_kom3'		=> 'Die Rufnummer kann nur Zahlen und Symbole -()+ und Raum.',
	 'text_kom4'		=> 'E-Mail Adresse ist ung&#252;ltig!',
	 'text_kom5'		=> 'Kein Inhalt!',
	 'form_podpis'		=> 'Name',
	 'form_telefon'	=> 'Telefon',
	 'form_email'		=> 'Email',
	 'error_send'		=> 'Leider, aus technischen Gr&#252;nden, k&#246;nnen E-Mail zu diesem Zeitpunkt nicht gesendet werden.<b>Bitte erneut versuchen in einem Augenblick.</b>',
	 'fsubmit'			=> 'senden',
	 'z'					=> ', um ',
	 'send_ok' 			=> 'Danke! Deine Nachricht wurde an <span >'.C::get('adres_strony').'</span> geschickt worden.',
  	 'send_next' 		=> 'n&#228;chste Nachricht',
    'wiad_z_serwisu' => 'Nachricht von Webseite',
    'wyslana_przez' 	=> 'Geschrieben von',
 	 'data' 				=> 'Datum',
 	 'tresc' 			=> 'Nachricht',
	 'form_nazwa'		=> 'Formularz kontaktowy'
   );

   break;

	case 'EN':

	self::$k = array
   (

	 'text_pocz'			=> 'Here, please enter your message...',
	 'text_kom'		 		=> 'Please fill out the form',
	 'text_kom0'			=> 'Errors in form! - Please correct and resend.',
	 'text_kom1'			=> 'Please sign the message, name and / or last name, or nickname',
	 'text_kom2'			=> 'Enter your e-mail address or telephone number.',
	 'text_kom3'			=> 'The phone number must contain only numbers and characters -()+ and spaces.',
	 'text_kom4'			=> 'E-mail address is invalid.',
	 'text_kom5'			=> 'This message does not contain any content!',
	 'form_podpis'	  		=> 'First and / or last name',
	 'form_telefon'		=> 'Telephone',
	 'form_email'	  		=> 'E-mail',
	 'error_send'	=> 'Unfortunately, for technical reasons, mail can not be sent at this time. <b>Please try again in a moment.</b>',
	 'fsubmit'				=> 'send',
	 'z'						=> ', from ',
	 'send_ok' 				=> 'Thank you! Your message has been sent to <span >'.C::get('adres_strony').'</span>',
	 'send_next' 			=> 'next message',
	 'wiad_z_serwisu' 	=> 'Message from website',
	 'wyslana_przez' 		=> 'posted by',
	 'data' 			  		=> 'date',
	 'tresc' 				=> 'message',
	 'form_nazwa'			=> 'Contact form'
   );

	break;

   default:
   self::$k = array
   (
    'text_pocz'		=> 'Tu proszę wpisać swoją',
  	 'text_kom'			=> 'Prosimy wypełnić formularz.',
	 'text_kom0'		=> 'Błędy w formularzu! - prosimy poprawić i wysłać ponownie.',
	 'text_kom1'		=> 'Prosimy podpisać wiadomość, imię lub/i nazwisko lub nick.',
	 'text_kom2'		=> 'Prosimy podać e-mail lub telefon kontaktowy.',
	 'text_kom3'		=> 'Numer telefonu może zawierać tylko cyfry oraz znaki -()+ i spacje.',
	 'text_kom4'		=> 'Adres e-mail jest nieprawidłowy.',
	 'text_kom5'		=> 'Wiadomość nie zawiera treści!.',
	 'form_podpis'		=> 'Nazwisko i/lub Imię',
	 'form_telefon'	=> 'Telefon',
	 'form_email'		=> 'E-mail',
	 'error_send'		=> 'Ze wzgledów technicznych wysłanie wiadomości w tej chwili jest niemożliwe.<br />Przepraszamy i prosimy spróbować później.',
	 'fsubmit'			=> 'wyślij',
	 'z'					=> ' z ',
	 'send_ok' 			=> 'Dziękujemy! Twoja wiadomość do <span >'.C::get('adres_strony').'</span> została wysłana.',
  	 'send_next' 		=> 'następna wiadomość',
    'wiad_z_serwisu' => 'Wiadomość z serwisu',
    'wyslana_przez' 	=> 'Wysłana przez',
 	 'data' 				=> 'data',
 	 'tresc' 			=> 'Treść wiadomości',
	 'form_nazwa'		=> 'Formularz kontaktowy'
   );
  }

 }

 /**
 *
 *  formularz -> akcja dla formularza
 *
 */

 protected function formularz()
 {
  if($this->w)
  {
   foreach($this->w as $kl => $wart)
	 $this->w[$kl] = '<b class=\'er-wal\'>'.$wart.'</b>';  				//-do komunikatów walidacji dołącza kod HTML

	$_SESSION['id-err'] = 'fform'; 												//-przesuwa stronę do pola formularza
  }
  else
   unset($_SESSION['id-err']);


  if(!$this->f['ftext'])
  {
   $this->f['ftext'] = self::$k['text_pocz'].$this->zach;

   $kaspow = " onfocus=\"if(this.value == '".$this->f['ftext']."') this.value='';\"";
  }

  C::add('javascript', $this->script());										//-dynamiczne dodanie skryptu skalującego pole textarea


  $this->wynik = '
	<form id=\'fform\' action=\''.$this->a.'.html\' method=\'post\'>
	 <input type=\'hidden\' name=\'send\' value=\''.md5(time()).'\' />
    <input type=\'hidden\' name=\'starttext\' value=\''.self::$k['text_pocz'].$this->zach.'\' />
	 <p id=\'form_tyt\'>'.self::$k['form_nazwa'].' '.$this->w['ko_aut_error'].'</p>
	 <div id=\'textarea\'>
		<textarea id=\'ftext\' name=\'ftext\' '.$kaspow.'>'.$this->f['ftext'].'</textarea>'.$this->w['ftext'].'
	 </div>
	 <div >
	  <label for=\'inazw\'>'.self::$k['form_podpis'].':</label>
	  <input type=\'text\' name=\'fname\' id=\'inazw\' value=\''.$this->f['fname'].'\' />'.$this->w['fname'].'
	 </div>
	 <div >
	  <label for=\'itel\'>'.self::$k['form_telefon'].':</label>
	  <input type=\'text\' name=\'ffone\' id=\'itel\' value=\''.$this->f['ffone'].'\' />'.$this->w['ffone'].'
	 </div>
	 <div >
	  <label for=\'imail\'>'.self::$k['form_email'].':</label>
	  <input type=\'text\' name=\'fmail\' id=\'imail\' value=\''.$this->f['fmail'].'\' />'.$this->w['fmail'].'
	 </div>';

	 if(C::get('kod_cap')) 				//-opcjonalne zabezpieczenie graficzne
  	 {
     $r = new Kapusta;

	  $r->kodCap();						//-przygotowanie kodu

	  $this->wynik .= '
	  <div >'.Kapusta::KOD.$this->w['kod_cap'].'
	  </div>';
  	 }

	$this->wynik .= '
	 <div class=\'form_ster\'>
	  <input type=\'submit\' value=\''.self::$k['fsubmit'].'\' title=\''.self::$k['fsubmit'].self::$k['z'].C::get('adres_strony').'\'/>
	 </div>
	</form>';

 }

 /**
 *
 * filtr spamu
 *
 */

 protected function filtr($t)
 {
  $e = 0;

  foreach($this->spam as $w)
  {
   $f = explode($w, $t);

	if(count($f) > 1)
	 $e++;

	unset($f);
  }

  return $e;
 }

 /**
 *
 * walidacja danych po stronie serwera
 *
 */

 protected function walidacja()
 {

  if($_POST['ftext'])
  {
	$this->f['ftext'] = C::odbDane($_POST['ftext']);

	if($this->f['ftext'] == self::$k['text_Pocz'].$this->zach) $this->f['ftext'] = '';
  }

  if($_POST['fname']) $this->f['fname'] = C::odbDane($_POST['fname']);

  if($_POST['ffone']) $this->f['ffone'] = C::odbDane($_POST['ffone']);

  if($_POST['fmail']) $this->f['fmail'] = C::odbDane($_POST['fmail']);

  if($this->f['ftext'] || $this->f['fname'] || $this->f['ffone'] || $this->f['fmail']) 	  			//-walidacja tylko jeśli są dane do walidacji
  {
	 if(!$this->f['ftext'] || $this->f['ftext'] == '')
	  $this->w['ftext'] = self::$k['text_kom5'];
	 else
	 {
	  if(($sp = $this->filtr($this->f['ftext'])) > 0) $this->w['ftext'] = 'spam = '.$sp; //-filtr spamu
	  unset($sp);
	 }



	 if(!$this->f['fname']) $this->w['fname'] = self::$k['text_kom1'];

	 if(!$this->f['ffone'] && !$this->f['fmail'])
	  $this->w['fmail'] = self::$k['text_kom2'];


	 if($this->f['ffone'] && !eregi('^\+*[ 0-9\(\)-]+$', $this->f['ffone']))
	  $this->w['ffone'] = self::$k['text_kom3']; 																	//-walidacja numeru telefonu


    /*
	 if($this->f['fmail'] && !eregi('^[0-9a-z_.-]+@([0-9a-z-]+(\.)+)+[a-z]{2,4}$', $this->f['fmail']))
	  $this->w['fmail'] = self::$k['text_kom4'];	   																//-walidacja adresu e-mail
	 */

	 if($this->f['fmail'] && C::walidEmail($this->f['fmail']))																				//-walidacja adresu e-mail
	  $this->w['fmail'] = self::$k['text_kom4'];


	 if(!$this->w && C::get('kod_cap')) 																				//-walidacja dla kodu graficznego
	 {
	  $r = new Kapusta;

	  if(!$r->kodCapWal('kod_cap')) $this->w['kod_cap'] = Kapusta::KOMUN1;
	 }

	 if($this->w) $this->w['ko_aut_error'] = self::$k['text_kom0'];
  }
  else
   $this->w['ko_aut_error'] = self::$k['text_kom'];


  if($this->w)
   $this->formularz();
  else
  {
	if(isset($_POST['send']) && $_SESSION['sended'] == $_POST['send'])
	{
    //$this->wynik .= Form_Kontakt::KOM_SEND;  								//-zabezpieczenie przed ponownym wysłaniem danych po odświewrzeniu strony

	 unset($this->f);

	 $this->formularz();																//-po komunikacie wyswietla pusty formularz
	}
   else
   {
    $_SESSION['sended'] = $_POST['send'];

	 $this->wyslij();
   }
  }

 }

 /*
 * - wysyłania wiadomości
 *

 'wiad_z_serwisu' => 'Wiadomość z serwisu'
 'wyslana_przez' => 'Wysłana przez'
 'data' => 'data'
 'tresc' => 'Treść wiadomości'

 *
 */

 protected function wyslij()
 {
  $tre_poczty_text = '
'.self::$k['wiad_z_serwisu'].'&nbsp;'.C::get('www').'
'.self::$k['wyslana_przez'].' :
'.$this->f['fname'].' | e-mail: '.$this->f['fmail'].' | tel: '.$this->f['ffone'].'
'.self::$k['data'].': '.date('d-m-Y h:i:s', time()).' | ip : '.$c['ip'].'

'.$this->k['tresc'].':
---------------------------------------------
'.$this->f['ftext'].'
---------------------------------------------
END';

  $tre_poczty_html = '
   <style type="text/css">
	body{background: #Ff9; padding: 10px;}
	h1 {font: bold 18px/160% Verdana;}
	.strona {width: 650px;}
	.p {font: normal 13px/160% Verdana;}
	</style>
	<div class="strona">
	<h1>'.self::$k['wiad_z_serwisu'].'&nbsp;'.C::get('www').'</h1>
	<p>'.self::$k['wyslana_przez'].' :'.$this->f['fname'].' | e-mail: '.$this->f['fmail'].' | tel: '.$this->f['ffone'].'</p>
	<p>'.self::$k['data'].': '.date('d-m-Y h:i:s', time()).' | ip : '.C::get('ip').'</p>
	<p>'.self::$k['tresc'].':</p>
	<hr>
	<p>'.$this->f['ftext'].'
	</p>
	<hr>
	<p>END</p>
	</div>';

  if(!$this->f['fmail']) $this->f['fmail'] = $this->f['ffone'];

  $adresatMail = preg_replace('/\(\+\)|\(at\)/', '@', C::get('con_mail'));

  $meil = new EmailHtml();

  if(!C::get('localhost'))
  {
   $wys = $meil->mailSend
	 (
		self::$k['wiad_z_serwisu'].' '.C::get('adres_strony'),
		$this->f['fname'],
		$this->f['fmail'],
		$tre_poczty_text,
		$tre_poczty_html,
		'',
		$adresatMail
	 );
	}
	else
	 $wys = true;

  //[tytuł][nazwa nadawcy][e-mail nadawcy][treść][pliki][e-mail odbiorcy]

  unset($tre_poczty_text, $tre_poczty_html, $adresatMail);

  //$wys = true;

  if(!$wys)
  {
   if(C::get('ja')) $this->wynik .= '
		<p class=\'ja\'>Błąd wysyłania poczty e-mail! </p>
		<p class=\'ja\'>'.__FILE__.'->'.__METHOD__.'->'.__FUNCTION__.'->'.__LINE__.'</p>';

   $this->wynik .= '
	<p class=\'side_error\'>'.self::$k['error_send'].'</p>';
  }
  elseif($this->targ)
  {
	S::ggoto($this->targ);
  }
  else
   $this->wynik .= '
	<p class=\'send_ok\'>'.self::$k['send_ok'].'</p>
	<a class=\'send_next\' href=\'./kontakt.html\'>'.self::$k['send_next'].'</a>';							//-jeśli e-mail został wysłany prawidłowo


  $this->wynik = '
  <div id=\'fform\'>'.$this->wynik.'</div>';

  $_SESSION['id-err'] = 'fform';											//-przesunięcie widoku na komunikat

  unset($wys, $mail);
 }

 /**
 *
 *
 */

 private function script()
 {

  switch(C::get('lang'))
  {

   case 'DE':
	$script = '
	<script type="text/javascript" src="./js/form.kontakt.walid.de.js"></script>';
	break;

	case 'EN':
	$script = '
	<script type="text/javascript" src="./js/form.kontakt.walid.en.js"></script>';
	break;

	default:
	$script = '
	<script type="text/javascript" src="./js/form.kontakt.walid.pl.js"></script>';
  }

  return '
	<script type="text/javascript" src="./cms/js/jquery.autogrow.js"></script>'.$script;

 }

 /**
 *
 * przekazanie wyniku działania metod klasy
 *
 */

 public function wynik()
 {
  return $this->wynik;
 }

 /**
 *
 * dstruktor
 *
 */

 function __destruct()
 {

 }
}
?>