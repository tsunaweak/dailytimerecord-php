<?php 

class Views extends Router{
	public function render(){
		$root = '/dtr/public/';
		$this->route($root, function(){
			require_once  'home/index.php';
		});
		$this->route($root .'index.php', function(){
			require_once 'home/index.php';
		});
		$this->route($root .'/index.html', function(){
			require_once 'home/index.php';
		});
		$this->route($root .'view', function(){
			require_once 'home/view.php';
		});
		//admin index
		$this->route($root . 'admin/', function(){
			require_once 'home/admin/index.php';
		});
		$this->route($root .'admin.php', function(){
			require_once 'home/admin/index.php';
		});
		$this->route($root. 'admin/index.php', function(){
			require_once 'home/admin/index.php';
		});
		$this->route($root .'admin/index.html', function(){
			require_once 'home/admin/index.php';
		});
		//main index
		$this->route($root .'admin/main', function(){
			require_once 'home/admin/main.php';
		});
		$this->route($root .'admin/main/index.php', function(){
			require_once 'home/admin/main.php';
		});
		$this->route($root .'admin/main/index.html', function(){
			require_once 'home/admin/main.php';
		});
		//event index
		$this->route($root .'admin/event', function(){
			require_once 'home/admin/event.php';
		});
		$this->route($root .'admin/event/index.php', function(){
			require_once $views.'/home/admin/event.php';
		});
		$this->route($root .'admin/event/index.html', function(){
			require_once 'home/admin/event.php';
		});
		//trainee index
		$this->route($root .'admin/trainee', function(){
			require_once 'home/admin/trainee.php';
		});
		$this->route($root .'admin/trainee/index.php', function(){
			require_once 'home/admin/trainee.php';
		});
		$this->route($root .'admin/trainee/index.html', function(){
			require_once 'home/admin/trainee.php';
		});
		//others index
		$this->route($root .'admin/others', function(){
			require_once 'home/admin/others.php';
		});
		$this->route($root .'admin/others/index.php', function(){
			require_once 'home/admin/others.php';
		});
		$this->route($root .'admin/others/index.html', function(){
			require_once  '/home/admin/others.php';
		});
		//404 page
		$this->route($root .'404', function(){
			require_once '404/404.php';
		});
		//logout
		$this->route($root .'admin/logout', function(){
			$this->logout();
		});
		//ajax login
		$this->route($root .'admin/login', function(){
			$this->actionRoute();
		});

		/*---TRAINEE---*/
		//add trainee
		$this->route($root .'admin/save/trainee', function(){
			$this->actionRoute();
		});
		//display trainee
		$this->route($root .'admin/display/trainee', function(){
			$this->actionRoute();
		});
		//delete triainee
		$this->route($root .'admin/delete/trainee', function(){
			$this->actionRoute();
		});
		//get trainee
		$this->route($root .'admin/get/trainee', function(){
			$this->actionRoute();
		});
		//update trainee
		$this->route($root .'admin/update/trainee', function(){
			$this->actionRoute();
		});
		//get trainee recods
		$this->route($root .'admin/get/records', function(){
			$this->actionRoute();
		});
		//delete trainee recods
		$this->route($root .'admin/delete/records', function(){
			$this->actionRoute();
		});
		//get trainee record to update
		$this->route($root .'admin/get/recorddata', function(){
			$this->actionRoute();
		});
		//get trainee record to update
		$this->route($root .'admin/update/recorddata', function(){
			$this->actionRoute();
		});
		/*---END TRAINEE---*/
		
		/*---EVENT---*/
		//add event
		$this->route($root .'admin/save/event', function(){
			$this->actionRoute();
		});
		//display event
		$this->route($root .'admin/display/event', function(){
			$this->actionRoute();
		});
		//delete event
		$this->route($root .'admin/delete/event', function(){
			$this->actionRoute();
		});
		//get event
		$this->route($root .'admin/get/event', function(){
			$this->actionRoute();
		});
		//update event
		$this->route($root .'admin/update/event', function(){
			$this->actionRoute();
		});
		/*--- END EVENT --- */

		/*--- OTHERS --- */
		//get others
		$this->route($root .'admin/get/others', function(){
			$this->actionRoute();
		});
		$this->route($root .'admin/update/deduct', function(){
			$this->actionRoute();
		});
		$this->route($root .'admin/update/afterlogin', function(){
			$this->actionRoute();
		});
		$this->route($root .'admin/update/afterlogout', function(){
			$this->actionRoute();
		});
		$this->route($root .'admin/account', function(){
			$this->actionRoute();
		});
		$this->route($root .'admin/solocheck', function(){
			$this->actionRoute();
		});
		$this->route($root .'admin/upload/datasheet', function(){
			$this->actionRoute();
		});
		/*--- END OTHERS --- */
		

		/*---INDEX---*/
		$this->route($root .'get/date', function(){
			$this->actionRoute();
		});
		$this->route($root .'get/eventlist', function(){
			$this->actionRoute();
		});
		$this->route($root .'action/check', function(){
			$this->actionRoute();
		});
		$this->route($root .'view/records', function(){
			$this->actionRoute();
		});
		$this->route($root. 'export/excel', function(){
			$this->actionRoute();
		});
		/*---END INDEX---*/
		$action = $_SERVER['REQUEST_URI'];
		//use to redirect the invalid directory to valid directory	
		if($action == "/dtr_v2/public/admin"){
			header('Location:/dtr_v2/public/admin/');
		}else if($action == "/dtr_v2/public/admin/event/"){
			header('Location:'.$root.'admin/event');
		}else if($action == "/dtr_v2/public/admin/main/"){
			header('Location:'.$root.'admin/main');
		}else if($action == "/dtr_v2/public/admin/others/"){
			header('Location:'.$root.'admin/others');
		}else if($action == "/dtr_v2/public/admin/logout/"){
			header('Location:'.$root.'admin/logout');
		}else if($action == "/dtr_v2/public/view/"){
			header('Location:/dtr_v2/public/view');
		}
		$this->dispatch($action);
	}
}

?>