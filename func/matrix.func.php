<?
function out($var){

}
function mult($mata, $matb) {
	//colums in a needs to equal rows in b
	if(count($mata[0]) == count($matb)) {
		$result = array();
		for($x = 0; $x < count($mata); $x++){
			for($y = 0; $y < count($matb[0]); $y++){
				$result[$x][$y] = 0;
			}
		}
		out($result);
		for ($a=0; $a < count($result); $a++) {
			for ($b=0; $b < count($result[0]); $b++) {
				$total = 0;
				for ($c=0; $c < count($matb); $c++) {
					$total += $mata[$a][$c] * $matb[$c][$b];
				}
				$result[$a][$b] = $total;
			}
		}
		return $result;
	} else {
		echo "Invalid Dim!!";
		return;	
	}
}
function returnHeader($ra, $ca, $rb, $cb, $type, $error = "") {
	$toreturn = "Location: ../?p=math";
	if(is_int($ra)) {
		$toreturn = $toreturn."&ra=".$ra;
	} else {
		$toreturn = $toreturn."&ra=1";
	}
	if(is_int($ca)) {
		$toreturn = $toreturn."&ca=".$ca;
	} else {
		$toreturn = $toreturn."&ca=1";
	}
	if(is_int($rb)) {
		$toreturn = $toreturn."&rb=".$rb;
	} else {
		$toreturn = $toreturn."&rb=1";
	}
	if(is_int($cb)) {
		$toreturn = $toreturn."&cb=".$cb;
	} else {
		$toreturn = $toreturn."&cb=1";
	}
	$toreturn = $toreturn."&type=".$type;
	$toreturn = $toreturn."&error=".$error;
	header($toreturn);
}
if(isset($_POST['form'])){
	if($_POST['form'] == "pre") {
		$ra = (int) $_POST['ra'];
		$ca = (int) $_POST['ca'];
		$rb = (int) $_POST['rb'];
		$cb = (int) $_POST['cb'];
		$type = $_POST['type'];
		$error = "";
		if(is_int($ra) && is_int($ca) && is_int($rb) && is_int($cb)){
			if($type == "add") {
				returnHeader($ra,$ca,$rb,$cb,$type);
			} else if($type == "sub") {
				returnHeader($ra,$ca,$rb,$cb,$type);
			} else if($type == "mult") {
				returnHeader($ra,$ca,$rb,$cb,$type);
			} else {
				returnHeader($ra,$ca,$rb,$cb,$type,"Operation not set!");
			}
		} else {
			returnHeader($ra,$ca,$rb,$cb,$type,"Integers only!");
		}
	}
}
$a = array(
array(2,3,5),
array(4,5,6));
$b = array(
array(5,6,7,9),
array(5,6,4,9),
array(7,8,1,4));
//out(mult($a, $b));
?>