<? if (empty($script_code)): exit; endif;

echo "<h1>Beginning installation.</h1>";

echo "<p>Diis will attempt to generate and correct its table structure, and check whether an administrator account has been generated.</p>";

$tables_array = [];

// Table schema for system configuration, e.g. for reCAPTCHA...
$tables_array['system_configuration'] = [
	"configuration_id" => "INTEGER",
	"configuration_category" => "VARCHAR(50)",
	"configuration_frontend" => "VARCHAR(100)",
	];

// Get the current username options
$username_options = file_get_contents("../username-options.txt", FILE_USE_INCLUDE_PATH);
$username_options = json_decode($username_options, TRUE);

// Table schema for username options...
$tables_array['username_options'] = [
	"option_id" => "INTEGER",
	"part" => "VARCHAR(100)",
	];
foreach ($username_options as $option_name => $option_info):
	foreach($option_info as $key_temp => $value_temp):
		$tables_array['username_options'][$key_temp] = "VARCHAR(100)";
		endforeach;
	break;
	endforeach;

// Table schema for users, including status...
$tables_array['users'] = [
	
	// Account info...
	"user_id" => "INTEGER", // The unique user id
	"username_one" => "INTEGER", // The first adjective of their author name
	"username_two" => "INTEGER", // The second adjective of their author name
	"username_three" => "INTEGER", // The noun of their author name
	"user_status" => "VARCHAR(20)", // Can be: administrator, editor, unconfirmed, pending, approved, frozen, removed

	// For the magic links to reset an account...
	"magic_code" => "VARCHAR(400)", // This is the URL for the magic link
	"magic_time" => "INTEGER", // This is the time when the magic link will expire
	
	// For the cookies to keep so the user stays logged in...
	"cookies_info" => "TEXT", // This can store a JSON of multiple cookies for multiple sessions
	
	// For logging in...
	"authenticator_hash" => "VARCHAR(400)", // Hash to use for checking the authenticator code
	"passcode_hash" => "VARCHAR(400)", // Hash to use for checking the user-entered login pin
	
	// General account timestamps...
	"user_created_time" => "INTEGER", // UNIX timestamp of when the user was created
	"user_hold_time" => "INTEGER", // UNIX timestamp of how long until the user can log in again, or indefinite

	];

// Table schema for shares...
$tables_array['shares_main'] = [
	"share_id" => "INTEGER",
	"author_id" => "INTEGER",
	"content_language" => "VARCHAR(20)",
	"relationship_type" => "VARCHAR(20)", // Can be: standalone, translation, reply (later add continuation)
	"relationship_to" => "INTEGER", // The share_id of what it has this relationship to
	"content_published" => "TEXT", // The published content body
	"content_draft" => "TEXT", // The draft content body
	"content_status" => "VARCHAR(20)", // Can be: published, saved, pending, frozen, removed
	"published_time" => "INTEGER", // UNIX timestamp of when the content is published
	];

// Table schema for shares access — all these users will have access, in addition to the author...
$tables_array['shares_access'] = [
	"access_id" => "INTEGER", // Unique ID of their access ... Should be a random digit
	"user_id" => "INTEGER", // The user that has the access
	"share_id" => "INTEGER", // The share that the user has access to
	"access_status" => "VARCHAR(20)", // Can be: active, deprecated
	"access_time" => "INTEGER", // UNIX timestamp of when the change was made
	];

// Table schema for archiving any work on shares...
$tables_array['shares_archive'] = [
	"content_archive_id" => "INTEGER", // Unique ID of the archived work
	"user_id" => "INTEGER", // The user making the change
	"change_field" => "VARCHAR(20)", // The field where the change is being made.
	"change_value" => "TEXT", // The value that is being placed
	"change_time" => "INTEGER", // UNIX timestamp of when the change was made
	];

