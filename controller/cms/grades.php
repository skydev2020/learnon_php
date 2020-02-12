<?php
class ControllerCmsGrades extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/grades');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('cms/grades');

		$this->getList();
	}

	public function insert() {
		$this->load->language('cms/grades');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/grades');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_cms_grades->addInformation($this->request->post);
			
			log_activity("Grade Added", "A new grade added.");
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('cms/grades');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/grades');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_cms_grades->editInformation($this->request->get['grades_id'], $this->request->post);
			
			log_activity("Grade Update", "Grade details updated.");
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('cms/grades');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/grades');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $grades_id) {
				$this->model_cms_grades->deleteInformation($grades_id);
			}
			
			log_activity("Grade(s) Deleted", "Grade(s) Deleted.");
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url);
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
			$sort = 'grades_id';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/grades/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/grades/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
				
		$information_total = $this->model_cms_grades->getTotalInformations();
	
		$results = $this->model_cms_grades->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/grades/update&token=' . $this->session->data['token'] . '&grades_id=' . $result['grades_id'] . $url
			);
			
			$subjects = "";			
			$grade_subjects = $this->model_cms_grades->getSubjectsByGrades($result['grades_id']);
			if(count($grade_subjects) > 0) {
				
				foreach($grade_subjects as $each_row) {
					$subjects .= ", ".$each_row;
				};	
				
				$subjects = substr($subjects, 2);
			}
						
			$this->data['informations'][] = array(
				'grades_id' => $result['grades_id'],
				'name'      => $result['grades_name'],
				'subjects' 	=> $subjects,
				'price_usa' => $result['price_usa'],
				'price_alb' => $result['price_alb'],
				'price_can' => $result['price_can'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['grades_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_hours'] = $this->language->get('column_hours');
		$this->data['column_student'] = $this->language->get('column_student');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_price_usa'] = $this->language->get('column_price_usa');
		$this->data['column_price_alb'] = $this->language->get('column_price_alb');
		$this->data['column_price_can'] = $this->language->get('column_price_can');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['curr_symbol'] = $this->currency->getSymbolLeft();		
		
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
		
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . '&sort=grades_name' . $url;
		$this->data['sort_price_usa'] = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . '&sort=price_usa' . $url;
		$this->data['sort_price_can'] = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . '&sort=price_can' . $url;
		$this->data['sort_price_alb'] = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . '&sort=price_alb' . $url;
		
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
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'cms/grades_list.tpl';
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
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_hours'] = $this->language->get('entry_hours');
		$this->data['entry_prepaid'] = $this->language->get('entry_prepaid');
		$this->data['entry_subjects'] = $this->language->get('entry_subjects');
		$this->data['entry_student_id'] = $this->language->get('entry_student_id');
		$this->data['entry_price_usa'] = $this->language->get('entry_price_usa');
		$this->data['entry_price_alb'] = $this->language->get('entry_price_alb');
		$this->data['entry_price_can'] = $this->language->get('entry_price_can');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['subjects'])) {
			$this->data['error_subjects'] = $this->error['subjects'];
		} else {
			$this->data['error_subjects'] = '';
		}
		
		if (isset($this->error['price_usa'])) {
			$this->data['error_price_usa'] = $this->error['price_usa'];
		} else {
			$this->data['error_price_usa'] = '';
		}
		
		if (isset($this->error['price_alb'])) {
			$this->data['error_price_alb'] = $this->error['price_alb'];
		} else {
			$this->data['error_price_alb'] = '';
		}
		
		if (isset($this->error['price_can'])) {
			$this->data['error_price_can'] = $this->error['price_can'];
		} else {
			$this->data['error_price_can'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['grades_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/grades/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/grades/update&token=' . $this->session->data['token'] . '&grades_id=' . $this->request->get['grades_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'] . $url;
		
		$all_grades_ids = array();
		$package_grades = array();		
		if (isset($this->request->get['grades_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_cms_grades->getInformation($this->request->get['grades_id']);
			$grade_subjects = $this->model_cms_grades->getSubjectsByGrades($this->request->get['grades_id']);
			$all_subjects_ids = array_keys($grade_subjects);
		}		
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($information_info)) {
			$this->data['name'] = $information_info['grades_name'];
		} else {
			$this->data['name'] = "";
		}
		
		if (isset($this->request->post['grades'])) {
			$this->data['all_subjects_ids'] = array_values($this->request->post['subjects']);
		} elseif (isset($information_info)) {
			$this->data['all_subjects_ids'] = $all_subjects_ids;
		} else {
			$this->data['all_subjects_ids'] = array();
		}
		
		if (isset($this->request->post['price_usa'])) {
			$this->data['price_usa'] = $this->request->post['price_usa'];
		} elseif (isset($information_info)) {
			$this->data['price_usa'] = $information_info['price_usa'];
		} else {
			$this->data['price_usa'] = 0;
		}
		
		if (isset($this->request->post['price_alb'])) {
			$this->data['price_alb'] = $this->request->post['price_alb'];
		} elseif (isset($information_info)) {
			$this->data['price_alb'] = $information_info['price_alb'];
		} else {
			$this->data['price_alb'] = 0;
		}
		
		if (isset($this->request->post['price_can'])) {
			$this->data['price_can'] = $this->request->post['price_can'];
		} elseif (isset($information_info)) {
			$this->data['price_can'] = $information_info['price_can'];
		} else {
			$this->data['price_can'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_info)) {
			$this->data['status'] = $information_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		$all_subjects = $this->model_cms_grades->getAllSubjects();
		$this->data['all_subjects'] = $all_subjects;
				
		$this->template = 'cms/grades_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/grades')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
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
		if (!$this->user->hasPermission('modify', 'cms/grades')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $grades_id) {
			if ($this->config->get('config_account_id') == $grades_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $grades_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByInformationId($grades_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
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