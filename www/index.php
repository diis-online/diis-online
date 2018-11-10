<? // Initiate the environment...
session_start();
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
$script_code = random_number(10);

// Get the configuration variables...
include_once('configuration.php');

$languages = [ 
	"en" => "English",
	"ar" => "عربي",
	"ku" => "سۆرانی",
	"tr" => "Türkçe",
	];

$translatable_elements = file_get_contents('../translatable-elements.txt', FILE_USE_INCLUDE_PATH);
$translatable_elements = json_decode($translatable_elements, TRUE);

// $signin_status = [
//	"user_id" => "123456789",
//	"level" => "administrator",
//	"signin_time" => (time()-5430),
//	];

// Process the request data...
$view_request = $_REQUEST['view'] ?? null;
$parameter_request = $_REQUEST['parameter'] ?? null;
$action_request = $_REQUEST['action'] ?? null;
$language_request = $_REQUEST['language'] ?? $_COOKIE['language'] ?? null;

// Handle the QR code script...
if ($view_request == "qrcode"):
	if (empty($parameter_request)): exit; endif;
	include_once("view-qrcode.php");
	exit; endif;

// If the user is trying to register but is already logged-in...
if ( ($view_request == "register") && (!(empty($signin_status)) && ($signin_status['level'] !== "administrator")) ):
    $view_request = "account";
    endif;

// Check languages...
if (empty($language_request) || empty($languages[$language_request])): $language_request = key($languages); endif;
if ($language_request !== $_COOKIE['language']): setcookie("language", $language_request, (time()+31557600), '/'); endif; // Expires in one year

// Don't allow someone to sign in if they already are...
if (!(empty($signin_status)) && ($view_request == "signin")): $view_request == "account"; $action_request = null; endif;

// Confirm if the URL is even correct...
$requests_url = [];
if (!(empty($view_request))): $requests_url[] = "view=".$view_request; endif;
if (!(empty($parameter_request))): $requests_url[] = "parameter=".$parameter_request; endif;
if (!(empty($action_request))): $requests_url[] = "action=".$action_request; endif;
$requests_url[] = "language=".$language_request;
$requests_url = "/?".implode("&", $requests_url);
url_structuring($requests_url);

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

		$language_request = "en";

		body("Installation", "view-install.php");

		endif;

	body("Install not allowed.");
	exit; endif;

$database_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
if (pg_connection_status($database_connection) !== PGSQL_CONNECTION_OK): body($translatable_elements['database-failure'][$language_request]); endif;

