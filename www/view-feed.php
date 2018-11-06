<? if (empty($script_code)): exit; endif;

// First, they have to pick a pseudonym
echo "<div id='feed-window-shares-alignment'>";

	echo "<amp-state id='feedcontent' src='https://diis.online/?view=feed&action=updates&language=". $language_request ."'></amp-state>";

	// So this initializes the pagination with default values
	echo "<amp-state id='feedpagination'><script type='application/json'>";
	echo '{ "pagenumber": 1, "morepages": true }';
	echo "</script></amp-state>";

	// This will totally refresh the feed
	$amp_setstate_temp = "{
		'feedcontent': { 'items': event.response.items },
		'feedpagination':  { pagenumber: feedpagination.pagenumber + 1, morepages: event.response.morepages } }";
	$html_temp = [
		"id"		=> "feed-window-refresh-form",
		"method"	=> "post",
		"action-xhr"	=> "https://diis.online/?view=feed&action=updates&language=". $language_request,
		"target"	=> "_top",
		"on"		=> "submit-success: AMP.setState(".$amp_setstate_temp.");",
		];
	echo "<form ". html_implode($html_temp) .">";
	echo "<input type='hidden' name='pagenumber' value='1' [value]='feedpagination.pagenumber'>";
	$html_temp = [
		"id"		=> "feed-window-refresh-button",
		"role"		=> "button",
		"tabindex"	=> "0",
		"on"		=> "tap:feed-window-refresh-form.submit",
		"amp-fx"	=> "fade-in",
		"data-easing"	=> "linear",
		];
	echo "<span ". html_implode($html_temp) ."><i class='material-icons'>refresh</i> ". $translatable_elements['refresh-shares'][$language_request] ."</span>";
	echo "</form>";

	// This is the feed itself
	$html_temp = [
		"id"		=> "feed-window-shares",
		"width"		=> "800",
		"height"	=> "570px",
		"[height]"	=> "ceil(feedcontent.items.length/2) * 190 + 'px'",
		"heights"	=> "(min-width:700 px) 570px, 950px",
		"[heights]"	=> "'(min-width:700 px) '+ceil(feedcontent.items.length/2)*190+'px, '+ feedcontent.items.length*190+'px'",
		"src"		=> "https://diis.online/?view=feed&action=updates&language=". $language_request,
		"[src]"		=> "feedcontent.items",
		];
	echo "<amp-list ". html_implode($html_temp) .">";
	echo "<span id='feed-window-shares-fallback' fallback>". $translatable_elements['failed-to-load'][$language_request] ."</span>";
	echo "<template type='amp-mustache'><div id='feed-window-share'>";
		echo "<span id='feed-window-share-read-more'>Read more...</span>";
		echo "<span id='feed-window-share-title'>{{name}}</span>";
		echo "<span id='feed-window-share-body'>{{body}}</span>";
	echo "</div></template>
	</amp-list>";

	$amp_setstate_temp = "{
		'feedcontent': { 'items': feedcontent.items.concat(event.response.items) },
		'feedpagination':  { pagenumber: feedpagination.pagenumber + 1, morepages: event.response.morepages } }";
	$html_temp = [
		"id"		=> "feed-window-form",
		"method"	=> "post",
		"action-xhr"	=> "https://diis.online/?view=feed&action=updates&language=". $language_request,
		"target"	=> "_top",
		"on"		=> "submit-success: AMP.setState(".$amp_setstate_temp.");",
		];
	echo "<form ". html_implode($html_temp) .">";
	echo "<input type='hidden' name='pagenumber' value='1' [value]='feedpagination.pagenumber'>";
	$html_temp = [
		"id"		=> "feed-window-load-more-button",
		"role"		=> "button",
		"tabindex"	=> "0",
		"on"		=> "tap:feed-window-form.submit",
		"amp-fx"	=> "fade-in",
		"data-easing"	=> "linear",
		"[text]"	=> "(feedpagination.morepages == 'false' ? '". $translatable_elements['nothing-more'][$language_request] ."' : '". $translatable_elements['fetch-more'][$language_request] ."')",
		];
	echo "<span ". html_implode($html_temp) .">". $translatable_elements['fetch-more'][$language_request] ."</span>";
	echo "</form>";

	echo "</div>";

echo "<div id='feed-window-mission-alignment' amp-fx='fade-in' data-easing='linear' data-margin-start='2%' data-duration='1000ms'>";
	echo "<h2 amp-fx='parallax' data-parallax-factor='1.05'>".$translatable_elements['our-mission'][$language_request]."</h2>";
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
