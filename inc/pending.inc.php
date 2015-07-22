<div class="container">
	<div class="row">
		<div class="12u">
			<paper-material>
				<h3>Quizzes In Progress</h3>
			</paper-material>
		</div>
		<div class="12u">
			<paper-material>
				<?
				if(isset($_SESSION["is_admin"])){
				?>
				<p>
				<script src="js/ajaxpending.js"></script>
					<script type="text/javascript">refreshdiv2();</script>
				<div id="timediv2" style="margin-left:20px">
				
				</div>
				<?
				}
				?>
			</paper-material>
		</div>
	</div>
</div>