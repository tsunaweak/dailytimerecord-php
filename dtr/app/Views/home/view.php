<!DOCTYPE html>
<html>
<head>
	<title>Daily Time Record</title>
	<meta charset="utf-8">
	<?php require 'links/links.php'; ?>
</head>
<body>
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		<a class="navbar-brand font-weight-bold" href="#">Daily Time Record</a>
	  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarLinks">
			<ul class="nav navbar-nav ml-auto">
				<div class="form-group mt-auto mb-auto">
					<div class="input-group">
						<div class="input-group-prepend">
							<button class="btn btn-primary" id="btn-view">View</button>
						</div>
						<input type="text" name="username" id="username" placeholder="Username" class="form-control">
					</div>
				</div>
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="./">Back</a>
				</li>
			</ul>	
		</div>
	</nav>
	<div class="container mt-5">
		<div id="view_result"></div>
	</div>
</body>
</html>
<script type="text/javascript" src="js/view.js"></script>