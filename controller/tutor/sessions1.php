<?php    
class ControllerTutorSessions extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('tutor/sessions');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/sessions');
    	$this->getList();
  	}
	
  	public function submit() {
		$this->load->language('tutor/sessions');
		if($this->validatePeriod()){
			$this->document->title = $this->language->get('heading_title_submission');
			$this->load->model('tutor/sessions');
			$this->getSubmissionList();
		}else{
			$this->document->title = $this->language->get('heading_title');
			$this->load->model('tutor/sessions');
			$this->getList();
		}
  	}
  
  	public function insert() {
		$this->load->language('tutor/sessions');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/sessions');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			// submission date
			$this->request->post['date_submission'] = date("Y-m-d H:i:s");
			
      	  	$this->model_tutor_sessions->addSession($this->request->post);
			log_activity("Logged Hours", "Tutor logged his session hours.");
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_student_name'])) {
				$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
			}
		
			if (isset($this->request->get['filter_session_duration'])) {
				$url .= '&filter_session_duration=' . $this->request->get['filter_session_duration'];
			}
			
			if (isset($this->request->get['filter_session_notes'])) {
				$url .= '&filter_session_notes=' . $this->request->get['filter_session_notes'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url);
		}
    	
    	$this->getForm("heading_title_insert");
  	} 
   
  	public function update() {
		$this->load->language('tutor/sessions');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/sessions');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_tutor_sessions->editSession($this->request->get['session_id'], $this->request->post);
	  		log_activity("Session Updated", "Tutor updated session details.");
			$this->session->data['success'] = $this->language->get('text_session_success');
	  
			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_student_name'])) {
				$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
			}
		
			if (isset($this->request->get['filter_session_duration'])) {
				$url .= '&filter_session_duration=' . $this->request->get['filter_session_duration'];
			}
			
			if (isset($this->request->get['filter_session_notes'])) {
				$url .= '&filter_session_notes=' . $this->request->get['filter_session_notes'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm("heading_title_update");
  	}   
	
  	public function confirm() {
		$this->load->model('tutor/sessions');
		
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $session_id) {
				$this->model_tutor_sessions->confirmSubmission($session_id);
			}
			log_activity("Official Submission", "Tutor submitted his hours.");
			$this->session->data['success'] = $this->language->get('text_submission_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token']);
		}
  	}   

  	public function delete() {
		$this->load->language('tutor/sessions');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/sessions');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $session_id) {
				$this->model_tutor_sessions->deleteSession($session_id);
			}
			log_activity("Session Deleted", "Tutor deleted his session.");
			$this->session->data['success'] = $this->language->get('text_delete_success');

			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_student_name'])) {
				$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
			}
		
			if (isset($this->request->get['filter_session_duration'])) {
				$url .= '&filter_session_duration=' . $this->request->get['filter_session_duration'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url);
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
			$sort = 'date_added'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['filter_session_date'])) {
			$filter_session_date = $this->request->get['filter_session_date'];
		} else {
			$filter_session_date = NULL;
		}

		if (isset($this->request->get['filter_student_name'])) {
			$filter_student_name = $this->request->get['filter_student_name'];
		} else {
			$filter_student_name = NULL;
		}

		if (isset($this->request->get['filter_session_duration'])) {
			$filter_session_duration = $this->request->get['filter_session_duration'];
		} else {
			$filter_session_duration = NULL;
		}	
		
		if (isset($this->request->get['filter_session_notes'])) {
			$filter_session_notes = $this->request->get['filter_session_notes'];
		} else {
			$filter_session_notes = NULL;
		}		
		
		$url = '';

		if (isset($this->request->get['filter_session_date'])) {
			$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
		}
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
			
		if (isset($this->request->get['filter_session_duration'])) {
			$url .= '&filter_session_duration=' . $this->request->get['filter_session_duration'];
		}
		
		if (isset($this->request->get['filter_session_notes'])) {
			$url .= '&filter_session_notes=' . $this->request->get['filter_session_notes'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		$this->data['official_submit'] = HTTPS_SERVER . 'index.php?route=tutor/sessions/submit&token=' . $this->session->data['token'] . $url;
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=tutor/sessions/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=tutor/sessions/delete&token=' . $this->session->data['token'] . $url;

		$this->data['sessions'] = array();

		$data = array(
			'filter_session_date'              => $filter_session_date, 
			'filter_student_name'             => $filter_student_name, 
			'filter_session_duration'        => $filter_session_duration,
			'filter_session_notes'        => $filter_session_notes,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$session_total = $this->model_tutor_sessions->getTotalSessions($data);
		$results = $this->model_tutor_sessions->getSessions($data);
 		$duration_array = $this->model_tutor_sessions->getAllDurations();
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=tutor/sessions/update&token=' . $this->session->data['token'] . '&session_id=' . $result['session_id'] . $url
			);

			$this->data['sessions'][] = array(
				'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'student_name'    => $result['student_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'session_duration'          => $duration_array[$result['session_duration']],
				'session_notes'     => $result['session_notes'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['session_duration'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
		$this->data['duration_array'] = 	$duration_array;		
		$this->data['heading_title'] = $this->language->get('heading_title');	
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
		$this->data['column_session_date'] = $this->language->get('column_session_date');
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

		if (isset($this->request->get['filter_session_date'])) {
			$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
		}
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_session_duration'])) {
			$url .= '&filter_session_duration=' . $this->request->get['filter_session_duration'];
		}
		
		if (isset($this->request->get['filter_session_notes'])) {
			$url .= '&filter_session_notes=' . $this->request->get['filter_session_notes'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
		$this->data['sort_session_date'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=session_date' . $url;
		$this->data['sort_session_duration'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=session_duration' . $url;
		$this->data['sort_session_notes'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=session_notes' . $url;


		$pagination = new Pagination();
		$pagination->total = $session_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_student_name'] = $filter_student_name;
		$this->data['filter_session_date'] = $filter_session_date;
		$this->data['filter_session_duration'] = $filter_session_duration;
		$this->data['filter_session_notes'] = $filter_session_notes;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'tutor/sessions.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
	
  	private function getSubmissionList() {
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}	
		
		$url = '';

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
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url;

		$this->data['sessions'] = array();
		$data = array(
			'sort'              => $sort,
			'order'            => $order,
			'is_locked'      => "0"
		);
		
		$session_total = $this->model_tutor_sessions->getTotalSessions($data);
		$results = $this->model_tutor_sessions->getSessions($data);
 		$duration_array = $this->model_tutor_sessions->getAllDurations();
    	foreach ($results as $result) {
			$this->data['sessions'][] = array(
				'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'student_name'    => $result['student_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'session_duration'          => $duration_array[$result['session_duration']],
				'session_notes'     => $result['session_notes'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['session_duration'], $this->request->post['selected'])
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title_submission');	
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
		$this->data['column_session_date'] = $this->language->get('column_session_date');	
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['token'] = $this->session->data['token'];
	
		$url = '';
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
		$this->data['sort_session_date'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=session_date' . $url;
		$this->data['sort_session_duration'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=session_duration' . $url;
		$this->data['sort_session_notes'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . '&sort=session_notes' . $url;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/sessions/confirm&token=' . $this->session->data['token'].$url;
		$this->template = 'tutor/confirm_submission.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	private function getForm($heading_title) {
    	$this->data['heading_title'] = $this->language->get($heading_title);
    	
		$this->data['entry_student'] = $this->language->get('entry_student');
		$this->data['entry_session_date'] = $this->language->get('entry_session_date');
    	$this->data['entry_session_duration'] = $this->language->get('entry_session_duration');
    	$this->data['entry_session_notes'] = $this->language->get('entry_session_notes');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add'] = $this->language->get('button_add');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['tutors_to_students_id'])) {
			$this->data['error_student'] = $this->error['tutors_to_students_id'];
		} else {
			$this->data['error_student'] = '';
		}

 		if (isset($this->error['session_date'])) {
			$this->data['error_session_date'] = $this->error['session_date'];
		} else {
			$this->data['error_session_date'] = '';
		}

 		if (isset($this->error['session_duration'])) {
			$this->data['error_session_duration'] = $this->error['session_duration'];
		} else {
			$this->data['error_session_duration'] = '';
		}

		$url = '';
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_session_date'])) {
			$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
		}
		
		if (isset($this->request->get['filter_session_duration'])) {
			$url .= '&filter_session_duration=' . $this->request->get['filter_session_duration'];
		}
		
		if (isset($this->request->get['filter_session_notes'])) {
			$url .= '&filter_session_notes=' . $this->request->get['filter_session_notes'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['session_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/sessions/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/sessions/update&token=' . $this->session->data['token'] . '&session_id=' . $this->request->get['session_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'] . $url;
		$session_id = "";
    	if (isset($this->request->get['session_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$session_id = $this->request->get['session_id'];
      		$session_info = $this->model_tutor_sessions->getSession($session_id);
    	}
		
    	if (isset($this->request->post['tutors_to_students_id'])) {
      		$this->data['tutors_to_students_id'] = $this->request->post['tutors_to_students_id'];
		} elseif (isset($session_info)) { 
			$this->data['tutors_to_students_id'] = $session_info['tutors_to_students_id'];
		} elseif (isset($this->request->get['tutors_to_students_id'])) { 
			$this->data['tutors_to_students_id'] = $this->request->get['tutors_to_students_id'];
		} else {
      		$this->data['tutors_to_students_id'] = '';
    	}
			
    	if (isset($this->request->post['session_date'])) {
      		$this->data['session_date'] = $this->request->post['session_date'];
		} elseif (isset($session_info)) { 
			$this->data['session_date'] = $session_info['session_date'];
		} else {
      		$this->data['session_date'] = '';
    	}

    	if (isset($this->request->post['session_duration'])) {
      		$this->data['session_duration'] = $this->request->post['session_duration'];
		} elseif (isset($session_info)) { 
			$this->data['session_duration'] = $session_info['session_duration'];
		} else {
      		$this->data['session_duration'] = '';
    	}

    	if (isset($this->request->post['session_notes'])) {
      		$this->data['session_notes'] = $this->request->post['session_notes'];
    	} elseif (isset($session_info)) { 
			$this->data['session_notes'] = $session_info['session_notes'];
		} else {
      		$this->data['session_notes'] = '';
    	}
		
		$this->load->model('tutor/assignment');
		$this->data['students'] = $this->model_tutor_assignment->getStudents();
		$this->data['duration_array'] = $this->model_tutor_sessions->getAllDurations();

		$this->template = 'tutor/sessions_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'tutor/sessions')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
    	if ($this->request->post['tutors_to_students_id']=="") {
      		$this->error['tutors_to_students_id'] = $this->language->get('error_student');
    	}
		
    	if ($this->request->post['session_date']=="") {
      		$this->error['session_date'] = $this->language->get('error_session_date');
    	}

    	if ($this->request->post['session_duration']=="") {
      		$this->error['session_duration'] = $this->language->get('error_session_duration');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
	
  	private function validatePeriod() {
    	if (!$this->user->hasPermission('modify', 'tutor/sessions')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
		$today = time();
		if(date('d')<6){
			$upperday = strtotime(date('Y').'-'.date('m').'-05 00:00:01');
			$lowerday = strtotime(date('Y').'-'.str_pad((date('m')-1),2,'0',STR_PAD_LEFT).'-28 00:00:01');
		}else{
			$upperday = strtotime(date('Y').'-'.str_pad((date('m')+1),2,'0',STR_PAD_LEFT).'-05 00:00:01');
			$lowerday = strtotime(date('Y').'-'.date('m').'-28 00:00:01');
		}

    	if ($today>$upperday || $today<$lowerday) {
      		$this->error['warning'] = $this->language->get('error_period');
    	}
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	} 	   

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'tutor/sessions')) {
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
