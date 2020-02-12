<?php
class ControllerCmsNotifications extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/notifications');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/notifications');
		$this->getList();
	}
 
	public function delete() {
		$this->load->language('cms/notifications');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/notifications');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $notification_id) {
				$this->model_cms_notifications->deleteInformation($notification_id);
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . $url);
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/notifications/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/notifications/delete&token=' . $this->session->data['token'] . $url;	
		$this->data['informations'] = array();
		
		
		$users_array = array();
		
		if($this->user->getUserGroupId() == '4')
			$users_array[] = 1;
					
		$users_array[] = $this->session->data['user_id'];
		
		$data = array(
			'filter_to_users' => $users_array,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_total = $this->model_cms_notifications->getTotalInformations($data);
		$results = $this->model_cms_notifications->getInformations($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/notifications/view&token=' . $this->session->data['token'] . '&notification_id=' . $result['notification_id'] . $url
			);	
			$this->data['informations'][] = array (
				'notification_id' => $result['notification_id'],
				'notification_from' => $result['notification_from'],
				'group_name' => $result['group_name'],
				'subject' => $result['subject'],
				'date_send' => $result['date_send'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['notification_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_notification_from'] = $this->language->get('column_notification_from');
		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_date_send'] = $this->language->get('column_date_send');
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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
		
		$this->data['sort_notification_to'] = HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . '&sort=notification_to' . $url;
		$this->data['sort_notification_from'] = HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . '&sort=notification_from' . $url;
		$this->data['sort_date_send'] = HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . '&sort=date_send' . $url;
		
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
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'cms/notifications_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function view() {
		$this->load->language('cms/notifications');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/notifications');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['entry_notification_from'] = $this->language->get('entry_notification_from');
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['notification_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/notifications/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/notifications/update&token=' . $this->session->data['token'] . '&notification_id=' . $this->request->get['notification_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/notifications&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['notification_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_cms_notifications->getInformation($this->request->get['notification_id']);
		}		
		
//		print_r($information_info);
		
		if (isset($information_info['notification_from'])) {
			$this->data['notification_from'] = $information_info['notification_from'];
		} else {
			$this->data['notification_from'] = "";
		}
		if (isset($information_info['group_name'])) {
			$this->data['group_name'] = $information_info['group_name'];
		} else {
			$this->data['group_name'] = "";
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
		
		$this->template = 'cms/notifications_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
	}



	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'cms/notifications')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $notification_id) {
			if ($this->config->get('config_account_id') == $notification_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			if ($this->config->get('config_checkout_id') == $notification_id) {
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