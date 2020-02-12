<?php    
class ControllerTutorWork extends Controller { 
	private $error = array();
  
  	public function index() {
  		
		$this->load->language('tutor/work');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/tutors');
		$this->load->model('cms/essays');
		
		if(isset($this->request->get['user_id']) and !empty($this->request->get['user_id']))
		{
			$get_work_details = true;
		}
		else
		{
			$get_work_details = false;
		}
		
		// -------------------------------- Homework Assignment Code --------------------------------
		if($get_work_details){
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		//set filter variables
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_assinged';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
    	
		if (isset($this->request->get['filter_assignment_num'])) {
			$filter_assignment_num = $this->request->get['filter_assignment_num'];
		} else {
			$filter_assignment_num = NULL;
		}
		
		if (isset($this->request->get['filter_price_paid'])) {
			$filter_price_paid = $this->request->get['filter_price_paid'];
		} else {
			$filter_price_paid = NULL;
		}
		
		if (isset($this->request->get['filter_paid_to_tutor'])) {
			$filter_paid_to_tutor = $this->request->get['filter_paid_to_tutor'];
		} else {
			$filter_paid_to_tutor = NULL;
		}
			
		
		if (isset($this->request->get['filter_student_name'])) {
			$filter_student_name = $this->request->get['filter_student_name'];
		} else {
			$filter_student_name = NULL;
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$filter_tutor_name = $this->request->get['filter_tutor_name'];
		} else {
			$filter_tutor_name = NULL;
		}
		
		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}

		if (isset($this->request->get['filter_topic'])) {
			$filter_topic = $this->request->get['filter_topic'];
		} else {
			$filter_topic = NULL;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}
		
		if (isset($this->request->get['filter_date_assigned'])) {
			$filter_date_assigned = $this->request->get['filter_date_assigned'];
		} else {
			$filter_date_assigned = NULL;
		}
		
		if (isset($this->request->get['filter_date_completed'])) {
			$filter_date_completed = $this->request->get['filter_date_completed'];
		} else {
			$filter_date_completed = NULL;
		}
		
		if (isset($this->request->get['filter_date_to_assigned'])) {
			$filter_date_to_assigned = $this->request->get['filter_date_to_assigned'];
		} else {
			$filter_date_to_assigned = NULL;
		}
		
		if (isset($this->request->get['filter_date_to_completed'])) {
			$filter_date_to_completed = $this->request->get['filter_date_to_completed'];
		} else {
			$filter_date_to_completed = NULL;
		}
		
		/* End of Code */
		
		// set default show only approved
		$filter_approved = '1';
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}		
		//end of set filter variables
		
		
		//set url variables		
		$url = '';
		
		if (isset($this->request->get['filter_assignment_num'])) {
			$url .= '&filter_assignment_num=' . $this->request->get['filter_assignment_num'];
		}
		
		if (isset($this->request->get['filter_price_paid'])) {
			$url .= '&filter_price_paid=' . $this->request->get['filter_price_paid'];
		}
				
