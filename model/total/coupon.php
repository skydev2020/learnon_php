<?php
class ModelTotalCoupon extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['coupon']) && $this->config->get('coupon_status')) {
			$this->load->model('total/coupon');
			 
			$coupon = $this->model_total_coupon->getCoupon($this->session->data['coupon']);
			
//			print_r($coupon);			
			
			if ($coupon) {
				$discount_total = 0;
				
				$package_total = $this->cart->getSubTotal();
				
//				print_r($package_total);
				
				$status = TRUE;
				$discount = 0;
				
				if ($status) {
					if ($coupon['type'] == 'F') {
						$discount = $coupon['discount'];
					} elseif ($coupon['type'] == 'P') {
						$discount = $package_total / 100 * $coupon['discount'];
					}		
				}
				
				$discount_total += $discount;
				$total_data[] = array(
        			'title'      => $coupon['name'] . ':',
	    			'text'       => '-' . $this->currency->format($discount_total),
        			'value'      => - $discount_total,
					'sort_order' => $this->config->get('coupon_sort_order')
      			);

				$total -= $discount_total;
			} 
		}
	}
	
	public function getCoupon($coupon) {
		$status = TRUE;
		
		$coupon_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($coupon) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");
			
		if ($coupon_query->num_rows) {
			
			$coupon_redeem_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_redeem_query->row['total'] >= $coupon_query->row['uses_total'])) {
				$status = FALSE;
			}
			
			if ($this->user->getId()) {
				$coupon_redeem_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND customer_id = '" . (int)$this->user->getId() . "'");
				
				if ($coupon_query->row['uses_customer'] > 0 && ($coupon_redeem_query->row['total'] >= $coupon_query->row['uses_customer'])) {
					$status = FALSE;
				}
			}
		}
		
		if ($coupon_query->num_rows && $status) {
			$coupon_data = array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
			
			return $coupon_data;
		}
	}
	
	public function redeem($coupon) {
		
	}
}
?>