<? // Check if the username exists...

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

json_output("success", "Helloooo2o".$_POST['pin'].$_POST['username']);

exit; ?>
