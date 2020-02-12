<?php
class ControllerCmsLogmail extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/logmail');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('cms/logmail');

		$this->getList();
	}
	
  	public function export() {
		$this->load->model('cms/logmail');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = ' date_send '; 
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['filter_mail_to'])) {
			$filter_mail_to = $this->request->get['filter_mail_to'];
		} else {
			$filter_mail_to = NULL;
		}
		
		$this->data['filter_mail_to'] = $filter_mail_to;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;	
	
		$select = " mail_to ";
		if(isset($this->request->get['mail_to'])){
			$select .= ", mail_to ";		
		}
		if(isset($this->request->get['subject'])){
			$select .= ", subject ";
		}
		if(isset($this->request->get['message'])){
			$select .= ", message";
		}
		if(isset($this->request->get['date_send'])){
			$select .= ", date_send";
		}
		
	
		$results = $this->model_cms_logmail->getInformations($this->data, $select);
		$arrresult = array();
		if(isset($this->request->get['message'])){
			foreach($results as $result){
				$result['message'] = strip_tags(html_entity_decode($result['message'],ENT_QUOTES, 'UTF-8'));
				$arrresult[] = $result;
			}
		}else{
			$arrresult = $results;
		}
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("mail_log.xls");
		exit;
  	}
	
	public function insert() {
		$this->load->language('cms/logmail');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/logmail');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_logmail->addInformation($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('cms/logmail');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/logmail');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_logmail->editInformation($this->request->get['information_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('cms/logmail');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/logmail');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $information_id) {
				$this->model_cms_logmail->deleteInformation($information_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_send';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['filter_mail_to'])) {
			$filter_mail_to = $this->request->get['filter_mail_to'];
		} else {
			$filter_mail_to = NULL;
		}
		
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_mail_to'])) {
			$url .= '&filter_mail_to=' . $this->request->get['filter_mail_to'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/logmail/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/logmail/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['informations'] = array();

		$data = array(
			'filter_mail_to' => $filter_mail_to, 
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_total = $this->model_cms_logmail->getTotalInformations($data);
	
		$results = $this->model_cms_logmail->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/logmail/view&token=' . $this->session->data['token'] . '&information_id=' . $result['log_id'] . $url
			);
						
			$this->data['informations'][] = array (
				'information_id' => $result['log_id'],
				'mail_to'      => $result['mail_to'],
				'mail_from' => $result['mail_from'],
				'subject' => $result['subject'],
				'date_send' => $result['date_send'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_mail_to'] = $this->language->get('column_mail_to');
		$this->data['column_mail_from'] = $this->language->get('column_mail_from');
		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_date_send'] = $this->language->get('column_date_send');
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_mail_to'])) {
			$url .= '&filter_mail_to=' . $this->request->get['filter_mail_to'];
		}
		
		$this->data['sort_mail_to'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . '&sort=mail_to' . $url;
		$this->data['sort_subject'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . '&sort=subject' . $url;
		$this->data['sort_mail_from'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . '&sort=mail_from' . $url;
		$this->data['sort_date_send'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . '&sort=date_send' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		$this->data['filter_mail_to'] = $filter_mail_to;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'cms/logmail_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function view() {
		$this->load->language('cms/logmail');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/logmail');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_mail_to'] = $this->language->get('entry_mail_to');
		$this->data['entry_mail_from'] = $this->language->get('entry_mail_from');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_date_send'] = $this->language->get('entry_date_send');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		} 		
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['information_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/logmail/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/logmail/update&token=' . $this->session->data['token'] . '&information_id=' . $this->request->get['information_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['information_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_cms_logmail->getInformation($this->request->get['information_id']);
		}		
		
		if (isset($information_info['mail_to'])) {
			$this->data['mail_to'] = $information_info['mail_to'];
		} else {
			$this->data['mail_to'] = "";
		}
		
		if (isset($information_info['mail_from'])) {
			$this->data['mail_from'] = $information_info['mail_from'];
		} else {
			$this->data['mail_from'] = "";
		}
		
		if (isset($information_info['subject'])) {
			$this->data['subject'] = $information_info['subject'];
		} else {
			$this->data['subject'] = "";
		}
		
		if (isset($information_info['date_send'])) {
			$this->data['date_send'] = $information_info['date_send'];
		} else {
			$this->data['date_send'] = "";
		}
		
		if (isset($information_info['message'])) {
			$this->data['message'] = $information_info['message'];
		} else {
			$this->data['message'] = "";
		}
		
		
		$this->template = 'cms/logmail_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['information_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/logmail/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/logmail/update&token=' . $this->session->data['token'] . '&information_id=' . $this->request->get['information_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['information_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_cms_logmail->getInformation($this->request->get['information_id']);
		}		
		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($information_info)) {
			$this->data['title'] = $information_info['title'];
		} else {
			$this->data['title'] = "";
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($information_info)) {
			$this->data['description'] = $information_info['description'];
		} else {
			$this->data['description'] = "";
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_info)) {
			$this->data['status'] = $information_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($information_info)) {
			$this->data['keyword'] = $information_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($information_info)) {
			$this->data['sort_order'] = $information_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->template = 'cms/logmail_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/logmail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		
		if ((strlen(utf8_decode($this->request->post['title'])) < 3) || (strlen(utf8_decode($this->request->post['title'])) > 32)) {
			$this->error['title'] = $this->language->get('error_title');
		}
	
		if (strlen(utf8_decode($this->request->post['description'])) < 3) {
			$this->error['description'] = $this->language->get('error_description');
		}
		

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'cms/logmail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $information_id) {
			if ($this->config->get('config_account_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>