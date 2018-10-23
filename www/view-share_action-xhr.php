<? if (empty($script_code)): exit; endif;

$share_id = $_POST['share_id'] ?? null;
if (empty($share_id)): json_output("failure", "Share empty."); endif;

$content_draft = $_POST['content_draft'] ?? null;
$content_draft = trim($content_draft);
if (empty($content_draft) && !(in_array($share_id, ["create", "reply", "translate"]))): json_output("failure", "Content empty."); endif;

$content_status_array = [
	"draft",
	"published",
	"pending",
	];
$content_status = $_POST['content_status'] ?? null;
if (empty($content_status)): json_output("failure", "Status empty."); endif;
if (!(in_array($content_status, $content_status_array))): json_output("failure", "Status invalid."); endif;

json_output("failure", "Got this far.");

$share_info = [];

// Prepare statement to look up by share

if ($_POST['share_id'] == "create"):

	$share_info = [
		"share_id" => random_number(9),
		"author_id" => $login_status['user_id'],
		];

	// While the share_id exists in the database then find another one...


	json_output("redirect", "Successfully created share.", "/?view=share&parameter=".$share_info['share_id']."&action=edit");

	exit; endif;

// Look up the share
$share_info = [
	"share_id" => "1111",
	"author_id" => "testing",
	];

if (empty($share_info)): json_output("failure", "Share does not exist.".implode($_POST)); endif;

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
		"content_archive_id" => random_number(9),
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
		"content_archive_id" => random_number(9),
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
			"content_archive_id" => random_number(9),
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
		"content_archive_id" => random_number(9),
		"user_id" => $login_status['user_id'],
		"change_field" => "content_status",
		"change_value" => $content_status,
		"change_time" => time(),
		];
			
	endif;

json_output("success", "Share saved."); ?>
