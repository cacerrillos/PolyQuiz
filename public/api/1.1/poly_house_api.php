<?

$app->get('/houses', function() {
	global $mysqli;
	if(isset($_GET['house_id'])) {
		echo json_encode(PolyHouse::get($mysqli, $_GET['house_id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
	} else {
		echo json_encode(PolyHouse::getAll($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
	}
	
});

$app->get('/houses/:houseid', function ($houseid) {
	global $mysqli;
	echo json_encode(PolyHouse::get($mysqli, $houseid, $_SESSION['dbext']), JSON_PRETTY_PRINT);
});

$app->post('/houses', function() {
	global $mysqli;

	global $_POST_JSON;

	if(isset($_POST_JSON['house_name'])) {
		echo json_encode(PolyHouse::post($mysqli, $_POST_JSON['house_name'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
	} else {
		$error = array();
		$error['code'] = 50;
		$error['message'] = "Invalid Arguments";
		$error['errors'] = array();
		$error['errors'][0]['code'] = 10;
		$error['errors'][0]['field'] = 'house_name';
		$error['errors'][0]['message'] = 'Body should contain the field name!';
		echo json_encode($error, JSON_PRETTY_PRINT);
	}
});

$app->put('/houses/:houseid', function($houseid) {
	global $mysqli;

	global $_POST_JSON;

	if($_POST_JSON && isset($_POST_JSON['house_name'])) {
		echo json_encode(PolyHouse::put($mysqli, $houseid, $_POST_JSON['house_name'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
	}

});

$app->put('/houses', function() {
	global $mysqli;

	global $_POST_JSON;

	if(isset($_GET['house_id'])) {
		if($_POST_JSON && isset($_POST_JSON['house_name'])) {
			echo json_encode(PolyHouse::put($mysqli, $_GET['house_id'], $_POST_JSON['house_name'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
		}
	}

});

$app->delete('/houses/:houseid', function($houseid) {
	global $mysqli;
	if(isAdmin()) {
		echo json_encode(PolyHouse::delete($mysqli, $houseid, $_SESSION['dbext']), JSON_PRETTY_PRINT);
	}
});

$app->delete('/houses', function() {
	global $mysqli;
	if(isset($_GET['house_id'])) {
		if(isAdmin()) {
			echo json_encode(PolyHouse::delete($mysqli, $_GET['house_id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
		}
	}
});

?>
