<?php
class ControllerStudentPackages extends Controller {
	private $error = array ();

	public function index() {
		$this->load->language('student/packages');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('student/packages');

		$this->getList();
	}
	
	public function success() {
		
		$this->cart->clear();
		unset($this->session->data['coupon']);
		unset($this->session->data['payment_method']);			

		$this->language->load('student/packages');
  
    	$this->document->title = $this->language->get('heading_title');
    	
    	$this->load->model('cms/information');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=student/packages',
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
    	
    	// set the information page for Payment Sucess
    	$information_info = $this->model_cms_information->getInformation('13');
    	
//    	print_r($information_info);
    	
    	$this->data['heading_title'] = $information_info['title'];

    	$this->data['text_message'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
		
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/account';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'student/help.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function cancel() {
		
		$this->cart->clear();
		unset($this->session->data['coupon']);
		unset($this->session->data['payment_method']);
		
		if(isset($this->request->get['package_id'])) {
			$url = "&package_id=".$this->request->get['package_id'];
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/packages/buynow&token=' . $this->session->data['token'] . $url);
		}					

		$this->language->load('student/packages');
  
    	$this->document->title = $this->language->get('heading_title');
    	
    	$this->load->model('cms/information');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=student/packages',
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
    	
    	// set the information page for Payment Sucess
    	$information_info = $this->model_cms_information->getInformation('14');
    	
//    	print_r($information_info);
    	
    	$this->data['heading_title'] = $information_info['title'];

    	$this->data['text_message'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
		
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/account';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'student/help.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function buynow() {
		
		
		$this->load->language('student/packages');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('student/packages');
		
		$url = '';
		
		if (isset ($this->request->get['package_id'])) {
			$url .= '&package_id=' . $this->request->get['package_id'];
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset ($this->request->post['coupon']) && $this->validate()) {
			
			$this->session->data['coupon'] = $this->request->post['coupon'];
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
    	}
		
		$this->getForm();
	}

	private function getList() {
		if (isset ($this->request->get['cancel'])) {
			$this->cart->clear();
			unset($this->session->data['coupon']);
			unset($this->session->data['payment_method']);			
		}
		
		if (isset ($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset ($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'package_name';
		}

		if (isset ($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';

		if (isset ($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset ($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset ($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->document->breadcrumbs = array ();

		$this->document->breadcrumbs[] = array (
			'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_home'
		), 'separator' => FALSE);

		$this->document->breadcrumbs[] = array (
			'href' => HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . $url,
			'text' => $this->language->get('heading_title'
		), 'separator' => ' :: ');

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=student/packages/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=student/packages/delete&token=' . $this->session->data['token'] . $url;

		$this->data['informations'] = array();

		$this->load->model('user/students');

		$student_info = $this->model_user_students->getStudent($this->session->data['user_id']);

		//print_r($student_info);
		if (!empty ($student_info['grades_id']))
			$filter_grade = $student_info['grades_id'];
		else
			$filter_grade = 0;


		$this->load->model('student/profile');
		$user_info = $this->model_student_profile->getStudent($this->session->data['user_id']);
		
		if($user_info['country'] == 'Canada') {
			if($user_info['state'] == 'Alberta' || $user_info['state'] == 'AB' )
				$user_currency = 'price_alb';
			else
				$user_currency = 'price_can';
		} else {
			$user_currency = 'price_usa';
		}

		$data = array (
			'filter_price' => $user_currency,
			'filter_grade' => $filter_grade,
			'filter_prepaid' => 1,
			'sort' => $sort,
			'order' => $order,
			'start' => ($page -1 ) * $this->config->get('config_admin_limit'), 'limit' => $this->config->get('config_admin_limit'));


		$information_total = $this->model_student_packages->getTotalInformations($data);

		$results = $this->model_student_packages->getInformations($data);

		foreach ($results as $result) {
			$action = array ();

			$action[] = array (
				'text' => $this->language->get('text_buynow'),
				'href' => HTTPS_SERVER . 'index.php?route=student/packages/buynow&token=' . $this->session->data['token'] . '&package_id=' . $result['package_id'] . $url
			);

			/*$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=student/packages/update&token=' . $this->session->data['token'] . '&package_id=' . $result['package_id'] . $url
			);*/

			$grades = "";
			$package_grades = $this->model_student_packages->getGradesByPackage($result['package_id']);
			if (count($package_grades) > 0) {

				foreach ($package_grades as $each_row) {
					$grades .= ", " . $each_row;
				};

				$grades = substr($grades, 2);
			}

			//			echo $grades."<hr />";
			
			$this->data['informations'][] = array (
				'package_id' => $result['package_id'],
				'name' => $result['package_name'],
				'grades' => $grades,
				'hours' => $result['hours'],
				'price' => $result[$user_currency],
				'selected' => isset ($this->request->post['selected']
			) && in_array($result['package_id'], $this->request->post['selected']), 'action' => $action);
		}
		
		$this->data['curr_symbol'] = $this->currency->getSymbolLeft();

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_hours'] = $this->language->get('column_hours');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_grades'] = $this->language->get('column_grades');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

		if (isset ($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset ($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset ($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset ($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . '&sort=package_name' . $url;
		$this->data['sort_grades'] = 'javascript:void(0)';
		$this->data['sort_price'] = 'javascript:void(0)';
		$this->data['sort_hours'] = HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . '&sort=hours' . $url;

		$url = '';

		if (isset ($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset ($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'student/packages_list.tpl';
		$this->children = array (
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

		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
		$this->data['text_coupon'] = $this->language->get('text_coupon');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_coupon'] = $this->language->get('button_coupon');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

		if (isset ($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset ($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		if (isset ($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
				
		$url = '';

		if (isset ($this->request->get['package_id'])) {
			$url .= '&package_id=' . $this->request->get['package_id'];
		}

		if (isset ($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset ($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset ($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->document->breadcrumbs = array ();

		$this->document->breadcrumbs[] = array (
			'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_home'
		), 'separator' => FALSE);

		$this->document->breadcrumbs[] = array (
			'href' => HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . $url,
			'text' => $this->language->get('heading_title'
		), 'separator' => ' :: ');

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/packages/buynow&token=' . $this->session->data['token'] . $url;
		
		$url = "&cancel=1";
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'] . $url;

		$package_price = 0;
		$package_grades = array ();
		if (isset ($this->request->get['package_id'])) {

			// Take only the one package in session 
			$this->cart->clear();
			$this->cart->add($this->request->get['package_id']);
			//
			
			$information_info = $this->model_student_packages->getInformation($this->request->get['package_id']);
			$package_price = $this->model_student_packages->getPackagePrice($this->session->data['user_id'], $this->request->get['package_id']);
		}

		if (isset ($information_info['package_name'])) {
			$this->data['name'] = $information_info['package_name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset ($information_info['package_description'])) {
			$this->data['description'] = html_entity_decode($information_info['package_description'], ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['description'] = '';
		}
		
		$curr_symbol = $this->currency->getSymbolLeft();
		$this->data['price'] = $curr_symbol." ".$package_price;
		
		#---------- Start Coupon Code --------------------#

		$this->data['coupon_status'] = $this->config->get('coupon_status');

		if (isset ($this->request->post['coupon'])) {
			$this->data['coupon'] = $this->request->post['coupon'];
		}
		elseif (isset ($this->session->data['coupon'])) {
			$this->data['coupon'] = $this->session->data['coupon'];
		} else {
			$this->data['coupon'] = '';
		}		
		
		if (isset($this->session->data['payment_method'])) {
			$this->data['confirm_order'] = 1;
		} else {
			$this->data['confirm_order'] = 0;
		}
		

		#------------ End Coupon Code  ---------------#

		#------------ Start Payment Methods -----------#
		$this->load->model('total/extension');

		$method_data = array ();
		// Need to check the address
		$payment_address = array ();

		$results = $this->model_total_extension->getExtensions('payment');

//				print_r($results);

		foreach ($results as $result) {
			
			
			if($result['key'] == 'pp_standard') continue;
			
			$this->load->model('payment/' . $result['key']);

			$method = $this-> {
				'model_payment_' . $result['key'] }
			->getMethod($payment_address);

			if ($method) {
				$method_data[$result['key']] = $method;
			}
		}

		$sort_order = array ();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);
		
		//print_r($method_data);

		$this->session->data['payment_methods'] = $method_data;

		//		print_r($method_data);

		if (isset ($this->session->data['payment_methods'])) {
			$this->data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$this->data['payment_methods'] = array ();
		}

		//		print_r($method_data);

		$this->data['payment_methods'] = $method_data;

		if (isset ($this->request->post['payment_method'])) {
			$this->data['payment'] = $this->request->post['payment_method'];
		}
		elseif (isset ($this->session->data['payment_method']['id'])) {
			$this->data['payment'] = $this->session->data['payment_method']['id'];
		} else {
			$this->data['payment'] = '';
		}

		#------------ End Payment Methods -----------#

		#------------ Start Total Code  ---------------#		

		$total_data = array ();
		$total = $package_price;
		$taxes = 0;
		$sort_order = array ();

		$results = $this->model_total_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);

			$this-> {
				'model_total_' . $result['key'] }
			->getTotal($total_data, $total, $taxes);		
			
		}

		$sort_order = array ();

		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $total_data);

		#------------ End Total Code  ---------------#				

		$this->data['totals'] = $total_data;
		$this->data['total'] = $total;

		$this->template = 'student/packages_form.tpl';

//		print_r($this->cart->getProduct());

		// Start processing order
		if($this->data['confirm_order']) {
			
			$this->load->model('total/order');
			
			$data = array();
			
//			print_r($this->user->getUserData());
					
			$data['customer_id'] = $this->user->getId();
			$data['package_id'] = $this->request->get['package_id'];
			$data['invoice_id'] = '0';
			$data['invoice_pk'] = '0';
			$data['customer_group_id'] = $this->user->getUserGroupId();
			$data['firstname'] = $this->user->getFirstName();
			$data['lastname'] = $this->user->getLastName();
			$data['email'] = $this->user->getEmail();
			$data['telephone'] = $this->user->getTelephone();
			$data['workphone'] = $this->user->getWorkPhone();
			
			if (isset($this->session->data['payment_method']['title'])) {
			$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			$product_data = array();
			$product = $this->cart->getProduct(); 			
      		$product_data[] = array(
        		'product_id' => $product['product_id'],
				'name'       => $product['name'],
        		'model'      => $product['model'],
				'quantity'   => $product['quantity'],
				'price'      => $product['price'],
        		'total'      => $product['total']
      		);
			
      		      		
			$data['products'] = $product_data;			
			$data['total_hours'] = $product['hours'];
			$data['left_hours'] = $product['hours'];
			$data['totals'] = $total_data;
			$data['comment'] = '';
			$data['total'] = $total;
			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency'] = $this->currency->getCode();
			$data['value'] = $this->currency->getValue($this->currency->getCode());
			
			if (isset($this->session->data['coupon'])) {
				$this->load->model('total/coupon');
			
				$coupon = $this->model_total_coupon->getCoupon($this->session->data['coupon']);
				
				if ($coupon) {
					$data['coupon_id'] = $coupon['coupon_id'];
				} else {
					$data['coupon_id'] = 0;
				}
			} else {
				$data['coupon_id'] = 0;
			}
			
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
			$this->data['paypal'] = $data;
			
			$this->session->data['order_id'] = $this->model_total_order->create($data);
			
			$url = "&package_id=".$this->request->get['package_id'];
			$this->session->data['payment_back'] = HTTPS_SERVER . 'index.php?route=student/packages/cancel&token=' . $this->session->data['token'] . $url;
			$this->session->data['payment_continue'] =  HTTPS_SERVER . 'index.php?route=student/packages/success&token=' . $this->session->data['token'];
			$this->session->data['payment_cancel'] =  HTTPS_SERVER . 'index.php?route=student/packages/cancel&token=' . $this->session->data['token'];
			
			
			$this->children = array (
				'common/header',
				'common/footer',
				'payment_student/' . $this->session->data['payment_method']['id']
			);
			
		} else {
			$this->children = array (
				'common/header',
				'common/footer'
			);			
		}
		
		//print_r($this);
		//exit('a');	
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'student/packages')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (strlen(utf8_decode($this->request->post['description'])) < 3) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset ($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validate() {
		
		if(!empty($this->request->post['coupon'])) {
						
			$this->load->model('total/coupon');
	
			$this->language->load('checkout/payment');
	
			$coupon = $this->model_total_coupon->getCoupon($this->request->post['coupon']);
			
			if (!$coupon) {
				$this->error['warning'] = $this->language->get('error_coupon');
			}
		}

		if (!isset ($this->request->post['payment_method'])) {
				$this->error['warning'] = $this->language->get('error_payment');
		} else {
			if (!isset ($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$this->error['warning'] = $this->language->get('error_payment');
			}
		}


		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* Softronikx Technologies */
	public function packages() {
	
		$this->load->language('student/packages');
		$this->document->title = $this->language->get('heading_title_packages');
		$this->load->model('sale/order');
		$this->load->model('student/profile');
		
    	//$this->getList();
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$filter_tutor_name = $this->request->get['filter_tutor_name'];
		} else {
			$filter_tutor_name = NULL;
		}

		if (isset($this->request->get['filter_student_name'])) {
			$filter_student_name = $this->request->get['filter_student_name'];
		} else {
			$filter_student_name = NULL;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}		
		
		$url = '';

		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
			
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
						
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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_student'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_packages'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=student/packages/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=student/packages/delete&token=' . $this->session->data['token'] . $url;

		$this->data['assignments'] = array();

		$data = array(
			'filter_tutor_name'              => $filter_tutor_name, 
			'filter_student_name'             => $filter_student_name, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$packages_total = 5; //$this->model_sale_order->getTotalPackages($data);
		$results = $this->model_sale_order->getPackages($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_reminder'),
				'href' => HTTPS_SERVER . 'index.php?route=student/packages/reminder&token=' . $this->session->data['token'] . '&student_id=' . $result['customer_id'] . $url
			);
			
			//$student_info = $this->model_student_profile->getStudent($result['customer_id']);
			
			$this->data['packages'][] = array(
				'student_name' => $result['name'],
				'package_name'    => $result['package_name'],
				'total_hours'    => $result['total_hours'],
				'left_hours'    => $result['left_hours'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title_packages');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_package_name'] = $this->language->get('column_package_name');
		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
		$this->data['column_left_hours'] = $this->language->get('column_left_hours');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_unassing'] = $this->language->get('button_unassing');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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
		
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . '&sort=name' . $url;
		$this->data['sort_package_name'] = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . '&sort=package_name' . $url;
		$this->data['sort_total_hours'] = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . '&sort=total_hours' . $url;
		$this->data['sort_left_hours'] = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . '&sort=left_hours' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . '&sort=date_added' . $url;
		
		$pagination = new Pagination();
		$pagination->total = $assignment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_student_name'] = $filter_student_name;
		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_date_added'] = $filter_date_added;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'student/student_packages.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	
	
	}

	/* Function by Softronikx Technologies */
	public function reminder() {
	
		if (isset ($this->request->get['student_id'])) {		
			
			$this->load->model('user/user');
			$student = $this->model_user_user->getUser($this->request->get['student_id']);
			
			// Email Reminder to Student			
			$subject = 'Reminder: Few Hours left in your Package';
			$message = 'Hello @STUDENT_NAME@, <br><br>
			You have few hours remaining in your package. <br>
			Purchase more hours to continue availaing discounted hours.<br>
			Login to your account to purchase discounted hours.
			<br><br> Regards, <br>Team Learnon!';
			
			// Here you can define keys for replace before sending mail to Student
			$replace_info = array(
							'STUDENT_NAME' => $student['firstname'].' '.$student['lastname'], 
						);
			
			foreach($replace_info as $rep_key => $rep_text) {
				$message = str_replace('@'.$rep_key.'@', $rep_text, $message);
			}
						
			$mail = new Mail($this);
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($student['email']);
	  		$mail->setFrom($this->config->get('config_email'));
	  		$mail->setSender($this->config->get('config_name'));
	  		$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
      		$mail->send();
			
			// Send to additional alert emails if account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match(EMAIL_PATTERN, $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
			
			$this->session->data['success'] = 'Reminder sent successfully!';
			
			$this->packages();
		}
	}
}
?>