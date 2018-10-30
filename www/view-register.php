<? if (empty($script_code)): exit; endif;

// If user is logged in and not an administrator, then tell them they cannot create new users

echo "<h1>Create new account.</h1>";

echo "<p>There are three steps!</p>";
echo "<p style='color: navy; opacity: 0.5;'>1) First, set your pseudonym. This serves as your author name and your sign-in.</p>";
echo "<p style='color: navy; opacity: 0.75;'>2) Next, set your passcode. Save this to sign-in to your account.</p>";
echo "<p style='color: navy; opacity: 1;'>3) Last, enable two-factor authentication.</p>";

echo "<form id='register-window-form' target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' verify-xhr='https://diis.online/?view=install&parameter=verify&action=xhr&language=".$language_request."' method='post' custom-validation-reporting=' as-you-go'>";
	
// Thing to say whether or not it was successful and to go to homepage...

echo "<h2>". $translatable_elements['first-choose-your-pseudonym'][$language_request] ."</h2>";

echo "<span class='register-window-helper'>Your pseudonym is automatically generated for privacy and anonymity.</span>";

echo "<amp-list id='username-option-list' max-items='1' width='auto' height='130' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
// echo "<span id='username-option-placeholder' placeholder>". $translatable_elements['loading'][$language_request] ."</span>";
echo "<span id='username-option-fallback' fallback>". $translatable_elements['failed-to-load-options'][$language_request] ."</span>";
echo "<template type='amp-mustache'>";
	echo "<input class='username-options-list-item-input' name='username' value='{{combined}}' type='hidden'>";
	echo "<span id='username-option-show'>{{username-one}} {{username-two}} {{username-three}}</span>";
echo "</template>";
echo "</amp-list>";

// Or choose to get the newest one
echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='username-option-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['dont-like-it'][$language_request] ."</span>";

// Now let the user go on to the next step

// If the option is selected, then give a six-digit numerical passcode
echo "<h2>". $translatable_elements['next-save-your-passcode'][$language_request] ."</h2>";
echo "<span id='register-window-security-key'>323 239</span>";
echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='username-option-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['not-memorable'][$language_request] ."</span>";


// We will validate in the XHR file
if ($parameter_request == "administrator"):

	// Input type hidden

	echo "<h2>Finally, set up Google Authenticator.</h2>";

	echo "<div id='register-window-two-factor-alignment'>";

	// Download links for Google Authenticator...
	echo "<p>1) Install the Google Authenticator app.</p>";
	echo "<a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'><span id='register-window-download-link'>Android</span></a>";
	echo "<a href='https://itunes.apple.com/us/app/google-authenticator/id388497605'><span id='register-window-download-link'>iOS</span></a>";

	// The user will add a lengthy key to Authenticator...
	echo "<p>2) Sync your Diis account to Google Authenticator.</p>";
	echo "<a href=''><span id='register-window-security-key-link'><i class='material-icons'>launch</i>  Launch in app.</span></a>";
	echo "<span class='register-window-helper'>If you are not on your phone then then you have to add this security key manually,</span>";
	echo "<span id='register-window-security-key'>DSK JLN SDF J32 343</span>";
	
	// And some recovery codes...
	echo "<p>3) Save these recovery codes.</p>";
	echo "<span class='register-window-helper'>These one-time codes can be used if your phone is lost.</span>";
	echo "<span id='register-window-recovery-keys'>231 9R8<br>PND 9X5<br>13K 94L</span>";

	echo "</div>";

	endif;

echo "<br>";

// Confirm your passcode and authenticator code to create your account.

echo "<h2>That's it! Confirm your account details.</h2>";

echo "<span class='register-window-helper'>Confirm your passcode.</span>";
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
