<? if (empty($script_code)): exit; endif;

$tables_array = [];

// Table schema for system configuration, e.g. for reCAPTCHA
$tables_array['system_configuration'] = [
	"configuration_id" => "INTEGER",
	"configuration_category" => "VARCHAR(50)",
	"configuration_frontend" => "VARCHAR(100)",
	];

// Table schema for translatable u.x. elements
$tables_array['translatable_elements'] = [
	"element_name" => "VARCHAR(20)", // the name of the translatable u.x. element
	];
foreach ($system_languages as $language_key => $language_frontend):
	$tables_array['translatable_elements'][$language_key] = "TEXT";
	endforeach;

// Table schema for username options
$tables_array['username_options'] = [
	"option_id" => "INTEGER",
	];
foreach ($system_languages as $language_key => $language_frontend):
	$tables_array['username_options'][$language_key] = "VARCHAR(100)";
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
	"user_created" => "INTEGER", // UNIX timestamp of when the user was created
	"user_hold" => "INTEGER", // UNIX timestamp of how long until the user can log in again, or indefinite
	];

// Table schema for shares
$tables_array['shares_main'] = [
	"content_id" => "INTEGER",
	"author_id" => "INTEGER",
	"content_language" => "VARCHAR(20)",
	"relationship_type" => "VARCHAR(20)", // Can be: translation, reply, continuation
	"relationship_to" => "INTEGER", // The content_id of what it has this relationship to
	"content_approved" => "TEXT", // The approved content body
	"content_draft" => "TEXT", // The draft content body
	"content_status" => "VARCHAR(20)", // Can be: published, published-pending, pending, draft, frozen, removed
	"content_published" => "VARCHAR(20)", // UNIX timestamp of when the content is published
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
	generate_table($table_name, $table_schema);
	endforeach;

// Fill in translatable elements...
$translatable_elements = file_get_contents("../translatable-elements.txt", FILE_USE_INCLUDE_PATH);
// parse $translatable_elements and insert into the database

// Fill in username options...
$username_options = file_get_contents("../username-options.txt", FILE_USE_INCLUDE_PATH);
// parse out the options and insert into the database

exit; ?>
