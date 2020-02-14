<?php
class ModelSettingGrades extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "grades SET " .
				" grades_name = '" . $this->db->escape($data['grade_name']) . 
				"'");

		$grades_id = $this->db->getLastId();

		return $grades_id; 
	}
	
	public function updateGradeSubjects($grade_id, $subjects) {
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_grades WHERE grades_id = '" . (int)$grade_id . "'");
		
		foreach($subjects as $subject_id ) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_to_grades SET " .
				" subjects_id = '" . (int)$subject_id .
				"', grades_id = '" . (int)$grade_id . 
				"'");	
		}
	}
	
	public function editInformation($grades_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "grades SET " .
				" grades_name = '" . $this->db->escape($data['grade_name']) . 
				"', status = '" . (int)$data['status'] . 
				"' WHERE grades_id = '" . (int)$grades_id . 
				"'");
	}
	
	public function deleteInformation($grades_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "grades WHERE grades_id = '" . (int)$grades_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_grades WHERE grades_id = '" . (int)$grades_id . "'");		
		return $result;
	}	

	public function getInformation($grades_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "grades WHERE grades_id = '" . (int)$grades_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "grades";
	
		$sort_data = array(
			'grades_name',
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY grades_id";	
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
	
	public function getFilteredSubjectsByGradeId($grade_id) {
      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subjects WHERE subjects_id not in (SELECT subjects_id FROM " . DB_PREFIX . "subjects_to_grades WHERE grades_id=" . (int)$grade_id. ')  ORDER BY subjects_name ASC' );
		
		return $query->rows;
	}
	
	public function getSubjectsByGradeId($grade_id) {
      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subjects sub LEFT JOIN " . DB_PREFIX . "subjects_to_grades stg ON (sub.subjects_id = stg.subjects_id) WHERE stg.grades_id=" . (int)$grade_id ." ORDER BY sub.subjects_name ASC" );
		
		return $query->rows;
	}
		
	public function getTotalInformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "grades");
		
		return $query->row['total'];
	}	
}
?>