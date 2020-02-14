<?php
class ModelStudentProfile extends Model {
	private $user_group_id = "1";

	public function addStudent($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "user " .
      			"SET username = '" . ucfirst(strtolower($this->db->escape($data['username']))) . 
				"', firstname = '" . ucfirst(strtolower($this->db->escape($data['firstname']))) . 
				"', lastname = '" . $this->db->escape($data['lastname']) . 
				"', email = '" . $this->db->escape($data['email']) .  
				"', user_group_id = '" . (int)$this->user_group_id .
				"', parent_id = '" . (int)$this->session->data['user_id'] .
				"', password = '" . $this->db->escape(md5($data['password'])) . 
				"', status = '" . (int)$data['status'] . 
				"', date_added = NOW()");
		 
		$user_id = $this->db->getLastId();
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "user_info " .
				"SET user_id = '" . (int)$user_id .
				"', grades_id = '" . (int)$data['grade_year'] .
				"', parents_first_name = '" . ucfirst(strtolower($this->db->escape($data['parent_firstname']))) .
				"', parents_last_name = '" . ucfirst(strtolower($this->db->escape($data['parent_lastname']))) .
				"', home_phone = '" . $this->db->escape($data['telephone']) .
				"', cell_phone = '" . $this->db->escape($data['cellphone']) .
				"', city = '" . ucfirst(strtolower($this->db->escape($data['city']))) .
				"', address = '" . $this->convert_address($this->db->escape($data['address'])) .
				"', pcode = '" . strtoupper($this->db->escape(strtoupper($data['postcode']))) .
				"', state = '" . $this->db->escape($data['state']) .
				"', country = '" . $this->db->escape($data['country']) .
				"', users_note = '" . $this->db->escape($data['student_note']) .
				"', major_intersection = '" . $this->db->escape($data['major_intersection']) .
				"', school = '" . $this->db->escape($data['school_name']) .
				"', referredby = '" . $this->db->escape($data['heard_aboutus']) .
				"'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_users WHERE user_id = '" . (int)$user_id . "'");

		//		Update Student Subjects
		if(count($data['subjects']) > 0) {
			foreach($data['subjects'] as $subject_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_to_users " .
					"SET user_id = '" . (int)$user_id .
					"', subjects_id = '" . (int)$subject_id .				
					"'");
			}
		}
	}

	public function changeStudentStatus($user_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "user_info SET " .
				" students_status_id = '" . (int)$data['students_status_id'] .
				"' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editStudent($user_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "user " .
				"SET username = '" . $this->db->escape($data['username']) . 
				"', firstname = '" . $this->db->escape($data['firstname']) .
				"', lastname = '" . $this->db->escape($data['lastname']) . 
				"', email = '" . $this->db->escape($data['email']) .      
				"' WHERE user_id = '" . (int)$user_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "user_info " .
				"SET grades_id = '" . (int)$data['grade_year'] .
				"', students_status_id = '" . (int)$data['student_status'] .				
				"', parents_first_name = '" . $this->db->escape($data['parent_firstname']) .
				"', parents_last_name = '" . $this->db->escape($data['parent_lastname']) .
				"', home_phone = '" . $this->db->escape($data['telephone']) .
				"', cell_phone = '" . $this->db->escape($data['cellphone']) .
				"', address = '" . $this->convert_address($this->db->escape($data['address'])) .
				"', city = '" . $this->db->escape($data['city']) .
				"', pcode = '" . $this->db->escape(strtoupper($data['postcode'])) .
				"', state = '" . $this->db->escape($data['state']) .
				"', country = '" . $this->db->escape($data['country']) .
				"', users_note = '" . $this->db->escape($data['student_note']) .
				"', major_intersection = '" . $this->db->escape($data['major_intersection']) .
				"', school = '" . $this->db->escape($data['school_name']) .
				"' WHERE user_id = '" . (int)$user_id . "'");

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

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "user " .
        	"SET password = '" . $this->db->escape(md5($data['password'])) . 
			"' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function getAddressesByStudentId($user_id) {
		$address_data = array();

		/*$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");

		$default_address_id = $query->row['address_id'];

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE user_id = '" . (int)$user_id . "'");*/

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");
				
			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}
				
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");
				
			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$code = $zone_query->row['code'];
			} else {
				$zone = '';
				$code = '';
			}

			$address_data[] = array(
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'postcode'       => strtoupper($result['postcode']),
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $code,
				'country_id'     => $result['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
		}

		return $address_data;
	}

	public function deleteStudent($user_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_users WHERE user_id = '" . (int)$user_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_info WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getGradesAndYears() {
		$grades = array();
		$query = $this->db->query("SELECT grades_id, grades_name FROM " . DB_PREFIX . "grades order by grades_id");

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

	public function checkStudent($username) {

		$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getStudent($user_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) WHERE c.user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getStudents($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, 'Student' AS user_group FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) ";

		$implode = array();

		// default parent id set to Student
		$implode[] = "c.parent_id = '" . (int)$this->session->data['user_id'] . "'";

		// default user group set to Students
		$implode[] = "c.user_group_id = '" . (int)$this->user_group_id . "'";



		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
		}

		if (isset($data['filter_city']) && !is_null($data['filter_city'])) {
			$implode[] = "city = '" . $this->db->escape($data['filter_city']) . "'";
		}

		/*if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$implode[] = "cg.user_group_id = '" . $this->db->escape($data['filter_subjects']) . "'";
			}*/

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'city',
			'c.status',
			'c.date_added'
			);
				
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY c.date_added";
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

	public function getStudentStatus() {
		$student_status = array(
			'1' => 'Resume Tutoring',
			'2' => 'Stop Tutoring',			
			'3' => 'Change Tutor',
			'4' => 'Start New Tutoring',
		);

		return $student_status;
	}

	public function approve($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "user SET approved = '1' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getStudentsByNewsletter() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE newsletter = '1' ORDER BY firstname, lastname, email");

		return $query->rows;
	}

	public function getStudentsByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' ORDER BY firstname, lastname, email");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getStudentsByProduct($product_id) {
		if ($product_id) {
			$query = $this->db->query("SELECT DISTINCT `email` FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND o.order_status_id <> '0'");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getTotalStudents($data = array()) {
		$sql = "SELECT COUNT(c.user_id) AS total FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) ";
		//      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user";

		$implode = array();

		// default user group set to Students
		$implode[] = "c.user_group_id = '" . $this->user_group_id . "'";

		// default parent id set to Student
		$implode[] = "c.parent_id = '" . (int)$this->session->data['user_id'] . "'";

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
		}

		if (isset($data['filter_city']) && !is_null($data['filter_city'])) {
			$implode[] = "city = '" . $this->db->escape($data['filter_city']) . "'";
		}

		/*if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$implode[] = "cg.user_group_id = '" . $this->db->escape($data['filter_subjects']) . "'";
			}*/

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalStudentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}

	public function getTotalAddressesByStudentId($user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE user_id = '" . (int)$user_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalStudentsByStudentGroupId($user_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
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