function body($title="Diis", $include=null) {
	
	global $_SESSION;
	global $_COOKIE;
	global $_SERVER;

	global $database_connection;
	
	global $languages;
	global $translatable_elements;
	
	global $view_request;
	global $parameter_request;
	global $action_request;
	global $language_request;
	
	global $requests_url;
	
	global $script_code;
	
	global $signin_status;
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
	echo '<script async custom-element="amp-install-serviceworker" src="https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js"></script>';

	echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
	echo '<script async custom-element="amp-fx-collection" src="https://cdn.ampproject.org/v0/amp-fx-collection-0.1.js"></script>';
	echo '<script async custom-element="amp-date-countdown" src="https://cdn.ampproject.org/v0/amp-date-countdown-0.1.js"></script>';
	echo '<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>';
	echo '<script async custom-element="amp-animation" src="https://cdn.ampproject.org/v0/amp-animation-0.1.js"></script>';
	echo '<script async custom-element="amp-list" src="https://cdn.ampproject.org/v0/amp-list-0.1.js"></script>';
	echo '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';
	echo '<script async custom-element="amp-timeago" src="https://cdn.ampproject.org/v0/amp-timeago-0.1.js"></script>';
	echo '<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>';
	
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
//	echo '<link href="https://fonts.googleapis.com/css?family=Rakkas" rel="stylesheet">'; // Arabic handwriting
//	echo '<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet">'; // Latin handwriting
//	echo '<link href="https://fonts.googleapis.com/css?family=Reenie+Beanie" rel="stylesheet">'; // Latin handwriting
	echo "<style amp-custom>";
	// The main CSS document
	include_once('style.css');
	// Adding in left-to-right and right-to-left for different languages
	if (in_array($language_request, ["ar", "ku"])): $direction_temp = "rtl"; $text_align_temp = "right";
	elseif (in_array($language_request, ["en", "tr"])): $direction_temp = "ltr"; $text_align_temp = "left"; endif;
	echo " body { direction: ". $direction_temp ."; unicode-bidi: bidi-override; }";
	echo " h1, h2, h3, h4, h5, h6, \n p, blockquote, \n span#edit-window-approved-post-header, \n div#edit-window-approved-post-alignment, \n div#edit-window-edit-post-alignment, \n #edit-window-form-submission-notice, \n div#edit-window-form-instructions p, \n div#edit-window-annotations-alignment { text-align: ". $text_align_temp ."; }";
	echo "</style>";

	echo "</head><body>";

	echo "<amp-install-serviceworker src='https://diis.online/service-worker.js' layout='nodisplay'></amp-install-serviceworker>";

	
	echo "<div id='navigation-chooser-parallax' amp-fx='parallax' data-parallax-factor='1.5'>";
	
	if (!(empty($signin_status)) && ($view_request !== "feed")):
	
		echo "<amp-date-countdown timestamp-seconds='".($signin_status['signin_time']+7200)."' layout='fixed-height' height='40px' when-ended='stop' on='timeout: timeout-overlay-open.start'>";
		echo "<template type='amp-mustache'><div id='signin-hourglass-countdown'><i class='material-icons'>timer</i> {{mm}}:{{ss}} ". $translatable_elements['left-on-page'][$language_request] .".</div></template>";
		echo "</amp-date-countdown>";
	
		echo "<div id='signin-hourglass-timeout'><i class='material-icons'>timer_off</i> ". $translatable_elements['session-may-be-expired'][$language_request] ."</div>";
	
		endif;
	
	if (!(empty($view_request)) && ($view_request !== "feed")):
		echo "<a href='/'><span id='navigation-chooser-home-button'><i class='material-icons'>home</i> ". $translatable_elements['home'][$language_request] ."</span></a>";
		endif;

	if ($view_request !== "install"):
	
		if (empty($signin_status) && ($view_request !== "signin")): echo "<a href='/?view=signin&language=".$language_request."'><span id='navigation-chooser-account-button'>". $translatable_elements['sign-in'][$language_request] ."</span></a>";
		elseif ($view_request !== "signin"): echo "<a href='/?view=account&language=".$language_request."'><span id='navigation-chooser-account-button'><i class='material-icons'>account_circle</i> ". $translatable_elements['account'][$language_request] ."</span></a>"; endif;

		echo "<span id='navigation-chooser-language-button' role='button' tabindex='0' on='tap: language-lightbox.open'><i class='material-icons'>language</i> ". $translatable_elements['language'][$language_request] ."</span>";

		endif;
	
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
	echo json_encode(["duration"=>"300ms", "fill"=>"both", "animations"=>[ [ "selector"=>"#signin-hourglass-countdown", "keyframes"=>["visibility"=>"hidden"]], ["selector"=>"#timeout-overlay", "keyframes"=>["visibility"=>"hidden"]], ["selector"=>"#signin-hourglass-timeout", "keyframes"=>["visibility"=>"visible"]] ] ]);
	echo "</script></amp-animation>";
	
	echo "<amp-lightbox id='language-lightbox' layout='nodisplay'>";
	echo "<span id='language-close-button' role='button' tabindex='0' on='tap: language-lightbox.close'><i class='material-icons'>cancel</i> ". $translatable_elements['close'][$language_request] ." </span>";
	if (!(empty($action_request))): echo "<p id='language-lightbox-caution'>". $translatable_elements['changing-language-will-reset-unsaved-work'][$language_request] ."</p>"; endif;
	foreach ($languages as $language_backend => $language_frontend):
		echo "<a href='https://diis.online".str_replace("language=".$language_request, "language=".$language_backend, $requests_url)."'><span class='language-list-item'>".$language_frontend."</span></a>";
		endforeach;
	echo "</amp-lightbox>";
	
	if (!(empty($include))): include_once($include);
	else: echo "<h1>". $title ."</h1>"; endif;
	
	footer(); }
	    
function footer() {
	global $view_request;
	global $language_request;
	global $translatable_elements;
	echo "<div id='footer-spacer' amp-fx='fade-in'>";
	echo "<div id='footer-spacer-alignment'>";
	if ($view_request !== "policies"): echo "<a href='/?view=policies'><i class='material-icons'>receipt</i> ". $translatable_elements['policies'][$language_request] ."</a>"; endif;	
	echo "<a href='/?view=policies'><i class='material-icons'>phonelink_erase</i> ". $translatable_elements['reset-session'][$language_request] ."</a>";
	echo "</div></div></body></html>";
	exit; }

