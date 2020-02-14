<?php
class ModelCmsPayment extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information SET " .
				" title = '" . $this->db->escape($data['title']) . 
				"', description = '" . $this->db->escape($data['description']) . 
				"', sort_order = '" . (int)$data['sort_order'] .
				"', status = '" . (int)$data['status'] . 
				"'");

		$information_id = $this->db->getLastId();
		
		return $information_id; 
	}
	
	
	public function getTutorSessionRate($tutor_id,$tutors_to_student_id,$session_id) {
	
		$tutor_raise_amount = 0;
		$all_students = array();
		$all_students_data = array();				
				
		$tutor_info = $this->db->query("select state, country from user_info where user_id='" . (int)$tutor_id . "'");
		$tutor_info = $tutor_info->row;			
			
		$sql = " SELECT ssn.session_id as session_id, tts.tutors_id, tts.students_id, tts.base_wage, tts.base_invoice, " .
			" ssn.session_duration, ssn.session_date, ssn.is_locked " .
			" FROM sessions ssn " .
			" JOIN tutors_to_students tts " .
			" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) ";
							
		$implode = array();

		$implode[] = " tts.tutors_to_students_id = '" . (int)$tutors_to_student_id . "'";
		
		//$implode[] = " ssn.session_date <= '" .$session_date. "'";
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		//$sql .= " ORDER BY session_date ASC ";
		$sql .= " ORDER BY date_submission ASC ";
		
		//echo $sql;
		
		$query = $this->db->query($sql);
		
		$all_sessions = $query->rows;
		
		$base_rate = 0;
		$base_total = 0;
		$base_plus_total = 0;
		$total_minutes = 0;
		
		// Raise slabs				
		if($tutor_info['country'] == "Canada") {
			if($tutor_info['state'] == "Alberta" || $tutor_info['state'] == "AB")
				$change_at = array('8');
			else
				$change_at = array('4','8','12');
		} else {
			$change_at = array('4','8','12');						
		}
		
		unset($change_at);
		//$change_at = array('5','15','20');		//new logic 27th sep 2014
		$change_at = array('6','16','21');	
		//$change_at = array('5','15','20');	
		
//				print_r($change_at);
//				print_r($all_sessions);
//				die;
		
		foreach($all_sessions as $key => $each_sessions) {
		
			//change below query to sum session duration
			//and change rate based on session hours
			//
			
			$raise_amount = 0;
			$session_minutes = ($each_sessions['session_duration'] * 60);
			$total_minutes = $total_minutes + $session_minutes;
			//echo 'total_minutes are'.$total_minutes;
			$total_hours = $total_minutes/60;
			if($total_hours >= 21) { $actual_total_hours = $total_hours; $total_hours = 21;}
			elseif($total_hours >= 16) {$actual_total_hours = $total_hours; $total_hours = 16;}
			elseif($total_hours >= 6) {$actual_total_hours = $total_hours; $total_hours = 6;}
			
			if(empty($base_rate)) {
				//$base_rate = $each_sessions['base_wage'];
				$base_rate = "20";
				$base_rate_plus = $base_rate;
			}
			
			if(in_array($total_hours, $change_at)) {
			
				//new logic on 29th sep 2014
				if($total_hours == '6')
				{
					$base_rate_plus = "22";
					//$hour_difference = $actual_total_hours - $total_hours; //Eg: (6.5 - 5) = 1.5
					//if($hour_difference < $each_sessions['session_duration'])
					//{
					//	$base_rate_plus = 22 - 						
					//}
				}
				elseif($total_hours == '16')
				{
					$base_rate_plus = "23";
				}
				elseif($total_hours == '21')
				{
					$base_rate_plus = "25";
				}					
			}	
			if($each_sessions['session_id'] == $session_id)
				break;	
			
			
			
			
			/*if(($key + 1) > $change_at[0]) {
				
//						echo  $key." | ";
				
				if(empty($base_rate)) {
					//$base_rate = $each_sessions['base_wage'];
					$base_rate = "20";
					$base_rate_plus = $base_rate;
				}
				
				// increase of 10% for each slab 
				//base_rate_plus is not reset in the loop, whenever key is in array 'change_at' it will increment by 10%
				if(in_array($key, $change_at)) {
				
					//new logic on 29th sep 2014
					if($key == '5')
					{
						$base_rate_plus = "22";
					}
					elseif($key == '15')
					{
						$base_rate_plus = "23";
					}
					elseif($key == '20')
					{
						$base_rate_plus = "25";
					}
					
					//$base_rate_plus = $base_rate_plus + ($base_rate_plus * 10 / 100);
				}	
				
			}
			
			//count only the sessions till the current session
			if($each_sessions['session_id'] == $session_id)
				break;*/
		}
		

		if(isset($base_rate_plus))
			return $base_rate_plus;
		else
			return 0;	
	
	}
	
	
	public function getTutorRaiseAmount($tutor_id, $data) {
		/*
		$sql = "SELECT tts.students_id, tts.base_invoice, " .
				" count(ssn.session_id) as num_of_sessions" .
				" FROM sessions ssn  " .
				" LEFT JOIN tutors_to_students tts " .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) " .
				" WHERE  tts.tutors_id = '". (int)$tutor_id ."' " .
				" AND  ssn.is_locked = '0' " .
				" GROUP BY tts.students_id " .
				" having num_of_sessions > 4";
		*/
		
		//get a list of all the students for this tutor who have taken session in this month
		$sql = "SELECT tts.students_id, tts.base_invoice, " .
				" (select state from user_info where user_id = tts.students_id) as state, " .
				" (select `country` from user_info where user_id = tts.students_id) as `country`, " .
				" count(ssn.session_id) as num_of_sessions " .
				" FROM sessions ssn  " .
				" JOIN tutors_to_students tts " .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) ";
		
		$implode = array();
		
		$implode[] = " tts.tutors_id = '" . (int)$tutor_id . "'";
		
		/*Below if statement commented by Softronikx  - as filtering of assignment rate increment should not be based on the month - since it is student list let this criteria be there*/
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
		}
		
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " ssn.p_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY tts.students_id " ;
				//" having num_of_sessions > 4"; //commented by softronikx technologies
		
