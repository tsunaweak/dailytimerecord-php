<?php 
$control = new MainController;
$control->setPath();
$control->check();
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
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="<?php echo $control->others_page; ?>">Others</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="<?php echo $control->event_page; ?>">Events</a>
				</li>
				<li class="nav-item active border border-secondary rounded">
					<a class="nav-link font-weight-bold" href="#">Trainee</a>
				</li>
				<li class="nav-item active rounded">
					<a class="nav-link font-weight-bold" title="Logout" id="logout" href="<?php echo $control->logout_page; ?>"><i class="fas fa-power-off text-danger"></i></a>
				</li>
			</ul>	
		</div>
	</nav>
	<div class="container mt-5" id="event_container">
		<div class="card mx-auto">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<span class="font-weight-bold h2">Trainee List</span>
					<button class="btn btn-primary" data-toggle="modal"  id="btn_add_trainee">Add Trainee</button>
				</div>
			</div>
			<div class="card-body">
				<span id="table_message"></span>
				<table class="table table-sm table-bordered dt-responsive nowrap" id="trainee_table">
					<thead>
						<tr>
							<th>Fullname</th>
							<th>Username</th>
							<th>Remaining Time</th>
							<th>Rendered Time</th>
							<th>View</th>
							<th>Update</th>
							<th>Delete</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
require 'modal/pop_modal.html'; 
require 'modal/records_modal.html'; 
require 'modal/add_trainee_modal.html';
require 'modal/edit_trainee_modal.html';
?>
<script type="text/javascript" src="../js/admin/main.js"></script>