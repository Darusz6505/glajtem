<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/**
*
* klasa : prezentacja rekordów tabeli + elementy dodatkowe : ver.1.3
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2019-02-19 : poprawiono błąd dla id > 999 (obcinanych do zadanej długości w celu prezentacji na stronie)
* 2014-05-11 : poprawiony format tabeli
* 2012-11-21 : dodano kolimnę id
* 2012-04-21 : dodanie odnośnika edycji
*
* 2011-10-19 : poprawki qwerendy dla tabel powiązanych
* 2011-10-14 : modyfikacje dla tabeli powiązanych ( tylko dla jednego pola)
* 2010-12-04
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2008-09-12-- UTF-8
* skrypt nie jest darmowy!!
* aby legalnie wykorzystywać skrypt należy posiadać wykupioną licencję lub sgodę autora
*/

class RekTabeli
{

 private $w = ''; 		 	//-wynik działania klasy
 private $error = '';		//-komunikaty błędów

 private $n = array();
 private $nx = array();
 private $nn = array();
 private $t = '';				//-nazwa tabeli

 private $ty = array();
 private $tyt = array();
 private $dl = array();
 private $kon = array();	//-tablica na powiązania

 private $sortowanie = '';
 private $dodCol = '';
 private $order = '';

 /*
 @
 */

 function __construct($n, $nn, $t)
 {
  //Test::tracer('lista_construct', $t);

  unset($_SESSION['id-err']); //-skasowanie znacznika powrotu

  if(isset($_POST['sortuj']))  $this->sortowanie = C::odbDane($_POST['sortuj']); else $this->sortowanie = false;
  if(isset($_POST['dod_col'])) $this->dodCol = C::odbDane($_POST['dod_col']); 	else $this->dodCol = false;
  if(isset($_POST['order']))   $this->order = C::odbDane($_POST['order']); 		else $this->order = false;

  if($this->sortowanie == '-wybierz-') $this->sortowanie = false;		//-sortowanie
  if($this->dodCol == '-wybierz-') $this->dodCol = false;				//-dodatkowa kolumna


  if($n)  $this->n = $n; else $this->error = 'Brak tabeli n';
  if($nn) $this->nn = $nn;	else $this->error = 'Brak tabeli nn';
  if($t)  $this->t = $t;	else $this->error = 'Brak nazwy tabeli';

  $i = 3;																	//-pętla po wszystkich istotnych polach tabeli, w poszukiwaniu znacznika L

																				//-RUSZA tylko od 3 !!!

  while(isset($this->n[$i]))
  {

	if(isset($this->nn[$i]['L']))
	{
	 $this->ty[]  = $this->n[$i];													   //-tablica nazw pól tabeli

    $this->tyt[] = $this->nn[$i][0];											   //-tablica nagłówków (opisu z definicji)

	 $this->dl[] = $this->nn[$i]['L'];												//-tablica znaczników L;xx

	 if(isset($this->nn[$i]['UB'][3]))												//-tablica powiązań
	 {
	  $this->kon[] = $this->nn[$i]['UB'][1];
	  $this->kon[] = $this->nn[$i]['UB'][2];
	  $this->kon[] = $this->nn[$i]['UB'][3];
	  $this->kon[] = $this->n[$i];
	 }

	}

   $i++;
  }
  unset($i);

  $this->kon = array_pad($this->kon, 4, '');

  //Test::tracer('kon', $this->kon);

  array_unshift($this->dl, 8);
  array_unshift($this->tyt, 'id');
  array_unshift($this->ty, $this->n[2]);

  if(!$this->error) $this->listaRek();
 }

 /**
 *
 * menu : lista pól
 * pole select do wybrania kolumny do sortowania
 *
 */

 private function listaPol()
 {

  $select1 = '';
  $select2 = '';

  $licz = count($this->dl);

  for($i=0; $licz>$i; $i++)
  {
   if($this->sortowanie == $this->ty[$i])
	 $sel = 'selected=\'selected\'';
	else
	 $sel = '';

   $select1 .= '
	<option value=\''.$this->ty[$i].'\' '.$sel.'>'.$this->tyt[$i].'</option>';
  }

  for($i=0; $licz>$i; $i++)
  {
   if($this->dodCol == $this->ty[$i])
	 $sel = 'selected=\'selected\'';
	else
	 $sel = '';

   $select2 .= '
	<option value=\''.$this->ty[$i].'\' '.$sel.'>'.$this->tyt[$i].'</option>';
  }


  if($select1 && $select2)
  {
   $select1 = '
	<option >-wybierz-</option>'.$select1;

	$select2 = '
	<option >-wybierz-</option>'.$select2;

	if($this->order) $check = 'checked=\'checked\''; else $check  = '';

   $wt = '
<form class=\'sel_rekord\' action=\'cms.cmsl\' method=\'post\'>
 <b>Sortuje według : </b>
 <select name=\'sortuj\'>'.$select1.'
 </select>
 <label id=\'label_sort\' for=\'order_sort\'>Malejąco <input type=\'checkbox\' name=\'order\' '.$check.' id=\'order_sort\'></label>
 <b>Dodaj kolumnę : </b>
 <select name=\'dod_col\'>'.$select2.'
 </select>
 <input type=\'hidden\' name=\'a\' value=\'lista\' />
 <input type=\'hidden\' name=\'t\' value=\''.$this->t.'\' />
 <input type=\'submit\' value=\'Wyświetl\'>
</form>';

  }

  unset($select1, $select2, $i);

  return $wt;
 }

