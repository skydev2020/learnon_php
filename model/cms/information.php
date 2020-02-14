<?php
class ModelCmsInformation extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information SET " .
				" title = '" . $this->db->escape($data['title']) . 
				"', description = '" . $this->db->escape($data['description']) . 
				"', sort_order = '" . (int)$data['sort_order'] .
				"', status = '" . (int)$data['status'] . 
				"'");

		$information_id = $this->db->getLastId();
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		} 
			
		return $information_id; 
	}
	
	public function editInformation($information_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "information SET " .
				" title = '" . $this->db->escape($data['title']) . 
				"', description = '" . $this->db->escape($data['description']) . 
				"', sort_order = '" . (int)$data['sort_order'] . 
				"', status = '" . (int)$data['status'] . 
				"' WHERE information_id = '" . (int)$information_id . 
				"'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('information');
	}
	
	public function deleteInformation($information_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "'");
		
		return $result;
	}	

	public function getInformation($information_id) {
		$query = $this->db->query("SELECT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS keyword FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "information";
		
			$sort_data = array(
				'title',
				'sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
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
			$information_data = $this->cache->get('information.' . $this->config->get('config_language_id'));
		
			if (!$information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information ORDER BY title");
	
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->config->get('config_language_id'), $information_data);
			}	
	
			return $information_data;			
		}
	}
	
	public function getInformationDescriptions($information_id) {
		$information_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $information_description_data;
	}
	
	public function getInformationStores($information_id) {
		$information_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_store_data[] = $result['store_id'];
		}
		
		return $information_store_data;
	}
	
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information");
		
		return $query->row['total'];
	}	
}
?>