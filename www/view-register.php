<? if (empty($script_code)): exit; endif;

// If user is logged in and not an administrator, then tell them they cannot create new users

echo "<div id='register-window-header-alignment' amp-fx='parallax' data-parallax-factor='1.3'>";

	echo "<h1>". $translatable_elements['create-new-account'][$language_request] ."</h1>";

	echo "<p>";
	if ($parameter_request !== "administrator"): echo $translatable_elements['set-up-your-name-and-passcode'][$language_request];
	else: echo $translatable_elements['set-up-your-name-and-passcode-and-two-factor-authentication'][$language_request]; endif;
	echo " ".$translatable_elements['your-name-and-passcode-are-automatically-generated'][$language_request] ."</p>";

	echo "</div>";

echo "<form id='register-window-form' target='_top' action-xhr='https://diis.online/?view=register&parameter=". $parameter_request ."&action=xhr&language=".$language_request."' verify-xhr='https://diis.online/?view=install&parameter=verify&action=xhr&language=".$language_request."' method='post' custom-validation-reporting='as-you-go'>";

// First, they have to pick a pseudonym
echo "<div id='register-window-name-alignment'>";

	echo "<p>". $translatable_elements['first-settle-on-your-name'][$language_request] ."</p>";

	echo "<amp-list id='register-window-name-list' max-items='1' height='170' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
	echo "<span id='register-window-name-fallback' fallback>". $translatable_elements['failed-to-load'][$language_request] ."</span>";
	echo "<template type='amp-mustache'><div id='register-window-name'>";
		echo "<input type='hidden' name='name_one' value='{{name-one}}'>";
		echo "<input type='hidden' name='name_two' value='{{name-two}}'>";
		echo "<input type='hidden' name='name_three' value='{{name-three}}'>";
		echo "{{combined}}";
	echo "</div></template></amp-list>";

	echo "<span role='button' tabindex='0' on='tap:register-window-name-list.refresh' class='register-window-new-button'><i class='material-icons'>autorenew</i> ". $translatable_elements['new-name'][$language_request] ."</span>";

	echo "</div>";


// Next, they have to pick a passcode
echo "<div id='register-window-passcode-alignment'>";
	echo "<p>". $translatable_elements['next-settle-on-your-passcode'][$language_request] ."</p>";

	echo "<amp-list id='register-window-passcode-list' max-items='1' height='160' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=passcode&language=". $language_request ."'>";
	echo "<span id='register-window-passcode-fallback' fallback>". $translatable_elements['failed-to-load'][$language_request] ."</span>";
	echo "<template type='amp-mustache'><div id='register-window-passcode'>";
		echo "<input type='hidden' name='passcode' value='{{passcode}}'>";
		echo "{{passcode-pretty}}";
	echo "</div></template></amp-list>";

	echo "<span role='button' tabindex='0' on='tap:register-window-passcode-list.refresh' class='register-window-new-button'><i class='material-icons'>autorenew</i> ". $translatable_elements['new-passcode'][$language_request] ."</span>";
	echo "</div>";

// We will validate in the XHR file
if ($parameter_request == "administrator"):

	echo "<div id='register-window-two-factor-alignment'>";

	echo "<p>". $translatable_elements['last-you-need-to-set-up-two-factor-authentication'][$language_request] ."</p>";

	// Download links for Google Authenticator...
	echo "<a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2' target='_blank'><span class='register-window-install-link'>". $translatable_elements['install-on-android'][$language_request] ."</span></a>";
	echo "<a href='https://itunes.apple.com/us/app/google-authenticator/id388497605' target='_blank'><span class='register-window-install-link'>". $translatable_elements['install-on-ios'][$language_request] ."</span></a>";

	// Sync by a link that opens to the app...
	$security_key = random_thirtytwo(16);
//	$security_key = "QLTVZG73VDVF3ZHR";
	echo "<br><br><span class='register-window-helper'>". $translatable_elements['add-your-security-key'][$language_request] ."</span>";
	echo "<span id='register-window-security-key'>". chunk_split($security_key, 4, ' ') ."</span>";
	echo "<a href='otpauth://totp/My account?secret=". encode_thirtytwo($security_key) ."&issuer=Diis'><span class='register-window-security-key-link'>". $translatable_elements['reading-this-on-your-phone'][$language_request] ."</span>"; // This link can also be sent to a QR code
	echo "<span class='register-window-security-key-link'><i class='material-icons'>launch</i> ". $translatable_elements['tap-here-to-add-your-security-key-automatically'][$language_request] ."</span></a>";
	echo "<input type='hidden' name='security_key' value='". $security_key ."'>";

	echo "</div>";

	// And some recovery codes...
	echo "<div id='register-window-recovery-alignment'>";
	echo "<p>". $translatable_elements['save-these-recovery-codes'][$language_request] ."</p>";
	$recovery_codes = [
		random_number(6),
		random_number(6),
		random_number(6),
		];
	echo "<span id='register-window-recovery-codes'>". chunk_split($recovery_codes[0], 3, ' ') ."<br>". chunk_split($recovery_codes[1], 3, ' ') ."<br>". chunk_split($recovery_codes[2], 3, ' ') ."</span>";
	echo "<input type='hidden' name='recovery_code_one' value='". $recovery_codes[0] ."'>";
	echo "<input type='hidden' name='recovery_code_two' value='". $recovery_codes[1] ."'>";
	echo "<input type='hidden' name='recovery_code_three' value='". $recovery_codes[2] ."'>";

	echo "</div>";

	endif;

echo "<br>";

// Confirm your passcode and authenticator code to create your account.

echo "<h2>". $translatable_elements['thats-it-confirm-your-account-details'][$language_request] ."</h2>";

echo "<p>". $translatable_elements['these-are-the-same-details'][$language_request] ."</p>";

$warning_temp = null;
if ($language_request == "ku"): $warning_temp = " " . $translatable_elements['must-use-kurdish-keyboard'][$language_request]; endif;
echo "<span class='register-window-helper'>". $translatable_elements['confirm-your-name'][$language_request] . $warning_temp ."</span>";
echo "<input id='register-window-authenticator-input' type='text' name='confirm_name' required>";

echo "<span class='register-window-helper'>". $translatable_elements['confirm-your-passcode'][$language_request] ."</span>";
echo "<input id='register-window-authenticator-input' type='number' pattern='.{6,6}' max='999999' name='confirm_passcode' required>";

if ($parameter_request == "administrator"):
	echo "<span class='register-window-helper'>". $translatable_elements['enter-your-google-authenticator-code'][$language_request] ."</span>";
	echo "<input id='register-window-authenticator-input' type='number' pattern='.{6,6}' max='999999' name='confirm_authenticator_code' required>";
	endif;

echo "<div submit-success><template type='amp-mustache'>Success! {{{message}}}</template></div>";
echo "<div submit-error><template type='amp-mustache'>Failure! {{{message}}}</template></div>";
echo "<div submitting><template type='amp-mustache'>Submitting...</template></div>";

echo "<br>";

echo "<span id='register-window-create-button' role='button' tabindex='0' on='tap:register-window-form.submit'>". $translatable_elements['create-account'][$language_request] ."</span>";

echo "</form>";

footer(); ?>
