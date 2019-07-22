<?php 
class MainController extends TraineeCont
{
	public $message = '';
	public $error = '';
	private $others = '';
	public $root;
	public $others_page;
	public $event_page;
	public $main_page;
	public $logout_page;
 	public function setPath(){
 		$this->root = DIRECTORY_SEPARATOR . 'dtr' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'admin';
 		$this->others_page = $this->root . DIRECTORY_SEPARATOR . 'others';	
 		$this->event_page = $this->root . DIRECTORY_SEPARATOR . 'event';
 		$this->main_page  = $this->root . DIRECTORY_SEPARATOR . 'main';
 		$this->logout_page  = $this->root . DIRECTORY_SEPARATOR . 'logout';
 	}
 	public function actionRoute(){
		if(isset($_POST['action'])){
			if($_POST['action'] == 'login'){
				$this->login();
			}else if($_POST['action'] == 'logout'){
				$this->logout();
			}else if($_POST['action'] == 'add_trainee'){
				$this->addTraineeCont();
			}else if($_POST['action'] == 'display_trainee'){
				$this->displayTraineeCont();
			}else if($_POST['action'] == 'delete_trainee'){
				$this->deleteTraineeCont();
			}else if($_POST['action'] == 'get_trainee'){
				$this->getTraineeCont();
			}else if($_POST['action'] == 'update_trainee'){
				$this->updateTraineeCont();
			}else if($_POST['action'] == 'add_event'){
				$this->addEventCont();
			}else if($_POST['action'] == 'display_event'){
				$this->displayEventCont();
			}else if($_POST['action'] == 'delete_event'){
				$this->deleteEventCont();
			}else if($_POST['action'] == 'get_event'){
				$this->getEventCont();
			}else if($_POST['action'] == 'update_event'){
				$this->updateEventCont();
			}else if($_POST['action'] == 'get_others'){
				$this->getOthersCont();
			}else if($_POST['action'] == 'update_deduct'){
				$this->others = 'deduct_time';
				$this->setOthersCont();
			}else if($_POST['action'] == 'update_afterlogin'){
				$this->others = 'after_login';
				$this->setOthersCont();
			}else if($_POST['action'] == 'update_afterlogout'){
				$this->others = 'after_logout';
				$this->setOthersCont();
			}else if($_POST['action'] == 'account'){
				$this->setAccountCont();
			}else if($_POST['action'] == 'get_date'){
				$this->getDate();
			}else if($_POST['action'] == 'get_eventlist'){
				$this->getEventListCont();
			}else if($_POST['action'] == 'solo_check'){
				$this->soloCheck();
			}else if($_POST['action'] == 'check'){
				$this->setStamp();
			}else if($_POST['action'] == 'get_records'){
				$this->getTraineeRecordsCont();
			}else if($_POST['action'] == 'view'){
				$this->viewRecordsCont();
			}else if($_POST['action'] == 'delete_record'){
				$this->deleteRecordCont();
			}else if($_POST['action'] == 'get_records_data'){
				$this->getRecordsDataCont();
			}else if($_POST['action'] == 'update_record'){
				$this->updateRecordDataCont();
			}else if($this->decryptString($_POST['action']) == 'export_excel'){
				$this->exportExcelCont();
			}else if($_POST['action'] == 'bulk_check'){
				$this->readExcel();
			}
		}else{
			require '../app/Views/404/404.php';
		}
	}
	public function readExcel(){
		$uname = $this->cleanString($_POST['uname']);
		if(!empty($_FILES['file']['name'])){
			$fileName = explode(".", $_FILES['file']['name']);
			$exten = array('xlsx', 'xls');
			if(in_array(end($fileName), $exten)){
				new PHPExcel();
				$excelReader = PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
				$worksheet = $excelReader->getSheet(0);
				$lastRow =  $worksheet->getHighestRow();
				for ($row = 7; $row <= $lastRow; $row++) {
					$login = $worksheet->getCell('A'.$row)->getValue();
					$logout = $worksheet->getCell('B'.$row)->getValue();
					if($login != '' && $logout != ''){
						$this->stampAttendace($uname, $login);
						if($logout != "None"){
							$this->stampAttendace($uname, $logout);
						}
					}
				}
				if($this->error == ''){
					$this->message = $this->successMessage('Excel file successfully uploaded');
				}
			}else{
				$this->error = $this->dangerMessage('Invalid file format.');
			}
		}else{
			$this->error = $this->dangerMessage('Please upload an excel file.');
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function deleteRecordCont(){
		if($_POST['select_id'] == ''){
			$this->error = $this->dangerMessage('Record id cannot be empty');
		}else{
			$id = $this->decryptString($_POST['select_id']);
			if($this->deleteRecordMod($id)){
				$this->message = $this->successMessage('Record successfully deleted.');
			}
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function viewRecordsCont(){
		$output = '';
		if($_POST['uname'] == ''){
			$this->error = $this->dangerMessage('Username cannot be empty');
		}else{
			if($this->checkUname($_POST['uname'])){
				$uname = $this->cleanString($_POST['uname']);
				$data = $this->viewRecordsMod($uname);
				$output = '<form method="POST" action="export/excel"><input type="hidden" value="'.$this->encryptString('export_excel').'" name="action"><input type="hidden" value="'.$this->encryptString($data[4]).'" name="select_id"><div class="modal-content"><div class="modal-header"><div class="row"><div class="col-auto mt-2"><span class="h5">Name: <span class="font-weight-bold">'.$data[0].'</span></span></div><div class="col-auto mt-2"><span class="h5">Remaining Time: <span class="font-weight-bold">'.$data[1].'</span></span></div><div class="col-auto mt-2"><span class="h5">Rendered Time: <span class="font-weight-bold">'.$data[2].'</span></span></div></div><button class="btn btn-primary" type="submit">Export</button></div><div class="modal-body"><div class="table-responsive"><table class="table"><thead><tr><th>Login Timestamp</th><th>Logout Timestamp</th><th>Spent Time</th></tr></thead><tbody>'.$data[3].'</tbody></table></div></div></div></form>';
			}else{
				$this->error = $this->dangerMessage('Username not found.');
			}
		}
		$data = array(
			'error' => $this->error,
			'output' => $output
		);
		echo json_encode($data);
	}
	public function stampAttendace($uname, $stamp){
		if($this->checkTimeStamp($stamp)){
			$stamp = $this->date2sec($stamp);
			if($this->checkUname($uname)){
				$id = $this->uname2id($uname);
				if($this->hasLogin($id, $stamp)){
					if($this->doLogout($id, $stamp)){
						$this->message = $this->successMessage($uname .' has successfully <strong>logout</strong>.');
					}
				}else{
					if($this->doLogin($id, $stamp)){
						$this->message = $this->successMessage($uname .' has successfully <strong>login</strong>.');
					}
				}
			}else{
				$this->error = $this->dangerMessage('Username not found, please try again.');
			}
		}else{
			$this->error = $this->dangerMessage('Login Timestamp is not a valid date.');
		}
	}
	public function soloCheck(){
		if($_POST['uname'] == '' || $_POST['stamp'] == ''){
			$this->error = $this->dangerMessage('Username or Login Timestamp cannot be empty.');
		}else{
			$uname = $this->cleanString($_POST['uname']);
			$stamp = $this->cleanString($_POST['stamp']);
			$this->stampAttendace($uname, $stamp);
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function setStamp(){
		if($_POST['uname'] == ''){
			$this->error = $this->dangerMessage('Username cannot be empty');
		}else{
			$uname = $this->cleanString($_POST['uname']);
			date_default_timezone_set('Asia/Manila');
			$stamp = Date('F d, Y h:i:s A');
			$this->stampAttendace($uname, $stamp);
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function getEventListCont(){
		$dateNow = $this->getTimestamp();
		$result = $this->getEventMod($dateNow);
		$count = $result[0];
		$data = $result[1];
		if($count > 0){
			$table = '';
			foreach ($data as $row) {
				$table .= '<tr>';
				$table .= '<td>' . $this->sec2date($row['start_time']) . '</td>';
				$table .= '<td>' . $this->sec2date($row['end_time']) . '</td>';
				if($row['isdouble'] == 1){
					$table .= '<td>Double Time</td>';
				}else{
				 	$table .= '<td>Additional '  . $row['add_time'] / 3600 . ' Hours</td>';
				}
				$table .= '</tr>';
			}
		}else{
			$table = 0;
		}
		$data = array(
			'row' => $count,
			'table' => $table
		);
		echo json_encode($data);
	}	
	public function getDate(){
		date_default_timezone_set('Asia/Manila');
		$date = Date('F d, Y h:i:s A');
		$data = array(
			'datenow' => $date
		);
		echo json_encode($data);
	}
	public function login(){
		if($_POST['uname'] == '' || $_POST['pword'] == ''){
			$this->error = $this->dangerMessage('Please input credentials.');
		}else{
			$uname = $this->cleanString($_POST['uname']);
			$pword = $this->hashString($_POST['pword']);
			if($this->modelLogin($uname, $pword)){
				$this->message = 'main';
			}else{
				$this->error = $this->dangerMessage('Incorrect username or password.');
			}
		}
		$this->errorMessage($this->error,  $this->message);
	}
	public function getOthersCont(){
		$result = $this->getOthersMod();
		$deductime_time = ($result[0]['deduct_time'] == 0) ? 0 : $result[0]['deduct_time'] / 3600;
		$after_login =   ($result[0]['after_login'] == 0) ? 0 : $result[0]['after_login'] / 3600;
		$after_logout = ($result[0]['after_logout'] == 0) ? 0 : $result[0]['after_logout'] / 3600;
		$data = array(
			'deduct_time' => $deductime_time,
			'after_login'   => $after_login,
			'after_logout' => $after_logout
		);
		echo json_encode($data);
	}
	public function setOthersCont(){
		if($_POST['time'] == ''){
			$this->error = $this->dangerMessage('Field empty, please try again.');
		}else{
			if($this->checkNum($_POST['time'])){
				if($_POST['time'] > 0){
					$time = $_POST['time'] * 3600;
				}else{
					$time = 0;
				}
				if($this->setOthersMod($this->others, $time)){
					$this->message = $this->successMessage('Settings successfully updated.');
				}else{
					$this->error = $this->dangerMessage('Settings failed to update.');
				}
			}else{
				$this->error = $this->dangerMessage('This field only accept numbers.');
			}
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function setAccountCont(){
		if($_POST['pword'] == '' || $_POST['uname'] == ''){
			$this->error = $this->dangerMessage('Username and password cannot be empty.');
		}else{
			$uname = $this->cleanString($_POST['uname']);
			$pword = $this->hashString($_POST['pword']);
			if($this->setAccountMod($uname, $pword)){
				$this->message = $this->successMessage('Account successfully updated.');
			}else{
				$this->error = $this->dangerMessage('Account failed to update.');
			}
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function logout(){
		session_start();
		unset($_SESSION['account']);
		unset($_SESSION['role']);
		session_destroy();
		header('Location:./');
	}
	public function check(){
		session_start();
		$id = $this->decryptString($_SESSION['account']);
		$role = $this->decryptString($_SESSION['role']);
		if(!$this->checkLogin($id, $role)){
			unset($_SESSION['account']);
			unset($_SESSION['role']);
			session_destroy();
			header('Location:./');
		}
	}
}
?>