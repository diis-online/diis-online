<? if (empty($script_code)): exit; endif;

echo "<span id='feed-window-refresh-button' role='button' tabindex='0' on='tap:feed-window-shares.refresh'><i class='material-icons'>refresh</i> ". $translatable_elements['refresh-shares'][$language_request] ."</span>";

// First, they have to pick a pseudonym
echo "<div id='feed-window-shares-alignment'>";
	echo "<amp-list id='feed-window-shares' max-items='5' height='900' layout='fixed-height' src='https://diis.online/?view=feed&action=updates&language=". $language_request ."'>";
	echo "<span id='feed-window-shares-fallback' fallback>". $translatable_elements['failed-to-load'][$language_request] ."</span>";
	echo "<template type='amp-mustache'><div id='feed-window-share'>";
		echo "{{name}}";
		echo "<br>";
		echo "{{body}}";
		echo "<br><br>";
	echo "</div></template></amp-list>";

	echo "<span role='button' tabindex='0' on='tap:feed-window-shares.refresh'><i class='material-icons'>timeline</i> ". $translatable_elements['load-more'][$language_request] ."</span>";

	echo "</div>";

echo "<div id='feed-window-mission-alignment' amp-fx='fade-in' data-easing='linear' data-margin-start='2%' data-duration='1000ms'>";
	echo "<h2 amp-fx='parallax' data-parallax-factor='1.05'>".$translatable_elements['welcome-to-diis'][$language_request]."</h2>";
	echo "<p amp-fx='parallax' data-parallax-factor='1.05'>".$translatable_elements['mission-statement'][$language_request]."</p>";
	echo "</div>";

echo "<div id='feed-window-who-we-are-alignment' amp-fx='fade-in' data-easing='linear' data-margin-start='2%' data-duration='1200ms'>";
	echo "<h2 amp-fx='parallax' data-parallax-factor='1.03'>".$translatable_elements['unsilenced'][$language_request]."</h2>";
	echo "<p amp-fx='parallax' data-parallax-factor='1.03'>".$translatable_elements['who-we-are'][$language_request]."</p>";
	echo "</div>";

if (empty($login_status)):
	echo "<div id='feed-window-become-a-publisher-alignment' amp-fx='fade-in' data-easing='linear' data-margin-start='2%' data-duration='1400ms'>";
		echo "<h2>".$translatable_elements['become-a-publisher'][$language_request]."</h2>";
		echo "<p>".$translatable_elements['publisher-join-instructions'][$language_request]."</p>";
		echo "<a href='?view=register' amp-fx='parallax' data-parallax-factor='0.98'><span id='feed-window-create-button'>".$translatable_elements['create-account'][$language_request]."</span></a>";
		echo "</div>";
	endif;

echo "<div id='feed-window-safety-first-alignment' amp-fx='fade-in' data-easing='linear' data-margin-start='2%' data-duration='1600ms'>";
	echo "<h2>".$translatable_elements['safety-first'][$language_request]."</h2>";
	echo "<p>".$translatable_elements['safety-description'][$language_request]."</p>";
	echo "</div>";

footer(); ?>