// Generate a random number
function random_number($length=10) {
	if (!(is_numeric($length))): $length = 10; endif;
	$length = abs(round($length, 0));
	$return_temp = null;
	while (strlen($return_temp) < $length):
		$count_temp = 0;
		$random_temp = rand(0,9);
		while ($random_temp == 0): $random_temp = rand(0,9); endwhile;
		$return_temp .= $random_temp;
		endwhile;
	return $return_temp; }

// Generate a random base32-compliant string
function random_thirtytwo($length=16) {
	$permitted_characters = [
		"2", "3", "4", "5", "6", "7",
		"B", "C", "D", "F", "G", "H", "J",
		"K", "L", "M", "N", "P", "Q", "R",
		"S", "T", "V", "W", "Y", "Z",
		];
	if (!(is_int($length)) || ($length < 1)): $length = 16; endif;
	$return_temp = null;
	while (strlen($return_temp) < $length): $return_temp .= $permitted_characters[rand(0,25)]; endwhile;
	return $return_temp; }

// Encode a string into base32
function encode_thirtytwo ($input_string) {	
	$character_map = [
		"0" => "A", "1" => "B", "2" => "C", "3" => "D", "4" => "E", "5" => "F", "6" => "G", "7" => "H",
		"8" => "I", "9" => "J", "10" => "K", "11" => "L", "12" => "M", "13" => "N", "14" => "O", "15" => "P",
		"16" => "Q", "17" => "R", "18" => "S", "19" => "T", "20" => "U", "21" => "V", "22" => "W", "23" => "X",
		"24" => "Y", "25" => "Z", "26" => "2", "27" => "3", "28" => "4", "29" => "5", "30" => "6", "31" => "7" ];
	$binary_string = $encoded_string = null;
	$string_array = str_split($input_string, 1);
	foreach ($string_array as $string_character):
		$binary_string .= sprintf( "%08d", decbin(ord($string_character)));
		endforeach;
	$binary_array = str_split($binary_string, 5);
	foreach ($binary_array as $binary_temp):
		$binary_temp = str_pad($binary_temp, 5, "0");
		$decimal_temp = bindec($binary_temp);
		$encoded_string .= $character_map[$decimal_temp];
		endforeach;
	return $encoded_string; }

function authenticator_code_check ($authenticator_key, $authenticator_code) {
	$result_temp = floor(gmmktime()/30);
	$result_temp = chr(0).chr(0).chr(0).chr(0).pack('N*', $result_temp);
	$result_temp = hash_hmac('SHA1', $result_temp, $authenticator_key, true);
	$result_temp = substr($result_temp, ord(substr($result_temp, -1)) & 0x0F, 4);
	$result_temp = unpack('N', $result_temp);
	$result_temp = $result_temp[1] & 0x7FFFFFFF;
	if ($authenticator_code == str_pad($result_temp % 1000000, 6, '0', STR_PAD_LEFT)): return "success"; endif;
	return "failure"; }

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

function database_result ($result, $description=null) {
	global $database_connection;
	if (!($result)):
		echo "<p>Failure<br>" . $description. "<br>" . pg_last_error($database_connection)."</p>";
		return "failure"; endif;
	return "success"; }

function url_structuring($requests_url) {
	global $_SERVER;	
	if ($_SERVER['REQUEST_URI'] == $requests_url): return; endif;
	if (strpos($_SERVER['REQUEST_URI'], "__amp_source_origin") !== FALSE): return; endif;
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('HTTP/1.1 301 Moved Permanently'); 
	header('Location: https://diis.online'. $requests_url); }

function json_output ($result, $message, $redirect_url=null) {
	
	header("Content-type: application/json");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: https://diis.online");
	header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");
	
	if ($result == "failure"): header("HTTP/1.0 412 Precondition Failed", true, 412);
	else: header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin"); endif;
	
	if ( ($result == "redirect") && !(empty($redirect_url))):
		header("AMP-Redirect-To: https://diis.online".$redirect_url);
		header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
		endif;
	
	echo json_encode(["result"=>$result, "message"=>$message, "time"=>date("Y-m-d\TG:i:s\Z (e)", time())]);
	
	exit; }

function html_implode($array_temp) {
	$return_temp = [];
	foreach ($array_temp as $key_temp => $value_temp):
		$quote_temp = "'";
		if (strpos($value_temp, $quote_temp) !== FALSE): $quote_temp = '"'; endif;
		$return_temp[] = $key_temp."=".$quote_temp.$value_temp.$quote_temp;
		endforeach;
	return implode(" ", $return_temp); }

// If there is the edit view, then show the edit

// If there is the history view, then show the history

// If there is no cookie, then show the info

if ($view_request == "policies"):
	body($translatable_elements['policies'][$language_request], 'view-policies.php');
	endif;

