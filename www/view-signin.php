<? if (empty($script_code)): exit; endif;

// Sign in with just a name and passcode
// However, after signing in then 2FA is required to unlock additional functionalities

echo "<h1>". $translatable_elements['sign-in'][$language_request] .".</h1>";

echo "<p>". $translatable_elements['not-a-publisher-yet'][$language_request] ." <a href='https://diis.online/?view=register'>". $translatable_elements['create-your-account'][$language_request] ."</a></p><br>";

echo "<form id='signin-window-form' method='post' action-xhr='https://diis.online?view=signin&action=xhr&language=". $language_request ."'>";

echo "<div id='signin-window-inputs-alignment'>";

// echo '<amp-state id="input_name"><script type="application/json">{"input_name_value": ""}</script></amp-state>';
echo "<span class='signin-window-helper'>". $translatable_elements['enter-your-name'][$language_request] ."</span>";
echo "<input id='signin-window-name-input' type='text' name='name' value= '' [value]=\"input_name.input_name_value\" required>";

echo "<span class='signin-window-helper'>". $translatable_elements['passcode'][$language_request] ."</span>";
echo "<input id='signin-window-passcode-input' type='password' name='passcode' required>";

echo "</div>";

echo "<div id='signin-window-button-alignment'>";
echo "<span id='signin-window-signin-button' role='button' tabindex='0' on='tap:signin-window-form.submit'><i class='material-icons'>label_important</i> ". $translatable_elements['sign-in'][$language_request] ." <i class='material-icons'>label_important</i></span>";
echo "</div>";

echo "<div class='signin-window-submit-success' submit-success><template type='amp-mustache'>". $translatable_elements['success'][$language_request] ." {{{message}}}</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
echo "<div class='signin-window-submitting' submitting>". $translatable_elements['sending-to-server'][$language_request] ."</div>";

echo "</form>"; ?>
