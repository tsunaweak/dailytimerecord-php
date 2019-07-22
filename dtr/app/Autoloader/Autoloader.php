<?php
if(!spl_autoload_register(function($className){
	$indexFolder = 'dtr/app';
	$root = $_SERVER['DOCUMENT_ROOT'];
	$views = $root . DIRECTORY_SEPARATOR . $indexFolder . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR; 
	$controllers = $root . DIRECTORY_SEPARATOR . $indexFolder . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR;
	$models = $root . DIRECTORY_SEPARATOR . $indexFolder . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR;
	$core = $root . DIRECTORY_SEPARATOR . $indexFolder . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR;
	$phpexcel = $root . DIRECTORY_SEPARATOR . $indexFolder . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR;
	$directory = [$views, $controllers, $models, $core, $phpexcel];	
	foreach ($directory as $folder) {
	$fileName = $folder . $className . '.php';
	if(!file_exists($fileName)){
		continue;
	}
	require_once $fileName;
	}
})){
	echo "Autoloader Failed to load.";
}
?>