//		echo $sql;
		
		$query = $this->db->query($sql);
		
		$total_students = $query->rows;
		
		if(count($total_students) > 0) {
			
			$tutor_raise_amount = 0;
			$total_amount = 0;
			$all_students = array();
			$all_students_data = array();
			foreach($total_students as $each_student) {
				
				$tutor_info = $this->db->query("select state, country from user_info where user_id='" . (int)$tutor_id . "'");
				$tutor_info = $tutor_info->row;
				
//				print_r($tutor_info);
				
				/*
				$sql = " SELECT ssn.session_id, tts.tutors_id, tts.students_id, tts.base_wage, tts.base_invoice, " .
					" ssn.session_duration, ssn.session_date " .
					" FROM sessions ssn " .
					" LEFT JOIN tutors_to_students tts " .
					" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) " .
					" WHERE  tts.tutors_id = '". (int)$tutor_id ."' " .
					" AND tts.students_id = '". (int)$each_student['students_id'] ."' " .
					" AND  ssn.is_locked = '0' " .
					" order by session_date asc ";
				*/

				$sql = " SELECT ssn.session_id, tts.tutors_id, tts.students_id, tts.base_wage, tts.base_invoice, tts.tutors_to_students_id, " .
					" ssn.session_duration, ssn.session_date, ssn.is_locked " .
					" FROM sessions ssn " .
					" JOIN tutors_to_students tts " .
					" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) ";
									
				$implode = array();
		
				$implode[] = " tts.tutors_id = '" . (int)$tutor_id . "'";
				
				$implode[] = " tts.students_id = '" . (int)$each_student['students_id'] . "'";
				
				/*
				Below if statement commented by Softronikx  - as filtering of assignment rate increment should not be based on the month
				if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
					$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
				}
				*/
				
				/* 
				Locked and unlocked sessions - both must be counted when increment is being calculated - commented by Softrnikx Technologies 7th August
				if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
					$implode[] = " ssn.p_locked = '" . $this->db->escape($data['filter_locked']) . "'";
				}
				
				SELECT ssn.session_id, tts.tutors_id, tts.students_id, tts.base_wage, tts.base_invoice, ssn.session_duration, ssn.session_date FROM sessions ssn  JOIN tutors_to_students tts ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) WHERE  tts.tutors_id = '446' AND tts.students_id = '495' ORDER BY date_submission ASC
				
				*/
				
				//readded by softronikx for new logic
				if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
					$implode[] = " ssn.p_locked = '" . $this->db->escape($data['filter_locked']) . "'";
				}
				
				/*if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
					$implode[] = " ssn.session_date like '". $this->db->escape($data['filter_billing_date']) . "%'";
				}*/
				
				if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
					$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
				}
				
				
				if ($implode) {
					$sql .= " WHERE " . implode(" AND ", $implode);
				}
				
				//$sql .= " ORDER BY session_date ASC ";
				$sql .= " ORDER BY date_submission ASC ";
				
