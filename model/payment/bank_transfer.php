<?php 
class ModelPaymentBankTransfer extends Model {
  	public function getMethod($address) {
		$this->load->language('payment_student/bank_transfer');
		
		$status = TRUE;
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'bank_transfer',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('bank_transfer_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>