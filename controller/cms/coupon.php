<?php  
class ControllerCmsCoupon extends Controller {
	private $error = array();
     
  	public function index() {
		$this->load->language('cms/coupon');
    	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/coupon');
		
		$this->getList();
  	}
  	
  	function genRandomString($length = 6) {
  		
	    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
	    $string = "";   
	
	    for ($p = 0; $p < $length; $p++) {
	        $string .= strtoupper($characters[mt_rand(0, strlen($characters))]);
	    }
	    
	    if (count($this->model_cms_coupon->getCouponByCode($string)) > 0) {
      		$string = $this->genRandomString();
    	}
	
	    return $string;
	}
  
  	public function insert() {
    	$this->load->language('cms/coupon');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/coupon');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_coupon->addCoupon($this->request->post);
			log_activity("Coupon created", "A new coupon code created.");
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('cms/coupon');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/coupon');
				
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_cms_coupon->editCoupon($this->request->get['coupon_id'], $this->request->post);
      		log_activity("Coupon updated", "Coupon details updated.");
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('cms/coupon');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('cms/coupon');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) { 
			foreach ($this->request->post['selected'] as $coupon_id) {
				$this->model_cms_coupon->deleteCoupon($coupon_id);
			}
      		log_activity("Coupon(s) Deleted", "Coupon(s) Deleted.");
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url);
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
			$sort = 'cd.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/coupon/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/coupon/delete&token=' . $this->session->data['token'] . $url;
		$this->data['coupons'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$coupon_total = $this->model_cms_coupon->getTotalCoupons();
	
		$results = $this->model_cms_coupon->getCoupons($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/coupon/update&token=' . $this->session->data['token'] . '&coupon_id=' . $result['coupon_id'] . $url
			);
						
			$this->data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
				'code'       => $result['code'],
				'discount'   => $result['discount'],
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['coupon_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}
									
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . '&sort=cd.name' . $url;
		$this->data['sort_code'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . '&sort=c.code' . $url;
		$this->data['sort_discount'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . '&sort=c.discount' . $url;
		$this->data['sort_date_start'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . '&sort=c.date_start' . $url;
		$this->data['sort_date_end'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . '&sort=c.date_end' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . '&sort=c.status' . $url;
				
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'cms/coupon_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
    	$this->data['text_percent'] = $this->language->get('text_percent');
    	$this->data['text_amount'] = $this->language->get('text_amount');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_description'] = $this->language->get('entry_description');
    	$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_logged'] = $this->language->get('entry_logged');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_product'] = $this->language->get('entry_product');
    	$this->data['entry_date_start'] = $this->language->get('entry_date_start');
    	$this->data['entry_date_end'] = $this->language->get('entry_date_end');
    	$this->data['entry_uses_total'] = $this->language->get('entry_uses_total');
		$this->data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
		$this->data['entry_status'] = $this->language->get('entry_status');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	 	
		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}		
		
		if (isset($this->error['date_start'])) {
			$this->data['error_date_start'] = $this->error['date_start'];
		} else {
			$this->data['error_date_start'] = '';
		}	
		
		if (isset($this->error['date_end'])) {
			$this->data['error_date_end'] = $this->error['date_end'];
		} else {
			$this->data['error_date_end'] = '';
		}	

		$url = '';
			
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		if (!isset($this->request->get['coupon_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/coupon/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/coupon/update&token=' . $this->session->data['token'] . '&coupon_id=' . $this->request->get['coupon_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/coupon&token=' . $this->session->data['token'] . $url;
  		
		if (isset($this->request->get['coupon_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$coupon_info = $this->model_cms_coupon->getCoupon($this->request->get['coupon_id']);
    	}
    
		if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} elseif (isset($coupon_info)) {
			$this->data['name'] = $coupon_info['name'];
		} else {
      		$this->data['name'] = '';
    	}
		
		if (isset($this->request->post['description'])) {
      		$this->data['description'] = $this->request->post['description'];
    	} elseif (isset($coupon_info)) {
			$this->data['description'] = $coupon_info['description'];
		} else {
      		$this->data['description'] = '';
    	}

    	if (isset($this->request->post['code'])) {
      		$this->data['code'] = $this->request->post['code'];
    	} elseif (isset($coupon_info)) {
			$this->data['code'] = $coupon_info['code'];
		} else {
      		$this->data['code'] = $this->genRandomString();
    	}
		
    	if (isset($this->request->post['type'])) {
      		$this->data['type'] = $this->request->post['type'];
    	} elseif (isset($coupon_info)) {
			$this->data['type'] = $coupon_info['type'];
		} else {
      		$this->data['type'] = '';
    	}
		
    	if (isset($this->request->post['discount'])) {
      		$this->data['discount'] = $this->request->post['discount'];
    	} elseif (isset($coupon_info)) {
			$this->data['discount'] = $coupon_info['discount'];
		} else {
      		$this->data['discount'] = '';
    	}
		
		if (isset($this->request->post['date_start'])) {
       		$this->data['date_start'] = $this->request->post['date_start'];
		} elseif (isset($coupon_info)) {
			$this->data['date_start'] = date('Y-m-d', strtotime($coupon_info['date_start']));
		} else {
			$this->data['date_start'] = date('Y-m-d', time());
		}

		if (isset($this->request->post['date_end'])) {
       		$this->data['date_end'] = $this->request->post['date_end'];
		} elseif (isset($coupon_info)) {
			$this->data['date_end'] = date('Y-m-d', strtotime($coupon_info['date_end']));
		} else {
			$this->data['date_end'] = date('Y-m-d', time());
		}

    	if (isset($this->request->post['uses_total'])) {
      		$this->data['uses_total'] = $this->request->post['uses_total'];
		} elseif (isset($coupon_info)) {
			$this->data['uses_total'] = $coupon_info['uses_total'];
    	} else {
      		$this->data['uses_total'] = 1;
    	}
  
    	if (isset($this->request->post['uses_customer'])) {
      		$this->data['uses_customer'] = $this->request->post['uses_customer'];
    	} elseif (isset($coupon_info)) {
			$this->data['uses_customer'] = $coupon_info['uses_customer'];
		} else {
      		$this->data['uses_customer'] = 1;
    	}
 
    	if (isset($this->request->post['status'])) { 
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (isset($coupon_info)) {
			$this->data['status'] = $coupon_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
		
		$this->template = 'cms/coupon_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'cms/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	      

  		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
    		$this->error['name'] = $this->language->get('error_name');
  		}

  		if (strlen(utf8_decode($this->request->post['description'])) < 3) {
    		$this->error['description'] = $this->language->get('error_description');
  		}


    	if ((strlen(utf8_decode($this->request->post['code'])) < 3) || (strlen(utf8_decode($this->request->post['code'])) > 10)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
    	
    	if (count($check_code = $this->model_cms_coupon->getCouponByCode($this->request->post['code'])) > 0) {
    		if($check_code['coupon_id'] != $this->request->get['coupon_id'])
      			$this->error['code'] = $this->language->get('error_code_exist');
    	}    	
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

	public function category() {
		$this->load->model('catalog/product');
		
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}
		
		$product_data = array();
		
		$results = $this->model_catalog_product->getProductsByCategoryId($category_id);
		
		foreach ($results as $result) {
			$product_data[] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model']
			);
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($product_data));
	}
	
	public function product() {
		$this->load->model('catalog/product');
		
		if (isset($this->request->post['coupon_product'])) {
			$products = $this->request->post['coupon_product'];
		} else {
			$products = array();
		}
	
		$product_data = array();
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$product_data[] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name'],
					'model'      => $product_info['model']
				);
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($product_data));
	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'cms/coupon')) {
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