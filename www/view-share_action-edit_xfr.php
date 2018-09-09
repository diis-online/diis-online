<?

	$page_confirmed = nesty_page($_POST['page']);
	if (empty($page_confirmed[$_POST['page']])): notfound(); endif;
	// largely thanks to https://stackoverflow.com/questions/43422257/amp-form-submission-redirect-or-response
	header("Content-type: application/json");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: https://".$domain);
	header("AMP-Access-Control-Allow-Source-Origin: https://".$domain);
	// if failure
	// header("HTTP/1.0 412 Precondition Failed", true, 412);
	// and end headers here
	// if no redirect
	// header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	// and end headers here
	header("AMP-Redirect-To: https://".$domain."/".$_POST['page']);
	header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
	if ($page_temp == "unlock"):
		$_SESSION[$_POST['page']] = $_POST['password'];
		echo json_encode(["result"=>"success", "message"=>"Password did not succeed."]);
		endif;
	if ($page_temp == "relock"):
		unset($_SESSION[$_POST['page']]);
		echo json_encode(["result"=>"success", "message"=>"Password cleared."]);
		endif;
    
?>
