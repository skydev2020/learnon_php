<?php 
class ControllerAccountSuccess extends Controller {  
	public function student() {
		
    	$this->language->load('account/success');
  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/success/student',
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);

    	$this->data['heading_title'] = $this->language->get('heading_title_student');

    	$this->data['text_message'] = $this->language->get('text_message_student');
		
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/account';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'common/student_success.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
  	
	}
	
	public function index() {
    	$this->language->load('account/success');
  
    	$this->document->title = $this->language->get('heading_title_tutor');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/success',
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);

    	$this->data['heading_title'] = $this->language->get('heading_title_tutor');

		if (!$this->config->get('config_customer_approval')) {
    		$this->data['text_message'] = sprintf($this->language->get('text_message_tutor'), HTTP_SERVER . 'index.php?route=information/contact');
		} else {
			$this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), HTTP_SERVER . 'index.php?route=information/contact');
		}
		
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/account';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/tutor_success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/tutor_success.tpl';
		} else {
			$this->template = 'common/tutor_success.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));				
  	}
}
?>