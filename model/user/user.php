<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` " .
				" SET username = '" . $this->db->escape($data['username']) . 
				"', password = '" . $this->db->escape(md5($data['password'])) . 
				"', firstname = '" . $this->db->escape($data['firstname']) . 
				"', lastname = '" . $this->db->escape($data['lastname']) . 
				"', email = '" . $this->db->escape($data['email']) . 
				"', user_group_id = '" . (int)$data['user_group_id'] .
				"', approved = '" . (int)$data['approved'] . 
				"', status = '" . (int)$data['status'] . 
				"', date_added = NOW()");
		
		$user_id = $this->db->getLastId();
      	
      	$this->db->query("INSERT INTO " . DB_PREFIX . "user_info " .
				"SET user_id = '" . (int)$user_id .
				"', grades_id = '" . (int)$data['grade_year'] .
				"', parents_first_name = '" . $this->db->escape($data['parent_firstname']) .
				"', parents_last_name = '" . $this->db->escape($data['parent_lastname']) .
				"', home_phone = '" . $this->db->escape($data['telephone']) .
				"', cell_phone = '" . $this->db->escape($data['cellphone']) .
				"', address = '" . $this->db->escape($data['address']) .
				"', city = '" . $this->db->escape($data['city']) .
				"', pcode = '" . $this->db->escape($data['postcode']) .
				"', state = '" . $this->db->escape($data['state']) .
				"', country = '" . $this->db->escape($data['country']) .
				"', users_note = '" . $this->db->escape($data['student_note']) .
				"', major_intersection = '" . $this->db->escape($data['major_intersection']) .
				"', school = '" . $this->db->escape($data['school_name']) .
				"', referredby = '" . $this->db->escape($data['heard_aboutus']) .
				"'");
	}
	
	public function updateUserProfile($user_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` " .
				" SET username = '" . $this->db->escape($data['username']) . 
				"', firstname = '" . $this->db->escape($data['firstname']) . 
				"', lastname = '" . $this->db->escape($data['lastname']) . 
				"', email = '" . $this->db->escape($data['email']) . 
				"' WHERE user_id = '" . (int)$user_id . "'");
		
		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}
	
	public function editUser($user_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` " .
				" SET username = '" . $this->db->escape($data['username']) . 
				"', firstname = '" . $this->db->escape($data['firstname']) . 
				"', lastname = '" . $this->db->escape($data['lastname']) . 
				"', email = '" . $this->db->escape($data['email']) . 
				"', user_group_id = '" . (int)$data['user_group_id'] .
				"', approved = '" . (int)$data['approved'] . 
				"', status = '" . (int)$data['status'] . 
				"' WHERE user_id = '" . (int)$user_id . "'");
		
		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}
	
	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_info WHERE user_id = '" . (int)$user_id . "'");
	}
	
	public function checkUser($username) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");
	
		return $query->row;
	}
	
	public function getUser($user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	
		return $query->row;
	}
	
	public function getUsers($data = array()) {
		
		if($data['sort'] == 'groupname')
			$data['sort'] = 'ug.name';
		
		$sql = "SELECT u.*, ug.name as groupname FROM `" . DB_PREFIX . "user` u LEFT JOIN `" . DB_PREFIX . "user_group` ug ON (u.user_group_id = ug.user_group_id) ";
			
		$sort_data = array(
			'username',
			'ug.name',
			'status',
			'date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY username";	
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

	public function getTotalUsers() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");
		
		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");
		return $query->row['total'];
	}
	
public function getUpdateUsers() {
		
	$query = $this->db->query("SELECT u.user_id,firstname, lastname, address, state, pcode from user u, user_info ui where u.user_id = ui.user_id"); 
			
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
	
		$results = $query->rows;
		
		foreach ($results as $result) {
			$user_id = $result['user_id'];
			$firstname = ucfirst(strtolower($result['firstname']));
			$lastname = ucfirst(strtolower($result['lastname']));
			$address = ucwords($result['address']);
			$state = $result['state'];
			$pcode = strtoupper($result['pcode']);
			
			if(strlen($pcode)==6)
			{
				$pcode = substr_replace($pcode, ' ', 3, 0);
				echo $pcode;
			}
			
			$query = $this->db->query("SELECT code FROM zone WHERE name='$state'");
			$state = $query->row['code'];
			
			$address = str_replace($street_array,$street_rep_array,$address);
			
			$this->db->query("UPDATE user set firstname = '$firstname', lastname='$lastname' WHERE user_id = $user_id");
			echo "UPDATE user set firstname = '$firstname', lastname='$lastname' WHERE user_id = $user_id";
			echo "UPDATE user_info set address = '$address', state='$state',pcode='$pcode' WHERE user_id = $user_id";
			echo "<br/>";
			$this->db->query("UPDATE user_info set address = '$address', state='$state',pcode='$pcode' WHERE user_id = $user_id"); 
		}
			
	}
	
}
?>