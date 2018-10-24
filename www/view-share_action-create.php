<? if (empty($script_code)): exit; endif;

// Statements for inspiration
$create_inspiration_array = [
	$translatable_elements['be-daring'][$language_request],
	$translatable_elements['smash-the-fucking-patriarchy'][$language_request],
	$translatable_elements['show-the-truth'][$language_request],
	$translatable_elements['destroy-racist-ignorance'][$language_request],
	$translatable_elements['tell-your-story'][$language_request],
	$translatable_elements['reclaim-your-voice'][$language_request],
	$translatable_elements['speak-directly-to-the-world'][$language_request],
	];

echo "<h1>".$translatable_elements['create-a-share'][$language_request]."</h1>";

echo "<form id='create-window-form' target='_top' action-xhr='https://diis.online/?view=share&action=xhr&language=".$language_request."' method='post'>";

echo "<input type='hidden' name='content_status' value='new'>";

// Can be create, reply, or translate
echo "<input type='hidden' name='relationship_type' value='". $action_request ."'>";

// What is being replied to or translated
if (in_array($action_request, [ "reply", "translate" ]) && !(empty($share_info)) ): echo "<input type='hidden' name='relationship_to' value='". $share_info['share_id'] ."'>"; endif;

if ( ($action_request == "translate") && !(empty($share_info)) ):
	// Something about relationships to mark what's it in relation to as hidden inputs
elseif ( ($action_request == "reply") && !(empty($share_info)) ):
	// Something about relationships to mark what's it in relation to as hidden inputs
elseif ($action_request == "create"):
	echo "<p>". $translatable_elements['need-ideas'][$language_request] ."<br>";
	echo $create_inspiration_array[array_rand($create_inspiration_array)] ."</p>";
	endif;

echo "<span id='create-window-button' role='button' tabindex='0' on='tap:edit-window-form-submission-alert-empty-state.hide,create-window-form.submit'>". $translatable_elements['create-now'][$language_request] ."</span>";

echo "<div id='edit-window-form-submission-alert-success' submit-success><template type='amp-mustache'>". $translatable_elements['saved'][$language_request] ." <amp-timeago id='edit-window-form-submit-timeago' layout='responsive' height='20' width='100' datetime='{{{time}}}' locale='en'>{{{time}}}</amp-timeago>.</template></div>";
echo "<div id='edit-window-form-submission-alert-failure' submit-error><template type='amp-mustache'>". $translatable_elements['not-saved'][$language_request] ." {{{message}}} &nbsp; <span id='edit-window-form-submission-alert-failure-try-again-button' role='button' tabindex='0' on='tap:edit-window-form.submit'>". $translatable_elements['try-again'][$language_request] ."</span></template></div>";
echo "<div submitting>". $translatable_elements['sending-to-server'][$language_request] ."</div>";

echo "</form>"; ?>
