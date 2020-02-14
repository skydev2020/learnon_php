<?php
class ModelCmsCoupon extends Model {
	public function addCoupon($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET " .
      			" code = '" . $this->db->escape($data['code']) .
      			"', name = '" . $this->db->escape($data['name']) . 
				"', description = '" . $this->db->escape($data['description']) . 
				"', discount = '" . (float)$data['discount'] . 
				"', type = '" . $this->db->escape($data['type']) .  
				"', date_start = '" . $this->db->escape($data['date_start']) . 
				"', date_end = '" . $this->db->escape($data['date_end']) . 
				"', uses_total = '" . (int)$data['uses_total'] . 
				"', uses_customer = '" . (int)$data['uses_customer'] . 
				"', status = '" . (int)$data['status'] . 
				"', date_added = NOW()");

      	$coupon_id = $this->db->getLastId();
	}
	
	public function editCoupon($coupon_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET " .
				" code = '" . $this->db->escape($data['code']) .
      			"', name = '" . $this->db->escape($data['name']) . 
				"', description = '" . $this->db->escape($data['description']) . 
				"', discount = '" . (float)$data['discount'] . 
				"', type = '" . $this->db->escape($data['type']) .  
				"', date_start = '" . $this->db->escape($data['date_start']) . 
				"', date_end = '" . $this->db->escape($data['date_end']) . 
				"', uses_total = '" . (int)$data['uses_total'] . 
				"', uses_customer = '" . (int)$data['uses_customer'] . 
				"' WHERE coupon_id = '" . (int)$coupon_id . "'");
	}
	
	public function deleteCoupon($coupon_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");		
	}
	
	public function getCoupon($coupon_id) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		return $query->row;
	}
	
	public function getCouponByCode($coupon_code) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE code = '" . (int)$coupon_code . "'");
		
		return $query->row;
	}
	
	public function getCoupons($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "coupon ";
		
		$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
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
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalCoupons() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");
		
		return $query->row['total'];
	}		
}
?>