<?php
class ModelCmsEmailTemplates extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "broadcasts SET " .
				"broadcasts_title = '" . $this->db->escape($data['title']) .
				"', broadcasts_subject = '" . $this->db->escape($data['subject']) .
				"', broadcasts_content = '" . $this->db->escape($data['template']) . 
				"', status = '" . (int)$data['status'] . 
				"'");

		$broadcasts_id = $this->db->getLastId(); 
				
		return $broadcasts_id;
	}
	
	public function editInformation($broadcasts_id, $data) {
		$result = $this->db->query("UPDATE " . DB_PREFIX . "broadcasts SET " .
				"broadcasts_title = '" . $this->db->escape($data['title']) .
				"', broadcasts_subject = '" . $this->db->escape($data['subject']) .
				"', broadcasts_content = '" . $this->db->escape($data['template']) .  
				"', status = '" . (int)$data['status'] . 
				"' WHERE broadcasts_id = '" . (int)$broadcasts_id . "'");
		
		return $result;
	}
	
	public function deleteInformation($broadcasts_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "broadcasts WHERE broadcasts_id = '" . (int)$broadcasts_id . "'");		

		return $result;
	}	

	public function getInformation($broadcasts_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "broadcasts WHERE broadcasts_id = '" . (int)$broadcasts_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "broadcasts";
		
			$sort_data = array(
				'broadcasts_title',
				'broadcasts_subject'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY broadcasts_title";	
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
		} else {
			$broadcasts_data = $this->cache->get('broadcasts.' . $this->config->get('config_language_id'));
		
			if (!$broadcasts_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "broadcasts WHERE status = '1' ORDER BY broadcasts_title");
	
				$broadcasts_data = $query->rows;
			
				$this->cache->set('broadcasts.' . $this->config->get('config_language_id'), $broadcasts_data);
			}	
	
			return $broadcasts_data;			
		}
	}
	
	
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "broadcasts");
		
		return $query->row['total'];
	}	
}
?>