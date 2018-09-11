<? if (empty($script_code)): exit; endif;

$_SESSION['identifier'] = "UUSSSSS";

if (!(empty($share_info['content_approved']))):

	// Button to toggle on the show-more approved content

	echo "<button id='edit-window-approved-post-open-button' on='tap: edit-window-approved-post-lightbox.open'><i class='material-icons'>visibility</i> Review approved post</button>";

	echo "<amp-lightbox id='edit-window-approved-post-lightbox' layout='nodisplay'>";
	echo "<button id='edit-window-approved-post-close-button' on='tap: edit-window-approved-post-lightbox.close'><i class='material-icons'>cancel</i> Close approved post</button>";
	echo "<div id='edit-window-approved-post-alignment'>";
	echo "<span id='edit-window-approved-post-header'>Approved post, live on website</span>";
	echo "<hr class='edit-window-stroke'>";
	echo $share_info['content_approved'];
	echo "</div>";
	echo "</amp-lightbox>";

	endif;

echo "<div id='edit-window-edit-post-alignment'>";

echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=xfr' method='post'>";
echo "<input type='hidden' name='share_id' value='".$share_info['share_id']."'>";

// Put identifier here...
echo "<textarea name='body' placeholder='Write here...' id='edit-window-draft-textarea' required>".$share_info['content_draft']."</textarea>";

if (!(empty($share_info['content_approved']))):
	echo "<button id='edit-window-reset-button' type='reset'>Reset draft</button>";
	endif;

echo "<button id='edit-window-save-button' type='submit' name='submit' value='save'>Save draft</button>";

if (($share_info['author_id'] !== $login_status['user_id']) && (in_array($login_status['level'], ["administrator", "editor"]))):
	echo "<hr class='edit-window-stroke'>";
	echo "<button id='edit-window-publish-button' type='submit' name='submit' value='publish'><i class='material-icons'>public</i> Publish to website</button>";
	endif;

// We need to add something about setting the relationship

echo "<div submit-success><template type='amp-mustache'>Success!</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";

echo "</form>";

echo "</div>";

echo "<hr class='edit-window-stroke'>";

echo "<div id='edit-window-annotations-alignment'>";

echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=updates' method='post'>";

echo "<span id='edit-window-annotations-header'><i class='material-icons'>all_inbox</i> Annotations</span>";
echo "<button on='tap:edit-window-annotations-list.refresh' id='edit-window-annotations-refresh-button'><i class='material-icons'>refresh</i> Check for updates</button>";

// Put identifier
echo "<textarea name='body' placeholder='Add annotation...' id='edit-window-annotations-textarea'></textarea>";
echo "<button on='tap:edit-window-annotations-list.refresh' id='edit-window-annotations-annotation-button'><i class='material-icons'>note_add</i> Add annotation</button>";

echo "<amp-list id='edit-window-annotations-list' max-items='10' src='https://diis.online?view=share&share=".$share_request."&action=updates'>";
echo "<span id='edit-window-annotations-placeholder' placeholder><i class='material-icons'>sentiment_very_satisfied</i> Loading</span>";
echo "<span id='edit-window-annotations-fallback' fallback><i class='material-icons'>sentiment_dissatisfied</i> Failed to load data.</span>";
echo "<template type='amp-mustache'>";
	echo "<div class='edit-window-annotations-list-item'>";
	echo "<span class='edit-window-annotations-list-item-author'>From: {{user_id}}</span>";
	echo "<span class='edit-window-annotations-list-item-time'>Time: {{annotation_timestamp}}</span>";
	echo "<span class='edit-window-annotations-list-item-contents'>{{annotation_text}}</span>";
	echo "</div>";
echo "</template></amp-list>";

echo "</form>";

echo "</div>"; ?>
