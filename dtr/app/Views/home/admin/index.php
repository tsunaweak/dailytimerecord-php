<!DOCTYPE html>
<html>
<head>
	<title>Daily Time Record</title>
	<meta charset="utf-8">
	<?php include 'links/admin_links.php'; ?>
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
					<a class="nav-link font-weight-bold" href="../" title="Back to main page">Back</a>
				</li>
			</ul>	
		</div>
	</nav>
	<div class="container pt-5 mt-5">
		<form id="loginForm">
			<div class="card mx-auto" style="max-width: 25rem;">
				<div class="card-header">
					<span class="h2">Login</span>
				</div>		
				<div class="card-body">
					<span id="message"></span>
					<div class="form-group">
						<label for="username">Enter Username</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="username" id="username" placeholder="Username" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="password">Enter Password</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
							</div>
							<input type="password" name="password" id="password" placeholder="Password" class="form-control" autocomplete="false">
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button title="Login Button" type="submit" class="btn btn-primary float-right" id="btn_login">Login</button>
				</div>
			</div>
		</form>
	</div>
</body>
</html>

<script type="text/javascript" src="../js/admin/login.js"></script>