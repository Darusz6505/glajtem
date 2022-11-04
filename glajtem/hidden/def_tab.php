<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* definicje tabel dla : rotgryf.pl ver: 3.0
*
* 2018-08-03 -> porawki tabel dla dpl 2018
* 2016-01-03 -> dpl 2016 nowa tabela
* 2013-06-06
* 2013-05-28
*
* definicje tabel MySQL
*
* 2013-04-18 -> zmiana tabeli dla plików nie będących zdjęciami
* 2013-01-04
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2008-09-12-- UTF-8
* skrypt nie jest darmowy!!
* aby legalnie wykorzystywać skrypt należy posiadać wykupioną licencję lub sgodę autora
*

	$this->p[$name[0]]['th'] [0] - x
	$this->p[$name[0]]['th'] [1] - y
	$this->p[$name[0]]['th'] [2] - jakość
	$this->p[$name[0]]['th'] [3] - czcionka (nr czcionki) i położenie znaku wodnego
	$this->p[$name[0]]['th'] [4] - format docelowy
	$this->p[$name[0]]['th'] [5] - preferencja formatu: H-Horizontal, V-vertical ( sprawia że zdjęcie pionowe jest traktowane jak poziome i odwrotnie)

	// 2013-06-07
	$this->p[$name[0]]['th'] [6] - kadrowane = 1, nie kadrowane = 0 ( dla plików graficznych )

*/

$tan[C::get('tab_reklama')] = "(
	rekl_id   	INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	rekl_stat   TINYINT UNSIGNED NOT NULL DEFAULT '0',			[pozycja na liście^L;4^S;X;0;30^H;sortowanie malejące]
	rekl_blok 	VARCHAR(1)  NOT NULL DEFAULT '',					[Blok widoczny tylko dla admina^W]
	rekl_dapu 	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data publikacji bloku^D]
	rekl_dado 	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data dodania bloku^D]
	rekl_nazw	VARCHAR(64) NOT NULL DEFAULT '',					[nazwa pola*^L;40^T;64]
	rekl_size   VARCHAR(32)	NOT NULL DEFAULT '',					[rozmiar szer x wys^S;20;750x200]
	rekl_kod		TEXT,														[kod reklamy^T;1000]
	rekl_fot0 	VARCHAR(30) NOT NULL DEFAULT '',					[grafika 0^ext;jpg^path;foty^th;L_,750,200,90,121,,,0]
	rekl_alt0   VARCHAR(255) NOT NULL DEFAULT '',				[tagi do alt^T;255]
	rekl_open	INT(11) UNSIGNED NOT NULL DEFAULT '0'			[licznik odsłon^L;16^B])ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


$tan[C::get('tab_definicje')] = "(
	def_id   	INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	def_name		VARCHAR(250) NOT NULL DEFAULT '',				[nazwa pola*^L;50^T;250]
	def_text		TEXT,														[wartość tekstowa^T;6000]
	def_bol		VARCHAR(1) NOT NULL DEFAULT '0',					[wartość logiczna^W]
	def_int		INT(11) UNSIGNED NOT NULL DEFAULT '0',			[wartość całkowita^I;6]
	def_float	FLOAT(6.2) NOT NULL DEFAULT '0.00'				[wartość rzeczywista^C;6])ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


$tan[C::get('tab_publikacje')] = "(
	pu_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	pu_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',				[pozycja na liście^L;4^S;X;0;30^H;sortowanie malejące]
	pu_blok VARCHAR(1)  NOT NULL DEFAULT '',						[rekord ukryty^L;4^W^H;widoczny tylko dla Admina]
	pu_dapu DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[data publikacji^D]
	pu_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[data dodania^D]
	pu_ogra VARCHAR(1)  NOT NULL DEFAULT '',						[warunek^V^W]
	pu_tytu VARCHAR(256) NOT NULL DEFAULT '',						[tytuł 1*^L;60^T;160]
	pu_stro VARCHAR(80) NOT NULL DEFAULT '',						[strona*^V^L;10^UB;40]
	pu_box  VARCHAR(30) NOT NULL DEFAULT '',						[box/kategoria*^V^L;10^UB;30]
	pu_kli0 INT UNSIGNED NOT NULL DEFAULT '0'  					[liczba odsłon^V^C;6^N]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//-bloki z treścią

$tan[C::get('tab_teksty')] = "(
	tr_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	tr_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',				[Pozycja na liście^S;X;0;30^H;priorytet bloku treści w publikacji]
	tr_idte INT(11) UNSIGNED NOT NULL DEFAULT '0',				[Identyfikator tematu^V^T;3^B]
	tr_blok VARCHAR(1)  NOT NULL DEFAULT '',						[Blok widoczny tylko dla admina^W]
	tr_dapu DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data publikacji bloku^D]
	tr_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data dodania bloku^D]
	tr_tytu VARCHAR(250) NOT NULL DEFAULT '',						[Tytuł bloku publikacji^T;250]
	tr_text TEXT															[Treść bloku publikacji*^L;80^T;6000]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


