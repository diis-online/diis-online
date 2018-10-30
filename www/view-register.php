<? if (empty($script_code)): exit; endif;

// If user is logged in and not an administrator, then tell them they cannot create new users

echo "<div id='register-window-header-alignment' amp-fx='parallax' data-parallax-factor='1.3'>";

	echo "<h1>Create new account.</h1>";

	$insert_temp = null;
	if ($parameter_request == "administrator"): $insert_temp = " confirm your details,"; endif;

	echo "<p>Pick your name, save your code,". $insert_temp. " and you are done!</p>";

	echo "</div>";

echo "<form id='register-window-form' target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' verify-xhr='https://diis.online/?view=install&parameter=verify&action=xhr&language=".$language_request."' method='post' custom-validation-reporting=' as-you-go'>";

// First, they have to pick a pseudonym
echo "<div id='register-window-pseudonym-alignment'>";

	echo "<p>First, set your pseudonym. This will be your author name and user name.</p>";

	echo "<amp-list id='username-option-list' max-items='1' width='auto' height='150' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
	echo "<span id='username-option-fallback' fallback>". $translatable_elements['failed-to-load-options'][$language_request] ."</span>";
	echo "<template type='amp-mustache'>";
		echo "<input class='username-options-list-item-input' name='username' value='{{combined}}' type='hidden'>";
		echo "<span id='username-option-show'>{{username-one}} {{username-two}} {{username-three}}</span>";
	echo "</template>";
	echo "</amp-list>";

	// Or choose to get the newest one
	echo "<span class='register-window-helper'>Your pseudonym is automatically generated for privacy and anonymity.</span>";
	echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='register-window-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['generate-pseudonym'][$language_request] ."</span>";

	echo "</div>";


// Next, they have to pick a passcode
echo "<div id='register-window-passcode-alignment'>";
	echo "<p>Next, pick your passcode. You need it to sign in to your account.</p>";
	echo "<span id='register-window-passcode'>323 239</span>";
	echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='register-window-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['generate-passcode'][$language_request] ."</span>";
	echo "</div>";

// We will validate in the XHR file
if ($parameter_request == "administrator"):

	// Input type hidden

	echo "<div id='register-window-two-factor-alignment'>";

	echo "<p>Last, you need to set up two-factor authentication by installing the Google Authenticator app and syncing it to your Diis account.</p>";

	// Download links for Google Authenticator...
	echo "<span class='register-window-helper'>Make sure you have Google Authenticator.</span>";
	echo "<a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'><span id='register-window-download-link'>Android</span></a>";
	echo "<a href='https://itunes.apple.com/us/app/google-authenticator/id388497605'><span id='register-window-download-link'>iOS</span></a>";

	// Sync by a link that opens to the app..
	$authenticator_link = null;
	echo "<br><br><br><span class='register-window-helper'>Add your security key to Google Authenticator.</span>";
	echo "<a href=''><span id='register-window-security-key-link'><i class='material-icons'>launch</i>  Open in app.</span></a>";
	echo "<span id='register-window-security-key'>DSK JLN SDF J32 343</span>";
	
	// And some recovery codes...
	echo "<p>Save these recovery codes. They can each be used once in case your phone is lost, or when you need to set up a sync with a new phone.</p>";
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
