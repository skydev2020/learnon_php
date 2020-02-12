<?php
class ControllerPaymentStudentPPStandard extends Controller {

	private $error;
	private $order_info;

	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('pp_standard_test')) {
    		$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$this->load->model('total/order');

		$this->language->load('payment_student/pp_standard');

		$this->data['testmode'] = $this->config->get('pp_standard_test');

		$this->data['text_testmode'] = $this->language->get('text_testmode');

		$this->data['continue'] = $this->session->data['payment_continue'];

		$this->order_info = $this->model_total_order->getOrder($this->session->data['order_id']);


		# Check for supported currency, otherwise convert to USD.
		$currencies = array('AUD','CAD','EUR','GBP','JPY','USD','NZD','CHF','HKD','SGD','SEK','DKK','PLN','NOK','HUF','CZK','ILS','MXN','MYR','BRL','PHP','TWD','THB','TRY');
		if (in_array($this->order_info['currency'], $currencies)) {
			$currency = $this->order_info['currency'];
		} else {
			$currency = 'USD';
		}


		# Get all totals and discount total
		$total = 0;
        $total_data = array();
		$taxes = $this->cart->getTaxes();

		$this->load->model('total/extension');

		$sort_order = array();

		$results = $this->model_total_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		$discount_total = 0;
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);
			$old_total = $total;
			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);

			if ($total < $old_total) {
				$discount_total += $old_total - $total;
			}
		}

		$this->data['total'] = $total;


		# Get Shipping Total
		$shipping_total = 0;
		if ($this->cart->hasShipping()) {
			$shipping_total = $this->session->data['shipping_method']['cost'];
		}


		# Get Tax Total
		$tax_total = 0;
		foreach ($taxes as $key => $value) {
			$tax_total += $value;
		}


		# Create form fields
		$this->data['fields'] = array();
		$this->data['fields']['cmd'] = '_cart';
		$this->data['fields']['upload'] = '1';


		# Get itemized product total
		$product_total = 0;
		$i = 1;
		$product = $this->cart->getProducts();
		
//		foreach ($this->cart->getProducts() as $product) {
			$price = $product['price'];
	        $this->data['fields']['item_number_' . $i . ''] = $product['model'];
            $this->data['fields']['item_name_' . $i . ''] = $product['name'];
            $this->data['fields']['amount_' . $i . ''] = $this->currency->format($price, $currency, FALSE, FALSE);

	        $product_total += ($price * $product['quantity']);
	        
//            $i++;
//        }


        # Hack to bypass paypal's discount limitation: https://www.x.com/thread/47330
        $this->data['fields']['discount_amount_cart'] = $this->currency->format($discount_total, $currency, FALSE, FALSE);
		if ($discount_total < $product_total) {
			$this->data['fields']['shipping_1'] = $this->currency->format($shipping_total, $currency, FALSE, FALSE);
			$this->data['fields']['tax_cart'] = $this->currency->format($tax_total, $currency, FALSE, FALSE);
		} elseif ($discount_total) {

			if ($shipping_total) {
				// Set Shipping as a line item to fool paypal in thinking that the subtotal is higher
				$this->data['fields']['item_number_' . $i . ''] = $this->session->data['shipping_method']['id'];
				$this->data['fields']['item_name_' . $i . ''] = $this->session->data['shipping_method']['title'];
				$this->data['fields']['amount_' . $i . ''] = $this->currency->format($shipping_total, $currency, FALSE, FALSE);
				$this->data['fields']['quantity_' . $i . ''] = 1;
				$this->data['fields']['weight_' . $i . ''] = 0;
				//$product_total += $shipping_total;
				$i++;
			}

			if ($tax_total) {
				// Set Tax as a line item to fool paypal in thinking that the subtotal is higher
				$this->data['fields']['item_number_' . $i . ''] = $this->language->get('text_tax');
				$this->data['fields']['item_name_' . $i . ''] = $this->language->get('text_tax');
				$this->data['fields']['amount_' . $i . ''] = $this->currency->format($tax_total, $currency, FALSE, FALSE);
				$this->data['fields']['quantity_' . $i . ''] = 1;
				$this->data['fields']['weight_' . $i . ''] = 0;
				//$product_total += $tax_total;
				$i++;
			}

			// Since shipping & tax are now line items, remove the actual shipping & tax value
			$this->data['fields']['shipping_1'] = '0.00';
			$this->data['fields']['tax_cart'] = '0.00';
		}


		# Get any remaining balance as a handling fee
		$remaining_total = $total - $product_total - $tax_total - $shipping_total + $discount_total;
		if ($remaining_total > 0) {
			$this->data['fields']['handling_cart'] = number_format(abs($this->currency->format($remaining_total, $currency, FALSE, FALSE)), 2, '.', '');
		}


		# Finish the rest of the form
		$this->data['fields']['business'] = $this->config->get('pp_standard_email');
		$this->data['fields']['currency_code'] = $currency;
		
		
