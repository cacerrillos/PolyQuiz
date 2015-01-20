<?
$theid = -1337;
function theSecretMenu(){
	global $theid, $db_host, $db_user, $db_password, $db_name;
	if(isset($_SESSION['admin_id_num'])){
		if($_SESSION['admin_id_num']==$theid && isset($_SESSION["is_admin"])){
			?>
            <form id="secret" action="func/secret.func.php" method="post">
            	Browse As:
                <select name="uuid">
                	<?
					$mysqli = new mysqli($db_host, $db_user, $db_password);
					$mysqli -> select_db($db_name);
					if(mysqli_connect_errno()) {
						echo "Connection Failed: " . mysqli_connect_errno();
						exit();
					}
					if($stmt = $mysqli->prepare("SELECT * FROM users;")){
						$stmt->execute();
						$stmt->bind_result($data['id'], $data['username'], $data['passwordHash'], $data['permsid']);
						while($stmt->fetch()){
							?>
                            <option value="<? echo $data['permsid']; ?>" <? if($_SESSION['dbext']=="_".$data['permsid']){ echo "selected"; } ?>><? echo $data['username']; ?></option>
                            <?
						}
					}
					?>
                    <input type="submit" value="Browse" name="submit">
                </select>
            </form>
            <form action="func/secret.func.php" method="post">
				<input type="submit" name="submit" value="Overlord" />
            </form>
            <?
		}
	}
}

?>