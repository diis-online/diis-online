<? if (empty($script_code)): exit; endif;

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

// You cannot create a user on behalf of another user...
if (!(empty($login_status))): json_output("failure", "Cannot create account. You are already signed in."); endif;

$_POST['name_one'] = trim($_POST['name_one']) ?? null;
$_POST['name_two'] = trim($_POST['name_two']) ?? null;
$_POST['name_three'] = trim($_POST['name_three']) ?? null;
$_POST['confirm_name'] = strtolower(trim($_POST['confirm_name'])) ?? null;
$_POST['passcode'] = trim($_POST['passcode']) ?? null;
$_POST['confirm_passcode'] = trim($_POST['confirm_passcode']) ?? null;
$_POST['security_key'] = trim($_POST['security_key']) ?? null;
$_POST['confirm_authenticator_code'] = trim($_POST['confirm_authenticator_code']) ?? null;

// If the name failed...
if (empty($_POST['name_one'])): json_output("failure", "Name was empty."); endif;
if (empty($_POST['name_two'])): json_output("failure", "Name was empty."); endif;
if (empty($_POST['name_three'])): json_output("failure", "Name was empty."); endif;
if (empty($_POST['confirm_name'])): json_output("failure", "Name confirmation was empty."); endif;
if (strlen($_POST['confirm_name']) > 40): json_output("failure", "Name too long. Try another."); endif;
if ($_POST['name_one'] == $_POST['name_two']): json_output("failure", "Redundant name."); endif;
if ($_POST['name_one'] == $_POST['name_three']): json_output("failure", "Redundant name."); endif;
if ($_POST['name_two'] == $_POST['name_three']): json_output("failure", "Redundant name."); endif;


// If the passcode failed...
if (empty($_POST['passcode'])): json_output("failure", "Passcode was empty."); endif;
if (empty($_POST['confirm_passcode'])): json_output("failure", "Passcode confirmation was empty."); endif;
if (strlen($_POST['passcode']) !== 6): json_output("failure", "Passcode was invalid length."); endif;
if (!(ctype_digit(strval($_POST['passcode'])))): json_output("failure", "Passcode was invalid type."); endif;
if ($_POST['passcode'] !== $_POST['confirm_passcode']): json_output("failure", "Passcode confirmation failed."); endif;

// If the administrator parameter is defined in the URL...
if (!(empty($parameter_request))):
	if ($parameter_request !== "administrator"): json_output("failure", "Invalid parameter."); endif; // If there's another parameter except 'administrator' then reject it...
	if ($allow_install !== "enabled"): json_output("failure", "Contact your webmaster to enable installation mode in the configuration file."); endif; // If installation mode is disabled, disallow creating administrators here...
	if (empty($_POST['security_key'])): json_output("failure", "Security key was empty."); endif; // If no security key was provided...
	if (empty($_POST['confirm_authenticator_code'])): json_output("failure", "Authenticator code confirmation was empty."); endif; // If the authenticator code is empty...
	endif;

// Load all current users and check if the name exists
$users_temp = 0;
$post_name_array_temp = [$_POST['name_one'], $_POST['name_two'], $_POST['name_three']];
sort($post_name_array_temp);
$statement_temp = "SELECT * FROM users";
$result = pg_query($database_connection, $statement_temp);
while ($row = pg_fetch_assoc($result)):
	$array_temp = [$row['name_one'], $row['name_two'], $row['name_three']];
	sort($array_temp);
	if ($array_temp == $post_name_array_temp): json_output("failure", "Name already exists."); endif;
	$users_temp = 1;
	endwhile;

// If you are creating an administrator and there are already any users ...
if ( ($parameter_temp == "administrator") && ($users_temp == 1) ): json_output("failure", "If you are locked out, contact your webmaster to ensure that installation is enabled and successful."); endif;

// Check if the authenticator code is confirmed...
if (authenticator_code_check($_POST['security_key'], $_POST['confirm_authenticator_code']) !== "success"): json_output("failure", "Please check authenticator code and try again."); endif;

// Check if the user correctly confirmed the name or not...
$name_array = [];
$count_temp = 0;
$statement_temp = "SELECT * FROM username_options";
$result = pg_query($database_connection, $statement_temp);
while ($row = pg_fetch_assoc($result)):

	// If it's not in use...
	if (!(in_array($row['option_id'], [$_POST['name_one'], $_POST['name_two'], $_POST['name_three']]))): continue; endif;

	// Get the word from the row...
	$word_temp = $row[$language_request] ?? $row[$language_request."_fem"] ?? $row[$language_request."_mas"] ?? null;

	// If the word is missing from the table...
	if (empty($word_temp)): continue; endif;

	// If it's not present then block progress..
	if (strpos($_POST['confirm_name'], $word_temp) === FALSE):
		json_output("failure", "Error confirming name.");
		// Add a function here to catch similarly spelled words...
		// http://php.net/manual/en/function.levenshtein.php
		// http://php.net/manual/en/function.similar-text.php
		endif;

	// If the word is present, we want to ensure there are not too many matches...
	$count_temp++; if ($count_temp > 3): json_output("failure", "Name too long."); endif;

	// We will feed this into a function that generates the full name...
	$name_array[$row['option_id']] = ["part"=>$row['part'], "word"=>$word_temp];

	endwhile;

// Let's not bother trying to check the order of the words in the name.
// We just want to be sure that the three right words were there and no extra words were there.
// 'ONE TWO THREE' or 'TWO ONE THREE' or 'TWOONETHREE' are all valid.

// We have checked that if the parameter is "administrator" then
// there are no users yet AND installation in configuration.php is enabled,
// meaning it is safe to proceed...

// Create account...
// Add to Postgres database


json_output("failure", "TestingaaaaaName too long.");

header("AMP-Redirect-To: https://diis.online/?view=login&parameter=success");
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
json_output("success", "<a href='https://diis.online/?view=login&parameter=success'>Click here</a> if you are not redirected."]);

exit; ?>
