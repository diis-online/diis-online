<? if (empty($script_code)): exit; endif;

echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=xfr' method='post'>";
echo "<input type='hidden' name='share_id' value='".$share_info['share_id']."'>";

echo "<textarea name='body' placeholder='Write here...' id='edit-window-textarea' required></textarea>";

echo "<button id='edit-window-publish'>Save draft</button>";

if (($share_info['author_id'] !== $login_status['user_id']) && (in_array($login_status['level'], ["administrator", "editor"]))):
	echo "<button id='edit-window-publish'>Publish</button>";
	endif;

echo "<div submit-success><template type='amp-mustache'>Success!</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";

echo "</form>";
echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=save' method='post'>";
echo "</form>";

echo "<button on='tap:annotations-list.refresh'>Refresh annotations</button>";
echo "<amp-list id='annotations-list' src='https://diis.online?view=share&share=".$share_request."&action=updates'>";
echo "<div placeholder>Loading ...</div>";
echo "<div fallback>Failed to load data.</div>";
echo "<template type='amp-mustache'>";
	echo "<div class='annotations-list-item'><span>Author:{{user_id}}</span><span>Time: {{annotation_timestamp}}</span><span>Contents: {{annotation_text}}</div>";
echo "</template></amp-list>";
	
echo "</form>"; ?>
