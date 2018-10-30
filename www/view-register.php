<? if (empty($script_code)): exit; endif;

echo "<span id='register-window-home-button'>Home</span></a>";

// If user is logged in and not an administrator, then tell them they cannot create new users

// Add bad button

	echo "<h1>Create new account.</h1>";

	echo "<form target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' method='post'>";
	
	// Thing to say whether or not it was successful and to go to homepage...

	echo "<h2>". $translatable_elements['first-choose-your-pseudonym'][$language_request] ."</h2>";

	echo "<p>Your pseudonym is used as your author name and your username for sign-in. It is automatically generated for privacy and anonymity.</p>";

	echo "<span class='username-option-helper'>This is your new pseudonym,</span>";

	echo "<amp-list id='username-option-list' max-items='1' width='auto' height='130' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
//	echo "<span id='username-option-placeholder' placeholder>". $translatable_elements['loading'][$language_request] ."</span>";
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

	echo "<p>Your passcode must be six to eight digits long. Please: be smart. Do not use your telephone or birthday. If someone suspects you, they could use this to hack you.</p>";

	echo "<input id='pincode-input' type='number' name='pin' pattern='.{6,8}'>";

	echo "<p>Set a color password for added security against bots.</p>";

	// Color interface

	

	// We will validate in the XHR file
	if ($parameter_request == "administrator"):

		// Input type hidden

		echo "<h2>Finally, set up your authenticator.</h2>";

		echo "<input type='number' name='pin-authenticator'>";

		endif;

	echo "<div submit-success><template type='amp-mustache'>Success! {{{message}}}</template></div>";
	echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
	echo "<div submitting><template type='amp-mustache'>Submitting...</template></div>";

	echo "<br><br>";

	echo "<h2>That's it! Ready?</h2>";

	echo "<input type='submit' name='submit' value='Create administrator'>";

	echo "</form>";

	// If success then echo the Complete and a little pararaph...


// view register

	// show various words

	// choose words

	// choose a six-digit code as your password

	// get the authenticator app

	// test you can enter the code correctly

  footer(); ?>
