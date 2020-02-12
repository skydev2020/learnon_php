<?php
class ControllerCmsPackages extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/packages');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('cms/packages');

		$this->getList();
	}

	public function insert() {
		$this->load->language('cms/packages');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/packages');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_packages->addInformation($this->request->post);
			log_activity("Package Added", "A new package added.");
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('cms/packages');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/packages');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_packages->editInformation($this->request->get['package_id'], $this->request->post);
			log_activity("Package Update", "Package details updated.");
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('cms/packages');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/packages');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $package_id) {
				$this->model_cms_packages->deleteInformation($package_id);
			}
			log_activity("Package(s) Deleted", "Package(s) Deleted.");
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url);
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
			$sort = 'package_name';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/packages/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/packages/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
				
		$information_total = $this->model_cms_packages->getTotalInformations();
	
		$results = $this->model_cms_packages->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/packages/update&token=' . $this->session->data['token'] . '&package_id=' . $result['package_id'] . $url
			);
			
			$grades = "";			
			$package_grades = $this->model_cms_packages->getGradesByPackage($result['package_id']);
			if(count($package_grades) > 0) {
				
				foreach($package_grades as $each_row) {
					$grades .= ", ".$each_row;
				};	
				
				$grades = substr($grades, 2);
			}
			
//			echo $grades."<hr />";
						
			$this->data['informations'][] = array(
				'package_id' => $result['package_id'],
				'name'      => $result['package_name'],
				'grades' => $grades,
				'student' => $result['student_name'],				
				'price_usa' => $result['price_usa'],
				'price_alb' => $result['price_alb'],
				'price_can' => $result['price_can'],
				'hours' => $result['hours'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['package_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_hours'] = $this->language->get('column_hours');
		$this->data['column_student'] = $this->language->get('column_student');
		$this->data['column_grades'] = $this->language->get('column_grades');
		$this->data['column_price_usa'] = $this->language->get('column_price_usa');
		$this->data['column_price_alb'] = $this->language->get('column_price_alb');
		$this->data['column_price_can'] = $this->language->get('column_price_can');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['curr_symbol'] = '$'; // $this->currency->getSymbolLeft()		
		
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
		
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . '&sort=package_name' . $url;
		$this->data['sort_grades'] = 'javascript:void(0)';
		$this->data['sort_hours'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . '&sort=hours' . $url;
		$this->data['sort_student'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . '&sort=student_id' . $url;
		$this->data['sort_price_usa'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . '&sort=price_usa' . $url;
		$this->data['sort_price_can'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . '&sort=price_can' . $url;
		$this->data['sort_price_alb'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . '&sort=price_alb' . $url;
		
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
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'cms/packages_list.tpl';
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
		$this->data['entry_grades'] = $this->language->get('entry_grades');
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
		
		if (isset($this->error['hours'])) {
			$this->data['error_hours'] = $this->error['hours'];
		} else {
			$this->data['error_hours'] = '';
		}
		
		if (isset($this->error['prepaid'])) {
			$this->data['error_prepaid'] = $this->error['prepaid'];
		} else {
			$this->data['error_prepaid'] = '';
		}
		
		if (isset($this->error['grades'])) {
			$this->data['error_grades'] = $this->error['grades'];
		} else {
			$this->data['error_grades'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		
		if (isset($this->error['student_id'])) {
			$this->data['error_student_id'] = $this->error['student_id'];
		} else {
			$this->data['error_student_id'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['package_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/packages/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/packages/update&token=' . $this->session->data['token'] . '&package_id=' . $this->request->get['package_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'] . $url;
		
		$all_grade_ids = array();
		$package_grades = array();		
		if (isset($this->request->get['package_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_cms_packages->getInformation($this->request->get['package_id']);
			$package_grades = $this->model_cms_packages->getGradesByPackage($this->request->get['package_id']);
			$all_grade_ids = array_keys($package_grades);
		}		
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($information_info)) {
			$this->data['name'] = $information_info['package_name'];
		} else {
			$this->data['name'] = "";
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($information_info)) {
			$this->data['description'] = $information_info['package_description'];
		} else {
			$this->data['description'] = "";
		}

		if (isset($this->request->post['hours'])) {
			$this->data['hours'] = $this->request->post['hours'];
		} elseif (isset($information_info)) {
			$this->data['hours'] = $information_info['hours'];
		} else {
			$this->data['hours'] = 1;
		}
		
		if (isset($this->request->post['prepaid'])) {
			$this->data['prepaid'] = $this->request->post['prepaid'];
		} elseif (isset($information_info)) {
			$this->data['prepaid'] = $information_info['prepaid'];
		} else {
			$this->data['prepaid'] = 1;
		}
		
		if (isset($this->request->post['grades'])) {
			$this->data['all_grade_ids'] = array_values($this->request->post['grades']);
		} elseif (isset($information_info)) {
			$this->data['all_grade_ids'] = $all_grade_ids;
		} else {
			$this->data['all_grade_ids'] = array();
		}
		
		if (isset($this->request->post['student_id'])) {
			$this->data['student_id'] = $this->request->post['student_id'];
		} elseif (isset($information_info)) {
			$this->data['student_id'] = $information_info['student_id'];
		} else {
			$this->data['student_id'] = "";
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
		
		$all_students = array();
		$this->load->model('user/students');
		$results = $this->model_user_students->getAllStudents(array('sort'=>'name', 'filter_approved' => 1));
		foreach ($results as $result) {
			$all_students[] = array(
				'student_id' => $result['user_id'],
				'student_name' => $result['firstname'].' '.$result['lastname'],
			);
		}		
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['students'] = $all_students;
		
		$all_grades = $this->model_cms_packages->getAllGrades();
		$this->data['all_grades'] = $all_grades;
		
		$this->template = 'cms/packages_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/packages')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
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
		if (!$this->user->hasPermission('modify', 'cms/packages')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $package_id) {
			if ($this->config->get('config_account_id') == $package_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $package_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByInformationId($package_id);

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