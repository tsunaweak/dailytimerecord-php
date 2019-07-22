<?php 
class TraineeMod extends EventMod
{
	public function addTraineeMod($trainee_id, $uname, $fname, $nhrs){
		$q1 = "INSERT INTO tbl_trainee(trainee_id, fname, total_time, rem_time, ren_time) VALUES('".$trainee_id."', '".$fname."', '".$nhrs."', '".$nhrs."', '0')";
		$s1 = $this->conn->prepare($q1);
		if($s1->execute()){
			$q2 = "INSERT INTO tbl_accounts(uname, pword, role, trainee_id) VALUES('".$uname."', 'none', 'Trainee', '".$trainee_id."')";
			$s2 = $this->conn->prepare($q2);
			if($s2->execute()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}		
	}
	public function updateRecordDataMod($id, $start_time, $end_time){
		$records = $this->getRecordsDataMod($id);
		$others = $this->getOthersMod();
		$old_spent_time = $records[2];
		$q = "SELECT isdouble, add_time FROM tbl_event WHERE start_time <= '".$start_time."' AND end_time >= '".$end_time."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			//check if have an event
			if($s->rowCount() > 0){
				//has event
				$d = $s->fetchAll();
				$event_type = $d[0]['isdouble'];
				if($event_type == 1){
					$spent_time = ($this->subNum($start_time, $end_time)) * 2;
				}else{
					$spent_time = ($this->subNum($start_time, $end_time)) + $d1[0]['add_time'];
				}
				$hasEvent = 'true';
			}else{
				//no event
				$spent_time = $this->subNum($start_time, $end_time);
				$hasEvent = 'false';
			}
			$new_spent_time = $this->subNum($spent_time, $others[0]['deduct_time']);
			$trainee_id = $this->rid2tid($id);
			$q1 = "SELECT rem_time, ren_time FROM tbl_trainee WHERE trainee_id = '".$trainee_id."'";
			$s1 = $this->conn->prepare($q1);
			if($s1->execute()){
				$d1 = $s1->fetchAll();
				$rem_time = $d1[0]['rem_time'];
				$ren_time = $d1[0]['ren_time'];
				if($old_spent_time > $new_spent_time){
					$rem_time = $rem_time + ($old_spent_time - $new_spent_time);
					$ren_time = $ren_time - ($old_spent_time - $new_spent_time);
				}else{
					$rem_time = $rem_time - ($new_spent_time - $old_spent_time);
					$ren_time = $ren_time + ($new_spent_time - $old_spent_time);
				}
				$q2 = "UPDATE tbl_trainee SET rem_time = '".$rem_time."', ren_time = '".$ren_time."' WHERE trainee_id = '".$trainee_id."'";
				$s2 = $this->conn->prepare($q2);
				if($s2->execute()){
					$q3 = "UPDATE tbl_records SET start_time = '".$start_time."', end_time ='".$end_time."', spent_time = '".$new_spent_time."' WHERE records_id = '".$id."'";
					$s3 = $this->conn->prepare($q3);
					if($s3->execute()){
						$this->message = $this->successMessage('Record successfully updated.');
						return true;
					}else{	
						$this->error = $this->dangerMessage('Error found on third query');
						return false;
					}
				}else{
					$this->error = $this->dangerMessage('Error found on second query');
					return false;
				}
			}else{
				$this->error = $this->dangerMessage('Error found on first query');
				return false;
			}
		}else{
			$this->error = $this->dangerMessage('Error found on query.');
			return false;
		}	
	}
	public function getRecordsDataMod($id){
		$q = "SELECT start_time, end_time, spent_time FROM tbl_records WHERE records_id = '".$id."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			$d = $s->fetchAll();
			$start_time = $d[0]['start_time'];
			$end_time = $d[0]['end_time'];
			$spent_time = $d[0]['spent_time'];
			return [$start_time, $end_time, $spent_time];
		}else{
			$this->error = $this->dangerMessage('Error found on query');
			return false;
		}
	}
	public function getTraineeRecordsMod($id){
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
					$table .= '<td>' . $this->sec2date($row['start_time']) . '</td>';
					if($row['end_time'] == '0'){
						$table .= '<td>None</td>';
						$table .= '<td>None</td>';
						$table .= '<td>None</td>';
					}else{
						$table .= '<td>' . $this->sec2date($row['end_time']) . '</td>';
						$table .= '<td>' . $this->sec2hrs($row['spent_time']) . '</td>';
						$table .= '<td><button class="btn btn-success btn-sm record_update" id="'.$this->encryptString($row['records_id']).'"><i class="fas fa-edit"></i></button></td>';
					}
					$table .= '<td><button class="btn btn-danger btn-sm record_delete" id="'.$this->encryptString($row['records_id']).'"><i class="fas fa-trash"></i></button></td>';
					$table .= '</tr>';		
				}
				return [$fname, $rem_time, $ren_time, $table];
			}else{
				$this->error = $this->dangerMessage('No records found.');
				return false;
			}
		}else{
			return false;
		}
	}
	public function displayTraineeMod(){
		$output = array();
		$result = array();
		$query = '';
		$availFilter = array('uname', 'fname', 'rem_time', 'ren_time');
		$query .= "SELECT tbl_trainee.trainee_id, fname, uname, rem_time, ren_time FROM tbl_trainee INNER JOIN tbl_accounts ON tbl_trainee.trainee_id = tbl_accounts.trainee_id ";
		if(isset($_POST["search"]["value"])){
            $query .= 'WHERE uname LIKE "%'. $_POST["search"]["value"] . '%" ';
        }
		if(isset($_POST["order"])){
			$query .= 'ORDER BY '.$availFilter[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' '; 
		}else{
			$query .= 'ORDER by tbl_trainee.trainee_id DESC ';
		}
		if($_POST["length"] != -1){
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
		$statement = $this->conn->prepare($query);
		$statement->execute();
		$data = $statement->fetchAll();
		$filteredRows = $statement->rowCount();
		foreach ($data as $row) {
			$sub_array = array();
			$sub_array[] = $this->cleanString($row['fname']);
			$sub_array[] = $this->cleanString($row['uname']);
			$sub_array[] = $this->sec2hrs($row['rem_time']);
			$sub_array[] = $this->sec2hrs($row['ren_time']);		
			$sub_array[] = '<button class="btn btn-primary btn-sm view" id='.$this->encryptString($row['trainee_id']).'><i class="fas fa-eye"></i></button>';
			$sub_array[] = '<button class="btn btn-success btn-sm edit" id='.$this->encryptString($row['trainee_id']).'><i class="fas fa-edit"></i></button>';
			$sub_array[] = '<button class="btn btn-danger btn-sm delete" id='.$this->encryptString($row['trainee_id']).'><i class="fas fa-trash"></i></button>';
			$output[] = $sub_array;	
		}
		$result[] = $filteredRows;
		$result[] = $this->countRows('trainee_id', 'tbl_trainee');
		$result[] = $output;
		return $result;
	}
	public function getTraineeMod($trainee_id){
		$query = "SELECT fname, uname, total_time FROM tbl_trainee INNER JOIN tbl_accounts ON tbl_trainee.trainee_id = tbl_accounts.trainee_id WHERE tbl_trainee.trainee_id = '".$trainee_id."'";
		$statement = $this->conn->prepare($query);
		$statement->execute();
		$data = $statement->fetchAll();
		return $data;	
	}
	public function updateTraineeMod($fname, $uname, $nhrs, $type, $id){
		$rem = 0;
		$q = "SELECT fname, uname, total_time, rem_time FROM tbl_trainee INNER JOIN tbl_accounts ON tbl_trainee.trainee_id = tbl_accounts.trainee_id WHERE tbl_trainee.trainee_id = '".$id."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			$d = $s->fetchAll();
			if($type == "add"){
				$rem = $nhrs + $d[0]['rem_time'];
				$nhrs = $nhrs + $d[0]['total_time'];
			}else{
				if($nhrs > $d[0]['rem_time']){
					return false;
				}else{
					$rem = $this->subNum($nhrs, $d[0]['rem_time']);	
					$nhrs = $this->subNum($nhrs, $d[0]['total_time']);
				}
			}
		}else{
			return false;
		}
		$q1 = "UPDATE tbl_trainee SET fname = '".$fname."', total_time = '".$nhrs."', rem_time = '".$rem."' WHERE trainee_id = '".$id."'";
		$s1 = $this->conn->prepare($q1);
		if($s1->execute()){
			$q2 = "UPDATE tbl_accounts SET uname = '".$uname."' WHERE trainee_id = '".$id."'";
			$s2 = $this->conn->prepare($q2);
			if($s2->execute()){
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