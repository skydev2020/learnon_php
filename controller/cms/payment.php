<?php
class ControllerCmsPayment extends Controller {
	private $error = array();

	public function test() {
		$this->load->model('cms/payment');
		echo "Test Here";
		$tutor_id = "1818";
		//$billing_date = "2013-01-24";
		$billing_date = "2014-09-05";
		$billing_date = substr($billing_date,0,strlen($billing_date)-2);

		$filter_data = array(
				'filter_billing_date' => $billing_date,
				'filter_current_status' => '6',
				'filter_locked' => 0
		);
			
		print_r($this->model_cms_payment->getTutorRaiseAmount($tutor_id, $filter_data));
		exit;
	}

	public function finished() {
		unset($this->session->data['process_date']);
		unset($this->session->data['payment_process']);
		unset($this->session->data['process_data']);
		unset($this->session->data['current_step']);

		$this->session->data['success'] = "Success: Billing process has been completed.";

		$this->redirect(HTTPS_SERVER . "index.php?route=cms/payment&token=" . $this->session->data['token']);
	}

	public function cancel() {
		unset($this->session->data['process_date']);
		unset($this->session->data['payment_process']);
		unset($this->session->data['process_data']);
		unset($this->session->data['current_step']);

		$this->session->data['error'] = "Error: Billing process has been canceled.";

		$this->redirect(HTTPS_SERVER . "index.php?route=cms/payment&token=" . $this->session->data['token']);
	}

	public function index() {
	
		//$this->test();
	
		$this->load->language('cms/payment');

		$this->document->title = $this->language->get('heading_title');
			
		$this->load->model('cms/payment');

		if(isset($this->session->data['current_step']))
		$current_step = $this->session->data['current_step'];
		else
		$current_step = "";

		if(isset($this->session->data['payment_process']))
		$payment_process = $this->session->data['payment_process'];
		else
		$payment_process = array();

		if(empty($current_step))
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->session->data['process_date'] = $this->request->post['payment_date'];
			$payment_process = array();

			if(isset($this->request->post['process'])) {

				if(count($this->request->post['process']) > 0) {

					foreach($this->request->post['process'] as $each_step) {
						$payment_process[$each_step] = 1;
					}

					$payment_process['finished'] = 1;

					$this->session->data['payment_process'] = $payment_process;
				}

				$payment_process = $this->session->data['payment_process'];
				$current_step = "";
				foreach($payment_process as $current_step => $each_step) {
					if($each_step) {
						$this->session->data['current_step'] = $current_step;
						break;
					}
				}
			}
		}

		switch($current_step) {
			case 'collect_hours':
				$this->collect_hours();
				break;
			case 'generate_invoices':
				$this->generate_invoices();
				break;
			case 'generate_paycheques':
				$this->generate_paycheques();
				break;
			case 'send_invoices':
				$this->send_invoices();
				break;
			case 'send_paycheques':
				$this->send_paycheques();
				break;
			case 'finished':
				$this->finished();
				break;
		}

		if(count($payment_process) > 1)
		$this->data['processing'] = 1;
		else
		$this->data['processing'] = 0;

		/*
		 echo "<pre>";
		 print_r($payment_process);
		 echo "</pre>";
		 */

		$this->data['billing_process'] = array_keys($payment_process);

