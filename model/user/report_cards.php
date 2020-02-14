<?php
class ModelUserReportCards extends Model {
	var $user_group_id = 1;
	
	
	public function getReport($progress_reports_id) {
		$query = $this->db->query("SELECT s.*, CONCAT(t.firstname, ' ', t.lastname) AS tutor_name, CONCAT(st.firstname, ' ', st.lastname) AS student_name FROM " . DB_PREFIX . "progress_reports s LEFT JOIN " . DB_PREFIX . "user t ON s.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user st ON s.students_id = st.user_id WHERE s.progress_reports_id = '$progress_reports_id' ");
		return $query->row;
	}
		
	public function getReports($data = array()) {
		$sql = "SELECT s.*, CONCAT(t.firstname, ' ', t.lastname) AS tutor_name, CONCAT(st.firstname, ' ', st.lastname) AS student_name FROM " . DB_PREFIX . "progress_reports s LEFT JOIN " . DB_PREFIX . "user t ON s.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user st ON s.students_id = st.user_id ";

		$implode = array();
		
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = "s.grade LIKE '%" . $this->db->escape($data['filter_grade']) . "%' ";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%' ";
		}
		
		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = "CONCAT(t.firstname, ' ', t.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%' ";
		}
		
		if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$implode[] = "s.subjects LIKE '%" . $this->db->escape($data['filter_subjects']) . "%' ";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "s.date_added = '" . $this->db->escape($data['filter_date_added']) . "' ";
		}
		
		if ($implode) {
			$sql .=" WHERE ". implode(" AND ", $implode);
		}
		
		$sort_data = array(
			's.grade',
			'tutor_name',
			'student_name',
			's.subjects',
			's.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY s.date_added";	
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
		
	public function getTotalReports($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "progress_reports s LEFT JOIN " . DB_PREFIX . "user t ON s.tutors_id = t.user_id LEFT JOIN " . DB_PREFIX . "user st ON s.students_id = st.user_id ";
		
		$implode = array();
		
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = "s.grade = '" . $this->db->escape($data['filter_grade']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = "CONCAT(t.firstname, ' ', t.lastname) LIKE '%" . $this->db->escape($data['filter_tutor_name']) . "%' ";
		}
		
		if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$implode[] = "s.subjects LIKE '%" . $this->db->escape($data['filter_subjects']) . "%' ";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "s.date_added = '%" . $this->db->escape($data['filter_date_added']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE ".implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
}
?>