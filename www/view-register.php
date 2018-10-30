<? if (empty($script_code)): exit; endif;

// If user is logged in and not an administrator, then tell them they cannot create new users

echo "<h1>Create new account.</h1>";

echo "<form id='register-window-form' target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' verify-xhr='https://diis.online/?view=install&parameter=verify&action=xhr&language=".$language_request."' method='post' custom-validation-reporting=' as-you-go'>";
	
// Thing to say whether or not it was successful and to go to homepage...

echo "<h2>". $translatable_elements['first-choose-your-pseudonym'][$language_request] ."</h2>";

echo "<p>Your pseudonym is used as your author name and your username for sign-in. It is automatically generated for privacy and anonymity.</p>";

echo "<span class='register-window-helper'>This is your new pseudonym,</span>";

echo "<amp-list id='username-option-list' max-items='1' width='auto' height='130' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
// echo "<span id='username-option-placeholder' placeholder>". $translatable_elements['loading'][$language_request] ."</span>";
echo "<span id='username-option-fallback' fallback>". $translatable_elements['failed-to-load-options'][$language_request] ."</span>";
echo "<template type='amp-mustache'>";
	echo "<input class='username-options-list-item-input' name='username' value='{{combined}}' type='hidden'>";
	echo "<span id='username-option-show'>{{username-one}} {{username-two}} {{username-three}}</span>";
echo "</template>";
echo "</amp-list>";

// Or choose to get the newest one
echo "<span class='register-window-helper'>Don't like it?</span>";
echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='username-option-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['generate-new-name'][$language_request] ."</span>";

// Now let the user go on to the next step

// If the option is selected, then give a six-digit numerical passcode
echo "<h2>". $translatable_elements['next-save-your-passcode'][$language_request] ."</h2>";
echo "<span id='register-window-security-key'>323 239</span>";
echo "<span class='register-window-helper'>". $translatable_elements['not-memorable'][$language_request] ."</span>";
echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='username-option-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['generate-new-passcode'][$language_request] ."</span>";


// We will validate in the XHR file
if ($parameter_request == "administrator"):

	// Input type hidden

	echo "<h2>Finally, set up two-factor authentication.</h2>";

	echo "<div id='register-window-two-factor-alignment'>";

	// Download links for Google Authenticator...
	echo "<p>1) First, you need the Google Authenticator app.</p>";
	echo "<a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'><span id='register-window-download-link'>Android</span></a>";
	echo "<a href='https://itunes.apple.com/us/app/google-authenticator/id388497605'><span id='register-window-download-link'>iOS</span></a>";

	// The user will add a lengthy key to Authenticator...
	echo "<p>2) Then, sync your Diis account with Google Authenticator.</p>";
	echo "<span class='register-window-helper'>Tap this link if you are on your phone,</span>";
	echo "<a href=''><span id='register-window-security-key-link'><i class='material-icons'>launch</i>  Launch in app.</span></a>";
	echo "<span class='register-window-helper'>Or add this security key manually,</span>";
	echo "<span id='register-window-security-key'>DSK JLN SDF J32 343</span>";
	
	// And some recovery codes...
	echo "<p>3) Also, save these recovery codes in case you lose your phone,</p>";
	echo "<span id='register-window-recovery-keys'>231 9R8<br>PND 9X5<br>13K 94L</span>";

	echo "</div>";

	endif;

echo "<br>";

// Confirm your passcode and authenticator code to create your account.

echo "<h2>That's it! Confirm your account details.</h2>";

echo "<span class='register-window-helper'>Confirm your passcode from above.</span>";
echo "<input id='register-window-authenticator-input' type='number' pattern='.{6,6}' max='999999' name='pin-authenticator'>";

echo "<span class='register-window-helper'>Get an authenticator code from Google Authenticator.</span>";
echo "<input id='register-window-authenticator-input' type='number' pattern='.{6,6}' max='999999' name='pin-authenticator'>";

echo "<div submit-success><template type='amp-mustache'>Success! {{{message}}}</template></div>";
echo "<div submit-error><template type='amp-mustache'>Failure! {{{message}}}</template></div>";
echo "<div submitting><template type='amp-mustache'>Submitting...</template></div>";

echo "<br>";

echo "<span id='register-window-create-button' role='button' tabindex='0' on='tap:register-window-form.submit'>Create account.</span>";

echo "</form>";

footer(); ?>
