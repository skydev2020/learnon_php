<?php 
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load->language('common/header');

		$this->data['title'] = $this->document->title;
		$this->data['base'] = (HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER;
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');	
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_backup'] = $this->language->get('text_backup');
		$this->data['text_catalog'] = $this->language->get('text_catalog');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_coupon'] = $this->language->get('text_coupon');
		$this->data['text_currency'] = $this->language->get('text_currency');			
		
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_package'] = $this->language->get('text_package');
		$this->data['text_essay'] = $this->language->get('text_essay');
		$this->data['text_student'] = $this->language->get('text_student');
		$this->data['text_tutor'] = $this->language->get('text_tutor');
		$this->data['text_tutor_assignment'] = $this->language->get('text_tutor_assignment');
		$this->data['text_student_assignment'] = $this->language->get('text_student_assignment');
		$this->data['text_session'] = $this->language->get('text_session');
		$this->data['text_cms'] = $this->language->get('text_cms');
		$this->data['text_work'] = $this->language->get('text_view_work');
		
		$this->data['text_subjects'] = $this->language->get('text_subjects');
		$this->data['text_grades'] = $this->language->get('text_grades');
		
		$this->data['text_customer'] = $this->language->get('text_customer');		
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_error_log'] = $this->language->get('text_error_log');
		$this->data['text_extension'] = $this->language->get('text_extension');
		$this->data['text_feed'] = $this->language->get('text_feed');
		$this->data['text_front'] = $this->language->get('text_front');
		$this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$this->data['text_dashboard'] = $this->language->get('text_dashboard');
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_email_templates'] = $this->language->get('text_email_templates');
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_logmail'] = $this->language->get('text_logmail');
		$this->data['text_logactivity'] = $this->language->get('text_logactivity');
		$this->data['text_language'] = $this->language->get('text_language');
      	$this->data['text_localisation'] = $this->language->get('text_localisation');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_expenses'] = $this->language->get('text_expenses');
		$this->data['text_income'] = $this->language->get('text_income'); //Softronikx Technologies
		$this->data['text_student_packages'] = $this->language->get('text_student_packages'); //Softronikx Technologies
		$this->data['text_payment'] = $this->language->get('text_payment');		
		$this->data['text_product'] = $this->language->get('text_product'); 
		$this->data['text_reports'] = $this->language->get('text_reports');
		$this->data['text_view_monthly_data'] = $this->language->get('text_view_monthly_data');
		
		$this->data['text_tutor_report'] = "Tutor Report";
		$this->data['text_student_report'] = "Student Report";
		
		$this->data['text_report_purchased'] = $this->language->get('text_report_purchased');     		
		$this->data['text_report_sale'] = $this->language->get('text_report_sale');
      	$this->data['text_report_viewed'] = $this->language->get('text_report_viewed');
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_support'] = $this->language->get('text_support'); 
		$this->data['text_shipping'] = $this->language->get('text_shipping');		
     	$this->data['text_setting'] = $this->language->get('text_setting');
		$this->data['text_stock_status'] = $this->language->get('text_stock_status');
		$this->data['text_system'] = $this->language->get('text_system');
		$this->data['text_tax_class'] = $this->language->get('text_tax_class');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_user_group'] = $this->language->get('text_user_group');
		$this->data['text_users'] = $this->language->get('text_users');
      	$this->data['text_documentation'] = $this->language->get('text_documentation');
      	$this->data['text_weight_class'] = $this->language->get('text_weight_class');
		$this->data['text_length_class'] = $this->language->get('text_length_class');
		$this->data['text_opencart'] = $this->language->get('text_opencart');
      	$this->data['text_zone'] = $this->language->get('text_zone');
		$this->data['text_billing'] = $this->language->get('text_billing');
		$this->data['text_invoices'] = $this->language->get('text_invoices');
		$this->data['text_paycheques'] = $this->language->get('text_paycheques');
		$this->data['text_resources'] = $this->language->get('text_resources');
		$this->data['text_rejected_tutors'] = $this->language->get('text_rejected_tutors');
		$this->data['text_notify'] = $this->language->get('text_notify');
		$this->data['text_csv'] = $this->language->get('text_csv');
		$this->data['text_base_invoice_rates'] = $this->language->get('text_base_invoice_rates');
		
		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->data['logged'] = '';
			
			$this->data['home'] = HTTPS_SERVER . 'index.php?route=common/login';
		} else {
			$this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getFirstName(), $this->user->getUserGroup());
			$this->data['reports'] = HTTPS_SERVER . 'index.php?route=user/report_cards&token=' . $this->session->data['token'];
			$this->data['view_monthly_data'] = HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'];
			$this->data['view_tutor_report'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/tutorreport&token=' . $this->session->data['token'];
			$this->data['view_student_report'] = HTTPS_SERVER . 'index.php?route=report/monthlydata/studentreport&token=' . $this->session->data['token'];
			$this->data['home'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token']; 
			
			$this->data['backup'] = HTTPS_SERVER . 'index.php?route=tool/backup&token=' . $this->session->data['token'];
			$this->data['category'] = HTTPS_SERVER . 'index.php?route=catalog/category&token=' . $this->session->data['token'];
			$this->data['country'] = HTTPS_SERVER . 'index.php?route=localisation/country&token=' . $this->session->data['token'];
			$this->data['currency'] = HTTPS_SERVER . 'index.php?route=localisation/currency&token=' . $this->session->data['token'];
			
			$this->data['account'] = HTTPS_SERVER . 'index.php?route=user/profile&token=' . $this->session->data['token'];
			$this->data['packages'] = HTTPS_SERVER . 'index.php?route=cms/packages&token=' . $this->session->data['token'];
			$this->data['essays'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'];			
			$this->data['students'] = HTTPS_SERVER . 'index.php?route=user/students&token=' . $this->session->data['token'];
			$this->data['tutors'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'];
			$this->data['tutor_assignment'] = HTTPS_SERVER . 'index.php?route=tutor/assignment&token=' . $this->session->data['token'];
			$this->data['student_assignment'] = HTTPS_SERVER . 'index.php?route=student/assignment&token=' . $this->session->data['token'];
			$this->data['sessions'] = HTTPS_SERVER . 'index.php?route=user/sessions&token=' . $this->session->data['token'];
			//softronikx technologies
			$this->data['student_packages'] = HTTPS_SERVER . 'index.php?route=student/packages/packages&token=' . $this->session->data['token'];
			
			$this->data['subjects'] = HTTPS_SERVER . 'index.php?route=cms/subjects&token=' . $this->session->data['token'];			
			$this->data['grades'] = HTTPS_SERVER . 'index.php?route=cms/grades&token=' . $this->session->data['token'];			
			
			$this->data['cms'] = 'javascript:void(0);';			
			$this->data['email_templates'] = HTTPS_SERVER . 'index.php?route=cms/email_templates&token=' . $this->session->data['token'];
			$this->data['information'] = HTTPS_SERVER . 'index.php?route=cms/information&token=' . $this->session->data['token'];
			$this->data['coupon'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'];
			$this->data['logmail'] = HTTPS_SERVER . 'index.php?route=cms/logmail&token=' . $this->session->data['token'];
			$this->data['logactivity'] = HTTPS_SERVER . 'index.php?route=cms/logactivity&token=' . $this->session->data['token'];
												
			$this->data['customer'] = HTTPS_SERVER . 'index.php?route=sale/customer&token=' . $this->session->data['token'];			
			$this->data['customer_group'] = HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'];
			
			$this->data['download'] = HTTPS_SERVER . 'index.php?route=catalog/download&token=' . $this->session->data['token'];
			$this->data['error_log'] = HTTPS_SERVER . 'index.php?route=tool/error_log&token=' . $this->session->data['token'];
			$this->data['feed'] = HTTPS_SERVER . 'index.php?route=extension/feed&token=' . $this->session->data['token'];	
			$this->data['resources'] = 'http://www.learnon.ca/tutor_help.html';	
			$this->data['tutor_rejected'] = HTTPS_SERVER . 'index.php?route=user/tutors/rejected&token=' . $this->session->data['token'];	
			
			$this->data['stores'] = array();
			
			$this->load->model('setting/store');
			
			$results = $this->model_setting_store->getStores();
			
			foreach ($results as $result) {
				$this->data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
			
			$this->data['geo_zone'] = HTTPS_SERVER . 'index.php?route=localisation/geo_zone&token=' . $this->session->data['token'];
			$this->data['language'] = HTTPS_SERVER . 'index.php?route=localisation/language&token=' . $this->session->data['token'];
			$this->data['logout'] = HTTPS_SERVER . 'index.php?route=common/logout&token=' . $this->session->data['token'];
			$this->data['contact'] = HTTPS_SERVER . 'index.php?route=sale/contact&token=' . $this->session->data['token'];
			$this->data['notify'] = HTTPS_SERVER . 'index.php?route=sale/notify&token=' . $this->session->data['token'];
			$this->data['manufacturer'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'];
			$this->data['module'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];
			$this->data['order'] = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'];
			$this->data['order_status'] = HTTPS_SERVER . 'index.php?route=localisation/order_status&token=' . $this->session->data['token'];
			$this->data['payment'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
			$this->data['expenses'] = HTTPS_SERVER . 'index.php?route=cms/expenses&token=' . $this->session->data['token'];
			$this->data['income'] = HTTPS_SERVER . 'index.php?route=cms/expenses/income&token=' . $this->session->data['token']; //Softronikx Technologies
			$this->data['payment_process'] = HTTPS_SERVER . 'index.php?route=cms/payment&token=' . $this->session->data['token'];
			$this->data['product'] = HTTPS_SERVER . 'index.php?route=catalog/product&token=' . $this->session->data['token'];
			$this->data['report_purchased'] = HTTPS_SERVER . 'index.php?route=report/purchased&token=' . $this->session->data['token'];
			$this->data['report_sale'] = HTTPS_SERVER . 'index.php?route=report/sale&token=' . $this->session->data['token'];
			$this->data['report_viewed'] = HTTPS_SERVER . 'index.php?route=report/viewed&token=' . $this->session->data['token'];
			$this->data['review'] = HTTPS_SERVER . 'index.php?route=catalog/review&token=' . $this->session->data['token'];
			$this->data['shipping'] = HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'];
			$this->data['setting'] = HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'];
			$this->data['store'] = HTTP_CATALOG;
			$this->data['stock_status'] = HTTPS_SERVER . 'index.php?route=localisation/stock_status&token=' . $this->session->data['token'];
			$this->data['tax_class'] = HTTPS_SERVER . 'index.php?route=localisation/tax_class&token=' . $this->session->data['token'];
			$this->data['total'] = HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'];
			$this->data['user'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'];
			$this->data['user_group'] = HTTPS_SERVER . 'index.php?route=user/user_permission&token=' . $this->session->data['token'];
			$this->data['weight_class'] = HTTPS_SERVER . 'index.php?route=localisation/weight_class&token=' . $this->session->data['token'];
			$this->data['length_class'] = HTTPS_SERVER . 'index.php?route=localisation/length_class&token=' . $this->session->data['token'];
			$this->data['zone'] = HTTPS_SERVER . 'index.php?route=localisation/zone&token=' . $this->session->data['token'];
			$this->data['paycheques'] = HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'];
			$this->data['invoices'] = HTTPS_SERVER . 'index.php?route=cms/invoices&token=' . $this->session->data['token'];
			$this->data['csv'] = HTTPS_SERVER . 'index.php?route=cms/expenses/csv&token=' . $this->session->data['token'];
			
			$this->data['base_invoice_rates'] = HTTPS_SERVER . 'index.php?route=cms/expenses/base_invoice_rates&token=' . $this->session->data['token'];
		
		}
		
		$this->id       = 'header';
		
		$this->data['is_parent'] = 1;
		$group_id = isset($this->session->data['group_id'])?$this->session->data['group_id']:0;

		// switching the header template according to the user group		
		switch($group_id) {
		
			case '1':
				
				$this->data['text_dashboard'] = $this->language->get('text_student_dashboard');
				$this->data['text_account'] = $this->language->get('text_student_account');
				$this->data['text_mytutors'] = $this->language->get('text_student_mytutors');
				$this->data['text_invoice'] = $this->language->get('text_student_invoice');				
				$this->data['text_children'] = $this->language->get('text_student_children');
				$this->data['text_package'] = $this->language->get('text_student_package');
				$this->data['text_essay'] = $this->language->get('text_student_essay');
				$this->data['text_session'] = $this->language->get('text_student_session'); 
				$this->data['text_report'] = $this->language->get('text_student_reports');
				$this->data['text_help'] = $this->language->get('text_student_help');
				
				$this->data['dashboard'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'];
				$this->data['account'] = HTTPS_SERVER . 'index.php?route=student/profile/update&token=' . $this->session->data['token'].'&user_id='.$this->session->data['user_id'];
				$this->data['mytutors'] = HTTPS_SERVER . 'index.php?route=student/mytutors&token=' . $this->session->data['token'];				
				$this->data['invoices'] = HTTPS_SERVER . 'index.php?route=student/invoice&token=' . $this->session->data['token'];
				$this->data['childrens'] = HTTPS_SERVER . 'index.php?route=student/childrens&token=' . $this->session->data['token'];
				$this->data['packages'] = HTTPS_SERVER . 'index.php?route=student/packages&token=' . $this->session->data['token'];
				$this->data['essays'] = HTTPS_SERVER . 'index.php?route=student/essays&token=' . $this->session->data['token'];
				$this->data['sessions'] = HTTPS_SERVER . 'index.php?route=student/sessions&token=' . $this->session->data['token'];
				$this->data['reports'] = HTTPS_SERVER . 'index.php?route=student/report_cards&token=' . $this->session->data['token'];
				$this->data['helps'] = HTTPS_SERVER . 'index.php?route=cms/information/student&token=' . $this->session->data['token'];
				$this->data['token'] = $this->session->data['token'];
				
				$this->load->model('student/childrens');
				$child_info = $this->model_student_childrens->getStudent($this->session->data['user_id']);				  		
				$parent_info = $this->model_student_childrens->getStudent($child_info['parent_id']);
				$this->data['parent_id'] = $child_info["parent_id"];
				if($parent_info) {				
					$data = array(
						'user_id' => $parent_info["user_id"],
						'sort'   => "name",
						'order'  => "ASC",
					);
					$results = $this->model_student_childrens->getStudentsByUserid($data);
				} else {
					$data = array(
						'sort'   => "name",
						'order'  => "ASC",
					);
					$results = $this->model_student_childrens->getStudents($data);
				}
				
				$redirect = $this->request->get['route'];
				$switch_str = "";
				if (count($results)>0) {
					if($parent_info) {
						$switch_str .= "<option value='index.php?route=student/childrens/activeParent&redirect=".$redirect."'>Parent Profile</option>";					}
				}
				foreach ($results as $result) {
					$switch_str .= "<option value='index.php?route=student/childrens/changeProfile&redirect=".$redirect."&user_id=".$result["user_id"]."'>".$result['name']."</option>";
				}
				
				$count = 0;
				str_replace("/","/", $redirect, $count);
 				
				if($count == 1 || $redirect == "student/profile/update")
					$this->data['switch_str'] = $switch_str;
				else
					$this->data['switch_str'] = "";
				
				$this->template = 'common/header_student.tpl';
			break;
			case '2':
				$this->data['text_dashboard'] = $this->language->get('text_tutor_dashboard');
				$this->data['text_account'] = $this->language->get('text_tutor_account');
				$this->data['text_payrecords'] = $this->language->get('text_payrecords');				
				$this->data['text_tutor_students'] = $this->language->get('text_tutor_students');
				$this->data['text_package'] = $this->language->get('text_tutor_package');
				$this->data['text_essay'] = $this->language->get('text_tutor_essay');
				$this->data['text_session'] = $this->language->get('text_tutor_session'); 
				
				$this->data['text_report'] = $this->language->get('text_tutor_reports');
				$this->data['text_help'] = $this->language->get('text_tutor_help');
				
				$this->data['dashboard'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'];
				$this->data['account'] = HTTPS_SERVER . 'index.php?route=tutor/profile&token=' . $this->session->data['token'];
				$this->data['paycheque'] = HTTPS_SERVER . 'index.php?route=tutor/paycheque&token=' . $this->session->data['token'];
				$this->data['assignment'] = HTTPS_SERVER . 'index.php?route=tutor/mystudents&token=' . $this->session->data['token'];
				$this->data['packages'] = HTTPS_SERVER . 'index.php?route=tutor/packages&token=' . $this->session->data['token'];
				$this->data['essays'] = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'];
				$this->data['sessions'] = HTTPS_SERVER . 'index.php?route=tutor/sessions&token=' . $this->session->data['token'];
				$this->data['work'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'].'&user_id='.$this->session->data['user_id'];
				$this->data['reports'] = HTTPS_SERVER . 'index.php?route=tutor/report_cards&token=' . $this->session->data['token'];
				$this->data['helps'] = HTTPS_SERVER . 'index.php?route=cms/information/tutor&token=' . $this->session->data['token'];
				
				$this->template = 'common/header_tutor.tpl';
			break;
			case '3':
				$this->template = 'common/header_manager.tpl';
				$this->data['text_report'] = $this->language->get('text_admin_reports');
				$this->data['reports'] = HTTPS_SERVER . 'index.php?route=user/report_cards&token=' . $this->session->data['token'];
				$this->data['helps'] = HTTPS_SERVER . 'index.php?route=cms/information/manager&token=' . $this->session->data['token'];
			break;
			case '4':
				$this->template = 'common/header_admin.tpl';
				$this->data['text_report'] = $this->language->get('text_admin_reports');
				$this->data['reports'] = HTTPS_SERVER . 'index.php?route=user/report_cards&token=' . $this->session->data['token'];
				$this->data['helps'] = HTTPS_SERVER . 'index.php?route=cms/information/administrator&token=' . $this->session->data['token'];
			break;
			case '5':
				$this->template = 'common/header.tpl';
				$this->data['text_report'] = $this->language->get('text_admin_reports');
				$this->data['reports'] = HTTPS_SERVER . 'index.php?route=user/report_cards&token=' . $this->session->data['token'];
				$this->data['helps'] = HTTPS_SERVER . 'index.php?route=cms/information/administrator&token=' . $this->session->data['token'];
			break;
			default:
				$this->template = 'common/header.tpl';		
				$this->data['text_report'] = $this->language->get('text_admin_reports');	
			break;
		};
		
		$this->render();
	}
}
?>