<?php
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'learnonc_openc');
define('DB_PASSWORD', 'openc@11');
define('DB_DATABASE', 'learnonc_dbopenc');


$conn = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD)or die(mysql_error());
mysql_select_db(DB_DATABASE, $conn) or die(mysql_error());


//not required currently
public function generateTutorsPaycheque($data) {	
		
	$sql = "SELECT tts.tutors_id, " .
			" (select concat(firstname,' ',lastname, ' (', user_id,')') from user where user_id = tts.tutors_id) as name, " .
			" count(ssn.session_id) as num_of_sessions, " .
			" sum(ssn.session_duration) as total_hours, " .
			" sum(tts.base_wage * ssn.session_duration) as total_amount " .
			" FROM sessions ssn " .
			" JOIN tutors_to_students tts" .
			" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id)";
	
	$implode = array();
	
	if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
		$implode[] = " ssn.date_submission like '". $data['filter_billing_date'] . "%'";
	}
	
	if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
		$implode[] = " ssn.p_locked = '" . $data['filter_locked'] . "'";
	}
	
	if ($implode) {
		$sql .= " WHERE " . implode(" AND ", $implode);
	}
	
	$sql .= " GROUP BY tts.tutors_id ";		
	
//		echo $sql;
//		die;
	
	$query = mysql_query($sql);
	
	$r = array();
	while($r[] = mysql_fetch_array($query))
	{		
	}
	
	return $r;
}
	

public function getTutorEssaysAmount($data) {

	$sql = "select count(essay_id) as num_of_essay, tutor_id, sum(paid) as total_amount from `essay_assignment` ";
	
	$implode = array();
	
	if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
		$implode[] = " tutor_id = '". (int)$data['filter_tutor_id'] . "'";
	}
	
	if (isset($data['filter_date_completed']) && !is_null($data['filter_date_completed'])) {
		$implode[] = " date_completed like '". mysql_real_escape_string($data['filter_date_completed']) . "%'";
	}
	
	if (isset($data['filter_current_status']) && !is_null($data['filter_current_status'])) {
		$implode[] = " current_status = '". mysql_real_escape_string($data['filter_current_status']) . "'";
	}
	
	if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
		$implode[] = " is_locked = '" . mysql_real_escape_string($data['filter_locked']) . "'";
	}
	
	if ($implode) {
		$sql .= " WHERE " . implode(" AND ", $implode);
	}
	
	$sql .= " GROUP BY tutor_id ";		
	
//		echo $sql;
//		die;
	
	$query = mysql_query($sql);
	
	$r = array();
	while($r[] = mysql_fetch_array($query))
	{		
	}
	
	return $r;
}
	
	
public function getTutorSessions($tutor_id, $data) {
		
	$sql = "SELECT ssn.session_id " .
			" FROM sessions ssn  " .
			" JOIN tutors_to_students tts " .
			" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) ";
	
	$implode = array();
	
	$implode[] = " tts.tutors_id = '" . (int)$tutor_id . "'";
	
	if (isset($data['filter_billing_date']) && !is_null($data['filter_billing_date'])) {
		$implode[] = " ssn.date_submission like '". mysql_real_escape_string($data['filter_billing_date']) . "%'";
	}
	
	if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
		$implode[] = " ssn.p_locked = '" . mysql_real_escape_string($data['filter_locked']) . "'";
	}
	
	if ($implode) {
		$sql .= " WHERE " . implode(" AND ", $implode);
	}
	
//		echo $sql;
	
	$query = mysql_query($sql);
	
	$results = array();
	while($results[] = mysql_fetch_array($query))
	{		
	}
	
	//return $results;
	
	$all_sessions = array();
	
	if(count($results) > 0)
	foreach($results as $each_row) {
		$all_sessions[] = $each_row['session_id'];
	}
	
	return $all_sessions;
}


