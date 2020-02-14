<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
      	
		// Properly format customer details with Title case
		if (function_exists('mb_convert_case')) {
			$data['company'] 	= trim($data['company']);
			$data['firstname'] 	= mb_convert_case(trim($data['firstname']), MB_CASE_TITLE, 'UTF-8');
			$data['lastname'] 	= mb_convert_case(trim($data['lastname']), MB_CASE_TITLE, 'UTF-8');
			$data['address_1'] 	= mb_convert_case(trim($data['address_1']), MB_CASE_TITLE,'UTF-8');
			$data['address_2'] 	= mb_convert_case(trim($data['address_2']), MB_CASE_TITLE,'UTF-8');
			$data['city'] 		= mb_convert_case(trim($data['city']), MB_CASE_TITLE, 'UTF-8');
			$data['postcode'] 	= mb_convert_case(trim($data['postcode']), MB_CASE_TITLE, 'UTF-8');
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "', status = '1', date_added = NOW()");
      	
		$customer_id = $this->db->getLastId();
			
      	$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");
		
		$address_id = $this->db->getLastId();

      	$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (!$this->config->get('config_customer_approval')) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");
		}		
	}
	
	public function editCustomer($data) {
		$data['firstname'] = ucwords(strtolower(trim($data['firstname'])));
		$data['lastname'] = ucwords(strtolower(trim($data['lastname'])));
		
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function editPassword($email, $password) {
      	$this->db->query("UPDATE " . DB_PREFIX . "user SET password = '" . $this->db->escape(md5($password)) . "' WHERE email = '" . $this->db->escape($email) . "'");
		$query = $this->db->query("SELECT user_id, user_group_id FROM " . DB_PREFIX . "user WHERE email = '" . $this->db->escape($email) . "'");
		log_activity("Forgot Password", "Password reset done.", $query->row['user_id'], $query->row['user_group_id']);
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}
			
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row;
	}
	
	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE LCASE(email) = '" . $this->db->escape(strtolower($email)) . "'");
		
		return $query->row['total'];
	}
}
?>