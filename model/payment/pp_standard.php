<?php 
class ModelPaymentPPStandard extends Model {
  	public function getMethod($address) {
		$this->load->language('payment_student/pp_standard');
				
		$status = TRUE;	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'pp_standard',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('pp_standard_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>