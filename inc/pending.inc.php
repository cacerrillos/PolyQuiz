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
					include_once("func/pending.func.php");
				?>
				<?
				}
				?>
			</paper-material>
		</div>
	</div>
</div>