// Table schema for internal shares annotations...
$tables_array['shares_annotations'] = [
	"annotation_id" => "INTEGER", // Unique ID of the annotation
	"share_id" => "INTEGER", // ID of the share being annotated
	"user_id" => "INTEGER", // ID of the user making the annotation
	"annotation_text" => "TEXT", // Content of the annotation
	"annotation_timestamp" => "VARCHAR(20)", // UNIX timestamp of when the annotation was made
	]; 

echo "<h2>Generating tables.</h2>";

foreach ($tables_array as $table_name => $table_schema):
	echo "<p>Generating <i>". $table_name."</i> table.</p>";
	generate_table($table_name, $table_schema);
	endforeach;

echo "<h2>Generating username options.</h2>";

// Get a list of all username options currently in the database...
$username_options_array = [];
$database_query = "SELECT * FROM username_options";
$result = pg_query($database_connection, $database_query);
while ($row = pg_fetch_assoc($result)):
	$username_options_array[$row['option_id']] = $row['en'];
	endwhile;

// How many username options are specified...
echo "<p>There were ".number_format(count($username_options))." possible username options specified.</p>";

// How many usenrame options exist in the database...
echo "<p>There were currently ".number_format(count($username_options_array))." username options in the database.</p>";

// Now add the username options into the database that are not already there...
$count_temp = 0;
foreach($username_options as $option_name => $option_info):

	$option_id = random_number(9);
	while (array_key_exists($option_id, $username_options_array)):
		$option_id = random_number(9);
		endwhile;

	// If the username option has already been added...
	if (in_array($option_info['en'], $username_options_array)):
		$option_id = array_search ($option_info['en'], $username_options_array);
	elseif (in_array($option_name, $username_options_array)):
		$option_id = array_search ($option_name, $username_options_array);
		endif;

	$option_info = array_merge(["option_id" => $option_id], $option_info);

	// If the statement has not been made yet, then prepare the statement...
	if ($count_temp == 0):
		$database_insert_statement = database_insert_statement("username_options", $option_info);
		$result_temp = database_result(pg_prepare($database_connection, "username_options_insert_statement", $database_insert_statement), "Preparing options insertion statement.");
		if ($result_temp !== "success"): break; endif;
		endif;

	// Execute values...
	$result_temp = database_result(pg_execute($database_connection, "username_options_insert_statement", $option_info), "Inserting option ". $option_id ." for ".$option_info['en']);

	// If the result is a success...
	if ($result_temp == "success"):
		$username_options_array[] = $option_name;
		$username_options_array[] = $option_info['en'];
		$username_options_ids_array[] = $option_id;
		$count_temp++;
		endif;

	endforeach;

// How many new username options were added to the database...
echo "<p>All in all, ".number_format($count_temp)." username options have been updated or added.</p>";

echo "<h2>Checking user accounts.</h2>";

// Get any users that currently exist...
$users_array = [];
$database_query = "SELECT * FROM users";
$result = pg_query($database_connection, $database_query);
while ($row = pg_fetch_assoc($result)):
	$users_array[$row['user_id']] = $row;
	endwhile;

// Do some sort of validation on them...
$admin_temp = 0;
foreach ($users_array as $user_id => $user_info):
	if ($user_info['user_status'] !== "administrator"): continue; endif;
	if (empty($user_info['authenticator_hash'])): continue; endif;
	if (empty($user_info['passcode_hash'])): continue; endif;
	$admin_temp = 1;
	endforeach;

// If there is already a viable admin account, then no more steps...
if ($admin_temp == 1):

	echo "<p>There is already an administrator with valid login credentials.</p>";

	echo "<h2>Complete.</h2>";

	echo "<p>There are no more steps. Any further issues will require the assistance of a webmaster.</p>";

	exit;

