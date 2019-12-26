<?php    
class ControllerStudentReportCards extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('student/report_cards');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('student/report_cards');
    	$this->getList();
  	}
   
  	public function view() {
		$this->load->language('student/report_cards');
    	$this->document->title = $this->language->get('heading_title');
		$this->load->model('student/report_cards');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_student_report_cards->editReport($this->request->get['progress_reports_id'], $this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_session_success');
	  
			$url = '';

			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm("heading_title_update");
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

		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['reportcards'] = array();

		$data = array(
			'filter_grade'              => $filter_grade, 
			'filter_tutor_name' => $filter_tutor_name, 
			'filter_subjects'          => $filter_subjects,
			'filter_date_added'    => $filter_date_added,
			'sort'                     => $sort,
			'order'                   => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                     => $this->config->get('config_admin_limit')
		);
		
		$session_total = $this->model_student_report_cards->getTotalReports($data);
		$results = $this->model_student_report_cards->getReports($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=student/report_cards/view&token=' . $this->session->data['token'] . '&progress_reports_id=' . $result['progress_reports_id'] . $url
			);

			$this->data['reportcards'][] = array(
				'progress_reports_id'    => $result['progress_reports_id'],
				'tutors_id'    => $result['tutors_id'],
				'tutor_name'    => $result['tutor_name'],
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
		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
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

		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
		
		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
		$this->data['sort_grade'] = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . '&sort=grade' . $url;
		$this->data['sort_subjects'] = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . '&sort=subjects' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . '&sort=date_added' . $url;


		$pagination = new Pagination();
		$pagination->total = $session_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_grade'] = $filter_grade;
		$this->data['filter_subjects'] = $filter_subjects;
		$this->data['filter_date_added'] = $filter_date_added;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'student/report_cards.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	private function getForm($heading_title) {
    	$this->data['heading_title'] = $this->language->get($heading_title);
    	
		$this->data['entry_tutor'] = $this->language->get('entry_tutor');
		$this->data['entry_grade'] = $this->language->get('entry_grade');
    	$this->data['entry_subjects'] = $this->language->get('entry_subjects');
    	$this->data['entry_student_prepared'] = $this->language->get('entry_student_prepared');
		$this->data['entry_questions_ready'] = $this->language->get('entry_questions_ready');
		$this->data['entry_pay_attention'] = $this->language->get('entry_pay_attention');
		$this->data['entry_weaknesses'] = $this->language->get('entry_weaknesses');
		$this->data['entry_listen_to_suggestions'] = $this->language->get('entry_listen_to_suggestions');
		$this->data['entry_improvements'] = $this->language->get('entry_improvements');
		$this->data['entry_other_comments'] = $this->language->get('entry_other_comments');
 
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['tutors_id'])) {
			$this->data['error_student'] = $this->error['tutors_id'];
		} else {
			$this->data['error_student'] = '';
		}
		
 		if (isset($this->error['weaknesses'])) {
			$this->data['error_weaknesses'] = $this->error['weaknesses'];
		} else {
			$this->data['error_weaknesses'] = '';
		}

		$url = '';
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=student/report_cards/update&token=' . $this->session->data['token'] . '&progress_reports_id=' . $this->request->get['progress_reports_id'] . $url;
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'] . $url;
	    
	    
		$this->data['report_info'] = $this->model_student_report_cards->getReport($this->request->get['progress_reports_id']);
		

		$this->template = 'student/view_report.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>