$tan[C::get('tab_testy')] = "(
	testy_id    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	testy_stat  TINYINT UNSIGNED NOT NULL DEFAULT '0',			[Pozycja na liście^S;X;0;30^H;priorytet bloku treści w publikacji]
	testy_blok  VARCHAR(1) NOT NULL DEFAULT '',					[Rekord widoczny tylko dla admina^W]
	testy_tryw  VARCHAR(1) NOT NULL DEFAULT '',					[Rekord trywialny^W]
	testy_nr	   TINYINT UNSIGNED NOT NULL DEFAULT '0',			[nr testu^L;5^C;2]
	testy_pyt   TEXT,														[pytanie*^L;40^T;300]
	testy_odp1  TEXT,														[odpow.1^L;30^T;300]
	testy_odp11 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.1 ok^W]
	testy_odp2	TEXT,														[odpow.2^L;30^T;300]
	testy_odp21 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.2 ok^W]
	testy_odp3	TEXT,														[odpow.3^L;30^T;300]
	testy_odp31 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.3 ok^W]
	testy_odp4	TEXT,														[odpow.4^L;30^T;300]
	testy_odp41 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.4 ok^W]
	testy_fot0  VARCHAR(40) NOT NULL DEFAULT ''					[Zdjęcie^ext;jpg;jpeg;gif;png^path;testy^th;m_,170,170,90;g_,420,300,90;L_,900,600,90,121]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


$tan[C::get('tab_test2')] = $tan[C::get('tab_test3')] = "(
	testy_id    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	testy_stat  TINYINT UNSIGNED NOT NULL DEFAULT '0',			[Pozycja na liście^S;X;0;30^H;priorytet bloku treści w publikacji]
	testy_blok  VARCHAR(1) NOT NULL DEFAULT '',					[Rekord widoczny tylko dla admina^W]
	testy_tryw  VARCHAR(1) NOT NULL DEFAULT '',					[Rekord trywialny^W]
	testy_nr	   TINYINT UNSIGNED NOT NULL DEFAULT '0',			[nr testu*^L;5^C;2]
	testy_name	TEXT, 		  											[nazwa testu^L;10^T;300]
	testy_pyt   TEXT,														[pytanie*^L;100^T;600]
	testy_odp1  TEXT,														[odpow.1^T;600]
	testy_odp11 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.1 ok^W]
	testy_odp2	TEXT,														[odpow.2^T;600]
	testy_odp21 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.2 ok^W]
	testy_odp3	TEXT,														[odpow.3^T;600]
	testy_odp31 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.3 ok^W]
	testy_odp4	TEXT,														[odpow.4^T;600]
	testy_odp41 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.4 ok^W]
	testy_praw  VARCHAR(1)  NOT NULL DEFAULT '',					[prawid.^T;5]
	testy_fot0  VARCHAR(40) NOT NULL DEFAULT ''					[Zdjęcie^ext;jpg;jpeg;gif;png^path;testy^th;m_,170,170,90;g_,420,300,90;L_,900,600,90,121]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

	//- [Foto 1^ext;jpg;gif;png^path;foty^th;m_,170,170,90;L_,800,600,90,121] ostatni człon dla L_ to kodowanie znaku wodnego:
	//- pierwsza cyfra to pozycja znaku wodnego
	//- druga cyfra to kąt obrotu
	//- trzecia cyfra to nr czcionki

//-zdjęcia do galerii startowej
$tan[C::get('tab_fots')] = "(
	fo_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	fo_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^S;X;0;30^H;priorytet bloku treści w publikacji]
	fo_kole TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^C;5]
	fo_idte INT(11) UNSIGNED NOT NULL DEFAULT '0',	[Identyfikator galerii^V^L;10^T;3^B]
	fo_blok VARCHAR(1)  NOT NULL DEFAULT '',			[Zdjęcie widoczne tylko dla admina^W]
	fo_dapu DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data publikacji zdjęcia^D]
	fo_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data dodania zdjęcia^D]
	fo_tytu VARCHAR(250) NOT NULL DEFAULT '',			[Tytuł zdjęcia^L;40^T;250]
   fo_fot0 VARCHAR(40) NOT NULL DEFAULT '',			[Zdjęcie*^ext;jpg;jpeg^path;foty^th;m_,170,170,90;g_,300,300,90;L_,900,600,90,121]
	fo_poz0 VARCHAR(1)  NOT NULL DEFAULT '4',			[Pozycja zdjęcia 3^S;X;0;9^H;pozycja zdjęcia względem tekstu]
	fo_opf0 VARCHAR(250) NOT NULL DEFAULT '',			[Opis zdjęcia i tagi^T;200^H;opis lub słowa kluczowe,albo opis średnik i słowa kluczowe]
	fo_kli0 INT UNSIGNED NOT NULL DEFAULT '0'	 		[Odsłony zdjęcia^V^B]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