// If there is no viable admin account, then make one...
elseif ($admin_temp !== 1):

	echo "<p>There was no administrator with valid login credentials. Please create one below.</p>";

	echo "<h2>Create administrator account.</h2>";

	echo "<form target='_top' action-xhr='https://diis.online/?view=install&action=xhr&language=".$language_request."' method='post'>";
	
	// Thing to say whether or not it was successful and to go to homepage...

	echo "<h3>". $translatable_elements['first-choose-your-name'][$language_request] ."</h3>";

	echo "<p>Your author name is also your username for sign-in. It is automatically generated for privacy and anonymity.</p>";

	echo "<span class='username-option-helper'>Either accept this automatically generated name,</span>";

	echo "<amp-list id='username-option-list' max-items='1' width='auto' height='100' layout='fixed-height' reset-on-refresh='always' src='https://diis.online/?view=register&action=usernames&language=". $language_request ."'>";
	echo "<span id='username-option-placeholder' placeholder>". $translatable_elements['loading'][$language_request] ."</span>";
	echo "<span id='username-option-fallback' fallback>". $translatable_elements['failed-to-load-options'][$language_request] ."</span>";
	echo "<template type='amp-mustache'>";
		echo "<input class='username-options-list-item-input' name='username' value='{{combined}}' type='hidden'>";
		echo "<span id='username-option-show' {{username-one}} {{username-two}} {{username-three}}</span>";
	echo "</template>";
	echo "</amp-list>";

	echo "<span class='username-option-helper'>Or choose to generate a new name,</span>";


	// Or choose more
	echo "<span tabindex='0' role='button' on='tap:username-options-list.refresh' id='username-option-new-button'><i class='material-icons'>refresh</i> ". $translatable_elements['generate-new-name'][$language_request] ."</span>";

	// Now let the user go on to the next step

	// If the option is selected, then give a six-digit numerical passcode
	echo "<h3>". $translatable_elements['second-choose-a-six-digit-numerical-passcode'][$language_request] ."</h3>";

	echo "<input id='pincode-input' type='number' name='pin'>";

	echo "<h3>Third, set up your authenticator.</h3>";

	echo "<input type='number' name='pin-authenticator'>";

	echo "<div submit-success><template type='amp-mustache'>Success! {{{message}}}</template></div>";
	echo "<div submit-error><template type='amp-mustache'>{{{message}}}</template></div>";
	echo "<div submitting><template type='amp-mustache'>Submitting...</template></div>";

	echo "<input type='submit' name='submit' value='Create administrator'>";

	echo "</form>";

	// If success then echo the Complete and a little pararaph...

	endif;

function generate_table($table_name, $table_schema, $table_existing=[]) {

	global $database_connection;
	
	if (empty($table_name)): return; endif;
	if (empty($table_schema)): return; endif;
	
	// Parse the table schema...
	$columns_array = [];
	foreach ($table_schema as $column_name => $column_type):
		$columns_array[] = $column_name." ".$column_type;
		endforeach;
	$columns_array[0] .= " PRIMARY KEY";

	// Generate table...
	if (empty($table_existing)):
		$sql_temp = "CREATE TABLE IF NOT EXISTS $table_name (". implode (", ", $columns_array) .")";
		database_result(pg_query($database_connection, $sql_temp), "Generating ".$table_name);
		return; endif;

	// Checking any existing columns
	foreach($table_schema as $column_name => $column_type):
		if (empty($table_existing[$column_name])):
			$sql_temp = "ALTER TABLE ". $table_name ." ADD COLUMN ". $column_name ." ". $column_type;
			database_result(pg_query($database_connection, $sql_temp), "Adding column ". $column_name ." in table ".$table_name);	
		elseif (trim(strtolower($table_existing[$column_name])) !== trim(strtolower($column_type))):
			$sql_temp = "ALTER TABLE ". $table_name ." ALTER COLUMN ". $column_name ." TYPE ".$column_type;
			database_result(pg_query($database_connection, $sql_temp), "Modifying column ". $column_name ." in table ".$table_name);
			endif;
		endforeach; } ?>
