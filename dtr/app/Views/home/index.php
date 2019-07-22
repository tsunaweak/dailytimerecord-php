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
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="admin/">Admin</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="view/">View</a>
				</li>
			</ul>	
		</div>
	</nav>
	<div id="margin"></div>	
	<div class="container mt-5">
		<div class="container">
			<div class="card mx-auto" style="max-width: 45rem;">
				<div class="card-header">
					<span class="h5" id="dateNow">Month 00, 0000 00:00:00 PM</span>
				</div>
				<div class="card-body">
					<span id="indexMessage"></span>
					<div class="input-group mb-3 mt-3">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name="username" id="username"class="form-control" placeholder="Enter your username.">
						<div class="input-group-append">
							<button class="btn btn-primary" id="check" name="check">Check</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container mt-3 mb-3" id="event_container">
			<div class="card mx-auto" style="max-width: 55rem;">
				<div class="card-header">
					<span class="font-weight-bold h2">Event List</span>
				</div>
				<div class="card-body" style="max-height: 220px; overflow: auto;">
					<div class="table-responsive" style="min-width: 540px;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Event Start</th>
									<th>Event End</th>
									<th>Event Description</th>
								</tr>
							</thead>
							<tbody id="table_body">
											
							</tbody>	
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript" src="js/index.js"></script>