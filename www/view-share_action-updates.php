<? if (empty($script_code)): exit; endif;

$json_result = [
  "items" => [
		[ "user_id"=>"111", "annotation_timestamp"=>"2018 Jan 1", "annotation_text"=>"Contents of first item"],
		[ "user_id"=>"222", "annotation_timestamp"=>"2018 Feb 02", "annotation_text"=>"Contents of second item"],
		[ "user_id"=>"333", "annotation_timestamp"=>"2018 Mar 3", "annotation_text"=>"Contents of third item"],
		[ "user_id"=>"444", "annotation_timestamp"=>"2018 Apr 4", "annotation_text"=>"Contents of fourth item"],
		[ "user_id"=>"555", "annotation_timestamp"=>"2018 May 5", "annotation_text"=>"Contents of fifth item"],
		],
	];

echo json_encode($json_result);

exit; ?>
