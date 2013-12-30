<?php
/*
Plugin Name: Social Slider
Plugin URI: http://xn--wicek-k0a.pl/projekty/social-slider
Description: This plugin adds links to your social networking sites' profiles in a box floating at the left side of the screen.
Version: 5.6.5
Author: Łukasz Więcek
Author URI: http://xn--wicek-k0a.pl/
*/

$socialslider			= "social-slider-2";
$socialslider_wersja	= "5.6.5";
$socialslider_baza		= str_replace("https://", "http://", get_bloginfo('wpurl'));

if(get_option('socialslider_tryb'))			$socialslider_tryb = get_option('socialslider_tryb');
else										$socialslider_tryb = "uproszczony";

// czas zainstalowania
if(get_option('socialslider_instalacja'))
	{
	$socialslider_instalacja = get_option('socialslider_instalacja');
	}
else
	{
	$socialslider_instalacja = time();
	add_option('socialslider_instalacja', $socialslider_instalacja);
	}

$socialslider_limit = $socialslider_instalacja+3600;

if(time()<=$socialslider_limit)	{$socialslider_promocja = "Y";}
else							{$socialslider_promocja = "N";}

// licencja
$ssp64 = dirname(dirname(dirname(__FILE__)))."/".$socialslider."/license.key";

if(file_exists($ssp64))
	{
	$ssexplode = explode("*", base64_decode(fread(fopen($ssp64,"r"), filesize($ssp64))));

	if(($ssexplode[0]=="W" && $ssexplode[2] == base64_encode(str_replace("https://", "http://", get_bloginfo('wpurl')))) || ($ssexplode[0]=="E" && $ssexplode[1] == base64_encode(get_bloginfo('admin_email'))) || ($ssexplode[0]=="M" && $ssexplode[1] == base64_encode(get_bloginfo('admin_email')) && $ssexplode[2] == base64_encode(str_replace("https://", "http://", get_bloginfo('wpurl')))))
		{$socialslider_licencja = base64_decode($ssexplode[3]);}
	else
		{$socialslider_licencja = base64_decode(get_option('socialslider_licencja'));}
	}
else
	{$socialslider_licencja = base64_decode(get_option('socialslider_licencja'));}

list($socialslider_rok, $socialslider_miesiac, $socialslider_dzien) = explode('-', base64_decode($socialslider_licencja));
if(@checkdate($socialslider_miesiac, $socialslider_dzien, $socialslider_rok))	$socialslider_data = $socialslider_licencja;
else																			$socialslider_data = "MjAxMC0wMS0wMQ==";

