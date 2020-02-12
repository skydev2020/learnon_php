<?php
class ControllerReportMonthlydata extends Controller {
	public function index() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title');
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['text_student_hours'] = $this->language->get('text_student_hours');
      		$this->data['text_tutor_hours'] = $this->language->get('text_tutor_hours');
      		$this->data['text_monthly_statiscs'] = $this->language->get('text_monthly_statiscs');
      		$this->data['text_yearly_statiscs'] = $this->language->get('text_yearly_statiscs');
      		$this->data['text_monthly_payroll_export'] = 'Export Payroll CSV (Monthly)';

      		$this->data['student_hours'] = HTTPS_SERVER.'index.php?route=report/monthlydata/studenthours&token='.$this->session->data['token'];
      		$this->data['tutor_hours'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/tutorhours&token=' . $this->session->data['token'];
      		$this->data['monthly_statiscs'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'];
      		$this->data['yearly_statiscs'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'];

      		$this->data['monthly_payroll_export'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlypayroll&token=' . $this->session->data['token'];
      		 
      		$this->template = 'report/monthlydata.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function studenthours() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_student');
		$this->data['heading_title'] = $this->language->get('heading_title_student');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'].$url,
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'].$url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/studenthours&token=' . $this->session->data['token'].$url,
       		'text'      => $this->language->get('heading_title_student'),
      		'separator' => ' :: '
      		);

      		$this->data['text_view_hours'] = $this->language->get('text_view_hours');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');
      		$this->data['column_action'] = $this->language->get('column_action');
      		$this->data['column_student_name'] = $this->language->get('column_student_name');
      		$this->data['token'] = $this->session->data['token'];

      		$this->load->model('user/students');

      		$data = array(
			'sort'                     => "name",
			'order'                    => "ASC",
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		$results = $this->model_user_students->getAllStudents($data);

      		foreach ($results as $result) {
      			$action = array();
      			$action[] = array(
				'text' => $this->language->get('text_view_hours'),
				'href' => HTTPS_SERVER . 'index.php?route=report/monthlydata/listhours&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id']
      			);
      			$this->data['students'][] = array(
				'user_id'    => $result['user_id'],
				'name'           => $result['name'],
				'action'         => $action
      			);
      		}

      		$users_total = $this->model_user_students->getTotalStudents($data);

      		$pagination = new Pagination();
      		$pagination->total = $users_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=report/monthlydata/studenthours&token=' . $this->session->data['token'] .$url.'&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		 
      		$this->template = 'report/list_students.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function tutorhours() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_tutor');
		$this->data['heading_title'] = $this->language->get('heading_title_tutor');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'].$url,
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'].$url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/tutorhours&token=' . $this->session->data['token'].$url,
       		'text'      => $this->language->get('heading_title_tutor'),
      		'separator' => ' :: '
      		);

      		$this->data['text_view_hours'] = $this->language->get('text_view_hours');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');
      		$this->data['column_action'] = $this->language->get('column_action');
      		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
      		$this->data['token'] = $this->session->data['token'];

      		$this->load->model('user/tutors');

      		$data = array(
			'sort'                     => "name",
			'order'                    => "ASC",
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'filter_approved' => 1, 
			'filter_status' => 1,
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		$results = $this->model_user_tutors->getAllTutors($data);

      		foreach ($results as $result) {
      			$action = array();
      			$action[] = array(
				'text' => $this->language->get('text_view_hours'),
				'href' => HTTPS_SERVER . 'index.php?route=report/monthlydata/listtutorhours&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id']
      			);
      			$this->data['tutors'][] = array(
				'user_id'    => $result['user_id'],
				'name'           => $result['name'],
				'action'         => $action
      			);
      		}

      		$users_total = $this->model_user_tutors->getTotalTutors($data);

