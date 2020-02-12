<?php
class ControllerCmsLogactivity extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/logactivity');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('cms/logactivity');

		$this->getList();
	}
 
	public function delete() {
		$this->load->language('cms/logactivity');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/logactivity');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $information_id) {
				$this->model_cms_logactivity->deleteInformation($information_id);
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . $url);
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
			$sort = 'date_added';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/logactivity/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/logactivity/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_total = $this->model_cms_logactivity->getTotalInformations();
	
		$results = $this->model_cms_logactivity->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/logactivity/view&token=' . $this->session->data['token'] . '&information_id=' . $result['activity_id'] . $url
			);
						
			$this->data['informations'][] = array (
				'information_id' => $result['activity_id'],
				'user_name'      => $result['user_name'],
				'group_name' => $result['group_name'],
				'activity' => $result['activity'],
				'date_added' => $result['date_added'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_user_name'] = $this->language->get('column_user_name');
		$this->data['column_user_group'] = $this->language->get('column_user_group');
		$this->data['column_activity'] = $this->language->get('column_activity');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
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
		
		$this->data['sort_user_name'] = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . '&sort=user_name' . $url;
		$this->data['sort_group_name'] = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . '&sort=group_name' . $url;
		$this->data['sort_activity'] = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . '&sort=activity' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . '&sort=date_added' . $url;
		
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
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'cms/logactivity_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function view() {
		$this->load->language('cms/logactivity');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/logactivity');

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_user_name'] = $this->language->get('entry_user_name');
		$this->data['entry_group_name'] = $this->language->get('entry_group_name');
		$this->data['entry_activity'] = $this->language->get('entry_activity');
		$this->data['entry_date_added'] = $this->language->get('entry_date_added');
		$this->data['entry_activity_details'] = $this->language->get('entry_activity_details');
		$this->data['entry_ip_address'] = $this->language->get('entry_ip_address');
		$this->data['entry_platform'] = $this->language->get('entry_platform');

		$this->data['button_back'] = $this->language->get('button_back');

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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['information_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/logactivity/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/logactivity/update&token=' . $this->session->data['token'] . '&information_id=' . $this->request->get['information_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['information_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_cms_logactivity->getInformation($this->request->get['information_id']);
		}		
		
		if (isset($information_info['user_name'])) {
			$this->data['user_name'] = $information_info['user_name'];
		} else {
			$this->data['user_name'] = "";
		}
		
		if (isset($information_info['group_name'])) {
			$this->data['group_name'] = $information_info['group_name'];
		} else {
			$this->data['group_name'] = "";
		}
		
		if (isset($information_info['activity'])) {
			$this->data['activity'] = $information_info['activity'];
		} else {
			$this->data['activity'] = "";
		}
		
		if (isset($information_info['date_added'])) {
			$this->data['date_added'] = $information_info['date_added'];
		} else {
			$this->data['date_added'] = "";
		}
		
		if (isset($information_info['activity_details'])) {
			$this->data['activity_details'] = $information_info['activity_details'];
		} else {
			$this->data['activity_details'] = "";
		}
		
		if (isset($information_info['ip_address'])) {
			$this->data['ip_address'] = $information_info['ip_address'];
		} else {
			$this->data['ip_address'] = "";
		}
		
		if (isset($information_info['platform'])) {
			$this->data['platform'] = $information_info['platform'];
		} else {
			$this->data['platform'] = "";
		}
		
		$this->template = 'cms/logactivity_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'cms/logactivity')) {
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