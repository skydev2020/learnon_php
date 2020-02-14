<?php
class ModelTotalOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
			
		if ($order_query->num_rows) {			
			
			$order_data = $order_query->row;
			
						
			return $order_data;
		} else {
			return FALSE;	
		}
	}	
	
	public function create($data) {
		$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE date_added < '" . date('Y-m-d', strtotime('-1 year')) . "' AND order_status_id = '0'");
		
		//Delete past records for which payment was not completed.. Softronikx Technologies changed -1 month to -1 year to have longer records
		foreach ($query->rows as $result) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$result['order_id'] . "'");
		}		
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET " . 
				" customer_id = '" . (int)$data['customer_id'] .
				"', package_id = '" . (int)$data['package_id'] .    
				"', invoice_id = '" . (int)$data['invoice_id'] .
				"', invoice_prefix = '" . $this->config->get('config_invoice_prefix') .
				"', invoice_pk = '" . (int)$data['invoice_pk'] .    
				"', total_hours = '" . (float)$data['total_hours'] .    
				"', left_hours = '" . (float)$data['left_hours'] .    
				"', firstname = '" . $this->db->escape($data['firstname']) . 
				"', lastname = '" . $this->db->escape($data['lastname']) . 
				"', email = '" . $this->db->escape($data['email']) . 
				"', telephone = '" . $this->db->escape($data['telephone']) . 
				"', workphone = '" . $this->db->escape($data['workphone']) . 
				"', total = '" . (float)$data['total'] . 
				"', language_id = '" . (int)$data['language_id'] . 
				"', currency = '" . $this->db->escape($data['currency']) . 
				"', currency_id = '" . (int)$data['currency_id'] . 
				"', value = '" . (float)$data['value'] . 
				"', coupon_id = '" . (int)$data['coupon_id'] . 
				"', ip = '" . $this->db->escape($data['ip']) .  
				"', payment_method = '" . $this->db->escape($data['payment_method']) . 
				"', comment = '" . $this->db->escape($data['comment']) . 
				"', date_modified = NOW(), date_added = NOW()");

		$order_id = $this->db->getLastId();

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function confirm($order_id, $order_status_id, $comment = '') {
		$order_query = $this->db->query("SELECT *, l.filename AS filename, l.directory AS directory FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
			
			if($order_status_id == "5") {
				$this->load->model('sale/order');
	    		$order_info = $this->model_sale_order->getOrder($order_id);
	    		
	    		// check for inovice payment and update it
	    		if(!empty($order_info['invoice_pk'])) {
	    			$this->load->model('cms/invoices');
					$invoice_data = $this->model_cms_invoices->getInvoice($order_info['invoice_pk']);
					
					$invoice_data['invoice_status'] = "Paid";
					$invoice_data['paid_amount'] = $invoice_data['total_amount'];
		    		$invoice_data['is_locked'] = '1';
					$invoice_data['pay_date'] = date("Y-m-d H:i:s");
					$invoice_data['balance_amount'] = $invoice_data['total_amount']-$invoice_data['paid_amount'];
		    		
		    		$this->model_sale_order->updateInvoice($invoice_data['invoice_id'], $invoice_data);		    			
	    		}
	    		
	    		
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
			
			/*
			$language = new Language($order_query->row['directory']);
			$language->load($order_query->row['filename']);
			$language->load('mail/order_confirm');
			
			$this->load->model('localisation/currency');
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			
			$subject = sprintf($language->get('text_subject'), $order_query->row['store_name'], $order_id);
			
			// HTML Mail
			$template = new Template();
			
			$template->data['title'] = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
			
			$template->data['text_greeting'] = sprintf($language->get('text_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['text_order_detail'] = $language->get('text_order_detail');
			$template->data['text_order_id'] = $language->get('text_order_id');
			$template->data['text_invoice'] = $language->get('text_invoice');
			$template->data['text_date_added'] = $language->get('text_date_added');
			$template->data['text_telephone'] = $language->get('text_telephone');
			$template->data['text_email'] = $language->get('text_email');
			$template->data['text_ip'] = $language->get('text_ip');
			$template->data['text_fax'] = $language->get('text_fax');		
			$template->data['text_shipping_address'] = $language->get('text_shipping_address');
			$template->data['text_payment_address'] = $language->get('text_payment_address');
			$template->data['text_shipping_method'] = $language->get('text_shipping_method');
			$template->data['text_payment_method'] = $language->get('text_payment_method');
			$template->data['text_comment'] = $language->get('text_comment');
			$template->data['text_powered_by'] = $language->get('text_powered_by');
			
			$template->data['column_product'] = $language->get('column_product');
			$template->data['column_model'] = $language->get('column_model');
			$template->data['column_quantity'] = $language->get('column_quantity');
			$template->data['column_price'] = $language->get('column_price');
			$template->data['column_total'] = $language->get('column_total');
					
			$template->data['order_id'] = $order_id;
			$template->data['customer_id'] = $order_query->row['customer_id'];	
			$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_query->row['date_added']));    	
			$template->data['logo'] = 'cid:' . basename($this->config->get('config_logo'));
			$template->data['store_name'] = $order_query->row['store_name'];
			$template->data['address'] = nl2br($this->config->get('config_address'));
			$template->data['telephone'] = $this->config->get('config_telephone');
			$template->data['fax'] = $this->config->get('config_fax');
			$template->data['email'] = $this->config->get('config_email');
			$template->data['store_url'] = $order_query->row['store_url'];
			$template->data['invoice'] = $order_query->row['store_url'] . 'index.php?route=account/invoice&order_id=' . $order_id;
			$template->data['firstname'] = $order_query->row['firstname'];
			$template->data['lastname'] = $order_query->row['lastname'];
			$template->data['shipping_method'] = $order_query->row['shipping_method'];
			$template->data['payment_method'] = $order_query->row['payment_method'];
			$template->data['customer_email'] = $order_query->row['email'];
			$template->data['customer_telephone'] = $order_query->row['telephone'];
			$template->data['customer_ip'] = $order_query->row['ip'];
			$template->data['comment'] = nl2br($order_query->row['comment']);
			
			if ($comment) {
				$template->data['comment'] .= ('<br /><br />' . nl2br($comment)); 
			}
	
			$template->data['totals'] = $order_total_query->rows;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'mail/order_confirm.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . 'mail/order_confirm.tpl');
			} else {
				$html = $template->fetch('mail/order_confirm.tpl');
			}

			// Text Mail
			$text  = sprintf($language->get('text_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
			$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
			$text .= $language->get('text_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
			$text .= $language->get('text_product') . "\n";
			
			foreach ($order_product_query->rows as $result) {
				$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
				foreach ($order_option_query->rows as $option) {
					$text .= chr(9) . '-' . $option['name'] . ' ' . $option['value'] . "\n";
				}
			}
			
			$text .= "\n";
			
			$text .= $language->get('text_total') . "\n";
			
			foreach ($order_total_query->rows as $result) {
				$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}			
			
			$order_total = $result['text'];
			
			$text .= "\n";
			
			if ($order_query->row['customer_id']) {
				$text .= $language->get('text_invoice') . "\n";
				$text .= $order_query->row['store_url'] . 'index.php?route=account/invoice&order_id=' . $order_id . "\n\n";
			}
		
			if ($order_download_query->num_rows) {
				$text .= $language->get('text_download') . "\n";
				$text .= $order_query->row['store_url'] . 'index.php?route=account/download' . "\n\n";
			}
			
			if ($order_query->row['comment'] != '') {
				$comment = ($order_query->row['comment'] .  "\n\n" . $comment);
			}
			
			if ($comment) {
				$text .= $language->get('text_comment') . "\n\n";
				$text .= $comment . "\n\n";
			}
			
			$text .= $language->get('text_footer');
						
			$mail = new Mail($this); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($order_query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_query->row['store_name']);
			$mail->setSubject($subject);
			$mail->setHtml($html);
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
			$mail->send();
			
			if ($this->config->get('config_alert_mail')) {
				
				// HTML
				$template->data['text_greeting'] = $language->get('text_received') . "\n\n";
				$template->data['invoice'] = '';
				$template->data['text_invoice'] = '';
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'mail/order_confirm.tpl')) {
					$html = $template->fetch($this->config->get('config_template') . 'mail/order_confirm.tpl');
				} else {
					$html = $template->fetch('mail/order_confirm.tpl');
				}
				
				$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id . ' (' . $order_total . ')');
				
				$mail->setSubject($subject);
				$mail->setTo($this->config->get('config_email'));
				$mail->setHtml($html);
				$mail->send();
				
				// Send to additional alert emails
				$emails = explode(',', $this->config->get('config_alert_emails'));
				foreach ($emails as $email) {
					if (strlen($email) > 0 && preg_match(EMAIL_PATTERN, $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}		
		*/
		}
	}
	
	public function update($order_id, $order_status_id, $comment = '', $notify = FALSE) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id > '0'");
		
		if ($order_query->num_rows) {
			
			if($order_status_id == "5") {
				$this->load->model('sale/order');
	    		$order_info = $this->model_sale_order->getOrder($order_id);
	    		
	    		// check for inovice payment and update it
	    		if(!empty($order_info['invoice_pk'])) {
	    			$this->load->model('cms/invoices');
					$invoice_data = $this->model_cms_invoices->getInvoice($order_info['invoice_pk']);
					
					$invoice_data['invoice_status'] = "Paid";
					$invoice_data['paid_amount'] = $invoice_data['total_amount'];
		    		$invoice_data['is_locked'] = '1';
					$invoice_data['pay_date'] = date("Y-m-d H:i:s");
					$invoice_data['balance_amount'] = $invoice_data['total_amount']-$invoice_data['paid_amount'];
		    		
		    		$this->model_sale_order->updateInvoice($invoice_data['invoice_id'], $invoice_data);		    			
	    		}
	    		
	    		
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
	
			/*
			if ($notify) {
				$language = new Language($order_query->row['directory']);
				$language->load($order_query->row['filename']);
				$language->load('mail/order_update');
			
				$subject = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
	
				$message  = $language->get('text_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
				
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";
				}
				
				$message .= $language->get('text_invoice') . "\n";
				$message .= $order_query->row['store_url'] . 'index.php?route=account/invoice&order_id=' . $order_id . "\n\n";
					
				if ($comment) { 
					$message .= $language->get('text_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}
					
				$message .= $language->get('text_footer');

				$mail = new Mail($this);
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($order_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($order_query->row['store_name']);
				$mail->setSubject($subject);
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
			}
			*/
		}
	}
}
?>