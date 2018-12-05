<? if (empty($script_code)): exit; endif;

// Sign in with just a name and passcode
// However, after signing in then 2FA is required to unlock additional functionalities

echo "<h1>". $translatable_elements['sign-in'][$language_request] .".</h1>";

echo "<p>Not a publisher yet? <a href='https://diis.online/?view=register'>". $translatable_elements['create-your-account'][$language_request] ."</a></p><br>";

echo "<form id='signin-window-form' method='post' action-xhr='https://diis.online?view=signin&action=xhr&language=". $language_request ."'>";

echo '<amp-state id="input_name"><script type="application/json">{"input_name_value": ""}</script></amp-state>';
echo "<span class='signin-window-helper'>Enter your name.</span>";
echo "<input id='signin-window-name-input' type='text' name='name' value='' [value]=\"input_name.input_name_value\" required>";

// Later on, allow the form to receive a recommended spelling
// And display a 'Did you mean...' button which updates the name input when you press it

echo "<span class='signin-window-helper'>Enter your passcode.</span>";
echo "<input id='signin-window-passcode-input' type='password' name='passcode' required>";

echo "<br><span id='signin-window-signin-button' role='button' tabindex='0' on='tap:signin-window-form.submit'>". $translatable_elements['sign-in'][$language_request] .".</span>";

echo "<div submitting><span class='signin-window-submitting'>". $translatable_elements['sending-to-server'][$language_request] ."</span></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
echo "<div submit-success>hbhjbj<span class='signin-window-submit-success'><template type='amp-mustache'>". $translatable_elements['success'][$language_request] ." {{{message}}}</template></span></div>";

echo "</form>"; ?>
