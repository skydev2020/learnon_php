<?php
class ModelTutorAssignment extends Model {
	var $user_group_id = 2;
	public function addAssignment($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tutors_to_students` SET tutors_id = '" . $this->db->escape($data['tutors_id']) . "', students_id = '" . $this->db->escape($data['students_id']) . "', base_wage = '" . $this->db->escape($data['base_wage']) . "', base_invoice = '" . $this->db->escape($data['base_invoice']) . "', active = '" . $this->db->escape($data['active']) . "', date_added = NOW()");
      	
      	$tutors_to_students_id = $this->db->getLastId();

        //Update Tutor Subjects	
		if(isset($data['subjects']))	
		if(count($data['subjects']) > 0) {	
			foreach($data['subjects'] as $subject_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_assignments " .
					"SET subjects_id = '" . (int)$subject_id ."', tutors_to_students_id = '" . (int)$tutors_to_students_id ."'");
			}
		}
	}
	
	public function editAssignment($tutors_to_students_id, $data) {
			$this->db->query("UPDATE `" . DB_PREFIX . "tutors_to_students` SET tutors_id = '" . $this->db->escape($data['tutors_id']) . "', students_id = '" . $this->db->escape($data['students_id']) . "', base_wage = '" . $this->db->escape($data['base_wage']) . "', base_invoice = '" . $this->db->escape($data['base_invoice']) . "', active = '" . $this->db->escape($data['active']) . "' WHERE tutors_to_students_id = '" . (int)$tutors_to_students_id . "'");
			
		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_assignments WHERE tutors_to_students_id = '" . (int)$tutors_to_students_id . "'");

        //Update Tutor Subjects	
		if(isset($data['subjects']))	
		if(count($data['subjects']) > 0) {	
			foreach($data['subjects'] as $subject_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_assignments " .
					"SET subjects_id = '" . (int)$subject_id ."', tutors_to_students_id = '" . (int)$tutors_to_students_id ."'");
			}
		}
	}
	
	public function updateTutorStatus($tutors_to_students_id, $status) {
			$this->db->query("UPDATE `" . DB_PREFIX . "tutors_to_students` SET status_by_tutor = '$status' WHERE tutors_to_students_id = '" . (int)$tutors_to_students_id . "'");
	}
	
	public function updateTutorStatusByStudent($tutors_to_students_ids, $status) {
	     foreach($tutors_to_students_ids as $tutors_to_students_id){
			$this->db->query("UPDATE `" . DB_PREFIX . "tutors_to_students` SET status_by_student = '$status' WHERE tutors_to_students_id = '" . (int)$tutors_to_students_id . "'");
		 }
	}
	
	public function deleteAssignment($tutors_to_students_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tutors_to_students WHERE tutors_to_students_id = '" . (int)$tutors_to_students_id . "'");
	}
	
	public function getAssignment($tutors_to_students_id) {
		$query = $this->db->query("SELECT a.*, CONCAT(t.firstname, ' ', t.lastname) AS tutor_name, CONCAT(s.firstname, ' ', s.lastname) AS student_name FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user s ON a.students_id = s.user_id WHERE a.tutors_to_students_id = '$tutors_to_students_id'");
		return $query->row;
	}
	
	public function getTutorDetails($tutors_to_students_id) {
		$query = $this->db->query("SELECT a.date_added, a.status_by_student, CONCAT(s.firstname, ' ', s.lastname) AS student_name, CONCAT(ui.address, '<br />', ui.city, ', ', ui.state, '<br />Postal/Zip Code - ',ui.pcode, '<br />', ui.country) AS faddress, ui.address, ui.city, ui.state, ui.pcode, ui.country, ui.cell_phone, ui.home_phone, s.email FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user s ON a.tutors_id = s.user_id LEFT JOIN " . DB_PREFIX . "user_info ui ON a.tutors_id = ui.user_id WHERE a.tutors_to_students_id = '$tutors_to_students_id'");
		return $query->row;
	}
	
	public function getStudentDetails($tutors_to_students_id) {
		$query = $this->db->query("SELECT a.base_wage, a.base_invoice, a.date_added, a.status_by_tutor, CONCAT(s.firstname, ' ', s.lastname) AS student_name, CONCAT(ui.address, '<br />', ui.city, ', ', ui.state, '<br />Postal/Zip Code - ',ui.pcode, '<br />', ui.country) AS faddress, ui.address, ui.city, ui.state, ui.pcode, ui.country, ui.cell_phone, ui.home_phone, s.email FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user s ON a.students_id = s.user_id LEFT JOIN " . DB_PREFIX . "user_info ui ON a.students_id = ui.user_id WHERE a.tutors_to_students_id = '$tutors_to_students_id'");
		return $query->row;
	}
	
	public function getAssignedSubjects($tutors_to_students_id){
		$query = $this->db->query("SELECT sub.subjects_id, sub.subjects_name FROM " . DB_PREFIX . "subjects_assignments sa LEFT JOIN " . DB_PREFIX . "subjects sub ON sa.subjects_id = sub.subjects_id WHERE sa.tutors_to_students_id = '$tutors_to_students_id'");
		return $query->rows;
	}
	
	public function getAssignedSubjectsByStuentID($students_id, $tutors_id){
		$query = $this->db->query("SELECT sub.subjects_id, sub.subjects_name FROM " . DB_PREFIX . "subjects_assignments sa LEFT JOIN " . DB_PREFIX . "subjects sub ON sa.subjects_id = sub.subjects_id LEFT JOIN " . DB_PREFIX . "tutors_to_students a ON sa.tutors_to_students_id = a.tutors_to_students_id WHERE a.students_id = '$students_id' AND a.tutors_id = '$tutors_id' ");
		$p=0; $subjects = "";
		foreach($query->rows as $subject){
			if($p)$subjects .= ", ";
			$subjects .= $subject['subjects_name'];
			$p++;
		}
		return $subjects;
	}
		
	public function getAssignments($data = array()) {
		$sql = "SELECT a.*, CONCAT(LCASE(t.firstname), ' ', LCASE(t.lastname), ' ( ', t.user_id, ' )') AS tutor_name, CONCAT(LCASE(s.firstname), ' ', LCASE(s.lastname), ' ( ', s.user_id, ' )') AS student_name FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user s ON a.students_id = s.user_id ";

		$implode = array();
		
		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = "CONCAT(t.firstname, ' ', t.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(s.firstname, ' ', s.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'tutor_name',
			'student_name',
			'date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY tutor_name";	
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
		
	public function getTotalAssignments($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user s ON a.students_id = s.user_id";
		
		$implode = array();
		
		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = "CONCAT(t.firstname, ' ', t.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(s.firstname, ' ', s.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function getStudents($data = array()) {
		$sql = "SELECT a.*, CONCAT(s.firstname, ' ', s.lastname) AS student_name FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user s ON a.students_id = s.user_id ";

		$implode = array();
		
		if (isset($data['filter_status_by_tutor']) && !is_null($data['filter_status_by_tutor'])) {
			$implode[] = " a.status_by_tutor = '" . $this->db->escape($data['filter_status_by_tutor']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(s.firstname, ' ', s.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		$sql .= " WHERE a.active = '1' AND a.tutors_id = '".$this->session->data['user_id']."' ";
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'status_by_tutor',
			'student_name',
			'date_added'
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
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	public function getTutors($data = array()) {
		$sql = "SELECT a.*, CONCAT(t.firstname, ' ', t.lastname) AS tutor_name FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id ";

		$implode = array();
		
		if (isset($data['filter_status_by_tutor']) && !is_null($data['filter_status_by_tutor'])) {
			$implode[] = " a.status_by_tutor = '" . $this->db->escape($data['filter_status_by_tutor']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(s.firstname, ' ', s.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		$sql .= " WHERE a.active = '1' AND a.students_id = '".$this->session->data['user_id']."' ";
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'status_by_tutor',
			'tutor_name',
			'date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY tutor_name";	
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
		
	public function getTotalStudents($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user s ON a.students_id = s.user_id";
		
		$implode = array();
		
		if (isset($data['filter_status_by_tutor']) && !is_null($data['filter_status_by_tutor'])) {
			$implode[] = " a.status_by_tutor = '" . $this->db->escape($data['filter_status_by_tutor']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(s.firstname, ' ', s.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		$sql .= " WHERE a.active = '1' AND a.tutors_id = '".$this->session->data['user_id']."' ";
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function getTotalTutors($data = array()) {
      	$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "tutors_to_students a LEFT JOIN " . DB_PREFIX . "user t ON a.tutors_id = t.user_id ";
		
		$implode = array();
		
		if (isset($data['filter_status_by_tutor']) && !is_null($data['filter_status_by_tutor'])) {
			$implode[] = " a.status_by_tutor = '" . $this->db->escape($data['filter_status_by_tutor']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(s.firstname, ' ', s.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		$sql .= " WHERE a.active = '1' AND a.students_id = '".$this->session->data['user_id']."' ";
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function validateAssignment($email, $user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE email = '".$email."' AND user_id<>'".$user_id."'");
		return $query->row['total'];
	}
	
}
?>