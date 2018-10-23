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

echo "<input type='hidden' name='content_status' [value]='content_status_state'>";

if (!(empty($share_info['content_approved']))):
	echo "<span id='edit-window-approved-post-open-button' role='button' tabindex='0' on='tap: edit-window-approved-post-lightbox.open'><i class='material-icons'>visibility</i> Review approved post</span>";
	endif;

// Put identifier here...
echo "<textarea name='content_draft' placeholder='Write here...' id='edit-window-draft-textarea' on='input-debounced:edit-window-form.submit,edit-window-form-submission-alert-empty-state.hide' required>".$share_info['content_draft']."</textarea>";

if (!(empty($share_info['content_approved']))):
	echo "<button id='edit-window-reset-button' type='reset'><i class='material-icons'>cancel_presentation</i> Undo changes</button>";
	endif;

// echo "<span id='edit-window-save-button' role='button' tabindex='0' on='tap:edit-window-form-submission-alert-empty-state.hide,edit-window-form.submit'>Save work.</span>";

// We need to add something about setting the relationship

echo "</div>";

echo "<div id='edit-window-form-submission-notice-alignment'>";
echo "<div id='edit-window-form-submission-notice'>";
	if (empty($share_info['content_draft'])): echo "<span id='edit-window-form-submission-alert-empty-state'>Not saved yet.</span>";
	else: echo "<span id='edit-window-form-submission-alert-empty-state'>No changes to save.</span>"; endif;
	echo "<span id='edit-window-form-submission-alert-success' submit-success><template type='amp-mustache'>Saved <amp-timeago id='edit-window-form-submit-timeago' layout='responsive' height='20' width='100' datetime='{{{time}}}' locale='en'>{{{time}}}</amp-timeago>.</template></span>";
	echo "<span id='edit-window-form-submission-alert-failure' submit-error><template type='amp-mustache'>Not saved. {{{message}}} &nbsp; <span id='edit-window-form-submission-alert-failure-try-again-button' role='button' tabindex='0' on='tap:edit-window-form.submit'>Try again.</span></template></span>";
	echo "<span submitting><template type='amp-mustache'>Syncing...</template></span>";
echo "</div></div>";

// if (($share_info['author_id'] !== $login_status['user_id']) && (in_array($login_status['level'], ["administrator", "editor"]))):
if ($share_info['author_id'] == $login_status['user_id']):
	echo "<div id='edit-window-form-instructions'>";
	echo "<span id='edit-window-form-instructions-header'>Sharing on Diis is easy.</span>";
	echo "<div id='edit-window-form-instructions-list'>1) Write something.<br>";
	echo "2) Save your work.<br>";
	echo "3) Submit for review.</span></span>";
	echo "<span id='edit-window-submit-button' role='button' tabindex='0' on='tap:AMP.setState({content_status_state: \"pending\"}),edit-window-form-submission-alert-empty-state.hide,edit-window-form.submit'>Submit for review.</span>";
	echo "<span id='edit-window-submit-button-caution'>Caution! This cannot be undone.</span>";
	endif;

echo "</form>";

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