      		$pagination = new Pagination();
      		$pagination->total = $users_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=report/monthlydata/tutorhours&token=' . $this->session->data['token'] .$url.'&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		 
      		$this->template = 'report/list_tutors.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function listtutorhours() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_tutor');
		$this->data['heading_title'] = $this->language->get('heading_title_tutor');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/tutorhours&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_tutor'),
      		'separator' => ' :: '
      		);

      		$user_id = $page = $this->request->get['user_id'];
      		if (isset($this->request->get['page'])) {
      			$page = $this->request->get['page'];
      		} else {
      			$page = 1;
      		}

      		if (isset($this->request->get['month'])) {
      			$month = $this->request->get['month'];
      		} else {
      			$month = date("m");
      		}

      		if (isset($this->request->get['year'])) {
      			$year = $this->request->get['year'];
      		} else {
      			$year = date("Y");
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

      		$this->data['text_no_results'] = $this->language->get('text_no_results');
      		$this->data['column_total_pay'] = $this->language->get('column_total_pay');
      		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
      		$this->data['column_pay_month'] = $this->language->get('column_pay_month');

      		$this->data['token'] = $this->session->data['token'];

      		$this->load->model('user/sessions');
      		if(isset($this->request->get['type'])){
      			$data = array(
				'filter_tutor_id'  => $user_id,
				'filter_month'         => $month,
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order
      			);
      		}else{
      			$data = array(
				'filter_tutor_id'  => $user_id,
				'filter_month'         => $month,
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                     => $this->config->get('config_admin_limit')
      			);
      		}

      		$payment_total = $this->model_user_sessions->getTotalPayments($data);
      		$results = $this->model_user_sessions->getPayments($data);
      		$this->data['payments'] = array();
      		$this->data['payments_ex'] = array();
      		foreach ($results as $result) {
      			$this->data['payments'][] = array(
				'total_pay'    => $result['total_pay'],
				'total_hours'    => $result['total_hours'],
				'pay_month'           => date('F Y', strtotime($result['session_date']))
      			);
      			$this->data['payments_ex'][] = array(
				'Total Hours'    => $result['total_hours'],
				'Total Pay'    => $result['total_pay'],
				'Month/Year'           => date('F Y', strtotime($result['session_date']))
      			);
      		}

      		if(isset($this->request->get['type'])){
      			// To setting Data
      			$this->export->addData($this->data['payments_ex']);

      			// To setting File Name
      			$this->export->download("tutor_hours.xls");
      			exit;
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
      		if (isset($month)) {
      			$url .= '&month=' . $month;
      		}
      		if (isset($month)) {
      			$url .= '&year=' . $year;
      		}
      		if (isset($this->request->get['user_id'])) {
      			$url .= '&user_id=' . $this->request->get['user_id'];
      		}

      		$this->data['sort_total_pay'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listtutorhours&token=' . $this->session->data['token'] . '&sort=total_pay' . $url;
      		$this->data['sort_total_hours'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listtutorhours&token=' . $this->session->data['token'] . '&sort=total_hours' . $url;
      		$this->data['sort_pay_month'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listtutorhours&token=' . $this->session->data['token'] . '&sort=pay_month' . $url;
      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listtutorhours&token=' . $this->session->data['token'].'&user_id=' . $this->request->get['user_id'];
      		$pagination = new Pagination();
      		$pagination->total = $payment_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=report/monthlydata/listtutorhours&token=' . $this->session->data['token'] .$url.'&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		$this->data['month'] = $month;
      		$this->data['year'] = $year;
      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;


      		$this->template = 'report/list_tutor_hours.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function monthlystatiscs() {

		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_statistics');
		$this->data['heading_title'] = $this->language->get('heading_title_statistics');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_statistics'),
      		'separator' => ' :: '
      		);


      		if (isset($this->request->get['page'])) {
      			$page = $this->request->get['page'];
      		} else {
      			$page = 1;
      		}

      		if (isset($this->request->get['month'])) {
      			$month = $this->request->get['month'];
      		} else {
      			$month = "";
      		}

      		if (isset($this->request->get['year'])) {
      			$year = $this->request->get['year'];
      		} else {
      			$year = date("Y");
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

      		/* Commented by Softronikx
      		 $this->data['text_no_results'] = $this->language->get('text_no_results');
      		 $this->data['column_total_revenue'] = $this->language->get('column_total_revenue');
      		 $this->data['column_hours_tutors'] = $this->language->get('column_hours_tutors');
      		 $this->data['column_total_expenses'] = $this->language->get('column_total_expenses');
      		 $this->data['column_total_profit'] = $this->language->get('column_total_profit');
      		 $this->data['column_no_of_students'] = $this->language->get('column_no_of_students');
      		 $this->data['column_no_of_tutors'] = $this->language->get('column_no_of_tutors');
      		 $this->data['column_pay_month'] = $this->language->get('column_pay_month');
      		 */

      		/* Softronikx Technologies */

      		$this->data['text_no_results'] = $this->language->get('text_no_results');
      		$this->data['column_monthly_tutoring_revenue'] = $this->language->get('column_monthly_tutoring_revenue');
      		$this->data['column_monthly_essay_revenue'] = $this->language->get('column_monthly_essay_revenue');
      		$this->data['column_monthly_other_revenue'] = $this->language->get('column_monthly_other_revenue');
      		$this->data['column_total_revenue'] = $this->language->get('column_total_revenue');

      		$this->data['column_total_payroll'] = $this->language->get('column_total_payroll');
      		$this->data['column_other_expenses'] = $this->language->get('column_other_expenses');
      		$this->data['column_total_expenses'] = $this->language->get('column_total_expenses');

      		$this->data['column_total_profit'] = $this->language->get('column_total_profit');

      		$this->data['column_no_of_students'] = $this->language->get('column_no_of_students');
      		$this->data['column_no_of_tutors'] = $this->language->get('column_no_of_tutors');
      		$this->data['column_pay_month'] = $this->language->get('column_pay_month');

      		$this->data['column_hours_tutors'] = $this->language->get('column_hours_tutors');

      		/* End of code by Softronikx Technologies */

      		$this->data['token'] = $this->session->data['token'];

      		$this->load->model('user/sessions');

      		if(isset($this->request->get['type'])){
      			$data = array(
				'filter_month'         => $month,
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order
      			);
      		}else{
      			$data = array(
				'filter_month'         => $month,
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                     => $this->config->get('config_admin_limit')
      			);
      		}

      		$this->data['curr_symbol'] = $this->currency->getSymbolLeft();

      		/*
      		 $this->load->model('cms/invoices');
      		 $this->model_cms_invoices->getMonthlyProfit();
      		 $this->model_cms_invoices->getYearlyProfit();

      		 $this->load->model('cms/paycheque');
      		 $this->model_cms_paycheque->getMonthlyExpense();
      		 $this->model_cms_paycheque->getYearlyExpense();
      		 */
      		$this->load->model('cms/invoices');
      		$this->load->model('cms/essays');
      		$this->load->model('cms/paycheque');
      		$this->load->model('cms/expenses');
      		$payment_total = $this->model_user_sessions->getTotalStatistics($data);
      		$results = $this->model_user_sessions->getStatistics($data);

      		$this->data['payments'] = array();
      		$this->data['payments_ex'] = array();
      		foreach ($results as $result) {

      			$total_package_profit = $this->model_cms_invoices->getMonthlyPackageProfit(date('m', strtotime($result['session_date'])), $year);

      			$total_student_profit = $this->model_cms_invoices->getMonthlyProfit(date('m', strtotime($result['session_date'])), $year);

      			$total_essays_profit = $this->model_cms_essays->getMonthlyProfit(date('m', strtotime($result['session_date'])), $year);


      			$total_tutor_expenses = $this->model_cms_paycheque->getMonthlyExpense(date('m', strtotime($result['session_date'])), $year);

      			$total_expenses = $this->model_cms_expenses->getMonthlyExpense(date('m', strtotime($result['session_date'])), $year);

      			$no_of_students = $this->model_user_sessions->getActiveStudents(date('m', strtotime($result['session_date'])), $year);
      			$no_of_tutors = $this->model_user_sessions->getActiveTutors(date('m', strtotime($result['session_date'])), $year);


      			$result['total_revenue'] = $total_student_profit + $total_package_profit + $total_essays_profit;
      			$result['total_profit'] = $result['total_revenue'] - $total_tutor_expenses;
      			//*/

      			if(!empty($total_expenses))
      			$total_profit = ($result['total_profit'] - $total_expenses);
      			else
      			$total_profit = $result['total_profit'];

      			$total_expenses = round($total_expenses, 2);

      			$total_other_profit = $this->model_cms_invoices->getMonthlyOtherIncome(date('m', strtotime($result['session_date'])), $year);//0;
      			$total_other_expenses = $total_expenses;


      			/* Commented by Softronikx Technologies
      			 $this->data['payments'][] = array(
      			 'total_revenue'    => round($result['total_revenue'], 2),
      			 'hours_tutors'    => $result['hours_tutors'],
      			 'total_expenses'    => round($total_expenses, 2),
      			 'total_profit'    => round($total_profit, 2),
      			 'no_of_students'    => $no_of_students,
      			 'no_of_tutors'    => $no_of_tutors,
      			 'pay_month'           => date('F Y', strtotime($result['session_date']))
      			 ); */

      			/* Softronikx Technologies */
      			$this->data['payments'][] = array(
				'tutoring_revenue' => round(($total_package_profit + $total_student_profit), 2),
				'assignment_revenue' => round(($total_essays_profit), 2),
				'other_revenue' => round($total_other_profit,2),
				'total_revenue'    => round($total_package_profit + $total_student_profit + $total_essays_profit + $total_other_profit, 2),
				'payroll' => round($total_tutor_expenses,2),				
				'other_expense' => round($total_other_expenses,2),
				'total_expenses' => round($total_tutor_expenses + $total_other_expenses,2),
				'total_profit' => round(($total_package_profit + $total_student_profit + $total_essays_profit + $total_other_profit)-($total_tutor_expenses + $total_other_expenses),2),
				'no_of_students'    => $no_of_students,
				'no_of_tutors'    => $no_of_tutors,
				'pay_month'           => date('F Y', strtotime($result['session_date']))
      			);


      			$this->data['payments_ex'][] = array(
				'Total Revenue'    => $result['total_revenue'],
				'Hours Tutors'    => $result['hours_tutors'],
				'Total Expenses'    => $total_expenses,
				'Total Profit'    => $total_profit,
				'# Active Students'    => $no_of_students,
				'# Active Tutors'    => $no_of_tutors,
				'Month/Year'           => date('F Y', strtotime($result['session_date']))
      			);
      		}
      		if(isset($this->request->get['type'])){
      			// To setting Data
      			$this->export->addData($this->data['payments_ex']);

      			// To setting File Name
      			$this->export->download("monthly_statistics.xls");
      			exit;
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
      		if (isset($month)) {
      			$url .= '&month=' . $month;
      		}
      		if (isset($month)) {
      			$url .= '&year=' . $year;
      		}

      		$this->data['sort_total_revenue'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'] . '&sort=total_revenue' . $url;
      		$this->data['sort_hours_tutors'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'] . '&sort=hours_tutors' . $url;
      		$this->data['sort_total_profit'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'] . '&sort=total_profit' . $url;
      		$this->data['sort_pay_month'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'] . '&sort=pay_month' . $url;
      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'];
      		$pagination = new Pagination();
      		$pagination->total = $payment_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'] .$url.'&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		$this->data['month'] = $month;
      		$this->data['year'] = $year;
      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;

      		$this->template = 'report/monthly_statistics.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function monthlypayroll() { 
		$this->load->language('report/monthlydata');
		$this->load->model('cms/paycheque');
		$this->document->title = "Export Payroll";
		$this->data['heading_title'] = "Export Payroll";

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateMonthlyPayrollForm()) {
			$monthname = date("F", mktime(0, 0, 0, $this->request->post['month'], 10));
			$results = $this->model_cms_paycheque->getMonthlyMasterExportList($this->request->post['month'],$this->request->post['year']);
			if($this->request->post['export_masterlist']!='')
			{
				$arrresult = array();
				$arrresult[] = array('Name','Address','City','State','ZIP','Amount','Country');
				foreach($results as $arr_result)
				{
					$pcode= $arr_result['pcode'];
					if(strlen($arr_result['pcode'])<=6 && !is_numeric($arr_result['pcode']))
					{
						$part1 = substr($pcode, 0, 3);
						$part2 = substr($pcode, 3);
						$pcode = $part1." ".$part2;
					}
					$arrresult[] = array(ucwords($arr_result['tutor_name']),
					$this->convert_address(ucwords(strtolower($arr_result['address']))),
					ucwords(strtolower($arr_result['city'])),
					ucwords($arr_result['state']),
					strtoupper($pcode),
					number_format($arr_result['total_amount'], 2),
					ucwords($arr_result['country']));
				}
				// To setting File Name month_year_cheques.xls
				$this->download($monthname."_".$this->request->post['year']."_envelopes.csv",$arrresult);

			}
			else if($this->request->post['export_paycheque']!='')
			{
				$arrresult = array();
				$total = 	count($results);
				$i=0;
				$arrresult[] = array('Name0','A0','C0','Amount Written 0','','Name1','A1','C1','Amount Written 1','','Name2','A2','C2','Amount Written 2');
				while($i<$total)
				{
					$row1 = $results[$i];
					$i++;
					$row2 = $results[$i];
					$i++;
					$row3 = $results[$i];
					$i++;
						
					//$arrresult[] = array(ucwords($row1['tutor_name']),$row1['address'],$row1['city'],$row1['state'],$row1['pcode'],$row1['total_amount'],substr($row1['total_amount'], -2),$this->convert_number_to_words(number_format($row1['total_amount'])),"",$row2['tutor_name'],$row2['address'],$row2['city'],$row2['state'],$row2['pcode'],$row2['total_amount'],substr($row2['total_amount'], -2),$this->convert_number_to_words($row2['total_amount']),"",$row3['tutor_name'],$row3['address'],$row3['city'],$row3['state'],$row3['pcode'],$row3['total_amount'],substr($row3['total_amount'], -2),$this->convert_number_to_words($row3['total_amount']));
					$arrresult[] = array(ucwords($row1['tutor_name']),
					number_format($row1['total_amount'], 2, '.', ''),
					"'".substr($row1['total_amount'], -2),
					ucfirst($this->convert_number_to_words(floor($row1['total_amount']))),
					" ",
					ucwords($row2['tutor_name']),
					number_format($row2['total_amount'], 2, '.', ''),
					"'".substr($row2['total_amount'], -2),
					ucfirst($this->convert_number_to_words(floor($row2['total_amount']))),
					" ",
					ucwords($row3['tutor_name']),
					number_format($row3['total_amount'], 2, '.', ''),
					"'".substr($row3['total_amount'], -2),
					ucfirst($this->convert_number_to_words(floor($row3['total_amount']))));
				}

				// To setting File Name
				$this->download($monthname."_".$this->request->post['year']."_cheques.csv",$arrresult);
			}
		}

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['button_cancel'] = $this->language->get('button_cancel');
      		$this->data['button_export'] = $this->language->get('button_export');
      		 
      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url;
      		 
      		$this->template = 'report/monthlypayroll.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}


	private function download($filename,$data) {
		header("Content-Type: application/force-download");
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment;filename=$filename ");
		// create a file pointer connected to the output stream
		$fp = fopen('php://output', 'a');
		foreach ($data as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
		exit;
	}

	/* Softronikx Technologies */
	private function validateMonthlyPayrollForm() {
		if ($this->request->server['month'] == "" || $this->request->server['year']=="") {
			$this->error['error_month_filter'] = "Please select Month and Year";
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	public function convert_address($address)
	{
		$street_array  = array('Street',
'Avenue',
'Boulevard',
'Place',
'Court',
'Crescent',
'Circle',
'Parkway',
'Drive',
'Road',
		);

		$street_sf_array  = array('/\bSt\b/',
'/\bAve\b/',
'/\bBlvd\b/',
'/\bPlc\b/',
'/\bCt\b/',
'/\bCres\b/',
'/\bCrl\b/',
'/\bPkwy\b/',
'/\bDr\b/',
'/\bRd\b/',
		);

		$street_rep_array  = array('St.',
'Ave.',
'Blvd.',
'Plc.',
'Ct.',
'Cres.',
'Crl.',
'Pkwy.',
'Dr.',
'Rd.',
		);

		$area_array  = array('Ne',
'Nw',
'Se',
'Sw',
		);

		$area_rep_array  = array('NE',
'NW',
'SE',
'SW',);



		$address =preg_replace($street_sf_array,$street_rep_array,ucwords($address));
		//		  str_replace($street_sf_array,$street_rep_array,ucwords($address));
		$address = str_replace('..','.',str_replace($street_array,$street_rep_array,ucwords($address)));

		return str_replace($area_array,$area_rep_array,ucwords($address));

	}

	public  function convert_number_to_words($number) {

		$hyphen      = ' ';
		$conjunction = ' and ';
		$separator   = '';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
		0                   => 'zero',
		1                   => 'one',
		2                   => 'two',
		3                   => 'three',
		4                   => 'four',
		5                   => 'five',
		6                   => 'six',
		7                   => 'seven',
		8                   => 'eight',
		9                   => 'nine',
		10                  => 'ten',
		11                  => 'eleven',
		12                  => 'twelve',
		13                  => 'thirteen',
		14                  => 'fourteen',
		15                  => 'fifteen',
		16                  => 'sixteen',
		17                  => 'seventeen',
		18                  => 'eighteen',
		19                  => 'nineteen',
		20                  => 'twenty',
		30                  => 'thirty',
		40                  => 'forty',
		50                  => 'fifty',
		60                  => 'sixty',
		70                  => 'seventy',
		80                  => 'eighty',
		90                  => 'ninety',
		100                 => 'hundred',
		1000                => 'thousand',
		1000000             => 'million',
		1000000000          => 'billion',
		1000000000000       => 'trillion',
		1000000000000000    => 'quadrillion',
		1000000000000000000 => 'quintillion'
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction .  $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string =  $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .=  $this->convert_number_to_words($remainder);
				}
				break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}
		return $string;
	}

	public function yearlystatiscs() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_yearly_statistics');
		$this->data['heading_title'] = $this->language->get('heading_title_yearly_statistics');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_yearly_statistics'),
      		'separator' => ' :: '
      		);


      		if (isset($this->request->get['page'])) {
      			$page = $this->request->get['page'];
      		} else {
      			$page = 1;
      		}

      		if (isset($this->request->get['year'])) {
      			$year = $this->request->get['year'];
      		} else {
      			$year = "";
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

      		$this->data['text_no_results'] = $this->language->get('text_no_results');
      		$this->data['column_total_revenue'] = $this->language->get('column_total_revenue');
      		$this->data['column_hours_tutors'] = $this->language->get('column_hours_tutors');
      		$this->data['column_total_profit'] = $this->language->get('column_total_profit');
      		$this->data['column_total_expenses'] = $this->language->get('column_total_expenses');
      		$this->data['column_no_of_students'] = $this->language->get('column_no_of_students');
      		$this->data['column_no_of_tutors'] = $this->language->get('column_no_of_tutors');
      		$this->data['column_pay_year'] = $this->language->get('column_pay_year');

      		$this->data['token'] = $this->session->data['token'];

      		$this->load->model('user/sessions');
      		if(isset($this->request->get['type'])){
      			$data = array(
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order
      			);
      		}else{
      			$data = array(
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                     => $this->config->get('config_admin_limit')
      			);
      		}

      		$this->data['curr_symbol'] = $this->currency->getSymbolLeft();

      		$this->load->model('cms/invoices');
      		$this->load->model('cms/essays');
      		$this->load->model('cms/paycheque');
      		$this->load->model('cms/expenses');
      		$payment_total = $this->model_user_sessions->getTotalStatistics($data);
      		$results = $this->model_user_sessions->getStatistics($data);

      		$this->data['payments'] = array();
      		$this->data['payments_ex'] = array();
      		foreach ($results as $result) {
      			$total_package_profit = $this->model_cms_invoices->getYearlyPackageProfit(date('Y', strtotime($result['session_date'])));

      			$total_student_profit = $this->model_cms_invoices->getYearlyProfit(date('Y', strtotime($result['session_date'])));

      			$total_essays_profit = $this->model_cms_essays->getYearlyProfit(date('Y', strtotime($result['session_date'])));

      			$total_tutor_expenses = $this->model_cms_paycheque->getYearlyExpense(date('Y', strtotime($result['session_date'])));

      			$total_expenses = $this->model_cms_expenses->getYearlyExpense(date('Y', strtotime($result['session_date'])));
      			$no_of_students = $this->model_user_sessions->getActiveStudents("", date('Y', strtotime($result['session_date'])));
      			$no_of_tutors = $this->model_user_sessions->getActiveTutors("", date('Y', strtotime($result['session_date'])));

      			$result['total_revenue'] = $total_student_profit + $total_package_profit + $total_essays_profit;
      			$result['total_profit'] = $result['total_revenue'] - $total_tutor_expenses;

      			if(!empty($total_expenses))
      			$total_profit = round(($result['total_profit'] - $total_expenses), 2);
      			else
      			$total_profit = $result['total_profit'];

      			$total_expenses = round($total_expenses, 2);

      			$this->data['payments'][] = array(
				'total_revenue'    => $result['total_revenue'],
				'hours_tutors'    => $result['hours_tutors'],
				'total_expenses'    => $total_expenses,
				'total_profit'    => $total_profit,
				'no_of_students'    => $no_of_students,
				'no_of_tutors'    => $no_of_tutors,
				'pay_month'           => date('Y', strtotime($result['session_date']))
      			);
      			$this->data['payments_ex'][] = array(
				'Total Revenue'    => $result['total_revenue'],
				'Hours Tutors'    => $result['hours_tutors'],
				'Total Expenses'    => $total_expenses,
				'Total Profit'    => $total_profit,
				'# Active Students'    => $no_of_students,
				'# Active Tutors'    => $no_of_tutors,
				'Year'           => date('Y', strtotime($result['session_date']))
      			);
      		}

      		if(isset($this->request->get['type'])){
      			// To setting Data
      			$this->export->addData($this->data['payments_ex']);

      			// To setting File Name
      			$this->export->download("yearly_statistics.xls");
      			exit;
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
      		if (isset($month)) {
      			$url .= '&year=' . $year;
      		}

      		$this->data['sort_total_revenue'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'] . '&sort=total_revenue' . $url;
      		$this->data['sort_hours_tutors'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'] . '&sort=hours_tutors' . $url;
      		$this->data['sort_total_profit'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'] . '&sort=total_profit' . $url;
      		$this->data['sort_pay_month'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'] . '&sort=pay_month' . $url;
      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'];
      		$pagination = new Pagination();
      		$pagination->total = $payment_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=report/monthlydata/yearlystatiscs&token=' . $this->session->data['token'] .$url.'&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		$this->data['year'] = $year;
      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;

      		$this->template = 'report/yearly_statistics.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function listhours() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_student');
		$this->data['heading_title'] = $this->language->get('heading_title_student');
		$user_id = $page = $this->request->get['user_id'];
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['month'])) {
			$month = $this->request->get['month'];
		} else {
			$month = date("m");
		}

		if (isset($this->request->get['year'])) {
			$year = $this->request->get['year'];
		} else {
			$year = date("Y");
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

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/studenthours&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_student'),
      		'separator' => ' :: '
      		);

      		$this->data['text_no_results'] = $this->language->get('text_no_results');
      		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
      		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
      		$this->data['column_session_date'] = $this->language->get('column_session_date');

      		$this->data['token'] = $this->session->data['token'];

      		$this->load->model('user/sessions');
      		if(isset($this->request->get['type'])){
      			$data = array(
				'filter_student_id'  => $user_id,
				'filter_month'         => $month,
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order
      			);
      		}else{
      			$data = array(
				'filter_student_id'  => $user_id,
				'filter_month'         => $month,
				'filter_year'         => $year,
				'sort'                     => $sort,
				'order'                   => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                     => $this->config->get('config_admin_limit')
      			);
      		}

      		$session_total = $this->model_user_sessions->getTotalSessions($data);
      		$results = $this->model_user_sessions->getSessions($data);
      		$duration_array = $this->model_user_sessions->getAllDurations();
      		$this->data['sessions'] = array();
      		$this->data['sessions_ex'] = array();
      		foreach ($results as $result) {
      			$this->data['sessions'][] = array(
				'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'tutor_name'    => $result['tutor_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'session_duration'          => $duration_array[$result['session_duration']]
      			);
      			$this->data['sessions_ex'][] = array(
				'Tutor Name'    => $result['tutor_name'],
				'Date of Session'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'Duration of Session'          => $duration_array[$result['session_duration']]
      			);
      		}

      		if(isset($this->request->get['type'])){
      			// To setting Data
      			$this->export->addData($this->data['sessions_ex']);

      			// To setting File Name
      			$this->export->download("student_hours.xls");
      			exit;
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
      		if (isset($month)) {
      			$url .= '&month=' . $month;
      		}
      		if (isset($year)) {
      			$url .= '&year=' . $year;
      		}
      		if (isset($this->request->get['user_id'])) {
      			$url .= '&user_id=' . $this->request->get['user_id'];
      		}

      		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listhours&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
      		$this->data['sort_session_date'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listhours&token=' . $this->session->data['token'] . '&sort=session_date' . $url;
      		$this->data['sort_session_duration'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listhours&token=' . $this->session->data['token'] . '&sort=session_duration' . $url;
      		$this->data['action'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/listhours&token=' . $this->session->data['token'].'&user_id=' . $this->request->get['user_id'];
      		$pagination = new Pagination();
      		$pagination->total = $session_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=report/monthlydata/listhours&token=' . $this->session->data['token'] .$url.'&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();
      		$this->data['month'] = $month;
      		$this->data['year'] = $year;
      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;
      		$this->template = 'report/list_hours.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function studentreport() {
		$this->load->language('report/monthlydata');
		$this->load->language('user/students');
		$this->document->title = $this->language->get('heading_title_statistics');
		$this->data['heading_title'] = $this->language->get('heading_title_statistics');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_statistics'),
      		'separator' => ' :: '
      		);

      		$this->data['column_user_id'] = $this->language->get('column_user_id');
      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_email'] = $this->language->get('column_email');
      		$this->data['column_city'] = $this->language->get('column_city');
      		$this->data['column_total_hours'] = "Total Hours";
      		$this->data['column_total_revenue'] = "Total Revenues";
      		$this->data['column_total_profit'] ="Total Profits";

      		$this->data['button_export'] = $this->language->get('button_export');

      		$this->load->model('user/students');
      		$results = $this->model_user_students->getStudentReport();

      		$this->data['results'] = $results;

      		$url = HTTPS_SERVER . 'index.php?route=report/monthlydata/exportstudentreport&token=' . $this->session->data['token'];
      		$this->data['action'] = $url;

      		$this->template = 'report/studentreport.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function exportstudentreport() {
		$this->load->model('user/students');

		$results = $this->model_user_students->getStudentReport();
		$arrresult = array();
		$arrresult = $results;
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("studentsreport.xls");
	}

	public function tutorreport() {
		$this->load->language('report/monthlydata');
		$this->load->language('user/students');
		$this->document->title = $this->language->get('heading_title_statistics');
		$this->data['heading_title'] = $this->language->get('heading_title_statistics');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_statistics'),
      		'separator' => ' :: '
      		);

      		$this->data['column_user_id'] = $this->language->get('column_user_id');
      		$this->data['column_name'] = "Tutor Name";
      		$this->data['column_email'] = $this->language->get('column_email');
      		$this->data['column_students_tutored'] = "Students Tutored";
      		$this->data['column_total_hours'] = "Total Hours Tutored";
      		$this->data['column_total_avg_hours'] = "Avg Per Student(Hours)";
      		$this->data['column_total_avg_duration'] ="Avg Per Student(Duration)";

      		$this->data['button_export'] = $this->language->get('button_export');

      		$this->load->model('user/students');
      		$results = $this->model_user_students->getTutorReport();

      		$this->data['results'] = $results;

      		$url = HTTPS_SERVER . 'index.php?route=report/monthlydata/exporttutorreport&token=' . $this->session->data['token'];
      		$this->data['action'] = $url;

      		$this->template = 'report/tutorreport.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function exporttutorreport() {
		$this->load->model('user/students');
		$results = $this->model_user_students->getTutorReport();
		$arrresult = array();
		$arrresult = $results;
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("tutorreport.xls");
	}
}
