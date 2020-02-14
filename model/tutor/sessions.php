<?php
class ModelTutorSessions extends Model {
	var $user_group_id = 2;
	public function addSession($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "sessions` SET tutors_to_students_id = '" . (int)$this->db->escape($data['tutors_to_students_id']) . "', session_date = '" . $this->db->escape($data['session_date']) . "', session_duration = '" . $this->db->escape($data['session_duration']) . "', session_notes = '" . $this->db->escape($data['session_notes']) . "', date_submission ='" . $this->db->escape($data['date_submission']) . "', date_added = NOW() ");
      	
      	$session_id = $this->db->getLastId();
         return $session_id;
	}
	
	public function editSession($session_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sessions` SET tutors_to_students_id = '" . (int)$this->db->escape($data['tutors_to_students_id']) . "', session_date = '" . $this->db->escape($data['session_date']) . "', session_duration = '" . $this->db->escape($data['session_duration']) . "', session_notes = '" . $this->db->escape($data['session_notes']) . "' WHERE session_id = '" . (int)$session_id . "' ");
	}
	
	public function confirmSubmission($session_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sessions` SET date_submission = now(), is_locked = '1' WHERE session_id = '" . (int)$session_id . "' ");
	}
	
	public function deleteSession($session_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sessions WHERE session_id = '" . (int)$session_id . "'");
	}
	
	public function getSession($session_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sessions WHERE session_id = '$session_id'");
		return $query->row;
	}
		
	public function getSessions($data = array()) {
		$sql = "SELECT s.*, CONCAT(st.firstname, ' ', st.lastname) AS student_name FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id LEFT JOIN " . DB_PREFIX . "user st ON a.students_id = st.user_id ";

		$implode = array();
		
		if (isset($data['is_locked']) && !is_null($data['is_locked'])) {
			$implode[] = "s.is_locked = '" . $this->db->escape($data['is_locked']) . "'";
		}
		
		if (isset($data['filter_session_date']) && !is_null($data['filter_session_date'])) {
			$implode[] = "s.session_date = '" . $this->db->escape($data['filter_session_date']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_session_duration']) && !is_null($data['filter_session_duration'])) {
			$implode[] = "s.session_duration = '" . $this->db->escape($data['filter_session_duration']) . "' ";
		}
		
		if (isset($data['filter_session_notes']) && !is_null($data['filter_session_notes'])) {
			$implode[] = "s.session_notes LIKE '%" . $this->db->escape($data['filter_session_notes']) . "%'";
		}
		$sql .= " WHERE a.tutors_id = '".$this->session->data['user_id']."' ";
		if ($implode) {
			$sql .=" AND ". implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'session_date',
			'student_name',
			'session_notes',
			'session_duration'
		);	
			
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
		
	public function getTotalSessions($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sessions s LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON s.tutors_to_students_id = a.tutors_to_students_id LEFT JOIN " . DB_PREFIX . "user st ON a.students_id = st.user_id ";
		
		$implode = array();
		
		if (isset($data['is_locked']) && !is_null($data['is_locked'])) {
			$implode[] = "s.is_locked = '" . $this->db->escape($data['is_locked']) . "'";
		}
		
		if (isset($data['filter_session_date']) && !is_null($data['filter_session_date'])) {
			$implode[] = "s.session_date = '" . $this->db->escape($data['filter_session_date']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_session_duration']) && !is_null($data['filter_session_duration'])) {
			$implode[] = "s.session_duration = '" . $this->db->escape($data['filter_session_duration']) . "' ";
		}
		
		if (isset($data['filter_session_notes']) && !is_null($data['filter_session_notes'])) {
			$implode[] = "s.session_notes LIKE '%" . $this->db->escape($data['filter_session_notes']) . "%'";
		}
		$sql .= " WHERE a.tutors_id = '".$this->session->data['user_id']."' ";
		if ($implode) {
			$sql .= " AND ".implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function validateSession($session_date, $session_duration, $tutors_to_students_id, $session_id = "") {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."sessions WHERE session_date = '".$session_date."' AND  session_duration = '".$session_duration."' AND  tutors_to_students_id = '".$tutors_to_students_id."' AND session_id<>'".$session_id."'");
		return $query->row['total'];
	}
	
	public function getAllDurations(){
		$duration_array = array(
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
						  "5.00"=>"5 Hours");
			return $duration_array;
	}
	
}
?>