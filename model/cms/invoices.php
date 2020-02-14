<?php
class ModelCmsInvoices extends Model {
	
	public function checkStudentMiniBill($data) {
		$student_id = $data['students_id'];
		$total_hours = $data['total_hours'];
		
		$bill_info = array();
		
		$student_info = $this->db->query("SELECT grades_id FROM " . DB_PREFIX . "user_info WHERE user_id = '" . (int)$student_id . "'");
//		echo "SELECT grades_id FROM " . DB_PREFIX . "user_info WHERE user_id = '" . (int)$student_id . "'";
		$student_grade = $student_info->row['grades_id'];
		if($student_grade <= 7 && $total_hours < '1.00') {
			$bill_info['charge_time'] = '1.00';
		} elseif ($student_grade >= 8 && $total_hours < '1.50') {
			$bill_info['charge_time'] = '1.00';	 //changed on 28th sep 2014, as per ne logic $bill_info['charge_time'] = '1.50';	
		}
		
//		print_r($data);
//		echo $student_id." => ".$student_grade." | ";

//		//188, 170, 261

//		if($student_id == '188' && 0) {
//			print_r($bill_info);
//			die;
//		}
//		echo "billinfo";print_r($bill_info);
		return $bill_info;
	}
	
	public function lockInvoice($invoice_id) {
		$locked_status = 1;
		$this->db->query("UPDATE `" . DB_PREFIX . "student_invoice` SET is_locked = '" . $locked_status . "' WHERE invoice_id = '" . (int)$invoice_id . "' ");
	}
	
	public function unlockInvoice($invoice_id) {
		$locked_status = 0;
		$this->db->query("UPDATE `" . DB_PREFIX . "student_invoice` SET is_locked = '" . $locked_status . "' WHERE invoice_id = '" . (int)$invoice_id . "' ");
	}
	
