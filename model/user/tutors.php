<?php
class ModelUserTutors extends Model {
	var $user_group_id = 2;
	public function addTutor($data) {
		if($data['criminal_conviction']!="2"){
			$data['status'] = "0";
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$this->user_group_id . "', approved = '" . (int)$data['approved'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
		 
		$user_id = $this->db->getLastId();
		 
		if ($user_id!="") {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "user_info` SET country = '" . $this->db->escape($data['country']) . "', pcode = '" . $this->db->escape($data['pcode']) . "', address = '" . $this->db->escape($data['address']) . "', city = '" . $this->db->escape($data['city']) . "', state = '" . $this->db->escape($data['state']) . "', home_phone = '" . $this->db->escape($data['home_phone']) . "', cell_phone = '" . $this->db->escape($data['cell_phone']) . "', users_note = '" . $this->db->escape($data['users_note']) . "', post_secondary_education = '" . $this->db->escape($data['post_secondary_education']) . "', subjects_studied = '" . $this->db->escape($data['subjects_studied']) . "', courses_available = '" . $this->db->escape($data['courses_available']) . "', previous_experience = '" . $this->db->escape($data['previous_experience']) . "', cities = '" . $this->db->escape($data['cities']) . "', `references` = '" . $this->db->escape($data['references']) . "', gender = '" . $this->db->escape($data['gender']) . "', certified_teacher = '" . $this->db->escape($data['certified_teacher']) . "', criminal_conviction = '" . $this->db->escape($data['criminal_conviction']) . "', background_check = '" . $this->db->escape($data['background_check']) . "' ");
		}
	}

	public function editTutor($user_id, $data) {
		if($data['criminal_conviction']!="2"){
			$data['status'] = "0";
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$this->user_group_id . "', approved = '" . (int)$data['approved'] . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
			
		$this->db->query("UPDATE `" . DB_PREFIX . "user_info` SET country = '" . $this->db->escape($data['country']) . "', pcode = '" . $this->db->escape($data['pcode']) . "', address = '" . $this->db->escape($data['address']) . "', city = '" . $this->db->escape($data['city']) . "', state = '" . $this->db->escape($data['state']) . "', home_phone = '" . $this->db->escape($data['home_phone']) . "', cell_phone = '" . $this->db->escape($data['cell_phone']) . "', users_note = '" . $this->db->escape($data['users_note']) . "', post_secondary_education = '" . $this->db->escape($data['post_secondary_education']) . "', subjects_studied = '" . $this->db->escape($data['subjects_studied']) . "', courses_available = '" . $this->db->escape($data['courses_available']) . "', previous_experience = '" . $this->db->escape($data['previous_experience']) . "', cities = '" . $this->db->escape($data['cities']) . "', `references` = '" . $this->db->escape($data['references']) . "', gender = '" . $this->db->escape($data['gender']) . "', certified_teacher = '" . $this->db->escape($data['certified_teacher']) . "', criminal_conviction = '" . $this->db->escape($data['criminal_conviction']) . "', background_check = '" . $this->db->escape($data['background_check']) . "' WHERE user_id = '" . $user_id . "' ");
	}

	public function deleteTutor($user_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getTutor($user_id) {
		$query = $this->db->query("SELECT DISTINCT u.*, ui.* FROM " . DB_PREFIX . "user AS u LEFT JOIN user_info AS ui ON u.user_id = ui.user_id WHERE u.user_id = '" . (int)$user_id . "' ");

		return $query->row;
	}

	public function getAllTutors($data = array()) {
		$sql = "SELECT user_id, firstname, lastname, CONCAT(LCASE(firstname), ' ', LCASE(lastname), ' ( ', user_id, ' )') AS name FROM " . DB_PREFIX . "user WHERE user_group_id='" . (int)$this->user_group_id . "'";

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "`status` = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
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

	public function getTutors($data = array(), $select = "c.*, CONCAT(c.firstname, ' ', c.lastname) AS name") {
		$sql = "SELECT $select FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) ";

		$implode = array();

		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {
				
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				
			$implode[] = "c.username LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.state LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.country LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.address LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.pcode LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.home_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cell_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.users_note LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.post_secondary_education LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.subjects_studied LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.courses_available LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.previous_experience LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cities LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.references LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.gender LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				

			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction= '2' AND c.user_group_id = '" . $this->user_group_id . "' AND (" . implode(" OR ", $implode)." )";
			}
		} else {
			$implode[] = "c.user_group_id = '" . $this->user_group_id . "'";
				
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
				
			if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
				$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
			}
				
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
				$sql .= " WHERE ui.criminal_conviction= '2' AND " . implode(" AND ", $implode);
			}
		}

		$sort_data = array(
			'name',
			'c.user_id',
			'c.email',
			'user_group',
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

	public function getTutorsRejected($data = array(), $select = "c.*, CONCAT(c.firstname, ' ', c.lastname) AS name") {
		$sql = "SELECT $select FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id) ";

		$implode = array();

		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {
				
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				
			$implode[] = "c.username LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.state LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.country LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.address LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.pcode LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.home_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cell_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.users_note LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.post_secondary_education LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.subjects_studied LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.courses_available LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.previous_experience LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cities LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.references LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.gender LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				

			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction= '2' AND c.user_group_id = '" . $this->user_group_id . "' AND (" . implode(" OR ", $implode)." )";
			}
		} else {
			$implode[] = "c.user_group_id = '" . $this->user_group_id . "'";
				
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
				
			if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
				$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
			}
				
			if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
				$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
				
			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction!= '2' AND " . implode(" AND ", $implode);
			}
		}

		$sort_data = array(
			'name',
			'c.email',
			'user_group',
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

	public function approve($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "user SET approved = '1' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getTutorsByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' ORDER BY firstname, lastname, email");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getTotalTutors($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id)";

		$implode = array();

		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {
				
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				
			$implode[] = "c.username LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.state LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.country LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.address LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.pcode LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.home_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cell_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.users_note LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.post_secondary_education LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.subjects_studied LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.courses_available LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.previous_experience LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cities LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.references LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.gender LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				

			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction= '2' AND c.user_group_id = '" . $this->user_group_id . "' AND (" . implode(" OR ", $implode)." )";
			}
		} else {
			$implode[] = "user_group_id = '" . $this->user_group_id . "'";
				
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
				
			if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
				$implode[] = "email = '" . $this->db->escape($data['filter_email']) . "'";
			}
				
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "status = '" . (int)$data['filter_status'] . "'";
			}
				
