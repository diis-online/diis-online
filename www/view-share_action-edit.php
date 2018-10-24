<? if (empty($script_code)): exit; endif;

if (!(empty($share_info['content_approved']))):
	echo "<amp-lightbox id='edit-window-approved-post-lightbox' layout='nodisplay'>";
	echo "<span id='edit-window-approved-post-close-button' role='button' tabindex='0' on='tap: edit-window-approved-post-lightbox.close'><i class='material-icons'>cancel</i> Close approved post</span>";
	echo "<div id='edit-window-approved-post-alignment'>";
	echo "<span id='edit-window-approved-post-header'>". $translatable_elements['approved-post-live-on-website'][$language_request] ."</span>";
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
	echo "<span id='edit-window-approved-post-open-button' role='button' tabindex='0' on='tap: edit-window-approved-post-lightbox.open'><i class='material-icons'>visibility</i> ". $translatable_elements['review-approved-post'][$language_request] ."</span>";
	endif;

// Put identifier here...
echo "<textarea name='content_draft' placeholder='". $translatable_elements['write-here'][$language_request] ."' id='edit-window-draft-textarea' on='input-debounced:edit-window-form.submit,edit-window-form-submission-alert-empty-state.hide' required>".$share_info['content_draft']."</textarea>";

if (!(empty($share_info['content_approved']))):
	echo "<button id='edit-window-reset-button' type='reset'><i class='material-icons'>cancel_presentation</i> ". $translatable_elements['undo-changes'][$language_request] ."</button>";
	endif;

// echo "<span id='edit-window-save-button' role='button' tabindex='0' on='tap:edit-window-form-submission-alert-empty-state.hide,edit-window-form.submit'>". $translatable_elements['save-work'][$language_request] ."</span>";

// We need to add something about setting the relationship

echo "</div>";

echo "<div id='edit-window-form-submission-notice-alignment'>";
echo "<div id='edit-window-form-submission-notice'>";
	if (empty($share_info['content_draft'])): echo "<span id='edit-window-form-submission-alert-empty-state'>". $translatable_elements['not-saved-yet'][$language_request] ."</span>";
	else: echo "<span id='edit-window-form-submission-alert-empty-state'>". $translatable_elements['no-changes-to-save'][$language_request] ."</span>"; endif;
	echo "<span id='edit-window-form-submission-alert-success' valid>Ready to save.</span>";
	echo "<span id='edit-window-form-submission-alert-success' invalid>". $translatable_elements['text-empty'][$language_request] ."</span>";
	echo "<span id='edit-window-form-submission-alert-success' submit-success><template type='amp-mustache'>". $translatable_elements['saved'][$language_request] ." <amp-timeago id='edit-window-form-submit-timeago' layout='responsive' height='20' width='100' datetime='{{{time}}}' locale='en'>{{{time}}}</amp-timeago>.</template></span>";
	echo "<span id='edit-window-form-submission-alert-failure' submit-error><template type='amp-mustache'>". $translatable_elements['not-saved'][$language_request] ." {{{message}}} &nbsp; <span id='edit-window-form-submission-alert-failure-try-again-button' role='button' tabindex='0' on='tap:edit-window-form.submit'>". $translatable_elements['try-again'][$language_request] ."</span></template></span>";
	echo "<span submitting>". $translatable_elements['sending-to-server'][$language_request] ."</span>";
echo "</div></div>";

// if (($share_info['author_id'] !== $login_status['user_id']) && (in_array($login_status['level'], ["administrator", "editor"]))):
if ($share_info['author_id'] == $login_status['user_id']):
	echo "<div id='edit-window-form-instructions'><p>". $translatable_elements['when-you-finish-writing-instructions'][$language_request] ."</p>";
	echo "<span id='edit-window-submit-button' role='button' tabindex='0' on='tap:AMP.setState({content_status_state: \"pending\"}),edit-window-form-submission-alert-empty-state.hide,edit-window-form.submit'>". $translatable_elements['submit-for-review'][$language_request] ."</span>";
	echo "<span id='edit-window-submit-button-caution'>". $translatable_elements['caution-this-cannot-be-undone'][$language_request] ."</span></div>";
	endif;

echo "</form>";

echo "<div id='edit-window-annotations-alignment'>";

echo "<form target='_top' action-xhr='?view=share&parameter=". $share_info['share_id'] ."&action=updates' method='post'>";

echo "<span id='edit-window-annotations-header'><i class='material-icons'>all_inbox</i> ". $translatable_elements['annotations'][$language_request] ."</span>";
echo "<button on='tap:edit-window-annotations-list.refresh' id='edit-window-annotations-refresh-button'><i class='material-icons'>refresh</i> ". $translatable_elements['check-for-updates'][$language_request] ."</button>";

// Put identifier
echo "<textarea name='body' placeholder='". $translatable_elements['write-here'][$language_request] ."' id='edit-window-annotations-textarea'></textarea>";
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