/*		# If no shipping address, just use the billing address for both
		if (!isset($this->session->data['shipping_address_id']) && !isset($this->session->data['guest']['shipping'])) {

			// Shipping Address uses payment address
			$this->data['fields']['first_name'] = html_entity_decode($this->order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['last_name'] = html_entity_decode($this->order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['address1'] = html_entity_decode($this->order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['address2'] = html_entity_decode($this->order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['city'] = html_entity_decode($this->order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['zip'] = html_entity_decode($this->order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['country'] = $this->order_info['payment_iso_code_2'];
			if ($this->order_info['payment_iso_code_2'] == 'US') {
				$this->load->model('localisation/zone');
				$zone = $this->model_localisation_zone->getZone($this->order_info['payment_zone_id']);
				if (isset($zone['code'])) {
					$this->data['fields']['state'] = html_entity_decode($zone['code'], ENT_QUOTES, 'UTF-8');
				}
				$phone = preg_replace("/[^0-9.]/", "", html_entity_decode($this->order_info['telephone'], ENT_QUOTES, 'UTF-8'));
				$this->data['fields']['night_phone_a'] = html_entity_decode(substr($phone,0,3), ENT_QUOTES, 'UTF-8');
				$this->data['fields']['night_phone_b'] = html_entity_decode(substr($phone,3,3), ENT_QUOTES, 'UTF-8');
				$this->data['fields']['night_phone_c'] = html_entity_decode(substr($phone,6), ENT_QUOTES, 'UTF-8');
			}

		} else { // if there is a shipping address

			// Shipping Address uses shipping address
			$this->data['fields']['first_name'] = html_entity_decode($this->order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['last_name'] = html_entity_decode($this->order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['address1'] = html_entity_decode($this->order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['address2'] = html_entity_decode($this->order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['city'] = html_entity_decode($this->order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['zip'] = html_entity_decode($this->order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
			$this->data['fields']['country'] = $this->order_info['shipping_iso_code_2'];
			if ($this->order_info['shipping_iso_code_2'] == 'US') {
				$this->load->model('localisation/zone');
				$zone = $this->model_localisation_zone->getZone($this->order_info['shipping_zone_id']);
				if (isset($zone['code'])) {
					$this->data['fields']['state'] = html_entity_decode($zone['code'], ENT_QUOTES, 'UTF-8');
				}
				$phone = preg_replace("/[^0-9.]/", "", html_entity_decode($this->order_info['telephone'], ENT_QUOTES, 'UTF-8'));
				$this->data['fields']['night_phone_a'] = html_entity_decode(substr($phone,0,3), ENT_QUOTES, 'UTF-8');
				$this->data['fields']['night_phone_b'] = html_entity_decode(substr($phone,3,3), ENT_QUOTES, 'UTF-8');
				$this->data['fields']['night_phone_c'] = html_entity_decode(substr($phone,6), ENT_QUOTES, 'UTF-8');
			}

		}*/
		
		$this->data['fields']['email'] = $this->order_info['email'];
		$this->data['fields']['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($this->order_info['firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($this->order_info['lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['lc'] = $this->session->data['language'];
		$this->data['fields']['rm'] = '2';
		$this->data['fields']['charset'] = 'utf-8';

		if (!$this->config->get('pp_standard_transaction')) {
			$this->data['fields']['paymentaction'] = 'authorization';
		} else {
			$this->data['fields']['paymentaction'] = 'sale';
		}

		$this->data['fields']['return'] = HTTPS_SERVER . 'index.php?route=payment_student/pp_standard/pdt';
		$this->data['fields']['notify_url'] = HTTP_SERVER . 'index.php?route=payment_student/pp_standard/callback';

		$this->data['fields']['cancel_return'] = $this->session->data['payment_cancel'];

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$this->data['fields']['custom'] = $encryption->encrypt($this->session->data['order_id']);

		
		$this->data['back'] = $this->session->data['payment_back'];
		

		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_standard.tpl')) {
			$this->template = $this->config->get('config_template') . '/payment_student/pp_standard.tpl';
		} else {
			$this->template = 'payment_student/pp_standard.tpl';
		}

		$this->render();
	}

	public function confirm() {
		
		# If total is 0.00, bypass paypal and force completed checkout
		$this->load->model('total/order');
		$order_info = $this->model_total_order->getOrder($this->session->data['order_id']);
		
//		var_dump((float)$order_info['total']);
		
		if (!(float)$order_info['total']) {
			$this->load->model('total/order');
			$this->model_total_order->confirm($this->session->data['order_id'], $this->config->get('pp_standard_order_status_id'));
			$this->redirect(HTTPS_SERVER . 'index.php?route=payment_student/success');
		}

		# If using ajax pre-confirm, set the order to "pending" before payment is made.
		# Only use when IPN and PDT are both failing.
		# Not meant to be a solution, but a temporary workaround
		if ($this->config->get('pp_standard_ajax')) {			
			$this->load->model('total/order');
			$this->model_total_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
		}
	}

	//return URL
	public function pdt() {

		if (isset($this->request->post)) {
        	$p_msg = "DEBUG POST VARS::"; foreach($this->request->post as $k=>$v) { $p_msg .= $k."=".$v."&"; }
		}
		if (isset($this->request->get)) {
        	$g_msg = "DEBUG GET VARS::"; foreach($this->request->get as $k=>$v) { $g_msg .= $k."=".$v."&"; }
		}

		if ($this->config->get('pp_standard_debug')) {
			$this->log->write("PP_STANDARD :: PDT INIT <-- $p_msg and $g_msg");
		}

        if (!isset($this->request->get['tx']) || $this->config->get('pp_standard_pdt_token') == '') {
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/packages/success&token=' . $this->session->data['token']);
		}

        $this->load->language('payment_student/pp_standard');

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		if (isset($this->request->get['cm'])) {
			$order_id = $encryption->decrypt($this->request->get['cm']);
		} else {
			$order_id = 0;
		}

		$this->load->model('total/order');

		$this->order_info = $this->model_total_order->getOrder($order_id);

		if ($this->order_info) {
			if ($this->order_info['order_status_id'] != 0) {
			//if ($this->order_info['order_status_id'] == $this->config->get('pp_standard_order_status_id')) {
				$this->redirect(HTTPS_SERVER . 'index.php?route=student/packages/success&token=' . $this->session->data['token']);
			}
		}

         // Paypal possible values for payment_status
		$success_status = array('Completed', 'Pending', 'In-Progress', 'Processed');
		$failed_status = array('Denied', 'Expired', 'Failed');

        // read the post from PayPal system and add 'cmd'
        $request = 'cmd=_notify-synch';
        $request .= '&tx=' . $this->request->get['tx'];
        $request .= '&at=' . $this->config->get('pp_standard_pdt_token');

        if (!$this->config->get('pp_standard_test')) {
			$url = 'https://www.paypal.com/cgi-bin/webscr';
		} else {
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		if (ini_get('allow_url_fopen')) {
			$response = file_get_contents($url . '?' . $request);
		} else {
			$response = $this->file_get_contents_curl($url . '?' . $request);
		}

		if ($this->config->get('pp_standard_debug')) {
			$this->log->write("PP_STANDARD :: PDT REQ  --> $request");
			$this->log->write("PP_STANDARD :: PDT RESP <-- " . str_replace("\n", "&", $response));
		}

		$resp_array = array();

		$verified = false;

		if ($response) {

			$lines = explode("\n", $response);
			if ($lines[0] == 'SUCCESS') {
				for ($i=1; $i<(count($lines)-1); $i++){
					list($key,$val) = explode("=", $lines[$i]);
					$resp_array[urldecode($key)] = urldecode($val);
				}
			}
		}

		if (isset($resp_array['memo'])) {
			$memo = $resp_array['memo'];
		} else {
			$memo = '';
		}

		if (!$this->validate($resp_array)) {
			if ($this->order_info['order_status_id'] == '0') {
				$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $memo . "\r\n\r\n" . $this->error);
			} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
				$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'),  $this->error, FALSE);
			}
			//commented by Softronikx as blank email
			//mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), $this->error . "\r\n\r\n" . str_replace("&", "\n", $g_msg));
		}

		if (strcmp($lines[0], 'SUCCESS') == 0) {
			$verified = true;
		}

		$this->checkPaymentStatus($resp_array, $verified);
	}

	//ipn URL
	public function callback() {

		if (isset($this->request->post)) {
        	$p_msg = "DEBUG POST VARS::"; foreach($this->request->post as $k=>$v) { $p_msg .= $k."=".$v."&"; }
		}
		if (isset($this->request->get)) {
        	$g_msg = "DEBUG GET VARS::"; foreach($this->request->get as $k=>$v) { $g_msg .= $k."=".$v."&"; }
		}

		if ($this->config->get('pp_standard_debug')) {
			$this->log->write("PP_STANDARD :: IPN INIT <-- $p_msg and $g_msg");
		}

		if (isset($this->request->post['memo'])) {
			$memo = $this->request->post['memo'];
		} else {
			$memo = '';
		}

		$this->load->language('payment_student/pp_standard');

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		if (isset($this->request->post['custom'])) {
			$order_id = $encryption->decrypt($this->request->post['custom']);
		} else {
			$order_id = 0;
		}

		$this->load->model('total/order');

		$this->order_info = $this->model_total_order->getOrder($order_id);
		
		if ($this->order_info) {
			$request = 'cmd=_notify-validate';

			$get_magic_quotes_exists = false;
	        if (function_exists('get_magic_quotes_gpc')) {
	            $get_magic_quotes_exists = true;
	        }
			
			foreach ($this->request->post as $key => $value) {
				if ($get_magic_quotes_exists && get_magic_quotes_gpc() == 1) {
					$request .= '&' . $key . '=' . urlencode(stripslashes(html_entity_decode($value, ENT_COMPAT, 'utf-8')));
				} else {
					$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_COMPAT, 'utf-8'));
				}
			}

			if (!$this->config->get('pp_standard_test')) {
				$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($request)));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($ch);

			curl_close($ch);

			if ($this->config->get('pp_standard_debug')) {
				$this->log->write("PP_STANDARD :: IPN REQ  --> $request");
				$this->log->write("PP_STANDARD :: IPN RESP <-- $response");
			}

			if (!$this->validate($this->request->post)) {
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $memo . "\r\n\r\n" . $this->error);
				} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'),  $this->error, FALSE);
				}				
				//commented by Softronikx as blank email
				//mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), $this->error . "\r\n\r\n" . str_replace("&", "\n", $p_msg));
			}

			$verified = false;
			if (strcmp($response, 'VERIFIED') == 0) {
				$verified = true;
			}

			$this->checkPaymentStatus($this->request->post, $verified, false);
			
			//Below code by Softronikx Technologies - send email during IPN payments
		
			//model used to get mail format
			$this->load->model('account/student');
			
			// Set the mail format which needs to send
			$student_mail = $this->model_account_student->getMailFormat('8');
			
			$subject = $student_mail['broadcasts_subject'];
			$message = $student_mail['broadcasts_content'];
			
			/*// Here you can define keys for replace before sending mail to Student
			$replace_info = array(
							'STUDENT_NAME' => $this->request->post['firstname'].' '.$this->request->post['lastname'], 
						);
			
			foreach($replace_info as $rep_key => $rep_text) {
				$message = str_replace('@'.$rep_key.'@', $rep_text, $message);
			}*/
			
			$to_email = $this->order_info['email'];
			//echo '<br>(1)To email is: '.$to_email;
			//$to_email = 'nikhil.oza@softronikx.com';	//temporary			
			//echo '<br>(2)To email is: '.$to_email;
			
			$mail = new Mail($this);
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($to_email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
			//End of code by Softronikx Technologies to send emails
			
		}
		
		
	}

	private function checkPaymentStatus($data, $verified, $redirect=true) {

		
		if (isset($this->request->post)) {
        	$p_msg = "DEBUG POST VARS::"; foreach($this->request->post as $k=>$v) { $p_msg .= $k."=".$v."&"; }
		}
		if (isset($this->request->get)) {
        	$g_msg = "DEBUG GET VARS::"; foreach($this->request->get as $k=>$v) { $g_msg .= $k."=".$v."&"; }
		}
		

		if (isset($this->order_info['order_id'])) {
			$order_id = $this->order_info['order_id'];
		} else {
			$order_id = 0;
		}
		
		$comment = '';
		
		if ($this->config->get('pp_standard_debug')) {
			if (isset($data['pending_reason'])) {
				$comment = $data['pending_reason'];
			} elseif (isset($data['reason_code'])) {
				$comment = $data['reason_code'];
			}
		}
		
		switch($data['payment_status']){
			case 'Completed':
				if ($verified) {
					if ($this->order_info['order_status_id'] == '0') {
						$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id'), $data['payment_status']);
					} elseif (isset($data['payment_type']) && $data['payment_type'] == 'echeck') {
						$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id'), $data['payment_status'], TRUE);
					} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
						$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id'), $data['payment_status'], FALSE);
					}
				} else {
					if ($this->order_info['order_status_id'] == '0') {
						$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $data['payment_status']);
					} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
						$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'), $data['payment_status'], FALSE);
					}
					if (!isset($data['payment_type']) || (isset($data['payment_type']) && $data['payment_type'] != 'echeck')) {
						
						//commented by Softronikx as blank email
						//mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), ($this->language->get('error_verify') . "\r\n\r\n" . $p_msg . "\r\n\r\n" . $g_msg));
					}
				}
				break;
			case 'Canceled_Reversal':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_canceled_reversal'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_canceled_reversal'), $comment, FALSE);
				}
				break;
			case 'Denied':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_denied'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_denied'), $comment, FALSE);
				}
				break;
			case 'Failed':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_failed'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_failed'), $comment, FALSE);
				}
				break;
			case 'Pending':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'), $comment, TRUE);
				}
				break;
			case 'Refunded':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_refunded'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_refunded'), $comment, FALSE);
				}
				break;
			case 'Reversed':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_reversed'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_reversed'), $comment, FALSE);
				}
				break;
			default:
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_total_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_unspecified'), $comment);
				} else {
					$this->model_total_order->update($order_id, $this->config->get('pp_standard_order_status_id_unspecified'), $comment, FALSE);
				}
				break;
		}

		if ($data['payment_status'] != 'Completed') {
			if (!isset($data['payment_type']) || (isset($data['payment_type']) && $data['payment_type'] == 'echeck')) {
				//commented by Softronikx as blank email
				//mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), ($this->language->get('error_non_complete') . "\r\n\r\n" . $p_msg . "\r\n\r\n" . $g_msg));
			}
		}

		if($redirect)
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/packages/success&token=' . $this->session->data['token']);
	}

	private function file_get_contents_curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;
	}

	private function validate($data = array()) {
		$this->load->language('payment/pp_standard');

		// verify there was some response
		if (empty($data)) {
			$this->error = $this->language->get('error_no_data');
		}

		// verify totals match
		if (isset($data['cc'])) { // PDT
			$currency = $data['cc'];
		} elseif (isset($data['mc_currency'])) { // IPN
			$currency = $data['mc_currency'];
		} else { // Default
			$currency = $this->order_info['currency'];
		}

		if (isset($data['payment_gross']) && $data['payment_gross']) {
			$amount = $data['payment_gross'];
		} elseif (isset($data['mc_gross']) && $data['mc_gross']) {
			$amount = $data['mc_gross'];
		} else {
			$amount = 0;
		}

        if (isset($data['payment_status']) && $data['payment_status'] != 'Refunded' && ((float)floor($amount) != (float)floor($this->currency->format($this->order_info['total'], $currency, False, False)))) {
			$this->error = sprintf($this->language->get('error_amount_mismatch'), $amount, $this->order_info['total']);
		}

		// verify paypal email matches
		if (isset($data['receiver_email']) && strtolower($data['receiver_email']) != strtolower($this->config->get('pp_standard_email'))) {
			if (isset($data['business']) && strtolower($data['business']) != strtolower($this->config->get('pp_standard_email'))) {
				$this->error = $this->language->get('error_email_mismatch');
			}
		}

    	if (!$this->error) {
			return TRUE;
    	} else {
    		$this->log->write("PP_STANDARD :: VALIDATION FAILED : $this->error");
      		return FALSE;
    	}
	}

}
?>