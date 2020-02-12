<?php    
class ControllerUserSessions extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('user/sessions');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('user/sessions');
    	$this->getList();
  	}
  
  	public function insert() {
		$this->load->language('user/sessions');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('user/sessions');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->model_user_sessions->addSession($this->request->post);
			
			log_activity("Logged Hours", "A new session logged.");
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url);
		}
    	
    	$this->getForm("heading_title_insert");
  	} 
   
  	public function update() {
		$this->load->language('user/sessions');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('user/sessions');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_sessions->editSession($this->request->get['session_id'], $this->request->post);
	  		log_activity("Session Updated", "Session details updated.");
			$this->session->data['success'] = $this->language->get('text_session_success');
	  
			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm("heading_title_update");
  	}
  	
  	public function lock() {
  		
  		$this->load->language('user/sessions');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/sessions');
			
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $session_id) {
				$this->model_user_sessions->lockSession($session_id);
			}
			
			log_activity("Session Locked", "Tutor session(s) Locked.");
			
			$this->session->data['success'] = $this->language->get('text_lock_success');

			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getList();  	
  	}
  	
  	public function unlock() {
  		$this->load->language('user/sessions');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/sessions');
			
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $session_id) {
				$this->model_user_sessions->unlockSession($session_id);
			}
			
			log_activity("Session Unlocked", "Tutor session(s) Unlocked.");
			
			$this->session->data['success'] = $this->language->get('text_unlock_success');

			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getList();
  	}

  	public function delete() {
		$this->load->language('user/sessions');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/sessions');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $session_id) {
				$this->model_user_sessions->deleteSession($session_id);
			}
			
			log_activity("Session Deleted", "Tutor session(s) deleted.");
			
			$this->session->data['success'] = $this->language->get('text_success_delete');

			$url = '';

			if (isset($this->request->get['filter_session_date'])) {
				$url .= '&filter_session_date=' . $this->request->get['filter_session_date'];
			}
			
			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url);
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
		
		if (isset($this->request->get['filter_session_date'])) {
			$filter_session_date = $this->request->get['filter_session_date'];
		} else {
			$filter_session_date = NULL;
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
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/sessions/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/sessions/delete&token=' . $this->session->data['token'] . $url;
		$this->data['lock_sessions'] = HTTPS_SERVER . 'index.php?route=user/sessions/lock&token=' . $this->session->data['token'] . $url;		
		$this->data['unlock_sessions'] = HTTPS_SERVER . 'index.php?route=user/sessions/unlock&token=' . $this->session->data['token'] . $url;		
		
		
		if($this->user->getUserGroupId() > 3)
			$this->data['sessions_controll'] = 1;
		else
			$this->data['sessions_controll'] = 0;

		$this->data['sessions'] = array();

		$data = array(
			'filter_session_date'              => $filter_session_date, 
			'filter_tutor_name'             => $filter_tutor_name, 
			'filter_student_name'             => $filter_student_name, 
			'filter_session_duration'        => $filter_session_duration,
			'filter_session_notes'        => $filter_session_notes,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$session_total = $this->model_user_sessions->getTotalSessions($data);
		$results = $this->model_user_sessions->getSessions($data);
 		$duration_array = $this->model_user_sessions->getAllDurations();
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/sessions/update&token=' . $this->session->data['token'] . '&session_id=' . $result['session_id'] . $url
			);
			
			if($result['p_locked'])
			$action[] = array(
				'text' => 'P Locked',
				'href' => 'javascript:void(0)'
			);
			
			if($result['i_locked'])
			$action[] = array(
				'text' => 'I Locked',
				'href' => 'javascript:void(0)'
			);

			$this->data['sessions'][] = array(
			    'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'tutor_wage'    => $result['base_wage'],
				'base_invoice'    => $result['base_invoice'],
				'tutor_name'    => $result['tutor_name'],
				'student_name'    => $result['student_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['date_submission'])),
				'session_duration'          => $duration_array[$result['session_duration']],
				'date'     => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['session_duration'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
		$this->data['duration_array'] = 	$duration_array;				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
		$this->data['column_session_date'] = $this->language->get('column_session_date');
		$this->data['column_date'] = $this->language->get('column_date');		
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_unassing'] = $this->language->get('button_unassing');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_lock'] = $this->language->get('button_lock');
		$this->data['button_unlock'] = $this->language->get('button_unlock');
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
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
		
        $this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . '&sort=student_name' . $url;
		$this->data['sort_session_date'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . '&sort=session_date' . $url;
		$this->data['sort_session_duration'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . '&sort=session_duration' . $url;
		$this->data['sort_session_notes'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . '&sort=session_notes' . $url;


		$pagination = new Pagination();
		$pagination->total = $session_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_student_name'] = $filter_student_name;
		$this->data['filter_session_date'] = $filter_session_date;
		$this->data['filter_session_duration'] = $filter_session_duration;
		$this->data['filter_session_notes'] = $filter_session_notes;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'user/sessions.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	private function getForm($heading_title) {
    	$this->data['heading_title'] = $this->language->get($heading_title);
		$this->data['entry_tutor_student'] = $this->language->get('entry_tutor_student');
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
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['session_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/sessions/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/sessions/update&token=' . $this->session->data['token'] . '&session_id=' . $this->request->get['session_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'] . $url;
		$session_id = "";
    	if (isset($this->request->get['session_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$session_id = $this->request->get['session_id'];
      		$session_info = $this->model_user_sessions->getSession($session_id);
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
		$this->data['assignments'] = $this->model_tutor_assignment->getAssignments();
		$this->data['duration_array'] = $this->model_user_sessions->getAllDurations();
		$this->template = 'user/sessions_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/sessions')) {
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

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'user/sessions')) {
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
