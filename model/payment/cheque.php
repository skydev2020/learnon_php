<?php 
class ModelPaymentCheque extends Model {
  	public function getMethod($address) {
		$this->load->language('payment_student/cheque');
		
		$status = TRUE;
				
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'cheque',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('cheque_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>