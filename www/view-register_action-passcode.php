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

$json_result = [ "items" => [] ];		   
	$json_result['items'][] = [
		"passcode" => random_number(6),
		];
	$count_temp++; endwhile;
echo json_encode($json_result);
exit; ?>
