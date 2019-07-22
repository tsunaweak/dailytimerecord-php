<?php  

class Core 
{
	public function date2sec($date){
		date_default_timezone_set('Asia/Manila');
		return strtotime($this->cleanString($date));
	}
	public function sec2date($sec){
		$timestamp = new DateTime("@$sec");
	    $timestamp->setTimezone(new DateTimeZone('Asia/Manila'));
	    return $timestamp->format('M d, Y h:i A');
	}
	public function sectodate($sec){
		$timestamp = new DateTime("@$sec");
	    $timestamp->setTimezone(new DateTimeZone('Asia/Manila'));
	    return $timestamp->format('F d, Y h:i:s A');	
	}
	public function subNum($num, $num2){
		if($num >= $num2){
			 return  $num - $num2;	
		}else{
			return  $num2 - $num;
		}
	}
	public function checkTimeStamp($stamp){
		if(strtotime($stamp)){
			return true;
		}else{
			return false;
		}
	}
	public function getTimestamp(){
		date_default_timezone_set('Asia/Manila');
		$date = Date('F d, Y h:i:s A');
		return strtotime($date);
	}
	public function outputTable($rows, $filtered, $output){
		$data = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $rows,
			"recordsFiltered" => $filtered,
			"data" => $output
		);
		echo json_encode($data);
	}
	public function errorMessage($error, $message){
		$this->conn = null;
		$data = array(
			'message' => $message,
			'error'   => $error
		);
		echo json_encode($data);
	}
	public function dangerMessage($message){
		return '<div class="alert alert-danger">'.$message.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}
	public function successMessage($message){
		return '<div class="alert alert-success">'.$message.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';	
	}
	public function checkNum($num){
		if(preg_match('/^[0-9][0-9]*$/', $num)){
			return true;			
		}else{
			return false;
		}
	}
	public function isNum($num){
		if(preg_match('/^[1-9][0-9]*$/', $num)){
			return true;			
		}else{
			return false;
		}
	}
	public function hrs2sec($hrs){
		$this->cleanString($hrs);
		return $hrs * 3600;
	}
	public function sec2hrs($sec){
		$time = round($sec);
    	return sprintf('%02d:%02d:%02d', ($time/3600),($time/60%60), $time%60);
	}
	public function isName($string){
		if(preg_match("/^[a-zA-Z\040]+$/", $string)){
			return true;
		}else{
			return false;
		}
	}
	public function hashString($string){
		$string = $this->cleanString($string);
		$hash = substr(hash('sha256', hash('SHA256', $string)), 0, 16);
		return substr(openssl_encrypt($hash, 'AES-256-CBC', $string, 0, $hash), 0, 32);

	}
	public function cleanString($string){
		return trim(stripcslashes(htmlspecialchars($string)));
	}
	public function encryptString($string){
		$string = $this->cleanString($string);
		$output = '';
		$method = "AES-256-CBC";
		$sk = 'eaiYYkYTysia2lnHiw0N0vx7t7a3kEJVLfbTKoQIx5o=';
		$siv = 'eaiYYkYTysia2lnHiw0N0';
		$key = hash('sha256', $sk);
		$init_iv = substr(hash('sha256', $siv), 0, 16);
		$output = openssl_encrypt($string, $method, $key, 0, $init_iv);
		return $output = base64_encode($output);
	}
	public function decryptString($string){
		$string = $this->cleanString($string);
		$output = '';
		$method = "AES-256-CBC";
		$sk = 'eaiYYkYTysia2lnHiw0N0vx7t7a3kEJVLfbTKoQIx5o=';
		$siv = 'eaiYYkYTysia2lnHiw0N0';
		$key = hash('sha256', $sk);
		$init_iv = substr(hash('sha256', $siv), 0, 16);
		return $output = openssl_decrypt(base64_decode($string), $method, $key, 0, $init_iv);	
	}
}

?>