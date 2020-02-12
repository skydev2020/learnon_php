<?php
class ControllerCmsInvoices extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/invoices');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/invoices');
		$this->getList();
	}

	public function update() {
		$this->load->language('cms/invoices');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/invoices');
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
//			($this->request->post['invoice_status_pre'] != $this->request->post['invoice_status']) && ()

			if($this->request->post['invoice_status'] == "Paid") {
				
				$invoice_order_id = 0;
				$invoice_order_id = $this->model_cms_invoices->getOrderIdByInvoiceId($this->request->get['invoice_id']);
				
				if(!empty($invoice_order_id))
					$this->request->post['order_id'] = $invoice_order_id;
				
				$this->request->post['is_locked'] = '1';
				$this->request->post['pay_date'] = date("Y-m-d H:i:s");
				$this->request->post['balance_amount'] = $this->request->post['total_amount']-$this->request->post['paid_amount'];				
			} else {
				$this->request->post['pay_date'] = "";				
			}


			$this->model_cms_invoices->editInvoice($this->request->get['invoice_id'], $this->request->post);
			log_activity("Paycheque Updated", "Paycheque details updated.");
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
			/* Softronikx Technologies */
			if (isset($this->request->post['src'])) {
			
				$url .= '&src=' . $this->request->post['src'];
				
				if (isset($this->request->post['user_id']) and $this->request->post['src'] == 'stud') {
					$url .= '&user_id=' . $this->request->post['user_id'];
				}
				
			}
			
					
			/* End of code by Softronikx Technologies */		
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url);
			
		}		
		$this->getForm();		
	}

	private function getList() {
		
		/*End of code by Softronikx Technologies */
	
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}		
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice_num';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		/*Softronikx Technologies*/
		if (isset($this->request->get['user_id'])) {
			$filter_user_id = $this->request->get['user_id'];
		} 	
		/*End of code by Softronikx Technologies */
		
		if (isset($this->request->get['filter_invoice_status'])) {
			$filter_invoice_status = $this->request->get['filter_invoice_status'];
		} else {
			$filter_invoice_status = NULL;
		}
		if (isset($this->request->get['filter_invoice_date'])) {
			$filter_invoice_date = $this->request->get['filter_invoice_date'];
		} else {
			$filter_invoice_date = NULL;
		}
		if (isset($this->request->get['filter_student_name'])) {
			$filter_student_name = $this->request->get['filter_student_name'];
		} else {
			$filter_student_name = NULL;
		}	
		if (isset($this->request->get['filter_total_amount'])) {
			$filter_total_amount = $this->request->get['filter_total_amount'];
		} else {
			$filter_total_amount = NULL;
		}	
		
		if (isset($this->request->get['filter_total_hours'])) {
			$filter_total_hours = $this->request->get['filter_total_hours'];
		} else {
			$filter_total_hours = NULL;
		}
		
		if (isset($this->request->get['filter_invoice_num'])) {
			$filter_invoice_num = $this->request->get['filter_invoice_num'];
		} else {
			$filter_invoice_num = NULL;
		}	

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		
   		$this->document->breadcrumbs[] = array(
       		'href'      => 'javascript:void(0)',
       		'text'      => 'Payments',
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_invoice_status'] = $this->language->get('column_invoice_status');
		$this->data['column_invoice_date'] = $this->language->get('column_invoice_date');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_invoice_num'] = $this->language->get('column_invoice_num');
		$this->data['column_total_amount'] = $this->language->get('column_total_amount');
		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_lock'] = $this->language->get('button_lock');
		$this->data['button_unlock'] = $this->language->get('button_unlock');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_late_fee'] = $this->language->get('button_late_fee');
		
		$this->data['button_print_all_invoices'] = $this->language->get('button_print_all_invoices');
		
		
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
		
		if (isset($this->request->get['filter_invoice_status'])) {
			$url .= '&filter_invoice_status=' . $this->request->get['filter_invoice_status'];
		}
		if (isset($this->request->get['filter_invoice_date'])) {
			$url .= '&filter_invoice_date=' . $this->request->get['filter_invoice_date'];
		}
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		if (isset($this->request->get['filter_total_amount'])) {
			$url .= '&filter_total_amount=' . $this->request->get['filter_total_amount'];
		}		
		if (isset($this->request->get['filter_invoice_num'])) {
			$url .= '&filter_invoice_num=' . $this->request->get['filter_invoice_num'];
		}		
		if (isset($this->request->get['filter_total_hours'])) {
			$url .= '&filter_total_hours=' . $this->request->get['filter_total_hours'];
		}
		
		/*Softronikx Technologies*/
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
			$this->data['user_id']=$this->request->get['user_id'];
		}	
		
		if (isset($this->request->get['src'])) {
			$url .= '&src=' . $this->request->get['src'];
			$this->data['src']=$this->request->get['src'];
		}
		
		/*End of Code by Softronikx Technologies */
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$this->data['sort_invoice_status'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&sort=invoice_status' . $url;
		$this->data['sort_invoice_date'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&sort=invoice_date' . $url;
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
		$this->data['sort_invoice_num'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&sort=invoice_num' . $url;
		$this->data['sort_total_amount'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&sort=total_amount' . $url;
		$this->data['sort_total_hours'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&sort=total_hours' . $url;
		
		$url = '';

		$filters = array(			
			'filter_invoice_status',
			'filter_invoice_date',
			'filter_student_name',
			'filter_invoice_num',
			'filter_total_amount',
			'filter_total_hours',
			'page',
			'sort',
			'order'
		);
		
		foreach($filters as $filter) {
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . $this->request->get[$filter];
			}
		}
		
		/* Softronikx Technologies */
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}
		
		if (isset($this->request->get['src'])) {
			$url .= '&src=' . $this->request->get['src'];
		}
		
		/* End of Code by Softronikx Technologies */
		
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/invoices/delete&token=' . $this->session->data['token'] . $url;
		$this->data['lock_sessions'] = HTTPS_SERVER . 'index.php?route=cms/invoices/lock&token=' . $this->session->data['token'] . $url;		
		$this->data['unlock_sessions'] = HTTPS_SERVER . 'index.php?route=cms/invoices/unlock&token=' . $this->session->data['token'] . $url;		
		$this->data['apply_late_fee'] = HTTPS_SERVER . 'index.php?route=cms/invoices/late_fee&token=' . $this->session->data['token'] . $url;		
		
		$this->data['print_all_invoices'] = HTTPS_SERVER . 'index.php?route=cms/invoices/print_all_invoices&print=1&token=' . $this->session->data['token'] . $url;
		
		
		if($this->user->getUserGroupId() > 3)
			$this->data['sessions_controll'] = 1;
		else
			$this->data['sessions_controll'] = 0;

		$this->data['sessions'] = array();
		
		
		$data = array(
			'filter_user_id'				=> $filter_user_id, //softronikx
			'filter_invoice_status'              => $filter_invoice_status, 
			'filter_invoice_date'             => $filter_invoice_date, 
			'filter_student_name'        => $filter_student_name,
			'filter_invoice_num'             => $filter_invoice_num, 
			'filter_total_amount'              => $filter_total_amount, 
			'filter_total_hours'             => $filter_total_hours, 
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$this->data['filter_user_id']=$filter_user_id; //softronikx 
		$this->data['user_id']=$filter_user_id; //softronikx 
		$this->data['filter_invoice_status']=$filter_invoice_status; 
		$this->data['filter_invoice_date']=$filter_invoice_date; 
		$this->data['filter_student_name']=$filter_student_name;
		$this->data['filter_invoice_num']=$filter_invoice_num; 
		$this->data['filter_total_amount']=$filter_total_amount; 
		$this->data['filter_total_hours']=$filter_total_hours; 
		$this->data['sort']=$sort; 
		$this->data['order']=$order; 
		
		$this->data['invoices'] = array();
		$invoice_total = $this->model_cms_invoices->getTotalInvoices($data);
		$results = $this->model_cms_invoices->getInvoices($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => "View / Edit",
				'href' => HTTPS_SERVER . 'index.php?route=cms/invoices/update&token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url
			);
			
			if($result['is_locked'])
			$action[] = array(
				'text' => "Locked",
				'href' => 'javascript:void(0)' 
			);
						
			$this->data['invoices'][] = array(
				'invoice_id' => $result['invoice_id'],
				'invoice_status'      => $result['invoice_status'],
				'student_name' => $result['student_name'],
				'invoice_num' => $result['invoice_prefix']."-".$result['invoice_num'],
				'total_amount' => $result['total_amount'],
				'total_hours' => $result['total_hours'],
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
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		$this->template = 'cms/invoices_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {      		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_invoice_num'] = $this->language->get('entry_invoice_num');
		$this->data['entry_package'] = $this->language->get('entry_package');
		$this->data['entry_hour_charged'] = 'Hour Charged';
		$this->data['entry_hour_left'] = $this->language->get('entry_hour_left');
		$this->data['entry_send_date'] = $this->language->get('entry_send_date');
		$this->data['entry_invoice_status'] = $this->language->get('entry_invoice_status');
		$this->data['entry_invoice_date'] = $this->language->get('entry_invoice_date');
		$this->data['entry_student_name'] = $this->language->get('entry_student_name');
		$this->data['entry_total_amount'] = $this->language->get('entry_total_amount');
		$this->data['entry_total_hours'] = $this->language->get('entry_total_hours');
		$this->data['entry_num_of_sessions'] = $this->language->get('entry_num_of_sessions');
		$this->data['entry_paid_amount'] = $this->language->get('entry_paid_amount');
		$this->data['entry_balance_amount'] = $this->language->get('entry_balance_amount');
		$this->data['entry_invoice_notes'] = $this->language->get('entry_invoice_notes');
		$this->data['entry_invoice_mail'] = $this->language->get('entry_invoice_mail');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['token'] = $this->session->data['token'];
		
		/* Softronikx Technologies */
		$url = '&invoice_id=' . $this->request->get['invoice_id'];
		$url .= '&print=1';
		$this->data['button_print'] = $this->language->get('button_print');
		$this->data['print_invoice'] = HTTPS_SERVER . 'index.php?route=cms/invoices/print_invoice&token=' . $this->session->data['token'] . $url;
		/* End of code by Softronikx Technologies */
		
		
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
		
		/* Softronikx Technologies */
		if (isset($this->request->get['src'])) {
			$url .= '&src=' . $this->request->get['src'];
		}
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}
		/* Softronikx Technologies */
		
		if (isset($this->error['total_hours'])) {
			$this->data['error_total_hours'] = $this->error['total_hours'];
		} else {
			$this->data['error_total_hours'] = '';
		}
		
		if (isset($this->error['total_amount'])) {
			$this->data['error_total_amount'] = $this->error['total_amount'];
		} else {
			$this->data['error_total_amount'] = '';
		}
		
		if (isset($this->error['num_of_sessions'])) {
			$this->data['error_num_of_sessions'] = $this->error['num_of_sessions'];
		} else {
			$this->data['error_num_of_sessions'] = '';
		}
		
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['invoice_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/invoices/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/invoices/update&token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url;
		$invoice_info = $this->model_cms_invoices->getInvoice($this->request->get['invoice_id']);
		
		$this->data['student_name'] = $invoice_info['student_name'];
		$this->data['student_id'] = $invoice_info['student_id'];
		$this->data['student_link'] = HTTPS_SERVER . 'index.php?route=user/students/update&token=' . $this->session->data['token'] . '&user_id='.$invoice_info['student_id'];
		
		$student_log_data = unserialize($invoice_info['log_data']);
//		print_r($student_log_data);
		
		if (isset($this->request->post['invoice_date'])) {
			$this->data['invoice_date'] = $this->request->post['invoice_date'];
		} elseif (isset($invoice_info)) {
			$this->data['invoice_date'] = date('Y-m-d', strtotime($invoice_info['invoice_date']));
		} else {
			$this->data['invoice_date'] = "";
		}
		
		if (isset($this->request->post['invoice_num'])) {
			$this->data['invoice_num'] = $this->request->post['invoice_num'];
		} elseif (isset($invoice_info)) {
			$this->data['invoice_num'] = $invoice_info['invoice_prefix']."-".$invoice_info['invoice_num'];
		} else {
			$this->data['invoice_num'] = "";
		}
		
		/*Softronikx Technnologies */
		if (isset($this->request->get['src'])) {
			$this->data['src'] = $this->request->get['src'];
		}else {
			$this->data['src'] = "";
		}
		
		if (isset($this->request->get['user_id'])) {
			$this->data['user_id'] = $this->request->get['user_id'];
		}else {
			$this->data['user_id'] = "";
		}
		
		/* End of code by Softronikx Technologies */
		
		if (isset($this->request->post['num_of_sessions'])) {
			$this->data['num_of_sessions'] = $this->request->post['num_of_sessions'];
		} elseif (isset($invoice_info)) {
			$this->data['num_of_sessions'] = $invoice_info['num_of_sessions'];
		} else {
			$this->data['num_of_sessions'] = "";
		}
		
		if (isset($this->request->post['invoice_notes'])) {
			$this->data['invoice_notes'] = $this->request->post['invoice_notes'];
		} elseif (isset($invoice_info)) {
			$this->data['invoice_notes'] = $invoice_info['invoice_notes'];
		} else {
			$this->data['invoice_notes'] = "";
		}
		
		if (isset($this->request->post['invoice_mail'])) {
			$this->data['invoice_mail'] = $this->request->post['invoice_mail'];
		} elseif (isset($invoice_info)) {
			$this->data['invoice_mail'] = $invoice_info['invoice_format'];
		} else {
			$this->data['invoice_mail'] = "";
		}
		
		if (isset($this->request->post['paid_amount'])) {
			$this->data['paid_amount'] = $this->request->post['paid_amount'];
		} elseif (isset($invoice_info)) {
			$this->data['paid_amount'] = $invoice_info['paid_amount'];
		} else {
			$this->data['paid_amount'] = "";
		}
		
		if (isset($this->request->post['total_amount'])) {
			$this->data['total_amount'] = $this->request->post['total_amount'];
		} elseif (isset($invoice_info)) {
			$this->data['total_amount'] = $invoice_info['total_amount'];
		} else {
			$this->data['total_amount'] = "";
		}
		
		if (isset($this->request->post['total_hours'])) {
			$this->data['total_hours'] = $this->request->post['total_hours'];
		} elseif (isset($invoice_info)) {
			$this->data['total_hours'] = $invoice_info['total_hours'];
		} else {
			$this->data['total_hours'] = "";
		}
		
		$hour_charged = (int) $invoice_info['hour_charged'];
		if (isset($invoice_info['hour_charged']) && !empty($hour_charged)) {
			$this->data['hour_charged'] = $invoice_info['hour_charged'];
		} else {
			$this->data['hour_charged'] = 0;
		}
		
		
		if (isset($this->request->post['pay_date'])) {
			$this->data['pay_date'] = $this->request->post['pay_date'];
		} elseif (isset($invoice_info)) {
			if($invoice_info['pay_date']!="" && $invoice_info['pay_date']!="0000-00-00 00:00:00")$this->data['pay_date'] = date($this->language->get('date_format_short'), strtotime($invoice_info['pay_date']));
			else $this->data['pay_date'] = "";
		} else {
			$this->data['pay_date'] = "";
		}

		if (isset($this->request->post['invoice_status'])) {
			$this->data['invoice_status'] = $this->request->post['invoice_status'];
		} elseif (isset($invoice_info)) {
			$this->data['invoice_status'] = $invoice_info['invoice_status'];
		} else {
			$this->data['invoice_status'] = "";
		}
		
		$this->data['invoice_status_pre'] = $invoice_info['invoice_status'];
		
		$student_packages = array();
		$student_packages = $this->model_cms_invoices->getStudentPackages($invoice_info['student_id']);

		$update_student_packages = array();
		if(count($student_log_data['student_packages']) > 0)
		foreach($student_log_data['student_packages'] as $each_package) {
			$update_student_packages[$each_package['package_id']] = $each_package;	
		}
		 
		if(count($student_packages) > 0)
		foreach($student_packages as $key => $each_package) {
			
			$each_package['view_details'] = HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $each_package['order_id'];
			
			if(isset($update_student_packages[$each_package['package_id']])) {
				
				$left_package_hours = $each_package['left_hours'] - $update_student_packages[$each_package['package_id']]['deduct_hours'];
				if($left_package_hours < 0)
					$left_package_hours = 0;
				
				if($invoice_info['invoice_status'] == "Hold for Approval")
					$each_package['left_hours'] = $left_package_hours;	
			}
			
			$student_packages[$key] = $each_package;			
		}
			
		/*
		print_r($update_student_packages);		
		print_r($student_packages);
		*/
		
		$this->data['student_packages'] = $student_packages;
				
//		print_r($student_packages);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/invoices/update&token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . $url;
		$this->template = 'cms/invoices_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function lock() {
  		
  		$this->load->language('cms/invoices');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/invoices');
			
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $invoice_id) {
				$this->model_cms_invoices->lockInvoice($invoice_id);
			}
			
			log_activity("Invoices Locked", "Student invoice(s) Locked.");
			
			$this->session->data['success'] = $this->language->get('text_success_lock');

			$url = '';
			
			$filters = array(
				'filter_invoice_status', 
				'filter_invoice_date', 
				'filter_student_name',
				'filter_invoice_num', 
				'filter_total_amount', 
				'filter_total_hours',
				'page', 
				'sort',
				'order'
			);
			
			foreach($filters as $filter) {
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}
			
			/* Softronikx Technologies */
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}
				
			if (isset($this->request->get['src'])) {
				$url .= '&src=' . $this->request->get['src'];
			}
			/* End of code by Softronikx Technologies */
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getList();  	
  	}
  	
	public function unlock() {
  		$this->load->language('cms/invoices');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/invoices');
			
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $invoice_id) {
				$this->model_cms_invoices->unlockInvoice($invoice_id);
			}
			
			log_activity("Invoices Unlocked", "Student invoice(s) Unlocked.");
			
			$this->session->data['success'] = $this->language->get('text_success_unlock');

			$url = '';
			
			$filters = array(
				'filter_invoice_status', 
				'filter_invoice_date', 
				'filter_student_name',
				'filter_invoice_num', 
				'filter_total_amount', 
				'filter_total_hours',
				'page', 
				'sort',
				'order'
			);
			
			foreach($filters as $filter) {
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}
			
			/* Softronikx Technologies */
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}
				
			if (isset($this->request->get['src'])) {
				$url .= '&src=' . $this->request->get['src'];
			}
			/* End of code by Softronikx Technologies */
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getList();
  	}
	
	public function delete() {
		$this->load->language('cms/invoices');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/invoices');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $invoice_id) {
				$this->model_cms_invoices->deleteInvoice($invoice_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_delete');
			
			log_activity("Invoices Deleted", "Student invoice(s) Deleted.");

			$url = '';
			
			$filters = array(
				'filter_invoice_status', 
				'filter_invoice_date', 
				'filter_student_name',
				'filter_invoice_num', 
				'filter_total_amount', 
				'filter_total_hours',
				'page', 
				'sort',
				'order'
			);
			
			foreach($filters as $filter) {
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}

			/* Softronikx Technologies */
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}
				
			if (isset($this->request->get['src'])) {
				$url .= '&src=' . $this->request->get['src'];
			}
			/* End of code by Softronikx Technologies */
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url);
    	}

    	$this->getList();
  	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/invoices')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		/*
		if (($this->request->post['num_of_sessions']=="0") || ($this->request->post['num_of_sessions']=="")) {
			$this->error['num_of_sessions'] = $this->language->get('error_num_of_sessions');
		}
		
		if (($this->request->post['total_hours']=="0") || ($this->request->post['total_hours']=="")) {
			$this->error['total_hours'] = $this->language->get('error_total_hours');
		}

		if (($this->request->post['total_amount']=="0.00") || ($this->request->post['total_amount']=="")) {
			$this->error['total_amount'] = $this->language->get('error_total_amount');
		}
		*/
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
    	if (!$this->user->hasPermission('modify', 'cms/invoices')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
	
	/* function by Softronikx Technologies */
	public function print_invoice(){
	
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
		
		foreach($all_sessions as $each_session) {
			$this->data['invoice_details'][] = array (
				'tutor_name'    => $each_session['tutor_name'],
				'date'    => date("M d", strtotime($each_session['session_date'])),
				'duration'    => $duration_array[$each_session['session_duration']],
				'rate'    => '$ '.$each_session['base_invoice']." /hours ",
				'total'    => '$ '.round(($each_session['session_duration'] * $each_session['base_invoice']), 2),
			);	
		}
		
//		print_r($this->data['invoice_details']);
		
		$result = $this->model_student_invoice->getInvoice($this->request->get['invoice_id']);
		
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
		
		$this->load->model('student/profile');
		$student_info = $this->model_student_profile->getStudent($result['student_id']);
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
	
/* function by Softronikx Technologies */
	public function print_all_invoices(){
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
		$this->load->model('student/profile');
		
		$duration_array = $this->model_user_sessions->getAllDurations();
		$this->data['duration_array']  = $duration_array;
		
		$results = $this->model_student_invoice->getInvoices_print();
		$this->data['results'] = $results;
		$this->data['config_name'] = $this->config->get('config_name');
		$this->data['config_address'] = nl2br($this->config->get('config_address'));

		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/invoice/view&token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . $url;
		$this->template = 'student/print_invoices_form.tpl';
		if(!isset($this->request->get['print']))
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	/* function by Softronikx Technologies */
	public function late_fee() {
  		
  		$this->load->language('cms/invoices');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/invoices');
			
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $invoice_id) {
			
				$invoice_details = $this->model_cms_invoices->getUnpaidInvoice($invoice_id);
			
				//get the date added for the invoice
				
				$date_added = $invoice_details['invoice_date'];
				
				$todays_date = date('Y-m-d')." 00:00:00";
		
				$date_added_obj	= new DateTime($date_added);
				$todays_date_obj = new DateTime($todays_date);
				
				$months = $todays_date_obj->diff($date_added_obj)->m;
				
				$late_fee = ($months - 1)*20;
				
				$total_amount = $invoice_details['total_amount'] - $invoice_details['late_fee'] + $late_fee;
				
				$invoice_mail = $invoice_details['invoice_format'];
				
				//modify the invoice mail				
				
				$invoice_mail = str_replace('<table width="50%" border="0" cellpadding="0" cellspacing="0"> <tr><td align="left"><strong>Late Fee</strong></td><td align="right"><strong>$'.$late_fee.'</strong></td></tr></table><br><br>TOTAL: $'.$total_amount,'TOTAL: $'.$invoice_details['total_amount'],$invoice_mail);
				
				$invoice_mail = str_replace('TOTAL: $'.$invoice_details['total_amount'],'TOTAL: $'.$total_amount,$invoice_mail);
				
				$invoice_mail = str_replace('TOTAL: $'.$total_amount,'<table width="50%" border="0" cellpadding="0" cellspacing="0"> <tr><td align="left"><strong>Late Fee</strong></td><td align="right"><strong>$'.$late_fee.'</strong></td></tr></table><br><br>TOTAL: $'.$total_amount,$invoice_mail);
				
				//exit(0);
				
				//update late fee in student_invoice as (number of days - 30) * $20
				
				$this->model_cms_invoices->applyLateFee($invoice_id,$late_fee,$total_amount,$invoice_mail);
				
				//send email
				$this->load->model('account/student');
				$tutor_mail = $this->model_account_student->getMailFormat('7');
				
				$subject = $tutor_mail['broadcasts_subject'];
				$message = $tutor_mail['broadcasts_content'];
				
				$this->load->model('user/user');
				$student_details = $this->model_user_user->getUser($invoice_details['student_id']);
				
				// Here you can define keys for replace before sending mail to Student
				$replace_info = array(
								'STUDENT_NAME' => $student_details['firstname'].' '.$student_details['lastname'], 
								'INVOICE_NUM'  => $invoice_details['invoice_num'],
								'TOTAL_AMOUNT' => $total_amount
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
				$mail->setTo($student_details['email']);
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

					
				
			}
			
			log_activity("Late Fee Applied", "Late Fees Applied to Student Invoices.");
			
			$this->session->data['success'] = $this->language->get('text_success_late_fee');

			$url = '';
			
			$filters = array(
				'filter_invoice_status', 
				'filter_invoice_date', 
				'filter_student_name',
				'filter_invoice_num', 
				'filter_total_amount', 
				'filter_total_hours',
				'page', 
				'sort',
				'order'
			);
			
			foreach($filters as $filter) {
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}
			
			/* Softronikx Technologies */
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}
				
			if (isset($this->request->get['src'])) {
				$url .= '&src=' . $this->request->get['src'];
			}
			/* End of code by Softronikx Technologies */
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getList();  	
  	}
  	
}
?>