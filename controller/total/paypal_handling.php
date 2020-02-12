<?php 
class ControllerTotalPaypalHandling extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/paypal_handling');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('paypal_handling', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
    	$this->data['text_amount'] = $this->language->get('text_amount');
		
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_fee'] = $this->language->get('entry_fee');
		$this->data['entry_fee_type'] = $this->language->get('entry_fee_type');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
 
		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_total'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=total/paypal_handling&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=total/paypal_handling&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'];

		if (isset($this->request->post['paypal_handling_total'])) {
			$this->data['paypal_handling_total'] = $this->request->post['paypal_handling_total'];
		} else {
			$this->data['paypal_handling_total'] = $this->config->get('paypal_handling_total');
		}
		
		if (isset($this->request->post['paypal_handling_fee'])) {
			$this->data['paypal_handling_fee'] = $this->request->post['paypal_handling_fee'];
		} else {
			$this->data['paypal_handling_fee'] = $this->config->get('paypal_handling_fee');
		}
		
		if (isset($this->request->post['paypal_handling_tax_class_id'])) {
			$this->data['paypal_handling_tax_class_id'] = $this->request->post['paypal_handling_tax_class_id'];
		} else {
			$this->data['paypal_handling_tax_class_id'] = $this->config->get('paypal_handling_tax_class_id');
		}

		if (isset($this->request->post['paypal_handling_status'])) {
			$this->data['paypal_handling_status'] = $this->request->post['paypal_handling_status'];
		} else {
			$this->data['paypal_handling_status'] = $this->config->get('paypal_handling_status');
		}

		if (isset($this->request->post['paypal_handling_sort_order'])) {
			$this->data['paypal_handling_sort_order'] = $this->request->post['paypal_handling_sort_order'];
		} else {
			$this->data['paypal_handling_sort_order'] = $this->config->get('paypal_handling_sort_order');
		}
		
		$this->template = 'total/paypal_handling.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/paypal_handling')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>