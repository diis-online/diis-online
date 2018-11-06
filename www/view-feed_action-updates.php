<? // Simple API for latest in feed... 

$count_temp = random_number(1);

if (!(empty($_POST))):

	$count_temp = $_POST['paging'] + 2;

	$json_result['next'] = "true";

	endif;

$json_result['items'][] = [
		"name" => "Article ".($count_temp+1),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

$json_result['items'][] = [
		"name" => "Article ".($count_temp+2),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

$json_result['items'][] = [
		"name" => "Article ".($count_temp+3),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

$json_result['items'][] = [
		"name" => "Article ".($count_temp+4),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

echo json_encode($json_result);

?>
