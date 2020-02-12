<?php
class ControllerPaymentStudentBankTransfer extends Controller {
	protected function index() {
		$this->language->load('payment_student/bank_transfer');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_payment'] = $this->language->get('text_payment');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['bank'] = nl2br($this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')));

		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=student/packages/success&token=' . $this->session->data['token'];

		$url = '';
		$url = "&cancel=1";
		$this->data['back'] = HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . $url;
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bank_transfer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/bank_transfer.tpl';
		} else {
			$this->template = 'payment_student/bank_transfer.tpl';
		}	
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->language->load('payment_student/bank_transfer');
		
		$this->load->model('total/order');
		
		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= $this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->language->get('text_payment');
		
		$this->model_total_order->confirm($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment);
	}
}
?>