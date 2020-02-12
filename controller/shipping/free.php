<?php
class ControllerShippingFree extends Controller {
	private $error = array();

	public function index() {
		$this->data = array_merge($this->data, $this->load->language('shipping/free'));

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if (isset($this->request->post['free_product'])) {
				$this->request->post['free_product'] = serialize($this->request->post['free_product']);
			}

			$this->model_setting_setting->editSetting('free', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token']);
		}

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=shipping/free&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=shipping/free&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'];

		$fields = array(
			'free_total',
			'free_geo_zone_id',
			'free_status',
			'free_inclusive',
			'free_sort_order'
		);
		
		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} else {
				$this->data[$field] = $this->config->get($field);
			}
		}
		
		if (isset($this->request->post['free_product'])) {
      		$this->data['free_product'] = $this->request->post['free_product'];
    	} elseif ($this->config->get('free_product')) {
      		$this->data['free_product'] = unserialize($this->config->get('free_product'));
    	} else {
			$this->data['free_product'] = array();
		}

		$this->load->model('catalog/category');

		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->template = 'shipping/free.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
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

		if (isset($this->request->post['free_product'])) {
			$products = $this->request->post['free_product'];
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

	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/free')) {
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