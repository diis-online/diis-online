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

echo "<form target='_top' action-xhr='https://diis.online/?view=share&action=xhr&language=".$language_request."' method='post'>";

echo "<input type='hidden' name='share_id' value='". $action_request ."'>";
echo "<input type='hidden' name='content_status' value='draft'>";

if ( ($action_request == "translate") && !(empty($share_info)) ):
	// Something about relationships to mark what's it in relation to as hidden inputs
elseif ( ($action_request == "reply") && !(empty($share_info)) ):
	// Something about relationships to mark what's it in relation to as hidden inputs
elseif ($action_request == "create"):
	echo "<p>". $translatable_elements['need-ideas'][$language_request] ."<br>";
	echo $create_inspiration_array[array_rand($create_inspiration_array)] ."</p>";
	endif;

echo "<input id='create-window-button' type='submit' name='submit' value='". $translatable_elements['create-now'][$language_request] ."'>";

echo "</form>"; ?>