//-zdjęcia przypięte z innej publikacji

$tan[C::get('tab_fotx')] = "(
	fo_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	fo_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^S;X;0;30^H;priorytet bloku treści w publikacji]
	fo_kole TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^C;5]
	fo_idte INT(11) UNSIGNED NOT NULL DEFAULT '0',	[Identyfikator galerii^V^L;10^T;3^B]
	fo_tytu VARCHAR(250) NOT NULL DEFAULT '',			[Tytuł zdjęcia^L;40^T;250]
   fo_fot0 VARCHAR(40) NOT NULL DEFAULT '',			[Zdjęcie*^L;40^T;40^path;foty^th;m_,170,170,90;g_,300,300,90;L_,900,600,90,121]
	fo_poz0 VARCHAR(1)  NOT NULL DEFAULT '4',			[Pozycja zdjęcia 3^S;X;0;9^H;pozycja zdjęcia względem tekstu]
	fo_opf0 VARCHAR(250) NOT NULL DEFAULT '',			[Opis zdjęcia i tagi^T;200^H;opis lub słowa kluczowe,albo opis średnik i słowa kluczowe]
	fo_kli0 INT UNSIGNED NOT NULL DEFAULT '0'	 		[Odsłony zdjęcia^V^B]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


//-zdjęcia do sesji
$tan[C::get('tab_fota')] = "(
	fo_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	fo_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^S;X;0;30^H;priorytet bloku treści w publikacji]
	fo_kole TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^C;5]
	fo_idte INT(11) UNSIGNED NOT NULL DEFAULT '0',	[Identyfikator galerii^V^L;10^T;3^B]
	fo_blok VARCHAR(1)  NOT NULL DEFAULT '',			[Zdjęcie widoczne tylko dla admina^W]
	fo_dapu DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data publikacji zdjęcia^D]
	fo_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data dodania zdjęcia^D]
	fo_tytu VARCHAR(250) NOT NULL DEFAULT '',			[Tytuł zdjęcia^L;40^T;250]
   fo_fot0 VARCHAR(40) NOT NULL DEFAULT '',			[Zdjęcie*^ext;jpg;jpeg^path;foty^th;m_,170,170,90;g_,300,300,90;L_,900,600,90,121]
	fo_poz0 VARCHAR(1)  NOT NULL DEFAULT '4',			[Pozycja zdjęcia 3^S;X;0;9^H;pozycja zdjęcia względem tekstu]
	fo_opf0 VARCHAR(250) NOT NULL DEFAULT '',			[Opis zdjęcia i tagi^T;200^H;opis lub słowa kluczowe,albo opis średnik i słowa kluczowe]
	fo_kli0 INT UNSIGNED NOT NULL DEFAULT '0'	 		[Odsłony zdjęcia^V^B]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//-zdjęcia do bloga
$tan[C::get('tab_fotb')] = "(
	fo_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	fo_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^S;X;0;30^H;priorytet bloku treści w publikacji]
	fo_kole TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^C;5]
	fo_idte INT(11) UNSIGNED NOT NULL DEFAULT '0',	[Identyfikator galerii^V^L;10^T;3^B]
	fo_blok VARCHAR(1)  NOT NULL DEFAULT '',			[Zdjęcie widoczne tylko dla admina^W]
	fo_dapu DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data publikacji zdjęcia^D]
	fo_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data dodania zdjęcia^D]
	fo_tytu VARCHAR(250) NOT NULL DEFAULT '',			[Tytuł zdjęcia^L;40^T;250]
   fo_fot0 VARCHAR(40) NOT NULL DEFAULT '',			[Zdjęcie*^ext;jpg;jpeg^path;foty^th;m_,170,170,90;g_,300,300,90;L_,900,600,90,121]
	fo_poz0 VARCHAR(1)  NOT NULL DEFAULT '4',			[Pozycja zdjęcia 3^S;X;0;9^H;pozycja zdjęcia względem tekstu]
	fo_opf0 VARCHAR(250) NOT NULL DEFAULT '',			[Opis zdjęcia i tagi^T;200^H;opis lub słowa kluczowe,albo opis średnik i słowa kluczowe]
	fo_kli0 INT UNSIGNED NOT NULL DEFAULT '0'	 		[Odsłony zdjęcia^V^B]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//-zdjęcia do bloga
