<? if (empty($script_code)): exit; endif;

//

echo "<h1 amp-fx='parallax' data-parallax-factor='1.2'>".$translatable_elements['coming-soon'][$language_request]."</h1>";
echo "<p>".$translatable_elements['mission-statement'][$language_request]."</p>";

echo "<h2 amp-fx='parallax' data-parallax-factor='1.05'>".$translatable_elements['unsilenced'][$language_request]."</h2>";
echo "<p>".$translatable_elements['who-we-are'][$language_request]."</p>";

if (empty($login_status)):
	echo "<p></p>";
	endif;

echo "<amp-img id='home-window-logo' alt='⨟' src='home-window-logo.jpg' width='1' height='1' layout='responsive' sizes='(min-width: 300px) 150px, 50vw' amp-fx='parallax' data-parallax-factor='1.04'></amp-img>";

if (empty($login_status)):
	echo "<h2 amp-fx='parallax' data-parallax-factor='1.05'>".$translatable_elements['become-a-publisher'][$language_request]."</h2>";
	echo "<p amp-fx='parallax' data-parallax-factor='1.03'>".$translatable_elements['publisher-join-instructions'][$language_request]."</p>";
	echo "<a href='?view=register' amp-fx='parallax' data-parallax-factor='0.98'><span id='home-window-register-button'>".$translatable_elements['create-account'][$language_request]."</span></a>";
	endif;

echo "<h2>".$translatable_elements['safety-first'][$language_request]."</h2>";
echo "<p>".$translatable_elements['safety-description'][$language_request]."</p>";

footer(); ?>