if ($view_request == "share"):

	$share_info = [];

	$statement_temp = "SELECT * FROM shares_main WHERE share_id=$1";
	$result_temp = pg_prepare($database_connection, "check_share_id_statement", $statement_temp);
	if (database_result($result_temp) !== "success"): json_output("failure", "Database #176."); endif;
		
	// The share ID is usually from the URL, but sometimes we want to look up something from a relationship...
	$share_id = $parameter_request ?? $_POST['relationship_to'] ?? null;
	if ( !(empty($share_id)) ):
		$result_temp = pg_execute($database_connection, "check_share_id_statement", ["share_id"=>$share_id]);
		if (database_result($result_temp) !== "success"): body('404'); endif;
		while ($row_temp = pg_fetch_assoc($result_temp)):
			$share_info = $row_temp;
			endwhile;
		endif;

	// This is case we are creating a standalone share and there is no share_id involved yet...
	if ( empty($share_id) && !(empty($_POST['content_status'])) && ($_POST['content_status'] == "uncreated") && ($action_request == "xhr") ):
		$share_info = [
			"share_id" => null,
			"author_id" => $signin_status['user_id'],
			];
		endif;
		
	// If the action requires permissions...
	if (in_array($action_request, ["edit", "xhr", "updates", "create-standalone", "create-reply", "create-translation"])):
		
		$permission_temp = 0;

		// If there is no signin status or an invalid signin status then they need to sign in...
		if (empty($signin_status) || !(in_array($signin_status['level'], ["administrator", "publisher-plus", "publisher"]))): body('Sign In', 'view-signin.php');

		// If this is about creating a new share...
		elseif (in_array($action_request, ["create-standalone"])): body($translatable_elements['create'][$language_request], 'view-share_action-create.php');

		// If this is about replying or translating a share...
		elseif (in_array($action_request, ["create-reply", "create-translation"]) && !(empty($share_info))): body($translatable_elements[$action_request][$language_request], 'view-share_action-create.php');

		// ... Otherwise, if the share does not exist or mismatches then issue a 404...
		elseif (empty($share_info) || ($share_info['share_id'] !== $parameter_request)): body('404');

		// If the user is neither the author, they have permission...
		elseif ($signin_status['user_id'] == $share_info['author_id']): $permission_temp = 1;

		// If the user is an administrator or editor, they have permission...
		elseif (in_array($signin_status['level'], ["administrator", "editor"])): $permission_temp = 1;

		// The user must have bad permissions...
		else: body($translatable_elements['bad-permissions'][$language_request]); endif;

		// Just reaffirming the user must have permission...
		if ($permission_temp == 1):
			if ($action_request == "edit"): body($translatable_elements['edit'][$language_request], 'view-share_action-edit.php');
			elseif ($action_request == "xhr"): include_once('view-share_action-xhr.php');
			elseif ($action_request == "updates"): include_once('view-share_action-updates.php');
			else: body('404'); endif;
			endif;

		endif;

	// If there is no share info...
	if (empty($share_info)): body("404"); endif;

	// If there share exists then give us a nice URL...
	url_structuring("/?view=share&parameter=".$share_info['share_id']);

	// At this point, it is okay to show the share
	body($share_info['share_id'], 'view-share.php');

	endif;

if (empty($view_request) || ($view_request == "feed")):
	if ($action_request == "updates"): include_once('view-feed_action-updates.php'); exit;
	else: body($translatable_elements['home'][$language_request], 'view-feed.php'); endif;
	endif;
	
if ($view_request == "signin"):
	if ($action_request == "xhr"): include_once('view-signin_action-xhr.php'); exit;
	else: body($translatable_elements['sign-in'][$language_request], 'view-signin.php'); endif;
	endif;

if ($view_request == "register"):

	// None of this is available if you are already logged in
	if (!(empty($signin_status))): body('404'); 

	// Displaying username options requires almost no security verification
	elseif ($action_request == "usernames"): include_once('view-register_action-usernames.php'); exit;

	// Displaying username options requires almost no security verification
	elseif ($action_request == "passcode"): include_once('view-register_action-passcode.php'); exit;

	// Block requests to create administrators if we are not in installation mode
	elseif ( ($parameter_request == "administrator") && ($allow_install !== "enabled") ): body('404'); 

	// Backend for adding in users, where the substantive security verification takes place
	elseif ($action_request == "xhr"): include_once('view-register_action-xhr.php'); exit;

	// Go ahead and bring this up, where no substantive security verification takes place
	else: body($translatable_elements['register'][$language_request], 'view-register.php'); endif;

	endif;

body('404'); ?>