//				echo $sql;

				$query = $this->db->query($sql);
				
				$all_sessions = $query->rows;
				
				$base_rate = 0;
				$base_total = 0;
				$base_plus_total = 0;
				
				// Raise slabs				
				if($tutor_info['country'] == "Canada") {
					if($tutor_info['state'] == "Alberta" || $tutor_info['state'] == "AB")
						$change_at = array('8');
					else
						$change_at = array('4','8','12');
				} else {
					$change_at = array('4','8','12');						
				}
				
				unset($change_at);
				$change_at = array('5','15','20');	//new condition on 27th sep, 2014
				
//				print_r($change_at);
//				print_r($all_sessions);
//				die;
				
				foreach($all_sessions as $key => $each_sessions) {
										
					$session_rate = $this->getTutorSessionRate($tutor_id,$each_sessions['tutors_to_students_id'],$each_sessions['session_id']);
					if(empty($session_rate))
					{
						$session_rate = $each_sessions['base_wage'];
						$raise_amount = $session_rate * $each_sessions['session_duration'];
					}
					else
					{	
						$raise_amount = $session_rate * $each_sessions['session_duration'];
						$all_students_data[$each_student['students_id']][] = array(
							'session_id' => $each_sessions['session_id'],
							'raise_amount' => $raise_amount,
						);
					}
					
					$total_amount = $total_amount + $raise_amount;
					
					$tutor_raise_amount = $tutor_raise_amount + ($session_rate - $each_sessions['base_wage'])* $each_sessions['session_duration'];
					
					
					//commented on 1st Feb, 2013 to try out new logic
					/*
					$raise_amount = 0;
					
					if(($key + 1) > $change_at[0]) {
						
//						echo  $key." | ";
						
						if(empty($base_rate)) {
							$base_rate = $each_sessions['base_wage'];
							$base_rate_plus = $base_rate;
						}
						
						// increase of 10% for each slab 
						//base_rate_plus is not reset in the loop, whenever key is in array 'change_at' it will increment by 10%
						if(in_array($key, $change_at)) {
							$base_rate_plus = $base_rate_plus + ($base_rate_plus * 10 / 100);
						}	
						
						//condition added by softronikx technologies. increment base wage for only sessions of this month
						if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) 
						{
							$date_to_compare = $data['filter_billing_date'];
							$billing_month = (int)substr($data['filter_billing_date'],5,2);
						}
						else
						{
							$date_to_compare = 'NONE'; //do not compare with blank as will be incremented for all sessions from start
						}
						//end of code by Softronikx  Technologies
						
						$session_month =(int)substr($each_sessions['session_date'],5,2);
						
						//if(strpos($each_sessions['session_date'],$date_to_compare) !== false) //condition added by softronikx technologies. increment base wage for only sessions of this month
						if($each_sessions['is_locked'] == $data['filter_locked']  and $session_month <= $billing_month ) //condition added by softronikx technologies. increment base wage for only sessions that are not yet locked month less than current month
						{
							$base_total += ($base_rate * $each_sessions['session_duration']);
							$raise_amount = ($base_rate_plus * $each_sessions['session_duration']);
							$base_plus_total += $raise_amount;
							
							$all_students_data[$each_student['students_id']][] = array(
								'session_id' => $each_sessions['session_id'],
								'raise_amount' => $raise_amount,
							);
						}
					}
					*/
					//commented on 1st Feb, 2013 to try out new logic
				}
				
				//$all_students[$each_student['students_id']]['base_total'] = $base_total;
				//$all_students[$each_student['students_id']]['base_plus_total'] = $base_plus_total;
				//$all_students[$each_student['students_id']]['raise_amount'] = $base_plus_total - $base_total;
				
				//$tutor_raise_amount = $tutor_raise_amount + $all_students[$each_student['students_id']]['raise_amount'];
			}

		//	print_r($all_students);
			
						
			return array('tutor_raise_amount' => $tutor_raise_amount, 'all_students_data' => $all_students_data);

		} else {
			return 0;
		}
			
	}
	
	public function getTutorEssaysDetails($data=array()) {
		$sql = "select essay_id from `essay_assignment` ";
		
		$implode = array();
		
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = " tutor_id = '". (int)$data['filter_tutor_id'] . "'";
		}
		
		if (isset($data['filter_date_completed']) && !is_null($data['filter_date_completed'])) {
			$implode[] = " date_completed like '". $this->db->escape($data['filter_date_completed']) . "%'";
		}
		
		if (isset($data['filter_current_status']) && !is_null($data['filter_current_status'])) {
			$implode[] = " current_status = '". $this->db->escape($data['filter_current_status']) . "'";
		}
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " is_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}		
		
