<? include_once("configuration.php");

//
//
// table schema for system configuration, e.g. for reCAPTCHA
$table_name = "system_configuration";
$table_schema = [
	"configuration_id" => "INTEGER",
	"configuration_category" => "VARCHAR(20)",
	"configuration_frontend" => "VARCHAR(50)",
	];



//
//
// table schema for translatable u.x. elements
$table_name = "translatable_elements";
$table_schema = [
	"element_id" => "VARCHAR(20)",
	];
foreach ($system_languages as $language_key => $language_frontend):
	$table_schema[$language_key] = "VARCHAR(500)";
	endforeach;

$translatable_elements = file_get_contents("../translatable-elements.txt", FILE_USE_INCLUDE_PATH);
// parse $translatable_elements and insert into the database



//
//
// table schema for username options
$table_name = "username_options";
$table_schema = [
  "option_id" => "INTEGER",
	];
foreach ($system_languages as $language_key => $language_frontend):
	$table_schema[$language_key] = "VARCHAR(100)";
	endforeach;

$username_options = file_get_contents("../username-options.txt", FILE_USE_INCLUDE_PATH);
// parse out the options and insert into the database



//
//
// table schema for users, including status
$table_name = "users";
$table_schema = [
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



//
//
// table schema for shares
$table_schema = [
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



//
//
// table schema for shares access — all these users will have access, in addition to the author
$table_schema = [
	"access_id" => "INTEGER", // Unique ID of their access ... Should be a random digit
	"user_id" => "INTEGER", // The user that has the access
	"content_id" => "INTEGER", // The content that the user has access to
	"access_status" => "VARCHAR(20)", // Can be: active, deprecated
	"access_time" => "INTEGER", // UNIX timestamp of when the change was made
	];



//
//
// table schema for archiving any work on shares
$table_schema = [
	"content_archive_id" => "INTEGER", // Unique ID of the archived work
	"user_id" => "INTEGER", // The user making the change
	"change_field" => "VARCHAR(20)", // The field where the change is being made.
	"change_value" => "TEXT", // The value that is being placed
	"change_time" => "INTEGER", // UNIX timestamp of when the change was made
	];



//
//
// table schema for internal shares annotations
$table_schema = [
	"annotation_id" => "INTEGER", // Unique ID of the annotation
	"content_id" => "INTEGER", // ID of the content being annotated
	"user_id" => "INTEGER", // ID of the user making the annotation
	"annotation_text" => "TEXT", // Content of the annotation
	"annotation_timestamp" => "VARCHAR(20)", // UNIX timestamp of when the annotation was made
	]; ?>
