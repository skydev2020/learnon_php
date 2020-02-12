<?php
class ControllerCmsPaycheque extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('cms/paycheque');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/paycheque');
		$this->getList();
	}

	public function export() {
		$this->load->model('cms/paycheque');
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'total_hours';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['filter_paycheque_status'])) {
			$filter_paycheque_status = $this->request->get['filter_paycheque_status'];
		} else {
			$filter_paycheque_status = NULL;
		}
		if (isset($this->request->get['filter_paycheque_date'])) {
			$filter_paycheque_date = $this->request->get['filter_paycheque_date'];
		} else {
			$filter_paycheque_date = NULL; //date("Y-m-d", strtotime("today -1 month"))
		}
		if (isset($this->request->get['filter_tutor_name'])) {
			$filter_tutor_name = $this->request->get['filter_tutor_name'];
		} else {
			$filter_tutor_name = NULL;
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

		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_paycheque_status'] = $filter_paycheque_status;
		$this->data['filter_total_amount'] = $filter_total_amount;
		$this->data['filter_total_hours'] = $filter_total_hours;
		$this->data['filter_paycheque_date'] = $filter_paycheque_date;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$select = "p.tutor_id, concat(ut.firstname,' ',ut.lastname) as tutor_name ";
		if(isset($this->request->get['tutor_name'])){
			$select .= ", concat(ut.firstname,' ',ut.lastname) as tutor_name";
		}
		if(isset($this->request->get['total_amount'])){
			$select .= ", p.total_amount";
		}
		if(isset($this->request->get['total_hours'])){
			$select .= ", p.total_hours";
		}
		if(isset($this->request->get['total_hours'])){
			$select .= ", p.total_hours";
		}
		if(isset($this->request->get['total_hours'])){
			$select .= ", p.total_hours";
		}
		if(isset($this->request->get['paid_amount'])){
			$select .= ", p.paid_amount";
		}
		if(isset($this->request->get['pay_date'])){
			$select .= ", p.pay_date";
		}
		if(isset($this->request->get['send_date'])){
			$select .= ", p.send_date";
		}
		if(isset($this->request->get['date_added'])){
			$select .= ", p.paycheque_date as date_added";
		}
		if(isset($this->request->get['status'])){
			$select .= ", p.paycheque_status as status";
		}

		$results = $this->model_cms_paycheque->getPayCheques($this->data, $select);
		$arrresult = array();

		$this->load->model('user/tutors');
		if(isset($this->request->get['address'])){
			foreach($results as $result){
				$user_info = $this->model_user_tutors->getTutor($result['tutor_id']);
				$result['address'] = $user_info['address'] . ", " .
				$user_info['city'] . ", " . $user_info['state'] . ", " .
				$user_info['pcode'] . ", " .
				$user_info['country'];
				/*Softronikx Technologies */
				$result['address'] = $user_info['address'];
				$result['city'] = $user_info['city'];
				$result['state'] = $user_info['state'];
				$result['pcode'] = $user_info['pcode'];
				$result['country'] = $user_info['country'];
				/* End of Code */

				unset($result['tutor_id']);

				if(isset($this->request->get['pay_date'])&& isset($result['pay_date'])){
					$result['pay_date'] = date("d/m/Y", strtotime($result['pay_date']));
				}
				if(isset($this->request->get['send_date']) && isset($result['send_date'])){
					$result['send_date'] = date("d/m/Y", strtotime($result['send_date']));
				}
				if(isset($this->request->get['date_added'])){
					$result['date_added'] = date("d/m/Y", strtotime($result['date_added']));
				}
				$arrresult[] = $result;

			}
		} else{
			foreach($results as $result){
				$user_info = $this->model_user_tutors->getTutor($result['tutor_id']);
				$result['address'] = $user_info['address'] . ", " .
				$user_info['city'] . ", " . $user_info['state'] . ", " .
				$user_info['pcode'] . ", " .
				$user_info['country'];
				/*Softronikx Technologies */
				$result['address'] = $user_info['address'];
				$result['city'] = $user_info['city'];
				$result['state'] = $user_info['state'];
				$result['pcode'] = $user_info['pcode'];
				$result['country'] = $user_info['country'];
				/* End of Code */

				unset($result['tutor_id']);
				if(isset($this->request->get['pay_date'])&& isset($result['pay_date'])){
					$result['pay_date'] = date("d/m/Y", strtotime($result['pay_date']));
				}
				if(isset($this->request->get['send_date']) && isset($result['send_date'])){
					$result['send_date'] = date("d/m/Y", strtotime($result['send_date']));
				}
				if(isset($this->request->get['date_added'])){
					$result['date_added'] = date("d/m/Y", strtotime($result['date_added']));
				}
				$arrresult[] = $result;
			}
		}
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("paycheque.xls");
		exit;
	}

	public function update() {
		$this->load->language('cms/paycheque');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/paycheque');
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if(($this->request->post['paycheque_status_pre']!=$this->request->post['paycheque_status'])&&($this->request->post['paycheque_status']=="Paid"))
			$this->request->post['pay_date'] = date("Y-m-d H:i:s");
			else
			$this->request->post['pay_date'] = "";
			$this->request->post['balance_amount'] = $this->request->post['total_amount']-$this->request->post['paid_amount'];
			$this->model_cms_paycheque->editPayCheque($this->request->get['paycheque_id'], $this->request->post);
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
				
			//print_r($this->request->post);
				
			if($this->request->post['paycheque_status_pre']!=$this->request->post['paycheque_status'])
			{
				// Set the mail format which needs to send
				$tutor_payment_mail = $this->model_cms_paycheque->getMailFormat('9');


				$subject = $tutor_payment_mail['broadcasts_subject'];
				$message = $tutor_payment_mail['broadcasts_content'];

				$paycheque_info = $this->model_cms_paycheque->getPayCheque($this->request->get['paycheque_id']);
      			$this->data['tutor_name'] = $paycheque_info['tutor_name'];
				
				// Here you can define keys for replace before sending mail to Student
				$replace_info = array(
							'TUTOR_NAME' => $this->data['tutor_name'], 
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
				$mail->setTo($this->request->post['user_email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();				
			}
				
				
			/* End of code by Softronikx Technologies */
				
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url);
		}
		$this->getForm();
	}
	/*
	 public function delete() {
		$this->load->language('cms/paycheque');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/paycheque');
			
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
		foreach ($this->request->post['selected'] as $paycheque_id) {
		//$this->model_cms_paycheque->deletePaycheque($paycheque_id);
		}
		log_activity("Paycheque(s) Deleted");
		$this->session->data['success'] = $this->language->get('text_success');
			
		$url = '';

		if (isset($this->request->get['filter_paycheque_status'])) {
		$url .= '&filter_paycheque_status=' . $this->request->get['filter_paycheque_status'];
		}
		if (isset($this->request->get['filter_paycheque_date'])) {
		$url .= '&filter_paycheque_date=' . $this->request->get['filter_paycheque_date'];
		}
		if (isset($this->request->get['filter_tutor_name'])) {
		$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		if (isset($this->request->get['filter_total_amount'])) {
		$url .= '&filter_total_amount=' . $this->request->get['filter_total_amount'];
		}
		if (isset($this->request->get['filter_total_hours'])) {
		$url .= '&filter_total_hours=' . $this->request->get['filter_total_hours'];
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

		$this->redirect(HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
		}*/


	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'total_hours';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['filter_paycheque_status'])) {
			$filter_paycheque_status = $this->request->get['filter_paycheque_status'];
		} else {
			$filter_paycheque_status = NULL;
		}
		if (isset($this->request->get['filter_paycheque_date'])) {
			$filter_paycheque_date = $this->request->get['filter_paycheque_date'];
		} else {
			$filter_paycheque_date = NULL; //date("Y-m-d", strtotime("today -1 month"))
		}
		if (isset($this->request->get['filter_tutor_name'])) {
			$filter_tutor_name = $this->request->get['filter_tutor_name'];
		} else {
			$filter_tutor_name = NULL;
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

		/*Softronikx Technologies*/
		if (isset($this->request->get['user_id'])) {
			$filter_user_id = $this->request->get['user_id'];
		}
		/*End of code by Softronikx Technologies */

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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['heading_title'] = $this->language->get('heading_title');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_paycheque_status'] = $this->language->get('column_paycheque_status');
      		$this->data['column_paycheque_date'] = $this->language->get('column_paycheque_date');
      		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
      		$this->data['column_total_amount'] = $this->language->get('column_total_amount');
      		$this->data['column_total_sessions'] = $this->language->get('column_total_sessions');
      		$this->data['column_raise_amount'] = $this->language->get('column_raise_amount');
      		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
      		$this->data['column_action'] = $this->language->get('column_action');

      		$this->data['button_filter'] = $this->language->get('button_filter');
      		$this->data['button_lock'] = $this->language->get('button_lock');
      		$this->data['button_unlock'] = $this->language->get('button_unlock');
      		$this->data['button_delete'] = $this->language->get('button_delete');
      		$this->data['button_mark_paid'] = $this->language->get('button_mark_paid');


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

      		if (isset($this->request->get['filter_paycheque_status'])) {
      			$url .= '&filter_paycheque_status=' . $this->request->get['filter_paycheque_status'];
      		}
      		if (isset($this->request->get['filter_paycheque_date'])) {
      			$url .= '&filter_paycheque_date=' . $this->request->get['filter_paycheque_date'];
      		}
      		if (isset($this->request->get['filter_tutor_name'])) {
      			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
      		}
      		if (isset($this->request->get['filter_total_amount'])) {
      			$url .= '&filter_total_amount=' . $this->request->get['filter_total_amount'];
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

      		$this->data['sort_paycheque_status'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . '&sort=paycheque_status' . $url;
      		$this->data['sort_paycheque_date'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . '&sort=paycheque_date' . $url;
      		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
      		$this->data['sort_total_amount'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . '&sort=total_amount' . $url;
      		$this->data['sort_total_hours'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . '&sort=total_hours' . $url;

      		$url = '';
      			
      		$filters = array(
			'filter_paycheque_status', 
			'filter_paycheque_date', 
			'filter_tutor_name',
			'filter_paycheque_num', 
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

			/* Softronikx Technologies */
			$this->data['mark_paid'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/paid&token=' . $this->session->data['token'] . $url;
			/* End of Code by Softronikx Technologies */
			$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/delete&token=' . $this->session->data['token'] . $url;
			$this->data['lock_sessions'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/lock&token=' . $this->session->data['token'] . $url;
			$this->data['unlock_sessions'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/unlock&token=' . $this->session->data['token'] . $url;


			if($this->user->getUserGroupId() > 3)
			$this->data['sessions_controll'] = 1;
			else
			$this->data['sessions_controll'] = 0;

			$this->data['sessions'] = array();


			$data = array(
			'filter_user_id'				=> $filter_user_id, //softronikx
			'filter_paycheque_status'              => $filter_paycheque_status, 
			'filter_paycheque_date'             => $filter_paycheque_date, 
			'filter_tutor_name'        => $filter_tutor_name,
			'filter_total_amount'              => $filter_total_amount, 
			'filter_total_hours'             => $filter_total_hours, 
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
			);

			$this->data['filter_user_id']=$filter_user_id; //softronikx
			$this->data['user_id']=$filter_user_id; //softronikx
			$this->data['filter_paycheque_status']=$filter_paycheque_status;
			$this->data['filter_paycheque_date']=$filter_paycheque_date;
			$this->data['filter_tutor_name']=$filter_tutor_name;
			$this->data['filter_total_amount']=$filter_total_amount;
			$this->data['filter_total_hours']=$filter_total_hours;
			$this->data['sort']=$sort;
			$this->data['order']=$order;

			$this->data['paycheques'] = array();
			$paycheque_total = $this->model_cms_paycheque->getTotalPayCheques($data);
			$results = $this->model_cms_paycheque->getPayCheques($data);

			foreach ($results as $result) {
				$action = array();
				$action[] = array(
				'text' => "View / Edit",
				'href' => HTTPS_SERVER . 'index.php?route=cms/paycheque/update&token=' . $this->session->data['token'] . '&paycheque_id=' . $result['paycheque_id'] . $url
				);
					
				if($result['is_locked'])
				$action[] = array(
				'text' => "Locked",
				'href' => 'javascript:void(0)' 
				);

				$this->data['paycheques'][] = array(
				'paycheque_id' => $result['paycheque_id'],
				'paycheque_status'      => $result['paycheque_status'],
				'tutor_name' => $result['tutor_name'],
				'total_amount' => $result['total_amount'],
				'total_sessions' => $result['num_of_sessions'],
				'raise_amount' => $result['raise_amount'],
				'total_hours' => $result['total_hours'],
				'paycheque_date' => date($this->language->get('date_format_short'), strtotime($result['paycheque_date'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['paycheque_id'], $this->request->post['selected']),
				'action'     => $action
				);
			}

			$pagination = new Pagination();
			$pagination->total = $paycheque_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url . '&page={page}';
				
			$this->data['pagination'] = $pagination->render();
			$this->template = 'cms/paycheques_list.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_paycheque_num'] = $this->language->get('entry_paycheque_num');
		$this->data['entry_send_date'] = $this->language->get('entry_send_date');
		$this->data['entry_paycheque_status'] = $this->language->get('entry_paycheque_status');
		$this->data['entry_paycheque_date'] = $this->language->get('entry_paycheque_date');
		$this->data['entry_tutor_name'] = $this->language->get('entry_tutor_name');
		$this->data['entry_num_of_essays'] = $this->language->get('entry_num_of_essays');
		$this->data['entry_essays_amount'] = $this->language->get('entry_essays_amount');
		$this->data['entry_raise_amount'] = $this->language->get('entry_raise_amount');
		$this->data['entry_total_amount'] = $this->language->get('entry_total_amount');
		$this->data['entry_total_hours'] = $this->language->get('entry_total_hours');
		$this->data['entry_num_of_sessions'] = $this->language->get('entry_num_of_sessions');
		$this->data['entry_paid_amount'] = $this->language->get('entry_paid_amount');
		$this->data['entry_balance_amount'] = $this->language->get('entry_balance_amount');
		$this->data['entry_paycheque_notes'] = $this->language->get('entry_paycheque_notes');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

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

		if (isset($this->error['num_of_essays'])) {
			$this->data['error_num_of_essays'] = $this->error['num_of_essays'];
		} else {
			$this->data['error_num_of_essays'] = '';
		}

		if (isset($this->error['essays_amount'])) {
			$this->data['error_essays_amount'] = $this->error['essays_amount'];
		} else {
			$this->data['error_essays_amount'] = '';
		}

		if (isset($this->error['raise_amount'])) {
			$this->data['error_raise_amount'] = $this->error['raise_amount'];
		} else {
			$this->data['error_raise_amount'] = '';
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

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		if (!isset($this->request->get['paycheque_id'])) {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/insert&token=' . $this->session->data['token'] . $url;
      		} else {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/update&token=' . $this->session->data['token'] . '&paycheque_id=' . $this->request->get['paycheque_id'] . $url;
      		}

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url;
      		$paycheque_info = $this->model_cms_paycheque->getPayCheque($this->request->get['paycheque_id']);
      		$this->data['tutor_name'] = $paycheque_info['tutor_name'];

      		/*$tutor_log_data = unserialize($paycheque_info['log_data']);
      		 print_r($tutor_log_data);*/

      		$this->load->model('user/tutors');
      		$user_info = $this->model_user_tutors->getTutor($paycheque_info['tutor_id']);

      		$this->data['text_tutor_address'] = $this->language->get('text_tutor_address');
      		$this->data['tutor_address'] = $user_info['address'] . "<br />" .
      		$user_info['city'] . ", " . $user_info['state'] . "<br />" .
      		$user_info['pcode'] . "<br />" .
      		$user_info['country'];
      		$this->data['user_id'] = $paycheque_info['tutor_id'];
      		$this->data['user_email'] = $user_info['email'];


      		//		echo $this->data['tutor_address'];
      		/*print_r($user_info);
      		 print_r($paycheque_info);*/

      		if (isset($this->request->post['paycheque_date'])) {
      			$this->data['paycheque_date'] = $this->request->post['paycheque_date'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['paycheque_date'] = date('Y-m-d', strtotime($paycheque_info['paycheque_date']));
      		} else {
      			$this->data['paycheque_date'] = "";
      		}

      		if (isset($this->request->post['paycheque_num'])) {
      			$this->data['paycheque_num'] = $this->request->post['paycheque_num'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['paycheque_num'] = $paycheque_info['paycheque_num'];
      		} else {
      			$this->data['paycheque_num'] = "";
      		}

      		if (isset($this->request->post['num_of_sessions'])) {
      			$this->data['num_of_sessions'] = $this->request->post['num_of_sessions'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['num_of_sessions'] = $paycheque_info['num_of_sessions'];
      		} else {
      			$this->data['num_of_sessions'] = "";
      		}

      		if (isset($this->request->post['paycheque_notes'])) {
      			$this->data['paycheque_notes'] = $this->request->post['paycheque_notes'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['paycheque_notes'] = $paycheque_info['paycheque_notes'];
      		} else {
      			$this->data['paycheque_notes'] = "";
      		}

      		if (isset($this->request->post['paid_amount'])) {
      			$this->data['paid_amount'] = $this->request->post['paid_amount'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['paid_amount'] = $paycheque_info['paid_amount'];
      		} else {
      			$this->data['paid_amount'] = "";
      		}

      		if (isset($this->request->post['num_of_essays'])) {
      			$this->data['num_of_essays'] = $this->request->post['num_of_essays'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['num_of_essays'] = $paycheque_info['num_of_essay'];
      		} else {
      			$this->data['num_of_essays'] = "";
      		}

      		if (isset($this->request->post['essays_amount'])) {
      			$this->data['essays_amount'] = $this->request->post['essays_amount'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['essays_amount'] = $paycheque_info['essay_amount'];
      		} else {
      			$this->data['essays_amount'] = "";
      		}

      		if (isset($this->request->post['raise_amount'])) {
      			$this->data['raise_amount'] = $this->request->post['raise_amount'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['raise_amount'] = $paycheque_info['raise_amount'];
      		} else {
      			$this->data['raise_amount'] = "";
      		}

      		if (isset($this->request->post['total_amount'])) {
      			$this->data['total_amount'] = $this->request->post['total_amount'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['total_amount'] = $paycheque_info['total_amount'];
      		} else {
      			$this->data['total_amount'] = "";
      		}

      		if (isset($this->request->post['total_hours'])) {
      			$this->data['total_hours'] = $this->request->post['total_hours'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['total_hours'] = $paycheque_info['total_hours'];
      		} else {
      			$this->data['total_hours'] = "";
      		}


      		if (isset($this->request->post['pay_date'])) {
      			$this->data['pay_date'] = $this->request->post['pay_date'];
      		} elseif (isset($paycheque_info)) {
      			if($paycheque_info['pay_date']!="" && $paycheque_info['pay_date']!="0000-00-00 00:00:00")$this->data['pay_date'] = date($this->language->get('date_format_short'), strtotime($paycheque_info['pay_date']));
      			else $this->data['pay_date'] = "";
      		} else {
      			$this->data['pay_date'] = "";
      		}

      		if (isset($this->request->post['paycheque_status'])) {
      			$this->data['paycheque_status'] = $this->request->post['paycheque_status'];
      		} elseif (isset($paycheque_info)) {
      			$this->data['paycheque_status'] = $paycheque_info['paycheque_status'];
      		} else {
      			$this->data['paycheque_status'] = "";
      		}

      		$this->data['paycheque_status_pre'] = $paycheque_info['paycheque_status'];

      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/paycheque/update&token=' . $this->session->data['token'] . '&paycheque_id=' . $this->request->get['paycheque_id'] . $url;
      		$this->template = 'cms/paycheques_form.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function lock() {

		$this->load->language('cms/paycheque');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/paycheque');
			
		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $paycheque_id) {
				$this->model_cms_paycheque->lockPaycheque($paycheque_id);
			}
				
			log_activity("Paycheques Locked", "Tutor paycheque(s) Locked.");
				
			$this->session->data['success'] = $this->language->get('text_success_lock');

			$url = '';
				
			$filters = array(
				'filter_paycheque_status', 
				'filter_paycheque_date', 
				'filter_student_name',
				'filter_paycheque_num', 
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
					
				$this->redirect(HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}
	 
	public function unlock() {
		$this->load->language('cms/paycheque');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/paycheque');
			
		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $paycheque_id) {
				$this->model_cms_paycheque->unlockPaycheque($paycheque_id);
			}
				
			log_activity("Paycheques Unlocked", "Tutor paycheque(s) Unlocked.");
				
			$this->session->data['success'] = $this->language->get('text_success_unlock');

			$url = '';
				
			$filters = array(
				'filter_paycheque_status', 
				'filter_paycheque_date', 
				'filter_student_name',
				'filter_paycheque_num', 
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
					
				$this->redirect(HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	public function delete() {
		$this->load->language('cms/paycheque');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/paycheque');

		if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $paycheque_id) {
				$this->model_cms_paycheque->deletePaycheque($paycheque_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_delete');
				
			log_activity("Paycheques Deleted", "Tutor paycheque(s) Deleted.");

			$url = '';
				
			$filters = array(
				'filter_paycheque_status', 
				'filter_paycheque_date', 
				'filter_tutor_name',
				'filter_paycheque_num', 
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
					
				$this->redirect(HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/paycheque')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		/*
		 if (($this->request->post['num_of_sessions']=="0") || ($this->request->post['num_of_sessions']=="")) {
			$this->error['num_of_sessions'] = $this->language->get('error_num_of_sessions');
			}

			if (($this->request->post['total_hours']=="0") || ($this->request->post['total_hours']=="")) {
			$this->error['total_hours'] = $this->language->get('error_total_hours');
			}
			*/
		if (($this->request->post['total_amount']=="0.00") || ($this->request->post['total_amount']=="")) {
			$this->error['total_amount'] = $this->language->get('error_total_amount');
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
		if (!$this->user->hasPermission('modify', 'cms/paycheque')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	  
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* Function by Softronikx Technologies - to mark multiple invoices as paid*/
	public function paid() {

		$this->load->language('cms/paycheque');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/paycheque');
			
		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $paycheque_id) {
					
				//set paid amount as total amount, blalance amount as 0, pay date as today and status as paid
				//only is status is not paid, then update the date, else do not update the date
				//in where clause add the condition status != paid so that the above scenario is taken care off
				//set paid_amount = total_amount where status ="Paid" and number = "paychq numbe" LIMIT 1;

				$data = array(
				 'paycheque_id' => $paycheque_id,								 
				 'balance_amount' => '0' ,				 				 
				 'paycheque_status' => 'Paid',
				 'pay_date' => date("Y-m-d H:i:s")				 					
				);

				$this->model_cms_paycheque->markPaychequeAsPaid($data);
				log_activity("Paycheque Updated", "Paycheque details updated.");
				
				/* Code to send email on marking as paid */
				// Set the mail format which needs to send
				$tutor_payment_mail = $this->model_cms_paycheque->getMailFormat('9');

				$subject = $tutor_payment_mail['broadcasts_subject'];
				$message = $tutor_payment_mail['broadcasts_content'];

				$paycheque_info = $this->model_cms_paycheque->getPayCheque($paycheque_id);
      			$this->data['tutor_name'] = $paycheque_info['tutor_name'];
				
				//print_r($paycheque_info);
				//exit(0);
				
				// Here you can define keys for replace before sending mail to Student
				$replace_info = array(
							'TUTOR_NAME' => $this->data['tutor_name'], 
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
				$mail->setTo($paycheque_info['tutors_email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();	
				/* End of code to send email on marking as Paid */			
				
			}
				
			$this->session->data['success'] = $this->language->get('text_success_marked_paid');

			$url = '';
				
			$filters = array(
				'filter_paycheque_status', 
				'filter_paycheque_date', 
				'filter_student_name',
				'filter_paycheque_num', 
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
					
				$this->redirect(HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}
	 
}
?>