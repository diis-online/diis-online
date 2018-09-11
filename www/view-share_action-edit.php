<? if (empty($script_code)): exit; endif;

echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=xfr' method='post'>";
echo "<input type='hidden' name='share_id' value='".$share_info['share_id']."'>";

if (!(empty($share_info['content_approved']))):

	// Button to toggle on the show-more approved content

	echo "<button id='edit-window-show-approved-button' on='tap: edit-window-approved-post-lightbox'>Show approved post</button>";

	echo "<amp-lightbox id='edit-window-approved-post-lightbox' layout='nodisplay'>";
	echo "<button id='edit-window-close-approved-button' on='tap: edit-window-approved-post-lightbox.close'>Close</button>";
	echo $share_info['content_approved'];
	echo "</amp-lightbox>";

	echo "<button id='edit-window-publish'>Reset draft</button>";

	echo "<hr id='edit-window-stroke'>";

	endif;

echo "<textarea name='body' placeholder='Write here...' id='edit-window-textarea' required>".$share_info['content_draft']."</textarea>";

echo "<button id='edit-window-publish'>Save draft</button>";

echo "<hr id='edit-window-stroke'>";

if (($share_info['author_id'] !== $login_status['user_id']) && (in_array($login_status['level'], ["administrator", "editor"]))):
	echo "<button id='edit-window-publish'>Publish</button>";
	endif;

echo "<div submit-success><template type='amp-mustache'>Success!</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";

echo "</form>";
echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=save' method='post'>";
echo "</form>";

echo "<hr id='edit-window-stroke'>";

echo "<span class='edit-window-annotations-header'>Annotations</span>";
echo "<button on='tap:annotations-list.refresh' id='edit-window-annotations-button'>Refresh annotations</button>";

echo "<amp-list id='annotations-list' max-items='10' src='https://diis.online?view=share&share=".$share_request."&action=updates'>";
echo "<div id='edit-window-annotations-placeholder' placeholder>Loading ...</div>";
echo "<div id='edit-window-annotations-fallback' fallback>Failed to load data.</div>";
echo "<template type='amp-mustache'>";
	echo "<div class='edit-window-annotations-list-item'><span>Author:{{user_id}}</span><span>Time: {{annotation_timestamp}}</span><span>Contents: {{annotation_text}}</div>";
echo "</template></amp-list>";
	
echo "</form>"; ?>
