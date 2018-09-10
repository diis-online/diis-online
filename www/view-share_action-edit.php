<? if (empty($script_code)): exit; endif;

echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=save' method='post'>";
echo "<input type='hidden' name='share_id' value='".$share_info['share_id']."'>";

echo "<textarea name='body' placeholder='Write here...' id='edit-window-textarea' required></textarea>";

// save draft button.... this will just update the draft...

if (($share_info['author_id'] !== $login_status['user_id']) && (in_array{$login_status['level'], ["administrator", "editor"]))):
	echo "<button id='edit-window-publish'>Publish</button>";
	endif;
// View all annotations

echo "<div submit-success><template type='amp-mustache'>Success!</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";

echo "</form>"; ?>