			if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
				$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
				
			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction= '2' AND " . implode(" AND ", $implode);
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalTutorsCurrentYear($date_arr) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE user_group_id = '" . $this->user_group_id . "' AND date_added >= '". $date_arr['start_date']."' AND date_added <= '".$date_arr['end_date']."' " );
		return $query->row['total'];
	}

	public function getTotalTutorsRejected($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_info ui ON (c.user_id = ui.user_id)";

		$implode = array();
		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {
				
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				
			$implode[] = "c.username LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.state LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.country LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.address LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.pcode LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.home_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cell_phone LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.users_note LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.post_secondary_education LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.subjects_studied LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.courses_available LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.previous_experience LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.cities LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.references LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ui.gender LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
				

			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction= '2' AND c.user_group_id = '" . $this->user_group_id . "' AND (" . implode(" OR ", $implode)." )";
			}
		} else {
			$implode[] = "user_group_id = '" . $this->user_group_id . "'";
				
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
				
			if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
				$implode[] = "email = '" . $this->db->escape($data['filter_email']) . "'";
			}
				
			if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
				$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
				
			if ($implode) {
				$sql .= " WHERE ui.criminal_conviction!= '2' AND " . implode(" AND ", $implode);
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function validateEmail($email, $user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE email = '".$email."' AND user_id<>'".$user_id."'");
		return $query->row['total'];
	}

	public function validateUsername($username, $user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE username = '".$username."' AND user_id<>'".$user_id."'");
		return $query->row['total'];
	}

}
?>
