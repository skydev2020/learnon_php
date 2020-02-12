<?php
class ControllerCmsExpenses extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/expenses');
		$this->getList();
	}

	public function export() {
		$this->load->model('cms/expenses');

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

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}

		if (isset($this->request->get['filter_amount'])) {
			$filter_amount = $this->request->get['filter_amount'];
		} else {
			$filter_amount = NULL;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = NULL;
		}
		if (isset($this->request->get['filter_start_date'])) {
			$filter_start_date = $this->request->get['filter_start_date'];
		} else {
			$filter_start_date = NULL;
		}
		if (isset($this->request->get['filter_end_date'])) {
			$filter_end_date = $this->request->get['filter_end_date'];
		} else {
			$filter_end_date = NULL;
		}

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_amount'] = $filter_amount;
		$this->data['filter_date'] = $filter_date;
		$this->data['filter_start_date'] = $filter_start_date;
		$this->data['filter_end_date'] = $filter_end_date;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$results = $this->model_cms_expenses->getAllExpenses($this->data);
		$arrresult = array();
		foreach($results as $result){
			unset($result['proexpen_id']);
			unset($result['type']);
			unset($result['date_added']);
			unset($result['date_modified']);
			unset($result['status']);
			$arrresult[] = $result;
		}
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("expenses.xls");
		exit;
	}

	public function insertall() {
			
		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title_insert');
		$this->load->model('cms/expenses');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormMultiple()) {

			$all_expenses = $this->request->post['all_expenses'];
			$all_amounts = $this->request->post['all_amounts'];
			$all_details = $this->request->post['all_details'];

			/* Softronikx Technologies */
			$all_dates = $this->request->post['all_dates'];

			/* End of Code by Softronikx Technologies */

			foreach($all_amounts as $key => $each_amount) {

				//and condition by Softronikx
				if(!empty($each_amount) and !empty($all_dates[$key])) {
					$data = array (
						'date' => $all_dates[$key],
					//'date' => $this->request->post['date'],
						'name' => $all_expenses[$key],
						'amount' => $each_amount,
						'detail' => $all_details[$key],
					);

					$this->model_cms_expenses->addExpenses($data);
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_insert');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_amount'])) {
				$url .= '&filter_amount=' . $this->request->get['filter_amount'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url);
		}
			
		$this->getFormMultiple();
	}

	public function insert() {
		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title_insert');
		$this->load->model('cms/expenses');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_cms_expenses->addExpenses($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_insert');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_amount'])) {
				$url .= '&filter_amount=' . $this->request->get['filter_amount'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url);
		}
			
		$this->getForm();
	}

	public function update() {
		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/expenses');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_cms_expenses->editExpenses($this->request->get['proexpen_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_update');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_amount'])) {
				$url .= '&filter_amount=' . $this->request->get['filter_amount'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('cms/expenses');

		$this->document->title = $this->language->get('heading_title_student');

		$this->load->model('cms/expenses');
			
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $proexpen_id) {
				$this->model_cms_expenses->deleteExpenses($proexpen_id);
			}
			log_activity("Expenses Deleted", "Student assignment deleted.");
			$this->session->data['success'] = $this->language->get('text_success_delete');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_amount'])) {
				$url .= '&filter_amount=' . $this->request->get['filter_amount'];
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url);
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
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}

		if (isset($this->request->get['filter_amount'])) {
			$filter_amount = $this->request->get['filter_amount'];
		} else {
			$filter_amount = NULL;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = NULL;
		}
		if (isset($this->request->get['filter_start_date'])) {
			$filter_start_date = $this->request->get['filter_start_date'];
		} else {
			$filter_start_date = NULL;
		}
		if (isset($this->request->get['filter_end_date'])) {
			$filter_end_date = $this->request->get['filter_end_date'];
		} else {
			$filter_end_date = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_amount'])) {
			$url .= '&filter_amount=' . $this->request->get['filter_amount'];
		}
			
		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}
		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/expenses/insert&token=' . $this->session->data['token'] . $url;
      		$this->data['insert_all'] = HTTPS_SERVER . 'index.php?route=cms/expenses/insertall&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/expenses/delete&token=' . $this->session->data['token'] . $url;

      		$this->data['expenses'] = array();

      		$data = array(
			'filter_name'              => $filter_name, 
			'filter_amount'             => $filter_amount, 
			'filter_date'        		=> $filter_date,
			'filter_start_date'        		=> $filter_start_date,
			'filter_end_date'        		=> $filter_end_date,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		$expenses_total = $this->model_cms_expenses->getTotalExpenses($data);
      		$results = $this->model_cms_expenses->getAllExpenses($data);

      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/expenses/update&token=' . $this->session->data['token'] . '&proexpen_id=' . $result['proexpen_id'] . $url
      			);

      			$this->data['expenses'][] = array(
				'proexpen_id'    => $result['proexpen_id'],
				'name'           => $result['name'],
				'amount'          => round($result['amount'], 2),
				'date'     		=> date("d-F-Y", strtotime($result['date'])),
				'action'         => $action
      			);
      		}
      		 
      		$this->data['heading_title'] = $this->language->get('heading_title');
      		$this->data['text_enabled'] = $this->language->get('text_enabled');
      		$this->data['text_disabled'] = $this->language->get('text_disabled');
      		$this->data['text_yes'] = $this->language->get('text_yes');
      		$this->data['text_no'] = $this->language->get('text_no');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_amount'] = $this->language->get('column_amount');
      		$this->data['column_subjects'] = $this->language->get('column_subjects');
      		$this->data['column_date'] = $this->language->get('column_date');
      		$this->data['column_action'] = $this->language->get('column_action');

      		$this->data['button_unassing'] = $this->language->get('button_unassing');
      		$this->data['button_insert'] = $this->language->get('button_insert');
      		$this->data['button_insert_all'] = $this->language->get('button_insert_all');
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

      		if (isset($this->request->get['filter_name'])) {
      			$url .= '&filter_name=' . $this->request->get['filter_name'];
      		}

      		if (isset($this->request->get['filter_amount'])) {
      			$url .= '&filter_amount=' . $this->request->get['filter_amount'];
      		}

      		if (isset($this->request->get['filter_date_added'])) {
      			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
      		}
      		if (isset($this->request->get['filter_start_date'])) {
      			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
      		}
      		if (isset($this->request->get['filter_end_date'])) {
      			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
      		}
      		 
      		if ($order == 'ASC') {
      			$url .= '&order=DESC';
      		} else {
      			$url .= '&order=ASC';
      		}

      		if (isset($this->request->get['page'])) {
      			$url .= '&page=' . $this->request->get['page'];
      		}

      		$this->data['sort_amount'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
      		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
      		$this->data['sort_date'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . '&sort=date_added' . $url;


      		$pagination = new Pagination();
      		$pagination->total = $expenses_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url . '&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();

      		$this->data['filter_name'] = $filter_name;
      		$this->data['filter_amount'] = $filter_amount;
      		$this->data['filter_date'] = $filter_date;
      		$this->data['filter_start_date'] = $filter_start_date;
      		$this->data['filter_end_date'] = $filter_end_date;

      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;

      		$this->template = 'cms/expenses_list.tpl';

      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getFormMultiple() {
			
		$this->data['heading_title'] = $this->language->get('heading_title_insert_all');
			
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
			
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_detail'] = $this->language->get('entry_detail');


		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add'] = $this->language->get('button_add');

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

		if (isset($this->error['amount'])) {
			$this->data['error_amount'] = $this->error['amount'];
		} else {
			$this->data['error_amount'] = '';
		}

		if (isset($this->error['date'])) {
			$this->data['error_date'] = $this->error['date'];
		} else {
			$this->data['error_date'] = '';
		}

		if (isset($this->error['detail'])) {
			$this->data['error_detail'] = $this->error['detail'];
		} else {
			$this->data['error_detail'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_amount'])) {
			$url .= '&filter_amount=' . $this->request->get['filter_amount'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		if (!isset($this->request->get['expennses_ids'])) {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/insertall&token=' . $this->session->data['token'] . $url;
      		} else {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/update&token=' . $this->session->data['token'] . '&proexpen_id=' . $this->request->get['proexpen_id'] . $url;
      		}

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url;

      		$proexpen_id = "";
      		if (isset($this->request->get['proexpen_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      			$this->data['heading_title'] = $this->language->get('heading_title_update');
      			$proexpen_id = $this->request->get['proexpen_id'];
      			$expense_info = $this->model_cms_expenses->getExpenses($proexpen_id);
      		}
      		 
      		if (isset($this->request->post['name'])) {
      			$this->data['name'] = $this->request->post['name'];
      		} elseif (isset($expense_info['name'])) {
      			$this->data['name'] = $expense_info['name'];
      		} else {
      			$this->data['name'] = "";
      		}
      		 
      		if (isset($this->request->post['amount'])) {
      			$this->data['amount'] = $this->request->post['amount'];
      		} elseif (isset($expense_info['amount'])) {
      			$this->data['amount'] = round($expense_info['amount'], 2);
      		} else {
      			$this->data['amount'] = "";
      		}
      		 
      		if (isset($this->request->post['date'])) {
      			$this->data['date'] = $this->request->post['date'];
      		} elseif (isset($expense_info['date'])) {
      			$this->data['date'] = $expense_info['date'];
      		} else {
      			$this->data['date'] = "";
      		}
      		 
      		if (isset($this->request->post['detail'])) {
      			$this->data['detail'] = $this->request->post['detail'];
      		} elseif (isset($expense_info['detail'])) {
      			$this->data['detail'] = $expense_info['detail'];
      		} else {
      			$this->data['detail'] = "";
      		}
      		 
      		if(isset($this->request->post['all_details']))
      		$all_details = $this->request->post['all_details'];
      		else
      		$all_details = array();

      		if(isset($this->request->post['all_amounts']))
      		$all_amounts = $this->request->post['all_amounts'];
      		else
      		$all_amounts = array();

      		if(isset($this->request->post['all_expenses']))
      		$all_expenses = $this->request->post['all_expenses'];
      		else
      		$all_expenses = $this->model_cms_expenses->getExpensesList();
      		 
      		if(!count($all_amounts))
      		foreach($all_expenses as $key => $each_expense) {

      			if(isset($all_amounts[$key]))
      			$all_amounts[$key] = $all_amounts[$key];
      			else
      			$all_amounts[$key] = "";

      			if(isset($all_details[$key]))
      			$all_details[$key] = $all_details[$key];
      			else
      			$all_details[$key] = "";
      		}
      		 
      		$this->data['all_expenses'] = $all_expenses;
      		$this->data['all_amounts'] = $all_amounts;
      		$this->data['all_details'] = $all_details;

      		$this->template = 'cms/expensesall_form.tpl';

      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
			
		$this->data['heading_title'] = $this->language->get('heading_title_insert');
			
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
			
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_detail'] = $this->language->get('entry_detail');


		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add'] = $this->language->get('button_add');

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

		if (isset($this->error['amount'])) {
			$this->data['error_amount'] = $this->error['amount'];
		} else {
			$this->data['error_amount'] = '';
		}

		if (isset($this->error['date'])) {
			$this->data['error_date'] = $this->error['date'];
		} else {
			$this->data['error_date'] = '';
		}

		if (isset($this->error['detail'])) {
			$this->data['error_detail'] = $this->error['detail'];
		} else {
			$this->data['error_detail'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_amount'])) {
			$url .= '&filter_amount=' . $this->request->get['filter_amount'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		if (!isset($this->request->get['proexpen_id'])) {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/insert&token=' . $this->session->data['token'] . $url;
      		} else {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/update&token=' . $this->session->data['token'] . '&proexpen_id=' . $this->request->get['proexpen_id'] . $url;
      		}

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url;

      		$proexpen_id = "";
      		if (isset($this->request->get['proexpen_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      			$this->data['heading_title'] = $this->language->get('heading_title_update');
      			$proexpen_id = $this->request->get['proexpen_id'];
      			$expense_info = $this->model_cms_expenses->getExpenses($proexpen_id);
      		}
      		 
      		if (isset($this->request->post['name'])) {
      			$this->data['name'] = $this->request->post['name'];
      		} elseif (isset($expense_info['name'])) {
      			$this->data['name'] = $expense_info['name'];
      		} else {
      			$this->data['name'] = "";
      		}
      		 
      		if (isset($this->request->post['amount'])) {
      			$this->data['amount'] = $this->request->post['amount'];
      		} elseif (isset($expense_info['amount'])) {
      			$this->data['amount'] = round($expense_info['amount'], 2);
      		} else {
      			$this->data['amount'] = "";
      		}
      		 
      		if (isset($this->request->post['date'])) {
      			$this->data['date'] = $this->request->post['date'];
      		} elseif (isset($expense_info['date'])) {
      			$this->data['date'] = $expense_info['date'];
      		} else {
      			$this->data['date'] = "";
      		}
      		 
      		if (isset($this->request->post['detail'])) {
      			$this->data['detail'] = $this->request->post['detail'];
      		} elseif (isset($expense_info['detail'])) {
      			$this->data['detail'] = $expense_info['detail'];
      		} else {
      			$this->data['detail'] = "";
      		}
      		 
      		$this->data['all_expenses'] = $this->model_cms_expenses->getExpensesList();

      		$this->template = 'cms/expenses_form.tpl';

      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateFormMultiple() {
		if (!$this->user->hasPermission('modify', 'cms/expenses')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
			
		//commented by softronikx technologies
		/*
		 if ($this->request->post['date'] == "") {
		 $this->error['date'] = $this->language->get('error_date');
		 }    */
			
		//    	print_r($this->error);

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/expenses')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['name'] == "") {
			$this->error['name'] = $this->language->get('error_name');
		}
			
		if ($this->request->post['amount'] == "") {
			$this->error['amount'] = $this->language->get('error_amount');
		}
			
		if ($this->request->post['date'] == "") {
			$this->error['date'] = $this->language->get('error_date');
		}
			
		//    	print_r($this->error);

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'cms/expenses')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
			
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* Softronikx Technologies */
	private function validateFormUpload() {
		if ($_FILES['income_file']['name'] == "" and $_FILES['expense_file']['name'] == "") {
			$this->error['income'] = $this->language->get('error_file');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* Softronikx Technologies */
	public function income() {
		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title_insert_income');
		$this->load->model('cms/expenses');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_cms_expenses->addIncome($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_income_insert');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_amount'])) {
				$url .= '&filter_amount=' . $this->request->get['filter_amount'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses/income&token=' . $this->session->data['token'] . $url);
		}
			
		$this->getIncomeForm();
	}

	/* Softronikx Technologies */
	private function getIncomeForm() {
			
		$this->data['heading_title'] = $this->language->get('heading_title_insert_income');
			
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
			
		$this->data['income_name'] = $this->language->get('income_name');
		$this->data['income_date'] = $this->language->get('income_date');
		$this->data['income_amount'] = $this->language->get('income_amount');
		$this->data['income_detail'] = $this->language->get('income_detail');


		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add'] = $this->language->get('button_add');

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

		if (isset($this->error['amount'])) {
			$this->data['error_amount'] = $this->error['amount'];
		} else {
			$this->data['error_amount'] = '';
		}

		if (isset($this->error['date'])) {
			$this->data['error_date'] = $this->error['date'];
		} else {
			$this->data['error_date'] = '';
		}

		if (isset($this->error['detail'])) {
			$this->data['error_detail'] = $this->error['detail'];
		} else {
			$this->data['error_detail'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_amount'])) {
			$url .= '&filter_amount=' . $this->request->get['filter_amount'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/expenses/income&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_insert_income'),
      		'separator' => ' :: '
      		);

      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/income&token=' . $this->session->data['token'] . $url;


      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url;

      		$proexpen_id = "";
      		if (isset($this->request->get['proexpen_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      			$this->data['heading_title'] = $this->language->get('heading_title_update');
      			$proexpen_id = $this->request->get['proexpen_id'];
      			$expense_info = $this->model_cms_expenses->getExpenses($proexpen_id);
      		}
      		 
      		if (isset($this->request->post['name'])) {
      			$this->data['name'] = $this->request->post['name'];
      		} elseif (isset($expense_info['name'])) {
      			$this->data['name'] = $expense_info['name'];
      		} else {
      			$this->data['name'] = "";
      		}
      		 
      		if (isset($this->request->post['amount'])) {
      			$this->data['amount'] = $this->request->post['amount'];
      		} elseif (isset($expense_info['amount'])) {
      			$this->data['amount'] = round($expense_info['amount'], 2);
      		} else {
      			$this->data['amount'] = "";
      		}
      		 
      		if (isset($this->request->post['date'])) {
      			$this->data['date'] = $this->request->post['date'];
      		} elseif (isset($expense_info['date'])) {
      			$this->data['date'] = $expense_info['date'];
      		} else {
      			$this->data['date'] = "";
      		}
      		 
      		if (isset($this->request->post['detail'])) {
      			$this->data['detail'] = $this->request->post['detail'];
      		} elseif (isset($expense_info['detail'])) {
      			$this->data['detail'] = $expense_info['detail'];
      		} else {
      			$this->data['detail'] = "";
      		}
      		 
      		//$this->data['all_expenses'] = $this->model_cms_expenses->getExpensesList();

      		$this->template = 'cms/income_form.tpl';

      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	/* Softronikx Technologies */
	public function csv()
	{
		ini_set('auto_detect_line_endings', true);

		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title_csv_upload');
		$this->load->model('cms/expenses');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormUpload()) {

			/* Code to upload CSV file data */
			//$this->request->post['income_file']['name']
			//$this->request->post['expense_file']['name']


			//upload income file data
			$csv = array();
			// check there are no errors
			if($_FILES['income_file']['error'] == 0){
				$name = $_FILES['income_file']['name'];
				$ext = strtolower(end(explode('.', $_FILES['income_file']['name'])));
				$type = $_FILES['income_file']['type'];
				$tmpName = $_FILES['income_file']['tmp_name'];

				// check the file is a csv
				if($ext === 'csv'){
					if(($handle = fopen($tmpName, 'r')) !== FALSE) {
							
						// necessary if a large csv file
						set_time_limit(0);

						$row = 0;

						while(($line = fgetcsv($handle)) !== FALSE) {

							$row++;
							if($row === 1)
							{
								continue;
							}

							// number of fields in the csv
							$num = count($line);

							if($num < 5)
							{
								// get the values from the csv
								$data['date'] = $line[0];
								$data['tutoring_revenue'] = $line[1];
								$data['homework_revenue'] = $line[2];
								$data['other_revenue'] = $line[3];

								//
								//print_r($data);

								if(!empty($data['date']) and $data['date'] <= '2011-08-31')
								$this->model_cms_expenses->addPastIncome($data);
									
								unset($data);
							}
						}
						fclose($handle);
					}
				}
			}

			//upload expense file data
			$csv = array();
			// check there are no errors
			if($_FILES['expense_file']['error'] == 0){
				$name = $_FILES['expense_file']['name'];
				$ext = strtolower(end(explode('.', $_FILES['expense_file']['name'])));
				$type = $_FILES['expense_file']['type'];
				$tmpName = $_FILES['expense_file']['tmp_name'];

				// check the file is a csv
				if($ext === 'csv'){
					if(($handle = fopen($tmpName, 'r')) !== FALSE) {
							
						// necessary if a large csv file
						set_time_limit(0);

						$row = 0;

						while(($line = fgetcsv($handle)) !== FALSE) {

							$row++;
							if($row == 1)
							{
								continue;
							}

							// number of fields in the csv
							$num = count($line);
							if($num > 30) //there should be atleast 30 columns in the csv file
							{
								// get the values from the csv
								$record['date'] = $line[0];
								$record['Tutor Payments'] = $line[1];
								$record['Drew Payments'] = $line[2];
								$record['Google Adwords'] = $line[3];
								$record['google adwords cards'] = $line[4];
								$record['Facebook Advertising'] = $line[5];
								$record['Yahoo Advertising'] = $line[6];
								$record['Print Advertising'] = $line[7];
								$record['Flyer Delivery'] = $line[8];
								$record['Other Advertising'] = $line[9];
								$record['Tutor Recruitment Cost'] = $line[10];
								$record['Printed Materials Expense'] = $line[11];
								$record['Website Expense'] = $line[12];
								$record['Website Domains'] = $line[13];
								$record['Website Maintenance'] = $line[14];
								$record['Website SEO monthly fee'] = $line[15];
								$record['Art & Designer Fees'] = $line[16];
								$record['LearnOn! Software'] = $line[17];
								$record['Computer Expense'] = $line[18];
								$record['Telephone Expense'] = $line[19];
								$record['1800 number Expense'] = $line[20];
								$record['Postage Expense'] = $line[21];
								$record['Office Supplies'] = $line[22];
								$record['Interest Expense'] = $line[23];
								$record['Bank Fees'] = $line[24];
								$record['Accountant Expense'] = $line[25];
								$record['Collections Expense'] = $line[26];
								$record['Legal Expense'] = $line[27];
								$record['Tax Expense'] = $line[28];
								$record['Auto: Depreciation'] = $line[29];
								$record['Auto: Parking and Tolls'] = $line[30];
								$record['Auto: Gasoline'] = $line[31];
								$record['Auto: Service and Reg'] = $line[32];
								$record['Auto: Public Transport'] = $line[33];
								$record['Auto: Misc'] = $line[34];
								$record['Gift Expense'] = $line[35];
								$record['Charity'] = $line[36];
								$record['Entertainment'] = $line[37];
								$record['Medical Expense'] = $line[38];
								$record['Travel Expense'] = $line[39];
								$record['Miscelanous Expense'] = $line[40];

								foreach($record as $key=>$value)
								{
									if($key != 'date')
									{
										$data['date'] = $record['date'];
										$data['name'] = $key;
										$data['amount'] = $value;
										$data['detail'] =  "Excel Upload";

										if(!empty($data['date']) and $data['date'] <= '2011-08-31' and !empty($data['name']) and !empty($data['amount']))
										{
											$this->model_cms_expenses->addExpenses($data);
										}
									}

									unset($data);
								}
							}

							unset($record);
						}
						fclose($handle);
					}
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_file_upload');


			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_amount'])) {
				$url .= '&filter_amount=' . $this->request->get['filter_amount'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url);
		}

		$this->getCSVUploadForm();
	}


	private function getCSVUploadForm()
	{

		$this->data['heading_title'] = $this->language->get('heading_title_csv_upload');
			
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
			
		$this->data['text_income_file_name'] = $this->language->get('text_income_file_name');
		$this->data['text_expense_file_name'] = $this->language->get('text_expense_file_name');

		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
			
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		//set error messages
		if (isset($this->error['income'])) {
			$this->data['error_income_file'] = $this->error['income'];
		} else {
			$this->data['error_income_file'] = '';
		}

		if (isset($this->error['expense'])) {
			$this->data['error_expense_file'] = $this->error['expense'];
		} else {
			$this->data['error_expense_file'] = '';
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_amount'])) {
			$url .= '&filter_amount=' . $this->request->get['filter_amount'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/expenses/csv&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_csv_upload'),
      		'separator' => ' :: '
      		);

      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/csv&token=' . $this->session->data['token'] . $url;


      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url;

      		$this->template = 'cms/csv_upload_form.tpl';

      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function base_invoice_rates()
	{
		ini_set('auto_detect_line_endings', true);

		$this->load->language('cms/expenses');
		$this->document->title = $this->language->get('heading_title_csv_upload');
		$this->load->model('cms/expenses');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateBaseForm()) {

			$this->model_cms_expenses->editBaseRates($this->request->post);

			if($this->validateFormUpload())
			{
			 //upload income file data
				$csv = array();
				// check there are no errors
				if($_FILES['income_file']['error'] == 0){
					$name = $_FILES['income_file']['name'];
					$ext = strtolower(end(explode('.', $_FILES['income_file']['name'])));
					$type = $_FILES['income_file']['type'];
					$tmpName = $_FILES['income_file']['tmp_name'];

					// check the file is a csv
					if($ext === 'csv'){
						if(($handle = fopen($tmpName, 'r')) !== FALSE) {

							// necessary if a large csv file
							set_time_limit(0);

							$row = 0;

							while(($line = fgetcsv($handle)) !== FALSE) {

								$row++;
								if($row === 1)
								{
									continue;
								}

								// number of fields in the csv
								$num = count($line);
								if($num > 5)
								{
									// get the values from the csv
									$data['base_wage'] = $line[3];
									$data['base_invoice'] = $line[4];
									$data['tutors_to_students_id'] = $line[0];

									$this->model_cms_expenses->updateTutorBaseRates($data);

									unset($data);
								}
							}
							fclose($handle);
						}
					}
				}
			}
			$this->session->data['success'] = $this->language->get('text_success_file_upload');


			$url = '';

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/expenses/base_invoice_rates&token=' . $this->session->data['token'] . $url);
		}
		$this->getBaseInvoiceForm();
	}

	public function exportBaseRates() {
		$this->load->model('cms/expenses');
		$results = $this->model_cms_expenses->getTutorBaseRates();
		// To setting Data
		$this->export->addData($results);

		// To setting File Name
		$this->export->download("baserates.csv");
		exit;
	}

	private function validateBaseForm() {
		if ($this->request->post['wage_usa'] == "" || $this->request->post['wage_canada'] == "" || $this->request->post['wage_alberta'] == "" || $this->request->post['invoice_usa'] == "" || $this->request->post['invoice_canada'] == "" || $this->request->post['invoice_alberta'] == "") {
			$this->error['name'] = "Please Enter All Details";
		}
			
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function getBaseInvoiceForm()
	{
		$this->load->model('cms/expenses');
		$this->data['heading_title'] = "Base Invoice Rates";
			
		$this->data['text_wage_usa'] = "Default Wage USA";
		$this->data['text_wage_canada'] = "Default Wage CANADA";
		$this->data['text_wage_alberta'] = "Default Wage ALBERTA";

		$this->data['text_invoice_usa'] = "Default Invoice USA";
		$this->data['text_invoice_canada'] = "Default Invoice CANADA";
		$this->data['text_invoice_alberta'] = "Default Invoice ALBERTA";

		$this->data['text_upload_new_rates'] = "Upload New Rates (CSV)";
		$this->data['text_download_old_rates'] = "Download Rates";

		$default_rates = $this->model_cms_expenses->getDefaultBaseRates();

		$this->data['wage_usa'] = $default_rates['wage_usa'];
		$this->data['wage_canada'] = $default_rates['wage_canada'];
		$this->data['wage_alberta'] = $default_rates['wage_alberta'];

		$this->data['invoice_usa'] =$default_rates['invoice_usa'];
		$this->data['invoice_canada'] = $default_rates['invoice_canada'];
		$this->data['invoice_alberta'] = $default_rates['invoice_alberta'];
			
		$this->data['button_edit'] = $this->language->get('button_edit');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
			
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$url = '';

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/expenses/base_invoice_rates&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_csv_upload'),
      		'separator' => ' :: '
      		);

      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/expenses/base_invoice_rates&token=' . $this->session->data['token'] . $url;


      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'] . $url;

      		$this->template = 'cms/base_invoice_form.tpl';

      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

}