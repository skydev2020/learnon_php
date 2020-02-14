<?php
class ModelSaleUser extends Model {
	public function addUser($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "user SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', user_group_id = '" . (int)$data['user_group_id'] . "', password = '" . $this->db->escape(md5($data['password'])) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
      	
      	$user_id = $this->db->getLastId();
      	
      	if (isset($data['addresses'])) {		
      		foreach ($data['addresses'] as $address) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET user_id = '" . (int)$user_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();
					
					$this->db->query("UPDATE " . DB_PREFIX . "user SET address_id = '" . $address_id . "' WHERE user_id = '" . (int)$user_id . "'");
				}
			}
		}
	}
	
	public function editUser($user_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "user SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', user_group_id = '" . (int)$data['user_group_id'] . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");
	
      	if ($data['password']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "user SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
      	}
      	
      	$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE user_id = '" . (int)$user_id . "'");
      	
      	if (isset($data['addresses'])) {
      		foreach ($data['addresses'] as $address) {	
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET user_id = '" . (int)$user_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();
					
					$this->db->query("UPDATE " . DB_PREFIX . "user SET address_id = '" . $address_id . "' WHERE user_id = '" . (int)$user_id . "'");
				}
			}
		}
	}
	
	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE user_id = '" . (int)$user_id . "'");
	}
	
	public function getUser($user_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
	
		return $query->row;
	}
		
	public function getUsers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS user_group FROM " . DB_PREFIX . "user c LEFT JOIN " . DB_PREFIX . "user_group cg ON (c.user_group_id = cg.user_group_id) ";

		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
		}
		
		if (isset($data['filter_user_group_id']) && !is_null($data['filter_user_group_id'])) {
			$implode[] = "cg.user_group_id = '" . $this->db->escape($data['filter_user_group_id']) . "'";
		}
		
		if (isset($data['filter_active_tutors']) && !empty($data['filter_active_tutors'])) {
			$implode[] = " c.user_id in (select distinct tutors_id from  tutors_to_students 
where active=1) ";
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
			$sql .= " WHERE " . implode(" AND ", $implode);
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
			$sql .= " ORDER BY name";	
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
	
	public function getUsersByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' ORDER BY firstname, lastname, email");
	
			return $query->rows;
		} else {
			return array();	
		}
	}
		
	public function getTotalUsers($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user";
		
		$implode = array();
		
		if (isset($data['filter_user_group_id']) && !is_null($data['filter_user_group_id'])) {
			$implode[] = "user_group_id = '" . (int)$data['filter_user_group_id'] . "'";
		}
		
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
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function getTotalUsersByUserGroupId($user_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		return $query->row['total'];
	}	
}
?>