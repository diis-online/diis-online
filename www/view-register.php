<? if (empty($script_code)): exit; endif;

	echo "<h2>Create new account.</h2>";

	echo "<form target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' method='post'>";
	
	// Thing to say whether or not it was successful and to go to homepage...

	echo "<h3>". $translatable_elements['first-choose-your-pseudonym'][$language_request] ."</h3>";

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
	echo "<h3>". $translatable_elements['second-choose-a-six-digit-numerical-passcode'][$language_request] ."</h3>";

	echo "<input id='pincode-input' type='number' name='pin'>";

	echo "<h3>Third, set up your authenticator.</h3>";

	echo "<input type='number' name='pin-authenticator'>";

	echo "<div submit-success><template type='amp-mustache'>Success! {{{message}}}</template></div>";
	echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
	echo "<div submitting><template type='amp-mustache'>Submitting...</template></div>";

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
