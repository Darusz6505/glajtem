<?


  $a = array('asd','asdas','asda');

  $b = array('', 'as', '');

  echo '<br>a = '.array_sum($a);
  echo '<br>b = '.array_sum($b);


  $sum = 0;
  $i = 0;

  while(isset($a[$i]))
  {
    if($a[$i++]) $sum++;
  }

  echo '<br>a = '.$sum;


  $sum = 0;
  $i = 0;

  while(isset($b[$i]))
  {
    if($b[$i++]) $sum++;
  }

  echo '<br>b = '.$sum;
?>
