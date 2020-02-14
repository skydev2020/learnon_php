<?php
class ModelTutorPaycheque extends Model {
	public function editPayCheque($paycheque_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "tutor_paycheque SET date_completed = '" . $this->db->escape($data['date_completed']) ."', current_status = '" . $this->db->escape($data['status']) ."', status = '" . (int)$data['status'] ."' WHERE paycheque_id = '" . (int)$paycheque_id."'  AND paycheque_status = 'Paid'");
	}
	
	public function getPayMonths() {
		$sql = "SELECT paycheque_id, paycheque_date FROM " . DB_PREFIX . "tutor_paycheque WHERE tutor_id = '".$this->session->data['user_id']."'  AND paycheque_status = 'Paid' GROUP BY YEAR(paycheque_date), MONTH(paycheque_date) ORDER BY paycheque_date DESC ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getAllSessions($session_ides) {
		$session_ides = implode(",", $session_ides);		
		$sql = "SELECT ssn.*, (ssn.session_duration * tts.base_wage) as session_amount, concat(us.firstname,' ',us.lastname) as student_name FROM " . DB_PREFIX . "sessions ssn LEFT JOIN " . DB_PREFIX . "tutors_to_students tts ON (ssn.tutors_to_students_id = tts.tutors_to_students_id) LEFT JOIN " . DB_PREFIX . "user us ON (tts.students_id = us.user_id)  WHERE session_id in (". $this->db->escape($session_ides).") order by session_date";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getAllEssays($essays_ides) {
		$essays_ides = implode(",", $essays_ides);		
		$sql = "SELECT * FROM " . DB_PREFIX . "essay_assignment WHERE essay_id in (". $this->db->escape($essays_ides).")";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getPayTotal($month, $year) {
		$sql = "SELECT paid_amount, total_amount, essay_amount FROM " . DB_PREFIX . "tutor_paycheque WHERE tutor_id = '".$this->session->data['user_id']."'  AND paycheque_status = 'Paid' AND MONTH(paycheque_date) = '$month' AND YEAR(paycheque_date) = '$year' ";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getPayCheque($paycheque_id) {
		$sql = "SELECT p.*, concat(ut.firstname,' ',ut.lastname) as tutor_name FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) WHERE paycheque_id = '" . (int)$paycheque_id . "' AND paycheque_status = 'Paid' ";
		$query = $this->db->query($sql);
		return $query->row;
	}
		
	public function getPayCheques($data = array()) {
			$sql = "SELECT p.*, concat(ut.firstname,' ',ut.lastname) as tutor_name FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) WHERE p.tutor_id = '".$this->session->data['user_id']."' AND p.paycheque_status = 'Paid' ";
		$implode = array();
		if (isset($data['filter_paycheque_num']) && !is_null($data['filter_paycheque_num'])) {
			$implode[] = "p.paycheque_num = '" . $this->db->escape($data['filter_paycheque_num']) . "' ";
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "p.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "p.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_paycheque_date']) && !is_null($data['filter_paycheque_date'])) {
			$implode[] = "DATE(p.paycheque_date) = DATE('" . $this->db->escape($data['filter_paycheque_date']) . "')";
		}
		
		if (isset($data['filter_send_date']) && !is_null($data['filter_send_date'])) {
			$implode[] = "DATE(p.send_date) = DATE('" . $this->db->escape($data['filter_send_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'total_hours',
			'total_amount',
			'paycheque_num',
			'send_date'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY send_date";	
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
	
	public function getTotalPayCheques($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tutor_paycheque p WHERE p.tutor_id = '".$this->session->data['user_id']."' AND p.paycheque_status = 'Paid' ";
		$implode = array();
		
		if (isset($data['filter_paycheque_num']) && !is_null($data['filter_paycheque_num'])) {
			$implode[] = "p.paycheque_num = '" . $this->db->escape($data['filter_paycheque_num']) . "' ";
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "p.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "p.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_paycheque_date']) && !is_null($data['filter_paycheque_date'])) {
			$implode[] = "DATE(p.paycheque_date) = DATE('" . $this->db->escape($data['filter_paycheque_date']) . "')";
		}
		
		if (isset($data['filter_send_date']) && !is_null($data['filter_send_date'])) {
			$implode[] = "DATE(p.send_date) = DATE('" . $this->db->escape($data['filter_send_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}	
}
?>