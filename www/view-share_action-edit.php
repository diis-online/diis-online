<? echo "<form target='_top' action-xhr='?view=publish' method='post'>";
echo "<span>Post locked. Please enter post password.</span>";
echo "<input type='password' name='password' placeholder='Password' autocomplete='off' id='lock-window-password' required>";
echo "<input type='hidden' name='page' value='".$page_temp."'>";
echo "<button type='submit' name='unlock' value='unlock' class='background_2' id='lock-window-submit-button'>Unlock page</button>";
echo "<div submit-success><template type='amp-mustache'>Success!</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
echo "</form></div>"; ?>