//		echo $sql;
		
		$query = $this->db->query($sql);
		
		$results = $query->rows;
		
		$all_essays_ides = array();
		
		if(count($results) > 0)
		foreach($results as $each_row) {
			$all_essays_ides[] = $each_row['essay_id'];	
		}
		
		return $all_essays_ides;
	}
	
	public function getTutorEssaysAmount($data) {
		$sql = "select count(essay_id) as num_of_essay, tutor_id, sum(paid) as total_amount from `essay_assignment` ";
		
		$implode = array();
		
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = " tutor_id = '". (int)$data['filter_tutor_id'] . "'";
		}
		
		if (isset($data['filter_date_completed']) && !is_null($data['filter_date_completed'])) {
			$implode[] = " date_completed like '". $this->db->escape($data['filter_date_completed']) . "%'";
		}
		
		if (isset($data['filter_current_status']) && !is_null($data['filter_current_status'])) {
			$implode[] = " current_status = '". $this->db->escape($data['filter_current_status']) . "'";
		}
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " is_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY tutor_id ";		
		
//		echo $sql;
//		die;
		
		$query = $this->db->query($sql);
		
		return $query->rows;		
	}
	
	public function lock_paycheques($data) {
		$locked_num = 1;
		
		$paycheque_sql = "UPDATE " . DB_PREFIX . "tutor_paycheque SET " . 
				" is_locked = '" . (int)$locked_num .
				"', date_modified = now() " .
				" WHERE paycheque_id = '" . (int)$data['paycheque_id'] . "'";
		
		$result = $this->db->query($paycheque_sql);
		
		if($result) {
			$sessions_ides = array();
			$log_data = unserialize($data['log_data']);
			$sessions_ides = $log_data['all_sessions'];			
			
			$sessions_ides = implode(",", $sessions_ides);
			
			if(empty($sessions_ides))
				$sessions_ides = 0;
			
			$sql = "UPDATE " . DB_PREFIX . "sessions SET " .
					" is_locked = '" . (int)$locked_num . 
					"', p_locked = '" . (int)$locked_num .
					//"', date_modified = now() " .
					"' WHERE session_id in (" . $this->db->escape($sessions_ides) . ")";
			
			$this->db->query($sql);
			
			$essays_ides = $log_data['tutor_essays_details'];			
			
			$essays_ides = implode(",", $essays_ides);
			
			if(empty($essays_ides))
				$essays_ides = 0;
			
			$sql = "UPDATE " . DB_PREFIX . "essay_assignment SET " . 
					" is_locked = '" . (int)$locked_num .
					//"', date_modified = now() " .
					"' WHERE essay_id in (" . $this->db->escape($essays_ides) . ")";
			
			$this->db->query($sql);
		}

		return $result;
	}
	
	public function checkTutorPaycheque($tutor_id, $billing_date) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tutor_paycheque WHERE tutor_id = '" . (int)$tutor_id . "' AND paycheque_date like '" . $this->db->escape($billing_date) . "%'");
		
		return $query->row;		
	}
	
	public function addTutorPaycheque($data) { 
				
		$this->db->query("INSERT INTO " . DB_PREFIX . "tutor_paycheque SET " .
				" tutor_id = '" . $this->db->escape($data['tutors_id']) . 
				"', paycheque_date = '" . $this->db->escape($data['paycheque_date']) . 
				"', num_of_essay = '" . (int)$data['num_of_essay'] .
				"', essay_amount = '" . (float)$data['essay_amount'] .
				"', raise_amount = '" . (float)$data['raise_amount'] .				
				"', num_of_sessions = '" . (int)$data['num_of_sessions'] .
				"', total_hours = '" . (float)$data['total_hours'] .
				"', total_amount = '" . (float)$data['total_amount'] .
				"', paycheque_status = '" . $this->db->escape($data['paycheque_status']) .
				"', log_data = '" . $this->db->escape($data['log_data']) . 
				"'");

		$information_id = $this->db->getLastId();
		
		return $information_id;
	}
	
	public function editTutorPaycheque($paycheque_id, $data) { 
		
		$result = $this->db->query("UPDATE " . DB_PREFIX . "tutor_paycheque SET " . 
				" paycheque_date = '" . $this->db->escape($data['paycheque_date']) .
				"', num_of_essay = '" . (int)$data['num_of_essay'] .
				"', essay_amount = '" . (float)$data['essay_amount'] . 
				"', raise_amount = '" . (float)$data['raise_amount'] . 
				"', num_of_sessions = '" . (int)$data['num_of_sessions'] .
				"', total_hours = '" . (float)$data['total_hours'] .
				"', total_amount = '" . (float)$data['total_amount'] .
				"', paycheque_status = '" . $this->db->escape($data['paycheque_status']) .
				"', log_data = '" . $this->db->escape($data['log_data']) .
				"', date_modified = now() " .
				" WHERE paycheque_id = '" . (int)$paycheque_id . 
				"'");
		
		return $result;
	}
	
	public function generateTutorsPaycheque($data) {	
		
		$sql = "SELECT tts.tutors_id, " .
				" (select concat(firstname,' ',lastname, ' (', user_id,')') from user where user_id = tts.tutors_id) as name, " .
				" count(ssn.session_id) as num_of_sessions, " .
				" sum(ssn.session_duration) as total_hours, " .
				" sum(tts.base_wage * ssn.session_duration) as total_amount " .
				" FROM " . DB_PREFIX . "sessions ssn " .
				" JOIN " . DB_PREFIX . "tutors_to_students tts" .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id)";
		
		$implode = array();
		
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
		}
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " ssn.p_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY tts.tutors_id ";		
		
