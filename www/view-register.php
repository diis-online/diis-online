<? if (empty($script_code)): exit; endif;

// If user is logged in and not an administrator, then tell them they cannot create new users

echo "<div id='register-window-header-alignment' amp-fx='parallax' data-parallax-factor='1.3'>";

	echo "<h1>". $translatable_elements['create-new-account'][$language_request] ."</h1>";

	echo "<p>". $translatable_elements['set-up-your-name-and-passcode'][$language_request] ." ";
	echo $translatable_elements['your-name-and-passcode-are-automatically-generated'][$language_request] ."</p>";

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

echo "<br>";

// Confirm your name and passcode to create your account.

echo "<h2>". $translatable_elements['thats-it-confirm-your-account-details'][$language_request] ."</h2>";

echo "<p>". $translatable_elements['these-are-the-same-details'][$language_request] ."</p>";

$warning_temp = null;
if ($language_request == "ku"): $warning_temp = " " . $translatable_elements['must-use-kurdish-keyboard'][$language_request]; endif;
echo "<span class='register-window-helper'>". $translatable_elements['confirm-your-name'][$language_request] . $warning_temp ."</span>";
echo "<input id='register-window-name-input' type='text' name='confirm_name' required>";

echo "<span class='register-window-helper'>". $translatable_elements['confirm-your-passcode'][$language_request] ."</span>";
echo "<input id='register-window-passcode-input' type='number' pattern='.{6,6}' max='999999' name='confirm_passcode' required>";

echo "<div submit-success><template type='amp-mustache'>". $translatable_elements['success'][$language_request] ." {{{message}}}</template></div>";
echo "<div submit-error><template type='amp-mustache'>". $translatable_elements['problem'][$language_request] ." {{{message}}}</template></div>";
echo "<div submitting><template type='amp-mustache'>$translatable_elements['sending-to-server'][$language_request]</template></div>";

echo "<br>";

echo "<span id='register-window-create-button' role='button' tabindex='0' on='tap:register-window-form.submit'>". $translatable_elements['create-account'][$language_request] ."</span>";

echo "</form>";

footer(); ?>