$tan[C::get('tab_pliki')] = "(
	fo_id   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	fo_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^S;X;0;30^H;priorytet bloku treści w publikacji]
	fo_kole TINYINT UNSIGNED NOT NULL DEFAULT '0',	[Pozycja na liście^V^C;5]
	fo_idte INT(11) UNSIGNED NOT NULL DEFAULT '0',	[Identyfikator bloku treści^V^L;10^T;3^B]
	fo_blok VARCHAR(1)  NOT NULL DEFAULT '',			[Plik widoczny tylko dla admina^W]
	fo_dapu DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data publikacji pliku^D]
	fo_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[Data dodania pliku^D]
	fo_tytu VARCHAR(250) NOT NULL DEFAULT '',			[Tytuł pliku^L;40^T;250]
   fo_fot0 VARCHAR(40) NOT NULL DEFAULT '',			[plik*^ext;pdf;doc^path;pliki]
	fo_opf0 VARCHAR(250) NOT NULL DEFAULT '',			[Opis pliku i tagi^T;200^H;opis lub słowa kluczowe,albo opis średnik i słowa kluczowe]
	fo_kli0 INT UNSIGNED NOT NULL DEFAULT '0'	 		[pobrania pliku^V^B]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


$tan[C::get('tab_piloci20')] = $tan[C::get('tab_piloci19')] = $tan[C::get('tab_piloci18')] = $tan[C::get('tab_piloci')] = "(
	pil_id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[9]
	pil_blok    VARCHAR(1)  NOT NULL DEFAULT '0',	[Zablokowany^L;8^W^H;pilot zablokowany]
	pil_name		VARCHAR(100) NOT NULL DEFAULT '',	[Pilot*^L;50^T;100^H;identyfikacja pilota, nazwisko, imię, nick]
	pil_opis		VARCHAR(250) NOT NULL DEFAULT '',	[Opis pilota^L;30^T;200^H;krótki opis pilota]
	pil_glajt   VARCHAR(1) NOT NULL DEFAULT '',		[Klasa skrzydła^L;5^S;2;O;F;S^O; O-open, F-fan, S-sport^H;O-open, F-fan, S-sport]
	pil_xc		VARCHAR(100) NOT NULL DEFAULT '',	[XC id^L;30^T;200^H;adres na portalu XC]
	pil_xcc		VARCHAR(100) NOT NULL DEFAULT '',	[XCC id^T;200^H;adres na portalu XCC]
	pil_fot0 	VARCHAR(40) NOT NULL DEFAULT ''		[Zdjęcie^ext;jpg;jpeg^path;foty^th;m_,170,170,90;g_,300,300,90;L_,900,600,90,121]
) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


//rap_maj 	VARCHAR(2) NOT NULL DEFAULT '',							[majster*^L;20^U;30;".C::get('tab_majster').";majs_id;majs_name]

$tan[C::get('tab_dpl')] = "(
	dpl_id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[9]
	dpl_pilot	VARCHAR(3) NOT NULL DEFAULT '',							[pilot*^L;40^U;40;".C::get('tab_piloci').";pil_id;pil_name]
	dpl_data		DATE NOT NULL DEFAULT '0000-00-00',						[Data lotu^L;20^D]
	dpl_km		FLOAT(6.2) NOT NULL DEFAULT '0.00',						[trasa*^L;20^C;6^O; km^H;długość trasy]
	dpl_track	VARCHAR(150) NOT NULL DEFAULT ''							[track^L;20^T;150^H;link do tacka]
) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$tan[C::get('tab_dpl18')] = "(
	dpl_id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[9]
	dpl_pilot	VARCHAR(3) NOT NULL DEFAULT '',							[pilot*^L;40^U;40;".C::get('tab_piloci18').";pil_id;pil_name]
	dpl_stref   VARCHAR(1)  NOT NULL DEFAULT '0',						[Strefy^L;8^W^H;naruszenie stref]
	dpl_data		DATE NOT NULL DEFAULT '0000-00-00',						[Data lotu^L;20^D]
	dpl_km		FLOAT(6.2) NOT NULL DEFAULT '0.00',						[trasa*^L;20^C;6^O; pkt^H;przyznane punkty]
	dpl_fai		VARCHAR(3) NOT NULL DEFAULT '',							[km or pkt^L;5^S;3;km;pkt^O; typ FAI^H;trójkąty czy przelot otwarty]
	dpl_track	VARCHAR(150) NOT NULL DEFAULT ''							[track^L;20^T;150^H;link do tacka]
) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