function SocialSliderUstawienia()
	{
	global $wpdb, $table_prefix, $socialslider, $socialslider_baza, $socialslider_promocja, $socialslider_data, $socialslider_referer, $socialslider_wersja;

	$socialtabela			= $table_prefix."socialslider";

	// Resetowanie ustawień
	if($_POST['SocialSliderResetuj'])
		{
		if($wpdb->get_var("SHOW TABLES LIKE '".$socialtabela."'") == $socialtabela)
			{
			// Tabela istnieje - usuwanie tabeli
			$wpdb->query("DROP TABLE `".$socialtabela."`");
			}

		delete_option('socialslider_widget');

		if(get_option('socialslider_widget_width'))		{update_option('socialslider_widget_width', '200px');}
		else											{add_option('socialslider_widget_width', '200px', ' ', 'yes');}

		if(get_option('socialslider_widget_height'))	{update_option('socialslider_widget_height', 'auto');}
		else											{add_option('socialslider_widget_height', 'auto', ' ', 'yes');}

		if(get_option('socialslider_miejsce'))			{update_option('socialslider_miejsce', 'lewa');}
		else											{add_option('socialslider_miejsce', 'lewa', ' ', 'yes');}

		if(get_option('socialslider_kolor'))			{update_option('socialslider_kolor', 'jasny');}
		else											{add_option('socialslider_kolor', 'jasny', ' ', 'yes');}

		if(get_option('socialslider_custom_background')){update_option('socialslider_custom_background', '#ffffff');}
		else											{add_option('socialslider_custom_background', '#ffffff', ' ', 'yes');}

		if(get_option('socialslider_custom_border'))	{update_option('socialslider_custom_border', '#cccccc');}
		else											{add_option('socialslider_custom_border', '#cccccc', ' ', 'yes');}

		if(get_option('socialslider_custom_font'))		{update_option('socialslider_custom_font', '#666666');}
		else											{add_option('socialslider_custom_font', '#666666', ' ', 'yes');}

		if(get_option('socialslider_custom_radius'))	{update_option('socialslider_custom_radius', '6px');}
		else											{add_option('socialslider_custom_radius', '6px', ' ', 'yes');}

		if(get_option('socialslider_opacity'))			{update_option('socialslider_opacity', '1');}
		else											{add_option('socialslider_opacity', '1', ' ', 'yes');}

		if(get_option('socialslider_ikony'))			{update_option('socialslider_ikony', 'standard');}
		else											{add_option('socialslider_ikony', 'standard', ' ', 'yes');}

		if(get_option('socialslider_szybkosc'))			{update_option('socialslider_szybkosc', 'normal');}
		else											{add_option('socialslider_szybkosc', 'normal', ' ', 'yes');}

		if(get_option('socialslider_link'))				{update_option('socialslider_link', 'tak');}
		else											{add_option('socialslider_link', 'tak', ' ', 'yes');}

		if(get_option('socialslider_position'))			{update_option('socialslider_position', 'fixed');}
		else											{add_option('socialslider_position', 'fixed', ' ', 'yes');}

		if(get_option('socialslider_target'))			{update_option('socialslider_target', 'self');}
		else											{add_option('socialslider_target', 'self', ' ', 'yes');}

		if(get_option('socialslider_nofollow'))			{update_option('socialslider_nofollow', 'tak');}
		else											{add_option('socialslider_nofollow', 'tak', ' ', 'yes');}

		if(get_option('socialslider_mobile'))			{update_option('socialslider_mobile', 'tak');}
		else											{add_option('socialslider_mobile', 'tak', ' ', 'yes');}

		if(get_option('socialslider_rozdzielczosc'))	{update_option('socialslider_rozdzielczosc', '0px');}
		else											{add_option('socialslider_rozdzielczosc', '0px', ' ', 'yes');}

		if(get_option('socialslider_top'))				{update_option('socialslider_top', '150px');}
		else											{add_option('socialslider_top', '150px', ' ', 'yes');}

		if(get_option('socialslider_tryb'))				{delete_option('socialslider_tryb');}
		}

	// Czy tabela istnieje?
	if($wpdb->get_var("SHOW TABLES LIKE '".$socialtabela."'") != $socialtabela)
		{
		// Tabela nie istnieje - tworzenie nowej tabeli
		$wpdb->query("CREATE TABLE  `".$socialtabela."` (`id` TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,`lp` TINYINT NOT NULL,`ikona` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,`nazwa` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,`adres` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL) ENGINE = MYISAM ;");
		$wpdb->query("ALTER TABLE  `".$socialtabela."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

		// Uzupełnienie tabeli
		$is = 1;
		$rss = get_bloginfo('rss2_url');

		$wpdb->query("INSERT INTO  `".$socialtabela."` (`id`,`lp`,`ikona`,`nazwa`,`adres`) VALUES
			(NULL,		'".$is++."',			'rss',				'RSS','".$rss."'),
			(NULL,		'".$is++."',			'newsletter',		'Newsletter',''),
			(NULL,		'".$is++."',			'sledzik',			'Śledzik',''),
			(NULL,		'".$is++."',			'blip',				'Blip',''),
			(NULL,		'".$is++."',			'flaker',			'Flaker',''),
			(NULL,		'".$is++."',			'twitter',			'Twitter',''),
			(NULL,		'".$is++."',			'soup',				'Soup.io',''),
			(NULL,		'".$is++."',			'buzz',				'Buzz',''),
			(NULL,		'".$is++."',			'tumblr',			'Tumblr',''),

			(NULL,		'".$is++."',			'facebook',			'Facebook',''),
			(NULL,		'".$is++."',			'googleplus',		'Google+',''),
			(NULL,		'".$is++."',			'spinacz',			'Spinacz',''),
			(NULL,		'".$is++."',			'goldenline',		'GoldenLine',''),
			(NULL,		'".$is++."',			'linkedin',			'LinkedIn',''),
			(NULL,		'".$is++."',			'naszaklasa',		'Nasza Klasa',''),
			(NULL,		'".$is++."',			'networkedblogs',	'NetworkedBlogs',''),
			(NULL,		'".$is++."',			'myspace',			'MySpace',''),
			(NULL,		'".$is++."',			'orkut',			'Orkut',''),
			(NULL,		'".$is++."',			'grono',			'Grono',''),
			(NULL,		'".$is++."',			'friendconnect',	'FriendConnect',''),
			(NULL,		'".$is++."',			'friendfeed',		'FriendFeed',''),


			(NULL,		'".$is++."',			'digg',				'Digg',''),
			(NULL,		'".$is++."',			'wykop',			'Wykop',''),
			(NULL,		'".$is++."',			'kciuk',			'Kciuk',''),

			(NULL,		'".$is++."',			'picasa',			'Picasa',''),
			(NULL,		'".$is++."',			'flickr',			'Flickr',''),
			(NULL,		'".$is++."',			'panoramio',		'Panoramio',''),
			(NULL,		'".$is++."',			'deviantart',		'DeviantArt',''),


			(NULL,		'".$is++."',			'youtube',			'YouTube',''),
			(NULL,		'".$is++."',			'vimeo',			'Vimeo',''),
			(NULL,		'".$is++."',			'imdb',				'IMDb',''),

			(NULL,		'".$is++."',			'lastfm',			'Last.fm',''),
			(NULL,		'".$is++."',			'ising',			'iSing',''),
			(NULL,		'".$is++."',			'blipfm',			'Blip.fm',''),

			(NULL,		'".$is++."',			'delicious',		'Delicious',''),
			(NULL,		'".$is++."',			'unifyer',			'Unifyer','')
			;");
		}

	include("language.php");

	if(WPLANG=="pl_PL")		{$la = "pl_PL";}
	elseif(WPLANG=="es_ES")	{$la = "es_ES";}
	else					{$la = "en_US";}

	// Zapisywanie ustawień
	if($_POST['SocialSliderZapisz'])
		{
		// Serwisy
		$serwisy = $wpdb->get_results("SELECT * FROM ".$socialtabela."");

		foreach ($serwisy as $serwis)
			{
			$input	= "socialslider_".$serwis->ikona;
			$inputn	= "socialslider_nazwa_".$serwis->ikona;

			if(isset($_POST[$inputn]))
				{
				$wpdb->query("UPDATE `".$socialtabela."` SET `adres` = '".$_POST[$input]."' WHERE `ikona` = '".$serwis->ikona."'");
				$wpdb->query("UPDATE `".$socialtabela."` SET `nazwa` = '".$_POST[$inputn]."' WHERE `ikona` = '".$serwis->ikona."'");
				}

			if(empty($_POST[$inputn]))
				{
				$wpdb->query("DELETE FROM `".$socialtabela."` WHERE `ikona` = '".$serwis->ikona."'");
				}
			}

		// Widget
		if(!empty($_POST['socialslider_widget']))
			{
			if(get_option('socialslider_widget'))			{update_option('socialslider_widget', $_POST['socialslider_widget']);}
			else											{add_option('socialslider_widget', $_POST['socialslider_widget']);}
			}
		else
			{
			if(get_option('socialslider_widget'))			{delete_option('socialslider_widget');}
			}

		// Widget width
		if(!empty($_POST['socialslider_widget_width']))
			{
			if(get_option('socialslider_widget_width'))		{update_option('socialslider_widget_width', $_POST['socialslider_widget_width']);}
			else											{add_option('socialslider_widget_width', $_POST['socialslider_widget_width']);}
			}
		else
			{
			if(get_option('socialslider_widget_width'))		{update_option('socialslider_widget_width', '200px');}
			else											{add_option('socialslider_widget_width', '200px', ' ', 'yes');}
			}

		// Widget height
		if(!empty($_POST['socialslider_widget_height']))
			{
			if(get_option('socialslider_widget_height'))	{update_option('socialslider_widget_height', $_POST['socialslider_widget_height']);}
			else											{add_option('socialslider_widget_height', $_POST['socialslider_widget_height']);}
			}
		else
			{
			if(get_option('socialslider_widget_height'))	{update_option('socialslider_widget_height', 'auto');}
			else											{add_option('socialslider_widget_height', 'auto', ' ', 'yes');}
			}

		// Miejsce
		if(!empty($_POST['socialslider_miejsce']))
			{
			if(get_option('socialslider_miejsce'))			{update_option('socialslider_miejsce', $_POST['socialslider_miejsce']);}
			else											{add_option('socialslider_miejsce', $_POST['socialslider_miejsce'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_miejsce'))			{update_option('socialslider_miejsce', 'lewa');}
			else											{add_option('socialslider_miejsce', 'lewa', ' ', 'yes');}
			}

		// Kolor
		if(!empty($_POST['socialslider_kolor']))
			{
			if(get_option('socialslider_kolor'))			{update_option('socialslider_kolor', $_POST['socialslider_kolor']);}
			else											{add_option('socialslider_kolor', $_POST['socialslider_kolor'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_kolor'))			{update_option('socialslider_kolor', 'jasny');}
			else											{add_option('socialslider_kolor', 'jasny', ' ', 'yes');}
			}

		// Własny CSS - tło
		if(!empty($_POST['socialslider_custom_background']))
			{
			if(get_option('socialslider_custom_background')){update_option('socialslider_custom_background', $_POST['socialslider_custom_background']);}
			else											{add_option('socialslider_custom_background', $_POST['socialslider_custom_background'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_custom_background')){update_option('socialslider_custom_background', '#ffffff');}
			else											{add_option('socialslider_custom_background', '#ffffff', ' ', 'yes');}
			}

		// Własny CSS - obramowanie
		if(!empty($_POST['socialslider_custom_border']))
			{
			if(get_option('socialslider_custom_border'))	{update_option('socialslider_custom_border', $_POST['socialslider_custom_border']);}
			else											{add_option('socialslider_custom_border', $_POST['socialslider_custom_border'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_custom_border'))	{update_option('socialslider_custom_border', '#cccccc');}
			else											{add_option('socialslider_custom_border', '#cccccc', ' ', 'yes');}
			}

		// Własny CSS - czcionka
		if(!empty($_POST['socialslider_custom_font']))
			{
			if(get_option('socialslider_custom_font'))		{update_option('socialslider_custom_font', $_POST['socialslider_custom_font']);}
			else											{add_option('socialslider_custom_font', $_POST['socialslider_custom_font'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_custom_font'))		{update_option('socialslider_custom_font', '#666666');}
			else											{add_option('socialslider_custom_font', '#666666', ' ', 'yes');}
			}

		// Własny CSS - narożnik
		if(!empty($_POST['socialslider_custom_radius']))
			{
			if(get_option('socialslider_custom_radius'))	{update_option('socialslider_custom_radius', $_POST['socialslider_custom_radius']);}
			else											{add_option('socialslider_custom_radius', $_POST['socialslider_custom_radius'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_custom_radius'))	{update_option('socialslider_custom_radius', '6px');}
			else											{add_option('socialslider_custom_radius', '6px', ' ', 'yes');}
			}

		// Przezroczystość
		if(!empty($_POST['socialslider_opacity']))
			{
			if(get_option('socialslider_opacity'))			{update_option('socialslider_opacity', $_POST['socialslider_opacity']);}
			else											{add_option('socialslider_opacity', $_POST['socialslider_opacity'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_opacity'))			{update_option('socialslider_opacity', '1');}
			else											{add_option('socialslider_opacity', '1', ' ', 'yes');}
			}

		// Ikony
		if(!empty($_POST['socialslider_ikony']))
			{
			if(get_option('socialslider_ikony'))			{update_option('socialslider_ikony', $_POST['socialslider_ikony']);}
			else											{add_option('socialslider_ikony', $_POST['socialslider_ikony'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_ikony'))			{update_option('socialslider_ikony', 'standard');}
			else											{add_option('socialslider_ikony', 'standard', ' ', 'yes');}
			}

		// Szybkosc
		if(!empty($_POST['socialslider_szybkosc']))
			{
			if(get_option('socialslider_szybkosc'))			{update_option('socialslider_szybkosc', $_POST['socialslider_szybkosc']);}
			else											{add_option('socialslider_szybkosc', $_POST['socialslider_szybkosc'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_szybkosc'))			{update_option('socialslider_szybkosc', 'normal');}
			else											{add_option('socialslider_szybkosc', 'normal', ' ', 'yes');}
			}

		// Link
		if(!empty($_POST['socialslider_link']))
			{
			if(get_option('socialslider_link'))				{update_option('socialslider_link', $_POST['socialslider_link']);}
			else											{add_option('socialslider_link', $_POST['socialslider_link'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_link'))				{update_option('socialslider_link', 'tak');}
			else											{add_option('socialslider_link', 'tak', ' ', 'yes');}
			}

		// Pozycja
		if(!empty($_POST['socialslider_position']))
			{
			if(get_option('socialslider_position'))			{update_option('socialslider_position', $_POST['socialslider_position']);}
			else											{add_option('socialslider_position', $_POST['socialslider_position'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_position'))			{update_option('socialslider_position', 'fixed');}
			else											{add_option('socialslider_position', 'fixed', ' ', 'yes');}
			}

		// Target
		if(!empty($_POST['socialslider_target']))
			{
			if(get_option('socialslider_target'))			{update_option('socialslider_target', $_POST['socialslider_target']);}
			else											{add_option('socialslider_target', $_POST['socialslider_target'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_target'))			{update_option('socialslider_target', 'self');}
			else											{add_option('socialslider_target', 'self', ' ', 'yes');}
			}

		// Nofollow
		if(!empty($_POST['socialslider_nofollow']))
			{
			if(get_option('socialslider_nofollow'))			{update_option('socialslider_nofollow', $_POST['socialslider_nofollow']);}
			else											{add_option('socialslider_nofollow', $_POST['socialslider_nofollow'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_nofollow'))			{update_option('socialslider_nofollow', 'tak');}
			else											{add_option('socialslider_nofollow', 'tak', ' ', 'yes');}
			}

		// Mobile
		if(!empty($_POST['socialslider_mobile']))
			{
			if(get_option('socialslider_mobile'))			{update_option('socialslider_mobile', $_POST['socialslider_mobile']);}
			else											{add_option('socialslider_mobile', $_POST['socialslider_mobile'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_mobile'))			{update_option('socialslider_mobile', 'tak');}
			else											{add_option('socialslider_mobile', 'tak', ' ', 'yes');}
			}

		// Rozdzielczość
		if(!empty($_POST['socialslider_rozdzielczosc']))
			{
			if(get_option('socialslider_rozdzielczosc'))	{update_option('socialslider_rozdzielczosc', $_POST['socialslider_rozdzielczosc']);}
			else											{add_option('socialslider_rozdzielczosc', $_POST['socialslider_rozdzielczosc']);}
			}
		else
			{
			if(get_option('socialslider_rozdzielczosc'))	{update_option('socialslider_rozdzielczosc', '0px');}
			else											{add_option('socialslider_rozdzielczosc', '0px', ' ', 'yes');}
			}

		// Top
		if(!empty($_POST['socialslider_top']))
			{
			if(get_option('socialslider_top'))				{update_option('socialslider_top', $_POST['socialslider_top']);}
			else											{add_option('socialslider_top', $_POST['socialslider_top'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_top'))				{update_option('socialslider_top', '150px');}
			else											{add_option('socialslider_top', '150px', ' ', 'yes');}
			}

		// Tryb
		if(!empty($_POST['socialslider_tryb']))
			{
			if(get_option('socialslider_tryb'))				{update_option('socialslider_tryb', $_POST['socialslider_tryb']);}
			else											{add_option('socialslider_tryb', $_POST['socialslider_tryb'], ' ', 'yes');}
			}
		else
			{
			if(get_option('socialslider_tryb'))				{delete_option('socialslider_tryb');}
			}
		}

	if($_POST['SocialSliderNew'])
		{
		if(!empty($_POST['socialslider_new']))
			{
			$nazwa		= $_POST['socialslider_new'];
			$ikona		= $_POST['socialslider_new_images'];
			$lastid		= $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM `".$socialtabela."`;"));
			$newid		= $lastid+1;

			$wpdb->query("INSERT INTO `".$socialtabela."` (`id`,`lp`,`ikona`,`nazwa`,`adres`) VALUES (NULL, '".$newid."', '".$ikona."', '".$nazwa."', '')");
			}
		}
	?>
	<div class="wrap">
		<style type="text/css">
			input, textarea, select, table tr td
				{
				color: #555;
				font-size: 10px;
				}

			ul.serwisy
				{
				width: 660px;
				margin: 15px 0 0 20px;
				}

			ul.serwisy li
				{
				margin-bottom: 10px;
				}

			ul.serwisy label
				{
				width: 150px;
				float: left;
				}

			div.pro label
				{
				width: 140px;
				float: left;
				}

			ul.serwisy label img
				{
				margin-right: 5px;
				vertical-align: middle;
				width: 20px;
				height: 20px;
				}

			ul.serwisy input, ul.serwisy textarea, div.pro input.text, div.pro select
				{
				float: right;
				}

			ul.serwisy textarea
				{
				width: 500px;
				}

			ul.serwisy input.text
				{
				width: 388px;
				}

			ul.serwisy input.textn
				{
				width: 110px;
				}

			div.opcja
				{
				margin: 0 0 40px 20px;
				padding-left: 15px;
				border-top: 1px solid #ddd;
				border-left: 1px solid #ddd;
				}

			p.radio
				{
				color: #555;
				font-size: 10px;
				margin-left: 20px;
				}

			div.pro
				{
				width: 800px;
				margin: 10px 0 40px 20px;
				}

			div.pro input.text, div.pro select
				{
				margin-right: 340px;
				}

			div.pro ul
				{
				list-style-type: circle;
				margin: 0 0 40px 20px;
				}

			div.tryby
				{
				float: left;
				margin-left: 15px;
				text-align: center;
				}

			div.tryby img
				{
				border: 1px solid #ddd;
				height: 250px;
				}
		</style>

		<?php
		if(preg_match('/[a-z]+/', get_option('socialslider_top')))				$socialslider_top			= get_option('socialslider_top');			else $socialslider_top				= get_option('socialslider_top')."px";
		if(preg_match('/[a-z]+/', get_option('socialslider_widget_width')))		$socialslider_widget_width	= get_option('socialslider_widget_width');	else $socialslider_widget_width		= get_option('socialslider_widget_width')."px";
		if(preg_match('/[a-z]+/', get_option('socialslider_widget_height')))	$socialslider_widget_height	= get_option('socialslider_widget_height');	else $socialslider_widget_height	= get_option('socialslider_widget_height')."px";

		$socialslider_name		= "Social Slider";
		$socialslider_sort		= "id";
		$socialslider_disable	= " disabled";
		$socialslider_only		= " (".$lang[15][$la].")";

		if(date("Y-m-d")<=base64_decode($socialslider_data))
			{
			$socialslider_name		= "Social Slider Pro";
			$socialslider_sort		= "lp";
			$socialslider_disable	= "";
			$socialslider_only		= "";
			}

		if(get_option('socialslider_ikony'))
			{
			$socialslider_ikony	= get_option('socialslider_ikony');
			}
		else
			{
			$socialslider_ikony	= "standard";
			}
		?>

		<div id="socialslider">
			<h2><?php echo $lang[1][$la]; ?> <?php echo $socialslider_name; ?></h2>

			<form action="options-general.php?page=<?php echo $socialslider; ?>/<?php echo $socialslider; ?>.php" method="post" id="social-slider-config">
				
				<div class="opcja" style="margin-top: 10px; padding-top: 10px;"><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FSocial-Slider%2F171766542851805&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe></div>
		
				<div class="opcja">
					<p><?php echo $lang[2][$la]; ?></p>
					<p class="radio" id="ss_pelny"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_pelny" value="pelny"<?php if(get_option('socialslider_tryb')=="pelny") echo " checked"; ?> /> <label for="socialslider_tryb_pelny"><?php echo $lang[3][$la]; ?> (<a href="http://social-slider.com/demo/full.html" target="_blank"><?php echo $lang[96][$la]; ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_uproszczony" value="uproszczony"<?php if(get_option('socialslider_tryb')=="uproszczony" OR !get_option('socialslider_tryb')) echo " checked"; ?> /> <label for="socialslider_tryb_uproszczony"><?php echo $lang[4][$la]; ?> (<a href="http://social-slider.com/demo/simple.html" target="_blank"><?php echo $lang[96][$la]; ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_kompaktowy" value="kompaktowy"<?php if(get_option('socialslider_tryb')=="kompaktowy") echo " checked"; ?> /> <label for="socialslider_tryb_kompaktowy"><?php echo $lang[5][$la]; ?> (<a href="http://social-slider.com/demo/compact.html" target="_blank"><?php echo $lang[96][$la]; ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_minimalny" value="minimalny"<?php if(get_option('socialslider_tryb')=="minimalny") echo " checked"; ?> /> <label for="socialslider_tryb_minimalny"><?php echo $lang[6][$la]; ?> (<a href="http://social-slider.com/demo/minimal-small-icons.html" target="_blank"><?php echo $lang[96][$la]; ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_minimalny_duzy" value="minimalny_duzy"<?php if(get_option('socialslider_tryb')=="minimalny_duzy") echo " checked"; echo $socialslider_disable; ?> /> <label for="socialslider_tryb_minimalny_duzy"><?php echo $lang[79][$la]; ?> (<a href="http://social-slider.com/demo/minimal-big-icons.html" target="_blank"><?php echo $lang[96][$la]; ?></a>)</label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[8][$la]; ?></p>
					<p class="radio"><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_top')) {echo $socialslider_top;} else {echo "150px";} ?>" name="socialslider_top" id="socialslider_top" /> (<?php echo $lang[13][$la]; ?> 150px)</p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[104][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_position" id="socialslider_position_fixed" value="fixed"<?php if(get_option('socialslider_position')=="fixed" OR !get_option('socialslider_position')) {echo " checked";} ?> /> <label for="socialslider_position_fixed"><?php echo $lang[105][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_position" id="socialslider_position_absolute" value="absolute"<?php if(get_option('socialslider_position')=="absolute") {echo " checked";}?> /> <label for="socialslider_position_absolute"><?php echo $lang[106][$la]; ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[14][$la]; ?></p>
					<p class="radio"><?php echo $lang[72][$la]; ?><br /><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_widget_width')) {echo $socialslider_widget_width;} else {echo "200px";} ?>" name="socialslider_widget_width" id="socialslider_widget_width" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></p>
					<p class="radio"><?php echo $lang[73][$la]; ?><br /><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_widget_height')) {echo $socialslider_widget_height;} else {echo "auto";} ?>" name="socialslider_widget_height" id="socialslider_widget_height" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[50][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_miejsce" id="socialslider_miejsce_lewa" value="lewa"<?php if(get_option('socialslider_miejsce')=="lewa" OR !get_option('socialslider_miejsce') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_miejsce_lewa"><?php echo $lang[51][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_miejsce" id="socialslider_miejsce_prawa" value="prawa"<?php if(get_option('socialslider_miejsce')=="prawa" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_miejsce_prawa"><?php echo $lang[52][$la]; ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[63][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_kolor" id="socialslider_kolor_jasny" value="jasny"<?php if(get_option('socialslider_kolor')=="jasny" OR !get_option('socialslider_kolor') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_kolor_jasny"><?php echo $lang[64][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_kolor" id="socialslider_kolor_ciemny" value="ciemny"<?php if(get_option('socialslider_kolor')=="ciemny") {echo " checked";}?> /> <label for="socialslider_kolor_ciemny"><?php echo $lang[65][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_kolor" id="socialslider_kolor_css" value="css"<?php if(get_option('socialslider_kolor')=="css" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_kolor_css"><?php echo $lang[107][$la]; ?></label> <?php echo $socialslider_only; ?></p>

					<table style="margin: 15px 0 10px 40px;">
						<tr><td style="width: 140px;"><?php echo $lang[108][$la]; ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_background')) {echo get_option('socialslider_custom_background');} else {echo "#ffffff";} ?>" name="socialslider_custom_background" id="socialslider_custom_background" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
						<tr><td><?php echo $lang[109][$la]; ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_border')) {echo get_option('socialslider_custom_border');} else {echo "#cccccc";} ?>" name="socialslider_custom_border" id="socialslider_custom_border" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
						<tr><td><?php echo $lang[110][$la]; ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_font')) {echo get_option('socialslider_custom_font');} else {echo "#666666";} ?>" name="socialslider_custom_font" id="socialslider_custom_font" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
						<tr><td><?php echo $lang[111][$la]; ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_radius')) {echo get_option('socialslider_custom_radius');} else {echo "6px";} ?>" name="socialslider_custom_radius" id="socialslider_custom_radius" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
					</table>

					<p class="radio" style="margin: 0 0 20px 40px;"><?php echo $lang[112][$la]; ?></p>

					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[90][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_opacity" id="socialslider_opacity_1" value="1"<?php if(get_option('socialslider_opacity')=="1" OR !get_option('socialslider_opacity') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_opacity_1"><?php echo $lang[91][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_opacity" id="socialslider_opacity_9" value="9"<?php if(get_option('socialslider_opacity')=="9" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_opacity_9"><?php echo $lang[92][$la]; ?></label> <?php echo $socialslider_only; ?></p>
					<p class="radio"><input type="radio" name="socialslider_opacity" id="socialslider_opacity_8" value="8"<?php if(get_option('socialslider_opacity')=="8" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_opacity_8"><?php echo $lang[93][$la]; ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[69][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_ikony" id="socialslider_ikony_standard" value="standard"<?php if(get_option('socialslider_ikony')=="standard" OR !get_option('socialslider_ikony')) {echo " checked";} ?> /> <label for="socialslider_ikony_standard"><?php echo $lang[70][$la]; ?>
						<p style="margin: 8px 0 0 20px;">
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/buzz-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/goldenline-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/lastfm-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/networkedblogs-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/orkut-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/panoramio-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/picasa-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/twitter-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/youtube-32.png" alt="" />
						</p>
					</label></p>

					<p class="radio"><input type="radio" name="socialslider_ikony" id="socialslider_ikony_futomaki" value="futomaki"<?php if(get_option('socialslider_ikony')=="futomaki") {echo " checked";} ?> /> <label for="socialslider_ikony_futomaki"><?php echo $lang[71][$la]; ?>
						<p style="margin: 8px 0 0 0;">
							<img style="width: 32px; height: 32px; margin-left: 20px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/buzz-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/goldenline-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/lastfm-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/networkedblogs-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/orkut-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/panoramio-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/picasa-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/twitter-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/youtube-32.png" alt="" />
						</p>
					</label></p>

					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[53][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_slow" value="slow"<?php if(get_option('socialslider_szybkosc')=="slow") echo " checked"; ?> /> <label for="socialslider_szybkosc_slow"><?php echo $lang[54][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_normal" value="normal"<?php if(get_option('socialslider_szybkosc')=="normal" OR !get_option('socialslider_szybkosc')) {echo " checked";} ?> /> <label for="socialslider_szybkosc_normal"><?php echo $lang[55][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_fast" value="fast"<?php if(get_option('socialslider_szybkosc')=="fast") echo " checked"; ?> /> <label for="socialslider_szybkosc_fast"><?php echo $lang[56][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_nojs" value="nojs"<?php if(get_option('socialslider_szybkosc')=="nojs") echo " checked"; ?> /> <label for="socialslider_szybkosc_nojs"><?php echo $lang[82][$la]; ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[36][$la]; ?></p>
					<p class="radio"><input type="radio" class="text" value="tak" name="socialslider_link" id="socialslider_link_tak"<?php if(get_option('socialslider_link')=="tak" OR !get_option('socialslider_link') OR !empty($socialslider_disable)) {echo " checked";} ?>/> <label for="socialslider_link_tak"><?php echo $lang[37][$la]; ?></label></p>
					<p class="radio"><input type="radio" class="text" value="nie" name="socialslider_link" id="socialslider_link_nie"<?php if(get_option('socialslider_link')=="nie" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?>/> <label for="socialslider_link_nie"><?php echo $lang[38][$la]; ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[67][$la]; ?></p>
					<p class="radio"><input type="radio" class="text" value="tak" name="socialslider_mobile" id="socialslider_mobile_tak"<?php if(get_option('socialslider_mobile')=="tak") echo " checked"; ?>/> <label for="socialslider_mobile_tak"><?php echo $lang[37][$la]; ?></label></p>
					<p class="radio"><input type="radio" class="text" value="nie" name="socialslider_mobile" id="socialslider_mobile_nie"<?php if(get_option('socialslider_mobile')=="nie" OR !get_option('socialslider_mobile')) {echo " checked";} ?>/> <label for="socialslider_mobile_nie"><?php echo $lang[38][$la]; ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[97][$la]; ?></p>
					<p class="radio"><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_rozdzielczosc')) {echo get_option('socialslider_rozdzielczosc');} else {echo "0px";} ?>" name="socialslider_rozdzielczosc" id="socialslider_rozdzielczosc" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?> /> (<?php echo $lang[13][$la]; ?> 0px) <?php echo $socialslider_only; ?></p>
					<p class="radio"><?php echo $lang[98][$la]; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[76][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_target" id="socialslider_target_self" value="self"<?php if(get_option('socialslider_target')=="self" OR !get_option('socialslider_target') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_target_self"><?php echo $lang[77][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_target" id="socialslider_target_blank" value="blank"<?php if(get_option('socialslider_target')=="blank" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_target_blank"><?php echo $lang[78][$la]; ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php echo $lang[95][$la]; ?></p>
					<p class="radio"><input type="radio" name="socialslider_nofollow" id="socialslider_nofollow_tak" value="tak"<?php if(get_option('socialslider_nofollow')=="tak" OR !get_option('socialslider_nofollow')) {echo " checked";} ?> /> <label for="socialslider_nofollow_tak"><?php echo $lang[37][$la]; ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_nofollow" id="socialslider_nofollow_nie" value="nie"<?php if(get_option('socialslider_nofollow')=="nie" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_nofollow_nie"><?php echo $lang[38][$la]; ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
				<?php
				if(date("Y-m-d")<=base64_decode($socialslider_data))
					{
					?>
					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery(function() {
								jQuery("ul#serwisy").sortable({ opacity: 0.6, cursor: 'nw-resize', update: function() {
									var order = jQuery(this).sortable("serialize") + '&action=ZapiszPozycje';
									jQuery.post("<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/ajax.php", order, function(theResponse){
										jQuery("div#ajax").html(theResponse);
									});
								}
								});
							});
						});
					</script>
					<?php } ?>

					<p><?php echo $lang[9][$la]; ?></p>
					<ul id="serwisy" class="serwisy">
						<?php
						$serwisy = $wpdb->get_results("SELECT * FROM ".$table_prefix."socialslider ORDER BY ".$socialslider_sort." ASC");
						foreach ($serwisy as $serwis)
							{
							$ikona = $serwis->ikona;
							if($ikona[0]=="_")		$socialslider_katalog_ikon = $socialslider_baza."/wp-content/".$socialslider;
							else					$socialslider_katalog_ikon = WP_PLUGIN_URL."/".$socialslider."/icons/".$socialslider_ikony;

							echo "<li id='rA_".$serwis->id."'>
								<label for 'socialslider_".$serwis->ikona."'><img src='".$socialslider_katalog_ikon."/".$serwis->ikona."-20.png' alt='".$serwis->nazwa."' />".$serwis->nazwa.":</label>

								<input type='text' class='text' value='".$serwis->adres."' name='socialslider_".$serwis->ikona."' id='socialslider_".$serwis->ikona."' />
								<input type='text' class='textn' value='".$serwis->nazwa."' name='socialslider_nazwa_".$serwis->ikona."' id='socialslider_nazwa_".$serwis->ikona."' /><br style='clear: both;' />
							</li>";
							}
						?>
					</ul>

					<ul class="serwisy">
						<li>
							<label for 'socialslider_widget'><img src='/wp-content/plugins/<?php echo $socialslider; ?>/icons/<?php echo $socialslider_ikony; ?>/widget-20.png' alt='Widget' /> <?php echo $lang[10][$la]; ?></label>
							<textarea name="socialslider_widget" id="socialslider_widget" style="height: 200px;"><?php echo stripslashes(get_option('socialslider_widget')); ?></textarea><br />
							<p style="font-size: 10px; color: #777; line-height: 14px; margin-left: 20px;"><?php echo $lang[118][$la]; ?></p>
						</li>
						<br style='clear: both;' />
					</ul>

					<input type="submit" name="SocialSliderZapisz" value="<?php echo $lang[12][$la]; ?>" style="margin: 15px 0 5px 20px;" />
				</div>
			</form>

			<h2><?php echo $lang[89][$la]; ?></h2>
			<div class="pro">
				<p style="margin-bottom: 25px;"><?php echo $lang[83][$la]; ?></p>

				<form action="options-general.php?page=<?php echo $socialslider; ?>/<?php echo $socialslider; ?>.php" method="post" id="social-slider-pro" style="margin-left: 20px;">

					<?php $times = time(); ?>

					<ul style="margin-left: 25px; list-style-type: none;">
						<li>
							<label for 'socialslider_new'><?php echo $lang[84][$la]; ?></label>
							<input type='text' class='text' value='' name='socialslider_new' id='socialslider_new' /><br style='clear: both;' />
							<input type='hidden' class='text' name='socialslider_new_images' id='socialslider_new_images' value='_<?php echo $times; ?>' />
						</li>
					</ul>

					<p style="margin-top: 25px;"><?php echo $lang[85][$la]; ?></p>

					<ul style="margin-left: 25px; list-style-type: none;">
						<li><b>_<?php echo $times; ?>-20.png</b> <i><?php echo $lang[86][$la]; ?></i></li>
						<li><b>_<?php echo $times; ?>-32.png</b> <i><?php echo $lang[87][$la]; ?></i></li>
					</ul>

					<p style="margin-top: 25px;"><?php echo $lang[88][$la]; ?><br /><input type="submit" name="SocialSliderNew" value="<?php echo $lang[89][$la]; ?>" style="margin: 20px 0 20px 0;" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "onclick='this.disabled=true;' ";} ?>/> <?php echo $socialslider_only; ?></p>
				</form>
			</div>

			<h2><?php echo $lang[99][$la]; ?></h2>
			<div class="pro">
				<p><?php echo $lang[100][$la]; ?></p>

				<p><?php echo $lang[101][$la]; ?></p>

					<p style="margin-left: 25px; text-decoration: underline;">http://del.icio.us/post?url=<strong>[URL]</strong>&title=<strong>[TITLE]</strong></p>

				<p style="margin-top: 25px;"><?php echo $lang[102][$la]; ?></p>

					<?php
					$w					= $wpdb->get_row("SELECT ID,post_title FROM $wpdb->posts WHERE `post_status` LIKE 'publish' AND `post_type` LIKE 'post' ORDER BY post_date DESC LIMIT 1");
					$socialslider_url	= rawurlencode(get_permalink($w->ID));
					$socialslider_title	= rawurlencode($w->post_title);
					?>

					<p style="margin-left: 25px;"><a href="http://del.icio.us/post?url=<?php echo $socialslider_url; ?>&title=<?php echo $socialslider_title; ?>" title="Facebook">http://del.icio.us/post?url=<?php echo $socialslider_url; ?>&title=<?php echo $socialslider_title; ?></a></p>
			</div>

			<h2><?php echo $lang[60][$la]; ?></h2>
			<div class="pro">
				<p><?php echo $lang[61][$la]; ?></p>
				<p><?php echo $lang[62][$la]; ?></p>

				<pre style="margin-left: 20px;"><span style="color: #FF0000;">&lt;?php</span><span style="color: #333333;"> SocialSlider</span><span style="color: #AE00FB;">(); </span><span style="color: #FF0000;">?&gt;</span></pre>
			</div>

			<h2 id="pro"><?php echo $lang[16][$la]; ?></h2>
			<div class="pro">

			<?php
			if(date("Y-m-d")<=base64_decode($socialslider_data))
				{
				if(base64_decode($socialslider_data)!="2099-12-31")	{$socialslider_data_do = base64_decode($socialslider_data);}
				else												{$socialslider_data_do = $lang[39][$la];}

				echo "<p style='margin-left: 20px; font-style: italic;'>".$lang[40][$la].": ".$socialslider_data_do."</p>";
				}
			else
				{
				?>

				<p><?php echo $lang[17][$la]; ?></p>
				<ul>
					<li><?php echo $lang[18][$la]; ?></li>
					<li><?php echo $lang[19][$la]; ?></li>
					<li><?php echo $lang[20][$la]; ?></li>
				</ul>

				<?php if($timeoff>0) echo '<p style="margin-bottom: 35px; color: darkgreen;">'.$lang[113][$la].'</p>'; ?>

				<p><?php echo $lang[74][$la]; ?></p>

				<p><?php echo $lang[21][$la]; ?></p>


				<form action="http://social-slider.com/" method="post" style="margin: 20px 0 20px 20px;">
				
					<input type="hidden" name="wpurl" value="<?php echo $socialslider_baza; ?>">
					<input type="hidden" name="admin_email" value="<?php bloginfo('admin_email'); ?>">
					<input type="hidden" name="promocja" value="<?php echo $socialslider_promocja; ?>">
					<input type="hidden" name="jezyk" value="<?php echo $la; ?>">
					<input type="hidden" name="referer" value="<?php echo $socialslider_referer; ?>">
					<input type="hidden" name="wersja" value="<?php echo $socialslider_wersja; ?>">
					<input type="image" style="margin-left: 20px;" src="https://www.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
				</form>

				<p><?php echo $lang[25][$la]; ?></p>
				<p><?php echo $lang[103][$la]; ?></p>
				<?php } ?>
			</div>

			<h2><?php echo $lang[116][$la]; ?></h2>
			<div class="pro">
				<form action="options-general.php?page=<?php echo $socialslider; ?>/<?php echo $socialslider; ?>.php" method="post" id="social-slider-reset">
					<input type="submit" name="SocialSliderResetuj" value="<?php echo $lang[117][$la]; ?>" style="margin: 15px 0 5px 20px;" />
				</form>
			</div>

			<h2><?php echo $lang[28][$la]; ?></h2>
			<div class="pro">
				<p><ol>
					<li><?php echo $lang[29][$la]; ?></li>
					<li><?php echo $lang[30][$la]; ?></li>
					<li><?php echo $lang[49][$la]; ?></li>
					<li><?php echo $lang[32][$la]; ?></li>
					<li><?php echo $lang[94][$la]; ?></li>
					<li><?php echo $lang[68][$la]; ?></li>
				</ol></p>
			</div>

			<p style="margin-top: 30px;"><?php echo $lang[35][$la]; ?></p>
			
		</div>
		<div id="ajax">&nbsp;</div>
	</div>
	<?php
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		PARAMETRY		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// *** Wartości domyślne *********************************************************************************************************************************************
$socialslider_kolor					= "jasny";
$socialslider_link					= "tak";
$socialslider_miejsce				= "lewa";
$socialslider_nazwa					= "Social Slider";
$socialslider_nocustom				= " AND ikona NOT LIKE '\_%'";
$socialslider_nofollow				= "tak";
$socialslider_opacity				= "1";
$socialslider_position 				= "fixed";
$socialslider_rozdzielczosc			= "0";
$socialslider_sort					= "id";
$socialslider_szybkosc 				= "normal";
$socialslider_target				= "self";
$socialslider_top					= "150px";
$socialslider_widget_height			= "auto";
$socialslider_widget_width			= "200px";
// *******************************************************************************************************************************************************************

// *** Pobranie ustawień *********************************************************************************************************************************************
if(get_option('socialslider_kolor')=="ciemny")		$socialslider_kolor				= "ciemny";
else												$socialslider_kolor				= "jasny";

if(get_option('socialslider_nofollow'))				$socialslider_nofollow			= get_option('socialslider_nofollow');
if(get_option('socialslider_position'))				$socialslider_position			= get_option('socialslider_position');
if(get_option('socialslider_szybkosc'))				$socialslider_szybkosc			= get_option('socialslider_szybkosc');
if(get_option('socialslider_top'))					$socialslider_top				= get_option('socialslider_top');
if(get_option('socialslider_widget'))				$socialslider_widget			= get_option('socialslider_widget');
// *******************************************************************************************************************************************************************

if($socialslider_nofollow!="nie")	{$nofollow = " rel='nofollow'";}
else								{$nofollow = "";}
	
// *** Pobranie ustawień dla licencji Pro ****************************************************************************************************************************
if(date("Y-m-d")<=base64_decode($socialslider_data))
	{
	if(get_option('socialslider_kolor'))			$socialslider_kolor				= get_option('socialslider_kolor');
	if(get_option('socialslider_link'))				$socialslider_link				= get_option('socialslider_link');
	if(get_option('socialslider_miejsce'))			$socialslider_miejsce			= get_option('socialslider_miejsce');
													$socialslider_nazwa				= "Social Slider Pro";
													$socialslider_nocustom			= "";
	if(get_option('socialslider_opacity'))			$socialslider_opacity			= get_option('socialslider_opacity');
	if(get_option('socialslider_rozdzielczosc'))	$socialslider_rozdzielczosc		= str_replace("px","",get_option('socialslider_rozdzielczosc'));
													$socialslider_sort				= "lp";
	if(get_option('socialslider_target'))			$socialslider_target			= get_option('socialslider_target');
	if(get_option('socialslider_widget_height'))	$socialslider_widget_height		= get_option('socialslider_widget_height');
	if(get_option('socialslider_widget_width'))		$socialslider_widget_width		= get_option('socialslider_widget_width');
	}
// *******************************************************************************************************************************************************************

// *** Ustawienia wersji kolorystycznej ******************************************************************************************************************************
if($socialslider_kolor=="jasny")
	{
	$socialslider_bg_color		= "#fff";
	$socialslider_border_color	= "#ccc";
	$socialslider_a_color		= "#666";
	$socialslider_autor_color	= "#2275ad";
	}

if($socialslider_kolor=="ciemny")
	{
	$socialslider_bg_color		= "#222";
	$socialslider_border_color	= "#5b5b5b";
	$socialslider_a_color		= "#eee";
	$socialslider_autor_color	= "#ccc";
	}

if($socialslider_kolor=="css")
	{
	if(get_option('socialslider_custom_background'))	$socialslider_bg_color		= get_option('socialslider_custom_background');	else $socialslider_bg_color		= "#ffffff";
	if(get_option('socialslider_custom_border'))		$socialslider_border_color	= get_option('socialslider_custom_border');		else $socialslider_border_color	= "#cccccc";
	if(get_option('socialslider_custom_font'))			$socialslider_a_color		= get_option('socialslider_custom_font');		else $socialslider_a_color		= "#666666";
	if(get_option('socialslider_custom_font'))			$socialslider_autor_color	= get_option('socialslider_custom_font');		else $socialslider_autor_color	= "#666666";
	if(get_option('socialslider_custom_radius'))		$socialslider_custom_radius	= get_option('socialslider_custom_radius');		else $socialslider_custom_radius	= "6px";
	}
// *******************************************************************************************************************************************************************

if($socialslider_miejsce=="lewa" || empty($socialslider_miejsce))
	{
	$socialslider_handle				= "handle-lewy-".$socialslider_kolor;
	$socialslider_handle_lr				= "right";

	switch($socialslider_tryb)
		{
		case "pelny":
			$socialslider_width0		= 100+$socialslider_widget_width;
			$socialslider_width1		= 102+$socialslider_widget_width;
			$socialslider_width_js		= "left:'-".$socialslider_width1."'";
			$socialslider_width_0js		= "left:'0'";
			$socialslider_width_css		= "width: ".$socialslider_width0."px; left: -".$socialslider_width1."px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "uproszczony":
			$socialslider_width_js		= "left:'-86'";
			$socialslider_width_0js		= "left:'0'";
			$socialslider_width_css		= "width: 85px; left: -86px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "kompaktowy":
			$socialslider_width_js		= "left:'-86'";
			$socialslider_width_0js		= "left:'0'";
			$socialslider_width_css		= "width: 85px; left: -86px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "minimalny":
			$socialslider_width_css		= "width: 0px; left: -1px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "minimalny_duzy":
			$socialslider_width_css		= "width: 0px; left: -1px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -44px;\"";
			break;
		}
	}

if($socialslider_miejsce=="prawa")
	{
	$socialslider_handle 				= "handle-prawy-".$socialslider_kolor;
	$socialslider_handle_lr 			= "left";
	$socialslider_margin_right			= " margin-right: 0; margin-left: -1px;";

	switch($socialslider_tryb)
		{
		case "pelny":
			$socialslider_width			= 100+$socialslider_widget_width;
			$socialslider_width1		= 101+$socialslider_widget_width;
			$socialslider_width_js		= "right:'-".$socialslider_width1."'";
			$socialslider_width_0js		= "right:'0'";
			$socialslider_width_css		= "width: ".$socialslider_width."px; right: -".$socialslider_width1."px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: ".$socialslider_width."px;\"";
			break;

		case "uproszczony":
			$socialslider_width_js		= "right:'-86'";
			$socialslider_width_0js		= "right:'0'";
			$socialslider_width_css		= "width: 85px; right: -86px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 85px;\"";
			break;

		case "kompaktowy":
			$socialslider_width_js		= "right:'-86'";
			$socialslider_width_0js		= "right:'0'";
			$socialslider_width_css		= "width: 85px; right: -86px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 85px;\"";
			break;

		case "minimalny":
			$socialslider_width_css		= "width: 0px; right: -1px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 0;\"";
			break;

		case "minimalny_duzy":
			$socialslider_width_css		= "width: 0px; right: -1px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 0;\"";
			break;
		}
	}

function katalog_ikon($ikona)
	{
	global $socialslider_baza, $socialslider;

	if($ikona[0]=="_")		return $socialslider_baza."/wp-content/".$socialslider;
	else					return WP_PLUGIN_URL ."/".$socialslider."/icons/".get_option('socialslider_ikony');
	}

if(WPLANG=="pl_PL")		{$li = "aHR0cDovL3huLS13aWNlay1rMGEucGwvcHJvamVrdHkvc29jaWFsLXNsaWRlcg==";}
else					{$li = "aHR0cDovL3dvcmRwcmVzcy5vcmcvZXh0ZW5kL3BsdWdpbnMvc29jaWFsLXNsaWRlci0yLw==";}

switch($socialslider_tryb)
	{
	case "pelny":				$socialslider_alink = "<a href='".base64_decode($li)."' title='".base64_decode('U29jaWFsIFNsaWRlcg==')."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>".base64_decode('U29jaWFsIFNsaWRlcg==')."</a>";	break;
	case "uproszczony":			$socialslider_alink = "<a href='".base64_decode($li)."' title='".base64_decode('U29jaWFsIFNsaWRlcg==')."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>".base64_decode('U29jaWFsIFNsaWRlcg==')."</a>";	break;
	case "kompaktowy":			$socialslider_alink = "<a href='".base64_decode($li)."' title='".base64_decode('U29jaWFsIFNsaWRlcg==')."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>".base64_decode('U29jaWFsIFNsaWRlcg==')."</a>";	break;
	case "minimalny":			$socialslider_alink = "<a href='".base64_decode($li)."' title='".base64_decode('U29jaWFsIFNsaWRlcg==')."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>".base64_decode('U2xpZGVy')."</a>";				break;
	case "minimalny_duzy":		$socialslider_alink = "<a href='".base64_decode($li)."' title='".base64_decode('U29jaWFsIFNsaWRlcg==')."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>".base64_decode('U29jaWFsIFNsaWRlcg==')."</a>";	break;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		STYLE CSS		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action('wp_head', 'headCSS');

function headCSS()
	{
	global $socialslider_szybkosc, $socialslider_miejsce, $socialslider_kolor, $socialslider_bg_color, $socialslider_handle_lr, $socialslider_custom_radius,
	$socialslider_border_color, $socialslider_margin_right, $socialslider_baza, $socialslider, $socialslider_handle, $socialslider_opacity;

	echo "<style type='text/css'>";
	if($socialslider_szybkosc=="nojs")
		{
		if($socialslider_miejsce=="lewa")	echo "#socialslider:hover {left: 0 !important;}";
		if($socialslider_miejsce=="prawa")	echo "#socialslider:hover {right: 0 !important;}";
		}

	if($socialslider_kolor=="css")
		{
		echo "#socialslider-ikony		{background: ".$socialslider_bg_color."; border-top-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";		-webkit-border-top-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";		-khtml-border-radius-top: ".$socialslider_custom_radius.";		-moz-border-radius-top".$socialslider_handle_lr.": ".$socialslider_custom_radius."; 	border-".$socialslider_handle_lr.": 1px solid ".$socialslider_border_color.";	border-top: 1px solid ".$socialslider_border_color.";}
		#socialslider-ikony ul			{background: ".$socialslider_bg_color."; border-bottom-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";	 	-webkit-border-bottom-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";	-khtml-border-radius-bottom: ".$socialslider_custom_radius.";	-moz-border-radius-bottom".$socialslider_handle_lr.": ".$socialslider_custom_radius."; 	border-".$socialslider_handle_lr.": 1px solid ".$socialslider_border_color.";	border-bottom: 1px solid ".$socialslider_border_color.";".$socialslider_margin_right."}";
		}
	else
		{
		echo "#socialslider-ikony		{background: transparent url('". WP_PLUGIN_URL ."/".$socialslider."/images/".$socialslider_handle.".png') no-repeat ".$socialslider_handle_lr." top; padding-top: 1px; padding-".$socialslider_handle_lr.": 1px;}
		#socialslider-ikony ul			{background: transparent url('". WP_PLUGIN_URL ."/".$socialslider."/images/".$socialslider_handle.".png') no-repeat ".$socialslider_handle_lr." bottom; padding-bottom: 1px; padding-".$socialslider_handle_lr.": 1px;".$socialslider_margin_right."}";
		}
	echo "</style>";

	if($socialslider_opacity=="9") echo '<!--[if !IE]><!--><link type="text/css" rel="stylesheet" href="'. WP_PLUGIN_URL .'/'.$socialslider.'/css/opacity-9.css" /><!--<![endif]-->';
	if($socialslider_opacity=="8") echo '<!--[if !IE]><!--><link type="text/css" rel="stylesheet" href="'. WP_PLUGIN_URL .'/'.$socialslider.'/css/opacity-8.css" /><!--<![endif]-->';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		  SLIDER		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SocialSlider()
	{
	global $post, $socialslider, $serwisy, $socialslider_wersja, $socialslider_tryb, $socialslider_baza, $socialslider_szybkosc, $socialslider_miejsce,
	$socialslider_kolor, $socialslider_bg_color, $socialslider_handle_lr, $socialslider_custom_radius, $socialslider_border_color, $socialslider_margin_right,
	$socialslider_handle, $socialslider_opacity, $socialslider_nazwa, $socialslider_wersja, $socialslider_top, $socialslider_width_css, $socialslider_data,
	$socialslider_a_color, $socialslider_target, $nofollow, $socialslider_link, $socialslider_alink, $socialslider_widget, $socialslider_widget_width,
	$socialslider_widget_height, $socialslider_width_ikony, $socialslider_rozdzielczosc, $socialslider_width_0js, $socialslider_width_js, $socialslider_nocustom,
	$socialslider_sort, $wpdb, $table_prefix, $socialslider_promocja;

	if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy" && $socialslider_szybkosc!="nojs")
		{
		?>
		<script type="text/javascript">
				jQuery(document).ready(function () {var hideDelay=200;var hideDelayTimer=null;jQuery("#socialslider").hover(function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);jQuery("#socialslider").animate({<?php echo $socialslider_width_0js; ?>},"<?php echo $socialslider_szybkosc; ?>");},function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);hideDelayTimer=setTimeout(function(){hideDelayTimer=null;jQuery("#socialslider").animate({<?php echo $socialslider_width_js; ?>},"<?php echo $socialslider_szybkosc; ?>");},hideDelay);});});
		</script>
		<?php
		}
	?>

	<!-- <?php echo $socialslider_nazwa; ?> v.<?php echo $socialslider_wersja; ?> -->
	<!-- SS#<?php echo base64_encode(get_bloginfo('wpurl')."#".WPLANG."#".$socialslider_promocja); ?>## -->
	
	<div id="socialslider" style="top: <?php echo $socialslider_top; ?>; <?php echo $socialslider_width_css; ?>">
		<div id="socialslider-contener" class="socialslider-contener">

			<?php
			function adres($adres, $data)
				{
				global $post;

				if(date("Y-m-d")<=base64_decode($data))
					{
					if(is_home())
						{
						$ss_title	= rawurlencode(get_bloginfo('name'));
						$ss_perma	= rawurlencode(get_bloginfo('url'));
						}
					else
						{
						$ss_title	= rawurlencode(get_the_title($post->ID));
						$ss_perma	= rawurlencode(get_permalink($post->ID));
						}

					$adres = str_replace("[URL]", $ss_perma, $adres);
					$adres = str_replace("[TITLE]", $ss_title, $adres);
					}

				return $adres;
				}

			$serwisy = $wpdb->get_results("SELECT * FROM ".$table_prefix."socialslider WHERE adres NOT LIKE ''".$socialslider_nocustom." ORDER BY ".$socialslider_sort." ASC");

			if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy")
				{
				?>
				<div id="socialslider-linki" class="socialslider-grupa">
					<ul>
						<?php
						if($socialslider_tryb!="kompaktowy")
							{
							foreach ($serwisy as $serwis) {echo "<li><a href='".adres($serwis->adres, $socialslider_data)."' title='".$serwis->nazwa."' style='color: ".$socialslider_a_color.";' target='_".$socialslider_target."'".$nofollow."><img src='".katalog_ikon($serwis->ikona)."/".$serwis->ikona."-32.png' alt='".$serwis->nazwa."' />".$serwis->nazwa."</a></li>";}
							}
						else
							{
							foreach ($serwisy as $serwis) {echo "<li><a href='".adres($serwis->adres, $socialslider_data)."' title='".$serwis->nazwa."' style='color: ".$socialslider_a_color.";' target='_".$socialslider_target."'".$nofollow.">".$serwis->nazwa."</a></li>";}
							}

						if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy" && (date("Y-m-d")>base64_decode($socialslider_data) || $socialslider_link=="tak"))
							{
							echo "<li id='".base64_decode('c29jaWFsc2xpZGVyLWF1dG9y')."'>".$socialslider_alink."</li>";
							}
						?>
					</ul>
				</div>
				<?php
				}

			if($socialslider_tryb=="pelny" && !empty($socialslider_widget)) echo "<div id='socialslider-widget' class='socialslider-grupa' style='width: ".$socialslider_widget_width."; height: ".$socialslider_widget_height.";'>".stripslashes($socialslider_widget)."</div>";
			?>
			<div id="socialslider-ikony" <?php echo $socialslider_width_ikony; ?>>
				<ul>
				<?php
				if($socialslider_tryb=="minimalny")			$socialslider_minimalny_rozmiar = "20";
				if($socialslider_tryb=="minimalny_duzy")	$socialslider_minimalny_rozmiar = "32";

				if($socialslider_tryb=="minimalny" || $socialslider_tryb=="minimalny_duzy")
					{
					foreach ($serwisy as $serwis) {echo "<li><a href='".adres($serwis->adres, $socialslider_data)."' title='".$serwis->nazwa."' target='_".$socialslider_target."'".$nofollow."><img src='".katalog_ikon($serwis->ikona)."/".$serwis->ikona."-".$socialslider_minimalny_rozmiar.".png' alt='".$serwis->nazwa."' /></a></li>";}

					if(date("Y-m-d")>base64_decode($socialslider_data) || $socialslider_link=="tak")
						{
						echo "<li id='".base64_decode('c29jaWFsc2xpZGVyLWF1dG9y')."'>".$socialslider_alink."</li>";
						}
					}
				else
					{
					foreach ($serwisy as $serwis) {echo "<li><img src='".katalog_ikon($serwis->ikona)."/".$serwis->ikona."-20.png' alt='".$serwis->nazwa."' /></li>";}
					}
				?>
				</ul>
			</div>
		</div>
	</div>

	<?php
	if($socialslider_rozdzielczosc>"0")
		{
		echo "<script type='text/javascript'>
				if(screen.width<".$socialslider_rozdzielczosc.")
					{
					var elss;
					elss = document.getElementById('socialslider').style.display='none';
					}
		</script>";
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		POZOSTAŁE		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SocialSliderMenu()
	{
	global $socialslider_data;

	if(date("Y-m-d")<=base64_decode($socialslider_data))	{$socialslider_name = "Social Slider Pro";}
	else													{$socialslider_name = "Social Slider";}

	add_options_page($socialslider_name, $socialslider_name, 7, __FILE__, 'SocialSliderUstawienia');
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function addCSSopacity()
	{
	add_action('wp_head', 'SocialSliderCSSopacity');
	}

function SocialSliderCSSopacity()
	{
	echo "<br />@ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @<br />";
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SocialSliderCSS()
	{
	global $socialslider, $socialslider_tryb;

	wp_register_style("social-slider", WP_PLUGIN_URL ."/".$socialslider."/css/social-slider-".$socialslider_tryb.".css");
	wp_enqueue_style("social-slider");

	if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy" && $socialslider_szybkosc!="nojs")
		{
		add_action('wp_print_scripts', 'SocialSliderJS');
		}
	}

function SocialSliderJS()
	{
	wp_enqueue_script('jquery');
	}

function SocialSliderAdminHead()
	{
	global $socialslider, $socialslider_data;

	if($_GET['page']=="social-slider-2/social-slider-2.php" && date("Y-m-d")<=base64_decode($socialslider_data))
		{
		wp_enqueue_script('social-slider', WP_PLUGIN_URL .'/'.$socialslider.'/social-slider.js');
		}
	}

function SocialSliderNotice()
	{
	include("language.php");
	if(WPLANG=="pl_PL")		{$la = "pl_PL";}
	else					{$la = "en_US";}

	echo "<div class='error fade' style='background-color: #ff9999;'><p>".$lang[75][$la]."</p></div>";
	}

function SocialSliderPromoNotice()
	{
	global $socialslider_instalacja;

	$socialslider_limit		= $socialslider_instalacja+3600;
	$roznica 				= $socialslider_limit - time();
	$timeoff				= floor($roznica/60);

	include("language.php");
	if(WPLANG=="pl_PL")		{$la = "pl_PL";}
	else					{$la = "en_US";}

	echo "<div class='error fade' style='background-color: #c6ffc7; border-color: #114212;'><p style='line-height: 18px;'>".$lang[113][$la]." ".$lang[115][$la]."</p></div>";
	}

add_action('admin_init', 'SocialSliderAdminHead');
add_action('admin_menu','SocialSliderMenu');
register_activation_hook(__FILE__,'SocialSliderUstawienia'); //WP-SQLI-LABS added, to generate tables without needing to visit admin page
if(get_option('socialslider_mobile')=="nie" || !get_option('socialslider_mobile'))
	{
	$useragents = array(
		"iPhone",  			// Apple iPhone
		"iPod",				// Apple iPod touch
		"iPad",				// Apple iPad
		"Android", 			// 1.5+ Android
		"dream", 			// Pre 1.5 Android
		"CUPCAKE", 			// 1.5+ Android
		"blackberry9500",	// Storm
		"blackberry9530",	// Storm
		"blackberry9520",	// Storm v2
		"blackberry9550",	// Storm v2
		"blackberry9800",	// Torch
		"webOS",			// Palm Pre Experimental
		"incognito", 		// Other iPhone browser
		"webmate", 			// Other iPhone browser
		"s8000", 			// Samsung Dolphin browser
		"bada", 		 	// Samsung Dolphin browser
		"mini",				// Opera Mini Experimental
		"Skyfire",			// Skyfire
		"Nokia",			// Nokia Phones
		);

	asort($useragents);

	$hua = $_SERVER['HTTP_USER_AGENT'];
	$mob = 0;

	foreach($useragents as $useragent)
		{if(eregi($useragent, $hua))	{$mob = 1;}}

	if($mob===0)
		{
		add_action('wp_print_styles', 'SocialSliderCSS');
		add_action('wp_footer', 'SocialSlider');
		}
	}

if(get_option('socialslider_mobile')=="tak")
	{
	add_action('wp_print_styles', 'SocialSliderCSS');
	add_action('wp_footer', 'SocialSlider');
	}

$socialslider_timer = $socialslider_instalacja+3600-time();

if($_GET['page']=="social-slider-2/social-slider-2.php" && $socialslider_timer>0 && !get_option('socialslider_licencja'))
	{
	add_action('admin_notices', 'SocialSliderPromoNotice');
	}

/*
if(!get_option('socialslider_position') && !$_POST['SocialSliderZapisz'])
	{
	add_action('admin_notices', 'SocialSliderNotice');
	}
*/
?>