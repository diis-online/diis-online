<? if (empty($script_code)): exit; endif;

// If user is logged in and not an administrator, then tell them they cannot create new users

echo "<h1>Create new account.</h1>";

echo "<form target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' method='post'>";
	
// Thing to say whether or not it was successful and to go to homepage...

echo "<h2>". $translatable_elements['first-choose-your-pseudonym'][$language_request] ."</h2>";

echo "<p>Your pseudonym is used as your author name and your username for sign-in. It is automatically generated for privacy and anonymity.</p>";

echo "<span class='username-option-helper'>This is your new pseudonym,</span>";

echo "<amp-list id='username-option-list' max-items='1' width='auto' height='130' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
// echo "<span id='username-option-placeholder' placeholder>". $translatable_elements['loading'][$language_request] ."</span>";
echo "<span id='username-option-fallback' fallback>". $translatable_elements['failed-to-load-options'][$language_request] ."</span>";
echo "<template type='amp-mustache'>";
	echo "<input class='username-options-list-item-input' name='username' value='{{combined}}' type='hidden'>";
	echo "<span id='username-option-show'>{{username-one}} {{username-two}} {{username-three}}</span>";
echo "</template>";
echo "</amp-list>";

// Or choose to get the newest one
echo "<span class='username-option-helper'>Don't like it?</span>";
echo "<span role='button' tabindex='0' on='tap:username-option-list.refresh' id='username-option-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['generate-new-name'][$language_request] ."</span>";

// Now let the user go on to the next step

// If the option is selected, then give a six-digit numerical passcode
echo "<h2>". $translatable_elements['next-set-a-passcode'][$language_request] ."</h2>";

echo "<p>Your passcode must be six to eight digits long. Please: be smart. Do not use your telephone or birthday. If someone suspects you, they can easily hack you and confirm your identity.</p>";

echo "<input id='pincode-input' type='number' name='pin' pattern='.{6,8}' max='999999'>";

// We will validate in the XHR file
if ($parameter_request == "administrator"):

	// Input type hidden

	echo "<h2>Finally, set up two-factor authentication.</h2>";

	echo "<p>This is required for administrators. Make sure you follow these steps. It is easy.</p>";

	// Install the app onto your phone...
	echo "<p>1) Install Google Authenticator on your phone,</p>";
	echo "<a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'><span id='register-window-download-link'>Android</span></a>";
	echo "<a href='https://itunes.apple.com/us/app/google-authenticator/id388497605'><span id='register-window-download-link'>iOS</span></a>";

	// The user will add a lengthy key to Authenticator...
	echo "<p>2) Add this key to the app,</p>";
	// Show security code or give link

	// The user will input their authentication code to check it...
	echo "<p>3) Check the six-digit code below,</p>"; 
	echo "<input id='authenticator-input' type='number' pattern='.{6,6}' max='999999' name='pin-authenticator'>";

	// And some recovery codes...
	echo "<p>4) Save these recovery codes in case you lose your phone,</p>";
	// Some recovery codes...

	endif;

echo "<br>";

echo "<span id='register-window-create-button' role='button' tabindex='0'>Create account</span>";

echo "<div submit-success><template type='amp-mustache'>Success! {{{message}}}</template></div>";
echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
echo "<div submitting><template type='amp-mustache'>Submitting...</template></div>";

echo "</form>";

footer(); ?>
