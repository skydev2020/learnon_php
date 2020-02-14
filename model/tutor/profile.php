<?php
class ModelTutorProfile extends Model {

	public function editTutor($user_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['email']) . "', firstname = '" . ucfirst(strtolower($this->db->escape($data['firstname']))) . "', lastname = '" . ucfirst(strtolower($this->db->escape($data['lastname']))) . "', email = '" . $this->db->escape($data['email']) . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
			
		$this->db->query("UPDATE `" . DB_PREFIX . "user_info` SET country = '" . $this->db->escape($data['country']) . "', pcode = '" . $this->db->escape(strtoupper($data['pcode'])) . "', address = '" . $this->convert_address($this->db->escape($data['address'])) . "', city = '" . ucfirst(strtolower($this->db->escape($data['city']))) . "', state = '" . $this->db->escape($data['state']) . "', home_phone = '" . $this->db->escape($data['home_phone']) . "', cell_phone = '" . $this->db->escape($data['cell_phone']) . "', users_note = '" . $this->db->escape($data['users_note']) . "', post_secondary_education = '" . $this->db->escape($data['post_secondary_education']) . "', subjects_studied = '" . $this->db->escape($data['subjects_studied']) . "', courses_available = '" . $this->db->escape($data['courses_available']) . "', previous_experience = '" . $this->db->escape($data['previous_experience']) . "', cities = '" . $this->db->escape($data['cities']) . "', `references` = '" . $this->db->escape($data['references']) . "', gender = '" . $this->db->escape($data['gender']) . "', certified_teacher = '" . $this->db->escape($data['certified_teacher']) . "', criminal_conviction = '" . $this->db->escape($data['criminal_conviction']) . "', background_check = '" . $this->db->escape($data['background_check']) . "' WHERE user_id = '" . $user_id . "' ");

		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_users WHERE user_id = '" . (int)$user_id . "'");

		//Update Tutor Subjects
		if(isset($data['subjects']))
		if(count($data['subjects']) > 0) {
				
			foreach($data['subjects'] as $subject_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_to_users " .
					"SET user_id = '" . (int)$user_id .
					"', subjects_id = '" . (int)$subject_id .				
					"'");
			}
		}

	}
		
	public function getTutor($user_id) {
		$query = $this->db->query("SELECT DISTINCT u.*, ui.* FROM " . DB_PREFIX . "user AS u LEFT JOIN user_info AS ui ON u.user_id = ui.user_id WHERE u.user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function validateEmail($email, $user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE email = '".$email."' AND user_id<>'".$user_id."'");
		return $query->row['total'];
	}

	public function validateUsername($username, $user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE username = '".$username."' AND user_id<>'".$user_id."'");
		return $query->row['total'];
	}

	public function getAllSubjects() {
		$subjects = array();
		$query = $this->db->query("SELECT subjects_id, subjects_name FROM " . DB_PREFIX . "subjects ");

		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$subjects[$each_row['subjects_id']] = $each_row['subjects_name'];
			};
		}

		return $subjects;
	}

	public function getTutorSubjects($user_id) {
		$subjects = array();
		$query = $this->db->query("SELECT sub.subjects_id, sub.subjects_name FROM " . DB_PREFIX . "subjects sub LEFT JOIN " . DB_PREFIX . "subjects_to_users stu ON (sub.subjects_id = stu.subjects_id) WHERE stu.user_id = '" . (int)$user_id . "'");

		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$subjects[] = $each_row['subjects_id'];
			};
		}
		return $subjects;
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
