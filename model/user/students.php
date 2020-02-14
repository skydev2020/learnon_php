<?php
class ModelUserStudents extends Model {
	private $user_group_id = "1";

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
				"', date_added = NOW()");
			
		$user_id = $this->db->getLastId();
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "user_info " .
				"SET user_id = '" . (int)$user_id .
				"', grades_id = '" . (int)$data['grade_year'] .
				"', students_status_id = '" . (int)$data['student_status'] .
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

	public function editStudent($user_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "user " .
				"SET username = '" . $this->db->escape($data['username']) . 
				"', firstname = '" . ucfirst(strtolower($this->db->escape($data['firstname']))) .
				"', lastname = '" . ucfirst(strtolower($this->db->escape($data['lastname']))) . 
				"', email = '" . $this->db->escape($data['email']) .
				"', approved = '" . (int)$data['approved'] .      
				"', status = '" . (int)$data['status'] . 
				"' WHERE user_id = '" . (int)$user_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "user_info " .
				"SET grades_id = '" . (int)$data['grade_year'] .
				"', students_status_id = '" . (int)$data['student_status'] .
				"', parents_first_name = '" . ucfirst(strtolower($this->db->escape($data['parent_firstname']))) .
				"', parents_last_name = '" . ucfirst(strtolower($this->db->escape($data['parent_lastname']))) .
				"', home_phone = '" . $this->db->escape($data['telephone']) .
				"', cell_phone = '" . $this->db->escape($data['cellphone']) .
				"', address = '" . $this->convert_address($this->db->escape($data['address'])) .
				"', city = '" . ucfirst(strtolower($this->db->escape($data['city']))) .
				"', pcode = '" . strtoupper( $this->db->escape($data['postcode'])) .
				"', state = '" . $this->db->escape($data['state']) .
				"', country = '" . $this->db->escape($data['country']) .
				"', users_note = '" . $this->db->escape($data['student_note']) .
				"', major_intersection = '" . $this->db->escape($data['major_intersection']) .
				"', school = '" . $this->db->escape($data['school_name']) .
				"', referredby = '" . $this->db->escape($data['heard_aboutus']) .
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
			$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$result['country_id'] . "'");

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

			$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$result['zone_id'] . "'");

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
				'postcode'       => $result['postcode'],
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
	
