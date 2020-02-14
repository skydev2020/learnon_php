<?php
class ModelCmsPackages extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "packages SET " .
				" package_name = '" . $this->db->escape($data['name']) .
				"', hours = '" . (int)$data['hours'] .
				"', student_id = '" . (int)$data['student_id'] .				
				"', prepaid = '" . (int)$data['prepaid'] . 
				"', package_description = '" . $this->db->escape($data['description']) . 
				"', price_usa = '" . $this->db->escape($data['price_usa']) .
				"', price_alb = '" . $this->db->escape($data['price_alb']) .
				"', price_can = '" . $this->db->escape($data['price_can']) .
				"', status = '" . (int)$data['status'] . 
				"'");

		$package_id = $this->db->getLastId();
		
		// Delete all Relations of Package to Grades 		
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "packages_to_grades WHERE package_id = '" . (int)$package_id . "'");
		// Make New Relations of Package to Grades
		if(isset($data['grades']))
		if(count($data['grades']) > 0) {
			foreach($data['grades'] as $grade_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "packages_to_grades SET " .
									" package_id = '" . (int)$package_id .
									"', grades_id = '" . (int)$grade_id .									 
									"'");				
			}
		} 
			
		return $package_id; 
	}
	
	public function editInformation($package_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "packages SET " .
				" package_name = '" . $this->db->escape($data['name']) .
				"', hours = '" . (int)$data['hours'] .
				"', student_id = '" . (int)$data['student_id'] .
				"', prepaid = '" . (int)$data['prepaid'] . 
				"', package_description = '" . $this->db->escape($data['description']) . 
				"', price_usa = '" . $this->db->escape($data['price_usa']) .
				"', price_alb = '" . $this->db->escape($data['price_alb']) .
				"', price_can = '" . $this->db->escape($data['price_can']) .
				"', status = '" . (int)$data['status'] .  
				"' WHERE package_id = '" . (int)$package_id . 
				"'");
		
		// Delete all Relations of Package to Grades 		
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "packages_to_grades WHERE package_id = '" . (int)$package_id . "'");
		// Make New Relations of Package to Grades
		if(isset($data['grades']))
		if(count($data['grades']) > 0) {
			foreach($data['grades'] as $grade_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "packages_to_grades SET " .
									" package_id = '" . (int)$package_id .
									"', grades_id = '" . (int)$grade_id .									 
									"'");				
			}
		}
		
		$this->cache->delete('packages');
	}
	
	public function deleteInformation($package_id) {
		$result = $this->db->query("DELETE FROM " . DB_PREFIX . "packages WHERE package_id = '" . (int)$package_id . "'");
		
		return $result;
	}	

	public function getInformation($package_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "packages WHERE package_id = '" . (int)$package_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		if ($data) {
			$sql = "SELECT p.*, concat(u.firstname,' ',u.lastname) as student_name FROM " . DB_PREFIX . "packages p LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.student_id) ";
			
			$implode = array();
			
			if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
				$implode[] = " grades_id = '" . (int)$data['filter_grade'] . "'";
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
		
			$sort_data = array(
				'package_name',
				'hours',
				'student_id',
				'price_can',
				'price_usa',
				'price_alb'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				
				if($data['sort'] == 'student_id')
					$sql .= " ORDER BY concat(u.firstname,' ',u.lastname) ";
				else
					$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY package_name";	
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
			$packages_data = $this->cache->get('packages.' . $this->config->get('config_language_id'));
		
			if (!$packages_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "packages ORDER BY title");
	
				$packages_data = $query->rows;
			
				$this->cache->set('packages.' . $this->config->get('config_language_id'), $packages_data);
			}	
	
			return $packages_data;			
		}
	}
	
	public function getAllGrades() {
		$all_grades = array();
				
      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "grades");
      	
		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$all_grades[$each_row['grades_id']] = $each_row['grades_name'];
			};
		}
		
		return $all_grades;
	}
	
	public function getGradesByPackage($package_id) {
		$all_grades = array();
      	$query = $this->db->query("SELECT gds.* FROM " . DB_PREFIX . "grades gds LEFT JOIN (packages_to_grades ptg, packages pkg) ON (gds.grades_id = ptg.grades_id AND ptg.package_id = pkg.package_id) WHERE pkg.package_id = '" . (int)$package_id . "'");
		
		if($query->num_rows > 0) {
			foreach($query->rows as $each_row) {
				$all_grades[$each_row['grades_id']] = $each_row['grades_name'];
			};
		}
		
		return $all_grades;
	}	
	
	public function getTotalInformations($data = array()) {
		
		$implode = array();
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "packages";
		
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