<? if (empty($script_code)): exit; endif;
header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");
	// if failure
	// header("HTTP/1.0 412 Precondition Failed", true, 412);
	// and end headers here
	// if no redirect
	// header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	// and end headers here
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");

$passcode_one = random_number(3);
$passcode_two = random_number(3);

$json_result = [ "items" => [] ];		   
	$json_result['items'][] = [
		"passcode" => $passcode_one.$passcode_two,
		"passcode-pretty" => $passcode_one." ".$passcode_two,
		];
echo json_encode($json_result);
exit; ?>