public function getGrade($grade_id) {
		$grades = array();
		$query = $this->db->query("SELECT  grades_name FROM " . DB_PREFIX . "grades WHERE grades_id='".$grade_id."'");
		$grade = $query->row;
		return $grade['grades_name'];
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

	public function getStudent($user_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) WHERE c.user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getAllStudents($data = array()) {
		$sql = "SELECT user_id, firstname, lastname, CONCAT(LCASE(firstname), ' ', LCASE(lastname), ' ( ', user_id, ' )') AS name FROM " . DB_PREFIX . "user WHERE user_group_id='" . (int)$this->user_group_id . "'";

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		$sort_data = array(
			'name',
			'email',
			'status',
			'date_added'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY date_added";
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

	public function getStudents($data = array(), $select = "") {

		if(!empty($select)) {
			$sql = "SELECT $select FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id)";
			//echo $sql;
		} else {
			$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, 'Student' AS user_group, c.user_id as user_id FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id)";
		}

		if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "subjects_to_users su ON (su.user_id=c.user_id)";
		}

		$implode = array();

		// default user group set to Students
			
		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {

			$sql .= " LEFT JOIN " . DB_PREFIX . "subjects_to_users su ON (su.user_id=c.user_id)";

			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";

			$implode[] = "c.username LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "city LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "state LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "country LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "parents_first_name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "parents_last_name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "home_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "cell_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "address LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "pcode LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "users_note LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "major_intersection LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "school LIKE '%" . $this->db->escape($data['filter_all']) . "%'";

			if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
				$implode[] = "(su.subjects_id IN (SELECT subjects_id FROM ".DB_PREFIX."subjects s WHERE s.subjects_name LIKE '%" . $this->db->escape($data['filter_subjects']) . "%') )";
			}

			if ($implode) {
				$sql .= " WHERE c.user_group_id = '" . $this->user_group_id . "' AND ( " . implode(" OR ", $implode) ." )";
			}
		} else{

			$implode[] = "c.user_group_id = '" . $this->user_group_id . "'";

			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}

			if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
				$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
			}

			if (isset($data['filter_city']) && !is_null($data['filter_city'])) {
				$implode[] = "city = '" . $this->db->escape($data['filter_city']) . "'";
			}
			if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
				$implode[] = "(su.subjects_id IN (SELECT subjects_id FROM ".DB_PREFIX."subjects s WHERE s.subjects_name LIKE '%" . $this->db->escape($data['filter_subjects']) . "%') )";
			}

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "ui.students_status_id = '" . (int)$data['filter_status'] . "'";
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
		}

		$sql .= " GROUP BY c.user_id ";

		$sort_data = array(
			'name',
			'c.user_id',
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

			//echo $sql;

			$query = $this->db->query($sql);

			return $query->rows;
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

	public function getStudentStatus() {
		$student_status = array(
			'1' => 'Need Tutoring',
			'2' => 'Stop Tutoring',
			'3' => 'Change Tutor',
			'4' => 'Start New Tutoring',
		);

		return $student_status;
	}

	public function getStudentsByProduct($product_id) {
		if ($product_id) {
			$query = $this->db->query("SELECT DISTINCT email FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND o.order_status_id <> '0'");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getTotalStudents($data = array()) {
		$sql = "SELECT COUNT(c.user_id) AS total FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id)";
		if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "subjects_to_users su ON (su.user_id=c.user_id)";
		}
		//      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user";

		$implode = array();

		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "subjects_to_users su ON (su.user_id=c.user_id)";
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";

			$implode[] = "c.username LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "city LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "state LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "country LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "parents_first_name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "parents_last_name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "home_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "cell_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "address LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "pcode LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "users_note LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "major_intersection LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "school LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
				$implode[] = "(su.subjects_id IN (SELECT subjects_id FROM ".DB_PREFIX."subjects s WHERE s.subjects_name LIKE '%" . $this->db->escape($data['filter_subjects']) . "%') )";
			}
			if ($implode) {
				$sql .= " WHERE c.user_group_id = '" . $this->user_group_id . "' AND ( " . implode(" OR ", $implode) ." )";
			}
		} else{
			// default user group set to Students
			$implode[] = "c.user_group_id = '" . $this->user_group_id . "'";

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
			if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
				$implode[] = "(su.subjects_id IN (SELECT subjects_id FROM ".DB_PREFIX."subjects s WHERE s.subjects_name LIKE '%" . $this->db->escape($data['filter_subjects']) . "%') )";
			}

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "ui.students_status_id = '" . (int)$data['filter_status'] . "'";
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

		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalStudentsCurrentYear($date_arr) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE user_group_id = '" . $this->user_group_id . "' AND date_added >= '". $date_arr['start_date']."' AND date_added <= '".$date_arr['end_date']."' " );
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

	public function getStudentReport() {
		$sql = "SELECT u.user_id, CONCAT_WS(' ',firstname,lastname) as name , email , city
					FROM user u, user_info ui WHERE u.user_id = ui.user_id AND u.user_group_id =1 GROUP BY u.user_id";
		$query = $this->db->query($sql);
		$final_result = array();
		$results = $query->rows;
		foreach($results as $result)
		{
			$sql = "SELECT SUM( total_hours ) as th , SUM( total_amount ) as ta
						FROM student_invoice WHERE student_id = '".$result['user_id']."'";
			$query = $this->db->query($sql);
			$row = $query->row;
			$total_hours =  (float)$row['th'] ;
			$total_amount = (float)$row['ta'] ;

			$sql = "SELECT SUM( total_hours ) as th ,  SUM( total ) as ta
						FROM `order` WHERE customer_id = '".$result['user_id']."'
						AND package_id<>0 AND order_status_id = '5'";
			$query = $this->db->query($sql);
			$row = $query->row;
			$total_hours =  $total_hours+(float)$row['th'] ;
			$total_amount = $total_amount+(float)$row['ta'] ;

			$sql = "SELECT SUM( base_wage * session_duration ) as tr FROM tutors_to_students ts, sessions s
						WHERE ts.tutors_to_students_id = s.tutors_to_students_id AND ts.students_id ='".$result['user_id']."'";
			$query = $this->db->query($sql);
			$row = $query->row;
			$total_revenues =  (float)$row['tr'];

			$final_result[] = array("Id"=>$result['user_id'],"Student Name"=>$result['name'],"Email"=>$result['email'],"City"=>$result['city'],"Total Hours"=>$total_hours,"Total Revenues"=>$total_amount,"Total Profit"=>$total_amount-$total_revenues);
		}
		return $final_result;
	}

	public function getTutorReport() {
		$sql = "SELECT u.user_id, CONCAT_WS(' ',firstname,lastname) as name , email , city
					FROM user u, user_info ui WHERE u.user_id = ui.user_id AND u.user_group_id =2 GROUP BY u.user_id";
		$query = $this->db->query($sql);
		$final_result = array();
		$results = $query->rows;
		foreach($results as $result)
		{
			$sql = "SELECT COUNT(students_id) as students_tutored FROM tutors_to_students ts, sessions s WHERE
					ts.tutors_to_students_id = s.tutors_to_students_id AND tutors_id='".$result['user_id']."'";
			$query = $this->db->query($sql);
			$row = $query->row;
			$total_students_tutored =  (float)$row['students_tutored'] ;

			$sql = "SELECT SUM( session_duration ) as total_hours FROM tutors_to_students ts, sessions s
					WHERE ts.tutors_to_students_id = s.tutors_to_students_id AND ts.tutors_id ='".$result['user_id']."'";
			$query = $this->db->query($sql);
			$row = $query->row;
			$total_hours_tutored =  (float)$row['total_hours'] ;
				
			$tutor_avg_hours = $total_hours_tutored/$total_students_tutored;

			$sql = "SELECT DATEDIFF( MAX( s.session_date ) , MIN( s.session_date ) ) as td , ts.students_id
					FROM tutors_to_students ts, sessions s
					WHERE ts.tutors_to_students_id = s.tutors_to_students_id AND ts.tutors_id ='".$result['user_id']."'
					GROUP BY ts.students_id";
			$query = $this->db->query($sql);
			$rows = $query->rows;
			$total_sessions_duration = 0;
			foreach($rows as $row)
			{
				$total_sessions_duration = (float)$total_sessions_duration+(float)$row['td'];
			}
			$total_duration =  $total_sessions_duration/$total_students_tutored;

			$final_result[] = array("Id"=>$result['user_id'],"Tutor Name"=>$result['name'],"Email"=>$result['email'],"Students Tutored"=>$total_students_tutored,"Hours Tutored"=>$total_hours_tutored,"Avg Hours Per Student"=>$tutor_avg_hours,"Average Duration Per Student"=>$total_duration);
		}
		return $final_result;
	}
}
?>