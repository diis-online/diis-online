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
if (authenticator_code_check($_POST['security_key'], $_POST['confirm_authenticator_code']) !== "success"): json_output("failure", $_POST['security_key']."_____".$_POST['confirm_authenticator_code']."____Please check authenticator code and try again."); endif;

json_output("failure", "Teseestisngdgdgfg");

// Check if the name is confirmed or not...
$statement_temp = "SELECT * FROM username_options";
$result = pg_query($database_connection, $statement_temp);
while ($row = pg_fetch_assoc($result)):

	$word_temp = $row[$language_request] ?? $row[$language_request."_fem"] ?? $row[$language_request."_mas"] ?? null;
	if (empty($word_temp)): continue; endif;

	// Arrange what the name should be based on name_one, name_two, name_three

	endwhile;

// If it cannot find name_one, name_two, and name_three
if (count($correct_array) !== 3): json_output("failure", "Error confirming name."); endif;

// Compare the name ... if it is not a perfect match then return an error

// Consider allowing 'Did you mean...' options based on Levenshtein or general similarity calculations
// http://php.net/manual/en/function.levenshtein.php
// http://php.net/manual/en/function.similar-text.php


// We have checked that if the parameter is "administrator" then
// there are no users yet AND installation in configuration.php is enabled,
// meaning it is safe to proceed...


// Create account...

// if failure
	header("HTTP/1.0 412 Precondition Failed", true, 412);
	// and end headers here
// if no redirect
//	header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	// and end headers here
// header("AMP-Redirect-To: https://".$domain."/".$_POST['page']);
// header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");

json_output("failure", "Pin: ".$_POST['pin-authenticator']);

exit; ?>