 /**
 *
 *
 *
 *
 * UWAGA!!! powiązanie zrobione tylko dla jednego pola !!!
 */

 private function listaRek()
 {

  $i = count($this->dl); 														//-ilość elementów opisujących rekord (ilość kolumn tabeli)

  if($this->sortowanie)
	$sort = $this->sortowanie;													//-wybrane sortowanie
  else
  {
   $sort = $this->n[2]; 														//-standardowo po id malejąco
	$order = ' DESC';
  }

  if($this->order) $order = ' DESC';

  if($this->t == C::get('tab_admini')) $ex_war = "admin_stat <= '{$_SESSION['admin']['status']}'"; else $ex_war = false;
  //-dla tabeli adminów tylko rekordy o statusie niższym, lub takim samym jak status zalogowanego

  if(array_sum($this->kon))
  {
    // $this->n[2] 	-> nazwa pola indeksowego aktualnej tabeli
	 // $this->kon[0]	-> nazwa tabeli z kótórej pochodzą dane
	 // $this->kon[1]	-> nazwa pola indeksowego tabeli z której pochodzą dane
	 // $this->kon[2]	-> nazwa pola z którego odczytywane są dane na podstawie indeksu
	 // $this->kon[3]	-> nazwa pola aktualnej tabeli, którego dotyczy podmiana


	$tab = "SELECT *, {$this->kon[2]} FROM $this->t
			  LEFT JOIN {$this->kon[0]}
			  ON {$this->kon[3]} = {$this->kon[1]}
			  ORDER BY $sort $order";

  }
  else
  {

	if($ex_war) $ex_war = ' WHERE '.$ex_war;

   $tab = "SELECT * FROM $this->t $ex_war ORDER BY $sort $order";

   unset($ex_war);
  }


  try
  {
   if($tab = DB::myQuery($tab))
   {
	 $wt = '';

    while($ta = mysqli_fetch_array($tab))
    {
	  $id_temp = $odn = '';


     for($j=0; $i>$j; $j++)
	  {
	   if($this->ty[$j] == $this->kon[3]) $this->ty[$j] = $this->kon[2]; 		//dla wartości indeksowanych z innej tabeli

	   if($this->dl[$j])
		{
		 if(!is_array($this->dl[$j]))
		  $width = $this->dl[$j];
		 else
		  $width = $this->dl[$j][0];

		 $styl = ' style=\'width:'.($width-2).'ex;\'';

		 if($this->ty[$j] == $this->n[2]) $id_temp = $ta[$this->ty[$j]];

		 $ta[$this->ty[$j]] = $this->zaja((int)$width-3, $ta[$this->ty[$j]]);

		}

      $odn .= '<b'.$styl.'>'.$ta[$this->ty[$j]].'</b>';

		unset($styl, $width);
	  }


     $wt .= '
	<li id=\'re'.$id_temp.'\'><label for=\'c'.$id_temp.'\'><input id=\'c'.$id_temp.'\' type=\'checkbox\' name=\'obs_zam['.base64_encode(serialize($id_temp)).']\' /></label><a href=\''.S::linkCode(array($this->t,$id_temp,'edycja', '', 'lista','re')).'.htmlc\' title=\'edytuj rekord '.$id_temp.'\'>'.$odn.'</a>
	 <a class=\'del\' href=\''.S::linkCode(array($this->t,$id_temp,'kasuj')).'.htmlc\' title= \'kasuj rekord\' alt=\'napradę chcesz skasować, ta operacja jest nie odwracalna!\'><img src=\'./cms/skin/ico_del.png\' alt=\'kasuj\'></a>
	 <a class=\'edi\' href=\''.S::linkCode(array($this->t,$id_temp,'pokaz')).'.htmlc\' title=\'kliknij aby wyświetlić szczegóły\'><img src=\'./cms/skin/ico_edit.png\' alt=\'edit\'></a>
	</li>';

     unset($odn);

    }

	 $fx = '';
	 $hed = '';

	 if($wt)																				//-utworzenie wiersza nagłówkowego
	 {

	  for($j=0; $i>$j; $j++)
	  {
	   if($this->dl[$j])
		{
		 if(!is_array($this->dl[$j]))
		  $width = $this->dl[$j];
		 else
		  $width = $this->dl[$j][0];

	    $styl = ' style=\'width:'.((int)$width-2).'ex;\'';

		 $tytul = $this->zaja(((int)$width-3), $this->tyt[$j]);			//-zajawka tytułu jeśli nie zmieści się cały

       $hed .= '<b'.$styl.' title=\''.$this->tyt[$j].'\'>'.html_entity_decode($tytul).'</b>';

		 unset($styl, $tytul);
		}

	  }

	  $hed =  '<label for=""><input type=\'checkbox\' /></label>'.$hed;
	  /*

	  $hed =  '<label for=""><input type=\'checkbox\' /></label>'.$hed.'<b class=\'noborder\' title=\'kasuj\'><img src=\'./cms/skin/ico_del.png\' alt=\'kasuj\'></b><b class=\'noborder\' title=\'edytuj\'><img src=\'./cms/skin/ico_edit.png\' alt=\'edytuj\'></b>';


	  	  $hed =  '<label for=""><input type=\'checkbox\' /></label>'.$hed.'<b title=\'kasuj\'><img src=\'./cms/skin/ico_del.png\' alt=\'kasuj\'></b><b title=\'edytuj\'><img src=\'./cms/skin/ico_edit.png\' alt=\'kasuj\'></b>';	 */

	  $wt = '<p>'.$hed.'</p>'.$wt;


	  $fx .= '
<script type=\'text/javascript\'>

function mirror()
{
  d=document.order;
  for (i=0;i<d.elements.length;i++) {
	 if (d.elements[i].type==\'checkbox\')
	 {
      if (d.elements[i].checked!=true)
		 d.elements[i].checked=true;
      else
		 d.elements[i].checked=false;
    }
  }
}

function zaznacz()
{

  d=document.order;
  for (i=0;i<d.elements.length;i++) {
	 if (d.elements[i].type==\'checkbox\')
	 {
		 d.elements[i].checked=true;
    }
  }
}

function cclear()
{
  d=document.order;
  for (i=0;i<d.elements.length;i++) {
	 if (d.elements[i].type==\'checkbox\')
	 {
      d.elements[i].checked=false;
    }
  }
}

</script>

	   <form name=\'order\' action=\'\' method=\'post\'>
		 <ul id=\'cms_lista\'>'.$wt.'
		 </ul>

		 <div id=\'rek_ster\'>
		 <input type=\'button\' value=\'wyczyść wszystko\' onclick=\'cclear()\' />
		 <input type=\'button\' value=\'zaznacz wszystko\' onclick=\'zaznacz()\' />
		 <input type=\'button\' value=\'odwróć wszystko\' onclick=\'mirror()\' />
		 <input type=\'submit\' value=\'zatwierdź\'>
		 </div>
		</form>';
 	 }
	 else
     $fx .= '
		<p class=\'error\'>BRAK REKORDÓW W TABELI - NOWA LISTA</p>';

	 unset($tab, $ta, $wt, $j, $width); //$nw, $ty, $tyt, $n, $nn

   }
  }
  catch(Exception $e)
  {
	$this->w .=  C::debug($e, 0);
  }


  $this->w .= $fx;

 }

 /**
 *
 *
 *
 */

 private function zaja($limit, $t)						//-zajawka wiekszego tekstu :: 2011-03-25
 {
  //-limit = limit znaków
  if(strlen($t) > $limit)  								//-jeśli tekst źródłowy jest dłuższy od limitu
  {

	$tmp = substr($t, 0, $limit);							//-przycinamy tekst do zadanego limitu

	$tmp = substr($tmp, 0 , strrpos($tmp, ' '));		//-przycinamy po raz kolejny do ostatniej spacji

	while(substr($tmp, -2, -1) == ' ')					//-jeśli przed ostatni znak to spacja, przycinamy jeszcze o 2 znaki
	{
	 $tmp = substr($tmp, 0, -2);							//-powtarzamy aż do skutku dla np. dlatego i w takich ...
	}

	if($tmp)
    return $tmp.'...';
	else
	 return C::substrText($t, 0, $limit).'...';

  }
  else
   return $t;

 }

 /**
 *
 *
 *
 */

 public function wynik()
 {

  if($this->error) return '
	<p class=\'error\'>'.$this->error.'</p>';
  else
   return array($this->listaPol(),'
	<div id=\'cms_tabela\'>'.$this->w.'
	</div>');

 }

 /**
 *
 *
 */

 function __destruct()
 {
  //unset();
 }

}
?>