public function getTutorEssaysDetails($data=array()) {
	$sql = "select essay_id from `essay_assignment` ";
	
	$implode = array();
	
	if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
		$implode[] = " tutor_id = '". (int)$data['filter_tutor_id'] . "'";
	}
	
	if (isset($data['filter_date_completed']) && !is_null($data['filter_date_completed'])) {
		$implode[] = " date_completed like '". mysql_real_escape_string($data['filter_date_completed']) . "%'";
	}
	
	if (isset($data['filter_current_status']) && !is_null($data['filter_current_status'])) {
		$implode[] = " current_status = '". mysql_real_escape_string($data['filter_current_status']) . "'";
	}
	
	if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
		$implode[] = " is_locked = '" . mysql_real_escape_string($data['filter_locked']) . "'";
	}
	
	if ($implode) {
		$sql .= " WHERE " . implode(" AND ", $implode);
	}		
	
//		echo $sql;
	
	$query = mysql_query($sql);
	
	$results = array();
	while($results[] = mysql_fetch_array($query))
	{		
	}
	
	$all_essays_ides = array();
	
	if(count($results) > 0)
	foreach($results as $each_row) {
		$all_essays_ides[] = $each_row['essay_id'];	
	}
	
	return $all_essays_ides;
}

//function to generate paycheques for a month
public function generate_paycheques() {
//		echo "generate_paycheques";		
	/*echo "<pre>";		
	print_r($this->session->data);
	print_r($this->request->post);		
	echo "</pre>";*/
	
	$process_date = '2012-11-17';
	
	
	$billing_date = $process_date;
	$billing_date = substr($billing_date,0,strlen($billing_date)-2);
	
	$filter_data = array (
		'filter_billing_date' => $billing_date,
		'filter_locked' => '0'
	);
	
	$total_tutors = generateTutorsPaycheque($filter_data);
	
	$check_all_tutors = array();
	foreach($total_tutors as $each_tutor) {
		$check_all_tutors[] = $each_tutor['tutors_id'];
	}
	
	$filter_data_essay = array(
		'filter_date_completed' => $billing_date,
		'filter_current_status' => '4',
		'filter_locked' => 0
	);
	
	$all_essayes_tutors = getTutorEssaysAmount($filter_data_essay);
	
	$check_all_essays = array();
	foreach($all_essayes_tutors as $each_tutor) {			
		$check_all_essays[] = $each_tutor['tutor_id'];
	}

//		print_r($total_tutors);		
//		print_r($check_all_tutors);
//		print_r($check_all_essays);
	$check_all_essays = array_diff($check_all_essays, $check_all_tutors);
//		print_r($check_all_essays);

	foreach($check_all_essays as $each_tutor) {
		$total_tutors[] = array(
			'tutors_id' => $each_tutor,
			'name' => '',
			'num_of_sessions' => '0', 
			'total_hours' => '0',
			'total_amount' => '0'			
		);				
	}
//		print_r($total_tutors);
//		die;
	


	$total_generated = 0;
	$total_updated = 0;
	
	// Generate paycheques for tutors
	foreach($total_tutors as $each_tutor) {
		$tutor_data = array();
		$tutor_data = $each_tutor;

		$log_data = array();
		$log_data['all_sessions'] = getTutorSessions($tutor_data['tutors_id'], $filter_data);
		//$log_data['all_sessions'] = array();
		
		$tutorRaiseAmount = getTutorRaiseAmount($tutor_data['tutors_id'], $filter_data);
		//$tutorRaiseAmount = 0;
		
		
		/*if($tutor_data['tutors_id'] == "1014") {
			print_r($tutorRaiseAmount);
			die;	
		}*/
		
		$raise_amount = $tutorRaiseAmount['tutor_raise_amount'];
		$log_data['all_students_data'] = $tutorRaiseAmount['all_students_data']; 
		
		if($raise_amount > 0) {
			$tutor_data['raise_amount'] = $raise_amount;
			$tutor_data['total_amount'] = ($tutor_data['total_amount'] + $raise_amount);
			
		} else {
			$tutor_data['raise_amount'] = 0;
		}			
		
		
		// default status for geneated invoice
		
		$filter_data_essay = array(
			'filter_tutor_id' => $tutor_data['tutors_id'],
			'filter_date_completed' => $billing_date,
			'filter_current_status' => '4',
			'filter_locked' => 0
		);
		
		$log_data['tutor_essays_details'] = array();
		$essay_info = array();
		$essay_info = getTutorEssaysAmount($filter_data_essay);
		
		if(count($essay_info) > 0) {
			
			// select the first one
			$essay_info = $essay_info['0'];
			
			$log_data['tutor_essays_details'] = getTutorEssaysDetails($filter_data_essay);
			
			$tutor_data['num_of_essay'] = $essay_info['num_of_essay'];
			$tutor_data['essay_amount'] = $essay_info['total_amount'];
			$tutor_data['total_amount'] = ($tutor_data['total_amount'] + $essay_info['total_amount']);
			
		} else {
			$tutor_data['num_of_essay'] = 0;
			$tutor_data['essay_amount'] = 0;
		}
		
		$tutor_data['total_amount'] = round($tutor_data['total_amount'], 2);
		$tutor_data['paycheque_status'] = 'Hold For Approval';
		$tutor_data['paycheque_date'] = $process_date;
		
		//$check_paycheque = $this->model_cms_payment->checkTutorPaycheque($tutor_data['tutors_id'], $billing_date);
		
//			print_r($log_data);
//			echo "<hr />";
//			die;
//			print_r($check_paycheque);
		
		// Make a log Data for tutor paycheque
		$tutor_data['log_data'] = serialize($log_data);
		
		
		print_r($tutor_data);
		echo "<br>"."\n";
		
		
		
		//if(count($check_paycheque) > 0) {
		//	if(! $check_paycheque['is_locked']) {
		//		$this->model_cms_payment->editTutorPaycheque($check_paycheque['paycheque_id'], $tutor_data);
		//		$total_updated += 1;
		//	}
		//} else {
		
			
			
			
			//$this->model_cms_payment->addTutorPaycheque($tutor_data);
			//$total_generated += 1;	
			
			
			
			
		//}
	}
		
}


