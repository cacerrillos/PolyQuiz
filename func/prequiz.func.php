<?
session_start();
include_once("genquiz.func.php");
include_once("polysession.func.php");
include_once("config.func.php");
if(isset($_POST['submit'])){
	if($_POST['submit']=="Begin"){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$type = $_POST['type'];
		if($type=="normal"){
			$uuid = $_POST['uuid'];
			$house = $_POST['house'];
			if(strlen($firstname)>0 && strlen($lastname)>0 && isset($_POST['house'])){
				$_SESSION['firstname'] = $_POST['firstname'];
				$_SESSION['lastname'] = $_POST['lastname'];
				$_SESSION['house'] = $house;
				$temp = new quizFromMysql();
				$generatedquiz = $temp -> createQuiz($uuid);
				$generatedquiz -> randomize();
				if($generatedquiz==false){
					header('Location: ' . $_SERVER['HTTP_REFERER']);
				} else {
					$_SESSION['quiz'] = $generatedquiz;
				}
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			if(strlen($firstname)>0 && strlen($lastname)>0 && strlen($_POST['sessionid'])>0 && strlen($_POST['sessionkey'])>0){
				$sessionid = $_POST['sessionid'];
				$sessionkey = $_POST['sessionkey'];
				$qsfm = new quizSessionFromMysql();
				$tempqs = $qsfm -> getSession(getDBExt($sessionid), $sessionid, $sessionkey);
				if($tempqs==false){
					header('Location: ' . $_SERVER['HTTP_REFERER']."");
				} else {
					if($tempqs!=false){
						$uuid = $tempqs->getQuizes();
						$house = $tempqs->getHouse();
					}
					if(strlen($firstname)>0 && strlen($lastname)>0){
						$_SESSION['firstname'] = $_POST['firstname'];
						$_SESSION['lastname'] = $_POST['lastname'];
						$_SESSION['house'] = $house;
						$_SESSION['dbext'] = getDBExt($sessionid);
						$temp = new quizFromMysql();
						$generatedquiz = $temp -> createQuiz($uuid);
						$generatedquiz -> randomize();
						if($generatedquiz==false){
							header('Location: ' . $_SERVER['HTTP_REFERER']);
						} else {
							$_SESSION['session'] = $tempqs;
							$generatedquiz -> setSession($tempqs);
							$_SESSION['quiz'] = $generatedquiz;
							header('Location: ' . $_SERVER['HTTP_REFERER']);
						}
						header('Location: ' . $_SERVER['HTTP_REFERER']);
					}
					header('Location: ' . $_SERVER['HTTP_REFERER']);
				}
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	} else if($_POST['submit']=="Resume"){
		$resumeid = $_POST['id'];
		$resumekey = $_POST['key'];
		if(strlen($resumeid)>0 && strlen($resumekey)>0){
			session_unset();
			$ps = new PolySessionRestore($resumeid, $resumekey);
			$ps->pull();
		} else {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} else {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>