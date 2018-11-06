<? if (empty($script_code)): exit; endif;

echo "<span id='navigation-chooser-feed-button' amp-fx='parallax' data-parallax-factor='1.4'><i class='material-icons'>refresh</i> ". $translatable_elements['refresh-shares'][$language_request] ."</span>";

echo "<div id='feed-window-mission-alignment' amp-fx='parallax' data-parallax-factor='1.2'>";
	echo "<h1>".$translatable_elements['coming-soon'][$language_request]."</h1>";
	echo "<p>".$translatable_elements['mission-statement'][$language_request]."</p>";
	echo "</div>";

echo "<div id='feed-window-who-we-are-alignment' amp-fx='parallax' data-parallax-factor='1.05'>";
	echo "<h2>".$translatable_elements['unsilenced'][$language_request]."</h2>";
	echo "<p>".$translatable_elements['who-we-are'][$language_request]."</p>";
	echo "</div>";

// echo "<amp-img id='home-window-logo' alt='⨟' src='home-window-logo.jpg' width='1' height='1' layout='responsive' sizes='(min-width: 300px) 150px, 50vw' amp-fx='parallax' data-parallax-factor='1.04'></amp-img>";

if (empty($login_status)):
	echo "<div id='feed-window-become-a-publisher-alignment' amp-fx='parallax' data-parallax-factor='1.05'>";
		echo "<h2>".$translatable_elements['become-a-publisher'][$language_request]."</h2>";
		echo "<p>".$translatable_elements['publisher-join-instructions'][$language_request]."</p>";
		echo "<a href='?view=register' amp-fx='parallax' data-parallax-factor='0.98'><span id='home-window-create-button'>".$translatable_elements['create-account'][$language_request]."</span></a>";
		echo "</div>";
	endif;

echo "<div id='feed-window-safety-first-alignment'>";
	echo "<h2>".$translatable_elements['safety-first'][$language_request]."</h2>";
	echo "<p>".$translatable_elements['safety-description'][$language_request]."</p>";
	echo "</div>";

footer(); ?>
