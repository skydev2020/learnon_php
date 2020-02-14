<?php
class ModelCmsSessions extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "essay_assignment SET " .
				" topic = '" . $this->db->escape($data['topic']) . 
				"', description = '" . $this->db->escape($data['description']) .
				"', format = '" . $this->db->escape($data['format']) .
				"', student_name = '" . $this->db->escape($data['student_name']) .
				"', student_id = '" . (int)$data['student_id'] . 
				"', tutor_id = '" . (int)$data['tutor_id'] .
				"', paid = '" . $this->db->escape($data['tutor_price']) .
				"', owed = '" . $this->db->escape($data['total_price']) .
				"', date_assigned = '" . $this->db->escape($data['date_assigned']) .
				"', date_completed = '" . $this->db->escape($data['date_completed']) .
				"', date_due = '" . $this->db->escape($data['due_date']) .
				"', current_status = '" . $this->db->escape($data['status']) .
				"', status = '" . (int)$data['status'] .  
				"'");

		$essay_id = $this->db->getLastId();
		
		return $essay_id; 
	}
	
	public function editInformation($essay_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "essay_assignment SET " .
				" topic = '" . $this->db->escape($data['topic']) . 
				"', description = '" . $this->db->escape($data['description']) .
				"', format = '" . $this->db->escape($data['format']) .
				"', student_name = '" . $this->db->escape($data['student_name']) .
				"', student_id = '" . (int)$data['student_id'] . 
				"', tutor_id = '" . (int)$data['tutor_id'] .
				"', paid = '" . $this->db->escape($data['tutor_price']) .
				"', owed = '" . $this->db->escape($data['total_price']) .
				"', date_assigned = '" . $this->db->escape($data['date_assigned']) .
				"', date_completed = '" . $this->db->escape($data['date_completed']) .
				"', date_due = '" . $this->db->escape($data['due_date']) .
				"', current_status = '" . $this->db->escape($data['status']) .
				"', status = '" . (int)$data['status'] . 
				"' WHERE essay_id = '" . (int)$essay_id . 
				"'");
		
		$this->cache->delete('essay_assignment');
	}
	
	public function deleteInformation($essay_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "essay_assignment WHERE essay_id = '" . (int)$essay_id . "'");
		
		return $result;
	}	

	public function getInformation($essay_id) {
		$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id) WHERE essay_id = '" . (int)$essay_id . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id)";
		
			$sort_data = array(
				'topic',
				'date_assigned'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY topic";	
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
			$essay_assignment_data = $this->cache->get('essay_assignment.' . $this->config->get('config_language_id'));
		
			if (!$essay_assignment_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "essay_assignment ORDER BY date_assigned");
	
				$essay_assignment_data = $query->rows;
			
				$this->cache->set('essay_assignment.' . $this->config->get('config_language_id'), $essay_assignment_data);
			}	
	
			return $essay_assignment_data;			
		}
	}
	
	public function getInformationDescriptions($essay_id) {
		$essay_assignment_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "essay_assignment WHERE essay_id = '" . (int)$essay_id . "'");

		foreach ($query->rows as $result) {
			$essay_assignment_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $essay_assignment_description_data;
	}
	
	public function getEssaysStatus() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "essay_assignment_status");
		
		return $query->rows;
	}
	
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "essay_assignment");
		
		return $query->row['total'];
	}	
}
?>