<?php
class ModelTutorEssays extends Model {

	//Softronikx Technology - added function addAttachmentInformation - 13th Sep, 2013
	public function addAttachmentInformation($essay_id, $file_name) {
	
		$this->db->query("INSERT INTO " . DB_PREFIX . " essay_assignment_attachments (essay_id, assignment_name) values ('" . $this->db->escape($essay_id) ."', '" . $this->db->escape($file_name) ."')");
		
	}

	//Softronikx Technology - added function getAttachmentInformation - 13th Sep, 2013
	public function getAttachmentInformation($essay_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "essay_assignment_attachments  WHERE essay_id = '" . (int)$essay_id . "'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function editInformation($essay_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "essay_assignment SET date_completed = '" . $this->db->escape($data['date_completed']) ."', current_status = '" . $this->db->escape($data['status']) ."', status = '" . (int)$data['status'] ."' WHERE essay_id = '" . (int)$essay_id."'");
	}

	public function getInformation($essay_id) {
		$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id) WHERE essay_id = '" . (int)$essay_id . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id) WHERE ea.tutor_id = '".$this->session->data['user_id']."' ";
		
			$sort_data = array(
				'topic',
				'date_assigned',
				'assignment_num' //softronikx technologies
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
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "essay_assignment_status WHERE essay_status_id IN (1,3,4)");
		return $query->rows;
	}
	
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "essay_assignment WHERE tutor_id = '".$this->session->data['user_id']."'");
		return $query->row['total'];
	}	
}
?>