<?php
class ControllerPaymentStudentCheque extends Controller {
	protected function index() {
		$this->language->load('payment_student/cheque');
		
    	$this->data['text_payable'] = $this->language->get('text_payable');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_payment'] = $this->language->get('text_payment');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['payable'] = $this->config->get('cheque_payable');
		$this->data['address'] = nl2br($this->config->get('config_address'));

		$this->data['continue'] = $this->session->data['payment_continue'];
		
		$this->data['back'] = $this->session->data['payment_back'];
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cheque.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cheque.tpl';
		} else {
			$this->template = 'payment_student/cheque.tpl';
		}
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->language->load('payment_student/cheque');
		
		$this->load->model('total/order');
		
		$comment  = $this->language->get('text_payable') . "\n";
		$comment .= $this->config->get('cheque_payable') . "\n\n";
		$comment .= $this->language->get('text_address') . "\n";
		$comment .= $this->config->get('config_address') . "\n\n";
		$comment .= $this->language->get('text_payment') . "\n";
		
		$this->model_total_order->confirm($this->session->data['order_id'], $this->config->get('cheque_order_status_id'), $comment);
	}
}
?>