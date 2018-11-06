<? if (empty($script_code)): exit; endif;

// First, they have to pick a pseudonym
echo "<div id='feed-window-shares-alignment'>";

	echo "<amp-state id='feedmore' src='https://ampbyexample.com/json/related_products.json'></amp-state>";
//	echo "<amp-state id='feedmore' src='/json/related_products.json'></amp-state>";

	// So this initializes feedmore with empty values
	echo "<amp-state id='feedpaging'><script type='application/json'>";
	echo '{ "moreItemsPageIndex": 1, "hasMorePages": true }';
//	echo '{ "feedpagingindex": 1, "feedpagingnext": true }';
	echo "</script></amp-state>";

	// This will totally refresh the feed
	echo "<span id='feed-window-refresh-button' role='button' tabindex='0' on='tap:feed-window-shares.refresh'><i class='material-icons'>refresh</i> ". $translatable_elements['refresh-shares'][$language_request] ."</span>";

	// This is the feed itself
	$html_temp = [
		"id"		=> "feed-window-shares",
		"height"	=> "800",
		"height"	=> "900",
		"[height]"	=> "feedmore.items.length * 40",
		"src"		=> "https://ampbyexample.com/json/related_products.json",
//		"src"		=> "https://diis.online/?view=feed&action=xhr&language=". $language_request,
		"[src]"		=> "feedmore.items",
		];
	echo "<amp-list ". html_implode($html_temp) .">";
	echo "<span id='feed-window-shares-fallback' fallback>". $translatable_elements['failed-to-load'][$language_request] ."</span>";
	echo "<template type='amp-mustache'><div id='feed-window-share'>";
		echo "{{img}}";
		echo "<br>";
		echo "{{name}}";
		echo "<br><br>";
	echo "</div></template></amp-list>";

	$amp_setstate_temp = [
		"feedmore" => [
			"items" => "feedmore.items.concat(event.response.items)" ],
		"feedpaging" => [
			"moreItemsPageIndex" => "feedpaging.moreItemsPageIndex + 1",
			"hasMorePages" => "event.response.hasMorePages" ],
//			"feedpagingindex" => "feedmore.items.concat(event.response.items)",
//			"feedpagingnext" => "event.response.feedpaginnext" ],
		];
	$html_temp = [
		"method"	=> "get",
		"action-xhr"	=> "https://ampbyexample.com/json/more_related_products_page",
//		"action-xhr"	=> "https://diis.online/",
		"target"	=> "_top",
		"on"		=> "submit-success: AMP.setState(". json_encode($amp_setstate_temp) .");",
		];
	echo "<form ". html_implode($html_temp) .">";
	echo "<input type='hidden' name='moreItemsPageIndex' value='0' [value]='product.moreItemsPageIndex'>";
	echo "<input type='submit' value='Show more'>";
	echo "</form>";

	echo "<span id='feed-window-load-more-button' role='button' tabindex='0' on='tap:feed-window-shares.refresh' amp-fx='fade-in' data-easing='linear' data-margin-start='2%' data-duration='1000ms'><i class='material-icons'>timeline</i> ". $translatable_elements['load-more'][$language_request] ."</span>";

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