//		echo $sql;
//		die;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getPayCheques($data = array()) {
		
		$sql = "SELECT p.*, concat(ut.firstname,' ',ut.lastname) as tutor_name FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) ";
			
		$implode = array();
		
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " p.paycheque_date like '". $this->db->escape($data['filter_billing_date']) . "%'";
		}
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " p.is_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if (isset($data['filter_paycheque_status']) && !is_null($data['filter_paycheque_status'])) {
			$implode[] = "p.paycheque_status = '" . $this->db->escape($data['filter_paycheque_status']) . "' ";
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "p.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "p.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_paycheque_date']) && !is_null($data['filter_paycheque_date'])) {
			$implode[] = "DATE(p.paycheque_date) = DATE('" . $this->db->escape($data['filter_paycheque_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'total_hours',
			'total_amount',
			'paycheque_status',
			'total_hours'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY total_hours";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}		

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getInvoices($data) {
		
			$sql = "SELECT concat(u.firstname,' ',u.lastname) as student_name, i.*, u.* FROM " . DB_PREFIX . "student_invoice i LEFT JOIN user u ON (i.student_id = u.user_id) ";
			
//			$sql = "SELECT * FROM " . DB_PREFIX . "student_invoice ";
			
			$implode = array();
		
			if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
				$implode[] = " i.invoice_date like '". $this->db->escape($data['filter_billing_date']) . "%'";
			}
			
			if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
				$implode[] = " i.is_locked = '" . $this->db->escape($data['filter_locked']) . "'";
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
		
			$sort_data = array(
				'i.invoice_date',
				'i.invoice_id',
				'i.i_locked'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY i.invoice_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
//			echo $sql;
			
			$query = $this->db->query($sql);
			
			return $query->rows;
	}
	
	public function lock_invoices($data) {
		$locked_num = 1;
		
		$invoice_sql = "UPDATE " . DB_PREFIX . "student_invoice SET " . 
				" is_locked = '" . (int)$locked_num .
				"', date_modified = now() " .
				" WHERE invoice_id = '" . (int)$data['invoice_id'] . "'";
		
		$result = $this->db->query($invoice_sql);
		
		if($result) {
			$get_log_data = unserialize($data['log_data']);
			
			$update_student_packages = array();
			if(count($get_log_data['student_packages']) > 0)
			foreach($get_log_data['student_packages'] as $each_package) {
				
				$left_package_hours = $each_package['left_hours'] - $each_package['deduct_hours'];
				if($left_package_hours < 0)
					$left_package_hours = 0; 
				
				$order_id = $each_package['order_id'];
				$hours_left = $left_package_hours;
				$old_left_hours = $each_package['left_hours'];
			
				if($old_left_hours <> $hours_left) {
					
					$order_status_id = 5; // valid for complete orders
					
					$comments = "Remaining Hours Update From ".$old_left_hours." To ".$hours_left;
					
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET left_hours = '" . (float)$hours_left . "' WHERE order_id = '" . (int)$order_id . "'");
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . 
						"', order_status_id = '" . (int)$order_status_id . 
						"', notify = '0" . 
						"', comment = '" . $this->db->escape($comments) . 
						"', date_added = NOW()");	
				}			
			
			}			
			
			$sessions_ides = array();
			$get_sessions = $get_log_data['student_sessions'];
			foreach($get_sessions as $each_sessions) {
				$sessions_ides[] = $each_sessions['session_id'];
			}
			
			$sessions_ides = implode(",", $sessions_ides);
			
			if(empty($sessions_ides))
				$sessions_ides = 0;
			
			$sql = "UPDATE " . DB_PREFIX . "sessions SET " . 
					" is_locked = '" . (int)$locked_num .
					"', i_locked = '" . (int)$locked_num .
					//"', date_modified = now() " .
					"' WHERE session_id in (" . $this->db->escape($sessions_ides) . ")";
			
			$this->db->query($sql);
		}

		return $result;		
	}
	
	public function generateStudentInvoiceNumber() {
		
		$query = $this->db->query("SELECT value as invoice_num FROM " . DB_PREFIX . "setting WHERE `key` = 'config_invoice_no'");
		
		if (!empty($query->row['invoice_num'])) {
			$invoice_id = $query->row['invoice_num'] + 1;
		} else {
			$invoice_id = 1;
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . (int)$invoice_id . "' WHERE `key` = 'config_invoice_no'");

		return $invoice_id;
	}
	
	public function checkStudentInvoice($student_id, $billing_date) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "student_invoice WHERE student_id = '" . (int)$student_id . "' AND invoice_date like '" . $this->db->escape($billing_date) . "%'");
		
		return $query->row;		
	}
	
	public function addStudentInvoice($data) { 
				
		$this->db->query("INSERT INTO " . DB_PREFIX . "student_invoice SET " .
				" student_id = '" . $this->db->escape($data['students_id']) .
				"', invoice_num = '" . (int)$data['invoice_num'] .
				"', invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . 
				"', invoice_date = '" . $this->db->escape($data['invoice_date']) . 
				"', num_of_sessions = '" . (int)$data['num_of_sessions'] .
				"', total_hours = '" . (float)$data['total_hours'] .
				"', hour_charged = '" . (float)$data['hour_charged'] .
				"', total_amount = '" . (float)$data['total_amount'] .
				"', invoice_format = '" . $this->db->escape($data['invoice_format']) .
				"', log_data = '" . $this->db->escape($data['log_data']) .
				"', invoice_status = '" . $this->db->escape($data['invoice_status']) . 
				"'");

		$information_id = $this->db->getLastId();
		
		return $information_id;
	}
	
	public function editStudentInvoice($invoice_id, $data) { 
				
		$result = $this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET " . 
				" invoice_date = '" . $this->db->escape($data['invoice_date']) . 
				"', num_of_sessions = '" . (int)$data['num_of_sessions'] .
				"', total_hours = '" . (float)$data['total_hours'] .
				"', hour_charged = '" . (float)$data['hour_charged'] .				
				"', total_amount = '" . (float)$data['total_amount'] .
				"', invoice_format = '" . $this->db->escape($data['invoice_format']) .
				"', log_data = '" . $this->db->escape($data['log_data']) .
				"', invoice_status = '" . $this->db->escape($data['invoice_status']) .
				"', date_modified = now() " .
				" WHERE invoice_id = '" . (int)$invoice_id . 
				"'");
		
		return $result;
	}
	
	public function updateInvoiceStatus($invoice_id, $data) { 
				
		$result = $this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET " . 
				" invoice_status = '" . $this->db->escape($data['invoice_status']) .
				"', send_date = now() " .
				" WHERE invoice_id = '" . (int)$invoice_id . 
				"'");
		
		return $result;
	}
	
	public function getTutorSessions($tutor_id, $data) {
		
		$sql = "SELECT ssn.session_id " .
				" FROM sessions ssn  " .
				" JOIN tutors_to_students tts " .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) ";
		
		$implode = array();
		
		$implode[] = " tts.tutors_id = '" . (int)$tutor_id . "'";
		
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
		}
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " ssn.p_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
//		echo $sql;
		
		$query = $this->db->query($sql);
		
		$results = $query->rows;
		
		$all_sessions = array();
		
		if(count($results) > 0)
		foreach($results as $each_row) {
			$all_sessions[] = $each_row['session_id'];
		}
		
		return $all_sessions;
	}
	
	public function getStudentSessions($student_id, $data) {
		$sql = "SELECT ssn.session_id, tts.tutors_id, tts.students_id, " .
				" concat(us.firstname,' ',us.lastname) as student_name, " .
				" concat(ut.firstname,' ',ut.lastname) as tutor_name, " .
				" ssn.session_date, ssn.session_duration, tts.base_invoice " .
				" FROM " . DB_PREFIX . "sessions ssn " .
				" JOIN " . DB_PREFIX . "tutors_to_students tts" .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id)" .
				" LEFT JOIN " . DB_PREFIX . "user us" .
				" ON (tts.students_id = us.user_id)" .
				" LEFT JOIN " . DB_PREFIX . "user ut" .
				" ON (tts.tutors_id = ut.user_id)";
		
		$implode = array();
		
		$implode[] = " tts.students_id = '". (int)$student_id . "'";
		
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
		} //session_date
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " ssn.i_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}		
		
