<?php
class ControllerStudentInvoice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('student/invoice');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('student/invoice');
		$this->getList();
	}

	public function addPackagesToInvoices($result, $data) {
		/*
		 $first_row = $result[0];
		 $last_row = end($result);
		 print_r($first_row['invoice_date']);
		 print_r($last_row['invoice_date']);
		 print_r($result);
		 print_r($data);*/

		foreach($data as $key => $each_row) {
			/*
			 *
				&& (($each_row['date_added'] >= $first_row['invoice_date'] && $each_row['date_added'] <= $last_row['invoice_date'])
				|| (0))
			 * */
			if(!empty($each_row['package_id']))
			$result[] = array (
					'order_id' => $each_row['order_id'],
					'invoice_id' => $each_row['order_id'],
		            'invoice_num' => $each_row['invoice_id'],
		            'invoice_prefix' => $each_row['invoice_prefix'],
		            'total_hours' => $each_row['total_hours'],
		            'total_amount' => number_format($each_row['total'], 2),
		            'invoice_status' => $each_row['status'],
		            'invoice_date' => $each_row['date_added'],
		            'send_date' => $each_row['date_added'],
		            'student_name' => $each_row['name']				
			);
		}

		//		print_r($result);
		return $result;
	}

	public function cancel() {

		$this->cart->clear();
		unset($this->session->data['coupon']);
		unset($this->session->data['payment_method']);

		if(isset($this->request->get['invoice_id'])) {
			$url = "&invoice_id=".$this->request->get['invoice_id'];
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/invoice/paynow&token=' . $this->session->data['token'] . $url);
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

	public function paynow() {


		$this->load->language('student/invoice');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('student/invoice');

		$url = '';

		if (isset ($this->request->get['invoice_id'])) {
			$url .= '&invoice_id=' . $this->request->get['invoice_id'];
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset ($this->request->post['coupon']) && $this->validatePayment()) {

			$this->session->data['coupon'] = $this->request->post['coupon'];
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePayment()) {

			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		}

		$this->getFormPayment();
	}

	public function package() {
		$this->load->language('student/invoice');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('student/invoice');


		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_invoice_num'] = $this->language->get('entry_invoice_num');
		$this->data['entry_invoice_date'] = $this->language->get('entry_invoice_date');
		$this->data['entry_total_hours'] = $this->language->get('entry_total_hours');
		$this->data['entry_total_amount'] = $this->language->get('entry_total_amount');
		$this->data['entry_send_date'] = $this->language->get('entry_send_date');
		$this->data['entry_num_of_sessions'] = $this->language->get('entry_num_of_sessions');
		$this->data['entry_paid_amount'] = $this->language->get('entry_paid_amount');
		$this->data['entry_balance_amount'] = $this->language->get('entry_balance_amount');
		$this->data['entry_invoice_notes'] = $this->language->get('entry_invoice_notes');

		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['token'] = $this->session->data['token'];

		$url = '&invoice_id=' . $this->request->get['invoice_id'];
		$url .= '&print=1';
		$this->data['button_print'] = $this->language->get('button_print');
		$this->data['print_invoice'] = HTTPS_SERVER . 'index.php?route=student/invoice/package&token=' . $this->session->data['token'] . $url;

		if(isset($this->request->get['print']))
		$this->data['is_print'] = 0;
		else
		$this->data['is_print'] = 1;
			
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['back'] = HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url;

      		$this->load->model('sale/order');

      		$order_detail = $this->model_sale_order->getOrder($this->request->get['invoice_id']);

      		//		print_r($order_detail);

      		$this->data['invoice_info'] = array(
			'invoice_num'    => $order_detail['invoice_prefix']."-".$order_detail['invoice_id'],
			'total_hours'    => '-',
			'invoice_date'           => date("F d, Y", strtotime($order_detail['date_added'])),
			'send_date'           => date("F d, Y", strtotime($order_detail['date_modified'])),
			'total_amount'          => '-',
			'paid_amount'           => '-',
			'balance_amount'           => '-',
			'invoice_notes'           => '-',
			'num_of_sessions'     => '-'
			);

			$this->load->model('cms/packages');
			$package_detail = $this->model_cms_packages->getInformation($order_detail['package_id']);
			$package_detail['total_hours'] = $order_detail['total_hours'];
			$this->data['package_detail'] = $package_detail;

			//		print_r($order_detail);

			$this->load->model('student/profile');
			$student_info = $this->model_student_profile->getStudent($order_detail['customer_id']);
			//		print_r($student_info);
			$this->data['student_info'] = array(
				'parent_name'    => $student_info['parents_first_name'].' '.$student_info['parents_last_name'],
				'student_name'    => $student_info['firstname'].' '.$student_info['lastname'],
				'street_address'    => $student_info['address'],
				'city'    => $student_info['city'],
				'state'    => $student_info['state'],
				'postcode'    => $student_info['pcode']
			);

			$this->data['config_name'] = $this->config->get('config_name');
			$this->data['config_address'] = nl2br($this->config->get('config_address'));

			$this->template = 'student/package_invoice.tpl';

			if(!isset($this->request->get['print']))
			$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function view() {
		$this->load->language('student/invoice');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('student/invoice');
		$this->getForm();
	}

	private function getList() {
		if (isset ($this->request->get['cancel'])) {
			$this->cart->clear();
			unset($this->session->data['coupon']);
			unset($this->session->data['payment_method']);
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
			
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'send_date';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['filter_invoice_num'])) {
			$filter_invoice_num = $this->request->get['filter_invoice_num'];
		} else {
			$filter_invoice_num = NULL;
		}
		if (isset($this->request->get['filter_invoice_date'])) {
			$filter_invoice_date = $this->request->get['filter_invoice_date'];
		} else {
			$filter_invoice_date = NULL;
		}
		if (isset($this->request->get['filter_total_hours'])) {
			$filter_total_hours = $this->request->get['filter_total_hours'];
		} else {
			$filter_total_hours = NULL;
		}
		if (isset($this->request->get['filter_total_amount'])) {
			$filter_total_amount = $this->request->get['filter_total_amount'];
		} else {
			$filter_total_amount = NULL;
		}
		if (isset($this->request->get['filter_send_date'])) {
			$filter_send_date = $this->request->get['filter_send_date'];
		} else {
			$filter_send_date = NULL;
		}

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['heading_title'] = $this->language->get('heading_title');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_invoice_num'] = $this->language->get('column_invoice_num');
      		$this->data['column_invoice_date'] = $this->language->get('column_invoice_date');
      		$this->data['column_invoice_status'] = $this->language->get('column_invoice_status');
      		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
      		$this->data['column_total_amount'] = $this->language->get('column_total_amount');
      		$this->data['column_send_date'] = $this->language->get('column_send_date');
      		$this->data['column_action'] = $this->language->get('column_action');

      		$this->data['button_filter'] = $this->language->get('button_filter');

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

      		if (isset($this->request->get['filter_invoice_num'])) {
      			$url .= '&filter_invoice_num=' . $this->request->get['filter_invoice_num'];
      		}
      		if (isset($this->request->get['filter_invoice_date'])) {
      			$url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
      		}
      		if (isset($this->request->get['filter_total_hours'])) {
      			$url .= '&filter_total_hours=' . $this->request->get['filter_total_hours'];
      		}
      		if (isset($this->request->get['filter_total_amount'])) {
      			$url .= '&filter_total_amount=' . $this->request->get['filter_total_amount'];
      		}
      		if (isset($this->request->get['filter_send_date'])) {
      			$url .= '&filter_send_date=' . $this->request->get['filter_send_date'];
      		}
      		if (isset($this->request->get['page'])) {
      			$url .= '&page=' . $this->request->get['page'];
      		}
      		if ($order == 'ASC') {
      			$url .= '&order=DESC';
      		} else {
      			$url .= '&order=ASC';
      		}


      		$this->data['sort_invoice_num'] = 'javascript:void(0)';
      		$this->data['sort_invoice_date'] = 'javascript:void(0)';
      		$this->data['sort_total_hours'] = 'javascript:void(0)';
      		$this->data['sort_total_amount'] = 'javascript:void(0)';
      		$this->data['sort_invoice_status'] = 'javascript:void(0)';
      		$this->data['sort_send_date'] = HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . '&sort=send_date' . $url;

      		$url = '';

      		if (isset($this->request->get['filter_invoice_num'])) {
      			$url .= '&filter_invoice_num=' . $this->request->get['filter_invoice_num'];
      		}
      		if (isset($this->request->get['filter_invoice_date'])) {
      			$url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
      		}
      		if (isset($this->request->get['filter_total_hours'])) {
      			$url .= '&filter_total_hours=' . $this->request->get['filter_total_hours'];
      		}
      		if (isset($this->request->get['filter_total_amount'])) {
      			$url .= '&filter_total_amount=' . $this->request->get['filter_total_amount'];
      		}
      		if (isset($this->request->get['filter_send_date'])) {
      			$url .= '&filter_send_date=' . $this->request->get['filter_send_date'];
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

      		$data = array(
			'filter_invoice_num'              => $filter_invoice_num, 
			'filter_invoice_date'             => $filter_invoice_date, 
			'filter_total_hours'        => $filter_total_hours,
			'filter_total_amount'              => $filter_total_amount, 
			'filter_send_date'             => $filter_send_date, 
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		$this->data['filter_invoice_num']=$filter_invoice_num;
      		$this->data['filter_invoice_date']=$filter_invoice_date;
      		$this->data['filter_total_hours']=$filter_total_hours;
      		$this->data['filter_total_amount']=$filter_total_amount;
      		$this->data['filter_send_date']=$filter_send_date;
      		$this->data['sort']=$sort;
      		$this->data['order']=$order;


      		$this->load->model('sale/order');

      		$packages_data = array(
			'filter_invoice_num'              => $filter_invoice_num, 
			'filter_invoice_date'             => $filter_invoice_date,
			'filter_customer_id'             => $this->user->getId()
      		);

      		$packages_total = $this->model_sale_order->getTotalOrders($packages_data);

      		$results = $this->model_sale_order->getOrders($data);


      		$this->data['invoices'] = array();
      		$invoice_total = $this->model_student_invoice->getTotalInvoices($data);
      		$results = $this->model_student_invoice->getInvoices($data);
      		 
      		//print_r($results);

      		if($packages_total) {
      			$packages_results = $this->model_sale_order->getOrders($packages_data);
      			$results = $this->addPackagesToInvoices($results, $packages_results);
      		}

      		foreach ($results as $result) {

      			$action = array();

      			if(empty($result['invoice_pk']) and empty($result['invoice_num']))
      			continue;

      			if(!isset($result['order_id'])) {

      				$invoice_order_id = $this->model_student_invoice->getOrderIdByInvoiceId($result['invoice_id']);

      				if(!empty($invoice_order_id['order_id'])) {

      					if($invoice_order_id['order_status_id'] == '5')
      					$invoice_status = 'Paid';
      					else
      					$invoice_status = 'Hold For Approval';

      				} else if($result['invoice_status'] != "Paid") {

      					$invoice_status = ($result['invoice_status'] == 'Reminder Sent') ? 'Unpaid' : $result['invoice_status'];

      					$action[] = array(
						'text' => "Pay Now",
						'href' => HTTPS_SERVER . 'index.php?route=student/invoice/paynow&token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url
      					);
      				}
      			} else {
      				if($result['invoice_status'] == "Pending")
      				$invoice_status = 'Hold For Approval';
      				else
      				$invoice_status = $result['invoice_status'];
      			}

      			if(isset($result['order_id'])) {
      				$action[] = array(
					'text' => "View Details",
					'href' => HTTPS_SERVER . 'index.php?route=student/invoice/package&token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url
      				);
      			} else {
      				$action[] = array(
					'text' => "View Details",
					'href' => HTTPS_SERVER . 'index.php?route=student/invoice/view&token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url
      				);
      			}

      			$this->data['invoices'][] = array(
				'invoice_id' => $result['invoice_id'],
				'invoice_num'      => $result['invoice_prefix']."-".$result['invoice_num'],
				'total_hours' => $result['total_hours'],
				'total_amount' => $result['total_amount'],
				'invoice_status' => $invoice_status,
				'send_date' => date($this->language->get('date_format_short'), strtotime($result['send_date'])),
				'invoice_date' => date($this->language->get('date_format_short'), strtotime($result['invoice_date'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['invoice_id'], $this->request->post['selected']),
				'action'     => $action
      			);
      		}

      		$pagination = new Pagination();
      		$pagination->total = $invoice_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url . '&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		$this->template = 'student/invoice_list.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_invoice_num'] = $this->language->get('entry_invoice_num');
		$this->data['entry_invoice_date'] = $this->language->get('entry_invoice_date');
		$this->data['entry_total_hours'] = $this->language->get('entry_total_hours');
		$this->data['entry_total_amount'] = $this->language->get('entry_total_amount');
		$this->data['entry_send_date'] = $this->language->get('entry_send_date');
		$this->data['entry_num_of_sessions'] = $this->language->get('entry_num_of_sessions');
		$this->data['entry_paid_amount'] = $this->language->get('entry_paid_amount');
		$this->data['entry_balance_amount'] = $this->language->get('entry_balance_amount');
		$this->data['entry_invoice_notes'] = $this->language->get('entry_invoice_notes');

		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['token'] = $this->session->data['token'];

		$url = '&invoice_id=' . $this->request->get['invoice_id'];
		$url .= '&print=1';
		$this->data['button_print'] = $this->language->get('button_print');
		$this->data['print_invoice'] = HTTPS_SERVER . 'index.php?route=student/invoice/view&token=' . $this->session->data['token'] . $url;

		if(isset($this->request->get['print']))
		$this->data['is_print'] = 0;
		else
		$this->data['is_print'] = 1;
			
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['back'] = HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url;

      		$this->load->model('user/sessions');
      		$duration_array = $this->model_user_sessions->getAllDurations();
      		//		print_r($duration_array);

      		$invoice_details = $this->model_student_invoice->getInvoiceDetails($this->request->get['invoice_id']);
      		//		print_r($invoice_details['sessions']);
      		$all_sessions = $invoice_details['sessions'];

      		$result = $this->model_student_invoice->getInvoice($this->request->get['invoice_id']);

      		//print_r($result);
      		
      		$this->load->model('student/profile');
      		$student_info = $this->model_student_profile->getStudent($result['student_id']);
      		//      		print_r($student_info);
      		$show_minimum_time = FALSE;
      		$correct_total = 0;
      		foreach($all_sessions as $each_session) {
      			$min_rate = FALSE;
      			if($student_info['grades_id'] <= 7 && $each_session['session_duration'] < '1.00') {
      				$min_charge_time= '1.00';
      				$show_minimum_time = $min_rate = TRUE;
      			} elseif ($student_info['grades_id'] >= 8 && $each_session['session_duration'] < '1.00') 				{
      				$min_charge_time= '1.00';
      				$show_minimum_time = $min_rate = TRUE;
      			}
      			else
      			{
      				$min_charge_time= $each_session['session_duration'] ;
      				$show_minimum_time = $min_rate = TRUE;
      				//$show_minimum_time = TRUE;
      			}
      			
      			
      			$total = round(($each_session['session_duration'] * $each_session['base_invoice']), 2);
      			if($min_rate)
      			{
      				$total = round(($min_charge_time * $each_session['base_invoice']), 2);
      			}
      			$correct_total = $correct_total + $total;
      			$this->data['show_minimum_time'] = $show_minimum_time;

      			$this->data['invoice_details'][] = array (
				'tutor_name'    => $each_session['tutor_name'],
				'date'    => date("M d", strtotime($each_session['session_date'])),
				'duration'    => $duration_array[$each_session['session_duration']],
				'rate'    => '$ '.$each_session['base_invoice']." /hours ",
				'total'    => '$ '.$total,
      			'min_charge_time'=> $min_charge_time,
      			);
      		}

      		$this->model_student_invoice->updateCorrectInvoiceTotal($this->request->get['invoice_id'],$correct_total);
      		unset($result);
      		$result = $this->model_student_invoice->getInvoice($this->request->get['invoice_id']);
      		
      		
      		//		print_r($this->data['invoice_details']);

      		$package_details = array();

      		$student_log_data = unserialize($result['log_data']);
      		//		print_r($student_log_data);
      		//		$package_details = $student_log_data['student_packages'];
      		if(isset($student_log_data['student_packages']))
      		foreach($student_log_data['student_packages'] as $each_package) {

      			$left_hours = $each_package['left_hours'] - $each_package['deduct_hours'];

      			$package_details[] = array(
				'package_name' => $each_package['package_name'], 
				'total_hours' => $each_package['total_hours'], 
				'deduct_hours' => $each_package['deduct_hours'], 
				'left_hours' => $left_hours
      			);
      		}


      		$this->data['package_details'] = $package_details;

      		$invoice_order_id = $this->model_student_invoice->getOrderIdByInvoiceId($result['invoice_id']);
      		 
      		if(!empty($invoice_order_id['order_id'])) {

      			$this->data['paynow'] = "javascript:void(0);";

      		} else if($result['invoice_status'] != "Paid") {

      			$this->data['paynow'] = HTTPS_SERVER . 'index.php?route=student/invoice/paynow&token=' . $this->session->data['token'] ."&invoice_id=". $this->request->get['invoice_id'];

      		}


      		//		print_r($student_info);
      		$this->data['student_info'] = array(
				'parent_name'    => $student_info['parents_first_name'].' '.$student_info['parents_last_name'],
				'student_name'    => $student_info['firstname'].' '.$student_info['lastname'],
				'street_address'    => $student_info['address'],
				'city'    => $student_info['city'],
				'state'    => $student_info['state'],
				'postcode'    => $student_info['pcode']
      		);

      		if(count($result) > 0) {
      			$this->data['invoice_info'] = array(
				'invoice_num'    => $result['invoice_prefix']."-".$result['invoice_num'],
				'total_hours'    => $result['total_hours'],
				'invoice_date'           => date("F Y", strtotime($result['invoice_date'])),
				'send_date'           => date("F d, Y", strtotime($result['send_date'])),
				'total_amount'          => $result['total_amount'],
				'paid_amount'           => $result['paid_amount'],
				'balance_amount'           => $result['balance_amount'],
				'invoice_notes'           => nl2br($result['invoice_notes']),
				'num_of_sessions'     => $result['num_of_sessions']
      			);
      		} else {
      			$this->data['invoice_info'] = array(
				'invoice_num'    => '-',
				'total_hours'    => '-',
				'invoice_date'           => '-',
				'send_date'           => '-',
				'total_amount'          => '-',
				'paid_amount'           => '-',
				'balance_amount'           => '-',
				'invoice_notes'           => '-',
				'num_of_sessions'     => '-'
				);
      		}

      		$this->data['config_name'] = $this->config->get('config_name');
      		$this->data['config_address'] = nl2br($this->config->get('config_address'));


      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/invoice/view&token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . $url;
      		$this->template = 'student/invoice_form.tpl';

      		if(!isset($this->request->get['print']))
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}


	private function getFormPayment() {

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

		if (isset ($this->request->get['invoice_id'])) {
			$url .= '&invoice_id=' . $this->request->get['invoice_id'];
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
			'href' => HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url,
			'text' => $this->language->get('heading_title'
			), 'separator' => ' :: ');

			$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/invoice/paynow&token=' . $this->session->data['token'] . $url;

			$url = "&cancel=1";
			$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'] . $url;

			$package_price = 0;
			$package_grades = array ();
			if (isset ($this->request->get['invoice_id'])) {

				// Take only the one package in session
				$this->cart->clear();
				$this->cart->add($this->request->get['invoice_id']);
				//
					
				$information_info = $this->model_student_invoice->getInvoice($this->request->get['invoice_id']);
				$information_info['package_name'] = $information_info['invoice_prefix']."-".$information_info['invoice_num'];
				$information_info['package_description'] = sprintf($this->language->get('invoice_description'), date("F Y", strtotime($information_info['invoice_date'])));
				$package_price = $information_info['total_amount'];
			}

			//		print_r($information_info);

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
			$this->data['currency_symbol'] = $this->currency->getSymbolLeft();
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

			//print_r($this->request->post);

			//sample $this->session->data before confirmation
			/*
			 [language] => en
			 [cart] => Array
				(
				[202] => 1
				)

				[currency] => GBP
				[user_id] => 2
				[group_id] => 4
				[last_login] => 2012-08-29 00:43:57
				[token] => 91e411357daf621a3294a5e2abe6ee9a
				[payment_methods] => Array
				(
				[pp_standard] => Array
				(
				[id] => pp_standard
				[title] => Credit Card
				[sort_order] => 1
				)

				[cheque] => Array
				(
				[id] => cheque
				[title] => Cheque
				[sort_order] => 2
				)

				)

				[coupon] =>
				[payment_method] => Array
				(
				[id] => pp_standard
				[title] => Credit Card
				[sort_order] => 1
				)

				[order_id] => 133
				[payment_back] => http://www.learnon.ca/portal/index.php?route=student/invoice/cancel&token=91e411357daf621a3294a5e2abe6ee9a&invoice_id=202
				[payment_continue] => http://www.learnon.ca/portal/index.php?route=student/invoice/success&token=91e411357daf621a3294a5e2abe6ee9a
				[payment_cancel] => http://www.learnon.ca/portal/index.php?route=student/invoice/cancel&token=91e411357daf621a3294a5e2abe6ee9a
				*/

			$this->session->data['payment_methods'] = $method_data;

			//		print_r($method_data);

			if (isset ($this->session->data['payment_methods'])) {
				$this->data['payment_methods'] = $this->session->data['payment_methods'];
			} else {
				$this->data['payment_methods'] = array ();
			}

			//print_r($method_data);
			/* Sample method_data
			 Array
			 (
				[pp_standard] =&gt; Array
				(
				[id] =&gt; pp_standard
				[title] =&gt; Credit Card
				[sort_order] =&gt; 1
				)

				[cheque] =&gt; Array
				(
				[id] =&gt; cheque
				[title] =&gt; Cheque
				[sort_order] =&gt; 2
				)

				)
				*/

			$this->data['payment_methods'] = $method_data;

			if (isset ($this->request->post['payment_method'])) {
				$this->data['payment'] = $this->request->post['payment_method'];
			}
			elseif (isset ($this->session->data['payment_method']['id'])) {
				$this->data['payment'] = $this->session->data['payment_method']['id'];
			} else {
				$this->data['payment'] = '';
			}
//print_r();
			#------------ End Payment Methods -----------#

			#------------ Start Total Code  ---------------#

			$total_data = array ();
			$total = $package_price;
			$taxes = 0;
			$sort_order = array ();

			$results = $this->model_total_extension->getExtensions('total');
			
			//print_r($results);

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

			//print_r($total_data);
			
			#------------ End Total Code  ---------------#

			$this->data['totals'] = $total_data;
			$this->data['total'] = $total;
			$this->data['paypal'] = 'test';
			$this->template = 'student/packages_form.tpl';

			//		print_r($this->cart->getProduct());

			//echo'data is: ';print_r($this->data);

			// Start processing order
			if($this->data['confirm_order']) {
					
				//paypal details are got here
				//$this->data['confirm_order'] is set when the confirm button is visible i.e. after selecting 'Credit Card' and clicking on next
					
				$this->load->model('total/order');
					
				$data = array();
					
				//			print_r($this->user->getUserData());
					
				$data['customer_id'] = $this->user->getId();
				$data['package_id'] = '0';
				$data['invoice_pk'] = $this->request->get['invoice_id'];
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
					
				$data['invoice_id'] = $product['invoice_num'];
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
					$this->data['paypal'] = $data;
				//print_r($this->data);		
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

				$this->session->data['order_id'] = $this->model_total_order->create($data);
					
				// set the buttons link for payment modules
				$url = "&invoice_id=".$this->request->get['invoice_id'];
				$this->session->data['payment_back'] = HTTPS_SERVER . 'index.php?route=student/invoice/cancel&token=' . $this->session->data['token'] . $url;
				$this->session->data['payment_continue'] =  HTTPS_SERVER . 'index.php?route=student/invoice/success&token=' . $this->session->data['token'];
				$this->session->data['payment_cancel'] =  HTTPS_SERVER . 'index.php?route=student/invoice/cancel&token=' . $this->session->data['token'];

				$this->children = array (
				'common/header',
				'common/footer'
				);
				
				//,	'payment_student/' . $this->session->data['payment_method']['id']
					
			} else {
				$this->children = array (
				'common/header',
				'common/footer'
				);
			}

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validatePayment() {

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
}
?>