<?php    
class ControllerStudentAssignment extends Controller { 
	private $error = array();
	
	public function getStudentRate() {
		$this->load->model('student/assignment');
		echo $this->model_student_assignment->getStudentRate($this->request->get['user_id']);
		exit;
	}
  
  	public function index() {
		$this->load->language('student/assignment');
		$this->document->title = $this->language->get('heading_title_student');
		$this->load->model('tutor/assignment');
    	$this->getList();
  	}
  
  	public function insert() {
		$this->load->language('student/assignment');
    	$this->document->title = $this->language->get('heading_title_student');
		$this->load->model('tutor/assignment');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			if($this->request->post['active']=="1"){
				$this->load->model('cms/notifications');
				$subject1 = "Tutor assigned";
				$message1 = "You have been assigned a tutor for tutoring.";
				
				$subject2 = "Student assigned";
				$message2 = "You have been assigned a student for tutoring.";
	
				$notification = array(
					'notification_from'=>$this->session->data['user_id'],
					'notification_to'=>$this->request->post['students_id'],
					'subject'=>$subject1,
					'message'=>$message1
				);
				$this->model_cms_notifications->addInformation($notification);
				$notification = array(
					'notification_from'=>$this->session->data['user_id'],
					'notification_to'=>$this->request->post['tutors_id'],
					'subject'=>$subject2,
					'message'=>$message2
				);
				$this->model_cms_notifications->addInformation($notification);
			}
				
      	  	$this->model_tutor_assignment->addAssignment($this->request->post);
			log_activity("Student Assigned", "A student is assigned to a tutor.");
			$this->session->data['success'] = $this->language->get('text_success');
		  
			/* Softronikx Technologies - Code to send Email notification to Tutor and Student */
			
			$this->load->model('user/user');
			$student = $this->model_user_user->getUser($this->request->post['students_id']);
			$tutor_details = $this->model_user_user->getUser($this->request->post['tutors_id']);
			
			//Email Tutor
			$this->load->model('account/student');
			$tutor_mail = $this->model_account_student->getMailFormat('4');
			
			$subject = $tutor_mail['broadcasts_subject'];
			$message = $tutor_mail['broadcasts_content'];
			
			// Here you can define keys for replace before sending mail to Student
			$replace_info = array(
							'TUTOR_NAME' => $tutor_details['firstname'].' '.$tutor_details['lastname'], 
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
			$mail->setTo($tutor_details['email']);
	  		$mail->setFrom("tutoring@LearnOn.ca");
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

			
			// Email Student
			
			$student_mail = $this->model_account_student->getMailFormat('5');
			
			$subject = $student_mail['broadcasts_subject'];
			$message = $student_mail['broadcasts_content'];
			
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
	  	  
			/* End of code by Softronikx Technologies */
			
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url);
		}
    	
