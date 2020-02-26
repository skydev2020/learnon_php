<?php    
class ControllerUserStudents extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('user/students');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/students');
		
    	$this->getList();
  	}
	
  	public function export() {
		$this->load->model('user/students');
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.date_added'; 
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}
		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}
		if (isset($this->request->get['filter_city'])) {
			$filter_city = $this->request->get['filter_city'];
		} else {
			$filter_city = NULL;
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
		
		//$filter_status = 1;
		$filter_approved = 1;	
		
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_all'] = $filter_all;
		$this->data['filter_city'] = $filter_city;
		$this->data['filter_subjects'] = $filter_subjects;
		//$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;	
	
		$select = array();
		if(isset($this->request->get['student_list'])){
			$select[] = " CONCAT(c.firstname, ' ', c.lastname) AS name ";		
		}
		if(isset($this->request->get['student_emails'])){
			$select[] = " c.email ";
		}
		if(isset($this->request->get['where_heared'])){
			$select[] = " ui.referredby ";
		}
		if(isset($this->request->get['contract'])){
			$select[] = " ui.agreement ";
		}
		
		if(count($select) > 0)
			$select = implode(", ", $select);
		else
			$select = " * ";
		
		$results = $this->model_user_students->getStudents($this->data, $select);
		$arrresult = array();
		if(strstr($select,'agreement')){
			foreach($results as $result){
				$result['agreement'] = strip_tags(html_entity_decode($result['agreement'],ENT_QUOTES, 'UTF-8'));
				$arrresult[] = $result;
			}
		}else{
			$arrresult = $results;
		}
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("students.xls");
		exit;
  	}
  
  	public function insert() {
		$this->load->language('user/students');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/students');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->model_user_students->addStudent($this->request->post);
			log_activity("Student Added", "A new student added.");
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_all'])) {
				$url .= '&filter_all=' . $this->request->get['filter_all'];
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['filter_user_group_id'])) {
				$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url);
		}
    	
    	$this->getForm();
  	} 
   
  	public function view() {
		$this->load->language('user/students');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/students');
		
    
    	$this->getForm_view();
  	}   
  	
  	public function update() {
		$this->load->language('user/students');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/students');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_students->editStudent($this->request->get['user_id'], $this->request->post);
	  		log_activity("Profile Updated", "Student profile details updated.");
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_all'])) {
				$url .= '&filter_all=' . $this->request->get['filter_all'];
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['filter_user_group_id'])) {
				$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('user/students');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('user/students');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_students->deleteStudent($user_id);
			}
			log_activity("Student(s) Deleted", "Student(s) account deleted.");
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_all'])) {
				$url .= '&filter_all=' . $this->request->get['filter_all'];
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['filter_user_group_id'])) {
				$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url);
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
			$sort = 'c.date_added'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}
		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = NULL;
		}
		
		if (isset($this->request->get['filter_city'])) {
			$filter_city = $this->request->get['filter_city'];
		} else {
			$filter_city = NULL;
		}
		
		if (isset($this->request->get['filter_subjects'])) {
			$filter_subjects = $this->request->get['filter_subjects'];
		} else {
			$filter_subjects = NULL;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = NULL;
		}
		
		// set default show only approved
		$filter_approved = '1';
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}		
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['approve'] = HTTPS_SERVER . 'index.php?route=user/students/approve&token=' . $this->session->data['token'] . $url;
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/students/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/students/delete&token=' . $this->session->data['token'] . $url;

		$this->data['customers'] = array();
			
		$data = array(
			'filter_name'              => $filter_name, 
			'filter_all'              => $filter_all, 
			'filter_email'             => $filter_email,  
			'filter_status'            => $filter_status,
			'filter_city'              => $filter_city,
			'filter_subjects'          => $filter_subjects, 
			'filter_approved'          => $filter_approved, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$student_status = $this->model_user_students->getStudentStatus();
		
		$user_total = $this->model_user_students->getTotalStudents($data);
	
		$results = $this->model_user_students->getStudents($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER . 'index.php?route=user/students/view&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
			);
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/students/update&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
			);
			
			/* Softronikx Technologies */
			$action[] = array(
				'text' => $this->language->get('text_view_invoices'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] .'&src=stud'. $url
			);
			
			$action[] = array(
					'text' => 'Contract',
					'href' => HTTPS_SERVER . 'index.php?route=user/students/pdf&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
			);
			/* End of code by Softronikx Technologies */
			
			$subjects = "";			
			$query = $this->db->query("SELECT sub.subjects_name FROM " . DB_PREFIX . "subjects sub LEFT JOIN " . DB_PREFIX . "subjects_to_users stu ON (sub.subjects_id = stu.subjects_id) WHERE stu.user_id = '" . (int)$result['user_id'] . "'");			
			if($query->num_rows > 0) {
				foreach($query->rows as $each_row) {
					$subjects .= ", ".$each_row['subjects_name'];
				};	
				$subjects = substr($subjects, 2);
			}
			
