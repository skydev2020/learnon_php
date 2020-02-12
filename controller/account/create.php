<?php 
class ControllerAccountCreate extends Controller {
	private $error = array();
	      
  	public function student() {
		
    	$this->language->load('account/student');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('account/student');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateStudent()) {
    		
    		
//    		if(!empty())
    		
    		$this->request->post['email'] = $this->request->post['username'];
    		
    		$this->request->post['students_status_id'] = 1;
    		$this->request->post['status'] = 1;
    		$this->request->post['approved'] = 1;
    		
    		// Student Agreement
			$information_info = $this->model_account_student->getStudentAgreement();
			$this->request->post['student_agrement'] = $information_info['description'];
    		
			$user_id = $this->model_account_student->addStudent($this->request->post);
			
			log_activity("Student Registration", "A new student registered.", $user_id, "1");
			
			// Set the mail format which needs to send
			$student_mail = $this->model_account_student->getMailFormat('1');

			/*print_r($this->request->post);
			die;*/
			
			// Here can logged IN Student
			// $this->customer->login($this->request->post['email'], $this->request->post['password']);
						
			$subject = $student_mail['broadcasts_subject'];
			$message = $student_mail['broadcasts_content'];
			
			// Here you can define keys for replace before sending mail to Student
			$replace_info = array(
							'STUDENT_NAME' => $this->request->post['firstname'].' '.$this->request->post['lastname'], 
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
			$mail->setTo($this->request->post['email']);
	  		$mail->setFrom($this->config->get('config_email'));
	  		$mail->setSender($this->config->get('config_name'));
	  		$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
      		$mail->send();
			
			/*
			// Send to main admin email if account email is enabled
			if ($this->config->get('config_account_mail')) {
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
			}
			*/
			
			// Send to additional alert emails if account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match(EMAIL_PATTERN, $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
	  	  
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/success/student');
    	} 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/create/student',
        	'text'      => $this->language->get('text_create'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
    	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), HTTPS_SERVER . 'index.php?route=common/home');
    	$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		
		$this->data['entry_username'] = $this->language->get('entry_email'); //entry_username
		$this->data['entry_parent'] = $this->language->get('entry_parent');
    	$this->data['entry_parent_firstname'] = $this->language->get('entry_parent_firstname');
    	$this->data['entry_parent_lastname'] = $this->language->get('entry_parent_lastname');
    	$this->data['entry_grade_year'] = $this->language->get('entry_grade_year');
    	$this->data['entry_subjects'] = $this->language->get('entry_subjects');
    	$this->data['entry_home_phone'] = $this->language->get('entry_home_phone');
    	$this->data['entry_cell_phone'] = $this->language->get('entry_cell_phone');
    	
