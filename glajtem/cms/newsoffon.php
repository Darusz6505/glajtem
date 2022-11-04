<?
defined('_SYSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

// projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2010-06-15 ---UTF-8
// klasy dla silnika etvn & aleproste.pl  ---------------------- 2010-10-08

// wywołanie klasy Newslettera;

$r = new NewsletterOrg();

$fx .= $r->wynik();

unset($r);
?>