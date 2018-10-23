<? if (empty($script_code)): exit; endif;

if (!(empty($share_info['content_approved']))):
	echo "<amp-lightbox id='edit-window-approved-post-lightbox' layout='nodisplay'>";
	echo "<span id='edit-window-approved-post-close-button' role='button' tabindex='0' on='tap: edit-window-approved-post-lightbox.close'><i class='material-icons'>cancel</i> Close approved post</span>";
	echo "<div id='edit-window-approved-post-alignment'>";
	echo "<span id='edit-window-approved-post-header'>Approved post, live on website</span>";
	echo "<hr class='edit-window-stroke'>";
	echo $share_info['content_approved'];
	echo "</div>";
	echo "</amp-lightbox>";
	endif;

$form_url = "https://diis.online/?view=share&parameter=". $share_info['share_id'] ."&action=xhr&language=".$language_request;
echo "<form id='edit-window-form' target='_top' action-xhr='".$form_url."' action='".$form_url."' method='post'>"; // Use action attribute so input type submit works

echo "<div id='edit-window-edit-post-alignment'>";

echo "<input type='hidden' name='share_id' value='".$share_info['share_id']."'>";

if (!(empty($share_info['content_approved']))):
	echo "<span id='edit-window-approved-post-open-button' role='button' tabindex='0' on='tap: edit-window-approved-post-lightbox.open'><i class='material-icons'>visibility</i> Review approved post</span>";
	endif;

// Put identifier here...
echo "<textarea name='content_draft' placeholder='Write here...' id='edit-window-draft-textarea' on='input-debounced:edit-window-form.submit,edit-window-form-not-submitted.hide' required>".$share_info['content_draft']."</textarea>";

if (!(empty($share_info['content_approved']))):
	echo "<button id='edit-window-reset-button' type='reset'><i class='material-icons'>cancel_presentation</i> Undo changes</button>";
	endif;

// echo "<input id='edit-window-save-button' type='submit' name='content_status' value='Save draft'>";

// We need to add something about setting the relationship

echo "</div>";

if (empty($share_info['content_draft'])): echo "<div id='edit-window-form-not-submitted'>Not saved yet. Nothing written!</div>";
else: echo "<div id='edit-window-form-not-submitted'>No new changes saved yet.</div>"; endif;

if (($share_info['author_id'] !== $login_status['user_id']) && (in_array($login_status['level'], ["administrator", "editor"]))):
	echo "Publishing on Diis is as easy as ❶❷❸:<br>
	1) Write what is on your mind in the space above.<br>
	2) Make sure your draft is saved.<br>
	3) When finished, submit it for review.";
	endif;

echo "<div submit-success><template type='amp-mustache'>Saved <amp-timeago id='edit-window-form-submit-timeago' layout='responsive' height='20' width='100' datetime='{{{time}}}' locale='en'>{{{time}}}</amp-timeago>. {{{message}}}</template></div>";
echo "<div submit-error><template type='amp-mustache'>Not saved. Last tried <amp-timeago id='edit-window-form-submit-timeago' layout='responsive' height='20' width='100' datetime='{{{time}}}' locale='en'>{{{time}}}</amp-timeago>. {{{message}}}</template></div>";
echo "<div submitting><template type='amp-mustache'>Saving...</template></div>";



//	echo "<hr class='edit-window-stroke'>";
//	echo "<input id='edit-window-publish-button' type='submit' name='content_status' value='Save and publish online' on='tap:edit-window-edit-post-alignment.toggle'>";
// elseif ( !(empty($share_info['content_draft'])) && ($share_info['content_status'] !== "pending") ):
//	echo "<input id='edit-window-submit-button' type='submit' name='content_status' value='Submit for publication' on='tap:edit-window-edit-post-alignment.toggle'>";
	// You will not be able to make further edits after submitting for publication

echo "</form>";

echo "<hr class='edit-window-stroke'>";

echo "<div id='edit-window-annotations-alignment'>";

echo "<form target='_top' action-xhr='?view=share&parameter=". $share_info['share_id'] ."&action=updates' method='post'>";

echo "<span id='edit-window-annotations-header'><i class='material-icons'>all_inbox</i> Annotations</span>";
echo "<button on='tap:edit-window-annotations-list.refresh' id='edit-window-annotations-refresh-button'><i class='material-icons'>refresh</i> Check for updates</button>";

// Put identifier
echo "<textarea name='body' placeholder='Add annotation...' id='edit-window-annotations-textarea'></textarea>";
echo "<input type='submit' id='edit-window-annotations-annotation-button' name='submit_annotation' value='Add annotation'>";

echo "<amp-list id='edit-window-annotations-list' max-items='10' src='https://diis.online/?view=share&parameter=".$share_info['share_id']."&action=updates&language=".$language_request."'>";
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