	public function deleteInvoice($invoice_id) {	
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "student_invoice` WHERE invoice_id = '" . (int)$invoice_id . "'");
	}
	
	public function editInvoice($invoice_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET " .
				" invoice_date = '" . $this->db->escape($data['invoice_date']) .
				"', total_hours = '" . $this->db->escape($data['total_hours']) .
				"', total_amount = '" . $this->db->escape($data['total_amount']) .
				"', invoice_format = '" . $this->db->escape($data['invoice_mail']) .
				"', invoice_status = '" . $this->db->escape($data['invoice_status']) .
				"', num_of_sessions = '" . (int)$data['num_of_sessions'] .
				"' WHERE invoice_id = '" . (int)$invoice_id."'");
		
		if(!empty($data['pay_date'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET " .
					" balance_amount = '" . (float)$data['balance_amount'] .
					"', paid_amount = '" . (float)$data['paid_amount'] .
					"',  pay_date = '" . $this->db->escape($data['pay_date']) .					
					"',  is_locked = '" . (int)$data['is_locked'] .
					"' WHERE invoice_id = '" . (int)$invoice_id."'");
		}		
		
		if(!empty($data['order_id']) && !empty($data['pay_date'])) {
			$order_id = $data['order_id'];
			$order_status_id = 5; // valid for complete orders
			$pay_date = date("Y-m-d H:i:s");
			$comments = $this->db->escape($data['invoice_notes']);
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = '" . $pay_date . "' WHERE order_id = '" . (int)$order_id . "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . 
				"', order_status_id = '" . (int)$order_status_id . 
				"', notify = '0" . 
				"', comment = '" . $this->db->escape($comments) . 
				"', date_added = '".$pay_date."'");
		}
		
		if(isset($data['packages']))
		if(count($data['packages']) > 0)		
		foreach($data['packages'] as $order_id => $hours_left) {
			
			$query = $this->db->query("SELECT left_hours FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
			$old_left_hours = $query->row['left_hours']; 
			
			if($old_left_hours <> $hours_left) {
				
				$data['order_status_id'] = 5; // valid for complete orders
				
				$comments = "Remaining Hours Update From ".$old_left_hours." To ".$hours_left;
				
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET left_hours = '" . (float)$hours_left . "' WHERE order_id = '" . (int)$order_id . "'");
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . 
					"', order_status_id = '" . (int)$data['order_status_id'] . 
					"', notify = '0" . 
					"', comment = '" . $this->db->escape($comments) . 
					"', date_added = NOW()");	
			}			
		}
	}
	
	public function getMonthlyPackageProfit($month=0, $year=0) {
		if(!empty($month) && !empty($year)) {
			
			$sql = "SELECT sum(total) as profit FROM `" . DB_PREFIX . "order` WHERE order_status_id = '5' AND package_id != '0' AND month(date_modified) = '". $month ."' AND year(date_modified) = '". $year ."' group by month(date_modified) ";
			
			$query = $this->db->query($sql);
				
			if(count($query->row) > 0)
				return $query->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getYearlyPackageProfit($year=0) {
		if(!empty($year)) {
			
			$sql = "SELECT sum(total) as profit FROM `" . DB_PREFIX . "order` WHERE order_status_id = '5' AND package_id != '0' AND year(date_modified) = '". $year ."' group by year(date_modified) ";
			
			$query = $this->db->query($sql);
				
			if(count($query->row) > 0)
				return $query->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getMonthlyProfit($month=0, $year=0) {
		if(!empty($month) && !empty($year)) {
			
			//must count even unpaid as per clients requirement, as it is still revenue and students will eventually pay up
			$sql = "SELECT sum(total_amount) as profit FROM " . DB_PREFIX . "student_invoice WHERE month(invoice_date) = '". $month ."' AND year(invoice_date) = '". $year ."' group by month(invoice_date) ";
			
			//Softronikx Technologies added the condition to only consider paid invoices in profits
			//$sql = "SELECT sum(total_amount) as profit FROM " . DB_PREFIX . "student_invoice WHERE  invoice_status = 'Paid' AND  month(invoice_date) = '". $month ."' AND year(invoice_date) = '". $year ."' group by month(invoice_date)";
						
			$query = $this->db->query($sql);
				
			if(count($query->row) > 0)
				return $query->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getMonthlyOtherIncome($month=0, $year=0) {
		if(!empty($month) && !empty($year)) {
			
			$sql = "SELECT sum(amount) as profit FROM " . DB_PREFIX . "other_income WHERE month(date) = '". $month ."' AND year(date) = '". $year ."' group by month(date) ";
			
			$query = $this->db->query($sql);
			
			$sql = "SELECT other_revenue as profit FROM " . DB_PREFIX . "monthly_income WHERE month(date) = '". $month ."' AND year(date) = '". $year ."' order by id DESC limit 0,1 ";
						
			$query2 = $this->db->query($sql);
			
			if((count($query->row) > 0) or (count($query2->row) > 0))
				return $query->row['profit'] + $query2->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getYearlyProfit($year=0) {
		if(!empty($year)) {
			
			//must count even unpaid as per clients requirement, as it is still revenue and students will eventually pay up
			$sql = "SELECT sum(total_amount) as profit FROM " . DB_PREFIX . "student_invoice WHERE year(invoice_date) = '". $year ."' group by year(invoice_date) ";
			
			//Softronikx Technologies added the condition to only consider paid invoices in profits
			//$sql = "SELECT sum(total_amount) as profit FROM " . DB_PREFIX . "student_invoice WHERE  invoice_status = 'Paid' AND year(invoice_date) = '". $year ."' group by year(invoice_date) ";
			
			
			$query = $this->db->query($sql);
				
			if(count($query->row) > 0)
				return $query->row['profit'];
			else
				return 0;
				
		} else {
			return 0;
		}
	}
	
	public function getOrderIdByInvoiceId($invoice_id) {
		$order_query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE invoice_pk = '" . (int)$invoice_id . "'");
		
		if(count($order_query->row) > 0)
			return $order_query->row['order_id'];
		else
			return 0;
	}
	
	public function getStudentPackages($student_id) 
	{
		$sql = "SELECT o.*, (select package_name from packages where package_id = o.package_id limit 1) as package_name FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = '" . (int)$student_id . "' AND o.order_status_id = '5' AND o.left_hours <> '0' order by order_id";		
		
//		echo $sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getLastInvoiceDate()
	{
		$sql = "SELECT DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date FROM student_invoice ORDER BY invoice_date DESC LIMIT 0,1";		
		
		$query = $this->db->query($sql);
		$result = $query->row;
		return $result['invoice_date'];
	}

	public function getInvoice($invoice_id) {
		$sql = "SELECT i.*, concat(ut.firstname,' ',ut.lastname) as student_name FROM " . DB_PREFIX . "student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE invoice_id = '" . (int)$invoice_id . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
		
	public function getInvoices($data = array()) {
		$sql = "SELECT i.*, concat(ut.firstname,' ',ut.lastname, ' (', user_id,')') as student_name FROM " . DB_PREFIX . "student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) ";
			
		$implode = array();
		
		$invoice_num = explode("-",$data['filter_invoice_num']);
		$invoice_num = end($invoice_num);
		
		/*Softronikx Technologies */
		if (isset($data['filter_user_id']) && !is_null($data['filter_user_id'])) {
			$implode[] = "i.student_id = '" . $this->db->escape($data['filter_user_id']) . "' ";
		}
		/* End of code by Softronikx Technologies */
		
		if (isset($data['filter_invoice_num']) && !is_null($data['filter_invoice_num'])) {
			$implode[] = "i.invoice_num = '" . $this->db->escape($invoice_num) . "' ";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = " concat(ut.firstname,' ',ut.lastname) like '%" . $this->db->escape($data['filter_student_name']) . "%' ";
		}
		
		if (isset($data['filter_invoice_status']) && !is_null($data['filter_invoice_status'])) {
		
			/*Softronikx Technologies */
			if($data['filter_invoice_status'] == 'Unpaid')
			{
				$implode[] = "i.invoice_status <> 'Paid' ";
			}/*End of code by Softronikx Technologies */
			else
			{
				$implode[] = "i.invoice_status = '" . $this->db->escape($data['filter_invoice_status']) . "' ";
			}
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "i.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "i.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_invoice_date']) && !is_null($data['filter_invoice_date'])) {		
			$implode[] = "i.invoice_date like '" . $this->db->escape($data['filter_invoice_date']) . "%'";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "DATE(i.total_hours) = DATE('" . $this->db->escape($data['filter_total_hours']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'student_name',
			'total_hours',
			'total_amount',
			'invoice_status',
			'total_hours'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if($data['sort'] == "student_name")
				$sql .= " ORDER BY lower(concat(ut.firstname,' ',ut.lastname)) ";
			else
				$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY invoice_num";	
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
//SELECT i.*, concat(ut.firstname,' ',ut.lastname, ' (', user_id,')') as student_name FROM student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE concat(ut.firstname,' ',ut.lastname) like '%monika%' ORDER BY invoice_num DESC LIMIT 0,1000
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalInvoices($data) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "student_invoice i  LEFT JOIN user ut ON (i.student_id = ut.user_id) ";
		$implode = array();
		
		$invoice_num = explode("-",$data['filter_invoice_num']);
		$invoice_num = end($invoice_num);
				
		/*Softronikx Technologies */
		if (isset($data['filter_user_id']) && !is_null($data['filter_user_id'])) {
			$implode[] = "i.student_id = '" . $this->db->escape($data['filter_user_id']) . "' ";
		}
		/* End of code by Softronikx Technologies */
		
		if (isset($data['filter_invoice_num']) && !is_null($data['filter_invoice_num'])) {
			$implode[] = "i.invoice_num = '" . $this->db->escape($invoice_num) . "' ";
		}
		
		if (isset($data['filter_student_name']) && !is_null($data['filter_student_name'])) {
			$implode[] = " concat(ut.firstname,' ',ut.lastname) like '%" . $this->db->escape($data['filter_student_name']) . "%' ";
		}
		
		if (isset($data['filter_invoice_status']) && !is_null($data['filter_invoice_status'])) {
			/*Softronikx Technologies */
			if($data['filter_invoice_status'] == 'Unpaid')
			{
				$implode[] = "i.invoice_status <> 'Paid' ";
			}/*End of code by Softronikx Technologies */
			else
			{
				$implode[] = "i.invoice_status = '" . $this->db->escape($data['filter_invoice_status']) . "' ";
			}
		}
		
		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "i.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "i.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}
		
		if (isset($data['filter_invoice_date']) && !is_null($data['filter_invoice_date'])) {
			$data['filter_invoice_date'] = date("Y-m-", strtotime($data['filter_invoice_date']));
			
			$implode[] = "i.invoice_date like '" . $this->db->escape($data['filter_invoice_date']) . "%'";
		}
		
		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "DATE(i.total_hours) = DATE('" . $this->db->escape($data['filter_total_hours']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}	

	/* function by Softronikx Technologies */
	public function getUnpaidInvoice($invoice_id)
	{
		
		$sql = "SELECT * from student_invoice where invoice_id = $invoice_id and invoice_status <> 'Paid' LIMIT 0,1";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	/* function by Softronikx Technologies */
	public function applyLateFee($invoice_id,$late_fee,$total_amount,$invoice_mail)
	{
		//update total_amount as total_amount + late_fee limit 1				
		//update status to Reminder Sent
		
		/*echo "UPDATE " . DB_PREFIX . "student_invoice SET " .
				" total_amount = " . (int)$total_amount ."
				, late_fee = '" . (int)$late_fee ."'
				, invoice_status = 'Reminder Sent' 
				, invoice_format = '".$this->db->escape($invoice_mail)."' 
				WHERE invoice_id = '" . (int)$invoice_id."' LIMIT 1";*/
		
		//exit(0);
		
		$this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET " .
				" total_amount = " . (int)$total_amount ."
				, late_fee = '" . (int)$late_fee ."'
				, invoice_status = 'Reminder Sent' 
				, invoice_format = '".$this->db->escape($invoice_mail)."' 
				WHERE invoice_id = '" . (int)$invoice_id."' and invoice_status <> 'Paid' LIMIT 1");
		
		/*$this->db->query("UPDATE " . DB_PREFIX . "student_invoice SET " .
				" total_amount = total_amount + " . (int)$late_fee ."
				, late_fee = '" . (int)$late_fee ."'
				, invoice_status = 'Reminder Sent' WHERE invoice_id = '" . (int)$invoice_id."' LIMIT 1");*/
		
	}
}
?>