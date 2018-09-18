<? // Initiate the environment...
session_start();
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
$script_code = random_number(10);

// Get the configuration variables...
include_once('configuration.php');

// Process the request data...
$view_request = $_REQUEST['view'] ?? null;
$share_request = $_REQUEST['share'] ?? null;
$action_request = $_REQUEST['action'] ?? null;
$language_request = $_REQUEST['language'] ?? $_COOKIE['language'] ?? null;

// Check if we are installing...
if ($view_request == "install"):

	if ($allow_install == "enabled"):

		// Generate main connection...
		$database_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");

		// If there is an error then maybe the database needs to be made, or user permissions need to be assigned...
		if (pg_connection_status($database_connection) !== PGSQL_CONNECTION_OK):

			// Connect to PostgreSQL...
			$install_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
			if (pg_connection_status($install_connection) !== PGSQL_CONNECTION_OK): echo "PostgreSQL connection failure."; exit; endif;

			// Create main database...
			$sql_temp = "CREATE DATABASE ". $postgres_database ." WITH ENCODING='UTF8' LC_COLLATE='en_US.UTF8' LC_CTYPE='en_US.UTF8'";
			$result = pg_query($install_connection, $sql_temp);
			if (!($result)): echo "<p>Failure creating database<br>" . pg_last_error($install_connection)."</p>"; endif;

			// Assign user to database...
			$sql_temp = "GRANT ALL PRIVILEGES ON DATABASE ". $postgres_database ." TO ". $postgres_user;
			$result = pg_query($install_connection, $sql_temp);
			if (!($result)): echo "<p>Failure assigning ".$postgres_user." to ".$postgres_database."<br>" . pg_last_error($install_connection)."</p>"; endif;

			// Generate main connection...
			$database_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
			if (pg_connection_status($database_connection) !== PGSQL_CONNECTION_OK): echo "Database connection failure."; exit; endif;

			endif;

		if ($action_request == "xhr"): include_once('view-install_action-xhr.php');
		else: body("Installation", "configuration-install.php"); endif;

		endif;

	body("Install not allowed.");
	exit; endif;

// Check languages...
if (empty($language_request) || empty($languages[$language_request])): $language_request = key($languages); endif;
if ($language_request !== $_COOKIE['language']): setcookie("language", $language_request, (time()+31557600), '/'); endif; // Expires in one year

// Confirm if the URL is even correct...
$requests_url = [];
if (!(empty($view_request))): $requests_url[] = "view=".$view_request; endif;
if (!(empty($share_request))): $requests_url[] = "share=".$share_request; endif;
if (!(empty($action_request))): $requests_url[] = "action=".$action_request; endif;
$requests_url[] = "language=".$language_request;
$requests_url = "/?".implode("&", $requests_url);
if ($_SERVER['REQUEST_URI'] !== $requests_url):
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('HTTP/1.1 301 Moved Permanently'); 
	header('Location: https://diis.online'. $requests_url);
	endif;

$database_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
if (pg_connection_status($database_connection) !== PGSQL_CONNECTION_OK): body("Database failure."); endif;

$translatable_elements = file_get_contents('../translatable-elements.txt', FILE_USE_INCLUDE_PATH);
$translatable_elements = json_decode($translatable_elements, TRUE);

function body($title="Diis", $include=null) {
	
	global $_SESSION;
	global $_COOKIE;
	global $_SERVER;

	global $database_connection;
	
	global $languages;
	global $translatable_elements;
	
	global $view_request;
	global $share_request;
	global $action_request;
	global $language_request;
	
	global $requests_url;
	
	global $script_code;
	
	global $login_status;
	global $share_info;
	
	$language_document = $language_request;
	if (empty($action_request) && !(empty($share_info['content_language'])) && ($language_request !== $share_info['content_language'])):
		$language_document = $share_info['content_language'];
		endif;
	
	echo "<!doctype html><html amp lang='".$language_document."'><head><meta charset='utf-8'>";

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
	echo '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';
	
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
	
	echo "<div id='navigation-chooser-parallax' amp-fx='parallax' data-parallax-factor='1.5'>";
	
	if (!(empty($login_status))):
	
		echo "<amp-date-countdown timestamp-seconds='".($login_status['user_login_time']+7200)."' layout='fixed-height' height='40px' when-ended='stop' on='timeout: timeout-overlay-open.start'>";
		echo "<template type='amp-mustache'><div id='login-hourglass-countdown'><i class='material-icons'>timer</i> {{mm}}:{{ss}} left on page.</div></template>";
		echo "</amp-date-countdown>";
	
		echo "<div id='login-hourglass-timeout'><i class='material-icons'>timer_off</i> Session may be expired.</div>";
	
		endif;
	
	if (!(empty($action_request))): echo "<a href='/'><span id='navigation-chooser-home-button'>". $translatable_elements['home'][$language_request] ."</span></a>"; endif;

	if (empty($login_status)): echo "<span id='navigation-chooser-account-button'><i class='material-icons'>account_circle</i> ". $translatable_elements['sign-in'][$language_request] ."</span>";
	else: echo "<span id='navigation-chooser-account-button'><i class='material-icons'>account_circle</i> ". $translatable_elements['account'][$language_request] ."</span>"; endif;

	if (empty($action_request)): echo "<span id='navigation-chooser-language-button' role='button' tabindex='0' on='tap: language-lightbox.open'><i class='material-icons'>translate</i> ". $translatable_elements['language'][$language_request] ."</span>"; endif;
	
	if (empty($view_request) || ($view_request == "feed")): echo "<span id='navigation-chooser-feed-button'><i class='material-icons'>refresh</i> ". $translatable_elements['refresh-shares'][$language_request] ."</span>";
	elseif (empty($action_request)): echo "<a href='/'><span id='navigation-chooser-feed-button'><i class='material-icons'>play_arrow</i> ". $translatable_elements['read-shares'][$language_request] ."</span></a>"; endif;
	
	echo "</div>";
	
	echo "<div id='timeout-overlay'>";
	echo "<div id='timeout-overlay-alignment'>";
	echo "<span id='timeout-overlay-header'>Your session may be expired.</span>";
	echo "<button id='timeout-overlay-button' on='tap: timeout-overlay-close.start'>Continue anyways</button>";
	echo "</div></div>";
	
	echo "<amp-animation id='timeout-overlay-open' layout='nodisplay'>";
	echo "<script type='application/json'>";
	echo json_encode(["duration"=>"300ms", "fill"=>"both", "animations"=>[ ["selector"=>"#timeout-overlay", "keyframes"=>["visibility"=>"visible"]] ] ]);
	echo "</script></amp-animation>";
	
	echo "<amp-animation id='timeout-overlay-close' layout='nodisplay'>";
	echo "<script type='application/json'>";
	echo json_encode(["duration"=>"300ms", "fill"=>"both", "animations"=>[ [ "selector"=>"#login-hourglass-countdown", "keyframes"=>["visibility"=>"hidden"]], ["selector"=>"#timeout-overlay", "keyframes"=>["visibility"=>"hidden"]], ["selector"=>"#login-hourglass-timeout", "keyframes"=>["visibility"=>"visible"]] ] ]);
	echo "</script></amp-animation>";
	
	echo "<amp-lightbox id='language-lightbox' layout='nodisplay'>";
	echo "<div id='language-close-button' role='button' tabindex='0' on='tap: language-lightbox.close'><i class='material-icons'>cancel</i> ". $translatable_elements['close'][$language_request] ."</div>";
	foreach ($languages as $language_backend => $language_frontend):
		echo "<a href='https://diis.online".str_replace("language=".$language_request, "language=".$language_backend, $requests_url)."'><span class='language-list-item'>".$language_frontend."</span></a>";
		endforeach;
	echo "</amp-lightbox>";
	
	if (!(empty($include))): include_once($include);
	else: echo "<h1>". $title ."</h1>"; endif;
	
	footer(); }
	    
