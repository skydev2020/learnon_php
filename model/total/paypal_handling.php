<?php
class ModelTotalPaypalHandling extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		
		if(isset($this->session->data['payment_method']))
			$payment_method = $this->session->data['payment_method'];
		else
			$payment_method = array();
		if(isset($payment_method['id']))
		if ($this->config->get('paypal_handling_status') && $payment_method['id'] == 'pp_standard') {
			$this->load->language('total_student/paypal_handling');
		 	
			$this->load->model('localisation/currency');
			
			$discount = 0;
			
			if ($this->config->get('fee_type') == 'F') {
				$discount = $this->config->get('paypal_handling_fee');
			} elseif ($this->config->get('fee_type') == 'P') {
				
				$discount = $total / 100 * $this->config->get('paypal_handling_fee');				
			}			

			$total_data[] = array( 
        		'title'      => $this->language->get('text_paypal_handling'),
        		'text'       => $this->currency->format($discount),
        		'value'      => $discount,
				'sort_order' => $this->config->get('paypal_handling_sort_order')
			);
				
			$total += $discount;
		}
	}
}
?>