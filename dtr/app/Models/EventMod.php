<?php  

class EventMod extends Core{
	public function addEventMod($start, $end, $type, $add){
		$id = $this->generateID('E', 'tbl_event', 'event_id');
		$q = "INSERT INTO tbl_event(event_id, start_time, end_time, isdouble, add_time) VALUES('".$id."', '".$start."', '".$end."', '".$type."', '".$add."')";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			return true;
		}else{
			return false;
		}
	}
	public function displayEventMod(){
		$output = array();
		$result = array();
		$query = '';
		$availFilter = array('start_time', 'end_time');
		$query .= "SELECT * FROM tbl_event ";
		if(isset($_POST["order"])){
			$query .= 'ORDER BY '.$availFilter[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' '; 
		}else{
			$query .= 'ORDER by event_id DESC ';
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
			$sub_array[] = $this->sec2date($row['start_time']);
			$sub_array[] = $this->sec2date($row['end_time']);
			if($row['isdouble'] == 1){
				$description = "Double Time";
			}else{
				$description = "Additional " . $row['add_time'] / 3600 . " Hours";
			}
			$sub_array[]  = $description;
			$sub_array[] = '<button class="btn btn-success btn-sm edit" id='.$this->encryptString($row['event_id']).'><i class="fas fa-edit"></i></button>';
			$sub_array[] = '<button class="btn btn-danger btn-sm delete" id='.$this->encryptString($row['event_id']).'><i class="fas fa-trash"></i></button>';
			$output[] = $sub_array;
		}
		$result[] = $filteredRows;
		$result[] = $this->countRows('event_id', 'tbl_event');
		$result[] = $output;
		return $result;
	}
	public function getEventMods($id){	
		$data = array();
		$q = "SELECT * FROM tbl_event WHERE event_id = '".$id."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			if($s->rowCount() > 0){
				$result = $s->fetchAll();
				$data['event_id'] = $result[0]['event_id'];
				$data['start_time'] = $result[0]['start_time'];
				$data['end_time'] = $result[0]['end_time'];
				$data['isdouble'] = $result[0]['isdouble'];
				$data['add_time'] = $result[0]['add_time'];
				return $data;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function updateEventMod($start, $end, $type, $add, $id){
		$q = "UPDATE tbl_event SET start_time = '".$start."', end_time = '".$end."', isdouble = '".$type."', add_time = '".$add."' WHERE event_id = '".$id."'";
		$s = $this->conn->prepare($q);
		if($s->execute()){
			return true;
		}else{
			return false;
		}
	}

}

?>