		$this->getForm();
	}

	public function insert() {
		$this->load->language('cms/payment');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/payment');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_information->addInformation($this->request->post);

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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('cms/payment');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/payment');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_information->editInformation($this->request->get['information_id'], $this->request->post);

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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('cms/payment');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/payment');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $information_id) {
				$this->model_cms_information->deleteInformation($information_id);
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url);
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
			$sort = 'id.title';
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
       		'href'      => 'javascript:void(0)',
       		'text'      => 'Payments',
      		'separator' => ' :: '
      		);

      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		 
      		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/payment/insert&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/payment/delete&token=' . $this->session->data['token'] . $url;

      		$this->data['informations'] = array();

      		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
      		);

      		$information_total = $this->model_cms_information->getTotalInformations();

      		$results = $this->model_cms_information->getInformations($data);

      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/payment/update&token=' . $this->session->data['token'] . '&information_id=' . $result['information_id'] . $url
      			);

      			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'title'      => $result['title'],
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
				'action'     => $action
      			);
      		}

      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_title'] = $this->language->get('column_title');
      		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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

      		$this->data['sort_title'] = HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . '&sort=id.title' . $url;
      		$this->data['sort_sort_order'] = HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url;

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
      		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url . '&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();

      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;

      		$this->template = 'cms/payment_list.tpl';
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
			
		$this->data['button_start'] = $this->language->get('button_start');
		$this->data['button_run'] = $this->language->get('button_run');
		$this->data['button_set_date'] = $this->language->get('button_set_date');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_payment_date'] = $this->language->get('entry_payment_date');
		$this->data['entry_collect_hourse'] = $this->language->get('entry_collect_hourse');
		$this->data['entry_generate_invoices'] = $this->language->get('entry_generate_invoices');
		$this->data['entry_send_invoices'] = $this->language->get('entry_send_invoices');
		$this->data['entry_generate_paycheques'] = $this->language->get('entry_generate_paycheques');
		$this->data['entry_send_paycheques'] = $this->language->get('entry_send_paycheques');
		$this->data['entry_finished'] = $this->language->get('entry_finished');

		if(isset($this->session->data['current_step']))
		$current_step = $this->session->data['current_step'];
		else
		$current_step = "";
			
		#---------------- Template Process Data ----------------#

		if(isset($this->session->data['process_data']))
		$process_data = $this->session->data['process_data'];
		else
		$process_data = array();

		/*echo "<pre>";
		 print_r($process_data);
		 echo "</pre>";*/

		if(isset($process_data['collect_hours'])) {
			$this->data['collect_hours'] = 1;
			$this->data['total_approved_hours'] = $process_data['collect_hours']['total_approved'];
			$this->data['total_notapproved_hours'] = $process_data['collect_hours']['total_notapproved'];
		} else {
			$this->data['collect_hours'] = 0;
		}

		if(isset($process_data['generate_invoices'])) {
			$this->data['generate_invoices'] = 1;
			$this->data['total_invoice_generated'] = $process_data['generate_invoices']['total_generated'];
			$this->data['total_invoice_updated'] = $process_data['generate_invoices']['total_updated'];
		} else {
			$this->data['generate_invoices'] = 0;
		}

		if(isset($process_data['send_invoices'])) {
			$this->data['send_invoices'] = 1;
			$this->data['total_invoice_lock'] = $process_data['send_invoices']['total_invoice_lock'];
			$this->data['total_invoice_sent'] = $process_data['send_invoices']['total_invoice_sent'];
		} else {
			$this->data['send_invoices'] = 0;
		}

		if(isset($process_data['generate_paycheques'])) {
			$this->data['generate_paycheques'] = 1;
			$this->data['total_paycheques_generated'] = $process_data['generate_paycheques']['paycheques_generated'];
			$this->data['total_paycheques_updated'] = $process_data['generate_paycheques']['paycheques_updated'];
		} else {
			$this->data['generate_paycheques'] = 0;
		}

		if(isset($process_data['send_paycheques'])) {
			$this->data['send_paycheques'] = 1;
			$this->data['total_paycheques_lock'] = $process_data['send_paycheques']['total_paycheques_lock'];
			$this->data['total_paycheques_sent'] = $process_data['send_paycheques']['total_paycheques_sent'];
		} else {
			$this->data['send_paycheques'] = 0;
		}

		#---------------- Template Process Data ----------------#

		if(isset($this->request->post['payment_date'])) {
			$process_date = $this->request->post['payment_date'];
		} else if(isset($this->session->data['process_date']))
		$process_date = $this->session->data['process_date'];
		else
		$process_date = "";

		if($current_step == "finished") {
			$this->data['button_save'] = $this->language->get('button_finished');
		} else if(!empty($current_step)) {
			$this->data['button_save'] = $this->language->get('button_continue');
		} else if(!empty($process_date)) {
			$this->data['button_save'] = $this->language->get('button_start');
		} else {
			$this->data['button_save'] = $this->language->get('button_set_date');
		}

		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

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

		if (isset($this->error['payment_date'])) {
			$this->data['error_payment_date'] = $this->error['payment_date'];
		} else {
			$this->data['error_payment_date'] = '';
		}

		if (isset($this->error['process'])) {
			$this->data['error_process'] = $this->error['process'];
		} else {
			$this->data['error_process'] = '';
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
       		'href'      => 'javascript:void(0)',
       		'text'      => 'Payments',
      		'separator' => ' :: '
      		);

      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		 

      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'] . $url;

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/payment/cancel&token=' . $this->session->data['token'] . $url;

      		if (isset($this->request->get['information_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      			$information_info = $this->model_cms_information->getInformation($this->request->get['information_id']);
      		}


      		if (isset($this->request->post['payment_date'])) {
      			$this->data['payment_date'] = $this->request->post['payment_date'];
      		} else if (isset($this->session->data['process_date'])) {
      			$this->data['payment_date'] = $this->session->data['process_date'];
      		} else {
      			$this->data['payment_date'] = 0;
      		}

      		if (isset($this->request->post['process'])) {
      			$payment_process = array();

      			foreach($this->request->post['process'] as $each_step) {
      				$payment_process[$each_step] = 1;
      			}

      			$this->data['process'] = $payment_process;
      		} else if (isset($this->session->data['payment_process'])) {
      			$this->data['process'] = $this->session->data['payment_process'];
      		} else {
      			$this->data['process'] = array();
      		}

      		$all_dates = array();

      		foreach(array(0,1,2,3,4) as $each_key) {
      			$timestamp = strtotime('now -'.$each_key.'month');

      			$all_dates[] = array(
								'value' => date('Y-m-d', $timestamp),
								'text' => date('M-Y', $timestamp)
      			);
      		}

      		$this->data['all_dates'] = $all_dates;

      		$this->template = 'cms/process_form.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/payment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['payment_date'])) {
			$this->error['payment_date'] = $this->language->get('error_payment_date');
		}

		if (isset($this->session->data['process_date']) && !isset($this->request->post['process'])) {
			$this->error['process'] = $this->language->get('error_process');
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

	public function collect_hours() {
		//		echo "collect_hours";
		/*echo "<pre>";
		 print_r($this->session->data);
		 echo "</pre>";*/

		$billing_date = $this->session->data['process_date'];
		$billing_date = substr($billing_date,0,strlen($billing_date)-2);

		$filter_data = array (
			'filter_billing_date' => $billing_date,
			'filter_locked' => '0'
			);

			$total_approved = $this->model_cms_payment->getTutorTotalHours($filter_data);

			//		print_r($total_approved);

			$filter_data = array (
			'filter_billing_date' => $billing_date,
			'filter_locked' => '0'
			);

			//		$total_notapproved = $this->model_cms_payment->getTutorTotalHours($filter_data);
			$total_notapproved = 0;


			#---------------- Setting Template Data ----------------#

			if(isset($this->session->data['process_data']))
			$process_data = $this->session->data['process_data'];
			else
			$process_data = array();

			if(!isset($process_data['collect_hours'])) {
				$process_data['collect_hours'] = array(
				'total_approved' => $total_approved,
				'total_notapproved' => $total_notapproved,
				);
					
				$this->session->data['process_data'] = $process_data;
			}
			#---------------- Setting Template Data ----------------#

			################# Setting up the next Step #####################
			$allowed_next = 1;
			if($allowed_next) {
				$payment_process = $this->session->data['payment_process'];
				$payment_process['collect_hours'] = 0;
				$this->session->data['payment_process'] = $payment_process;
					
				foreach($payment_process as $current_step => $each_step) {
					if($each_step) {
						$this->session->data['current_step'] = $current_step;
						break;
					}
				}
			}
			################# Setting up the next Step #####################
	}

	private function format_hours($hours, $flag=0) {

		if(strpos($hours, '.') !== false)
		$time = explode(".", $hours);
		else if(strpos($hours, ':') !== false)
		$time = explode(":", $hours);
		else
		return false;
			
		$hours = $time['0'];
		$minutes = $time['1'];
			
		//		print_r($time);

		if($flag) {
			$minutes = (int) $minutes;
			if(!empty($minutes)) { // put formula here for minuts
				//				$time = ($hours * 100);
				$minutes = round(($minutes * 1.666666667));
				return $hours.".".$minutes;
			} else {
				return $hours.".00";
			}

		} else {

			if(isset($time['1']) && !empty($time['1'])) {
				$minutes = round(($minutes * 10 / 100) + ($minutes / 2));

				if(!empty($minutes))
				return $hours.":".$minutes;
				else
				return $hours;
			} else {
				return $hours;
			}
		}
	}

	private function format_hours_old($hours) {
		$time = explode(".", $hours);
		$hours = $time['0'];
		$minutes = $time['1'];

		//		print_r($time);

		if(isset($time['1']) && !empty($time['1'])) {
			$minutes = round(($minutes * 10 / 100) + ($minutes / 2));

			if(!empty($minutes))
			return $hours.":".$minutes;
			else
			return $hours;
		} else {
			return $hours;
		}
	}

	private function invoices_format($message, $invoice_data) {
		//		echo $this->format_hours("2.25");
		//		die;
		//print_r($invoice_data);

		$duration_array = $invoice_data['duration_array'];

		//		print_r($invoice_data['student_sessions']);

		$sessions_details = "";
		if(isset($invoice_data['student_sessions']))
		if(count($invoice_data['student_sessions']) > 0) {

			$sessions_details = '<table width="50%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td align="left"><strong>DATE</strong></td>
			    <td align="right"><strong>DURATION</strong></td>
			  </tr>';
			/*
			 <td align="right"><strong>HOURLY RATE</strong></td>
			 <td align="right"><strong>TOTAL</strong></td>
	 		*/
			foreach($invoice_data['student_sessions'] as $each_session) {
				$bill_info = $this->model_cms_invoices->checkStudentMiniBill(array('students_id'=>$invoice_data['student_id'],'total_hours'=>$each_session['session_duration']));
				if(count($bill_info) > 0) {
					$min_charged_hours= $bill_info['charge_time'];
				}
				else
				{
					$min_charged_hours= $each_session['session_duration'];
				}
				$sessions_details .= '
				  <tr>
				    <td align="left">'.date("d M Y", strtotime($each_session['session_date'])).'</td>
				    <td align="right">'.$duration_array[$min_charged_hours].'</td>
				  </tr>';				
				/*
				 <td align="right">'.$duration_array[$each_session['session_duration']].'</td>
			 	<td align="right">$ '.$each_session['base_invoice'].' /hours</td>
			 	<td align="right">$ '.round(($each_session['session_duration'] * $each_session['base_invoice']), 2).'</td>
			 	*/
			}

			$sessions_details .= '</table>';
		}

		$package_details = "";
		if(isset($invoice_data['update_student_packages']))
		if(count($invoice_data['update_student_packages']) > 0) {
			$package_details = '<table width="80%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td align="left"><strong>Package Name </strong></td>
			    <td align="center"><strong>Total Hours </strong></td>
			    <td align="center"><strong>Deducted Hours </strong></td>
			  </tr>';
			foreach($invoice_data['update_student_packages'] as $each_package) {
				$package_details .= '
				  <tr>
				    <td align="left">'.$each_package['package_name'].'</td>
				    <td align="center">'.$each_package['left_hours'].'</td>
				    <td align="center">'.$each_package['deduct_hours'].'</td>
				  </tr>';				
			}

			$package_details .= '</table>';
		}

		if($invoice_data['hour_charged'] != '0.00' && isset($invoice_data['hour_charged'])) {
			$invoice_data['total_hours'] = $invoice_data['hour_charged'];

			//commented by shabbir on 11/1/2012
			//$sessions_details = "";
		}

		// Here you can define keys for replace before sending mail to Student
		$replace_info = array(
						'STUDENT_NAME' => $invoice_data['student_name'], 
						'INVOICE_NUMBER' => $invoice_data['invoice_num'], //$invoice_data['invoice_prefix'].'-'.$invoice_data['invoice_num'] 
						'NUM_OF_SESSIONS' => $invoice_data['num_of_sessions'], 
						'PACKAGES_DETAILS' => $package_details, 
						'SESSIONS_DETAILS' => $sessions_details, 
						'TOTAL_HOURS' => $this->format_hours($invoice_data['total_hours']), 
						'TOTAL_AMOUNT' => '$'.$invoice_data['total_amount'], 
						'INVOICE_DATE' => date("M Y", strtotime($invoice_data['invoice_date'])), 
						'INVOICE_NOTE' => $invoice_data['invoice_notes'], 
		);

		foreach($replace_info as $rep_key => $rep_text) {
			$message = str_replace('@'.$rep_key.'@', $rep_text, $message);
		}

		//		echo html_entity_decode($message, ENT_QUOTES, 'UTF-8');
		//		die;
		return $message;
	}

	public function generate_invoices() {
		$this->load->model('cms/information');
		$this->load->model('cms/invoices');
		//		echo "generate_invoices";
		/*echo "<pre>";
		 print_r($this->session->data);
		 print_r($this->request->post);
		 echo "</pre>";*/

		$billing_date = $this->session->data['process_date'];
		$billing_date = substr($billing_date,0,strlen($billing_date)-2);

		$filter_data = array (
			'filter_billing_date' => $billing_date,
			'filter_locked' => '0'
			);

			//get information of all the students who have conducted sessions (either package or no package)
			$total_student = $this->model_cms_payment->generateStudentsInvoice($filter_data);
			$total_generated = 0;
			$total_updated = 0;
			// Generate invoice for student
			foreach($total_student as $each_student) {
				$log_data = array();
				$student_data = array();
				$student_data = $each_student;
					
				$student_sessions_updated = array();
				$student_sessions = $this->model_cms_payment->getStudentSessions($student_data['students_id'], $filter_data);
				$total_session_hours = 0;
				//print_r($student_sessions);
				foreach($student_sessions as $each_session)
				{
					$bill_info = $this->model_cms_invoices->checkStudentMiniBill(array('students_id'=>$student_data['students_id'],'total_hours'=>$each_session['session_duration']));
					if(count($bill_info) > 0) {
						$total_session_hours += $bill_info['charge_time'];
					}
					else
					{
						$total_session_hours += $each_session['session_duration'];
					}
				}
				//total hours  = total session hours
				$student_data['total_hours'] = $student_data['total_session_hours'] = $total_session_hours;
					
				$log_data['student_sessions'] = $student_sessions;
					
				// default status for geneated invoice
				$student_data['invoice_status'] = 'Hold For Approval';
				$student_data['invoice_date'] = $this->session->data['process_date'];
					
				$student_total = $student_packages = $update_student_packages = array();
					
				//Get Package details of this student. Only packages with payment status as 5 are considered.
				$student_packages = $this->model_cms_invoices->getStudentPackages($student_data['students_id']);
					
				// Minimum Billable Amount by shabbir as we are calculting it above
				//			if(count($student_packages) <= '0' && $this->config->get('config_minimum_bill')) {
				if(true){
					$bill_info = $this->model_cms_invoices->checkStudentMiniBill($student_data);

					//if(count($bill_info) > 0) {
					if(true){
						$charge_total_hours = $student_data['total_hours'];
						$student_rate = $this->model_cms_payment->getStudentRate($student_data['students_id']);
						$student_data['hour_charged'] =  $charge_total_hours;
						$student_data['total_amount'] =  round(($charge_total_hours * $student_rate), 2);
					}

					//				print_r($student_data);
					//				die;
				}
				//			$student_total_hours = ;
					
				$left_total_hours = $student_data['total_hours'];
				foreach($student_packages as $each_package) {

					if($left_total_hours > 0) {
						$left_total_hours = $left_total_hours - $each_package['left_hours'];
							
						if($left_total_hours >= 0)
						$each_package['deduct_hours'] = $each_package['left_hours'];
						else if($left_total_hours < 0) {
							$each_package['deduct_hours'] = $each_package['left_hours'] - abs($left_total_hours);
						}
						$update_student_packages[] = $each_package;
					}
				}
					
				$student_data['update_student_packages'] = $update_student_packages;
				$log_data['student_packages'] = $update_student_packages;
					
				//process here for packages . The condition will not be true if code on line 890 is evaluated further, ie a student has packages
				if($left_total_hours != $student_data['total_hours']) {
					$student_rate = $this->model_cms_payment->getStudentRate($student_data['students_id']);

					if($left_total_hours < 0)
					$left_total_hours = 0;

					$student_total['total_hours'] = $student_data['total_hours'];
					$student_total['total_amount'] = $student_data['total_amount'];
					$student_total['student_rate'] = $student_rate;
					$student_total['left_total_hours'] = $left_total_hours;

					//				$student_data['total_hours'] = $left_total_hours;
					$student_data['total_amount'] =  round(($left_total_hours * $student_rate), 2);
				}
					
				$student_data['student_total'] = $student_total;
				$log_data['student_total'] = $student_total;
					
				/*
				 if($student_data['students_id'] == '1013') {
				 //				print_r($student_total);
				 //				print_r($student_data);
				 print_r($update_student_packages);

				 echo $left_total_hours;
				 die;
				 }//*/

				//			$total_hours = ;
					
				$check_invoice = $this->model_cms_payment->checkStudentInvoice($student_data['students_id'], $billing_date);
					
				$log_data = serialize($log_data); 	
				// Load the sessions duration array
				$this->load->model('user/sessions');
					
				/* Softronikx Technologies - Code to send different invoice and package update */
				//15 for package email and 12 for invoice email format from db
				if(isset($student_data['update_student_packages']))
				if(count($student_data['update_student_packages']) > 0) {
					$invoice_mailmsg = $this->model_cms_information->getInformation('15');
				}
				else
				{
					$invoice_mailmsg = $this->model_cms_information->getInformation('12');
				}
				//end of code by Softronikx Technologies
					
					
				// Set the mail format which needs to send
				//$invoice_mailmsg = $this->model_cms_information->getInformation('12');
				$invoice_mailmsg = $invoice_mailmsg['description'];

				if(count($check_invoice) > 0) {
					if(! $check_invoice['is_locked'])
					{

				 	// Update Invoice Mail Format
				 	$check_invoice['update_student_packages'] = $student_data['update_student_packages'];
				 	$check_invoice['student_total'] = $student_data['student_total'];
				 	$check_invoice['student_name'] = $student_data['name'];
				 		
				 	if(isset($student_data['hour_charged']))
						$check_invoice['hour_charged'] = $student_data['hour_charged'];

						$check_invoice['total_hours'] = $student_data['total_hours'];
						$check_invoice['student_sessions'] = $student_sessions;
						$check_invoice['duration_array'] = $this->model_user_sessions->getAllDurations();

						$student_data['invoice_format'] = $this->invoices_format($invoice_mailmsg, $check_invoice);
						$student_data['log_data'] = $log_data;
							
						//					if($check_invoice['invoice_id'] == "37") {
						//						print_r($student_data);
						//						die;
						//					}
							
						//print_r($check_invoice);
						$this->model_cms_payment->editStudentInvoice($check_invoice['invoice_id'], $student_data);
						$total_updated += 1;
				 }
				} else {
					// setting up the invoice number and prefix
					$next_invoice = $this->model_cms_payment->generateStudentInvoiceNumber();
					$student_data['invoice_num'] = $next_invoice;
					$student_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');

					// Add Invoice Mail Format
					$student_data['student_name'] = $student_data['name'];
					$student_data['invoice_notes'] = "";

					$student_data['student_sessions'] = $student_sessions;
					$student_data['duration_array'] = $this->model_user_sessions->getAllDurations();

					$student_data['invoice_format'] = $this->invoices_format($invoice_mailmsg, $student_data);
					$student_data['log_data'] = $log_data;
					//				print_r($student_data);
					$this->model_cms_payment->addStudentInvoice($student_data);
					$total_generated += 1;
				}
			}


			#---------------- Setting Template Data ----------------#

			$process_data = $this->session->data['process_data'];

			if(!isset($process_data['generate_invoices'])) {
				$process_data['generate_invoices'] = array(
				'total_generated' => $total_generated,
				'total_updated' => $total_updated,
				);
					
				$this->session->data['process_data'] = $process_data;
			}
			#---------------- Setting Template Data ----------------#


			################# Setting up the next Step #####################
			$allowed_next = 1;
			if($allowed_next) {
				$payment_process = $this->session->data['payment_process'];
					
				$payment_process['generate_invoices'] = 0;
				$this->session->data['payment_process'] = $payment_process;
					
				foreach($payment_process as $current_step => $each_step) {
					if($each_step) {
						$this->session->data['current_step'] = $current_step;
						break;
					}
				}
			}
			################# Setting up the next Step #####################
	}

	public function send_invoices() {
		set_time_limit(300);
		//		echo "send_invoices";
		$this->load->model('cms/information');
		$this->load->model('cms/notifications');
			
		$billing_date = $this->session->data['process_date'];
		$billing_date = substr($billing_date,0,strlen($billing_date)-2);

		$filter_data = array(
			'filter_billing_date' => $billing_date,
			'filter_locked' => '0'
			);

			$total_invoices = $this->model_cms_payment->getInvoices($filter_data);

			$total_invoice_lock = 0;
			foreach($total_invoices as $each_invoice) {
					
				$result = $this->model_cms_payment->lock_invoices($each_invoice);
					
				if($result)
				$total_invoice_lock = $total_invoice_lock + 1;
			}

			//		$total_invoice_lock = 0;

			$filter_data = array(
			'filter_billing_date' => $billing_date,
			'filter_locked' => '1'
			);

			$total_locked_invoices = $this->model_cms_payment->getInvoices($filter_data);


			/* Commented by Softronikx
			 // Set the mail format which needs to send
			 $invoice_mailsubject = $this->model_cms_information->getInformation('12');
			 $invoice_mailsubject = $invoice_mailsubject['title'];
			 End of comment by Softronikx */

			$total_invoice_sent = 0;

			foreach($total_locked_invoices as $each_locked_invoices) {
					
				//			print_r($each_locked_invoices);
					
				if(!empty($each_locked_invoices['email'])) {

					$invoice_date = date('d-M-Y',strtotime($each_locked_invoices['invoice_date']));
					$each_locked_invoices['invoice_date'] = $invoice_date;

					/*Softronikx Technologies */
					if(substr_count($each_locked_invoices['invoice_format'],"Account Update")>0)
					{
						$invoice_mailsubject = $this->model_cms_information->getInformation('15');
					}
					else
					{
						$invoice_mailsubject = $this->model_cms_information->getInformation('12');
					}
					/* End of code by Softronikx Technologies */

					$invoice_mailsubject = $invoice_mailsubject['title'];


					// take subject from information table
					$subject = $this->invoices_format($invoice_mailsubject, $each_locked_invoices);

					// take mail body from invoice table
					$message = $each_locked_invoices['invoice_format'];

					/*
					 echo $subject;
					 echo html_entity_decode($message, ENT_QUOTES, 'UTF-8');
					 echo "<hr />";
					 */

					$mail = new Mail($this);
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');
					$mail->setTo($each_locked_invoices['email']);
					$mail->setFrom($this->config->get('config_email3'));
					$mail->setSender($this->config->get('config_name'));
					$mail->setSubject($subject);
					$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));

					if($each_locked_invoices['total_amount'] <= 0) {
						$mail->send(); // Sending mail if due amount is 0 //commented on 28th sep 2014
						$filter_data = array(
			  			'invoice_status' => 'Paid'
			  			);
					} else {
						$mail->send(); //commented on 28th sep 2014
						$filter_data = array(
			  			'invoice_status' => 'Reminder Sent'
			  			);
					}

					$this->model_cms_payment->updateInvoiceStatus($each_locked_invoices['invoice_id'], $filter_data);

					$notification = array(
					'notification_from' => $this->session->data['user_id'],
					'notification_to' => $each_locked_invoices['student_id'],
					'subject' => $subject,
					'message' => 'Your Invoice for the date('.$invoice_date.') has been generated.'
					);

					$this->model_cms_notifications->addInformation($notification);

					$total_invoice_sent += 1;
				}
			}

			#---------------- Setting Template Data ----------------#

			if(isset($this->session->data['process_data']))
			$process_data = $this->session->data['process_data'];
			else
			$process_data = array();

			if(!isset($process_data['send_invoices'])) {
				$process_data['send_invoices'] = array(
				'total_invoice_lock' => $total_invoice_lock,
				'total_invoice_sent' => $total_invoice_sent,
				);
					
				$this->session->data['process_data'] = $process_data;
			}
			#---------------- Setting Template Data ----------------#

			################# Setting up the next Step #####################
			$allowed_next = 1;
			if($allowed_next) {
				$payment_process = $this->session->data['payment_process'];
					
				$payment_process['send_invoices'] = 0;
				$this->session->data['payment_process'] = $payment_process;
					
				foreach($payment_process as $current_step => $each_step) {
					if($each_step) {
						$this->session->data['current_step'] = $current_step;
						break;
					}
				}
			}
			################# Setting up the next Step #####################
	}

	public function generate_paycheques() {
		//		echo "generate_paycheques";
		/*echo "<pre>";
		 print_r($this->session->data);
		 print_r($this->request->post);
		 echo "</pre>";*/

		$billing_date = $this->session->data['process_date'];
		$billing_date = substr($billing_date,0,strlen($billing_date)-2);

		$filter_data = array (
			'filter_billing_date' => $billing_date,
			'filter_locked' => '0'
			);

			$total_tutors = $this->model_cms_payment->generateTutorsPaycheque($filter_data);

			$check_all_tutors = array();
			foreach($total_tutors as $each_tutor) {
				$check_all_tutors[] = $each_tutor['tutors_id'];
			}

			$filter_data_essay = array(
			'filter_date_completed' => $billing_date,
			'filter_current_status' => '4',
			'filter_locked' => 0
			);

			$all_essayes_tutors = $this->model_cms_payment->getTutorEssaysAmount($filter_data_essay);

			$check_all_essays = array();
			foreach($all_essayes_tutors as $each_tutor) {
				$check_all_essays[] = $each_tutor['tutor_id'];
			}

			//		print_r($total_tutors);
			//		print_r($check_all_tutors);
			//		print_r($check_all_essays);
			$check_all_essays = array_diff($check_all_essays, $check_all_tutors);
			//		print_r($check_all_essays);

			foreach($check_all_essays as $each_tutor) {
				$total_tutors[] = array(
				'tutors_id' => $each_tutor,
	            'name' => '',
	            'num_of_sessions' => '0', 
	            'total_hours' => '0',
	            'total_amount' => '0'			
	            );
			}
			//		print_r($total_tutors);
			//		die;



			$total_generated = 0;
			$total_updated = 0;

			// Generate paycheques for tutors
			foreach($total_tutors as $each_tutor) {
				$tutor_data = array();
				$tutor_data = $each_tutor;

				$log_data = array();
				$log_data['all_sessions'] = $this->model_cms_payment->getTutorSessions($tutor_data['tutors_id'], $filter_data);
					
				$tutorRaiseAmount = $this->model_cms_payment->getTutorRaiseAmount($tutor_data['tutors_id'], $filter_data);
					
				/*if($tutor_data['tutors_id'] == "1014") {
				 print_r($tutorRaiseAmount);
				 die;
				 }*/
					
				$raise_amount = $tutorRaiseAmount['tutor_raise_amount'];
				$log_data['all_students_data'] = $tutorRaiseAmount['all_students_data'];
					
				if($raise_amount > 0) {
					$tutor_data['raise_amount'] = $raise_amount;
					$tutor_data['total_amount'] = ($tutor_data['total_amount'] + $raise_amount);

				} else {
					$tutor_data['raise_amount'] = 0;
				}
					
					
				// default status for geneated invoice
					
				$filter_data_essay = array(
				'filter_tutor_id' => $tutor_data['tutors_id'],
				'filter_date_completed' => $billing_date,
				'filter_current_status' => '4',
				'filter_locked' => 0
				);
					
				$log_data['tutor_essays_details'] = array();
				$essay_info = array();
				$essay_info = $this->model_cms_payment->getTutorEssaysAmount($filter_data_essay);
					
				if(count($essay_info) > 0) {

					// select the first one
					$essay_info = $essay_info['0'];

					$log_data['tutor_essays_details'] = $this->model_cms_payment->getTutorEssaysDetails($filter_data_essay);

					$tutor_data['num_of_essay'] = $essay_info['num_of_essay'];
					$tutor_data['essay_amount'] = $essay_info['total_amount'];
					$tutor_data['total_amount'] = ($tutor_data['total_amount'] + $essay_info['total_amount']);

				} else {
					$tutor_data['num_of_essay'] = 0;
					$tutor_data['essay_amount'] = 0;
				}
					
				$tutor_data['total_amount'] = round($tutor_data['total_amount'], 2);
				$tutor_data['paycheque_status'] = 'Hold For Approval';
				$tutor_data['paycheque_date'] = $this->session->data['process_date'];
					
				$check_paycheque = $this->model_cms_payment->checkTutorPaycheque($tutor_data['tutors_id'], $billing_date);
					
				//			print_r($log_data);
				//			echo "<hr />";
				//			die;
				//			print_r($check_paycheque);
					
				// Make a log Data for tutor paycheque
				$tutor_data['log_data'] = serialize($log_data);
					
				if(count($check_paycheque) > 0) {
					if(! $check_paycheque['is_locked']) {
						$this->model_cms_payment->editTutorPaycheque($check_paycheque['paycheque_id'], $tutor_data);
						$total_updated += 1;
					}
				} else {

					$this->model_cms_payment->addTutorPaycheque($tutor_data);
					$total_generated += 1;
				}
			}

			#---------------- Setting Template Data ----------------#

			$process_data = $this->session->data['process_data'];

			if(!isset($process_data['generate_paycheques'])) {
				$process_data['generate_paycheques'] = array (
				'paycheques_generated' => $total_generated,
				'paycheques_updated' => $total_updated,
				);
					
				$this->session->data['process_data'] = $process_data;
			}
			#---------------- Setting Template Data ----------------#

			################# Setting up the next Step #####################
			$allowed_next = 1;
			if($allowed_next) {
				$payment_process = $this->session->data['payment_process'];
					
				$payment_process['generate_paycheques'] = 0;
				$this->session->data['payment_process'] = $payment_process;
					
				foreach($payment_process as $current_step => $each_step) {
					if($each_step) {
						$this->session->data['current_step'] = $current_step;
						break;
					}
				}
			}
			################# Setting up the next Step #####################
	}

	public function send_paycheques() {
		//		echo "send_paycheques";

		$billing_date = $this->session->data['process_date'];
		$billing_date = substr($billing_date,0,strlen($billing_date)-2);

		$filter_data = array(
			'filter_billing_date' => $billing_date,
			'filter_locked' => '0'
			);

			$total_paycheques = $this->model_cms_payment->getPaycheques($filter_data);

			$total_paycheques_lock = 0;
			foreach($total_paycheques as $each_paycheque) {
				$result = $this->model_cms_payment->lock_paycheques($each_paycheque);
					
				if($result)
				$total_paycheques_lock = $total_paycheques_lock + 1;
			}

			//		$total_paycheques_lock = 0;

			$total_paycheques_sent = 0;

			#---------------- Setting Template Data ----------------#

			$process_data = $this->session->data['process_data'];

			if(!isset($process_data['send_paycheques'])) {
				$process_data['send_paycheques'] = array(
				'total_paycheques_lock' => $total_paycheques_lock,
				'total_paycheques_sent' => $total_paycheques_sent,
				);
					
				$this->session->data['process_data'] = $process_data;
			}
			#---------------- Setting Template Data ----------------#

			################# Setting up the next Step #####################
			$allowed_next = 1;
			if($allowed_next) {
				$payment_process = $this->session->data['payment_process'];
					
				$payment_process['send_paycheques'] = 0;
				$this->session->data['payment_process'] = $payment_process;
					
				foreach($payment_process as $current_step => $each_step) {
					if($each_step) {
						$this->session->data['current_step'] = $current_step;
						break;
					}
				}
			}
			################# Setting up the next Step #####################

	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'cms/payment')) {
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

			$store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);

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