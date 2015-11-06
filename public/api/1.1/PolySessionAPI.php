<?
$app->get('/sessions', function() {
	global $mysqli;
	if(isset($_GET['id'])) {
		echo json_encode(PolySession::fromMySQL($mysqli, $_GET['id'], $_SESSION['dbext']), JSON_PRETTY_PRINT);
	} else {
		echo json_encode(PolySession::ownedBy($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
	}
});

$app->get('/sessions/:id', function($id) {
	global $mysqli;
	echo json_encode(PolySession::fromMySQL($mysqli, $id, $_SESSION['dbext']), JSON_PRETTY_PRINT);
});

$app->post('/sessions', function() {
	global $mysqli, $_POST_JSON;
	$status = array();
	$status['status'] = false;
	$status['seg'] = $_POST_JSON;
	if($_POST_JSON) {
		if(isset($_POST_JSON['name']) && isset($_POST_JSON['quiz']) && isset($_POST_JSON['house']) && isset($_POST_JSON['active']) && isset($_POST_JSON['show'])) {
			$obj = PolySession::createCheckMysql($mysqli);
			$obj->owner = $_SESSION['dbext'];
			
			$obj->name = $_POST_JSON['name'];
			$obj->quiz = $_POST_JSON['quiz'];
			$obj->house = $_POST_JSON['house'];

			if($_POST_JSON['active']) {
				$obj->setStatus(true);
			} else {
				$obj->setStatus(false);
			}

			if($_POST_JSON['show']){
				$obj->setShowScores(true);

			} else {
				$obj->setShowScores(false);
			}
			$subStatus = $obj->saveToMysql($mysqli, $_SESSION['dbext']);
			if($subStatus['status']){
				$status['status'] = true;
			} else {
				$status['sub'] = $subStatus;
			}
		}
	}
	echo json_encode($status, JSON_PRETTY_PRINT);
});

$app->put('/sessions', function() {
	global $mysqli;
	if(isset($_GET['id'])) {

	} else {
		
	}
});

$app->put('/sessions/:id', function($id) {
	global $mysqli;
});

$app->delete('/sessions', function() {
	global $mysqli;
	if(isset($_GET['id'])) {
		echo json_encode(PolySession::fromMySQL($mysqli, $_GET['id'], $_SESSION['dbext'])->delete($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
	} else {
		
	}
});

$app->delete('/sessions/:id', function($id) {
	global $mysqli;
	echo json_encode(PolySession::fromMySQL($mysqli, $id, $_SESSION['dbext'])->delete($mysqli, $_SESSION['dbext']), JSON_PRETTY_PRINT);
});

?>