		if (isset($this->request->get['filter_paid_to_tutor'])) {
			$url .= '&filter_paid_to_tutor=' . $this->request->get['filter_paid_to_tutor'];
		}

		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
				
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_topic'])) {
			$url .= '&filter_topic=' . $this->request->get['filter_topic'];
		}
		
		if (isset($this->request->get['filter_subjects'])) {
			$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
			
		if (isset($this->request->get['filter_date_assigned'])) {
			$url .= '&filter_date_assigned=' . $this->request->get['filter_date_assigned'];
		}
		
		if (isset($this->request->get['filter_date_completed'])) {
			$url .= '&filter_date_completed=' . $this->request->get['filter_date_completed'];
		}
			
		if (isset($this->request->get['filter_date_to_assigned'])) {
			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
		}
		
		if (isset($this->request->get['filter_date_to_completed'])) {
			$url .= '&filter_date_to_completed=' . $this->request->get['filter_date_to_completed'];
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
		
		//end of set url variables
		
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['informations'] = array();

			$data = array(
			'filter_assignment_num'    => $filter_assignment_num, 
			'filter_price_paid'        => $filter_price_paid, 
			'filter_paid_to_tutor'     => $filter_paid_to_tutor, 
			'filter_student_name'      => $filter_student_name, 
			'filter_tutor_name'        => $filter_tutor_name, 
			//'filter_all'               => $filter_all, 
			'filter_topic'             => $filter_topic,  
			'filter_status'            => $filter_status,
			'filter_date_assigned'     => $filter_date_assigned,
			'filter_date_completed'    => $filter_date_completed,
			'filter_date_to_assigned'  => $filter_date_to_assigned,			
			'filter_date_to_completed' => $filter_date_to_completed,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit'),
			'filter_tutor_id'		   => $this->request->get['user_id']
		);
		
		/*Softronikx Technologies */
		$assignment_status = $this->model_cms_essays->getAssignmentStatus();
		/*End of Code */
		
		$information_total = $this->model_cms_essays->getTotalInformations($data);
	
		$results = $this->model_cms_essays->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/work/update&token=' . $this->session->data['token'] . '&essay_id=' . $result['essay_id'] . $url
			);
			
			if($result['is_locked'])
			$action[] = array(
				'text' => 'Locked',
				'href' => 'javascript:void(0)'
			);
			
			$due_date = strtotime($result['date_completed']);
			if(!empty($due_date))
				$due_date = date('Y-m-d', strtotime($result['date_completed']));
			else
				$due_date = date('Y-m-d', strtotime($result['date_due']))." *";				
//			
						
			$this->data['informations'][] = array(
				'essay_id' => $result['essay_id'],
				'topic'      => $result['topic'],
				'assignment_num' => "A".$result['assignment_num'],
				'student_name' => $result['student_name'],
				'tutor_name' => $result['tutor_name'],
				'status' => $result['curr_status'],
				'owed' => $result['owed'],
				'paid' => $result['paid'],
				'due_date' => $due_date,
				'date_assigned' => date('Y-m-d', strtotime($result['date_assigned'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['essay_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title_tutor_work_details');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');	
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_assignment_num'] = $this->language->get('column_assignment_num');
		$this->data['column_student_name'] = $this->language->get('column_student_name');		
		$this->data['column_topic'] = $this->language->get('column_topic');
		$this->data['column_date_assigned'] = $this->language->get('column_date_assigned');
		$this->data['column_due_date'] = $this->language->get('column_due_date');	
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['column_homework_assignment'] = $this->language->get('column_homework_assignment');
		
		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		
		
		$this->data['token'] = $this->session->data['token'];
 
 		if (isset($this->error['warning'])) {
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

		//url for sorting
		$url = '';

		if (isset($this->request->get['filter_assignment_num'])) {
			$url .= '&filter_assignment_num=' . $this->request->get['filter_assignment_num'];
		}
		
		if (isset($this->request->get['filter_price_paid'])) {
			$url .= '&filter_price_paid=' . $this->request->get['filter_price_paid'];
		}
		
		if (isset($this->request->get['filter_paid_to_tutor'])) {
			$url .= '&filter_paid_to_tutor=' . $this->request->get['filter_paid_to_tutor'];
		}
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_topic'])) {
			$url .= '&filter_topic=' . $this->request->get['filter_topic'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_assigned'])) {
			$url .= '&filter_date_assigned=' . $this->request->get['filter_date_assigned'];
		}	
		
		if (isset($this->request->get['filter_date_completed'])) {
			$url .= '&filter_date_completed=' . $this->request->get['filter_date_completed'];
		}
		
		if (isset($this->request->get['filter_date_to_assigned'])) {
			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
		}	
		
		if (isset($this->request->get['filter_date_to_completed'])) {
			$url .= '&filter_date_to_completed=' . $this->request->get['filter_date_to_completed'];
		}
		
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_assignment_num'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.assignment_num' . $url;
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.student_name' . $url;
		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.tutor_id' . $url;
		$this->data['sort_topic'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.topic' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.status' . $url;
		$this->data['sort_date_assigned'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.date_assigned' . $url;;
		$this->data['sort_due_date'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.date_due' . $url;;
		$this->data['sort_price_paid'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.owed' . $url;;
		$this->data['paid_to_tutor'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.paid' . $url;;
		
		//url for pagination		
		$url = '';

		$url = '';

		if (isset($this->request->get['filter_assignment_num'])) {
			$url .= '&filter_assignment_num=' . $this->request->get['filter_assignment_num'];
		}
		
		if (isset($this->request->get['filter_price_paid'])) {
			$url .= '&filter_price_paid=' . $this->request->get['filter_price_paid'];
		}
		
		if (isset($this->request->get['filter_paid_to_tutor'])) {
			$url .= '&filter_paid_to_tutor=' . $this->request->get['filter_paid_to_tutor'];
		}
		
		
		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_topic'])) {
			$url .= '&filter_topic=' . $this->request->get['filter_topic'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_assigned'])) {
			$url .= '&filter_date_assigned=' . $this->request->get['filter_date_assigned'];
		}

		if (isset($this->request->get['filter_date_completed'])) {
			$url .= '&filter_date_completed=' . $this->request->get['filter_date_completed'];
		}
		
		if (isset($this->request->get['filter_date_to_assigned'])) {
			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
		}

		if (isset($this->request->get['filter_date_to_completed'])) {
			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
		}
		
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		//get tutors name
		$tutor_details = $this->model_user_tutors->getTutor($this->request->get['user_id']);
		
		$this->data['tutor_name'] = $tutor_details['firstname'].' '.$tutor_details['lastname'];
		
		$this->data['filter_student_name'] = $filter_student_name;
		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_all'] = $filter_all;
		$this->data['filter_topic'] = $filter_topic;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date_assigned'] = $filter_date_assigned;
		$this->data['filter_date_completed'] = $filter_date_completed;	
		$this->data['filter_date_to_assigned'] = $filter_date_to_assigned;
		$this->data['filter_date_to_completed'] = $filter_date_to_completed;	
		$this->data['assignment_status'] = $assignment_status;	
		$this->data['filter_assignment_num'] = $filter_assignment_num;
		$this->data['filter_price_paid'] = $filter_price_paid;
		$this->data['filter_paid_to_tutor'] = $filter_paid_to_tutor;		
		$this->data['user_id'] = $this->request->get['user_id'];
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		
		// -------------------------------- End of Homework Assignment Code --------------------------------
		
		}
		
		// -------------------------------- Session Details Code ----------------------------------------
		if($get_work_details) {
		
		$this->load->model('user/sessions');
		
		
		if (isset($this->request->get['page_s'])) {
			$page_s = $this->request->get['page_s'];
		} else {
			$page_s = 1;
		}
		
		if (isset($this->request->get['sort_s'])) {
			$sort_s = $this->request->get['sort_s'];
		} else {
			$sort_s = 'name'; 
		}
		
		if (isset($this->request->get['order_s'])) {
			$order_s = $this->request->get['order_s'];
		} else {
			$order_s = 'ASC';
		}
		
		if (isset($this->request->get['filter_session_date_s'])) {
			$filter_session_date_s = $this->request->get['filter_session_date_s'];
		} else {
			$filter_session_date_s = NULL;
		}
		
		if (isset($this->request->get['filter_tutor_name_s'])) {
			$filter_tutor_name_s = $this->request->get['filter_tutor_name_s'];
		} else {
			$filter_tutor_name_s = NULL;
		}

		if (isset($this->request->get['filter_student_name_s'])) {
			$filter_student_name_s = $this->request->get['filter_student_name_s'];
		} else {
			$filter_student_name_s = NULL;
		}

		if (isset($this->request->get['filter_session_duration_s'])) {
			$filter_session_duration_s = $this->request->get['filter_session_duration_s'];
		} else {
			$filter_session_duration_s = NULL;
		}	
		
		if (isset($this->request->get['filter_session_notes_s'])) {
			$filter_session_notes_s = $this->request->get['filter_session_notes_s'];
		} else {
			$filter_session_notes_s = NULL;
		}		
		
		$url = '';

		if (isset($this->request->get['filter_session_date_s'])) {
			$url .= '&filter_session_date_s=' . $this->request->get['filter_session_date_s'];
		}
		
		if (isset($this->request->get['filter_tutor_name_s'])) {
			$url .= '&filter_tutor_name_s=' . $this->request->get['filter_tutor_name_s'];
		}
		
		if (isset($this->request->get['filter_student_name_s'])) {
			$url .= '&filter_student_name_s=' . $this->request->get['filter_student_name_s'];
		}
			
		if (isset($this->request->get['filter_session_duration_s'])) {
			$url .= '&filter_session_duration_s=' . $this->request->get['filter_session_duration_s'];
		}
		
		if (isset($this->request->get['filter_session_notes_s'])) {
			$url .= '&filter_session_notes_s=' . $this->request->get['filter_session_notes_s'];
		}
						
		if (isset($this->request->get['page_s'])) {
			$url .= '&page_s=' . $this->request->get['page_s'];
		}

		if (isset($this->request->get['sort_s'])) {
			$url .= '&sort_s=' . $this->request->get['sort_s'];
		}

		if (isset($this->request->get['order_s'])) {
			$url .= '&order_s=' . $this->request->get['order_s'];
		}

		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/delete&token=' . $this->session->data['token'] . $url;
		$this->data['lock_sessions'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/lock&token=' . $this->session->data['token'] . $url;		
		$this->data['unlock_sessions'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/unlock&token=' . $this->session->data['token'] . $url;		
		
		
		if($this->user->getUserGroupId() > 3)
			$this->data['sessions_controll'] = 1;
		else
			$this->data['sessions_controll'] = 0;

		$this->data['sessions'] = array();

		$data = array(
			'filter_session_date'              => $filter_session_date_s, 
			'filter_tutor_name'             => $filter_tutor_name_s, 
			'filter_student_name'             => $filter_student_name_s, 
			'filter_session_duration'        => $filter_session_duration_s,
			'filter_session_notes'        => $filter_session_notes_s,
			'sort'                     => $sort_s,
			'order'                    => $order_s,
			'start'                    => ($page_s - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit'),
			'filter_tutor_id'		=> $this->request->get['user_id'] //filter the data by tutor id here
		);
		
		$session_total = $this->model_user_sessions->getTotalSessions($data);
		$results = $this->model_user_sessions->getSessions($data);
 		$duration_array = $this->model_user_sessions->getAllDurations();
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/work/update&token=' . $this->session->data['token'] . '&session_id=' . $result['session_id'] . $url
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
		$this->data['heading_title_s'] = $this->language->get('heading_title_session');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
		$this->data['column_session_date'] = $this->language->get('column_session_date');
		$this->data['column_date'] = $this->language->get('column_date');		
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['heading_title_session'] = $this->language->get('heading_title_session');	
		$this->data['column_tutor_wage'] = $this->language->get('column_tutor_wage');		
		$this->data['column_base_invoice'] = $this->language->get('column_base_invoice');	
		
		
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

		if (isset($this->request->get['filter_session_date_s'])) {
			$url .= '&filter_session_date_s=' . $this->request->get['filter_session_date_s'];
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_student_name_s'])) {
			$url .= '&filter_student_name_s=' . $this->request->get['filter_student_name_s'];
		}
		
		if (isset($this->request->get['filter_session_duration_s'])) {
			$url .= '&filter_session_duration_s=' . $this->request->get['filter_session_duration_s'];
		}
		
		if (isset($this->request->get['filter_session_notes_s'])) {
			$url .= '&filter_session_notes_s=' . $this->request->get['filter_session_notes_s'];
		}
		
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}
		
		if ($order_s == 'ASC') {
			$url .= '&order_s=DESC';
		} else {
			$url .= '&order_s=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page_s=' . $this->request->get['page'];
		}
		
        $this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=tutor_name' . $url;
		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=student_name' . $url;
		$this->data['sort_session_date'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=session_date' . $url;
		$this->data['sort_session_duration'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=session_duration' . $url;
		$this->data['sort_session_notes'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=session_notes' . $url;


		$pagination_s = new Pagination();
		$pagination_s->total = $session_total;
		$pagination_s->page = $page;
		$pagination_s->limit = $this->config->get('config_admin_limit');
		$pagination_s->text = $this->language->get('text_pagination');
		$pagination_s->url = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url . '&page_s={page}';
			
		$this->data['pagination_s'] = $pagination_s->render();
		$this->data['filter_tutor_name_s'] = $filter_tutor_name_s;
		$this->data['filter_student_name_s'] = $filter_student_name_s;
		$this->data['filter_session_date_s'] = $filter_session_date_s;
		$this->data['filter_session_duration_s'] = $filter_session_duration_s;
		$this->data['filter_session_notes_s'] = $filter_session_notes_s;
		
		
		$this->data['sort_s'] = $sort_s;
		$this->data['order_s'] = $order_s;
		
		}
		
		// -------------------------------- End of Session Details Code -------------------------------
		
		
		
		if($get_work_details){
		$this->template = 'user/tutors_work_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
		else
		{
			echo "Invalid Tutor!";
		}
		//$this->getForm("heading_title_update");
		//code to load the work template and display the work
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
