<?php  
class Models extends TraineeMod{	
	public $db_uname;
	public $db_pword;
	public $db_host;
	public $db_name;
	public $conn;
	public function __construct(){
		$this->db_uname = "/* Your database username */";
		$this->db_pword = "/* Your database password */";
		$this->db_host = "localhost";
		$this->db_name = "dtr";
		try {
		    $this->conn = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_uname, $this->db_pword);
		    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			if(strstr($e->getMessage(), "[1045]")){
				echo "Incorrect Database Username or Password";
			}else if(strstr($e->getMessage(), "[1049]")){
				echo "Database Not Found.";
			}else if(strstr($e->getMessage(), "[2002]")){
				echo "Database Server Timeout.";
			}else{
				echo $e->getMessage();
			}
		}		
	}
	public function exportExcelMod($id){
		$q = "SELECT fname, total_time, rem_time, ren_time, start_time, end_time, spent_time FROM tbl_records INNER JOIN tbl_trainee ON tbl_records.trainee_id = tbl_trainee.trainee_id WHERE tbl_records.trainee_id = '".$id."'";
		$s  = $this->conn->prepare($q);
		$s->execute();
		return $s->fetchAll();
	}
	public function deleteData($tbl, $id, $id_val){
		$query = 'DELETE FROM '.$tbl.' WHERE '.$id.' = "'.$id_val.'"';
		$statement = $this->conn->prepare($query);
		try{
			$statement->execute();
		}catch(PDOException $e){
			return false;
		}
		return true;
	}
	public function deleteRecordMod($id){
		$q = "SELECT trainee_id, spent_time FROM tbl_records WHERE records_id = '".$id."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			if($s->rowCount() > 0){
				$d = $s->fetchAll();
				$trainee_id = $d[0]['trainee_id'];
				$q1 = "SELECT total_time, rem_time, ren_time FROM tbl_trainee WHERE trainee_id = '".$trainee_id."'";
				$s1 = $this->conn->prepare($q1);
				if($s1->execute()){
					$d1 = $s1->fetchAll();
					$rem_time = $d1[0]['rem_time'] + $d[0]['spent_time'];
					$ren_time = $this->subNum($d[0]['spent_time'], $d1[0]['ren_time']);
					$ren_time = ($rem_time > $d1[0]['total_time']) ? '0' : $ren_time;
					$rem_time = ($rem_time > $d1[0]['total_time']) ? $d1[0]['total_time'] : $rem_time;
					$q2 = "UPDATE tbl_trainee SET rem_time = '".$rem_time."', ren_time = '".$ren_time."' WHERE trainee_id = '".$trainee_id."'";
					$s2 = $this->conn->prepare($q2);
					if($s2->execute()){
						if($this->deleteData('tbl_records', 'records_id', $id)){
							return true;
						}else{
							return false;
						}			
					}else{
						$this->error = $this->dangerMessage('Error found on third query');
						return false;
					}
				}else{
					$this->error = $this->dangerMessage('Error found on first query');
					return false;
				}
			}else{
				$this->error = $this->dangerMessage('No record found to delete.');
				return false;
			}
		}else{
			$this->error = $this->dangerMessage('Error found on query');
			return false;
		}
	}
	public function getEventMod($dateNow){
		$q = "SELECT start_time, end_time, isdouble, add_time FROM tbl_event WHERE end_time >= '".$dateNow."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			$row = $s->rowCount();
			$data = $s->fetchAll();
			return [$row, $data];
		}else{
			return false;
		}
	}
	public function getOthersMod(){
		$query = "SELECT deduct_time, after_login, after_logout FROM tbl_others";
		$statement = $this->conn->prepare($query);
		$statement->execute();
		return $statement->fetchAll();
	}
	public function setOthersMod($id, $value){
		$query = "UPDATE tbl_others SET $id = '".$value."' WHERE id = 1";
		$statement = $this->conn->prepare($query);
		if($statement->execute()){
			return true;
		}else{
			return false;
		}	
	}
	public function setAccountMod($uname, $pword){
		$query = "UPDATE tbl_accounts SET uname = '".$uname."', pword = '".$pword."' WHERE role = 'Administrator'";
		$statement = $this->conn->prepare($query);
		if($statement->execute()){
			return true;
		}else{
			return false;
		}
	}
	public function generateID($initString, $tblName, $colName){
		$idString = $initString . '-00001';
		repeat:
		$query = "SELECT $colName FROM $tblName WHERE $colName = '".$idString."'";
		$statement = $this->conn->prepare($query);
		$statement->execute();
		if($statement->rowCount() > 0){
			$idString++;
			goto repeat;
		}else{
			return $idString;
		}
	}
	public function countRows($id, $tbl){
		$q = "SELECT $id FROM $tbl";
		$s = $this->conn->prepare($q);
		$s->execute(); 
		return $s->rowCount();
	}
	public function hasLogin($id){
		$q = "SELECT records_id FROM tbl_records WHERE trainee_id = '".$id."' AND end_time = '0'";
		$s = $this->conn->prepare($q);
		$s->execute();
		if($s->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}
	public function doLogout($id, $stamp){
		$q = "SELECT records_id, start_time FROM tbl_records WHERE trainee_id = '".$id."' AND end_time = '0'";
		$s = $this->conn->prepare($q);
		$s->execute();
		$data = $s->fetchAll();
		$others = $this->getOthersMod();
		if($data[0]['start_time'] >= $stamp){
			$this->error = $this->dangerMessage('Login Timestamp cannot be greater than Logout Timestamp');
			return false;
		}else{
			$stamptime = $data[0]['start_time'] + $others[0]['after_login'];
			if($stamp >= $stamptime){
				$q1 = "SELECT isdouble, add_time FROM tbl_event WHERE start_time <= '".$data[0]['start_time']."' AND end_time >= '".$stamp."'";
				$s1 = $this->conn->prepare($q1);
				if($s1->execute()){
					//check if have an event
					if($s1->rowCount() > 0){
						//has event
						$d1 = $s1->fetchAll();
						$event_type = $d1[0]['isdouble'];
						//$this->error = $event_type;
						if($event_type == 1){
							$spent_time = ($stamp - $data[0]['start_time']) * 2;
						}else{
							$spent_time = ($stamp - $data[0]['start_time']) + $d1[0]['add_time'];
						}
						$hasEvent = 'true';
					}else{
						//no event
						$spent_time = $stamp - $data[0]['start_time'];
						$hasEvent = 'false';
					}
					$spent_time = $this->subNum($spent_time, $others[0]['deduct_time']);
					//update table records
					$q2 = "UPDATE tbl_records SET end_time = '".$stamp."', spent_time = '".$spent_time."', hasEvent = '".$hasEvent."' WHERE records_id = '".$data[0]['records_id']."'";
					$s2 = $this->conn->prepare($q2);
					if($s2->execute()){
						//get trainee records to update
						$q3 = "SELECT rem_time, ren_time FROM tbl_trainee WHERE trainee_id = '".$id."'";
						$s3 = $this->conn->prepare($q3);
						if($s3->execute()){
							$d3 = $s3->fetchAll();
							$rem_time = ($spent_time >= $d3[0]['rem_time']) ? 0 : $d3[0]['rem_time'] - $spent_time;
							if($spent_time >= $d3[0]['rem_time']){
								$ren_time = $d3[0]['ren_time'] + $d3[0]['rem_time'];
							}else{
								$ren_time = $d3[0]['ren_time'] + $spent_time;	
							}
							$q4 = "UPDATE tbl_trainee SET rem_time = '".$rem_time."', ren_time = '".$ren_time."' WHERE trainee_id = '".$id."'";
							$s4 = $this->conn->prepare($q4);
							if($s4->execute()){
								return true;
							}else{
								$this->error = $this->dangerMessage('Error found on query four.');
								return false;
							}
						}else{
							$this->error = $this->dangerMessage('Error found on query three.');
							return false;	
						}
					}else{
						$this->error = $this->dangerMessage('Error found on query two.');
						return false;				
					}
				}else{
					$this->error = $this->dangerMessage('Error found on query one.');
					return false;
				}
			}else{
				$this->error = $this->dangerMessage('You just login recently, you can logout later.');
				return false;
			}
		}
	}
	public function doLogin($id, $stamp){
		$q = "SELECT end_time FROM tbl_records WHERE trainee_id = '".$id."' AND end_time != '0' ORDER BY records_id DESC";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			if($s->rowCount() > 0){
				$data = $s->fetchAll();
				$end_time = $data[0]['end_time'];
				$others = $this->getOthersMod();
				$stamptime = $data[0]['end_time'] + $others[0]['after_logout'];
				if($stamp >= $stamptime){
					$q = "INSERT INTO tbl_records(start_time, end_time, spent_time, hasEvent, trainee_id) VALUES('".$stamp."', '0', '0', '0', '".$id."')";
					$s = $this->conn->prepare($q);
					if($s->execute()){
						return true;
					}else{
						$this->error = $this->dangerMessage('Error found while trying to login.');
						return false;
					}
				}else{
					$this->error = $this->dangerMessage('You just logout recenly, you can login later.');
					return false;
				}
			}else{
				$q = "INSERT INTO tbl_records(start_time, end_time, spent_time, hasEvent, trainee_id) VALUES('".$stamp."', '0', '0', '0', '".$id."')";
				$s = $this->conn->prepare($q);
				if($s->execute()){
					return true;
				}else{
					return false;
				}
			}
		}else{
			$this->error = $this->dangerMessage('Error found while trying to login.');
			return false;
		}
	}
	public function viewRecordsMod($uname){
		$id = $this->uname2id($uname);
		$q = "SELECT records_id, start_time, end_time, spent_time, hasEvent, fname, rem_time, ren_time FROM tbl_records INNER JOIN tbl_trainee ON tbl_records.trainee_id = tbl_trainee.trainee_id WHERE tbl_records.trainee_id = '".$id."' ORDER BY records_id DESC";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			if($s->rowCount() > 0){
				$data = $s->fetchAll();
				$fname = $data[0]['fname'];
				$rem_time = $this->sec2hrs($data[0]['rem_time']);
				$ren_time = $this->sec2hrs($data[0]['ren_time']);
				$table = '';	
				foreach ($data as $row) {
					if($row['hasEvent'] == 'true'){
						$table .= '<tr style="color:red;">';
					}else{
						$table .= '<tr>';
					}
					if($row['end_time'] == '0'){
						$table .= '<td>' . $this->sec2date($row['start_time']) . '</td>';
						$table .= '<td>None</td>';
						$table .= '<td>None</td>';
					}else{
						$table .= '<td>' . $this->sec2date($row['start_time']) . '</td>';
						$table .= '<td>' . $this->sec2date($row['end_time']) . '</td>';
						$table .= '<td>' . $this->sec2hrs($row['spent_time']) . '</td>';
					}
					$table .= '</tr>';		
				}
				return [$fname, $rem_time, $ren_time, $table, $id];
			}else{
				$this->error = $this->dangerMessage('No records found.');
				return false;
			}
		}else{
			$this->error = $this->dangerMessage('Error found on query one');
			return false;
		}
	}
	//username to trainee id
	public function uname2id($uname){
		$q = "SELECT trainee_id FROM tbl_accounts WHERE uname = '".$uname."' AND role != 'Administrator'";
		$s = $this->conn->prepare($q);
		$s->execute();
		$data = $s->fetchAll();
		return $data[0]['trainee_id'];
	}
	//record id to trainee id
	public function rid2tid($rid){
		$q = "SELECT trainee_id FROM tbl_records WHERE records_id = '".$rid."'";
		$s = $this->conn->prepare($q);
		$s->execute();
		$data = $s->fetchAll();
		return $data[0]['trainee_id'];
	}
	public function checkUser($uname, $id){
		$q1 = "SELECT uname FROM tbl_accounts WHERE uname = '".$uname."' AND trainee_id != '".$id."' AND trainee_id IS NOT NULL";
		$s1 = $this->conn->prepare($q1);
		$s1->execute();
		if($s1->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}
	public function checkTrainee($id){
		$q = "SELECT trainee_id FROM tbl_trainee WHERE trainee_id = '".$id."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			if($s->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function checkUname($uname){
		$q1 = "SELECT uname FROM tbl_accounts WHERE uname = '".$uname."' AND trainee_id IS NOT NULL";
		$s1 = $this->conn->prepare($q1);
		$s1->execute();
		if($s1->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}
	public function modelLogin($username, $password){
		session_start();
		$query = "SELECT id, uname, pword, role FROM tbl_accounts WHERE uname = '".$username."' AND pword = '".$password."' AND role = 'Administrator'";
		$statement = $this->conn->prepare($query);
		if($statement->execute()){
			if($statement->rowCount() > 0){
				$data = $statement->fetchAll();
				$_SESSION['account'] = $this->encryptString($data[0][0]);
				$_SESSION['role'] = $this->encryptString($data[0][3]);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}		
	}
	public function checkLogin($account, $role){
		$query = "SELECT id, role FROM tbl_accounts WHERE id = '".$account."' AND role = '".$role."'";
		$statement = $this->conn->prepare($query);
		if($statement->execute()){
			if($statement->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	
	}
}

?>