// $tutor_id = 462;
// $data['filter_locked'] = 1;
// $data['filter_billing_date'] = '2012-04';

// print_r(getTutorRaiseAmount($tutor_id, $data));

function getTutorRaiseAmount($tutor_id, $data) 
{
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
	$implode[] = " ssn.date_submission like '". $data['filter_billing_date'] . "%'";
}


if (isset($data['filter_locked']) && !is_null($data['filter_locked'])) {
	$implode[] = " ssn.p_locked = '" . $data['filter_locked'] . "'";
}

if ($implode) {
	$sql .= " WHERE " . implode(" AND ", $implode);
}

$sql .= " GROUP BY tts.students_id " ;
		//" having num_of_sessions > 4"; //commented by softronikx technologies

echo $sql."<br>";

$query = mysql_query($sql);

$total_students = array();
while($r = mysql_fetch_array($query))
{
	$total_students[] = $r;
}

//print_r($total_students);

if(count($total_students) > 0) {
	
	$tutor_raise_amount = 0;
	$all_students = array();
	$all_students_data = array();
	foreach($total_students as $each_student) {
		
		$tutor_info = mysql_query("select state, country from user_info where user_id='" . (int)$tutor_id . "'");
		$tutor_info = mysql_fetch_array($tutor_info);
		
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

		$sql = " SELECT ssn.session_id, tts.tutors_id, tts.students_id, tts.base_wage, tts.base_invoice, " .
			" ssn.session_duration, ssn.session_date " .
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
		
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " ORDER BY date_submission ASC ";
		
		echo $sql."<br>";

		$query = mysql_query($sql);
		$all_sessions = array();
		
		while($all_sessions[] = mysql_fetch_array($query))
		{
		}
		
		
		$base_rate = 0;
		$base_total = 0;
		$base_plus_total = 0;
		
		// Raise slabs				
		if($tutor_info['country'] == "Canada") {
			if($tutor_info['state'] == "Alberta")
				$change_at = array('8');
			else
				$change_at = array('4','8','12');
		} else {
			$change_at = array('4','8','12');						
		}
		
//				print_r($change_at);
//				print_r($all_sessions);
//				die;
		
		foreach($all_sessions as $key => $each_sessions) {
			
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
				}
				else
				{
					$date_to_compare = 'NONE'; //do not compare with blank as will be incremented for all sessions from start
				}
				//end of code by Softronikx Technologies
				
				if(strpos($each_sessions['session_date'],$date_to_compare) !== false) //condition added by softronikx technologies. increment base wage for only sessions of this month
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
		}
		
		$all_students[$each_student['students_id']]['base_total'] = $base_total;
		$all_students[$each_student['students_id']]['base_plus_total'] = $base_plus_total;
		$all_students[$each_student['students_id']]['raise_amount'] = $base_plus_total - $base_total;
		
		$tutor_raise_amount = $tutor_raise_amount + $all_students[$each_student['students_id']]['raise_amount'];
	}

