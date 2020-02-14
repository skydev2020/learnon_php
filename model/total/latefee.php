<?php
class ModelTotalLatefee extends Model {
	public function getTotal(&$total_data, &$total) {
		
		if ($this->config->get('latefee_status') && isset($this->request->get['invoice_id'])) 
		{
			$sql = "SELECT i.*, concat(ut.firstname,' ',ut.lastname) as student_name FROM " . DB_PREFIX . "student_invoice i LEFT JOIN user ut ON (i.student_id = ut.user_id) WHERE invoice_id = '" . (int)$this->request->get['invoice_id'] . "' AND invoice_status != 'Hold For Approval' ";
			
			$query = $this->db->query($sql);
			
			$total_late_fee = $query->row['late_fee'];
			if(is_numeric($total_late_fee))
			{
				if($total_late_fee > 0) {
					$total_data[] = array(
							'title'      => $this->language->get('text_latefee'),
							'text'       => $this->currency->format($total_late_fee),
							'value'      => $total_late_fee,
							'sort_order' => $this->config->get('latefee_sort_order')
					);
				
					$total += $total_late_fee;
				}
			}
			
		}
		
		
		/*
		
		if ($this->config->get('latefee_status') && isset($this->request->get['invoice_id'])) {
			$this->load->language('total_student/latefee');
		 	
			$this->load->model('localisation/currency');
			$invoice_id =  $this->request->get['invoice_id'];
			$this->load->model('student/invoice');
			$invoice_details = $this->model_student_invoice->getInvoice($invoice_id);
			$invoice_date = $invoice_details["invoice_date"];
			$invoice_due_date = strtotime(date("Y-m-d", strtotime($invoice_details["date_added"])) . " +".$this->config->get('latefee_start')." day");
			$current_date = mktime(0,0,0,date("m"),date("d"),date("y"));
			$total_months = @ceil(($current_date-$invoice_due_date)/2628000);
			$total_late_fee = $total_months*$this->config->get('latefee_fee');
			
			if($total_late_fee > 0) {
				$total_data[] = array( 
	        		'title'      => $this->language->get('text_latefee'),
	        		'text'       => $this->currency->format($total_late_fee),
	        		'value'      => $total_late_fee,
					'sort_order' => $this->config->get('latefee_sort_order')
				);
				
				$total += $total_late_fee;	
			}
		}
		
		*/
		
		
	}
}
?>