$tan[C::get('tab_dpl19')] = "(
	dpl_id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[9]
	dpl_pilot	VARCHAR(3) NOT NULL DEFAULT '',							[pilot*^L;40^U;40;".C::get('tab_piloci18').";pil_id;pil_name]
	dpl_stref   VARCHAR(1)  NOT NULL DEFAULT '0',						[Strefy^L;8^W^H;naruszenie stref]
	dpl_data		DATE NOT NULL DEFAULT '0000-00-00',						[Data lotu^L;20^D]
	dpl_odl		FLOAT(6.2) NOT NULL DEFAULT '0.00',						[trasa*^L;20^C;6^O; km^H;przeleciany dystans]
	dpl_km		FLOAT(6.2) NOT NULL DEFAULT '0.00',						[trasa*^L;20^C;6^O; pkt^H;przyznane punkty]
	dpl_fai		VARCHAR(3) NOT NULL DEFAULT '',							[km or pkt^L;5^S;3;km;pkt^O; typ FAI^H;trójkąty czy przelot otwarty]
	dpl_track	VARCHAR(150) NOT NULL DEFAULT ''							[track^L;20^T;150^H;link do tacka]
) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

/*
$tan[C::get('tab_dpl2016')] = "(
	dpl_id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[9]
	dpl_pilot	VARCHAR(3) NOT NULL DEFAULT '',							[pilot*^L;40^U;40;".C::get('tab_piloci').";pil_id;pil_name]
	dpl_data		DATE NOT NULL DEFAULT '0000-00-00',						[Data lotu^L;20^D]
	dpl_km		FLOAT(6.2) NOT NULL DEFAULT '0.00',						[trasa*^L;20^C;6^O; km^H;długość trasy]
	dpl_track	VARCHAR(150) NOT NULL DEFAULT ''							[track^L;20^T;150^H;link do tacka]
) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci"; */

// [Zdjęcie*^ext;jpg^path;foty^th;m_,170,170,90;L_,300,300,90,121,,V]
// [Zdjęcie*^T;40]
/* tabela administratorów */ //Zdjęcie*^ext;jpg^path;foty^th;m_,170,170,90;L_,800,600,90,121
/*
 	$this->p[$name[0]]['th'] [0] - x
	$this->p[$name[0]]['th'] [1] - y
	$this->p[$name[0]]['th'] [2] - jakość
	$this->p[$name[0]]['th'] [3] - czcionka (nr czcionki) i położenie znaku wodnego
	$this->p[$name[0]]['th'] [4] - format docelowy
	$this->p[$name[0]]['th'] [5] - preferencja formatu: H-Horizontal, V-vertical ( sprawia że zdjęcie pionowe jest traktowane jak poziome i odwrotnie)
*/


//blokady logowania
$tan[C::get('tab_log_blok')] = "(
	blok_ip INT(10) UNSIGNED NOT NULL PRIMARY KEY,
	blok_licznik INT(11) NOT NULL DEFAULT '1',
	blok_czas DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00') ENGINE=MYISAM";


//-dane właściciela serwisu
//	own_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',		[status^A^S;X;0;10^O;1-ON, 0-OFF]
//	own_ide  VARCHAR(30)  NOT NULL DEFAULT '',			[identyfikator rekordu*^L;80^T;30]

