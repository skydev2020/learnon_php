<?php
class ModelCmsGrades extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "grades SET " .
				" grades_name = '" . $this->db->escape($data['name']) . 
				"', price_usa = '" . $this->db->escape($data['price_usa']) .
				"', price_alb = '" . $this->db->escape($data['price_alb']) .
				"', price_can = '" . $this->db->escape($data['price_can']) .
				"', status = '" . (int)$data['status'] . 
				"'");

		$grades_id = $this->db->getLastId();
		
		// Delete all Relations of Subjects to Grades 		
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_grades WHERE grades_id = '" . (int)$grades_id . "'");
		// Make New Relations of Subjects to Grades
		if(isset($data['subjects']))
		foreach($data['subjects'] as $subjects_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_to_grades SET " .
							" subjects_id = '" . (int)$subjects_id .
							"', grades_id = '" . (int)$grades_id .									 
							"'");				
		}
		
		return $grades_id; 
	}
	
	public function editInformation($grades_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "grades SET " .
				" grades_name = '" . $this->db->escape($data['name']) .
				"', price_usa = '" . $this->db->escape($data['price_usa']) .
				"', price_alb = '" . $this->db->escape($data['price_alb']) .
				"', price_can = '" . $this->db->escape($data['price_can']) .
				"', status = '" . (int)$data['status'] .  
				"' WHERE grades_id = '" . (int)$grades_id . 
				"'");
				
		// Delete all Relations of Subjects to Grades 		
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "subjects_to_grades WHERE grades_id = '" . (int)$grades_id . "'");
		// Make New Relations of Subjects to Grades
		if(isset($data['subjects']))
		foreach($data['subjects'] as $subjects_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "subjects_to_grades SET " .
							" subjects_id = '" . (int)$subjects_id .
							"', grades_id = '" . (int)$grades_id .									 
							"'");				
		}
	}
	
	public function deleteInformation($grades_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "grades WHERE grades_id = '" . (int)$grades_id . "'");
		
		return $result;
	}	

	public function getInformation($grades_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "grades WHERE grades_id = '" . (int)$grades_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "grades ";
		
		
		$implode = array();
		
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = " grades_id = '" . (int)$data['filter_grade'] . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
	
		$sort_data = array(
			'grades_id',
			'grades_name',
			'price_can',
			'price_usa',
			'price_alb'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY grades_name";
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
	
	public function getAllSubjects() {
		$all_subjects = array();
				
      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subjects order by subjects_name");
      	
		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$all_subjects[$each_row['subjects_id']] = $each_row['subjects_name'];
			};
		}
		
		return $all_subjects;
	}
	
	public function getSubjectsByGrades($grades_id) {
		$all_subjects = array();
      	$query = $this->db->query("SELECT s.* FROM " . DB_PREFIX . "subjects_to_grades stg LEFT JOIN subjects s ON stg.subjects_id = s.subjects_id WHERE stg.grades_id = '" . (int)$grades_id . "'");
		
		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$all_subjects[$each_row['subjects_id']] = $each_row['subjects_name'];
			};
		}
		
		return $all_subjects;
	}
	
	public function getTotalInformations($data = array()) {
		
		$implode = array();
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "grades";
		
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = " grades_id = '" . (int)$data['filter_grade'] . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
      	
      	$query = $this->db->query($sql);
      			
		return $query->row['total'];
	}	
}
?>