//			echo $subjects."<hr />";
			
			$this->data['customers'][] = array(
				'user_id'    => $result['user_id'],
				'name'           => $result['name']."<br /><br /><small>Email:".$result['email']."</small>",
				'email'          => $result['email'],
				'city' 			 => $result['city'],
				'subjects' 		 => $subjects,
				'status'         => $student_status[$result['students_status_id']],
//				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}
		
		$this->data['student_status'] = $student_status;
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_user_id'] = $this->language->get('column_user_id');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_approve'] = $this->language->get('button_approve');
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
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
		
		$this->data['sort_user_id'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=c.user_id' . $url;
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=name' . $url;
		$this->data['sort_email'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=c.email' . $url;
		$this->data['sort_city'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=city' . $url;
		$this->data['sort_subjects'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=subjects' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=c.status' . $url;
		$this->data['sort_approved'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=c.approved' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . '&sort=c.date_added' . $url;
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
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
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_all'] = $filter_all;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_city'] = $filter_city;
		$this->data['filter_subjects'] = $filter_subjects;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_date_added'] = $filter_date_added;
		
		$this->load->model('user/user_group');
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'user/students_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	public function pdf() {
  		$this->load->language('user/students');
  	
  		$this->load->model('user/students');
  	
  		$this->data['text_enabled'] = $this->language->get('text_enabled');
  		$this->data['text_disabled'] = $this->language->get('text_disabled');
  		$this->data['text_select'] = $this->language->get('text_select');
  		$this->data['entry_username'] = $this->language->get('entry_username');
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
  	
  		$this->data['tab_general'] = $this->language->get('tab_general');
  	
  	
  		$user_info = $this->model_user_students->getStudent($this->request->get['user_id']);
  			
  		if (isset($user_info)) {
  			$this->data['username'] = $user_info['username'];
  		} else {
  			$this->data['username'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['ip'] = $user_info['ip'];
  		} else {
  			$this->data['ip'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['date_added'] = $user_info['date_added'];
  		} else {
  			$this->data['date_added'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['firstname'] = $user_info['firstname'];
  		} else {
  			$this->data['firstname'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['lastname'] = $user_info['lastname'];
  		} else {
  			$this->data['lastname'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['home_phone'] = $user_info['home_phone'];
  		} else {
  			$this->data['home_phone'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['cell_phone'] = $user_info['cell_phone'];
  		} else {
  			$this->data['cell_phone'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['email'] = $user_info['email'];
  		} else {
  			$this->data['email'] = '';
  		}
  	
  		$this->load->model('user/user_group');
  	
  		if (isset($user_info)) {
  			$this->data['user_group_id'] = $user_info['user_group_id'];
  		} else {
  			$this->data['user_group_id'] = $this->config->get('config_user_group_id');
  		}
  	
  		if (isset($user_info)) {
  			$this->data['status'] = $user_info['status'];
  		} else {
  			$this->data['status'] = 1;
  		}
  	
  		if (isset($user_info)) {
  			$this->data['address'] = $user_info['address'];
  		} else {
  			$this->data['address'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['city'] = $user_info['city'];
  		} else {
  			$this->data['city'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['state'] = $user_info['state'];
  		} else {
  			$this->data['state'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['pcode'] = $user_info['pcode'];
  		} else {
  			$this->data['pcode'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['country'] = $user_info['country'];
  		} else {
  			$this->data['country'] = '';
  		}
  	
  	
  		if (isset($user_info)) {
  			$this->data['users_note'] = $user_info['users_note'];
  		} else {
  			$this->data['users_note'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['post_secondary_education'] = $user_info['post_secondary_education'];
  		} else {
  			$this->data['post_secondary_education'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['subjects_studied'] = $user_info['subjects_studied'];
  		} else {
  			$this->data['subjects_studied'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['courses_available'] = $user_info['courses_available'];
  		} else {
  			$this->data['courses_available'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['previous_experience'] = $user_info['previous_experience'];
  		} else {
  			$this->data['previous_experience'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['cities'] = $user_info['cities'];
  		} else {
  			$this->data['cities'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['references'] = $user_info['references'];
  		} else {
  			$this->data['references'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['gender'] = $user_info['gender'];
  		} else {
  			$this->data['gender'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['certified_teacher'] = $user_info['certified_teacher'];
  		} else {
  			$this->data['certified_teacher'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['criminal_conviction'] = $user_info['criminal_conviction'];
  		} else {
  			$this->data['criminal_conviction'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['background_check'] = $user_info['background_check'];
  		} else {
  			$this->data['background_check'] = '';
  		}
  	
  		if (isset($user_info)) {
  			$this->data['agreement'] = $user_info['agreement'];
  		} else {
  			$this->data['agreement'] = '';
  		}
  	
  		$this->template = 'user/students_pdf_form.tpl';
  	
  		//$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  		//echo $this->render(TRUE);
  	
  		$content = $this->render(TRUE);
  		$html2pdf = new HTML2PDF('P','A4','fr');
  		$html2pdf->WriteHTML($content);
  		$html2pdf->Output($user_info['firstname'].'-'.$user_info['lastname'].'.pdf');
  		exit;
  	}
  	
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
    	
    	$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_parent_firstname'] = $this->language->get('entry_parent_firstname');
    	$this->data['entry_parent_lastname'] = $this->language->get('entry_parent_lastname');
    	$this->data['entry_grade_year'] = $this->language->get('entry_grade_year');
    	$this->data['entry_subjects'] = $this->language->get('entry_subjects');
    	$this->data['entry_home_phone'] = $this->language->get('entry_home_phone');
    	$this->data['entry_cell_phone'] = $this->language->get('entry_cell_phone');
    	
    	$this->data['entry_major_intersection'] = $this->language->get('entry_major_intersection');
    	$this->data['entry_school_name'] = $this->language->get('entry_school_name');
    	$this->data['entry_heard_aboutus'] = $this->language->get('entry_heard_aboutus');
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
		
		$this->data['entry_student_status'] = $this->language->get('entry_student_status');
		$this->data['entry_approved'] = $this->language->get('entry_approved');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_state'] = $this->language->get('entry_state');
		$this->data['entry_country'] = $this->language->get('entry_country');
		
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add'] = $this->language->get('button_add');
    	$this->data['button_remove'] = $this->language->get('button_remove');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');

		$this->data['token'] = $this->session->data['token'];

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
		
		
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/students/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/students/update&token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url;
		
		$all_subject_ids = array();
		$all_subjects = array();
    	if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_user_students->getStudent($this->request->get['user_id']);
      		$all_subject_ids = array_keys($this->model_user_students->getStudentSubjects($user_info['user_id']));
      		$all_subjects = $this->model_user_students->getSubjectsByGradeId($user_info['grades_id']);
      		
//    		print_r($user_info);
    	}
			
    	if (isset($this->request->post['username'])) {
      		$this->data['username'] = $this->request->post['username'];
		} elseif (isset($user_info)) { 
			$this->data['username'] = $user_info['username'];
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
		} elseif (isset($user_info)) { 
			$this->data['firstname'] = $user_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (isset($user_info)) { 
			$this->data['lastname'] = $user_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

		if (isset($this->request->post['grade_year'])) {
      		$this->data['grade_year'] = $this->request->post['grade_year'];
    	} elseif (isset($user_info)) { 
			$this->data['grade_year'] = $user_info['grades_id'];
		} else {
      		$this->data['grade_year'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['all_subject_ids'] = $all_subject_ids;
		} else {
      		$this->data['all_subject_ids'] = array();
    	}
    	
    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (isset($user_info)) { 
			$this->data['email'] = $user_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
    	
    	if (isset($this->request->post['parent_firstname'])) {
      		$this->data['parent_firstname'] = $this->request->post['parent_firstname'];
    	} elseif (isset($user_info)) { 
			$this->data['parent_firstname'] = $user_info['parents_first_name'];
		} else {
      		$this->data['parent_firstname'] = '';
    	}
    	
    	if (isset($this->request->post['parent_lastname'])) {
      		$this->data['parent_lastname'] = $this->request->post['parent_lastname'];
    	} elseif (isset($user_info)) { 
			$this->data['parent_lastname'] = $user_info['parents_last_name'];
		} else {
      		$this->data['parent_lastname'] = '';
    	}
    	
    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (isset($user_info)) { 
			$this->data['telephone'] = $user_info['home_phone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if (isset($this->request->post['cellphone'])) {
      		$this->data['cellphone'] = $this->request->post['cellphone'];
    	} elseif (isset($user_info)) { 
			$this->data['cellphone'] = $user_info['cell_phone'];
		} else {
      		$this->data['cellphone'] = '';
    	}

    	if (isset($this->request->post['address'])) {
      		$this->data['address'] = $this->request->post['address'];
    	} elseif (isset($user_info)) { 
			$this->data['address'] = $user_info['address'];
		} else {
      		$this->data['address'] = '';
    	}
    	
    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	} elseif (isset($user_info)) { 
			$this->data['city'] = $user_info['city'];
		} else {
      		$this->data['city'] = '';
    	}
    	
    	if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} elseif (isset($user_info)) { 
			$this->data['postcode'] = $user_info['pcode'];
		} else {
      		$this->data['postcode'] = '';
    	}
    	
    	if (isset($this->request->post['country'])) {
      		$this->data['country'] = $this->request->post['country'];
    	} elseif (isset($user_info)) { 
			$this->data['country'] = $user_info['country'];
		} else {
      		$this->data['country'] = '';
    	}
		
		$all_countries = $this->country($this->data['country']);
		$this->data['list_country'] = $all_countries;
    	
		if (isset($this->request->post['state'])) {
      		$this->data['state'] = $this->request->post['state'];
    	} elseif (isset($user_info)) { 
			$this->data['state'] = $user_info['state'];
		} else {
      		$this->data['state'] = '';
    	}
    	
    	$all_states = $this->zone($this->data['state']);
		$this->data['list_states'] = $all_states;
		
		
    	if (isset($this->request->post['student_note'])) {
      		$this->data['student_note'] = $this->request->post['student_note'];
    	} elseif (isset($user_info)) { 
			$this->data['student_note'] = $user_info['users_note'];
		} else {
      		$this->data['student_note'] = '';
    	}
    	
    	if (isset($this->request->post['major_intersection'])) {
      		$this->data['major_intersection'] = $this->request->post['major_intersection'];
    	} elseif (isset($user_info)) { 
			$this->data['major_intersection'] = $user_info['major_intersection'];
		} else {
      		$this->data['major_intersection'] = '';
    	}
    	
    	if (isset($this->request->post['school_name'])) {
      		$this->data['school_name'] = $this->request->post['school_name'];
    	} elseif (isset($user_info)) { 
			$this->data['school_name'] = $user_info['school'];
		} else {
      		$this->data['school_name'] = '';
    	}
    	
    	if (isset($this->request->post['heard_aboutus'])) {
      		$this->data['heard_aboutus'] = $this->request->post['heard_aboutus'];
    	} elseif (isset($user_info)) { 
			$this->data['heard_aboutus'] = $user_info['referredby'];
		} else {
      		$this->data['heard_aboutus'] = '';
    	}
    	
    	if (isset($this->request->post['student_status'])) {
      		$this->data['student_status'] = $this->request->post['student_status'];
    	} elseif (isset($user_info)) { 
			$this->data['student_status'] = $user_info['students_status_id'];
		} else {
      		$this->data['student_status'] = 1;
    	}
    	
    	if (isset($this->request->post['approved'])) {
      		$this->data['approved'] = $this->request->post['approved'];
    	} elseif (isset($user_info)) { 
			$this->data['approved'] = $user_info['approved'];
		} else {
      		$this->data['approved'] = 1;
    	}
		
    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (isset($user_info)) { 
			$this->data['status'] = $user_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
    	
    	$this->data['student_status_all'] = $this->model_user_students->getStudentStatus();
    			
		// get all grades
		$grade_years = $this->model_user_students->getGradesAndYears();
		$this->data['grade_years'] = $grade_years;		

		// get all subjects
		$this->data['all_subjects'] = $all_subjects;				
		
		$this->template = 'user/students_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
private function getForm_view() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
    	
    	$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_parent_firstname'] = $this->language->get('entry_parent_firstname');
    	$this->data['entry_parent_lastname'] = $this->language->get('entry_parent_lastname');
    	$this->data['entry_grade_year'] = $this->language->get('entry_grade_year');
    	$this->data['entry_subjects'] = $this->language->get('entry_subjects');
    	$this->data['entry_home_phone'] = $this->language->get('entry_home_phone');
    	$this->data['entry_cell_phone'] = $this->language->get('entry_cell_phone');
    	
    	$this->data['entry_major_intersection'] = $this->language->get('entry_major_intersection');
    	$this->data['entry_school_name'] = $this->language->get('entry_school_name');
    	$this->data['entry_heard_aboutus'] = $this->language->get('entry_heard_aboutus');
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
		
		$this->data['entry_student_status'] = $this->language->get('entry_student_status');
		$this->data['entry_approved'] = $this->language->get('entry_approved');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_state'] = $this->language->get('entry_state');
		$this->data['entry_country'] = $this->language->get('entry_country');
		
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add'] = $this->language->get('button_add');
    	$this->data['button_remove'] = $this->language->get('button_remove');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');

		$this->data['token'] = $this->session->data['token'];

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
		
		
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/students/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/students/update&token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url;
		
		$all_subject_ids = array();
		$all_subjects = array();
    	if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_user_students->getStudent($this->request->get['user_id']);
      		$all_subject_ids = array_keys($this->model_user_students->getStudentSubjects($user_info['user_id']));
      		$all_subjects = $this->model_user_students->getSubjectsByGradeId($user_info['grades_id']);      
      		$user_grade= $this->model_user_students->getGrade($user_info['grades_id']);      				
//    		print_r($user_info);
    	}
			
    	if (isset($user_info)) { 
			$this->data['username'] = $user_info['username'];
		} else {
      		$this->data['username'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['firstname'] = $user_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($user_info)) { 
			$this->data['lastname'] = $user_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

		if (isset($user_info)) { 
			$this->data['grade_year'] = $user_grade;
		} else {
      		$this->data['grade_year'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['all_subject_ids'] = $all_subject_ids;
		} else {
      		$this->data['all_subject_ids'] = array();
    	}
    	
    	if (isset($user_info)) { 
			$this->data['email'] = $user_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['parent_firstname'] = $user_info['parents_first_name'];
		} else {
      		$this->data['parent_firstname'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['parent_lastname'] = $user_info['parents_last_name'];
		} else {
      		$this->data['parent_lastname'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['telephone'] = $user_info['home_phone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if (isset($user_info)) { 
			$this->data['cellphone'] = $user_info['cell_phone'];
		} else {
      		$this->data['cellphone'] = '';
    	}

    	if (isset($user_info)) { 
			$this->data['address'] = $user_info['address'];
		} else {
      		$this->data['address'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['city'] = $user_info['city'];
		} else {
      		$this->data['city'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['postcode'] = $user_info['pcode'];
		} else {
      		$this->data['postcode'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['country'] = $user_info['country'];
		} else {
      		$this->data['country'] = '';
    	}
		
		$all_countries = $this->country($this->data['country']);
		$this->data['list_country'] = $all_countries;
    	
		if (isset($user_info)) { 
			$this->data['state'] = $user_info['state'];
		} else {
      		$this->data['state'] = '';
    	}
    	
    	$all_states = $this->zone($this->data['state']);
		$this->data['list_states'] = $all_states;
		
		
    	if (isset($user_info)) { 
			$this->data['student_note'] = $user_info['users_note'];
		} else {
      		$this->data['student_note'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['major_intersection'] = $user_info['major_intersection'];
		} else {
      		$this->data['major_intersection'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['school_name'] = $user_info['school'];
		} else {
      		$this->data['school_name'] = '';
    	}
    	
      if (isset($user_info)) { 
			$this->data['heard_aboutus'] = $user_info['referredby'];
		} else {
      		$this->data['heard_aboutus'] = '';
    	}
    	
    	if (isset($user_info)) { 
			$this->data['student_status'] = $user_info['students_status_id'];
		} else {
      		$this->data['student_status'] = 1;
    	}
    	
    	if (isset($user_info)) { 
			$this->data['approved'] = $user_info['approved'];
		} else {
      		$this->data['approved'] = 1;
    	}
		
    	if (isset($user_info)) { 
			$this->data['status'] = $user_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
    	
    	$this->data['student_status_all'] = $this->model_user_students->getStudentStatus();
    			
		// get all grades
		$grade_years = $this->model_user_students->getGradesAndYears();
		$this->data['grade_years'] = $grade_years;		

		// get all subjects
		$this->data['all_subjects'] = $all_subjects;				
		
		$this->template = 'user/students_view.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function subjects() {
		  $all_subjects = array();
		  
		  $this->load->model('user/students');
		  
		  $grade_id = $this->request->get['grade_id'];
		  $all_subject_ids = explode(",", $this->request->get['filter_ids']);
		  $all_subjects = $this->model_user_students->getSubjectsByGradeId($grade_id);
		  
		  $class = 'odd';
	      foreach ($all_subjects as $subject_id => $subject_name) {
	      $class = ($class == 'even' ? 'odd' : 'even'); ?>
	      <div class="<?php echo $class; ?>">
	        <?php if (in_array($subject_id, $all_subject_ids)) { ?>
	        <input type="checkbox" name="subjects[]" value="<?php echo $subject_id; ?>" checked="checked" />
	        <?php echo $subject_name; ?>
	        <?php } else { ?>
	        <input type="checkbox" name="subjects[]" value="<?php echo $subject_id; ?>" />
	        <?php echo $subject_name; ?>
	        <?php } ?>
	      </div>
	      <?php }
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
		 
	public function approve() {
		$this->load->language('user/students');
		$this->load->language('mail/customer');
    	
		if (!$this->user->hasPermission('modify', 'user/students')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['selected'])) {
				$this->load->model('user/students');			
			
				foreach ($this->request->post['selected'] as $user_id) {
					$user_info = $this->model_user_students->getStudent($user_id);
			
					if ($user_info && !$user_info['approved']) {
						$this->model_user_students->approve($user_id);
						
						$this->load->model('setting/store');
									
						$store_info = $this->model_setting_store->getStore($user_info['store_id']);
						
						if ($store_info) {
							$store_name = $store_info['name'];
							$store_url = $store_info['url'] . 'index.php?route=account/login';
						} else {
							$store_name = $this->config->get('config_name');
							$store_url = $this->config->get('config_url') . 'index.php?route=account/login';
						}
				
						$message  = sprintf($this->language->get('text_welcome'), $store_name) . "\n\n";;
						$message .= $this->language->get('text_login') . "\n";
						$message .= $store_url . "\n\n";
						$message .= $this->language->get('text_services') . "\n\n";
						$message .= $this->language->get('text_thanks') . "\n";
						$message .= $store_name;
				
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->hostname = $this->config->get('config_smtp_host');
						$mail->username = $this->config->get('config_smtp_username');
						$mail->password = $this->config->get('config_smtp_password');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->port = $this->config->get('config_smtp_port');
						$mail->timeout = $this->config->get('config_smtp_timeout');							
						$mail->setTo($user_info['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($store_name);
						$mail->setSubject(sprintf($this->language->get('text_subject'), $store_name));
						$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
						$mail->send();
					
						$this->session->data['success'] = sprintf($this->language->get('text_approved'), $user_info['firstname'] . ' ' . $user_info['lastname']);
					}
				}
			}			
		}
		
		$url = '';
	
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}
	
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
		
		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}
	
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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

		$this->redirect(HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'] . $url);
	} 
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/students')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if ((strlen(utf8_decode($this->request->post['username'])) < 1) || (strlen(utf8_decode($this->request->post['username'])) > 32)) {
      		$this->error['username'] = $this->language->get('error_username');
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
    	
		if ((strlen(utf8_decode($this->request->post['email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
    	
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
    	
    	if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'user/students')) {
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
