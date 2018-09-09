<? $login_status = [
	"user_id" => "testing",
	"level" => "testing",
	];

if (empty($login_status)): echo json_encode(["result"=>"success", "message"=>"Login failed."]); exit; endif;

if (empty($_POST['share_id'])): echo json_encode(["result"=>"success", "message"=>"Share not specified."]); exit; endif;

$share_info = [];
if ($_POST['share_id'] == "create"):
	$share_info = [
		"share_id" => random_number(10);
		"author_id => $login_status['user_id'],
		];
else:		
	// Look up the share
	$share_info = [
		"share_id" => "1111",
		"author_id" => "testing",
		];
	endif;

if (empty($share_info)): echo json_encode(["result"=>"success", "message"=>"Content does not exist."]); exit; endif;

$permission_temp = 0;
if ($login_status['user_id'] == $share_info['author_id']): $permission_temp = 1; endif;
if (in_array($login_status['level'], ["administrator", "editor"])): $permission_temp = 1; endif;

if ($permission_temp == 0): echo json_encode(["result"=>"success", "message"=>"Login failed."]); exit; endif;

// If there is an administrator

// Is it being saved as a draft or as approved content

// Check posted data and do SQL work

// largely thanks to https://stackoverflow.com/questions/43422257/amp-form-submission-redirect-or-response
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
header("AMP-Redirect-To: https://diis.online/?view=share&action=edit&share=".$share_info['share_id']);
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin"); ?>
