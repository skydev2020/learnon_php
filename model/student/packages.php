<?php
class ModelStudentPackages extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "packages SET " .
				" package_name = '" . $this->db->escape($data['name']) .
				"', hours = '" . (int)$data['hours'] .
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
	
	public function getPackagePrice($user_id, $package_id) {
		$package_info = $this->getInformation($package_id);
		
		$this->load->model('user/students');
		$user_info = $this->model_user_students->getStudent($user_id);
		
		switch($user_info['country']) {
			case 'Canada':
				if($user_info['state'] == 'Alberta' || $user_info['state'] == 'AB' )
					return $package_info['price_alb'];
				else
					return $package_info['price_can'];
			case 'USA':
				return $package_info['price_usa'];
			default :
				return $package_info['price_usa'];
		}		
	}

	public function getInformation($package_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "packages WHERE package_id = '" . (int)$package_id . "'");
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
			
			$curr_user = $this->session->data['user_id'];
			
			$sql = "SELECT *, pks.package_id as package_id FROM " . DB_PREFIX . "packages pks LEFT JOIN " . DB_PREFIX . "packages_to_grades ptg ON pks.package_id = ptg.package_id ";
			
			$implode = array();
			
			if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
				$implode[] = " ptg.grades_id = '" . (int)$data['filter_grade'] . "' OR pks.student_id = '". $curr_user ."'";
			}
			
			if (isset($data['filter_prepaid']) && !is_null($data['filter_prepaid'])) {
				$implode[] = " pks.prepaid = '" . (int)$data['filter_prepaid'] . "'";
			}
			
			if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
				switch($data['filter_price']) {
					case 'price_usa':
						$implode[] = " pks.price_usa != '0.00' ";
					break;
					case 'price_can':
						$implode[] = " pks.price_can != '0.00' ";
					break;
					case 'price_alb':
						$implode[] = " pks.price_alb != '0.00' ";
					break;	
				}
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
		
			$sort_data = array(
				'package_name',
				'hours'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
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
			
//			echo $sql;
			
			$query = $this->db->query($sql);
			
			return $query->rows;
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
		
		$curr_user = $this->session->data['user_id'];
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . 
				" packages pks LEFT JOIN " . DB_PREFIX . 
				" packages_to_grades ptg ON (pks.package_id = ptg.package_id) ";
		
		$implode = array();
		
			
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = " ptg.grades_id = '" . (int)$data['filter_grade'] . "' OR pks.student_id = '". $curr_user ."'";
		}
		
		if (isset($data['filter_prepaid']) && !is_null($data['filter_prepaid'])) {
			$implode[] = " pks.prepaid = '" . (int)$data['filter_prepaid'] . "'";
		}
		
		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			switch($data['filter_price']) {
				case 'price_usa':
					$implode[] = " pks.price_usa != '0.00' ";
				break;
				case 'price_can':
					$implode[] = " pks.price_can != '0.00' ";
				break;
				case 'price_alb':
					$implode[] = " pks.price_alb != '0.00' ";
				break;	
			}
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
      	
//      	echo $sql;
      	
      	$query = $this->db->query($sql);
      			
		return $query->row['total'];
	}	
}
?>