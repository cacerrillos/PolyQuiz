<!doctype html>
<dom-module id="admin-secret-menu">
<template>
<form id="secret" action="../func/secret.func.php" method="post">
	Browse As:
	<select name="uuid">
		<?
		include_once("../func/config.func.php");
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
				<option value="<? echo $data['permsid']; ?>" <? if($_SESSION['dbext']==$data['permsid']){ echo "selected"; } ?>><? echo $data['username']; ?></option>
				<?
			}
		}
		?>
	</select>
	<input type="submit" value="Browse" name="submit">
</form>
<form action="func/secret.func.php" method="post">
	<input type="submit" name="submit" value="Overlord" />
</form>
</template>
<script>
Polymer({
	is: "admin-secret-menu"
});
</script>
</dom-module>