<?php
class ModelAccountStudent extends Model {
	private $user_group_id = "1";

	public function getMailFormat($format_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "broadcasts WHERE broadcasts_id = '" . (int)$format_id . "'");

		return $query->row;
	}

	public function getStudent($username) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $username . "'");

		return $query->row;
	}

	public function addStudent($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "user " .
      			"SET username = '" . $this->db->escape($data['username']) . 
				"', firstname = '" . ucfirst(strtolower($this->db->escape($data['firstname']))) . 
				"', lastname = '" . ucfirst(strtolower($this->db->escape($data['lastname']))) . 
				"', email = '" . $this->db->escape($data['email']) .  
				"', user_group_id = '" . $this->user_group_id . 
				"', password = '" . $this->db->escape(md5($data['password'])) . 
				"', approved = '" . (int)$data['approved'] .				
				"', status = '" . (int)$data['status'] . 
				"', date_added = NOW()".
				", ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' ");
		 
		$user_id = $this->db->getLastId();
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "user_info " .
				"SET user_id = '" . (int)$user_id .
				"', grades_id = '" . (int)$data['grade_year'] .
				"', students_status_id = '" . (int)$data['students_status_id'] .
				"', parents_first_name = '" . ucfirst(strtolower($this->db->escape($data['parent_firstname']))) .
				"', parents_last_name = '" . ucfirst(strtolower($this->db->escape($data['parent_lastname']))) .
				"', home_phone = '" . $this->db->escape($data['telephone']) .
				"', cell_phone = '" . $this->db->escape($data['cellphone']) .
				"', address = '" . $this->convert_address($this->db->escape($data['address'])) .
				"', city = '" . ucfirst(strtolower($this->db->escape($data['city']))) .
				"', pcode = '" . strtoupper($this->db->escape($data['postcode'])) .
				"', state = '" . $this->db->escape($data['state']) .
				"', country = '" . $this->db->escape($data['country']) .
				"', users_note = '" . $this->db->escape($data['student_note']) .
				"', major_intersection = '" . $this->db->escape($data['major_intersection']) .
				"', school = '" . $this->db->escape($data['school_name']) .
				"', referredby = '" . $this->db->escape($data['heard_aboutus']) .
				"', agreement = '" . $this->db->escape($data['student_agrement']) .
				"'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_users WHERE user_id = '" . (int)$user_id . "'");

		//		Update Student Subjects
		if(isset($data['subjects']))
		if(count($data['subjects']) > 0) {
			foreach($data['subjects'] as $subject_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_to_users " .
					"SET user_id = '" . (int)$user_id .
					"', subjects_id = '" . (int)$subject_id .				
					"'");
			}
		}

		return $user_id;
	}

	public function getGradesAndYears() {
		$grades = array();
		$query = $this->db->query("SELECT grades_id, grades_name FROM " . DB_PREFIX . "grades ORDER BY FIELD(grades_id,1,2,3,4,5,6,7,8,9,10,11,12,13,14,20,21,15,16,17,18,19)");

		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$grades[$each_row['grades_id']] = $each_row['grades_name'];
			};
		}

		return $grades;
	}

	public function getSubjectsByGradeId($grade_id) {
		$subjects = array();
		$query = $this->db->query("SELECT sub.subjects_id, sub.subjects_name FROM " . DB_PREFIX . "subjects sub LEFT JOIN " . DB_PREFIX . "subjects_to_grades stg ON (sub.subjects_id = stg.subjects_id) WHERE stg.grades_id = '" . (int)$grade_id . "'");

		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$subjects[$each_row['subjects_id']] = $each_row['subjects_name'];
			};
		}

		return $subjects;
	}

	public function getStudentSubjects($user_id) {
		$subjects = array();
		$query = $this->db->query("SELECT sub.subjects_id, sub.subjects_name FROM " . DB_PREFIX . "subjects sub LEFT JOIN " . DB_PREFIX . "subjects_to_users stu ON (sub.subjects_id = stu.subjects_id) WHERE stu.user_id = '" . (int)$user_id . "'");

		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$subjects[$each_row['subjects_id']] = $each_row['subjects_name'];
			};
		}

		return $subjects;
	}

	public function getStudentAgreement() {
		$information_id = 6;

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "' AND status = '1'");

		return $query->row;
	}

	public function convert_address($address)
	{
		$street_array  = array('Street',
'Avenue',
'Boulevard',
'Place',
'Court',
'Crescent',
'Circle',
'Parkway',
'Drive',
'Road',
		);

		$street_rep_array  = array('St.',
'Ave.',
'Blvd.',
'Plc.',
'Ct.',
'Cres.',
'Crl.',
'Pkwy.',
'Dr.',
'Rd.',
		);

		return str_replace($street_array,$street_rep_array,ucwords($address));

	}
}
?>