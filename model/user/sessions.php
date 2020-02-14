<?php
class ModelUserSessions extends Model {
	var $user_group_id = 2;
	public function addSession($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "sessions` SET tutors_to_students_id = '" . (int)$this->db->escape($data['tutors_to_students_id']) . "', session_date = '" . $this->db->escape($data['session_date']) . "', session_duration = '" . $this->db->escape($data['session_duration']) . "', session_notes = '" . $this->db->escape($data['session_notes']) . "', date_added = NOW() ");
      	
      	$session_id = $this->db->getLastId();
         return $session_id;
	}
	
	public function editSession($session_id, $data) {
			$this->db->query("UPDATE `" . DB_PREFIX . "sessions` SET tutors_to_students_id = '" . (int)$this->db->escape($data['tutors_to_students_id']) . "', session_date = '" . $this->db->escape($data['session_date']) . "', session_duration = '" . $this->db->escape($data['session_duration']) . "', session_notes = '" . $this->db->escape($data['session_notes']) . "' WHERE session_id = '" . (int)$session_id . "' ");
	}
	
	public function lockSession($session_id) {
		$locked_status = 1;
		$this->db->query("UPDATE `" . DB_PREFIX . "sessions` SET is_locked = '" . $locked_status . "', i_locked = '" . $locked_status . "', p_locked = '" . $locked_status . "' WHERE session_id = '" . (int)$session_id . "' ");
	}
	
	public function unlockSession($session_id) {
		$locked_status = 0;
		$this->db->query("UPDATE `" . DB_PREFIX . "sessions` SET is_locked = '" . $locked_status . "', i_locked = '" . $locked_status . "', p_locked = '" . $locked_status . "' WHERE session_id = '" . (int)$session_id . "' ");
	}
	
	public function deleteSession($session_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sessions WHERE session_id = '" . (int)$session_id . "'");
	}
	
	public function getSession($session_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sessions WHERE session_id = '$session_id'");
		return $query->row;
	}
	
	public function getTotalStudentsWhoReceivedClass($date_arr = array()) {
		
		$condition = "";
		if(!empty($date_arr))
			$condition = " WHERE date(session_date) >= '". $date_arr['start_date'] ."' AND date(session_date) <= '". $date_arr['end_date'] ."' ";
		
		$sql = "SELECT a.tutors_to_students_id FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id $condition GROUP BY a.students_id ";
		$query = $this->db->query($sql);

		return count($query->rows);
	}
		
	public function getSessions($data = array()) {
		$sql = "SELECT s.*, a.base_wage, a.base_invoice, CONCAT(st.firstname, ' ', st.lastname) AS student_name, CONCAT(t.firstname, ' ', t.lastname) AS tutor_name FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id JOIN " . DB_PREFIX . "user st ON a.students_id = st.user_id LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id ";

		$implode = array();
		
		/*Softronikx Technolgoies	*/	
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = "a.tutors_id = '" . $this->db->escape($data['filter_tutor_id']) . "'";
		}
		/* End of Code by Softronikx Technologies */
		
		if (isset($data['filter_session_date']) && !is_null($data['filter_session_date'])) {
			$implode[] = "s.date_submission like '%" . $this->db->escape($data['filter_session_date']) . "%'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = "CONCAT(t.firstname, ' ', t.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%'";
		}
		
		if (isset($data['filter_session_duration']) && !is_null($data['filter_session_duration'])) {
			$implode[] = "s.session_duration = '" . $this->db->escape($data['filter_session_duration']) . "' ";
		}
		
		if (isset($data['filter_session_notes']) && !is_null($data['filter_session_notes'])) {
			$implode[] = "s.session_date like '%" . $this->db->escape($data['filter_session_notes']) . "%'";
//			$implode[] = "s.session_notes LIKE '%" . $this->db->escape($data['filter_session_notes']) . "%'";
		}
		
		if (isset($data['filter_student_id']) && !is_null($data['filter_student_id'])) {
			$implode[] = "st.user_id = '" . $this->db->escape($data['filter_student_id']) . "' ";
		}
		
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = "t.user_id = '" . $this->db->escape($data['filter_tutor_id']) . "' ";
		}
		
		if (isset($data['filter_month']) && !is_null($data['filter_month'])) {
			$implode[] = "MONTH(session_date) = '" . $this->db->escape($data['filter_month']) . "' ";
		}
		
		if (isset($data['filter_year']) && !is_null($data['filter_year'])) {
			$implode[] = "YEAR(session_date) = '" . $this->db->escape($data['filter_year']) . "' ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'session_date',
			'student_name',
			'tutor_name',
			'session_notes',
			'session_duration'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY student_name";	
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
		
//		echo $sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
		
	public function getTotalSessions($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id LEFT JOIN " . DB_PREFIX . "user st ON a.students_id = st.user_id  LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id ";
		
		$implode = array();
		
		/*Softronikx Technolgoies	*/	
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = "a.tutors_id = '" . $this->db->escape($data['filter_tutor_id']) . "'";
		}
		/* End of Code by Softronikx Technologies */
		
		if (isset($data['filter_session_date']) && !is_null($data['filter_session_date'])) {
			$implode[] = "s.date_submission like '%" . $this->db->escape($data['filter_session_date']) . "%'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = "CONCAT(t.firstname, ' ', t.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%'";
		}
		
		if (isset($data['filter_session_duration']) && !is_null($data['filter_session_duration'])) {
			$implode[] = "s.session_duration = '" . $this->db->escape($data['filter_session_duration']) . "' ";
		}
		
		if (isset($data['filter_session_notes']) && !is_null($data['filter_session_notes'])) {
			$implode[] = "s.session_date like '%" . $this->db->escape($data['filter_session_notes']) . "%'";
//			$implode[] = "s.session_notes LIKE '%" . $this->db->escape($data['filter_session_notes']) . "%'";
		}
		
		if (isset($data['filter_student_id']) && !is_null($data['filter_student_id'])) {
			$implode[] = "st.user_id = '" . $this->db->escape($data['filter_student_id']) . "' ";
		}
		
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = "t.user_id = '" . $this->db->escape($data['filter_tutor_id']) . "' ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function getPayments($data = array()) {
		$sql = "SELECT SUM(s.session_duration*a.base_wage) as total_pay, SUM(s.session_duration) as total_hours, s.session_date FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id ";

		$implode = array();
		
		if (isset($data['filter_year']) && !is_null($data['filter_year'])) {
			$implode[] = "YEAR(session_date) = '" . $this->db->escape($data['filter_year']) . "' ";
		}
		
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = "t.user_id = '" . $this->db->escape($data['filter_tutor_id']) . "' ";
		}
		
		if (isset($data['filter_month']) && !is_null($data['filter_month'])) {
			$implode[] = "MONTH(session_date) = '" . $this->db->escape($data['filter_month']) . "' ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'session_date',
			'total_hours',
			'total_pay'
		);	
		
		$sql .= " GROUP BY a.tutors_id ";
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY session_date";	
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
	
	public function getTotalPayments($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id ";
		
		$implode = array();
		
		if (isset($data['filter_year']) && !is_null($data['filter_year'])) {
			$implode[] = "YEAR(session_date) = '" . $this->db->escape($data['filter_year']) . "' ";
		}
		
		if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
			$implode[] = "t.user_id = '" . $this->db->escape($data['filter_tutor_id']) . "' ";
		}
		
		if (isset($data['filter_month']) && !is_null($data['filter_month'])) {
			$implode[] = "MONTH(session_date) = '" . $this->db->escape($data['filter_month']) . "' ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY a.tutors_id ";
		$query = $this->db->query($sql);
				
		return isset($query->row['total'])?count($query->rows):0;
	}
	
	public function getStatistics($data = array()) {
		$sql = "SELECT SUM(s.session_duration*a.base_invoice) as total_revenue, SUM(s.session_duration) as hours_tutors, (SUM(s.session_duration*a.base_invoice)-SUM(s.session_duration*a.base_wage)) as total_profit, s.session_date FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id ";

		$implode = array();
		$group = "";
				
		if (isset($data['filter_month']) && ($data['filter_month']!="")) {
			$implode[] = "MONTH(session_date) = '" . $this->db->escape($data['filter_month']) . "' ";
			$group = "MONTH(session_date), ";
		}else{
			if(isset($data['filter_month']))
			$group = "MONTH(session_date), ";
		}
		
		if (isset($data['filter_year']) && ($data['filter_year']!="")) {
			$implode[] = "YEAR(session_date) = '" . $this->db->escape($data['filter_year']) . "' ";
			$group .= "YEAR(session_date)";
		}else{
			$group .= "YEAR(session_date)";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'session_date',
			'total_hours',
			'total_pay'
		);	
		
		$sql .= " GROUP BY $group ";
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY session_date";	
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
//echo $sql;  
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	public function getTotalStatistics($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id ";
		
		$implode = array();
		$group = "";
				
		if (isset($data['filter_month']) && ($data['filter_month']!="")) {
			$implode[] = "MONTH(session_date) = '" . $this->db->escape($data['filter_month']) . "' ";
			$group = "MONTH(session_date), ";
		}else{
			if(isset($data['filter_month']))
			$group = "MONTH(session_date), ";
		}
		
		if (isset($data['filter_year']) && ($data['filter_year']!="")) {
			$implode[] = "YEAR(session_date) = '" . $this->db->escape($data['filter_year']) . "' ";
			$group .= "YEAR(session_date)";
		}else{
			$group .= "YEAR(session_date)";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY $group ";
		$query = $this->db->query($sql);
		return isset($query->row['total'])?count($query->rows):0;
	}
	
	public function getActiveStudents($month="", $year="") {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id ";
		
		$implode = array();
		$group = "";
				
		if ($month!="") {
			$implode[] = "MONTH(session_date) = '$month' ";
		}
		
		if ($year!="") {
			$implode[] = "YEAR(session_date) = '$year' ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY a.students_id ";
		$query = $this->db->query($sql);
		return isset($query->row['total'])?count($query->rows):0;
	}
	
	public function getActiveTutors($month="", $year="") {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id ";
		
		$implode = array();
		$group = "";
				
		if ($month!="") {
			$implode[] = "MONTH(session_date) = '$month' ";
		}
		
		if ($year!="") {
			$implode[] = "YEAR(session_date) = '$year' ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " GROUP BY a.tutors_id ";
		$query = $this->db->query($sql);
		return isset($query->row['total'])?count($query->rows):0;
	}
	
	public function validateSession($session_date, $session_duration, $tutors_to_students_id, $session_id = "") {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."sessions WHERE session_date = '".$session_date."' AND  session_duration = '".$session_duration."' AND  tutors_to_students_id = '".$tutors_to_students_id."' AND session_id<>'".$session_id."'");
		return $query->row['total'];
	}
	
	public function getAllDurations(){
		$duration_array = array(
						  ""=>"", 
						  "0.50"=>"30 Minutes", 
						  "0.75"=>"45 Minutes", 
						  "1.00"=>"1 Hour", 
						  "1.25"=>"1 Hour + 15 Minutes", 
						  "1.50"=>"1 Hour + 30 Minutes", 
						  "1.75"=>"1 Hour + 45 Minutes", 
						  "2.00"=>"2 Hours", 
						  "2.25"=>"2 Hours + 15 Minutes", 
						  "2.50"=>"2 Hours + 30 Minutes", 
						  "2.75"=>"2 Hours + 45 Minutes", 
						  "3.00"=>"3 Hours", 
						  "3.25"=>"3 Hours + 15 Minutes", 
						  "3.50"=>"3 Hours + 30 Minutes", 
						  "3.75"=>"3 Hours + 45 Minutes", 
						  "4.00"=>"4 Hours", 
						  "4.25"=>"4 Hours + 15 Minutes", 
						  "4.50"=>"4 Hours + 30 Minutes", 
						  "4.75"=>"4 Hours + 45 Minutes", 
						  "5.00"=>"5 Hours",
						  "1:00"=>"cancelled with less than 24 hours", 
						  );
			return $duration_array;
	}
	
}
?>