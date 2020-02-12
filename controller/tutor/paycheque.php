<?php
class ControllerTutorPaycheque extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('tutor/paycheque');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/paycheque');
		
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/paycheque&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		$this->data['pay_link'] = HTTPS_SERVER . 'index.php?route=tutor/paycheque/paymentrecords&token=' . $this->session->data['token'];
		$this->data['column_click_month'] = $this->language->get('column_click_month');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$results = $this->model_tutor_paycheque->getPayMonths();
		$this->data['results'] = $results;
		$this->template = 'tutor/paycheques_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function paymentrecords() {
		$this->load->language('tutor/paycheque');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/paycheque');
		$this->getList();
	}

	private function getList() {
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/paycheque&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['button_print'] = $this->language->get('button_print');
		
		$this->data['text_essay_details'] = $this->language->get('text_essay_details');
		$this->data['text_session_total'] = $this->language->get('text_session_total');
		$this->data['text_essay_total'] = $this->language->get('text_essay_total');
		
		//$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_student_name'] = 'Assignment#'; //modified Softronikx Technologies 7th May, 2013
		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_session_date'] = $this->language->get('column_session_date');
		$this->data['column_session_amount'] = $this->language->get('column_session_amount');
		
		
		$this->data['column_essay_topic'] = $this->language->get('column_essay_topic');
		$this->data['column_essay_date'] = $this->language->get('column_essay_date');
		$this->data['column_essay_amount'] = $this->language->get('column_essay_amount');	
		
		
		
		$this->data['token'] = $this->session->data['token'];
		
		$month = $this->request->get['month'];
		$year = $this->request->get['year'];
		$paycheque_id = $this->request->get['paycheque_id'];
		
		
		$paycheque_info = $this->model_tutor_paycheque->getPayCheque($paycheque_id);
		$log_data = unserialize($paycheque_info['log_data']); 
//		print_r($log_data);
		
		$this->data['text_grand_total'] = sprintf($this->language->get('text_grand_total'), date("F Y", strtotime($paycheque_info['paycheque_date'])));
						
		$this->data['paycheque_info'] = array(
			'session_amount' => round(($paycheque_info['total_amount'] - $paycheque_info['essay_amount']), 2),
			'essay_amount' => $paycheque_info['essay_amount'],
			'total_paid' => $paycheque_info['paid_amount']
		);
		
		$sessions_ides = $log_data['all_sessions'];
		$students_raise = $log_data['all_students_data'];
		$essays_ides = $log_data['tutor_essays_details'];
		
		$sessions_raise = array();
		foreach($students_raise as $each_student) {
			foreach($each_student as $each_session) {
				$sessions_raise[$each_session['session_id']] = $each_session['raise_amount'];		
			}
		}
		$sessions_raise_keys = array_keys($sessions_raise);
//		print_r($sessions_raise);
		
		$sessions_info = array();
		if(!empty($sessions_ides))
			$sessions_info = $this->model_tutor_paycheque->getAllSessions($sessions_ides);
		
		$essays_info = array();
		if(!empty($essays_ides))		
			$essays_info = $this->model_tutor_paycheque->getAllEssays($essays_ides);
		
		$this->data['all_sessions'] = array();
		foreach ($sessions_info as $each_session) {
			$this->data['all_sessions'][] = array(
				'session_id' => $each_session['session_id'],
				'student_name' => $each_session['student_name'],
				'session_duration' => $each_session['session_duration']." hours",
				'session_date' => date("d-M-Y", strtotime($each_session['session_date'])),
				'session_amount' => (in_array($each_session['session_id'], $sessions_raise_keys)) ? $sessions_raise[$each_session['session_id']] : $each_session['session_amount']
			);			
		}
		
		$this->data['all_essays'] = array();
		foreach ($essays_info as $each_essay) {
			$this->data['all_essays'][] = array(
				'essay_id' => $each_essay['essay_id'],
				'student_name' => $each_essay['assignment_num'], //changed 7th May, 2013 as name is supposed to be hidden in tutors login
				'essay_topic' => $each_essay['topic'],
				'essay_date' => date("Y-m-d", strtotime($each_essay['date_completed'])),
				'essay_amount' => $each_essay['paid']
			);			
		}
			
		/*print_r($paycheque_info);
		print_r($sessions_info);
		print_r($essays_info);*/

		$this->template = 'tutor/payment_records_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

}
?>