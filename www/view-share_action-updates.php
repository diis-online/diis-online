<? if (empty($script_code)): exit; endif;

$json_result = [
  "items" => [
		[ "author"=>"111", "timestamp"=>"2018 Jan 1", "contents"=>"Contents of first item"],
		[ "author"=>"222", "timestamp"=>"2018 Feb 02", "contents"=>"Contents of second item"],
		[ "author"=>"333", "timestamp"=>"2018 Mar 3", "contents"=>"Contents of third item"],
		[ "author"=>"444", "timestamp"=>"2018 Apr 4", "contents"=>"Contents of fourth item"],
		[ "author"=>"555", "timestamp"=>"2018 May 5", "contents"=>"Contents of fifth item"],
		],
	];

echo json_encode($json_result);

exit; ?>
