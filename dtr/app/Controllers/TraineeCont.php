<?php 
class TraineeCont extends EventCont
{
	public function getRecordsDataCont(){
		if($_POST['select_id'] == ''){
			$this->error = $this->dangerMessage('Record Id cannot be empty');
		}else{
			$id = $this->decryptString($_POST['select_id']);
			$result = $this->getRecordsDataMod($id);
		}
		$data = array(
			'start' => $this->sectodate($result[0]),
			'end'   => $this->sectodate($result[1])
		);
		echo json_encode($data);	
	}
	public function exportExcelCont(){
		$id = $this->decryptString($_POST['select_id']);
		$result = $this->exportExcelMod($id);
		$fname = $result[0]['fname'];
		$rem_time = $result[0]['rem_time'];
		$ren_time = $result[0]['ren_time'];
		$excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
		$row = 7;
        $num = 1;
        foreach ($result as $r1) {
        	$end_time = ($r1['end_time'] == '0') ? "None" : $this->sec2date($r1['end_time']);
        	$spent_time = ($r1['end_time'] == '0') ? "None" : $this->sec2hrs($r1['spent_time']);
            $excel->getActiveSheet()
                    ->setCellValue('A'.$row, $this->sec2date($r1['start_time']))
                    ->setCellValue('B'.$row, $end_time)
                    ->setCellValue('C'.$row, $spent_time);
            $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal('center');	
            $row++;
            $excel->getActiveSheet()->getStyle('A'.($row-1).':C'.($row-1))->applyFromArray(
                array(
                    'borders' => array(
                    'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                    ),
                    'vertical' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
              )
            );
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);

            //table headers
            $excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()
              ->setCellValue('A2', $fname)
              ->setCellValue('A3', "Name")
              ->setCellValue('B2', $this->sec2hrs($rem_time))
              ->setCellValue('B3', "Remaining Time")
              ->setCellValue('C2', $this->sec2hrs($ren_time))
              ->setCellValue('C3', "Rendered Time")
              ->setCellValue('A6', 'Start Time')
              ->setCellValue('B6', 'End Time')
              ->setCellValue('C6', 'Spent Time');
            $excel->getActiveSheet()->getStyle('A6:C6')->applyFromArray(
              array(
                'font' => array(
                    'bold' => true,
                ),
                'boders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
              )
            );
            $excel->getActiveSheet()->getStyle('A2:C2')->applyFromArray(
              array(
                'font' => array(
                    'bold' => true,
                ),
                'boders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
              )
            );
            $excel->getActiveSheet()->getStyle('A3:C3')->applyFromArray(
              array(
                'font' => array(
                    'bold' => true,
                ),
                'boders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
              )
            );

            //give borders to the data
            $excel->getActiveSheet()->getStyle('A7:C'.($row-1))->applyFromArray(
              array(
                'borders' => array(
                  'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  ),
                  'vertical' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  )
                )
              )
            );
        }
        $file_name = $fname . '-record.xls';
        header('Content-Type: application/vnd.vnd.ms-excel');
        header('Content-Disposition: attachement; filename='.$file_name.'');
        header('Cache-Control: max-age=0');
        $file = PHPExcel_IOFactory::createWriter($excel, 'Excel5');	
        $file->save('php://output');
	}
	public function updateRecordDataCont(){
		if($_POST['select_id'] == '' || $_POST['start'] == '' || $_POST['end'] == ''){
			$this->error = $this->dangerMessage('Please enter required data.');
		}else{
			if($this->checkTimeStamp($_POST['start']) && $this->checkTimeStamp($_POST['end'])){
				$id = $this->decryptString($_POST['select_id']);
				$start_time = $this->date2sec($_POST['start']);
				$end_time = $this->date2sec($_POST['end']);
				$this->updateRecordDataMod($id, $start_time, $end_time);
			}else{
				$this->error = $this->dangerMessage('Please enter a valid date.');
			}
		}
		$this->errorMessage($this->error, $this->message);
	}
	public function getTraineeRecordsCont(){
		if($_POST['select_id'] == ''){
			$this->error = $this->dangerMessage('Trainee id not found.');
		}else{
			$id = $this->decryptString($_POST['select_id']);
			if($this->checkTrainee($id)){
				$data = $this->getTraineeRecordsMod($id);	
			}else{
				$this->error = $this->dangerMessage('Trainee records not found.');
			}
		}
		$data = array(
			'error' => $this->error,
			'fname' => $data[0],
			'rem_time' => $data[1],
			'ren_time' => $data[2],
			'tbody' => $data[3]
		);
		echo json_encode($data);
	}	
	public function addTraineeCont(){
		if($_POST['uname'] == '' || $_POST['fname'] == '' || $_POST['nhrs'] == ''){
			$this->error = $this->dangerMessage('Please input required data.');
		}else{
			//check if the nhrs is number
			if($this->isNum($this->cleanString($_POST['nhrs']))){
				$nhrs = $this->hrs2sec($_POST['nhrs']);
			}else{
				$this->error = $this->dangerMessage('Error found on number of hours.');
			}
			//check username is alphanumeric
			if(ctype_alnum($this->cleanString($_POST['uname']))){
				$uname = $this->cleanString($_POST['uname']);
			}else{
				$this->error = $this->dangerMessage('Error found on username.');
			}
			//check fullname if alphanumeric and contains space
			if($this->isName($this->cleanString($_POST['fname']))){
				$fname = $this->cleanString($_POST['fname']);
			}else{
				$this->error = $this->dangerMessage('Error found on fullname.');
			}
			//check if no errors are found on checking
			if($this->error == ''){
				$trainee_id = $this->generateID('A', 'tbl_trainee', 'trainee_id'); 
				if($this->checkUname($uname)){
					$this->error = $this->dangerMessage('Username already exist.');
				}else{
					if($this->message = $this->addTraineeMod($trainee_id, $uname, $fname, $nhrs)){
						$this->message = $this->successMessage('Trainee successfully added.');
					}else{
						$this->error = $this->dangerMessage('Failed to add trainee.');
					}
				}	 
			}
		}
		$this->errorMessage($this->error,  $this->message);
	}
	public function displayTraineeCont(){
		$result = $this->displayTraineeMod();
		//$rows = $this->countRows('trainee_id', 'tbl_trainee'); 
		$this->outputTable($result[0], $result[1], $result[2]);
	}
	public function deleteTraineeCont(){
		$id = $this->decryptString($_POST['select_id']);
		if($this->deleteData('tbl_accounts', 'trainee_id', $id)){
			if($this->deleteData('tbl_trainee', 'trainee_id', $id)){
				if($this->deleteData('tbl_records', 'trainee_id', $id)){
					$this->message = $this->successMessage('Trainee data successfully deleted.');
				}else{
					$this->error = $this->dangerMessage('Trainee data failed to delete.');
				}
			}else{
				$this->error = $this->dangerMessage('Trainee data failed to delete.');
			}
		}else{
			$this->error = $this->dangerMessage('Trainee data failed to delete.');
		}
		$this->errorMessage($this->error,  $this->message);
	}
	public function getTraineeCont(){
		$data = $this->getTraineeMod($this->decryptString($_POST['select_id']));
		$output = array(
			'fname' => $data[0]['fname'],
			'uname' => $data[0]['uname'],
			'nhrs'  => $this->sec2hrs($data[0]['total_time'])
		);
		echo json_encode($output);
	}
	public function updateTraineeCont(){
		$fname = $_POST['edit_fname'];
		$uname = $_POST['edit_uname'];
		$nhrs = $_POST['edit_nhrs'];
		$type = $_POST['edit_type'];
		$id = $_POST['select_id'];
		if($fname == '' || $uname == '' || $type == '' || $id == '' || $nhrs == ''){
			$this->error = $this->dangerMessage('Please input the data needed.');
		}else{
			$fname = $this->cleanString($fname);
			$nhrs = $this->hrs2sec($nhrs);
			$type = $this->cleanString($type);
			$id = $this->decryptString($id);
			if($this->checkUser($uname, $id)){
				$this->error = $this->dangerMessage('Username already exist.');
			}else{
				$uname = $this->cleanString($uname);
				if($this->updateTraineeMod($fname, $uname, $nhrs, $type, $id)){
					$this->message = $this->successMessage('Trainee successfully updated.');
				}else{
					$this->error = $this->dangerMessage('Error found while trying to update.');
				}

			}

		}
		$this->errorMessage($this->error,  $this->message);
	}		
}
?>