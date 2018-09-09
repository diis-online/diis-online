<? if (empty($script_code)): exit; endif;

echo "<form target='_top' action-xhr='?view=share&share=". $share_info['share_id'] ."&action=save' method='post'>";
echo "<input type='hidden' name='share_id' value='".$share_info['share_id']."'>";

echo "<textarea name='body' placeholder='Write here...' required>";

// save draft button.... this will just update the draft...

// if the user is the admin doing the review then give a publish live button... this will update the draft and published item...

// View all annotations

echo "<div submit-success><template type='amp-mustache'>Success!</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";

echo "</form>"; ?>
