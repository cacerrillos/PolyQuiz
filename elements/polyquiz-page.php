<?
session_start();
?>
<!doctype html>
<dom-module id="polyquiz-page">
	<link rel="import" href="../bower_components/paper-progress/paper-progress.html">
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
	
	<style is="custom-style">
	paper-drawer-panel {
		--paper-drawer-panel-main-container: {
			background-color: #BBDEFB;
		};
	}
	paper-fab {
		position: absolute;
		bottom: 16px;
		right: 16px;
	}
	:host {
		--paper-item-min-height: 32px;
	}
	.smallbuttons {
		min-width: 2em;
	}
	</style>
	<template>
		<paper-drawer-panel id="mainPanel" on-paper-responsive-change="onresp">
			<paper-header-panel drawer>
				<paper-toolbar>
					<div style="color:#FFFFFF">Application</div>
				</paper-toolbar>
				<paper-menu id="sidebarMenu">
					<paper-icon-item on-click="goHome">
						<iron-icon icon="icons:home" item-icon></iron-icon> Home
					</paper-icon-item>
					<paper-icon-item on-click="goTakeAQuiz">
						<iron-icon icon="icons:content-paste" item-icon></iron-icon> Take A Quiz
					</paper-icon-item>
					<paper-icon-item on-click="goLogin">
						<iron-icon icon="icons:settings" item-icon></iron-icon> Manage
					</paper-icon-item>
				</paper-menu>
				<?
				if(isset($_SESSION["is_admin"])){
					if(isset($_SESSION['admin_id_num'])){
						if($_SESSION['admin_id_num']==-1337 && isset($_SESSION["is_admin"])){
						?>
							<admin-secret-menu></admin-secret-menu>
						<?
						}
					}
					?>
					<paper-progress value="100" style="width:90%; margin-left:5%; margin-right: 5%;"></paper-progress>
					<admin-sidebar page="<? echo $_SESSION['page']; ?>"></admin-sidebar>
				<?
				} else if($_SESSION['pagetype'] != "takequiz") {  
				?>
					<admin-login></admin-login>
				<?
				}
				?>
			</paper-header-panel>
			<paper-scroll-header-panel main class="flex" id="scrollHeader" scrollAwayTopbar>
				<paper-toolbar>
					<paper-icon-button icon="menu" paper-drawer-toggle></paper-icon-button>
					<span class="title">PolyQuiz 3.0</span>
					<paper-button on-click="goHome" class="smallbuttons"><iron-icon icon="icons:home"></iron-icon><span class="not-small"> Home</span></paper-button>
					<paper-button on-click="goTakeAQuiz" class="smallbuttons"><iron-icon icon="icons:content-paste"></iron-icon><span class="not-small"> Take A Quiz</span></paper-button>
					<paper-button on-click="goLogin" class="smallbuttons"><iron-icon icon="icons:settings"></iron-icon><span class="not-small"> Manage</span></paper-button>
				</paper-toolbar>
				<content id="globalMainContainer">
				
				</content>
			</paper-scroll-header-panel>
		</paper-drawer-panel>
		<paper-fab icon="home"></paper-fab>
	</template>
	<script>
	Polymer({
		is: "polyquiz-page",
		listeners: {
			"toastEvent": "fireToast"
		},
		properties: {
			sidebarpage: {
				type: Number,
				value: 0
			}
		},
		goHome: function(){
			window.location = "?p=home";
		},
		goTakeAQuiz: function(){
			window.location = "?p=takequiz";
		},
		goLogin: function(){
			window.location = "?p=admin";
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
			this.$.mainPanel.responsiveWidth = "980px";
			//this.$.mainPanel.forceNarrow = true;
			this.fire('iron-signal', {name: 'hideshowevent', data: { show: this.$.mainPanel.narrow } });
			this.$.sidebarMenu.select(this.sidebarpage);
			if(this.$.mainPanel.narrow){
			//	this.$.scrollHeader.scrollAwayTopbar = true;
			} else {
			//	this.$.scrollHeader.fixed = true;
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