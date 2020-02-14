<?php
class ModelSettingSubjects extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "subjects SET " .
				" subjects_name = '" . $this->db->escape($data['subject_name']) .  
				"'");

		$subjects_id = $this->db->getLastId();

		return $subjects_id; 
	}
	
	public function editInformation($subjects_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "subjects SET " .
				" subjects_name = '" . $this->db->escape($data['subject_name']) . 
				"', status = '" . (int)$data['status'] . 
				"' WHERE subjects_id = '" . (int)$subjects_id . 
				"'");
	}
	
	public function deleteInformation($subjects_id) {
		
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "subjects WHERE subjects_id in (" . (int)$subjects_id . ")");
		
		return $result;
	}	

	public function getInformation($subjects_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subjects WHERE subjects_id = '" . (int)$subjects_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "subjects";
	
		$sort_data = array(
			'subjects_name'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY subjects_name";	
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
	
		
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "subjects");
		
		return $query->row['total'];
	}	
}
?>