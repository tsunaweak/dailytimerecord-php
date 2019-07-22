<?php  

class EventCont extends Models
{
	public function addEventCont(){
		if($_POST['start'] == '' || $_POST['end'] == '' || $_POST['type'] == ''){
			$this->error = $this->dangerMessage('Please provided the needed data.');
		}else{
			if($_POST['type'] == 'additional'){
				$start = $this->date2sec($_POST['start']);
				$end = $this->date2sec($_POST['end']);
				$type = 0;
				$add = $this->hrs2sec($_POST['add']);
			}else{
				$start = $this->date2sec($_POST['start']);
				$end = $this->date2sec($_POST['end']);
				$type = 1;
				$add = 0;
			}
			//check if the start is greater than the end 
			if($start >= $end){
				$this->error = $this->dangerMessage('Event Start should be greater than Event End');
			}else{
				if($this->addEventMod($start, $end, $type, $add)){
					$this->message = $this->successMessage('Event schedule scucessfully saved.');
				}else{
					$this->error = $this->dangerMessage('Event schedule failed save.');
				}
			}
		}
		$this->errorMessage($this->error, $this->message);		
	}
	public function displayEventCont(){
		$result = $this->displayEventMod();
		$this->outputTable($result[0], $result[1], $result[2]);
	}
	public function deleteEventCont(){
		$id = $this->decryptString($_POST['select_id']);
		if($this->deleteData('tbl_event', 'event_id', $id)){
			$this->message = $this->successMessage('Event scucessfully deleted.');
		}else{
			$this->error = $this->dangerMessage('Event failed to delete.');
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function getEventCont(){
		$id = $this->decryptString($_POST['select_id']);
		$result = $this->getEventMods($id);
		if(!$result){
			$data = array(
				'error' => 'Failed to get the event data.' 
			);
		}else{
			$data = array(
				'event_id' 	   => $this->encryptString($result['event_id']),
				'event_start'  => $this->sec2date($result['start_time']),
				'event_end'    => $this->sec2date($result['end_time']),
				'event_type'   => $result['isdouble'],
				'event_add'	   => $result['add_time'] / 3600,
			);
		}
		echo json_encode($data);
	}
	public function updateEventCont(){
		if($_POST['start'] == '' || $_POST['end'] == '' || $_POST['type'] == '' || $_POST['select_id'] == ''){
			$this->error = $this->dangerMessage('Please provided the needed data.');
		}else{
			if($_POST['type'] == 'additional'){
				$start = $this->date2sec($_POST['start']);
				$end = $this->date2sec($_POST['end']);
				$type = 0;
				$add = $this->hrs2sec($_POST['add']);
				$id = $this->decryptString($_POST['select_id']);
			}else{
				$start = $this->date2sec($_POST['start']);
				$end = $this->date2sec($_POST['end']);
				$id = $this->decryptString($_POST['select_id']);
				$type = 1;
				$add = 0;
			}
			//check if the start is greater than the end 
			if($start >= $end){
				$this->error = $this->dangerMessage('Event Start should be greater than Event End');
			}else{
				if($this->updateEventMod($start, $end, $type, $add, $id)){
					$this->message = $this->successMessage('Event schedule scucessfully updated.');
				}else{
					$this->error = $this->dangerMessage('Event schedule failed update.');
				}
			}
		}
		$this->errorMessage($this->error, $this->message);	
	}
}



?>
