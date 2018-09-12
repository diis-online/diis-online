<? if (empty($script_code)): exit; endif;

$install_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
if (pg_connection_status($install_connection) !== PGSQL_CONNECTION_OK): body("Connection failure."); endif;

if (strpos($postgres_database, " ") !== FALSE): body("Database name invalid."); exit; endif;

$sql_temp = "CREATE DATABASE ". $postgres_database ." WITH ENCODING='UTF8' LC_COLLATE='en_US.UTF8' LC_CTYPE='en_US.UTF8'";
$result = pg_query($install_connection, $sql_temp);
if (!($result)): echo "<p>Failure<br>Creating database<br>" . pg_last_error($install_connection)."</p>"; endif;

$sql_temp = "GRANT ALL PRIVILEGES ON DATABASE ". $postgres_database ." TO ". $postgres_user;
$result = pg_query($install_connection, $sql_temp);
if (!($result)): echo "<p>Failure<br>Assigning ".$postgres_user." to ".$postgres_database."<br>" . pg_last_error($install_connection)."</p>"; endif;

$database_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
if (pg_connection_status($database_connection) !== PGSQL_CONNECTION_OK): body("Database failure."); endif;

$tables_array = [];

// Table schema for system configuration, e.g. for reCAPTCHA
$tables_array['system_configuration'] = [
	"configuration_id" => "INTEGER",
	"configuration_category" => "VARCHAR(50)",
	"configuration_frontend" => "VARCHAR(100)",
	];

// Table schema for username options
$username_options = file_get_contents("../username-options.txt", FILE_USE_INCLUDE_PATH);
$username_options = json_decode($username_options, TRUE);
$tables_array['username_options'] = [
	"option_id" => "INTEGER",
	];
foreach ($username_options as $option_name => $option_info):
	foreach (array_keys($option_info) as $language_key):
		$tables_array['username_options'][$language_key] = "VARCHAR(100)";
		endforeach;
	break;
	endforeach;

// Table schema for users, including status
$tables_array['users'] = [
	"user_id" => "INTEGER", // The unique user id
	"username_one" => "INTEGER", // The first adjective of their username
	"username_two" => "INTEGER", // The second adjective of their username
	"username_three" => "INTEGER", // The noun of their username
	"user_status" => "VARCHAR(20)", // Can be: administrator, unconfirmed, pending, approved, frozen, removed
	"user_pin_authenticator_hashed" => "VARCHAR(300)", // For authenticating the six-digit pin they get from Authenticator
	"user_pin_memory_hashed" => "VARCHAR(300)", // For authenticating the six-digit pin they memorize
	"user_created_time" => "INTEGER", // UNIX timestamp of when the user was created
	"user_hold_time" => "INTEGER", // UNIX timestamp of how long until the user can log in again, or indefinite
	"user_login_time" => "INTEGER", // UNIX timestamp of when the last login was created
	];

// Table schema for shares
$tables_array['shares_main'] = [
	"content_id" => "INTEGER",
	"author_id" => "INTEGER",
	"content_language" => "VARCHAR(20)",
	"relationship_type" => "VARCHAR(20)", // Can be: translation, reply, continuation
	"relationship_to" => "INTEGER", // The content_id of what it has this relationship to
	"content_published" => "TEXT", // The published content body
	"content_draft" => "TEXT", // The draft content body
	"content_status" => "VARCHAR(20)", // Can be: published, saved, pending, frozen, removed
	"published_time" => "INTEGER", // UNIX timestamp of when the content is published
	];

// Table schema for shares access — all these users will have access, in addition to the author
$tables_array['shares_access'] = [
	"access_id" => "INTEGER", // Unique ID of their access ... Should be a random digit
	"user_id" => "INTEGER", // The user that has the access
	"content_id" => "INTEGER", // The content that the user has access to
	"access_status" => "VARCHAR(20)", // Can be: active, deprecated
	"access_time" => "INTEGER", // UNIX timestamp of when the change was made
	];

// Table schema for archiving any work on shares
$tables_array['shares_archive'] = [
	"content_archive_id" => "INTEGER", // Unique ID of the archived work
	"user_id" => "INTEGER", // The user making the change
	"change_field" => "VARCHAR(20)", // The field where the change is being made.
	"change_value" => "TEXT", // The value that is being placed
	"change_time" => "INTEGER", // UNIX timestamp of when the change was made
	];

// Table schema for internal shares annotations
$tables_array['shares_annotations'] = [
	"annotation_id" => "INTEGER", // Unique ID of the annotation
	"content_id" => "INTEGER", // ID of the content being annotated
	"user_id" => "INTEGER", // ID of the user making the annotation
	"annotation_text" => "TEXT", // Content of the annotation
	"annotation_timestamp" => "VARCHAR(20)", // UNIX timestamp of when the annotation was made
	]; 

foreach ($tables_array as $table_name => $table_schema):
	echo "<p>Generating ". $table_name."</p>";
	generate_table($table_name, $table_schema);
	endforeach;

// Get a list of all username options currently in the database...
$username_options_array = [];
$username_options_ids_array = [];

echo "<p>There are currently ".number_format(count($username_options_array))." username options in the database.</p>";

// Now add the username options into the database that are not already there...
$count_temp = 0;
foreach($username_options as $option_name => $option_info):

	// If the username option has already been added...
	if (in_array($option_info['en'], $username_options_array)): continue; endif;

	$option_id = random_number(10);
	while (in_array($option_id, $username_options_ids_array)):
		$option_id = random_number(10);
		endwhile;

	$option_info = array_merge(["option_id" => $option_id], $option_info);

	// If the statement has not been made yet, then prepare the statement...
	if ($count_temp == 0):
		$database_insert_statement = database_insert_statement("username_options", $option_info);
		$result_temp = database_result(pg_prepare($database_connection, "username_options_insert_statement", $database_insert_statement), "Preparing options insertion statement.");
		if ($result_temp !== "success"): break; endif;
		endif;

	// Execute values
	$result_temp = database_result(pg_execute($database_connection, "username_options_insert_statement", $option_info), "Inserting option ". $option_id ." for ".$option_info['en']);

	if ($result_temp == "success"):
		$username_options_array[] = $option_info['en'];
		$username_options_ids_array[] = $option_id;
		endif;

	$count_temp++; endforeach;

echo "<p>Inserted ".number_format($count_temp)." username options.</p>";

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
		endforeach; }

exit; ?>