    	$this->getForm("heading_title_insert");
  	} 
   
  	public function update() {
		$this->load->language('student/assignment');
    	$this->document->title = $this->language->get('heading_title_student');
		$this->load->model('tutor/assignment');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			if($this->request->post['previous_status']!=$this->request->post['active']){
				$this->load->model('cms/notifications');
				if($this->request->post['previous_status']=="0"){
					$subject = "Tutoring started";
					$message = "You have been re-assigned for tutoring.";
				}else{
					$subject = "Tutoring cancelled";
					$message = "Your assignment has been cancelled for tutoring.";
				}
				$notification = array(
					'notification_from'=>$this->session->data['user_id'],
					'notification_to'=>$this->request->post['students_id'],
					'subject'=>$subject,
					'message'=>$message
				);
				$this->model_cms_notifications->addInformation($notification);
				$notification = array(
					'notification_from'=>$this->session->data['user_id'],
					'notification_to'=>$this->request->post['tutors_id'],
					'subject'=>$subject,
					'message'=>$message
				);
				$this->model_cms_notifications->addInformation($notification);
			}
		
			$this->model_tutor_assignment->editAssignment($this->request->get['tutors_to_students_id'], $this->request->post);
	  		log_activity("Assignment Updated", "Student assignment details updated.");
			$this->session->data['success'] = $this->language->get('text_success');
	  
			/* Softronikx Technologies - Code to send Email notification to Tutor and Student */
			
			$this->load->model('user/user');
			$student = $this->model_user_user->getUser($this->request->post['students_id']);
			$tutor_details = $this->model_user_user->getUser($this->request->post['tutors_id']);
			
			//Email Tutor
			
			$this->load->model('account/student');
			$tutor_mail = $this->model_account_student->getMailFormat('4');
			
			$subject = $tutor_mail['broadcasts_subject'];
			$message = $tutor_mail['broadcasts_content'];
			
			// Here you can define keys for replace before sending mail to Student
			$replace_info = array(
							'TUTOR_NAME' => $tutor_details['firstname'].' '.$tutor_details['lastname'], 
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
			$mail->setTo($tutor_details['email']);
	  		$mail->setFrom("tutoring@LearnOn.ca");
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

			
			// Email Student
			
			$student_mail = $this->model_account_student->getMailFormat('6');
			
			$subject = $student_mail['broadcasts_subject'];
			$message = $student_mail['broadcasts_content'];
			
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
	  	  
			/* End of code by Softronikx Technologies */
	  
	  
	  
	  
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm("heading_title_update");
  	}   

  	public function delete() {
		$this->load->language('student/assignment');

    	$this->document->title = $this->language->get('heading_title_student');
		
		$this->load->model('tutor/assignment');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tutors_to_students_id) {
				$this->model_tutor_assignment->deleteAssignment($tutors_to_students_id);
			}
			log_activity("Assignment Deleted", "Student assignment deleted.");
			$this->session->data['success'] = $this->language->get('text_success');

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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url);
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_student'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=student/assignment/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=student/assignment/delete&token=' . $this->session->data['token'] . $url;

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
		
		$assignment_total = $this->model_tutor_assignment->getTotalAssignments($data);
		$results = $this->model_tutor_assignment->getAssignments($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=student/assignment/update&token=' . $this->session->data['token'] . '&tutors_to_students_id=' . $result['tutors_to_students_id'] . $url
			);
			$subjects = $this->model_tutor_assignment->getAssignedSubjects($result['tutors_to_students_id']);
			$this->data['assignments'][] = array(
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'subjects'    => $subjects,
				'tutor_name'           => $result['tutor_name'],
				'student_name'          => $result['student_name'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title_student');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
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

		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . '&sort=date_added' . $url;


		$pagination = new Pagination();
		$pagination->total = $assignment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_student_name'] = $filter_student_name;
		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_date_added'] = $filter_date_added;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'student/assignments.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	private function getForm($heading_title) {
    	$this->data['heading_title'] = $this->language->get($heading_title);
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
    	
		$this->data['entry_tutor_name'] = $this->language->get('entry_tutor_name');
    	$this->data['entry_student_name'] = $this->language->get('entry_student_name');
    	$this->data['entry_wage'] = $this->language->get('entry_wage');
		$this->data['entry_invoice'] = $this->language->get('entry_invoice');
    	$this->data['entry_subjects'] = $this->language->get('entry_subjects');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_status_by_tutor'] = $this->language->get('entry_status_by_tutor');
		$this->data['entry_status_by_student'] = $this->language->get('entry_status_by_student');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add'] = $this->language->get('button_add');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['tutors_id'])) {
			$this->data['error_tutor_name'] = $this->error['tutors_id'];
		} else {
			$this->data['error_tutor_name'] = '';
		}

 		if (isset($this->error['students_id'])) {
			$this->data['error_student_name'] = $this->error['students_id'];
		} else {
			$this->data['error_student_name'] = '';
		}
		
 		if (isset($this->error['base_wage'])) {
			$this->data['error_wage'] = $this->error['base_wage'];
		} else {
			$this->data['error_wage'] = '';
		}
		
 		if (isset($this->error['base_invoice'])) {
			$this->data['error_invoice'] = $this->error['base_invoice'];
		} else {
			$this->data['error_invoice'] = '';
		}

		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_student'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['tutors_to_students_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/assignment/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/assignment/update&token=' . $this->session->data['token'] . '&tutors_to_students_id=' . $this->request->get['tutors_to_students_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'] . $url;
		$tutors_to_students_id = "";
    	if (isset($this->request->get['tutors_to_students_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tutors_to_students_id = $this->request->get['tutors_to_students_id'];
      		$assignment_info = $this->model_tutor_assignment->getAssignment($tutors_to_students_id);
    	}
			
    	if (isset($this->request->post['tutors_id'])) {
      		$this->data['tutors_id'] = $this->request->post['tutors_id'];
		} elseif (isset($assignment_info)) { 
			$this->data['tutors_id'] = $assignment_info['tutors_id'];
		} else {
      		$this->data['tutors_id'] = '';
    	}

    	if (isset($this->request->post['students_id'])) {
      		$this->data['students_id'] = $this->request->post['students_id'];
		} elseif (isset($assignment_info)) { 
			$this->data['students_id'] = $assignment_info['students_id'];
		} else {
      		$this->data['students_id'] = '';
    	}

		$this->load->model('cms/settings');
		$wages = $this->model_cms_settings->getSetting();	

    	if (isset($this->request->post['base_wage'])) {
      		$this->data['base_wage'] = $this->request->post['base_wage'];
    	} elseif (isset($assignment_info)) { 
			$this->data['base_wage'] = $assignment_info['base_wage'];
		} else {
      		$this->data['base_wage'] = $wages['wage_usa'];
    	}
		
    	if (isset($this->request->post['base_invoice'])) {
      		$this->data['base_invoice'] = $this->request->post['base_invoice'];
    	} elseif (isset($assignment_info)) { 
			$this->data['base_invoice'] = $assignment_info['base_invoice'];
		} else {
      		$this->data['base_invoice'] = $wages['invoice_usa'];
    	}
		
    	if (isset($this->request->post['active'])) {
      		$this->data['active'] = $this->request->post['active'];
    	} elseif (isset($assignment_info)) { 
			$this->data['active'] = $assignment_info['active'];
		} else {
      		$this->data['active'] = '';
    	}
		
    	if (isset($this->request->post['previous_status'])) {
      		$this->data['previous_status'] = $this->request->post['previous_status'];
    	} elseif (isset($assignment_info)) { 
			$this->data['previous_status'] = $assignment_info['active'];
		} else {
      		$this->data['previous_status'] = '';
    	}
		
    	if (isset($this->request->post['status_by_tutor'])) {
      		$this->data['status_by_tutor'] = $this->request->post['status_by_tutor'];
    	} elseif (isset($assignment_info)) { 
			$this->data['status_by_tutor'] = $assignment_info['status_by_tutor'];
		} else {
      		$this->data['status_by_tutor'] = '';
    	}
		
    	if (isset($this->request->post['status_by_student'])) {
      		$this->data['status_by_student'] = $this->request->post['status_by_student'];
    	} elseif (isset($assignment_info)) { 
			$this->data['status_by_student'] = $assignment_info['status_by_student'];
		} else {
      		$this->data['status_by_student'] = '';
    	}
		
		$this->load->model('tutor/profile');
		// get all subjects
		$all_subjects = $this->model_tutor_profile->getAllSubjects();
		$this->data['all_subjects'] = $all_subjects;
		$arrsubids = $this->model_tutor_assignment->getAssignedSubjects($tutors_to_students_id);
		$all_subject_ids = array();
		foreach($arrsubids as $subid){
			$all_subject_ids[] = $subid['subjects_id'];
		}
		$this->data['all_subject_ids'] = $all_subject_ids;

		$this->load->model('user/tutors');
		$this->data['all_tutors'] = $this->model_user_tutors->getAllTutors(array('sort'=>'name', 'filter_approved' => 1, 'filter_status' => 1));
		
		$this->load->model('user/students');
		$this->data['all_students'] = $this->model_user_students->getAllStudents(array('sort'=>'name', 'filter_approved' => 1));
		$this->template = 'student/assignment_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'tutor/assignment')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
    	if ($this->request->post['tutors_id']=="") {
      		$this->error['tutors_id'] = $this->language->get('error_tutor_name');
    	}

    	if ($this->request->post['students_id']=="") {
      		$this->error['students_id'] = $this->language->get('error_student_name');
    	}
		
    	if(!ereg('^[0-9]+\.[0-9]{2}$', $this->request->post['base_wage'])){
      		$this->error['base_wage'] = $this->language->get('error_wage');
    	}
		
    	if(!ereg('^[0-9]+\.[0-9]{2}$', $this->request->post['base_invoice'])){
      		$this->error['base_invoice'] = $this->language->get('error_invoice');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'tutor/assignment')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	} 	

}
?>
