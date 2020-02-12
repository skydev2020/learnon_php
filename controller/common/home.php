<?php   
class ControllerCommonHome extends Controller {   
	public function index() {

		$group_id = isset($this->session->data['group_id'])?$this->session->data['group_id']:0;

		// switching the header template according to the user group		
		switch($group_id) {
		
			case '1':
				$this->student();
				$this->template = 'common/home_student.tpl';
			break;
			case '2':
				$this->tutor();
				$this->template = 'common/home_tutor.tpl';
			break;
			case '3':
				$this->administrator();
				$this->template = 'common/home.tpl';
			break;
			default:
				$this->administrator();
				$this->template = 'common/home.tpl';
			break;
		};

		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
	
	public function student(){
		
		if (isset ($this->request->get['cancel'])) {
			$this->cart->clear();
			unset($this->session->data['coupon']);
			unset($this->session->data['payment_method']);
			$this->data['error'] = 'Your payment has been canceled.';			
		}
		
    	$this->load->language('student/invoice');
    	$this->load->language('common/home_student');
		
		$this->document->title = $this->language->get('heading_title');
    	
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_active_packages'] = $this->language->get('text_active_packages');
		
		$this->data['text_latest_5_students'] = $this->language->get('text_latest_5_students');
		$this->data['text_latest_5_invoices'] = $this->language->get('text_latest_5_invoices');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_last_login'] = $this->language->get('text_last_login');
		$this->data['text_total_report_card'] = $this->language->get('text_total_report_card');
		$this->data['text_total_sessions'] = $this->language->get('text_total_sessions');
		$this->data['text_total_students'] = $this->language->get('text_total_students');
		$this->data['text_latest_notifications'] = $this->language->get('text_latest_notifications');
		$this->data['text_view_all'] = $this->language->get('text_view_all');
		$this->data['text_total_notifications'] = $this->language->get('text_total_notifications');
		
		
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
		
		
		$this->load->model('student/profile');
		$user_info = $this->model_student_profile->getStudent($this->session->data['user_id']);
		
//		print_r($user_info);

		$student_status = $this->model_student_profile->getStudentStatus();
		
		$this->data['text_active_status'] = $this->language->get('text_active_status');
		
		$this->data['text_package_update'] = $this->language->get('text_package_update');
		
		if($user_info['students_status_id'] == '1')
			$this->data['active_status'] = $this->language->get('text_active');
		else
			$this->data['active_status'] = $student_status[$user_info['students_status_id']];
		
		$key = 2;
		$url = "&status=".$key;
		$this->data['all_status'][] = array(
			'key' => $key,
			'text' => $student_status[$key],
			'link' => HTTPS_SERVER . 'index.php?route=common/home/changeStudentStatus&token=' . $this->session->data['token']. $url,
			'help' => $this->language->get('help_stop_tutoring'),			
		);
		
		$key = 1;
		$url = "&status=".$key;
		$this->data['all_status'][] = array(
			'key' => $key,
			'text' => $student_status[$key],
			'link' => HTTPS_SERVER . 'index.php?route=common/home/changeStudentStatus&token=' . $this->session->data['token']. $url,
			'help' => $this->language->get('help_need_tutoring'),			
		);		
		
		$key = 3;
		$url = "&status=".$key;
		$this->data['all_status'][] = array(
			'key' => $key,
			'text' => $student_status[$key],
			'link' => HTTPS_SERVER . 'index.php?route=common/home/changeStudentStatus&token=' . $this->session->data['token']. $url,
			'help' => $this->language->get('help_change_tutor'),			
		);
		
		$key = 4;
		$url = "&status=".$key;
		$this->data['all_status'][] = array(
			'key' => $key,
			'text' => $student_status[$key],
			'link' => HTTPS_SERVER . 'index.php?route=common/home/changeStudentStatus&token=' . $this->session->data['token']. $url,
			'help' => $this->language->get('help_start_new'),			
		);	
		

  		$this->data['column_package_name'] = $this->language->get('column_package_name');
		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
		$this->data['column_hours_left'] = $this->language->get('column_hours_left');		
		
  		$this->data['column_invoice_num'] = $this->language->get('column_invoice_num');
		$this->data['column_invoice_date'] = $this->language->get('column_invoice_date');
		$this->data['column_total_hours'] = $this->language->get('column_total_hours');
		$this->data['column_total_amount'] = $this->language->get('column_total_amount');		
		
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_date_sent'] = $this->language->get('column_date_sent');
		$this->data['column_grade'] = $this->language->get('column_grade');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		
		$this->data['column_notification_from'] = $this->language->get('column_notification_from');
		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_date_send'] = $this->language->get('column_date_send');
		
		$this->data['column_action'] = $this->language->get('column_action');

		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_warning'] = $this->language->get('error_warning');
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
		
		$this->data['token'] = $this->session->data['token'];
		
		if($this->session->data['last_login'] == "" || $this->session->data['last_login'] == "0000-00-00 00:00:00"){
			$this->data['last_login'] = "Never";
		}else{
			$this->data['last_login'] = date($this->language->get('date_format_long').' '.$this->language->get('time_format'), strtotime($this->session->data['last_login']));
		}
		
		$student_packages = array();
		$this->load->model('cms/invoices');
		$student_packages = $this->model_cms_invoices->getStudentPackages($this->session->data['user_id']);
		$this->data['active_packages'] = $student_packages;
		
		$packages = $student_packages;
		$hours_left = 0;
		foreach($packages as $package)
		{
			$hours_left = $hours_left+(float)$package['left_hours'];
		}
		
		$invoice_date = $this->model_cms_invoices->getLastInvoiceDate();
		$this->data['text_hours_message'] = str_replace('%%date%%',$invoice_date,str_replace("%%hours%%",$hours_left,$this->language->get('text_hours_message')));
//		print_r($student_packages);
		
		$this->load->model('student/report_cards');
		$this->data['total_report_card'] = $this->model_student_report_cards->getTotalReports();
		
		$this->load->model('cms/notifications');
//		$this->data['total_notifications'] = $this->model_cms_notifications->getTotalInformations();
		
		$this->load->model('tutor/sessions');
		$this->data['total_sessions'] = $this->model_tutor_sessions->getTotalSessions();
		
		$this->load->model('tutor/assignment');
		$this->data['total_tutors'] = $this->model_tutor_assignment->getTotalTutors();
		
		$users_array = array();
		$users_array[] = $this->session->data['user_id'];
		
		$this->data['notifications'] = array(); 
		$data = array('filter_to_users' => $users_array, 'sort'  => 'date_send','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_cms_notifications->getInformations($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER.'index.php?route=cms/notifications/view&token='.$this->session->data['token'].'&notification_id='.$result['notification_id']
			);	
			$this->data['notifications'][] = array(
				'group_name'          => trim($result['group_name']),
				'notification_from'          => trim($result['notification_from']),
				'notification_id'          => $result['notification_id'],
				'subject'          => $result['subject'],
				'date_send'     => date($this->language->get('date_format_short'), strtotime($result['date_send'])),
				'action'     => $action
			);
		}
		
		/*
		$this->data['reportcards'] = array(); 
		$data = array('sort'  => 'date_added','order' => 'DESC','start' => 0,'limit' => 10);
	    $results = $this->model_student_report_cards->getReports($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER . 'index.php?route=student/report_cards/view&token=' . $this->session->data['token'] . '&progress_reports_id=' . $result['progress_reports_id']
			);
			$this->data['reportcards'][] = array(
				'progress_reports_id'    => $result['progress_reports_id'],
				'tutor_name'    => $result['tutor_name'],
				'date_added'           => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'grade'          => $result['grade'],
				'subjects'     => $result['subjects'],
				'action'     => $action
			);
		}
		*/
		
		$this->load->model('student/invoice');
		$this->data['myinvoices'] = array(); 
		$data = array('filter_invoice_status' => 'Reminder Sent','sort'  => 'date_added','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_student_invoice->getInvoices($data);	    
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view_details'),
				'href' => HTTPS_SERVER . 'index.php?route=student/invoice/view&token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url
			);
						
			$this->data['myinvoices'][] = array(
				'invoice_id' => $result['invoice_id'],
				'invoice_num'      => $result['invoice_prefix']."-".$result['invoice_num'],
				'total_hours' => $result['total_hours'],
				'total_amount' => $result['total_amount'],
				'send_date' => date($this->language->get('date_format_short'), strtotime($result['send_date'])),
				'invoice_date' => date($this->language->get('date_format_short'), strtotime($result['invoice_date'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['invoice_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}
		
		
		$this->data['mytutors'] = array(); 
		$data = array('sort'  => 'date_added','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_tutor_assignment->getTutors($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view_details'),
				'href' => HTTPS_SERVER . 'index.php?route=student/mytutors/viewdetails&token=' . $this->session->data['token'] . '&tutors_to_students_id=' . $result['tutors_to_students_id']
			);	
			$this->data['mytutors'][] = array(
				'status'    =>  $result['status_by_student'],
				'tutor_name'          => $result['tutor_name'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'     => $action
			);
		}
		
	}
	
	public function tutor(){
    	$this->load->language('common/home_tutor');
		$this->document->title = $this->language->get('heading_title');
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_latest_5_students'] = $this->language->get('text_latest_5_students');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_last_login'] = $this->language->get('text_last_login');
		$this->data['text_total_report_card'] = $this->language->get('text_total_report_card');
		$this->data['text_total_sessions'] = $this->language->get('text_total_sessions');
		$this->data['text_total_students'] = $this->language->get('text_total_students');
		$this->data['text_latest_notifications'] = $this->language->get('text_latest_notifications');
		$this->data['text_view_all'] = $this->language->get('text_view_all');
		$this->data['text_total_notifications'] = $this->language->get('text_total_notifications');

		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_session_date'] = $this->language->get('column_session_date');
		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
		
		$this->data['column_notification_from'] = $this->language->get('column_notification_from');
		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_date_send'] = $this->language->get('column_date_send');

		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		
		$this->data['column_action'] = $this->language->get('column_action');
		
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

		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_warning'] = $this->language->get('error_warning');
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
		
		$this->data['token'] = $this->session->data['token'];
		
		if($this->session->data['last_login'] == "" || $this->session->data['last_login'] == "0000-00-00 00:00:00"){
			$this->data['last_login'] = "Never";
		}else{
			$this->data['last_login'] = date($this->language->get('date_format_long').' '.$this->language->get('time_format'), strtotime($this->session->data['last_login']));
		}
		
		$this->load->model('tutor/report_cards');
		$this->data['total_report_card'] = $this->model_tutor_report_cards->getTotalReports();
		
		$this->load->model('cms/notifications');
		$this->data['total_notifications'] = $this->model_cms_notifications->getTotalInformations();
		
		$this->load->model('tutor/sessions');
		$this->data['total_sessions'] = $this->model_tutor_sessions->getTotalSessions();
		
		$this->load->model('tutor/assignment');
		$this->data['total_students'] = $this->model_tutor_assignment->getTotalStudents();
		
		$users_array = array();
		$users_array[] = $this->session->data['user_id'];
		
		$this->data['notifications'] = array(); 
		$data = array('filter_to_users' => $users_array, 'sort'  => 'date_send','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_cms_notifications->getInformations($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER.'index.php?route=cms/notifications/view&token='.$this->session->data['token'].'&notification_id='.$result['notification_id']
			);	
			$this->data['notifications'][] = array(
				'group_name'          => trim($result['group_name']),
				'notification_from'          => trim($result['notification_from']),
				'notification_id'          => $result['notification_id'],
				'subject'          => $result['subject'],
				'date_send'     => date($this->language->get('date_format_short'), strtotime($result['date_send'])),
				'action'     => $action
			);
		}
		
		$this->data['sessions'] = array(); 
		$data = array('sort'  => 'session_date','order' => 'DESC','start' => 0,'limit' => 10);
	    $results = $this->model_tutor_sessions->getSessions($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=tutor/sessions/update&token=' . $this->session->data['token'] . '&session_id=' . $result['session_id']
			);
			$this->data['sessions'][] = array(
				'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'student_name'    => $result['student_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'session_duration'          => $result['session_duration'],
				'session_notes'     => $result['session_notes'],
				'action'     => $action
			);
		}
		
		$url = "";		
		$this->data['mystudents'] = array(); 
		$data = array('sort'  => 'date_added','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_tutor_assignment->getStudents($data);
    	foreach ($results as $result) {
			$action = array();
			$subjects = $this->model_tutor_assignment->getAssignedSubjects($result['tutors_to_students_id']);
			$action[] = array(
				'text' => "View Student Info",
				'href' => HTTPS_SERVER . 'index.php?route=tutor/mystudents/viewdetails&token=' . $this->session->data['token'] . '&tutors_to_students_id=' . $result['tutors_to_students_id']
			);
			
			if($result['status_by_tutor'] == 'Active') {
				$action[] = array(
					'text' => $this->language->get('button_stop_tutoring'),
					'href' => HTTPS_SERVER . 'index.php?route=tutor/mystudents/changeStatus&token=' . $this->session->data['token'] . '&tutors_status_id=2&tutors_to_students_id=' . $result['tutors_to_students_id'] . $url
				);				
			} else {				
				$action[] = array(
					'text' => $this->language->get('button_start_tutoring'),
					'href' => HTTPS_SERVER . 'index.php?route=tutor/mystudents/changeStatus&token=' . $this->session->data['token'] . '&tutors_status_id=1&tutors_to_students_id=' . $result['tutors_to_students_id'] . $url
				);				
			}
				
			$this->data['mystudents'][] = array(
				'subjects'    => $subjects,
				'student_name'          => $result['student_name'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'     => $action
			);
		}
		
	}
	
	public function administrator(){
    	$this->load->language('common/home');
		$this->document->title = $this->language->get('heading_title');
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_latest_5_assignments'] = $this->language->get('text_latest_5_assignments');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_latest_10_sessions'] = $this->language->get('text_latest_10_sessions');
		$this->data['text_last_login'] = $this->language->get('text_last_login');
		$this->data['text_total_order'] = $this->language->get('text_total_order');
		$this->data['text_total_report_card'] = $this->language->get('text_total_report_card');
		$this->data['text_total_sessions'] = $this->language->get('text_total_sessions');
		$this->data['text_received_class'] = $this->language->get('text_received_class');
		
		$this->data['text_total_tutors_curryear'] = $this->language->get('text_total_tutors_curryear');
		$this->data['text_total_students_curryear'] = $this->language->get('text_total_students_curryear');
		$this->data['text_received_class_curryear'] = $this->language->get('text_received_class_curryear');
		
		$this->data['text_total_students'] = $this->language->get('text_total_students');
		$this->data['text_total_tutors'] = $this->language->get('text_total_tutors');
		$this->data['text_total_users'] = $this->language->get('text_total_users');
		$this->data['text_latest_user_registrations'] = $this->language->get('text_latest_user_registrations');
		$this->data['text_latest_notifications'] = $this->language->get('text_latest_notifications');
		$this->data['text_view_all'] = $this->language->get('text_view_all');
		
		$this->data['column_notification_from'] = $this->language->get('column_notification_from');
		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_date_send'] = $this->language->get('column_date_send');
		
		$this->data['column_user_name'] = $this->language->get('column_user_name');
		$this->data['column_user_group'] = $this->language->get('column_user_group');
		$this->data['column_date_registration'] = $this->language->get('column_date_registration');

		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_session_date'] = $this->language->get('column_session_date');
		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
		
		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_method'] = $this->language->get('column_method');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_placed'] = $this->language->get('column_date_placed');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		
		$this->data['column_action'] = $this->language->get('column_action');

		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_warning'] = $this->language->get('error_warning');
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
		
		$this->data['token'] = $this->session->data['token'];
		
		if($this->session->data['last_login'] == "" || $this->session->data['last_login'] == "0000-00-00 00:00:00"){
			$this->data['last_login'] = "Never";
		}else{
			$this->data['last_login'] = date($this->language->get('date_format_long').' '.$this->language->get('time_format'), strtotime($this->session->data['last_login']));
		}
		
		$this->load->model('user/report_cards');
		$this->data['total_report_card'] = $this->model_user_report_cards->getTotalReports();
		
		$this->load->model('cms/notifications');
		$this->data['total_notifications'] = $this->model_cms_notifications->getTotalInformations();
		
		$this->load->model('user/sessions');
//		$this->data['total_sessions'] = $this->model_user_sessions->getTotalSessions();
		
		$this->load->model('tutor/assignment');
		$this->data['total_assignments'] = $this->model_tutor_assignment->getTotalAssignments();
		
		$this->load->model('user/user');
		$this->data['total_users'] = $this->model_user_user->getTotalUsers();
		
		$this->load->model('sale/order');
		$this->data['total_order'] = $this->model_sale_order->getTotalOrders();
		
		$this->load->model('user/tutors');
		$this->load->model('user/students');
		$this->load->model('user/user_group');
		$this->data['total_students'] = $this->model_user_students->getTotalStudents();
		
		$received_class = $this->model_user_sessions->getTotalStudentsWhoReceivedClass();
		$this->data['total_received_class'] = round($received_class * 100 / $this->data['total_students'], 2);
		
		
		// for the current year
		$curr_timestamp = strtotime("now");
		$check_timestamp = strtotime("1 sep");
		
		if($curr_timestamp > $check_timestamp) {
			$date_arr = array (
				'start_date' => date("Y-m-d", strtotime("September 1")),
				'end_date' => date("Y-m-d", strtotime("August 31 +1 year")),
			);
		} else {
			$date_arr = array (
				'start_date' => date("Y-m-d", strtotime("September 1 -1 year")),
				'end_date' => date("Y-m-d", strtotime("August 31")),
			);
		}
		
//		$this->data['text_received_class_curryear'] = sprintf($this->language->get('text_received_class_curryear'), $date_arr['start_date']." to ".$date_arr['end_date']);
		$received_class = $this->model_user_sessions->getTotalStudentsWhoReceivedClass($date_arr);
		$this->data['total_received_class_curryear'] = round($received_class * 100 / $this->data['total_students'], 2);
		
		$this->data['total_tutors'] = $this->model_user_tutors->getTotalTutors();
		
		$this->data['total_students_curryear'] = $this->model_user_students->getTotalStudentsCurrentYear($date_arr);
		$this->data['total_tutors_curryear'] = $this->model_user_tutors->getTotalTutorsCurrentYear($date_arr);
		
		$users_array = array();
		$users_array[] = 1;
		$users_array[] = $this->session->data['user_id']; 
		
		$this->data['notifications'] = array(); 
		$data = array('filter_to_users' => $users_array, 'sort'  => 'date_send','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_cms_notifications->getInformations($data);
		
		$combined_results = $this->model_cms_notifications->getHomepageInformations();
		
		foreach ($combined_results as $result) {
			$action = array();
			
			if($result['type'] == 'notification')
			{			
				$action[] = array(
					'text' => $this->language->get('text_view'),
					'href' => HTTPS_SERVER.'index.php?route=cms/notifications/view&token='.$this->session->data['token'].'&notification_id='.$result['id']
				);	
				
				$this->data['combined_data'][] = array(
					'type' => 'notification',
					'group_name'          => trim($result['group_name']),
					'notification_from'          => trim($result['name']),
					'notification_id'          => $result['id'],
					'subject'          => ($result['subject'] == "Tutoring status changed"
											&& $result['group_name'] == 'Student') ? $result['message'] : $result['subject'],
					'date_send'     => date($this->language->get('date_format_short'), strtotime($result['date_considered'])),
					'action'     => $action
				);
				
			}
			else
			{
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => HTTPS_SERVER.'index.php?route=user/students/update&token='.$this->session->data['token'].'&user_id='.$result['id']
				);
				
				$this->data['combined_data'][] = array(
					'type' => 'registration',
					'name'          => trim($result['name']),
					'user_group'          => $user_group['group_name'],
					'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_considered'])),
					'action'     => $action
				);		
				
			}
			
			
		}
		
		
		//print_r($this->data['combined_data']);
		
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER.'index.php?route=cms/notifications/view&token='.$this->session->data['token'].'&notification_id='.$result['notification_id']
			);	
			$this->data['notifications'][] = array(
				'group_name'          => trim($result['group_name']),
				'notification_from'          => trim($result['notification_from']),
				'notification_id'          => $result['notification_id'],
				'subject'          => ($result['subject'] == "Tutoring status changed"
										&& $result['group_name'] == 'Student') ? $result['message'] : $result['subject'],
				'date_send'     => date($this->language->get('date_format_short'), strtotime($result['date_send'])),
				'action'     => $action
			);
		}
		
		$this->data['users'] = array(); 
		$data = array('sort'  => 'date_added','order' => 'DESC','start' => 0,'limit' => 10);
	    $results = $this->model_user_students->getStudents($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER.'index.php?route=user/students/update&token='.$this->session->data['token'].'&user_id='.$result['user_id']
			);	
			$user_group = $this->model_user_user_group->getUserGroup($result['user_group_id']);
			$this->data['users'][] = array(
				'name'          => trim($result['firstname'].' '.$result['lastname']),
				'user_group'          => $user_group['name'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'     => $action
			);
		}
		
		/*$this->data['sessions'] = array(); 
		$data = array('sort'  => 'session_date','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_user_sessions->getSessions($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/sessions/update&token=' . $this->session->data['token'] . '&session_id=' . $result['session_id']
			);
			$this->data['sessions'][] = array(
				'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'student_name'    => $result['student_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'session_duration'          => $result['session_duration'],
				'session_notes'     => $result['session_notes'],
				'action'     => $action
			);
		}*/
		
		$this->data['mystudents'] = array(); 
		
		$data = array('sort'  => 'date_added','order' => 'DESC','start' => 0,'limit' => 5);
	    $results = $this->model_tutor_assignment->getAssignments($data);
    	foreach ($results as $result) {
			$action = array();
			$subjects = $this->model_tutor_assignment->getAssignedSubjects($result['tutors_to_students_id']);
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=tutor/assignment/update&token=' . $this->session->data['token'] . '&tutors_to_students_id=' . $result['tutors_to_students_id']
			);
			$this->data['mystudents'][] = array(
				'tutor_name'          => $result['tutor_name'],
				'student_name'          => $result['student_name'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'     => $action
			);
		}
		
		$this->data['orders'] = array(); 
		$data = array('sort'  => 'o.date_added','order' => 'DESC','start' => 0,'limit' => 10);
		$results = $this->model_sale_order->getOrders($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $result['order_id']
			);	
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'type'       => (!empty($result['invoice_pk'])) ? 'Invoice':'Package',
				'method'       => $result['payment_method'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'action'     => $action
			);
		}
		
	}
	
	public function changeStudentStatus() {
		
		if(isset($this->request->get['status'])) {
			
			$this->load->language('common/home_student');
			
			$this->load->model('student/profile');
			
			$status_id = $this->request->get['status'];
			
			$data = array(
				'students_status_id' => $status_id 
			);
			
			$student_status = array(
				'1' => $this->language->get('text_need_tutoring'),
				'2' => $this->language->get('text_nomore_tutoring'),
				'3' => $this->language->get('text_change_tutor'),
				'4' => $this->language->get('text_new_tutoring'),
			);
			
			$this->session->data['success'] = sprintf($this->language->get('text_status_change'), $student_status[$status_id]);
			
			$this->load->model('cms/notifications');
			$message = "Student changed tutoring status to <b>$student_status[$status_id]</b>";
			$notification = array(
				'notification_from' => $this->session->data['user_id'],
				'notification_to' => '1',
				'subject'=> 'Tutoring status changed',
				'message'=> $message 
			);
			
			$this->model_cms_notifications->addInformation($notification);
		
			log_activity("Status Changed", "Student changed his/her Status to (".$student_status[$status_id].").", $this->session->data['user_id'], "1");
			
			$this->model_student_profile->changeStudentStatus($this->session->data['user_id'], $data);
			
		}
		
		$this->redirect(HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token']);
	}
	
	public function login() { 
		if (!$this->user->isLogged()) {
			return $this->forward('common/login');
		}
		
		if (isset($this->request->get['route'])) {
			$route = '';
						
			$part = explode('/', $this->request->get['route']);
			
			if (isset($part[0])) {
				$route .= $part[0];
			}
			
			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
			
			$ignore = array(
				'common/login',
				'common/logout',
				'error/not_found',
				'error/permission'
			);
			
			$config_ignore = array();
			
			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}
				
			$ignore = array_merge($ignore, $config_ignore);
						
			if (!in_array($route, $ignore)) {
				if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
					return $this->forward('common/login');
				}
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return $this->forward('common/login');
			}
		}
	}
	
	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';
			
			$part = explode('/', $this->request->get['route']);
			
			if (isset($part[0])) {
				$route .= $part[0];
			}
			
			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
			
			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'error/not_found',
				'error/permission',	
				'error/token'		
			);			
						
			if (!in_array($route, $ignore)) {
				if (!$this->user->hasPermission('access', $route)) {
					return $this->forward('error/permission');
				}
			}
		}
	}
}
?>