function footer() {
	echo "<div class='footer-spacer'></div>";
	echo "</body></html>";
	exit; }

function random_number($length=10) {
	if (!(is_numeric($length))): $length = 10; endif;
	$length = abs(round($length, 0));
	$return_temp = null;
	$count_temp = 0;
	while ($count_temp < $length):
		$random_temp = rand(0,9);
		while (($count_temp == 0) && ($random_temp == 0)): $random_temp = rand(0,9); endwhile;
		$return_temp .= $random_temp;
		$count_temp++; endwhile;
	return $return_temp; }

function database_insert_statement ($table_name, $values_temp, $primary_key=null) {
	
	$columns_temp = $bound_values_temp = $updates_temp = [];
	$count_temp = 1;
	foreach ($values_temp as $column_temp => $value_temp):
		$columns_temp[] = $column_temp;
		$bound_values_temp[] = "$".$count_temp;
		$updates_temp[] = $column_temp."=excluded.".$column_temp;
		$count_temp++; endforeach;

	if (empty($primary_key)):
		reset($values_temp);
		$primary_key = key($values_temp);
		endif;

	$database_insert_statement = "INSERT INTO ". $table_name ." (". implode(", ", $columns_temp) .") VALUES (". implode(", ", $bound_values_temp) .") ON CONFLICT (". $primary_key .") DO UPDATE SET ".implode(", ", $updates_temp);
	return $database_insert_statement; }

function database_result($result, $description) {
	global $database_connection;
	if (!($result)):
		echo "<p>Failure<br>" . $description. "<br>" . pg_last_error($database_connection)."</p>";
		return "failure"; endif;
	return "success"; }

// If there is the edit view, then show the edit

// If there is the history view, then show the history

// If there is no cookie, then show the info

$login_status = [
	"user_id" => "testing",
	"level" => "testing",
	"user_login_time" => (time()-5430),
	];

$share_info = [];

if ( ($view_request == "share") && !(empty($share_request))):

	// Look up the share
	$share_info = [
		"share_id" => "1111",
		"author_id" => "testing",
		"content_approved" => "This is the approved post.",
		"content_draft" => "This is the draft post.",
		];

	// If the action requires permissions...
	if (in_array($action_request, ["edit", "xhr", "updates"])):
		
		$permission_temp = 0;

		// If there is no login status then they need to log in...
		if (empty($login_status)): body('Log In', 'view-login.php');

		// If this is about making a new share...
		elseif ($share_request == "create"): body('Create', 'view-share_action-edit.php');

		// ... Otherwise, if the share does not exist then issue a 404...
		elseif (empty($share_info) || ($share_info['share_id'] !== $share_request)): body('404');

		// If the user is neither the author, they have permission...
		elseif ($login_status['user_id'] == $share_info['author_id']): $permission_temp = 1;

		// If the user is an administrator or editor, they have permission...
		elseif (in_array($login_status['level'], ["administrator", "editor"])): $permission_temp = 1;

		// The user must have bad permissions...
		else: body('Bad permissions'); endif;

		// Just reaffirming the user must have permission...
		if ($permission_temp == 1):
			if ($action_request == "edit"): body('Edit', 'view-share_action-edit.php');
			elseif ($action_request == "xhr"): include_once('view-share_action-xhr.php');		
			elseif ($action_request == "updates"): include_once('view-share_action-updates.php');
			else: body('404'); endif;
			endif;

		endif;


	// At this point, it is okay to show the share
	body($share_info['share_id'], 'view-share.php');

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
