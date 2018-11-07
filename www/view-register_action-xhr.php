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
$_POST['recovery_code_one'] = trim($_POST['recovery_code_one']) ?? null;
$_POST['recovery_code_two'] = trim($_POST['recovery_code_two']) ?? null;
$_POST['recovery_code_three'] = trim($_POST['recovery_code_three']) ?? null;
$_POST['confirm_authenticator_code'] = trim($_POST['confirm_authenticator_code']) ?? null;

// If the name failed...
if (empty($_POST['name_one'])): json_output("failure", "Name was incomplete."); endif;
if (empty($_POST['name_two'])): json_output("failure", "Name was incomplete."); endif;
if (empty($_POST['name_three'])): json_output("failure", "Name was incomplete."); endif;
if (empty($_POST['confirm_name'])): json_output("failure", "Name confirmation was empty."); endif;
if (strlen($_POST['confirm_name']) > 40): json_output("failure", "Name too long."); endif;
if (strlen($_POST['confirm_name']) < 9): json_output("failure", "Name too short."); endif;
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
	if (empty($_POST['recovery_code_one']) || empty($_POST['recovery_code_two']) || empty($_POST['recovery_code_three'])): json_output("failure", "Recovery code was empty."); endif; // If any recovery code is missing...
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
if ( ($parameter_request == "administrator") && ($users_temp == 1) ): json_output("failure", "If you are locked out, contact your webmaster to ensure that installation is enabled and successful."); endif;

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
	if (strpos(" ".$_POST['confirm_name']." ", " ".$word_temp." ") === FALSE):
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
// 'ONE TWO THREE' or 'TWO ONE THREE' or 'TWO ONE THREE' are all valid.

// Generate name_one, name_two, name_three...
ksort($name_array);
$name_one_temp = $name_two_temp = $name_three_temp = null;
foreach($name_array as $option_id => $name_info):
	if (in_array($name_info['part'], ["adjective quality"]) && empty($name_one_temp)): $name_one_temp = $option_id; 
	elseif (in_array($name_info['part'], ["adjective quality", "adjective color"])): $name_two_temp = $option_id;
	elseif (in_array($name_info['part'], ["noun"])): $name_three_temp = $option_id; endif;
	endforeach;

// Check if any part of the name is missing...
if (empty($name_three_temp) || empty($name_three_temp) || empty($name_three_temp)):
	json_output("failure", "Missing name component.");
	endif;

// Create account...
$user_temp = [
	"user_id" => random_number(9),
	"name_one" => $name_one_temp,
	"name_two" => $name_two_temp,
	"name_three" => $name_three_temp,
	"level" => "publisher",
	"passcode_hash" => sha1($name_one_temp.$name_two_temp.$name_three_temp.$_POST['passcode']),
	"created_time" => time(),
	];

// We have checked that if the parameter is "administrator" then
// there are no users yet AND installation in configuration.php is enabled,
// meaning it is safe to proceed...
if ($parameter_request == "administrator"):
	$user_temp['level'] = "administrator";
	$user_temp['security_key'] = $_POST['security_key'];
	$user_temp['recovery_codes'] = json_encode([$_POST['recovery_code_one'], $_POST['recovery_code_two'], $_POST['recovery_code_three']]);
	endif;

// Prepare user registration statement...
$statement_temp = database_insert_statement("users", $user_temp, "user_id");
$result_temp = pg_prepare($database_connection, "register_user_statement", $statement_temp);
if (database_result($result_temp) !== "success"): json_output("failure", "Database #101."); endif;

// Execute user registration statement...
$result_temp = pg_execute($database_connection, "register_user_statement", $user_temp);
if (database_result($result_temp) !== "success"): json_output("failure", "Database #102."); endif;

// This means it worked! The user should be able to log in...
header("AMP-Redirect-To: https://diis.online/?view=signin&parameter=success");
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
json_output("success", "<a href='https://diis.online/?view=signin&parameter=success'>Click here</a> if you are not redirected.");

exit; ?>
