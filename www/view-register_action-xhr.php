<? if (empty($script_code)): exit; endif;

// You cannot create a user on behalf of another user...
if (!(empty($login_status))): json_output("failure", "Cannot create account. You are already signed in."); endif;

// If the name failed...
if (empty(trim($_POST['name'])) || ): json_output("failure", "Name was empty."); endif;
if (empty(trim($_POST['confirm-name']))): json_output("failure", "Name was not confirmed."); endif;

// If the passcode failed...
if (empty(trim($_POST['passcode']))): json_output("failure", "Passcode was empty."); endif;
if (empty(trim($_POST['confirm-passcode']))): json_output("failure", "Passcode was not confirmed."); endif;

// If the administrator parameter is defined in the URL...
if (!(empty($parameter_request))):
	if ($parameter_request !== "administrator")): json_output("failure", "Invalid parameter."); endif; // If theres'a nother parameter except 'administrator' then reject it...
	if (empty(trim($_POST['security-key']))): json_output("failure", "Security key was empty."); endif; // If no security key was provided...
	if (empty(trim($_POST['confirm-authenticator-code']))): json_output("failure", "Authenticator code was not confirmed."); endif; // If the authenticator code is empty...
	endif;

// Do an explicit check if the user account exists...


// Load all current users...


// If you are creating an administrator and there are already any users ...
// Simple error report with vague information. If you are locked out, contact your webmaster to check if installation is enabled and successful.

// If you are creating an administrator and there are no users but installation is not enabled
// Simple error report with vague information. If you are locked out, contact your webmaster to check if installation is enabled and successful.

// If it's asking to create an administrator then...
// If parameter is "administrator" and there are no users yet AND the install in configuration.php is enabled...

// Check if the username exists...

// Check if the pin code is valid...

// Check if the authenticator code is valid...

// Create account...

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");
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
