<? // create postgres connection
$postgres_host = "";
$postgres_port = "";
$postgres_database = "";
$postgres_user = "";
$postgres_password = "";
$pg_connection = pg_connect("host=".$postgres_host." port=".$postgres_port." dbname=".$postgres_database." user=".$postgres_user." password=".$postgres_password." options='--client_encoding=UTF8'");
$status = pg_connection_status($pg_connection);
if ($status !== PGSQL_CONNECTION_OK): echo "failed database connection"; exit; endif;

// supported languages
$languages = [
  "arabic" => "عربي",
  "english" => "English",
  "sorani" => "سۆرانی",
  "turkish" => "Türkçe",
  ];
?>