$tan[C::get('tab_owner')] = "(
	own_id  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	own_mapa VARCHAR(1) NOT NULL DEFAULT '1',				[wyświetl mapę dojazdu^W]
	own_show VARCHAR(1) NOT NULL DEFAULT '1',				[pokaż dane adresowe^W]
	own_data DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[data utworzenia^D^H;utworzenia rekordu]
	own_imie VARCHAR(80),										[imię/nazwa1*^L;80^T;80^O;*nazwa firmy część 1]
	own_nzwi VARCHAR(80),										[nazwisko/nazwa2^T;80^O;*nazwa firmy część 2]
	own_kraj VARCHAR(40),										[kraj^T;40]
	own_kodp VARCHAR(10),										[kod pocztowy^T;10]
	own_mias VARCHAR(40),										[miejscowość^T;40]
	own_woje VARCHAR(20),										[województwo^T;40]
	own_plac VARCHAR(3),											[miejsce?^S;6;ul.;al.;pl.;Rynek;^O;ulica/aleja/plac/rynek]
	own_ulic VARCHAR(30),										[nazwa^T;30^O;ulicy/alei/placu]
	own_nab1 VARCHAR(50),										[nazwa banku^T;50]
	own_nrk1 VARCHAR(32),										[nr konta ze spacjami^T;32^H;format = ## #### #### #### #### #### #### ####]
	own_firm VARCHAR(1),											[firma?^W]
	own_nip  VARCHAR(13),										[nip^T;13^O;*dotyczy firm]
	own_rego VARCHAR(9),											[regon^C;9^O;*dotyczy firm]
	own_pese VARCHAR(11),										[pesel^C;11]
	own_tel1 VARCHAR(20),										[nr tel.1^F;20]
	own_ote1 VARCHAR(30),										[opis tel.1^T;30]
	own_tel2 VARCHAR(20),										[nr kom.2^F;20]
	own_ote2 VARCHAR(30),										[opis kom.2^T;30]
	own_fax  VARCHAR(20),										[nr faxu^F;20]
	own_ofa  VARCHAR(30),										[opis faxu^T;30]
	own_nada VARCHAR(50),										[nadawca^T;50^O;poczty automatycznej^H;nazwa nadawcy w nagłówku poczty]
	own_ma1  VARCHAR(30) NOT NULL DEFAULT '',				[mail-1^E;30]
	own_oma1 VARCHAR(30),										[opis mail.1^T;30]
	own_ma2  VARCHAR(30) NOT NULL DEFAULT '',				[mail-2^E;30]
	own_oma2 VARCHAR(30),										[opis mail.2^T;30]
	own_ma3  VARCHAR(30) NOT NULL DEFAULT '',				[mail-3^E;30]
	own_oma3 VARCHAR(30),										[opis mail.3^T;30]
	own_ma4  VARCHAR(30) NOT NULL DEFAULT '',				[mail-4^E;30]
	own_oma4 VARCHAR(30),										[opis mail.4^T;30]
	own_info TEXT													[informacje dodatkowe^T;1000]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


//-konfiguracja dostępna dla administratora

$tan[C::get('tab_config')] = "(
	con_id  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	con_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',					[status^A^S;X;0;10^O;1-ON, 0-OFF]
	con_ide  VARCHAR(30)  NOT NULL DEFAULT '',						[identyfikator rekordu*^L;80^T;30]
	con_data DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[data utworzenia^D^H;utworzenia rekordu]
	con_nazw VARCHAR(250) NOT NULL DEFAULT '',		  				[tytuł^T;250^O;strony^H;tytuł nagłówka okna przeglądarki]
	con_keyw TEXT,											  					[keywords^T;1000]
	con_desk TEXT,											  					[description^T;1000]
	con_kadr VARCHAR(1) NOT NULL DEFAULT '',	 						[domyślne kadrowanie zdjęć^W]
   con_zoom VARCHAR(2),														[google map zoom^C;2]
	con_size VARCHAR(9),														[google map rozmiar^T;9^H;szer. x wys. bez spacji]
	con_mail_test VARCHAR(30) NOT NULL DEFAULT '',					[mail testowy^E;30^H;adres to testów newslettera]
	con_li_akc VARCHAR(2) NOT NULL DEFAULT '20',  					[news ilość^L;12^C;2^H;ilość maili rozsyłanych w jednym cyklu Crona]
	con_mail_admin VARCHAR(30) NOT NULL DEFAULT '',		  			[e-mail admina^T;30^H;zawarty w nagłówku HTML strony]
	con_mail VARCHAR(30) NOT NULL DEFAULT '',		  					[e-mail dla formularza^T;30^H;dla formularza kontaktowego]
	con_mese VARCHAR(30) NOT NULL DEFAULT '',		  					[e-mail dla powiadomień mailowych^T;30^H;dla powiadomień mailowych]
	con_stop VARCHAR(100) NOT NULL DEFAULT '',		  				[stopka^T;100^O;strony^H; po znaku &copy; a przed stroną autora]
	con_styl VARCHAR(20) NOT NULL DEFAULT '',		  					[styl*^T;20^O;nazwa pliku css^H;bez .css]
	con_ropl VARCHAR(7) NOT NULL DEFAULT '', 							[pliki^C;7^O;max. romiar pobieranych plików na server]
	con_oppu VARCHAR(2) NOT NULL DEFAULT '',							[czas^C;2^O;opóźnienie publikacji w min.]
	con_awar VARCHAR(1) NOT NULL DEFAULT '',	  						[stop^W^O;strona awaryjna 1(on) 0(of)]
	con_ipad VARCHAR(100) NOT NULL DEFAULT ''							[ip^O;admina^T;50^H;adresy można łączyć znkaiem średnika]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";


$tan[C::get('tab_admini')] = "(
	admin_id   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	admin_stat TINYINT UNSIGNED NOT NULL DEFAULT '0',				[status^L;8^A^S;X;0;10^O;1-Admin, 10-VIP,]
	admin_blok VARCHAR(1) NOT NULL DEFAULT '1',						[blokada^L;8^A^W^O;użytkowanika^H;np. za nauszenie regulaminu]
	admin_logi VARCHAR(80) NOT NULL DEFAULT '',						[login*^L;40^B^T;50^O;login = e-mail^H;login to adres e-mail podany przy rejestracji]
	admin_pass VARCHAR(40) NOT NULL DEFAULT '',						[hasło*^!;20]
	admin_dado DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[data utworzenia konta^D^B]
	admin_dalo DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	[data ostatniego logowania^D^B]
	admin_nrlg INT(11) UNSIGNED NOT NULL DEFAULT '0',				[nr logowania^L;15^B]
	admin_koak VARCHAR(200),												[kod aktywacji usług^A]
	admin_ipus VARCHAR(30)													[ip ostatniego łączenia^L;20^B]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//blokady logowania
$tan[C::get('tab_log_blok')] = "(
	blok_ip INT(10) UNSIGNED NOT NULL PRIMARY KEY,
	blok_licznik INT(11) NOT NULL DEFAULT '1',
	blok_czas DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00') ENGINE=MYISAM";

//-tabele systemowe

$tan[C::get('tab_boxy')] = "(
	id   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[status^L;8^A^S;X;0;10^H;sortowanie boxów po statusie]
	blok VARCHAR(1)  NOT NULL DEFAULT '1',			[ukryty^L;8^W^H;widoczny tylko dla admina]
	dado DATETIME NOT NULL DEFAULT '2010-00-00 00:00:00', [data dodania^D^B^H;data dodania kontenera]
	dapu DATETIME NOT NULL DEFAULT '2010-00-00 00:00:00',	[data publikacju^D^B^H;data publikacji kontenera]
	nazw VARCHAR(30) NOT NULL DEFAULT '',			[identyfikator*^L;30^T;29^H;identyfikator kontenera]
	loka VARCHAR(20) NOT NULL DEFAULT '',			[lokacja*^L;25^J;20^H;położenie kontenera w szkielecie HTML]
	stro VARCHAR(40) NOT NULL DEFAULT '',			[podstrona*^L;25^UB;20^T;40]
	kate VARCHAR(20) NOT NULL DEFAULT '',			[szkielet html dla podstrony*^K;30;"._APATH."szablony/]
	wtyk VARCHAR(30) NOT NULL DEFAULT '',			[wtyczka programowa^K;30;"._APATH."wtyczki/;;"._APATH."klasy/]
	porc VARCHAR(1) NOT NULL DEFAULT '', 			[porcjowanie^W^H;włącznik porcjowania elementów w polu]
	klko VARCHAR(20) NOT NULL DEFAULT '',			[klasa kontenera^UB;20^T;20]
	idko VARCHAR(20) NOT NULL DEFAULT ''			[id kontenera^UB;20^T;20]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//-tabela zgodna ze starą wersją

$tan[C::get('tab_pola')] = "(
	id   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	stat TINYINT UNSIGNED NOT NULL DEFAULT '0',	[status^S;X;0;10^O;sortowanie po statusie]
	blok VARCHAR(1)  NOT NULL DEFAULT '',			[pole zablokowane^W^H;widoczne tylko dla admina]
	dapu DATETIME NOT NULL DEFAULT '2010-00-00 00:00:00', [data publikacju pola^D^B]
	dado DATETIME NOT NULL DEFAULT '2010-00-00 00:00:00',	[data utworzenia pola^D^B]
	kate VARCHAR(30) NOT NULL DEFAULT '',			[kategoria^L;32^UB;30^O;podział tematyczny^H;podział tematyczny treści tabeli]
	tyt  VARCHAR(255) NOT NULL DEFAULT '',			[tytuł*^L;80^T;255^O;tytuł treści]
	czwi VARCHAR(1) NOT NULL DEFAULT '',			[ukryj tytuł^W]
	h2   VARCHAR(1) NOT NULL DEFAULT '',			[h2/h3^W^H;0FF=h3 ON=h2]
	tres TEXT,												[treść^T;2000^H;zawartość tekstowa pola]
	form VARCHAR(1)  NOT NULL DEFAULT '1',			[formatuj treść^W]
	wtyk VARCHAR(30) NOT NULL DEFAULT '',			[wtyczka programowa^K;20;"._APATH."wtyczki/;;"._APATH."klasy/]
	loka VARCHAR(30) NOT NULL DEFAULT '',			[lokacja w boxie^L;40^U;40;".C::get('tab_boxy').";nazw]
	typ  VARCHAR(40) NOT NULL DEFAULT '',			[klasa pola^V^UB;20^H;klasa css kontenera obejmującego treść]
	cssid VARCHAR(40) NOT NULL DEFAULT '',			[id pola^V^UB;20^H;id css kontenera obejmującego treść]
	data DATETIME DEFAULT NULL,						[data ostatniej odsłony^H;data ostatniego wyświetlenia rekordu^N]
	kli0 INT UNSIGNED NOT NULL DEFAULT '0',  		[ilość odsłon pola^C;6^N]
	str  VARCHAR(20) NOT NULL DEFAULT ''			[podstrona^U;40;".C::get('tab_boxy').";stro]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

	/*
 	roz0 VARCHAR(20) NOT NULL DEFAULT '',			[rozmiar dla zdjęcia nr 1^T;20^H;szer;wys;jak;znak wodny]
	fot0 VARCHAR(30) NOT NULL DEFAULT '',			[zdjęcie-1^ext;jpg;gif;png^path;foty^th;m_,170,170,90;L_,800,600,90,111]
	poz0 VARCHAR(1)  NOT NULL DEFAULT '4',			[pozycja-1^S;X;0;9^zdjęcia nr 1^H;zdjęcie nr 1 widoczne na 1 stronie i na stronie z pełnym tekstem]
	roz1 VARCHAR(20) NOT NULL DEFAULT '',			[rozmiar dla zdjęcia nr 2^T;20^H;szer;wys;jak;znak wodny]
	fot1 VARCHAR(30) NOT NULL DEFAULT '',			[zdjęcie-2^ext;jpg;gif;png^path;foty^th;m_,170,170,90;L_,800,600,90,111]
	poz1 VARCHAR(1)  NOT NULL DEFAULT '4',			[pozycja-2^S;X;0;9^zdjęcia nr 1^H;zdjęcie nr 1 widoczne na 1 stronie i na stronie z pełnym tekstem] */

	//	form VARCHAR(1)  NOT NULL DEFAULT '1',			[formatuj treść^W]

//-konfiguracja serwisu --- dostęp tylko dla VIP'a ---

$tan[C::get('tab_vip')] = "(
	vi_id   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	vi_tyt  VARCHAR(250) NOT NULL DEFAULT '',		[opis*^L;30^T;30^H;identyfikator konfiguracji]
	vi_data DATETIME NOT NULL DEFAULT '2010-00-00 00:00:00', [data utworzenia^D^H;utworzenia rekordu]
	vi_dawa DATETIME NOT NULL DEFAULT '2010-00-00 00:00:00',	[data abonamentu^D^H;końcowa abonamentu]
	vi_test VARCHAR(1) NOT NULL DEFAULT '',		[test^W^O;komunikaty testowe 1(on) 0(off)]
	vi_stat VARCHAR(1) NOT NULL DEFAULT '',		[status^W^O;1(on) 0(of)]
	vi_licz VARCHAR(1) NOT NULL DEFAULT '',		[licznik^W^H;czy licznik dostępny dla admina w statystykach]
	vi_neli VARCHAR(2) NOT NULL DEFAULT '',		[limit newsletera^C;2^H;Ilość wiadomości wysłanych w okresie Crona]
	vi_tend INT UNSIGNED NOT NULL DEFAULT '0',	[tendencja wejść^B^H;0-spadek,1-bez zmian,2-wzrost]
	vi_ipad VARCHAR(100) NOT NULL DEFAULT ''		[ip^O;Vipa^T;50^H;adresy można łączyć znkaiem średnika]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//-licznik odwiedzin	---
/*
$tan[C::get('tab_licznik')] = "(
	id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 	ip   VARCHAR(15) DEFAULT NULL,
  	data TIMESTAMP NOT NULL,
  	licz INT UNSIGNED NOT NULL DEFAULT '0',
	akt  INT UNSIGNED NOT NULL DEFAULT '0',
	rob  TINYINT UNSIGNED NOT NULL DEFAULT '0',
	info VARCHAR(200) NOT NULL DEFAULT '',
  	roz  SMALLINT UNSIGNED NOT NULL DEFAULT '0')"; */

//-tabela systemowa - z definicjami tabel w serwisie */

$tan[C::get('tab_tab')] = "(
	id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nazwa VARCHAR(80) NOT NULL DEFAULT ''";

for($i=0; $i<120; $i++)
 $tan[C::get('tab_tab')] .= ',
	n'.$i." TEXT";		//-to jest max. powyżej 108 generuje błąd :: VARCHAR(180) NOT NULL DEFAULT ''

$tan[C::get('tab_tab')] .= ") ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";

//-konwersja tabeli
//-wartoby sprawdzić czy nie powtarzają się nazwy pól

foreach($tan as $kl => $zaw)
{
 $ta = '';
 preg_match_all("/\[(.*)\]/", $tan[$kl], $out);

 $end = count($out[1]);

 for ($i=0; $end>$i; $i++)
  $ta.= $out[1][$i].'|';

 $tap[$kl] = substr($ta,0,-1);									 //-same opisy pól bez nawiasów kwadratowych
 $tan[$kl] = preg_replace("/(\[.*\])/", "", $tan[$kl]); 	 //-tablica definicji tabeli bez opisów pól
}

$this->tap = $tap;
$this->tan = $tan;

unset($tan, $tap);
?>