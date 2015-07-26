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
<dom-module id="admin-login">
<style is="custom-style">
	form {
		margin-left: 10px;
		margin-right: 10px;
	}
</style>
<template>
<form id="adminlogin" action="../api/1.0/admin/login.php" method="post">
	<paper-input name="user" label="Username"></paper-input>
	<paper-input name="pass" label="Password" type="password"></paper-input>
	<paper-button on-click="submitLogin" raised>Login</paper-button>
</form>
</template>
<script>
Polymer({
is: "admin-login",
properties: {
	sidebarpage: {
		type: Number,
		value: 0
	}
},
submitLogin: function() {
	document.getElementById('adminlogin').submit();
},
ready: function() {
	
},
});
</script>
</dom-module>
<dom-module id="admin-sidebar">
	<link rel="import" href="../bower_components/paper-input/paper-input.html">
	<link rel="import" href="../bower_components/polymer/polymer.html">
	<link rel="import" href="../bower_components/paper-drawer-panel/paper-drawer-panel.html">
	<link rel="import" href="../bower_components/paper-button/paper-button.html">
	<link rel="import" href="../bower_components/paper-fab/paper-fab.html">
	<link rel="import" href="../bower_components/paper-material/paper-material.html">
	<link rel="import" href="../bower_components/paper-toolbar/paper-toolbar.html">
	<link rel="import" href="../bower_components/paper-icon-button/paper-icon-button.html">
	<link rel="import" href="../bower_components/paper-scroll-header-panel/paper-scroll-header-panel.html">
	<link rel="import" href="../bower_components/paper-dialog-scrollable/paper-dialog-scrollable.html">
	<link rel="import" href="../bower_components/paper-dialog/paper-dialog.html">
	<link rel="import" href="../bower_components/paper-menu/paper-menu.html">
	<link rel="import" href="../bower_components/paper-item/paper-item.html">
	<link rel="import" href="../bower_components/paper-item/paper-icon-item.html">
	<link rel="import" href="../bower_components/paper-styles/paper-styles.html">
	<link rel="import" href="../bower_components/paper-drawer-panel/paper-drawer-panel.html">
	<link rel="import" href="../bower_components/paper-toast/paper-toast.html">
	<link rel="import" href="../bower_components/iron-icon/iron-icon.html">
	<link rel="import" href="../bower_components/iron-icons/iron-icons.html">
	<style>
		:host {
			--paper-item-min-height: 32px;
		}
	</style>
	<template>
		<paper-menu id="sidebarAdminMenu">
			<paper-item on-click="goAdminHome"><i class="fa fa-cog"></i> Quick Start Guide</paper-item>
			<paper-progress value="100" style="width:90%; margin-left:5%; margin-right: 5%;"></paper-progress>
			<paper-item on-click="goEditSessions"><i class="fa fa-cog"></i> Edit Sessions</paper-item>
			<paper-item on-click="goPendingSessions"><i class="fa fa-cog"></i> Quizzes In Progress</paper-item>
			<paper-progress value="100" style="width:90%; margin-left:5%; margin-right: 5%;"></paper-progress>
			<paper-item on-click="goEditQuizzes"><i class="fa fa-cog"></i> My Quizzes</paper-item>
			<paper-progress value="100" style="width:90%; margin-left:5%; margin-right: 5%;"></paper-progress>
			<paper-item on-click="goGrading"><i class="fa fa-cog"></i> Pending Free Response Grading</paper-item>
			<paper-item on-click="goLogout">Logout</paper-item>
		</paper-menu>
	</template>
	<script>
	Polymer({
		is: "admin-sidebar",
		listeners: {
			"toastEvent": "fireToast"
		},
		properties: {
			sidebarpage: {
				type: Number,
				value: 0
			},
			page: {
				type: String,
				value: ""
			}
		},
		goAdminHome: function(){
			window.location = "?p=admin";
		},
		goEditSessions: function(){
			window.location = "?p=sessions";
		},
		goPendingSessions: function(){
			window.location = "?p=pendingsessions";
		},
		goEditQuizzes: function(){
			window.location = "?p=quizadmin";
		},
		goResults: function(){
			window.location = "?p=results";
		},
		goGrading: function(){
			window.location = "?p=gradefr";
		},
		goLogout: function(){
			window.location = "../func/admin.logout.php";
		},
		fireToastNonEvent: function(text){
			this.$.globalToast.text = text;
			this.$.globalToast.show();
		},
		fireToast: function(vars){
			this.$.globalToast.text = vars.detail.toastString;
			this.$.globalToast.show();
		},
		ready: function() {
			if(this.page != ""){
				switch(this.page){
					case "admin":
						this.$.sidebarAdminMenu.select(0);
						break;
					case "sessions":
						this.$.sidebarAdminMenu.select(2);
						break;
					case "pendingsessions":
						this.$.sidebarAdminMenu.select(3);
						break;
					case "quizadmin":
					case "managequiz":
						this.$.sidebarAdminMenu.select(5);
						break;
					case "results":
						this.$.sidebarAdminMenu.select(7);
						break;
					case "gradefr":
						this.$.sidebarAdminMenu.select(8);
						break;
				}
			}
			
		},
		onresp: function(){
			//this.fireToastNonEvent(this.$.mainPanel.narrow);
			this.fire('iron-signal', {name: 'hideshowevent', data: { show: this.$.mainPanel.narrow } });
			if(this.$.mainPanel.narrow){
				
			//	this.$.scrollHeader.scrollAwayTopbar = true;
			} else {
			//	this.$.scrollHeader.fixed = true;
			}
		}
	});
	</script>
	
</dom-module>