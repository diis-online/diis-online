<? include_once('configuration.php');

$database_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
if (pg_connection_status($database_connection) !== PGSQL_CONNECTION_OK): body("Database failure."); endif;

$view_request = $_REQUEST['view'] ?? null;
$share_request = $_REQUEST['share'] ?? null;
$action_request = $_REQUEST['action'] ?? null;

$script_code = random_number(10);

function body($title="Diis", $include=null) {
	
	global $database_connection;
	
	global $view_request;
	global $share_request;
	global $action_request;
	
	global $script_code;
	
	global $login_status;
	
	echo "<!doctype html><html amp lang='en'><head><meta charset='utf-8'>";

	echo "<script async src='https://cdn.ampproject.org/v0.js'></script>";
	echo "<link rel='canonical' href='https://diis.online'>"; // must define canonical url for amp

	// PWA manifest
	// https://developers.google.com/web/fundamentals/web-app-manifest/
	echo "<link rel='manifest' href='manifest.json'>";

	// Include AMP elements
	echo "<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>";
	echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
	echo '<script async custom-element="amp-install-serviceworker" src="https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js"></script>';
	echo '<script async custom-element="amp-fx-collection" src="https://cdn.ampproject.org/v0/amp-fx-collection-0.1.js"></script>';
	echo '<script async custom-element="amp-date-countdown" src="https://cdn.ampproject.org/v0/amp-date-countdown-0.1.js"></script>';
	echo '<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>';
	echo '<script async custom-element="amp-animation" src="https://cdn.ampproject.org/v0/amp-animation-0.1.js"></script>';
	echo '<script async custom-element="amp-list" src="https://cdn.ampproject.org/v0/amp-list-0.1.js"></script>';
	
	// Must define viewport for AMP
	echo "<meta name='viewport' content='width=device-width,minimum-scale=1,initial-scale=1'>";
	
	// General appearance
	echo "<title>Diis</title>";
	echo "<meta name='theme-color' content='#2850AA'>";
	echo "<link rel='icon' type='image/png' href='https://diis.online/browser-icon.png'>";
	echo "<link rel='shortcut icon' type='image/png' href='https://diis.online/browser-icon.png'>";
	echo "<link rel='apple-touch-icon' type='image/png' href='https://diis.online/browser-icon.png'>";

	// Fonts and CSS style
	echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
	echo '<link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">';
	echo '<link href="https://fonts.googleapis.com/css?family=Palanquin" rel="stylesheet">';
	echo "<style amp-custom>";
	include_once('style.css');
	echo "</style>";

	echo "</head><body>";

	echo "<amp-install-serviceworker src='https://diis.online/service-worker.js' layout='nodisplay'></amp-install-serviceworker>";

	if (!(empty($login_status))):
	
		echo "<amp-date-countdown timestamp-seconds='".($login_status['user_login_time']+5)."' layout='fixed-height' height='100' when-ended='stop' on='timeout: timeout-overlay-open.start'>";
		echo "<template type='amp-mustache'><div id='login-hourglass-countdown'>{{m}} minutes, {{s}} seconds left on page</div></template>";
		echo "</amp-date-countdown>";
	
		echo "<div id='login-hourglass-timeout'>Session maybe expired.</div>";

		echo "<div id='timeout-overlay'>";
		echo "<div id='timeout-overlay-alignment'>";
		echo "<span id='timeout-overlay-header'>Your session may be expired.</span>";
		echo "<button id='timeout-overlay-button' on='tap: timeout-overlay-close.start'>Continue anyways</button>";
		echo "</div></div>";
	
		echo "<amp-animation id='timeout-overlay-open' layout='nodisplay'>";
		echo "<script type='application/json'>";
		echo json_encode(["duration"=>"300ms", "animations"=>[ ["selector"=>"#timeout-overlay", "keyframes"=>["visibility"=>"visible"]], [ "selector"=>"#login-hourglass-countdown", "keyframes"=>["visibility"=>"hidden"]] ] ]);
		echo "</script></amp-animation>";
	
		echo "<amp-animation id='timeout-overlay-close' layout='nodisplay'>";
		echo "<script type='application/json'>";
		echo json_encode(["duration"=>"300ms", "animations"=>[ ["selector"=>"#timeout-overlay", "keyframes"=>["visibility"=>"hidden"]], ["selector"=>"#login-hourglass-timeout", "keyframes"=>["visibility"=>"visible"]] ] ]);
		echo "</script></amp-animation>";
	
	
		endif;
	
	if (!(empty($include))): include_once($include);
	else: echo "<h1>". $title ."</h1>"; endif;
	
	footer(); }

function footer() {
	echo "<div id='footer-spacer'></div>";
	echo "</body></html>";
	exit; }

function random_number($length=10) {
	if (!(is_numeric($length))): $length = 10; endif;
	$length = abs(round($length, 0));
	$return_temp = null;
	$count_temp = 0;
	while ($count_temp < $length):
		$return_temp .= rand(0,9);
		$count_temp++; endwhile;
	return $return_temp; }

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
		$sql_temp = "CREATE TABLE IF NOT EXISTS $table (". implode (", ", $columns_array) .")";
		database_result(pg_query($database_connection, $sql_temp), "generating ".$table_name);
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

function database_result($result, $description) {
	global $database_connection;
	if (!($result)):
		echo "<p>Failure<br>" . $description. "<br>" . pg_last_error($database_connection)."</p>";
		return "failure"; endif;
	return "success"; }

if ($view_request == "install"):
	include_once('configuration-install.php');
	endif;

// If there is the edit view, then show the edit

// If there is the history view, then show the history

// If there is no cookie, then show the info

$login_status = [
	"user_id" => "testing",
	"level" => "testing",
	"user_login_time" => time(),
	];

if ( ($view_request == "share") && !(empty($share_request))):

	$share_info = [];

	// Look up the share
	$share_info = [
		"share_id" => "1111",
		"author_id" => "testing",
		];

	// If the action is to edit...
	if ($action_request == "edit"):
		
		// If there is no login status then they need to log in...
		if (empty($login_status)): body('Log In', 'view-login.php');

		// If this is about making a new share...
		elseif ($share_request == "create"): body('Create', 'view-share_action-edit.php');

		// ... Otherwise, if the share does not exist then issue a 404...
		elseif (empty($share_info)): body('404');

		// If the user is the author then they have access of course...
		elseif ($login_status['user_id'] == $share_info['author_id']): body('Edit', 'view-share_action-edit.php');

		// If the user is not the author but is an administator or editor...
		elseif (in_array($login_status['level'], ["administrator", "editor"])): body('Edit', 'view-share_action-edit.php');

		else: body('Bad permissions'); endif;
		
		endif;
	
	// If the share does not exist then issue a 404...
	if (empty($share_info)):
		body('404');
		endif;
			
	// If this is a save request then save it...
	if ($action_request == "save"):
		include_once('view-share_action-edit_xfr.php');		
		endif;
			
	// At this point, it is okay to show the shre
	body($share_info['share_id'], 'view-share.php');

	endif;

if (empty($view_request) && empty($_COOKIE['visit'])):
	body('About', 'view-info.php');
	endif;

if ($view_request == "info"):
	body('About', 'view-info.php');
	endif;
	
if (empty($view_request) || ($view_request == "feed")):
	body('Feed', 'view-feed.php');
	endif;

if ($view_request == "login"):
	body('Log In', 'view-login.php');
	endif;

if ($view_request == "register"):
	body('Register', 'view-register.php');
	endif;

body('404'); ?>
