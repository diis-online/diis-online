<? if (empty($script_code)): exit; endif;

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");
	// if failure
	// header("HTTP/1.0 412 Precondition Failed", true, 412);
	// and end headers here
	// if no redirect
	// header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	// and end headers here
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");

$json_result = [
  "items" => [
		[ "user_id"=>$_SESSION['identifier']."111", "annotation_timestamp"=>"2018 Jan 1", "annotation_text"=>"Contents of first item".random_number(10)],
		[ "user_id"=>"222", "annotation_timestamp"=>"2018 Feb 02", "annotation_text"=>"Contents of second item".random_number(10)],
		[ "user_id"=>"333", "annotation_timestamp"=>"2018 Mar 3", "annotation_text"=>"Contents of third item".random_number(10)],
		[ "user_id"=>"444", "annotation_timestamp"=>"2018 Apr 4", "annotation_text"=>"Contents of fourth item".random_number(10)],
		[ "user_id"=>"555", "annotation_timestamp"=>"2018 May 5", "annotation_text"=>"Contents of fifth item".random_number(10)],
		],
	];

echo json_encode($json_result);

exit; ?>
