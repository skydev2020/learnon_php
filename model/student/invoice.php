<?php
class ModelStudentInvoice extends Model {
	public function editInvoice($invoice_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET date_completed = '" . $this->db->escape($data['date_completed']) ."', current_status = '" . $this->db->escape($data['status']) ."', status = '" . (int)$data['status'] ."' WHERE invoice_id = '" . (int)$invoice_id."'  AND invoice_status = 'Paid'");
	}

	public function getInvoice($invoice_id) {
		$sql = "SELECT i.*, concat(ut.firstname,' ',ut.lastname) as student_name FROM " . DB_PREFIX . "student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE invoice_id = '" . (int)$invoice_id . "' AND invoice_status != 'Hold For Approval' ";
		
		//echo $sql;
		//SELECT i.*, concat(ut.firstname,' ',ut.lastname) as student_name FROM student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE invoice_id = '559' AND invoice_status != 'Hold For Approval' 

		
		$query = $this->db->query($sql);
		
		return $query->row;
	} 
	
	public function updateCorrectInvoiceTotal($invoice_id,$total_amount) {
		
		if(is_numeric($total_amount) and $total_amount > 0)
		{
			$sql = "Update " . DB_PREFIX . "student_invoice set total_amount = '".$total_amount."' WHERE invoice_id = '" . (int)$invoice_id . "' AND invoice_status != 'Hold For Approval' ";
		
			
			$query = $this->db->query($sql);	
		}
	}
	
	
	
	public function getInvoices_print() {
		$sql = "SELECT i.*, concat(ut.firstname,' ',ut.lastname) as student_name FROM " . DB_PREFIX . "student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE invoice_status != 'Paid'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	} 
	
	public function getInvoiceDetails($invoice_id) {
		$invoiceDetails = array();
		
		$invoice_info = $this->getInvoice($invoice_id); 
		//echo $invoice_info['log_data'];
		$log_data = unserialize($invoice_info['log_data']);
		$sessions_info = $log_data['student_sessions'];
		//print_r($sessions_info);
				
		$invoiceDetails['sessions'] = $sessions_info;
		
		return $invoiceDetails;
	}
	
	public function getOrderIdByInvoiceId($invoice_id) {
		$order_query = $this->db->query("SELECT order_id, order_status_id FROM `" . DB_PREFIX . "order` WHERE invoice_pk = '" . (int)$invoice_id . "' AND order_status_id !=0 ");
		return $order_query->row;
	}
		
	public function getInvoices($data = array()) {
		$sql = "SELECT i.invoice_id, i.invoice_num, i.invoice_prefix, i.total_hours, i.total_amount, i.invoice_status, i.invoice_date, i.send_date, concat(ut.firstname,' ',ut.lastname) as student_name " .
					" FROM " . DB_PREFIX . "student_invoice i " .
					" LEFT JOIN user ut ON (i.student_id = ut.user_id) ";
			
		$implode = array();
		
		
		$implode[] = "i.student_id = '" . (int) $this->session->data['user_id'] . "' ";
		
		
		if (isset($data['filter_invoice_num']) && !is_null($data['filter_invoice_num'])) {
			
			$invoice_num = explode("-", $data['filter_invoice_num']);
			$invoice_num = end($invoice_num);
			
			$implode[] = "i.invoice_num = '" . $invoice_num . "' ";
		}
		
		if (isset($data['filter_invoice_status']) && !is_null($data['filter_invoice_status'])) {
			$implode[] = "i.invoice_status = '" . $this->db->escape($data['filter_invoice_status']) . "' ";
		} else {
			$implode[] = " i.invoice_status != 'Hold For Approval' ";
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "i.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "i.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_invoice_date']) && !is_null($data['filter_invoice_date'])) {
			$implode[] = "DATE(i.invoice_date) = DATE('" . $this->db->escape($data['filter_invoice_date']) . "')";
		}
		
		if (isset($data['filter_send_date']) && !is_null($data['filter_send_date'])) {
			$implode[] = "DATE(i.send_date) = DATE('" . $this->db->escape($data['filter_send_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'total_hours',
			'total_amount',
			'invoice_num',
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
		
		//echo $sql;
		//SELECT i.invoice_id, i.invoice_num, i.invoice_prefix, i.total_hours, i.total_amount, i.invoice_status, i.invoice_date, i.send_date, concat(ut.firstname,' ',ut.lastname) as student_name FROM student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE i.student_id = '866' AND i.invoice_status != 'Hold For Approval' ORDER BY send_date DESC LIMIT 0,1000
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalInvoices($data) {
			
		$sql = "SELECT COUNT(*) AS total " .
					" FROM " . DB_PREFIX . "student_invoice i " .
					" LEFT JOIN user ut ON (i.student_id = ut.user_id) ";
			
		$implode = array();
		
		
		$implode[] = "i.student_id = '" . (int) $this->session->data['user_id'] . "' ";
		
		
		if (isset($data['filter_invoice_num']) && !is_null($data['filter_invoice_num'])) {
			
			$invoice_num = explode("-", $data['filter_invoice_num']);
			$invoice_num = end($invoice_num);
			
			$implode[] = "i.invoice_num = '" . $invoice_num . "' ";
		}
		
		if (isset($data['filter_invoice_status']) && !is_null($data['filter_invoice_status'])) {
			$implode[] = "i.invoice_status = '" . $this->db->escape($data['filter_invoice_status']) . "' ";
		} else {
			$implode[] = " i.invoice_status != 'Hold For Approval' ";
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "i.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "i.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_invoice_date']) && !is_null($data['filter_invoice_date'])) {
			$implode[] = "DATE(i.invoice_date) = DATE('" . $this->db->escape($data['filter_invoice_date']) . "')";
		}
		
		if (isset($data['filter_send_date']) && !is_null($data['filter_send_date'])) {
			$implode[] = "DATE(i.send_date) = DATE('" . $this->db->escape($data['filter_send_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
}
?>