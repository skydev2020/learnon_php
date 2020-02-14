<?php
class ModelTutorReportCards extends Model {
	var $user_group_id = 2;
	public function addReport($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "progress_reports` SET tutors_id = '" . (int)$this->session->data['user_id'] . "', students_id = '" . $this->db->escape($data['students_id']) . "', grade = '" . $this->db->escape($data['grade']) . "', subjects = '" . $this->db->escape($data['subjects']) . "', student_prepared = '" . $this->db->escape($data['student_prepared']) . "', questions_ready = '" . $this->db->escape($data['questions_ready']) . "', pay_attention = '" . $this->db->escape($data['pay_attention']) . "',  weaknesses = '" . $this->db->escape($data['weaknesses']) . "', listen_to_suggestions = '" . $this->db->escape($data['listen_to_suggestions']) . "', improvements = '" . $this->db->escape($data['improvements']) . "', other_comments = '" . $this->db->escape($data['other_comments']) . "', date_added = NOW() ");
      	
      	$progress_reports_id = $this->db->getLastId();
         return $progress_reports_id;
	}
	
	public function editReport($progress_reports_id, $data) {
			$this->db->query("UPDATE `" . DB_PREFIX . "progress_reports` SET students_id = '" . $this->db->escape($data['students_id']) . "', grade = '" . $this->db->escape($data['grade']) . "', subjects = '" . $this->db->escape($data['subjects']) . "', student_prepared = '" . $this->db->escape($data['student_prepared']) . "', questions_ready = '" . $this->db->escape($data['questions_ready']) . "', pay_attention = '" . $this->db->escape($data['pay_attention']) . "',  weaknesses = '" . $this->db->escape($data['weaknesses']) . "', listen_to_suggestions = '" . $this->db->escape($data['listen_to_suggestions']) . "', improvements = '" . $this->db->escape($data['improvements']) . "', other_comments = '" . $this->db->escape($data['other_comments']) . "' WHERE progress_reports_id = '" . (int)$progress_reports_id . "' ");
	}
	
	public function deleteReport($progress_reports_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "progress_reports WHERE progress_reports_id = '" . (int)$progress_reports_id . "'");
	}
	
	public function getGradeByStudentID($student_id) {
		$grades = array();
		$query = $this->db->query("SELECT g.grades_name FROM " . DB_PREFIX . "grades g LEFT JOIN " . DB_PREFIX . "user_info u ON g.grades_id = u.grades_id WHERE u.user_id = '$student_id' ");
		if(count($query->row))
		return $query->row['grades_name'];
	}
	
	public function getReport($progress_reports_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "progress_reports WHERE progress_reports_id = '$progress_reports_id'");
		return $query->row;
	}
		
	public function getReports($data = array()) {
		$sql = "SELECT s.*, CONCAT(st.firstname, ' ', st.lastname) AS student_name FROM " . DB_PREFIX . "progress_reports s LEFT JOIN " . DB_PREFIX . "user st ON s.students_id = st.user_id ";

		$implode = array();
		
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = "s.grade LIKE '%" . $this->db->escape($data['filter_grade']) . "%' ";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%' ";
		}
		
		if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$implode[] = "s.subjects LIKE '%" . $this->db->escape($data['filter_subjects']) . "%' ";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "s.date_added = '" . $this->db->escape($data['filter_date_added']) . "' ";
		}
		$sql .= " WHERE s.tutors_id = '".$this->session->data['user_id']."' ";
		if ($implode) {
			$sql .=" AND ". implode(" AND ", $implode);
		}
		
		$sort_data = array(
			's.grade',
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
      	$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX."progress_reports s LEFT JOIN " . DB_PREFIX . "user st ON s.students_id = st.user_id ";
		
		$implode = array();
		
		if (isset($data['filter_grade']) && !is_null($data['filter_grade'])) {
			$implode[] = "s.grade = '" . $this->db->escape($data['filter_grade']) . "'";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = "CONCAT(st.firstname, ' ', st.lastname) LIKE '%" . $this->db->escape($data['filter_student_name']) . "%'";
		}
		
		if (isset($data['filter_subjects']) && !is_null($data['filter_subjects'])) {
			$implode[] = "s.subjects LIKE '%" . $this->db->escape($data['filter_subjects']) . "%' ";
		}
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "s.date_added = '%" . $this->db->escape($data['filter_date_added']) . "%'";
		}
		$sql .= " WHERE s.tutors_id = '".$this->session->data['user_id']."' ";
		if ($implode) {
			$sql .= " AND ".implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function validateReportCard($progress_reports_id = "") {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."progress_reports WHERE progress_reports_id<>'".$progress_reports_id."'");
		return $query->row['total'];
	}
	
}
?>