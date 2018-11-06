<? // Simple API for latest in feed... 

$count_temp = random_number(1);

if (!(empty($_POST))):

	$count_temp = $_POST['page'] + 2;

	$json_result['next'] = "true";

	endif;

$counter_temp = 0;

echo "test"; exit;

while ($counter_temp < 5):
	$counter_temp++;
	$count_temp++;
	$json_result['items'][] = [
		"id" => $count_temp*random_number(2),
		"name" => "Article ".($count_temp+1),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];
	endwhile;

echo json_encode($json_result);

?>
