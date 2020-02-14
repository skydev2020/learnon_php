<?php
class ModelCmsEssays extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "essay_assignment SET " .
				" assignment_num = '" . (int)$data['assignment_num'] . 
				"', topic = '" . $this->db->escape($data['topic']) . 
				"', description = '" . $this->db->escape($data['description']) .
				"', format = '" . $this->db->escape($data['format']) .
				"', student_name = '" . $this->db->escape($data['student_name']) .
				"', student_email = '" . $this->db->escape($data['student_email']) .
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
	
public function addInformation_csv($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "essay_assignment SET " .
				" assignment_num = '" . (int)$data['assignment_num'] . 
				"', topic = '" . $this->db->escape($data['topic']) . 
				"', description = '" . $this->db->escape($data['description']) .
				"', format = '" . $this->db->escape($data['format']) .
				"', student_name = '" . $this->db->escape($data['student_name']) .
				"', student_email = '" . $this->db->escape($data['student_email']) .
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
	
	public function getUserId($email)
	{
		$sql = "SELECT user_id FROM " . DB_PREFIX . "user WHERE username='$email' OR email='$email'";
		$query = $this->db->query($sql);
		return $query->row['user_id'];
	}
	
	public function editInformation($essay_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "essay_assignment SET " .
				" assignment_num = '" . (int)$data['assignment_num'] . 
				"', topic = '" . $this->db->escape($data['topic']) . 
				"', description = '" . $this->db->escape($data['description']) .
				"', format = '" . $this->db->escape($data['format']) .
				"', student_name = '" . $this->db->escape($data['student_name']) .
				"', student_email = '" . $this->db->escape($data['student_email']) .				
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
	
	public function getNextAssignmentNumber() {
		$sql = "SELECT max(assignment_num) as number FROM " . DB_PREFIX . "essay_assignment ";
		
		$query = $this->db->query($sql);
		
		return ($query->row['number'] + 1);
	}	

	/* Softronikx Technologies */
	public function getAssignmentStatus() {
		$assignment_status = array(
			'1' => 'Assigned',
			'2' => 'Accepted',
			'3' => 'Returned',
			'4' => 'Tutor Complete',
			'5' => 'Administrator Review',
			'6' => 'Assignment Accepted By Administrator',
			'7' => 'Administrator Returned'
		);
		
		return $assignment_status;
	}
	
	/* Softronikx Technologies */
	public function updateAssignmentStatus($essay_id, $assignment_status) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "essay_assignment SET " .
				" date_completed = '" . $this->db->escape(date('Y-m-d',strtotime("now"))) .
				"', current_status = '" . $this->db->escape($assignment_status) .
				"', status = '" . (int)$assignment_status . 
				"' WHERE essay_id = '" . (int)$essay_id . 
				"'");
		 
		return $assignment_status;
	}
	/* End of Code */
	
	public function getInformation($essay_id) {
		$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id) WHERE essay_id = '" . (int)$essay_id . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
		
	public function getInformations($data = array()) {
		
		$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status 
		FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) 
		ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id)";
	
		/* Softronikx Technologies */
		$implode = array();
		
		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {	
						
			$implode[] = "concat(ut.firstname,' ',ut.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";			
			$implode[] = "ea.topic LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ea.description LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ea.student_name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "es.name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";	
			$implode[] = "ea.assignment_num LIKE '%" . $this->db->escape(trim($data['filter_all'],'Aa')) . "%'";		
			$implode[] = "ea.paid LIKE '%" . $this->db->escape($data['filter_all']) . "%'";	
			$implode[] = "ea.owed LIKE '%" . $this->db->escape($data['filter_all']) . "%'";	
			
			if ($implode) {
				$sql .= " WHERE ( " . implode(" OR ", $implode) ." )";
			}			
		} 
		else
		{			
			if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
				$implode[] = "ea.tutor_id = '" . $this->db->escape($data['filter_tutor_id']) . "'";			
			}
			
			if (isset($data['filter_assignment_num']) && !is_null($data['filter_assignment_num'])) {
				$implode[] = "ea.assignment_num LIKE '%" . $this->db->escape(trim($data['filter_assignment_num'],'Aa')) . "%'";			
			}
			
			if (isset($data['filter_price_paid']) && !is_null($data['filter_price_paid'])) {
				$implode[] = "ea.owed LIKE '%" . $this->db->escape($data['filter_price_paid']) . "%'";			
			}
			
			if (isset($data['filter_paid_to_tutor']) && !is_null($data['filter_paid_to_tutor'])) {
				$implode[] = "ea.paid LIKE '%" . $this->db->escape($data['filter_paid_to_tutor']) . "%'";			
			}
			
			if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
				$implode[] = "concat(ut.firstname,' ',ut.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%'";			
			}
			
			if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
				$implode[] = "ea.student_name LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
				}
			
			if (isset($data['filter_topic']) && !is_null($data['filter_topic'])) {
				$implode[] = "ea.topic LIKE '%" . $this->db->escape($data['filter_topic']) . "%'";
			}
				
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "ea.current_status LIKE '%" . $this->db->escape($data['filter_status']) . "%'";
			}	
			
			if (isset($data['filter_date_assigned']) && !is_null($data['filter_date_assigned'])) {
				$implode[] = "ea.date_assigned >= '" . $this->db->escape($data['filter_date_assigned']) . "'";
			}			
			
			if (isset($data['filter_date_completed']) && !is_null($data['filter_date_completed'])) {
				$implode[] = "ea.date_completed >= '" . $this->db->escape($data['filter_date_completed']) . "'";
			}
			
			if (isset($data['filter_date_to_assigned']) && !is_null($data['filter_date_to_assigned'])) {
				$implode[] = "ea.date_assigned <= '" . $this->db->escape($data['filter_date_to_assigned']) . "'";
			}			
			
			if (isset($data['filter_date_to_completed']) && !is_null($data['filter_date_to_completed'])) {
				$implode[] = "ea.date_completed <= '" . $this->db->escape($data['filter_date_to_completed']) . "'";
			}			
		
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
		}
		
		/* End of Code */
		
		$sort_data = array(
			'ea.topic',
			'ea.assignment_num',
			'ea.student_name',
			'ea.tutor_id',
			'ea.status',
			'ea.date_assigned',
			'ea.date_due',
			'ea.owed',
			'ea.paid'
		);		
				
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if($data['sort'] == "ea.tutor_id")
				$sql .= " ORDER BY concat(ut.firstname,' ',ut.lastname) ";
			else
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
		
		//echo $sql;
		
		
		$query = $this->db->query($sql);
		
		return $query->rows;
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
	
	public function getMonthlyProfit($month=0, $year=0) {
		if(!empty($month) && !empty($year)) {
			
			$sql = "SELECT sum(owed) as profit FROM " . DB_PREFIX . "essay_assignment WHERE month(date_completed) = '". $month ."' AND year(date_completed) = '". $year ."' AND current_status = '4' group by month(date_completed) ";
			
			$query = $this->db->query($sql);			
			
			if(count($query->row) > 0)
				return $query->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getYearlyProfit($year=0) {
		if(!empty($year)) {
			
			$sql = "SELECT sum(owed) as profit FROM " . DB_PREFIX . "essay_assignment WHERE year(date_completed) = '". $year ."' AND current_status = '4' group by year(date_completed) ";
			
			$query = $this->db->query($sql);
				
			if(count($query->row) > 0)
				return $query->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getEssaysStatus() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "essay_assignment_status");
		
		return $query->rows;
	}
	
	public function getTotalInformations($data = array()) {
		
		$sql = "SELECT ea.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, es.name as curr_status 
		FROM " . DB_PREFIX . "essay_assignment ea LEFT JOIN (user ut, essay_assignment_status es) 
		ON (ea.tutor_id = ut.user_id AND ea.current_status = es.essay_status_id)";
	
		/* Softronikx Technologies */
		$implode = array();
		
		if (isset($data['filter_all']) && !is_null($data['filter_all'])) {	
			
			$implode[] = "concat(ut.firstname,' ',ut.lastname) LIKE '%" . $this->db->escape($data['filter_all']) . "%'";			
			$implode[] = "ea.topic LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ea.description LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "ea.student_name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";
			$implode[] = "es.name LIKE '%" . $this->db->escape($data['filter_all']) . "%'";	
			$implode[] = "ea.assignment_num LIKE '%" . $this->db->escape(trim($data['filter_all'],'Aa')) . "%'";
			$implode[] = "ea.paid LIKE '%" . $this->db->escape($data['filter_all']) . "%'";	
			$implode[] = "ea.owed LIKE '%" . $this->db->escape($data['filter_all']) . "%'";	
			
			if ($implode) {
				$sql .= " WHERE ( " . implode(" OR ", $implode) ." )";
			}
		} 
		else
		{
			if (isset($data['filter_tutor_id']) && !is_null($data['filter_tutor_id'])) {
				$implode[] = "ea.tutor_id = '" . $this->db->escape($data['filter_tutor_id']) . "'";			
			}
			
			if (isset($data['filter_assignment_num']) && !is_null($data['filter_assignment_num'])) {
				$implode[] = "ea.assignment_num LIKE '%" . $this->db->escape(trim($data['filter_assignment_num'],'Aa')) . "%'";		
			}
			
			if (isset($data['filter_price_paid']) && !is_null($data['filter_price_paid'])) {
				$implode[] = "ea.owed LIKE '%" . $this->db->escape($data['filter_price_paid']) . "%'";			
			}
			
			if (isset($data['filter_paid_to_tutor']) && !is_null($data['filter_paid_to_tutor'])) {
				$implode[] = "ea.paid LIKE '%" . $this->db->escape($data['filter_paid_to_tutor']) . "%'";			
			}
						
			if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
				$implode[] = "concat(ut.firstname,' ',ut.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%'";			
			}
			
			if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
				$implode[] = "ea.student_name LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
				}
			
			if (isset($data['filter_topic']) && !is_null($data['filter_topic'])) {
				$implode[] = "ea.topic LIKE '%" . $this->db->escape($data['filter_topic']) . "%'";
			}
				
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "ea.status LIKE '%" . $this->db->escape($data['filter_status']) . "%'";
			}	
			
			if (isset($data['filter_date_assigned']) && !is_null($data['filter_date_assigned'])) {
				$implode[] = "ea.date_assigned >= '" . $this->db->escape($data['filter_date_assigned']) . "'";
			}			
			
			if (isset($data['filter_date_completed']) && !is_null($data['filter_date_completed'])) {
				$implode[] = "ea.date_completed >= '" . $this->db->escape($data['filter_date_completed']) . "'";
			}
			
			if (isset($data['filter_date_to_assigned']) && !is_null($data['filter_date_to_assigned'])) {
				$implode[] = "ea.date_assigned <= '" . $this->db->escape($data['filter_date_to_assigned']) . "'";
			}			
			
			if (isset($data['filter_date_to_completed']) && !is_null($data['filter_date_to_completed'])) {
				$implode[] = "ea.date_completed <= '" . $this->db->escape($data['filter_date_to_completed']) . "'";
			}	
								
		
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
		}
		
		/* End of Code */
		
		$sort_data = array(
			'ea.topic',
			'ea.assignment_num',
			'ea.student_name',
			'ea.tutor_id',
			'ea.status',
			'ea.date_assigned',
			'ea.date_due'
		);		
				
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if($data['sort'] == "ea.tutor_id")
				$sql .= " ORDER BY concat(ut.firstname,' ',ut.lastname) ";
			else
				$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY topic";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
				
		$query = $this->db->query($sql);
		
		return $query->num_rows;
	
		
		
		/* Commented by Softronikx 
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "essay_assignment");
		
		return $query->row['total'];*/
	}	
}
?>