<? if (empty($script_code)): exit; endif;

// Load all current users.

// If you are creating an administrator and there are already any users ...
// Simple error report with vague information. If you are locked out, contact your webmaster to check if installation is enabled and successful.

// If you are creating an administrator and there are no users but installation is not enabled
// Simple error report with vague information. If you are locked out, contact your webmaster to check if installation is enabled and successful.

// If you are creating an account and already logged in


// If it's asking to create an administrator then...
// If parameter is "administrator" and the login_status IS administator, or if it is empty and there are no users yet AND the install in configuration.php is enabled...
	
// Check if the username exists...

// Check if the pin code is valid...

// Check if the authenticator code is valid...

// Create account...

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");
// if failure
	// header("HTTP/1.0 412 Precondition Failed", true, 412);
	// and end headers here
// if no redirect
	header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	// and end headers here
// header("AMP-Redirect-To: https://".$domain."/".$_POST['page']);
// header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");

json_output("success", $_POST['username']);

exit; ?>