    	$this->data['entry_major_intersection'] = $this->language->get('entry_major_intersection');
    	$this->data['entry_school_name'] = $this->language->get('entry_school_name');
    	$this->data['entry_heard_aboutus'] = $this->language->get('entry_heard_aboutus');
    	$this->data['entry_heard_aboutus_other'] = $this->language->get('entry_heard_aboutus_other');
    	$this->data['entry_student_note'] = $this->language->get('entry_student_note');
    	    	
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_cellphone'] = $this->language->get('entry_cellphone');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_state'] = $this->language->get('entry_state');
		$this->data['entry_country'] = $this->language->get('entry_country');
		
		$this->data['entry_header'] = $this->language->get('entry_header');
		$this->data['entry_info'] = $this->language->get('entry_info');
		
		$this->data['button_save'] = $this->language->get('button_register');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['button_continue'] = $this->language->get('button_continue');
    
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

 		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
		if (isset($this->error['parent'])) {
			$this->data['error_parent'] = $this->error['parent'];
		} else {
			$this->data['error_parent'] = '';
		}
		
 		if (isset($this->error['grade_year'])) {
			$this->data['error_grade_year'] = $this->error['grade_year'];
		} else {
			$this->data['error_grade_year'] = '';
		}
 		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['parent_firstname'])) {
			$this->data['error_parent_firstname'] = $this->error['parent_firstname'];
		} else {
			$this->data['error_parent_firstname'] = '';
		}
		
		if (isset($this->error['parent_lastname'])) {
			$this->data['error_parent_lastname'] = $this->error['parent_lastname'];
		} else {
			$this->data['error_parent_lastname'] = '';
		}
 		
 		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
		if (isset($this->error['cellphone'])) {
			$this->data['error_cellphone'] = $this->error['cellphone'];
		} else {
			$this->data['error_cellphone'] = '';
		}
		
		if (isset($this->error['address'])) {
			$this->data['error_address'] = $this->error['address'];
		} else {
			$this->data['error_address'] = '';
		}
		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}
		
		if (isset($this->error['state'])) {
			$this->data['error_state'] = $this->error['state'];
		} else {
			$this->data['error_state'] = '';
		}
		
		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}
		
		if (isset($this->error['student_note'])) {
			$this->data['error_student_note'] = $this->error['student_note'];
		} else {
			$this->data['error_student_note'] = '';
		}
		
		if (isset($this->error['major_intersection'])) {
			$this->data['error_major_intersection'] = $this->error['major_intersection'];
		} else {
			$this->data['error_major_intersection'] = '';
		}
		
		if (isset($this->error['school_name'])) {
			$this->data['error_school_name'] = $this->error['school_name'];
		} else {
			$this->data['error_school_name'] = '';
		}
		
		if (isset($this->error['heard_aboutus'])) {
			$this->data['error_heard_aboutus'] = $this->error['heard_aboutus'];
		} else {
			$this->data['error_heard_aboutus'] = '';
		}
		
		if (isset($this->error['heard_aboutus_other'])) {
			$this->data['error_heard_aboutus_other'] = $this->error['heard_aboutus_other'];
		} else {
			$this->data['error_heard_aboutus_other'] = '';
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=common/home';
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=account/create/student';
		
		if (isset($this->request->post['grade_year'])) {
      		$default_grade_id = $this->request->post['grade_year'];
		} else {
			$default_grade_id = "1";
    	}
    			
		$all_subjects = $this->model_account_student->getSubjectsByGradeId($default_grade_id);
		
		if (isset($this->request->post['subjects'])) {
			if(count($this->request->post['subjects']) > 0)
				$all_subject_ids = $this->request->post['subjects'];
			else
      			$all_subject_ids = "";      		
		} else {
			$all_subject_ids = "";
    	}		
		
		if (isset($this->request->post['username'])) {
      		$this->data['username'] = $this->request->post['username'];
		} else {
      		$this->data['username'] = '';
    	}
    	
    	if (isset($this->request->post['password'])) { 
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) { 
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
    	
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = '';
    	}
    	
    	if (isset($this->request->post['parent'])) {
      		$this->data['parent'] = $this->request->post['parent'];
    	} else {
      		$this->data['parent'] = 'N';
    	}

		if (isset($this->request->post['grade_year'])) {
      		$this->data['grade_year'] = $this->request->post['grade_year'];
    	} else {
      		$this->data['grade_year'] = $default_grade_id;
    	}
    	
    	if (!empty($all_subject_ids)) { 
			$this->data['all_subject_ids'] = $all_subject_ids;
		} else {
      		$this->data['all_subject_ids'] = array();
    	}
    	
    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} else {
      		$this->data['email'] = '';
    	}
    	
    	if (isset($this->request->post['parent_firstname'])) {
      		$this->data['parent_firstname'] = $this->request->post['parent_firstname'];
    	} else {
      		$this->data['parent_firstname'] = '';
    	}
    	
    	if (isset($this->request->post['parent_lastname'])) {
      		$this->data['parent_lastname'] = $this->request->post['parent_lastname'];
    	} else {
      		$this->data['parent_lastname'] = '';
    	}
    	
    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if (isset($this->request->post['cellphone'])) {
      		$this->data['cellphone'] = $this->request->post['cellphone'];
		} else {
      		$this->data['cellphone'] = '';
    	}

    	if (isset($this->request->post['address'])) {
      		$this->data['address'] = $this->request->post['address'];
		} else {
      		$this->data['address'] = '';
    	}
    	
    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
		} else {
      		$this->data['city'] = '';
    	}
    	
    	if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
		} else {
      		$this->data['postcode'] = '';
    	}
    	
    	if (isset($this->request->post['state'])) {
      		$this->data['state'] = $this->request->post['state'];
		} else {
      		$this->data['state'] = '';
    	}
    	$all_states = $this->zone($this->data['state']);
		$this->data['list_states'] = $all_states;
		
    	if (isset($this->request->post['country'])) {
      		$this->data['country'] = $this->request->post['country'];
		} else {
      		$this->data['country'] = '';
    	}
    	$all_countries = $this->country($this->data['country']);
		$this->data['list_country'] = $all_countries;
		
    	if (isset($this->request->post['student_note'])) {
      		$this->data['student_note'] = $this->request->post['student_note'];
		} else {
      		$this->data['student_note'] = '';
    	}
    	
    	if (isset($this->request->post['major_intersection'])) {
      		$this->data['major_intersection'] = $this->request->post['major_intersection'];
		} else {
      		$this->data['major_intersection'] = '';
    	}
    	
    	if (isset($this->request->post['school_name'])) {
      		$this->data['school_name'] = $this->request->post['school_name'];
		} else {
      		$this->data['school_name'] = '';
    	}
    	
    	if (isset($this->request->post['heard_aboutus'])) {
      		$this->data['heard_aboutus'] = $this->request->post['heard_aboutus'];
		} else {
      		$this->data['heard_aboutus'] = '';
    	}
    	
    	if (isset($this->request->post['heard_aboutus_other'])) {
      		$this->data['heard_aboutus_other'] = $this->request->post['heard_aboutus_other'];
		} else {
      		$this->data['heard_aboutus_other'] = '';
    	}
		
    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
		} else {
      		$this->data['status'] = 1;
    	}
    			
		// get all grades
		$grade_years = $this->model_account_student->getGradesAndYears();
		$this->data['grade_years'] = $grade_years;		

		// get all subjects
		$this->data['all_subjects'] = $all_subjects;	
		
		
		// Student Agreement
		$information_info = $this->model_account_student->getStudentAgreement();
		$this->data['student_agrement'] = html_entity_decode($information_info['description']);
		$this->data['text_agree'] = $this->language->get('text_agree');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/create.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/create.tpl';
		} else {
			$this->template = 'account/student.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
  	}
	
  	public function tutor() {
    	$this->language->load('account/create');
		$this->document->title = $this->language->get('heading_title_tutor');
		$this->load->model('account/tutor');
		
		if(!isset($this->request->post['step'])){
			$this->request->post['step'] = "";
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateTutor() && ($this->request->post['step']=="3")) {
    		$this->request->post['status'] = 1;
    		$this->request->post['approved'] = 1; 
			
			$user_id = $this->model_account_tutor->addTutor($this->request->post);
			log_activity("Tutor Registration", "A new tutor registered.", $user_id, "2");
			// Set the mail format which needs to send
			$tutor_mail = $this->model_account_tutor->getMailFormat('3');

			/*print_r($this->request->post);die;*/
			
			// Here can logged IN Tutor
            //$this->customer->login($this->request->post['email'], $this->request->post['password']);
						
			$subject = $tutor_mail['broadcasts_subject'];
						
			$message = $tutor_mail['broadcasts_content'];
			
			// Here you can define keys for replace before sending mail to Tutor
			$replace_info = array(
							'TUTOR_NAME' => $this->request->post['firstname'].' '.$this->request->post['lastname'], 
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
			$mail->setTo($this->request->post['email']);
	  		$mail->setFrom('jobs@LearnOn.ca'); //$this->config->get('config_email2')
	  		$mail->setSender($this->config->get('config_name'));
	  		$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
      		$mail->send();
			
			/*
			// Send to main admin email if account email is enabled
			if ($this->config->get('config_account_mail')) {
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
			}
			*/
			
			// Send to additional alert emails if account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match(EMAIL_PATTERN, $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
	  	  
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/success');
    	} 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/create/tutor',
        	'text'      => $this->language->get('text_create'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title_tutor'] = $this->language->get('heading_title_tutor');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
    	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), HTTPS_SERVER . 'index.php?route=common/home');
    	$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
				
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_cellphone'] = $this->language->get('entry_cellphone');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_notes'] = $this->language->get('entry_notes');
		$this->data['entry_post_secondary_education'] = $this->language->get('entry_post_secondary_education');
		$this->data['entry_subjects_studied'] = $this->language->get('entry_subjects_studied');
		
		$this->data['entry_courses_available'] = $this->language->get('entry_courses_available');
		$this->data['entry_previous_experience'] = $this->language->get('entry_previous_experience');
		$this->data['entry_cities'] = $this->language->get('entry_cities');
		$this->data['entry_references'] = $this->language->get('entry_references');
		$this->data['entry_gender'] = $this->language->get('entry_gender');
		$this->data['entry_certified_teacher'] = $this->language->get('entry_certified_teacher');
		$this->data['entry_criminal_conviction'] = $this->language->get('entry_criminal_conviction');
		$this->data['entry_background_check'] = $this->language->get('entry_background_check');
		
		$this->data['entry_header'] = $this->language->get('entry_tutor_header');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

 		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
 		if (isset($this->error['home_phone'])) {
			$this->data['error_telephone'] = $this->error['home_phone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
 		if (isset($this->error['cell_phone'])) {
			$this->data['error_cellphone'] = $this->error['cell_phone'];
		} else {
			$this->data['error_cellphone'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
 		if (isset($this->error['address'])) {
			$this->data['error_address_1'] = $this->error['address'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
 		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
 		if (isset($this->error['state'])) {
			$this->data['error_zone'] = $this->error['state'];
		} else {
			$this->data['error_zone'] = '';
		}
		
 		if (isset($this->error['pcode'])) {
			$this->data['error_pcode'] = $this->error['pcode'];
		} else {
			$this->data['error_pcode'] = '';
		}		
		
 		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}
		
 		if (isset($this->error['users_note'])) {
			$this->data['error_notes'] = $this->error['users_note'];
		} else {
			$this->data['error_notes'] = '';
		}	
		
 		if (isset($this->error['post_secondary_education'])) {
			$this->data['error_post_secondary_education'] = $this->error['post_secondary_education'];
		} else {
			$this->data['error_post_secondary_education'] = '';
		}
		
 		if (isset($this->error['subjects_studied'])) {
			$this->data['error_subjects_studied'] = $this->error['subjects_studied'];
		} else {
			$this->data['error_subjects_studied'] = '';
		}
		
 		if (isset($this->error['courses_available'])) {
			$this->data['error_courses_available'] = $this->error['courses_available'];
		} else {
			$this->data['error_courses_available'] = '';
		}
		
 		if (isset($this->error['previous_experience'])) {
			$this->data['error_previous_experience'] = $this->error['previous_experience'];
		} else {
			$this->data['error_previous_experience'] = '';
		}
		
 		if (isset($this->error['cities'])) {
			$this->data['error_cities'] = $this->error['cities'];
		} else {
			$this->data['error_cities'] = '';
		}
		
 		if (isset($this->error['references'])) {
			$this->data['error_references'] = $this->error['references'];
		} else {
			$this->data['error_references'] = '';
		}
		
 		if (isset($this->error['gender'])) {
			$this->data['error_gender'] = $this->error['gender'];
		} else {
			$this->data['error_gender'] = '';
		}
		
 		if (isset($this->error['certified_teacher'])) {
			$this->data['error_certified_teacher'] = $this->error['certified_teacher'];
		} else {
			$this->data['error_certified_teacher'] = '';
		}
		
 		if (isset($this->error['criminal_conviction'])) {
			$this->data['error_criminal_conviction'] = $this->error['criminal_conviction'];
		} else {
			$this->data['error_criminal_conviction'] = '';
		}
		
 		if (isset($this->error['background_check'])) {
			$this->data['error_background_check'] = $this->error['background_check'];
		} else {
			$this->data['error_background_check'] = '';
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=common/home';
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=account/create/tutor';

    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		}else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	}else {
      		$this->data['lastname'] = '';
    	}
		
    	if (isset($this->request->post['home_phone'])) {
      		$this->data['home_phone'] = $this->request->post['home_phone'];
    	}else {
      		$this->data['home_phone'] = '';
    	}
		
    	if (isset($this->request->post['cell_phone'])) {
      		$this->data['cell_phone'] = $this->request->post['cell_phone'];
    	}else {
      		$this->data['cell_phone'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	}else {
      		$this->data['email'] = '';
    	}
		
		$this->load->model('user/user_group');

    	if (isset($this->request->post['user_group_id'])) {
      		$this->data['user_group_id'] = $this->request->post['user_group_id'];
    	}else {
      		$this->data['user_group_id'] = $this->config->get('config_user_group_id');
    	}
		
    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	}else {
      		$this->data['status'] = 1;
    	}

    	if (isset($this->request->post['password'])) { 
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) { 
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
    	if (isset($this->request->post['address'])) {
      		$this->data['address'] = $this->request->post['address'];
    	}else {
      		$this->data['address'] = '';
    	}	
		
    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	}else {
      		$this->data['city'] = '';
    	}	
		
    	if (isset($this->request->post['state'])) {
      		$this->data['state'] = $this->request->post['state'];
    	}else {
      		$this->data['state'] = '';
    	}	
		$all_states = $this->zone($this->data['state']);
		$this->data['list_states'] = $all_states;
		
    	if (isset($this->request->post['pcode'])) {
      		$this->data['pcode'] = $this->request->post['pcode'];
    	}else {
      		$this->data['pcode'] = '';
    	}
		
    	if (isset($this->request->post['country'])) {
      		$this->data['country'] = $this->request->post['country'];
    	}else {
      		$this->data['country'] = '';
    	}
		$all_countries = $this->country($this->data['country']);
		$this->data['list_country'] = $all_countries;
		
    	if (isset($this->request->post['users_note'])) {
      		$this->data['users_note'] = $this->request->post['users_note'];
    	}else {
      		$this->data['users_note'] = '';
    	}
		
    	if (isset($this->request->post['post_secondary_education'])) {
      		$this->data['post_secondary_education'] = $this->request->post['post_secondary_education'];
    	}else {
      		$this->data['post_secondary_education'] = '';
    	}	
		
    	if (isset($this->request->post['subjects_studied'])) {
      		$this->data['subjects_studied'] = $this->request->post['subjects_studied'];
    	}else {
      		$this->data['subjects_studied'] = '';
    	}	
		
    	if (isset($this->request->post['courses_available'])) {
      		$this->data['courses_available'] = $this->request->post['courses_available'];
    	}else {
      		$this->data['courses_available'] = '';
    	}	
		
    	if (isset($this->request->post['previous_experience'])) {
      		$this->data['previous_experience'] = $this->request->post['previous_experience'];
    	}else {
      		$this->data['previous_experience'] = '';
    	}	
		
    	if (isset($this->request->post['cities'])) {
      		$this->data['cities'] = $this->request->post['cities'];
    	}else {
      		$this->data['cities'] = '';
    	}	
		
    	if (isset($this->request->post['references'])) {
      		$this->data['references'] = $this->request->post['references'];
    	}else {
      		$this->data['references'] = '';
    	}	
		
    	if (isset($this->request->post['gender'])) {
      		$this->data['gender'] = $this->request->post['gender'];
    	}else {
      		$this->data['gender'] = '';
    	}	
		
    	if (isset($this->request->post['certified_teacher'])) {
      		$this->data['certified_teacher'] = $this->request->post['certified_teacher'];
    	}else {
      		$this->data['certified_teacher'] = '';
    	}	
		
    	if (isset($this->request->post['criminal_conviction'])) {
      		$this->data['criminal_conviction'] = $this->request->post['criminal_conviction'];
    	}else {
      		$this->data['criminal_conviction'] = '';
    	}
		
    	if (isset($this->request->post['background_check'])) {
      		$this->data['background_check'] = $this->request->post['background_check'];
    	}else {
      		$this->data['background_check'] = '';
    	}
		
		if($this->request->post['step']=="2"){
			$this->data['heading_title_tutor'] = "Independent Contractor - Tutor Agreement";
			// Tutor Agreement
			$information_info = $this->model_account_tutor->getTutorAgreement();
			$this->data['tutor_agrement'] = html_entity_decode($information_info['description']);
			$this->data['text_agree'] = $this->language->get('text_agree');
			
			if (isset($this->request->post['name1'])) {
				$this->data['name1'] = $this->request->post['name1'];
			} else {
				$this->data['name1'] = "";
			}
			
			if (isset($this->request->post['name2'])) {
				$this->data['name2'] = $this->request->post['name2'];
			} else {
				$this->data['name2'] = "";
			}
			
			if (isset($this->request->post['name3'])) {
				$this->data['name3'] = $this->request->post['name3'];
			} else {
				$this->data['name3'] = "";
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/tutor_step2.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/tutor_step2.tpl';
			} else {
				$this->template = 'account/tutor_step2.tpl';
			}
		}else{
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/tutor.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/tutor.tpl';
			} else {
				$this->template = 'account/tutor.tpl';
			}
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
  	}

	public function zone($zid) {
		$output = '';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getAllZones();
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['code'] . '"';

			if (isset($zid) && ($zid == $result['code'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="">' . $this->language->get('text_none') . '</option>';
		}

		return $output;
	}
	
	public function country($cid) {
		$output = '';
		
		$this->load->model('localisation/country');
		
		$results = $this->model_localisation_country->getCountries();
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['code'] . '"';

			if (isset($cid) && ($cid == $result['code'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="">' . $this->language->get('text_none') . '</option>';
		}

		return $output;
	}


	private function validateStudent() {
		/*
    	if ((strlen(utf8_decode($this->request->post['username'])) < 1) || (strlen(utf8_decode($this->request->post['username'])) > 32)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}
    	*/
    	if ((strlen(utf8_decode($this->request->post['username'])) > 150) || (!preg_match(EMAIL_PATTERN, $this->request->post['username']))) {
      		$this->error['username'] = $this->language->get('error_username');
    	}
		
    	if (!isset($this->error['username']) && count($this->model_account_student->getStudent($this->request->post['username']))) {
			$this->error['username'] = $this->language->get('error_username_exsit');
		}
    	
    	if (($this->request->post['password']) || (!isset($this->request->get['user_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
    	
    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	
    	if (empty($this->request->post['grade_year'])) {
    		$this->error['grade_year'] = $this->language->get('error_grade_year');
    	}
    	/*
		if ((strlen(utf8_decode($this->request->post['email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
    	*/
    	if ((strlen(utf8_decode($this->request->post['parent_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['parent_firstname'])) > 32)) {
      		$this->error['parent_firstname'] = $this->language->get('error_parent_firstname');
    	}
    	
    	if ((strlen(utf8_decode($this->request->post['parent_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['parent_lastname'])) > 32)) {
 			$this->error['parent_lastname'] = $this->language->get('error_parent_lastname');   	
    	}      		

    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if (empty($this->request->post['address'])) {
    		$this->error['address'] = $this->language->get('error_address');
    	}
    	
    	if (empty($this->request->post['city'])) {
    		$this->error['city'] = $this->language->get('error_city');
    	}
    	
    	if (empty($this->request->post['postcode'])) {
    		$this->error['postcode'] = $this->language->get('error_postcode');
    	}
    	
    	if (empty($this->request->post['state'])) {
    		$this->error['state'] = $this->language->get('error_state');
    	}
    	
    	if (empty($this->request->post['country'])) {
    		$this->error['country'] = $this->language->get('error_country');
    	}
    	
    	if (empty($this->request->post['major_intersection'])) {
    		$this->error['major_intersection'] = $this->language->get('error_major_intersection');
    	}
    	
    	if (empty($this->request->post['school_name'])) {
    		$this->error['school_name'] = $this->language->get('error_school_name');
    	}
    	
    	if(empty($this->request->post['heard_aboutus_other']))
    	if (empty($this->request->post['heard_aboutus'])) {
    		$this->error['heard_aboutus'] = $this->language->get('error_heard_aboutus');
    	}
    	
    	if(empty($this->request->post['heard_aboutus_other']))
    	if(!empty($this->request->post['heard_aboutus']) && $this->request->post['heard_aboutus'] == "Other")
    	if (empty($this->request->post['heard_aboutus_other'])) {
    		$this->error['heard_aboutus_other'] = $this->language->get('error_heard_aboutus');
    	}
    	
    	if(count($this->error) > 0) {
			$this->error['warning'] = $this->language->get('error_warning');
		} else if (!isset($this->request->post['agree'])) {
			$this->error['warning'] = $this->language->get('error_agree');	
		} 
    	
    	if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  	
	}
	
  	private function validateTutor() {
    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	
		if ((strlen(utf8_decode($this->request->post['email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		if($this->model_account_tutor->validateEmail($this->request->post['email'])){
			$this->error['email'] = $this->language->get('error_emailexist');
		}		

    	if ((strlen(utf8_decode($this->request->post['home_phone'])) < 3) || (strlen(utf8_decode($this->request->post['home_phone'])) > 32)) {
      		$this->error['home_phone'] = $this->language->get('error_telephone');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['address'])) < 3) || (strlen(utf8_decode($this->request->post['address'])) > 128)) {
      		$this->error['address'] = $this->language->get('error_address_1');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}

    	if (utf8_decode($this->request->post['state'])=="") {
      		$this->error['state'] = $this->language->get('error_zone');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['pcode'])) < 4) || (strlen(utf8_decode($this->request->post['pcode'])) > 10)) {
      		$this->error['pcode'] = $this->language->get('error_pcode');
    	}	
		
    	if (utf8_decode($this->request->post['country'])=="") {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if (strlen(utf8_decode($this->request->post['users_note'])) < 3) {
      		$this->error['users_note'] = $this->language->get('error_notes');
    	}
		
    	if (strlen(utf8_decode($this->request->post['post_secondary_education'])) < 3) {
      		$this->error['post_secondary_education'] = $this->language->get('error_post_secondary_education');
    	}
		
    	if (strlen(utf8_decode($this->request->post['subjects_studied'])) < 3) {
      		$this->error['subjects_studied'] = $this->language->get('error_subjects_studied');
    	}
		
    	if (strlen(utf8_decode($this->request->post['courses_available'])) < 3) {
      		$this->error['courses_available'] = $this->language->get('error_courses_available');
    	}
		
    	if (strlen(utf8_decode($this->request->post['previous_experience'])) < 3) {
      		$this->error['previous_experience'] = $this->language->get('error_previous_experience');
    	}
		
    	if (strlen(utf8_decode($this->request->post['cities'])) < 3) {
      		$this->error['cities'] = $this->language->get('error_cities');
    	}
		
    	if (strlen(utf8_decode($this->request->post['references'])) < 3) {
      		$this->error['references'] = $this->language->get('error_references');
    	}
		if(isset($this->request->post['certified_teacher'])){
			if (utf8_decode($this->request->post['certified_teacher'])=="") {
				$this->error['certified_teacher'] = $this->language->get('error_certified_teacher');
			}
		}
		
		if(isset($this->request->post['criminal_conviction'])){
			if (utf8_decode($this->request->post['criminal_conviction'])=="") {
				$this->error['criminal_conviction'] = $this->language->get('error_criminal_conviction');
			}
		}
		
		if(isset($this->request->post['background_check'])){
			if (utf8_decode($this->request->post['background_check'])=="") {
				$this->error['background_check'] = $this->language->get('error_background_check');
			}
		}

    	if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}
    	
    	$tutor_name = strtolower(utf8_decode(str_replace(' ','',$this->request->post['firstname'].$this->request->post['lastname'])));

		if(($this->request->post['step']=="2") && (utf8_decode($this->request->post['name1']) == "")) {
				$this->error['warning'] = sprintf($this->language->get('error_name_mismach'), "First Box");
		}else if(($this->request->post['step'] == "2")
					&& (strtolower(utf8_decode(str_replace(' ','',$this->request->post['name1']))) != $tutor_name)) {
				$this->error['warning'] = sprintf($this->language->get('error_name_mismach'), "First Box");
		}else if(($this->request->post['step']=="2") 
					&& (utf8_decode($this->request->post['name2']) == "")) {
				$this->error['warning'] = sprintf($this->language->get('error_name_mismach'), "Second Box");
		}else if(($this->request->post['step']=="2") 
					&& (strtolower(utf8_decode(str_replace(' ','',$this->request->post['name2']))) != $tutor_name)) {
				$this->error['warning'] = sprintf($this->language->get('error_name_mismach'), "Second Box");
		}else if(($this->request->post['step']=="2") 
					&& (utf8_decode($this->request->post['name3']) == "")) {
				$this->error['warning'] = sprintf($this->language->get('error_name_mismach'), "Third Box");
		}else if(($this->request->post['step']=="2") 
					&& (strtolower(utf8_decode(str_replace(' ','',$this->request->post['name3']))) != $tutor_name)) {
				$this->error['warning'] = sprintf($this->language->get('error_name_mismach'), "Third Box");
		}else if($this->request->post['step']=="2"){
			$this->request->post['step'] = "3";
		}

    	if (!$this->error) {
		    if($this->request->post['step'] != "3")
			$this->request->post['step'] = "2";
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
}
?>