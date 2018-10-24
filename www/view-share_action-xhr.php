<? if (empty($script_code)): exit; endif;

// Check if we are creating something new
if ($_POST['content_status'] == "uncreated"):

	$share_info = [
		"share_id" => null,
		"author_id" => $login_status['user_id'],
		"relationship_type" => $_POST['relationship_type'],
		"relationship_to" => $_POST['relationship_to'],
		"content_status" => "draft",
		];

	// We will check for duplicates to ensure the share is uniquely identified
	$statement_temp = "SELECT share_id FROM `shares_main` WHERE `share_id`=$1";
	$result_temp = pg_prepare($database_connection, "check_share_id_statement", $statement_temp);
	if (database_result($result_temp) !== "success"): json_output("failure", "Database #176."); endif;

	// Initialize null values
	$share_id_temp = $count_temp = null;

	// Perform the check for a unique identifier
	while (empty($share_id_temp)):

		$count_temp++;
		if ($count_temp > 5): json_output("failure", "Share not unique."); endif;

		$share_id_temp = random_number(9);

		$result_temp = pg_execute($database_connection, "check_share_id_statement", ["share_id"=>$share_id_temp]);
		if (database_result($result_temp) !== "success"): json_output("failure", "Database #177."); endif;
		while ($row_temp = pg_fetch_assoc($result_temp)):
			$share_id_temp = null;
			continue 2; endwhile;
		
		endwhile;

	$share_info['share_id'] = $share_id_temp;

	// Prepare share insert statement
	$statement_temp = database_insert_statement("shares_main", $share_info, "share_id");
	$result_temp = pg_prepare($database_connection, "create_share_statement", $statement_temp);
	if (database_result($result_temp) !== "success"): json_output("failure", "Database #178."); endif;

	// Insert into the database
	$result_temp = pg_execute($database_connection, "create_share_statement", $values_temp);
	if (database_result($result_temp) !== "success"): json_output("failure", "Database #179."); endif;

	json_output("failure", "Successfully created share.".$share_info['share_id'], "/?view=share&parameter=".$share_info['share_id']."&action=edit");

	endif;

// Check if share id exists
$share_id = $_POST['share_id'] ?? null;
if (empty($share_id)): json_output("failure", $translatable_elements['not-found'][$language_request]); endif;

// Check content status
$content_status_array = [ "draft", "published", "pending" ];
$content_status = $_POST['content_status'] ?? null;
if (!(empty($content_status)) && !(in_array($content_status, $content_status_array))): json_output("failure", $translatable_elements['invalid-status'][$language_request]); endif;

// We are not creating something new, so make sure it has content
$content_draft = $_POST['content_draft'] ?? null;
$content_draft = trim($content_draft);
if (empty($content_draft)): json_output("failure", $translatable_elements['empty-content'][$language_request]); endif;

json_output("failure", "Got this far.");

// Look up the share
$share_info = [
	"share_id" => "1111",
	"author_id" => "testing",
	];

if (empty($share_info)): json_output("failure", "Cannot reach."); endif;

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