//	print_r($all_students);
	
				
	return array('tutor_raise_amount' => $tutor_raise_amount, 'all_students_data' => $all_students_data);

} else {
	return 0;
}
	
}





/*
//get students list
$sql = "SELECT tts.students_id, tts.tutors_id, tts.base_invoice, " .
				" (select state from user_info where user_id = tts.students_id) as state, " .
				" (select `country` from user_info where user_id = tts.students_id) as `country`, " .
				" count(ssn.session_id) as num_of_sessions " .
				" FROM sessions ssn  " .
				" JOIN tutors_to_students tts " .
				" ON (ssn.tutors_to_students_id = tts.tutors_to_students_id)".
				" where ssn.date_submission like '2012-08%' and  ssn.p_locked = '1' GROUP BY tts.students_id  having num_of_sessions > 4";
//tts.tutors_id = '197' and  
//echo $sql;				
				
$res = mysql_query($sql);

if(mysql_num_rows($res)>0)
{
	$tutor_raise_amount = 0;
	$all_students = array();
	$all_students_data = array();
	while($each_student = mysql_fetch_array($res))
	{
		$students_id = (int)$each_student['students_id'];
		$tutors_id = (int)$each_student['tutors_id'];
		
		$q = "SELECT ssn.session_id, tts.tutors_id, tts.students_id, tts.base_wage, tts.base_invoice,ssn.session_duration, ssn.session_date
					FROM sessions ssn JOIN tutors_to_students tts ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) 
					where  tts.tutors_id = $tutors_id and  tts.students_id = $students_id ORDER BY date_submission ASC";
					
		$r = mysql_query($q);
		$all_sessions = array();
		while($a = mysql_fetch_array($r))
		{
			$all_sessions[] = $a;
		}	

		$base_rate = 0;
		$base_total = 0;
		$base_plus_total = 0;
		$tutor_info['country'] = "USA";

		// Raise slabs				
		if($tutor_info['country'] == "Canada") {
			if($tutor_info['state'] == "Alberta")
				$change_at = array('8');
			else
				$change_at = array('4','8','12');
		} else {
			$change_at = array('4','8','12');						
		}

		//print_r($all_sessions);
		
		foreach($all_sessions as $key => $each_sessions) 
		{
						
			$raise_amount = 0;
				
			
			if(($key + 1) > $change_at[0]) {
				
				//echo  $key." | ";
				
				if(empty($base_rate)) {
					$base_rate = $each_sessions['base_wage'];
					$base_rate_plus = $base_rate;
				}
				
				// increase of 10% for each slab 
				//base_rate_plus is not reset in the loop, whenever key is in array 'change_at' it will increment by 10%
				if(in_array($key, $change_at)) {
					$base_rate_plus = $base_rate_plus + ($base_rate_plus * 10 / 100);
				}						
				
				//check if the sesssion is of the current month, then only increment the base rate
				if(strpos($each_sessions['session_date'],'2012-08') !== false)
				{
					$base_total += ($base_rate * $each_sessions['session_duration']);
					$raise_amount = ($base_rate_plus * $each_sessions['session_duration']);
					$base_plus_total += $raise_amount;
				
				
					echo "base_total: $base_total, raise_amount: $raise_amount, base_plus_total: $base_plus_total.<br>";
					
					
					$all_students_data[$each_student['students_id']][] = array(
						'session_id' => $each_sessions['session_id'],
						'raise_amount' => $raise_amount,
					);
				}
			}
		}
					
		$all_students[$each_student['students_id']]['base_total'] = $base_total;
		$all_students[$each_student['students_id']]['base_plus_total'] = $base_plus_total;
		$all_students[$each_student['students_id']]['raise_amount'] = $base_plus_total - $base_total;
	}
}

print_r($all_students);
*/
?>