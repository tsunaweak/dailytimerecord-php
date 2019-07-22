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
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="<?php echo $control->others_page; ?>">Others</a>
				</li>
				<li class="nav-item active border border-secondary rounded">
					<a class="nav-link font-weight-bold" href="#">Events</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link font-weight-bold" href="<?php echo $control->main_page; ?>">Trainee</a>
				</li>
				<li class="nav-item active rounded">
					<a class="nav-link font-weight-bold"  href="<?php echo $control->logout_page; ?>" title="Logout"><i class="fas fa-power-off text-danger"></i></a>
				</li>
			</ul>	
		</div>
	</nav>
	<div class="container mt-5" id="event_container">
		<div class="card mx-auto">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<span class="font-weight-bold h2">Event List</span>
					<button class="btn btn-primary" id="btn_add_event">Add Event</button>
				</div>
			</div>
			<div class="card-body">
				<span id="event_table_message"></span>
				<table class="table table-sm table-bordered dt-responsive nowrap" id="event_table">
					<thead>
						<tr>
							<th>Event Start</th>
							<th>Event End</th>
							<th>Event Description</th>
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
require 'modal/add_event_modal.html';
?>
<script type="text/javascript" src="../js/admin/event.js"></script>