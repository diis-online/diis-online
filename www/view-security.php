<? if (empty($script_code)): exit; endif;

// This script is for setting up two-factor and for resetting passcodes

echo '<amp-iframe width="200" height="100"
    sandbox="allow-scripts allow-same-origin"
    layout="responsive"
    frameborder="0"
    src="https://diis.online?view=qrcode&parameter=sdfs&language=en">
</amp-iframe>';

// We will validate in the XHR file

echo "<div id='twofactor-window-setup-alignment'>";

echo "<p>". $translatable_elements['last-you-need-to-set-up-two-factor-authentication'][$language_request] ."</p>";

// Download links for Google Authenticator...
echo "<a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2' target='_blank'><span class='twofactor-window-install-link'>". $translatable_elements['install-on-android'][$language_request] ."</span></a>";
echo "<a href='https://itunes.apple.com/us/app/google-authenticator/id388497605' target='_blank'><span class='twofactor-window-install-link'>". $translatable_elements['install-on-ios'][$language_request] ."</span></a>";

// Sync by a link that opens to the app...
$authenticator_key = random_thirtytwo(16);
//	$authenticator_key = "QLTVZG73VDVF3ZHR";
echo "<br><br><span class='twofactor-window-helper'>". $translatable_elements['add-your-authenticator-key'][$language_request] ."</span>";
echo "<span id='twofactor-window-security-key'>". chunk_split(encode_thirtytwo($authenticator_key), 4, ' ') ."</span>";
echo "<a href='otpauth://totp/My account?secret=". encode_thirtytwo($authenticator_key) ."&issuer=Diis'><span class='twofactor-window-security-key-link'>". $translatable_elements['reading-this-on-your-phone'][$language_request] ."</span>"; // This link can also be sent to a QR code
echo "<span class='twofactor-window-security-key-link'><i class='material-icons'>launch</i> ". $translatable_elements['tap-here-to-add-your-security-key-automatically'][$language_request] ."</span></a>";
echo "<input type='hidden' name='authenticator_key' value='". $authenticator_key ."'>";

echo "</div>";

// And some recovery codes...
echo "<div id='twofactor-window-recovery-alignment'>";
echo "<p>". $translatable_elements['save-these-recovery-codes'][$language_request] ."</p>";
$recovery_codes = [
	random_number(6),
	random_number(6),
	random_number(6),
	];
echo "<span id='twofactor-window-recovery-codes'>". chunk_split($recovery_codes[0], 3, ' ') ."<br>". chunk_split($recovery_codes[1], 3, ' ') ."<br>". chunk_split($recovery_codes[2], 3, ' ') ."</span>";
echo "<input type='hidden' name='recovery_code_one' value='". $recovery_codes[0] ."'>";
echo "<input type='hidden' name='recovery_code_two' value='". $recovery_codes[1] ."'>";
echo "<input type='hidden' name='recovery_code_three' value='". $recovery_codes[2] ."'>";

echo "</div>";

echo "<span class='twofactor-window-helper'>". $translatable_elements['enter-your-google-authenticator-code'][$language_request] ."</span>";
echo "<input id='twofactor-window-authenticator-input' type='number' pattern='.{6,6}' max='999999' name='confirm_authenticator_code' required>"; ?>
