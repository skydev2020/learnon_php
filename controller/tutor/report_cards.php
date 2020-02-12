<?php    
class ControllerTutorReportCards extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('tutor/report_cards');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/report_cards');
    	$this->getList();
  	}
  
  	public function insert() {
		$this->load->language('tutor/report_cards');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/report_cards');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->model_tutor_report_cards->addReport($this->request->post);
			log_activity("Report Card Sent", "Tutor sent student progress report card.");
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_student_name'])) {
				$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
			}
			
			if (isset($this->request->get['filter_grade'])) {
				$url .= '&filter_grade=' . $this->request->get['filter_grade'];
			}
				
			if (isset($this->request->get['filter_subjects'])) {
				$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url);
		}
    	
    	$this->getForm("heading_title_insert");
  	} 
   
  	public function update() {
		$this->load->language('tutor/report_cards');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/report_cards');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_tutor_report_cards->editReport($this->request->get['progress_reports_id'], $this->request->post);
	  		log_activity("Report Card Updated", "Tutor updated student progress report card.");
			$this->session->data['success'] = $this->language->get('text_session_success');
	  
			$url = '';

			if (isset($this->request->get['filter_student_name'])) {
				$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
			}
			
			if (isset($this->request->get['filter_grade'])) {
				$url .= '&filter_grade=' . $this->request->get['filter_grade'];
			}
				
			if (isset($this->request->get['filter_subjects'])) {
				$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm("heading_title_update");
  	}   

  	public function delete() {
		$this->load->language('tutor/reportcards');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/report_cards');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $progress_reports_id) {
				$this->model_tutor_report_cards->deleteSession($progress_reports_id);
			}
			log_activity("Report Card(s) Deleted", "Tutor deleted student progress report card(s).");
			$this->session->data['success'] = $this->language->get('text_delete_success');

			$url = '';

			if (isset($this->request->get['filter_student_name'])) {
				$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
			}
			
			if (isset($this->request->get['filter_grade'])) {
				$url .= '&filter_grade=' . $this->request->get['filter_grade'];
			}
				
			if (isset($this->request->get['filter_subjects'])) {
				$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url);
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
		
		if (isset($this->request->get['filter_student_name'])) {
			$filter_student_name = $this->request->get['filter_student_name'];
		} else {
			$filter_student_name = NULL;
		}

		if (isset($this->request->get['filter_grade'])) {
			$filter_grade = $this->request->get['filter_grade'];
		} else {
			$filter_grade = NULL;
		}

		if (isset($this->request->get['filter_subjects'])) {
			$filter_subjects = $this->request->get['filter_subjects'];
		} else {
			$filter_subjects = NULL;
		}	
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}		
		
		$url = '';

		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_grade'])) {
			$url .= '&filter_grade=' . $this->request->get['filter_grade'];
		}
			
		if (isset($this->request->get['filter_subjects'])) {
			$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		$this->data['official_submit'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards/submit&token=' . $this->session->data['token'] . $url;
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards/delete&token=' . $this->session->data['token'] . $url;

		$this->data['reportcards'] = array();

		$data = array(
			'filter_grade'              => $filter_grade, 
			'filter_student_name' => $filter_student_name, 
			'filter_subjects'          => $filter_subjects,
			'filter_date_added'    => $filter_date_added,
			'sort'                     => $sort,
			'order'                   => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                     => $this->config->get('config_admin_limit')
		);
		
		$session_total = $this->model_tutor_report_cards->getTotalReports($data);
		$results = $this->model_tutor_report_cards->getReports($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=tutor/report_cards/update&token=' . $this->session->data['token'] . '&progress_reports_id=' . $result['progress_reports_id'] . $url
			);

			$this->data['reportcards'][] = array(
				'progress_reports_id'    => $result['progress_reports_id'],
				'students_id'    => $result['students_id'],
				'student_name'    => $result['student_name'],
				'date_added'           => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'grade'          => $result['grade'],
				'subjects'     => $result['subjects'],
				'selected'       => isset($this->request->post['selected'])&&in_array($result['progress_reports_id'],$this->request->post['selected']),
				'action'         => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');	
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_grade'] = $this->language->get('column_grade');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_submit'] = $this->language->get('button_submit');
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

		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_grade'])) {
			$url .= '&filter_grade=' . $this->request->get['filter_grade'];
		}
			
		if (isset($this->request->get['filter_subjects'])) {
			$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
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
		
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
		$this->data['sort_grade'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . '&sort=grade' . $url;
		$this->data['sort_subjects'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . '&sort=subjects' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . '&sort=date_added' . $url;


		$pagination = new Pagination();
		$pagination->total = $session_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_student_name'] = $filter_student_name;
		$this->data['filter_grade'] = $filter_grade;
		$this->data['filter_subjects'] = $filter_subjects;
		$this->data['filter_date_added'] = $filter_date_added;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'tutor/report_cards.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	private function getForm($heading_title) {
    	$this->data['heading_title'] = $this->language->get($heading_title);
    	
		$this->data['entry_student'] = $this->language->get('entry_student');
		$this->data['entry_grade'] = $this->language->get('entry_grade');
    	$this->data['entry_subjects'] = $this->language->get('entry_subjects');
    	$this->data['entry_student_prepared'] = $this->language->get('entry_student_prepared');
		$this->data['entry_questions_ready'] = $this->language->get('entry_questions_ready');
		$this->data['entry_pay_attention'] = $this->language->get('entry_pay_attention');
		$this->data['entry_weaknesses'] = $this->language->get('entry_weaknesses');
		$this->data['entry_listen_to_suggestions'] = $this->language->get('entry_listen_to_suggestions');
		$this->data['entry_improvements'] = $this->language->get('entry_improvements');
		$this->data['entry_other_comments'] = $this->language->get('entry_other_comments');
 
		$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['students_id'])) {
			$this->data['error_student'] = $this->error['students_id'];
		} else {
			$this->data['error_student'] = '';
		}
		
 		if (isset($this->error['weaknesses'])) {
			$this->data['error_weaknesses'] = $this->error['weaknesses'];
		} else {
			$this->data['error_weaknesses'] = '';
		}

		$url = '';
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_grade'])) {
			$url .= '&filter_grade=' . $this->request->get['filter_grade'];
		}
			
		if (isset($this->request->get['filter_subjects'])) {
			$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['progress_reports_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards/update&token=' . $this->session->data['token'] . '&progress_reports_id=' . $this->request->get['progress_reports_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'] . $url;
		$progress_reports_id = "";
    	if (isset($this->request->get['progress_reports_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$progress_reports_id = $this->request->get['progress_reports_id'];
      		$report_info = $this->model_tutor_report_cards->getReport($progress_reports_id);
    	}
		
		$this->load->model('tutor/assignment');
		$this->data['students'] = $this->model_tutor_assignment->getStudents();
		
    	if (isset($this->request->post['students_id'])) {
      		$this->data['students_id'] = $this->request->post['students_id'];
		} elseif (isset($report_info)) { 
			$this->data['students_id'] = $report_info['students_id'];
		}else {
      		$this->data['students_id'] = '';
    	}
		
		$subjects = $this->model_tutor_assignment->getAssignedSubjectsByStuentID($this->data['students_id'],$this->session->data['user_id']);
		$grade = $this->model_tutor_report_cards->getGradeByStudentID($this->data['students_id']);

    	if (isset($report_info)) { 
			$this->data['grade'] = $report_info['grade'];
		} else {
      		$this->data['grade'] = $grade;
    	}

    	if (isset($report_info)) { 
			$this->data['subjects'] = $report_info['subjects'];
		} else {
      		$this->data['subjects'] = $subjects;
    	}

    	if (isset($this->request->post['student_prepared'])) {
      		$this->data['student_prepared'] = $this->request->post['student_prepared'];
    	} elseif (isset($report_info)) { 
			$this->data['student_prepared'] = $report_info['student_prepared'];
		} else {
      		$this->data['student_prepared'] = '';
    	}
		
    	if (isset($this->request->post['questions_ready'])) {
      		$this->data['questions_ready'] = $this->request->post['questions_ready'];
    	} elseif (isset($report_info)) { 
			$this->data['questions_ready'] = $report_info['questions_ready'];
		} else {
      		$this->data['questions_ready'] = '';
    	}
		
    	if (isset($this->request->post['pay_attention'])) {
      		$this->data['pay_attention'] = $this->request->post['pay_attention'];
    	} elseif (isset($report_info)) { 
			$this->data['pay_attention'] = $report_info['pay_attention'];
		} else {
      		$this->data['pay_attention'] = '';
    	}
		
    	if (isset($this->request->post['weaknesses'])) {
      		$this->data['weaknesses'] = $this->request->post['weaknesses'];
    	} elseif (isset($report_info)) { 
			$this->data['weaknesses'] = $report_info['weaknesses'];
		} else {
      		$this->data['weaknesses'] = '';
    	}
		
    	if (isset($this->request->post['listen_to_suggestions'])) {
      		$this->data['listen_to_suggestions'] = $this->request->post['listen_to_suggestions'];
    	} elseif (isset($report_info)) { 
			$this->data['listen_to_suggestions'] = $report_info['listen_to_suggestions'];
		} else {
      		$this->data['listen_to_suggestions'] = '';
    	}
		
    	if (isset($this->request->post['improvements'])) {
      		$this->data['improvements'] = $this->request->post['improvements'];
    	} elseif (isset($report_info)) { 
			$this->data['improvements'] = $report_info['improvements'];
		} else {
      		$this->data['improvements'] = '';
    	}
		
    	if (isset($this->request->post['other_comments'])) {
      		$this->data['other_comments'] = $this->request->post['other_comments'];
    	} elseif (isset($report_info)) { 
			$this->data['other_comments'] = $report_info['other_comments'];
		} else {
      		$this->data['other_comments'] = '';
    	}

		$this->template = 'tutor/send_report.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	 
  	private function validateForm() {

    	if ($this->request->post['students_id']=="") {
      		$this->error['students_id'] = $this->language->get('error_student');
    	}
		
    	if ($this->request->post['weaknesses']=="") {
      		$this->error['weaknesses'] = $this->language->get('error_weaknesses');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}  

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'tutor/reportcards')) {
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