//		echo $sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function generateStudentsInvoice($data) {
		
		$sql = "SELECT tts.students_id, " .
				" (select concat(firstname,' ',lastname, ' (', user_id,')') from user where user_id = tts.students_id) as name, " .
				" count(ssn.session_id) as num_of_sessions, " .
				" sum(ssn.session_duration) as total_hours, " .
				" sum(tts.base_invoice * ssn.session_duration) as total_amount " .
				" FROM " . DB_PREFIX . "sessions ssn " .
				" JOIN " . DB_PREFIX . "tutors_to_students tts" .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id)";
		
		$implode = array();
		
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " ssn.date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
		} //session_date
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " ssn.i_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY tts.students_id";		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTutorTotalHours($data) {
		
		$sql = "SELECT sum(session_duration) as total FROM " . DB_PREFIX . "sessions ";
		
		$implode = array();
		
		if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
			$implode[] = " date_submission like '". $this->db->escape($data['filter_billing_date']) . "%'";
		}
		
		if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
			$implode[] = " i_locked = '" . $this->db->escape($data['filter_locked']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
//		echo $sql; //getTutorTotalHours
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function editInformation($information_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "information SET " .
				" title = '" . $this->db->escape($data['title']) . 
				"', description = '" . $this->db->escape($data['description']) . 
				"', sort_order = '" . (int)$data['sort_order'] . 
				"', status = '" . (int)$data['status'] . 
				"' WHERE information_id = '" . (int)$information_id . 
				"'");
	}
	
	public function deleteInformation($information_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");

		return $result;
	}	

	public function getInformation($information_id) {
		$query = $this->db->query("SELECT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS keyword FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information";
		
			$sort_data = array(
				'title',
				'sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if (!$information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information ORDER BY title");
	
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}	
	
			return $information_data;			
		}
	}
	
	public function getInformationDescriptions($information_id) {
		$information_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $information_description_data;
	}
	
	public function getInformationStores($information_id) {
		$information_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_store_data[] = $result['store_id'];
		}
		
		return $information_store_data;
	}
	
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information");
		
		return $query->row['total'];
	}
	
	public function getStudentRate($user_id) {
		$query = $this->db->query("SELECT DISTINCT *, c.user_id as user_id FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) WHERE c.user_id = '" . (int)$user_id . "'");
		$user_info = $query->row;
		
		if(!empty($user_info['grades_id'])) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "grades WHERE grades_id = '" . (int)$user_info['grades_id'] . "'");
			$grade_info = $query->row;
			
			switch($user_info['country']) {
				case 'Canada':
					if($user_info['state'] == 'Alberta' || $user_info['state'] == 'AB')
						return $grade_info['price_alb'];
					else
						return $grade_info['price_can'];
				case 'USA':
					return $grade_info['price_usa'];
				default :
					return $grade_info['price_usa'];
			}
		}
		
		return 0;
	}	
}
?>