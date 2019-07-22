<?php 
class Router extends MainController{
	public $routes = [];
	public function route($action, $callback){
		$action = trim($action, '/');
		$this->routes[$action] = $callback;
	}
	public function dispatch($action){
		$action = trim($action, '/');
		$callback = (isset($this->routes[$action])) ? $this->routes[$action] : function() { require '../app/Views/404/404.php'; };
		call_user_func($callback);
	}
}
?>