<?php 
$control = new MainController;
$control->check();
$control->setPath();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Daily Time Record</title>
	<meta charset="utf-8">
	<?php require 'links/admin_links.php'; ?>
</head>
<body>
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		<a class="navbar-brand font-weight-bold" href="#">Daily Time Record</a>
	  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarLinks">
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item active border border-secondary rounded">
					<a class="nav-link font-weight-bold" href="#">Others</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="<?php echo $control->event_page; ?>">Events</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="<?php echo $control->main_page; ?>">Trainee</a>
				</li>
				<li class="nav-item active rounded">
					<a class="nav-link font-weight-bold" href="<?php echo $control->logout_page; ?>" title="Logout"><i class="fas fa-power-off text-danger"></i></a>
				</li>
			</ul>	
		</div>
	</nav>
	<div class="container">
		<div class="card mt-5">
			<div class="card-header">
				<span class="h2">Other Options</span>
				<button class="btn btn-primary float-right" id="show_modal">Change Credentials</button>
			</div>
			<div class="card-body">
				<span id="checkMessage"></span>
				<div class="row">
					<div class="col-sm">
						<div class="card">
							<div class="card-header">
								 <span class="h4">Solo Insert</span>
							</div>
							<div class="card-body">
								<div class="form-group">
									<div class="form-group">
										<label for="solouser">Enter Username</label>
										<div class="input-group">
											<div class="input-group-prepend">
												 <span class="input-group-text"><i class="fas fa-user"></i></span>
											</div>
											<input type="text" name="solouser" placeholder="Username" class="form-control" id="solouser">
										</div>
									</div>
									<div class="form-group">
										<label>Enter Timestamp</label>
										<div class="input-group">
											<div class="input-group-prepend">
												 <span class="input-group-text"><i class="fas fa-clock"></i></span>
											</div>
											<input type="text" name="solostart" class="form-control" id="solostart" placeholder="Timestamp">
											<div class="input-group-append">
												<button class="btn btn-success" id="btn-check">Check</button>
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="card">
							<div class="card-header">
								 <span class="h4">Bulk Insert</span>
							</div>
							<div class="card-body">
								<div class="form-group">
									<div class="form-group">
										<label for="loginUser">Enter Username</label>
										<div class="input-group">
											<div class="input-group-prepend">
												 <span class="input-group-text"><i class="fas fa-user"></i></span>
											</div>
											<input type="text" name="bulkUname" placeholder="Username" class="form-control" id="bulkUname">
											<div class="input-group-append">
												<button class="float-right btn btn-success" id="btn_bulk">Upload</button>		
											</div>
										</div>
									</div>
									<div class="form-group">
										<label>Upload Excel File</label>
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="datasheet" name="datasheet">
											<label class="custom-file-label" for="datasheet">Choose file</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card mt-3">
					<div class="card-header">
						<span class="h4">Website Configuration</span>
					</div>
					<div class="card-body">
						<span id="configMessage"></span>
						<div class="row">
							<div class="col-sm">
								 <div class="form-group">
									<label>Time Deduction</label>
									<div class="input-group">
										<input type="text" name="timeDeduct" id="timeDeduct" class="form-control" placeholder="Hours" title="Set deduction time in hours" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" autocomplete="off">
										<div class="input-group-append">
											<button class="btn btn-success" id="btntimeDeduct">Save</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm">
								<div class="form-group">
									<label>Time After Login</label>
									<div class="input-group">
										<input type="text" name="afterLogin" id="afterLogin" class="form-control" placeholder="Hours" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" title="Set time interval to logout after login" autocomplete="off">
										<div class="input-group-append">
											<button class="btn btn-success" id="btnafterLogin">Save</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm">
								<label>Time After Logout</label>
								<div class="input-group">
									<input type="text" name="afterLogout" id="afterLogout" class="form-control" placeholder="Hours" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" title="Set time interval to login afater logout" autocomplete="off">
									<div class="input-group-append">
										<button class="btn btn-success" id="btnafterLogout">Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
require 'modal/account_modal.html'; 
?>
<script type="text/javascript" src="../js/admin/others.js"></script>