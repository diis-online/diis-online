<? if (empty($script_code)): exit; endif;

if (empty($_POST['share_id'])): echo json_encode(["result"=>"success", "message"=>"Share not specified."]); exit; endif;

$content_draft = $_POST['content_draft'] ?? null;
$content_draft = trim($content_draft);

if (empty($content_draft)): echo json_encode(["result"=>"success", "message"=>"Content empty."]); exit; endif;

$content_status = $_POST['content_status'] ?? null;

if (empty($content_status)): echo json_encode(["result"=>"success", "message"=>"Status empty."]); exit; endif;


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

$change_temp = 0;

// If a change has happened to the draft...
if ($content_draft !== $share_info['content_draft']):

	// Is it being saved as a draft or as approved content
	$values_temp = [
		"share_id" => $share_info['share_id'],
		"author_id" => $share_info['author_id'],
		"content_draft" => $content_draft,
		];
		
	// also, add to archive
	$values_temp = [
		"content_archive_id" => random_number(10),
		"user_id" => $login_status['user_id'],
		"change_field" => "content_draft",
		"change_value" => $content_draft,
		"change_time" => time(),
		];
	
	$change_temp = 1;
	
	endif;

// If there is an administrator AND not the author AND we publish...
if ( ($login_status['user_id'] !== $share_info['author_id']) && ($content_status == "published") ):

	$values_temp = [
		"share_id" => $share_info['share_id'],
		"content_published" => $content_draft,
		];

	// also, add to archive
	$values_temp = [
		"content_archive_id" => random_number(10),
		"user_id" => $login_status['user_id'],
		"change_field" => "content_published",
		"change_value" => $content_draft,
		"change_time" => time(),
		];
		
	if (empty($shared_info['published_time'])):

		$published_time = $_POST['published_time'] ?? time();

		$values_temp = [
			"share_id" => $share_info['share_id'],
			"published_time" => $published_time,
			];
		
		// also, add to archive
		$values_temp = [
			"content_archive_id" => random_number(10),
			"user_id" => $login_status['user_id'],
			"change_field" => "published_time",
			"change_value" => $published_time,
			"change_time" => time(),
			];
			
		endif;
		
	$change_temp = 1;

	endif;

// If a change occurred to the content, then also update the status...
if ( ($change_temp == 1) && ($share_info['content_status'] !== $content_status) ):	
	
	$values_temp = [
		"share_id" => $share_info['share_id'],
		"content_status" => $content_status,
		];
			
	// also, add to archive
	$values_temp = [
		"content_archive_id" => random_number(10),
		"user_id" => $login_status['user_id'],
		"change_field" => "content_status",
		"change_value" => $content_status,
		"change_time" => time(),
		];
			
	endif;

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
header("AMP-Redirect-To: https://diis.online/?view=share&action=edit&share=".$share_info['share_id']."&action=edit");
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin"); ?>
