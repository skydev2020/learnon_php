<?php if($is_print) {
		echo $header;
	} else { 
?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<? } ?>

<?php foreach ($results as $result) {
		$invoice_details = $this->model_student_invoice->getInvoiceDetails($result['invoice_id']);
//		print_r($invoice_details['sessions']);
		$all_sessions = $invoice_details['sessions'];
		
		foreach($all_sessions as $each_session) {
			$invoice_details[] = array (
				'tutor_name'    => $each_session['tutor_name'],
				'date'    => date("M d", strtotime($each_session['session_date'])),
				'duration'    => $duration_array[$each_session['session_duration']],
				'rate'    => '$ '.$each_session['base_invoice']." /hours ",
				'total'    => '$ '.round(($each_session['session_duration'] * $each_session['base_invoice']), 2),
			);	
		}
	
		$package_details = array();
		
		$student_log_data = unserialize($result['log_data']);
		//print_r($student_log_data);
//		$package_details = $student_log_data['student_packages'];
		if(isset($student_log_data['student_packages']))
		foreach($student_log_data['student_packages'] as $each_package) {
			
			$left_hours = $each_package['left_hours'] - $each_package['deduct_hours']; 
						
			$package_details[] = array(
				'package_name' => $each_package['package_name'], 
				'total_hours' => $each_package['total_hours'], 
				'deduct_hours' => $each_package['deduct_hours'], 
				'left_hours' => $left_hours
			);	
		}
		
		
		$package_details = $package_details;
		
		$paynow = "javascript:void(0);";
		
		$student_info = $this->model_student_profile->getStudent($result['student_id']);
		//print_r($student_info);
		$student_info = array(
				'parent_name'    => $student_info['parents_first_name'].' '.$student_info['parents_last_name'],
				'student_name'    => $student_info['firstname'].' '.$student_info['lastname'],
				'street_address'    => $student_info['address'],
				'city'    => $student_info['city'],
				'state'    => $student_info['state'],
				'postcode'    => $student_info['pcode']
			);
		
		if(count($result) > 0) {
			$invoice_info = array(
				'invoice_num'    => $result['invoice_prefix']."-".$result['invoice_num'],
				'total_hours'    => $result['total_hours'],
				'invoice_date'           => date("F Y", strtotime($result['invoice_date'])),
				'send_date'           => date("F d, Y", strtotime($result['send_date'])),
				'total_amount'          => $result['total_amount'],
				'paid_amount'           => $result['paid_amount'],
				'balance_amount'           => $result['balance_amount'],
				'invoice_notes'           => nl2br($result['invoice_notes']),
				'num_of_sessions'     => $result['num_of_sessions']
			);			
		} else {
			$invoice_info = array(
				'invoice_num'    => '-',
				'total_hours'    => '-',
				'invoice_date'           => '-',
				'send_date'           => '-',
				'total_amount'          => '-',
				'paid_amount'           => '-',
				'balance_amount'           => '-',
				'invoice_notes'           => '-',
				'num_of_sessions'     => '-'
			);			
		}
		
?>
<div class="box" style="border-bottom:1px solid;padding-bottom:5px;page-break-after: always">
  
<!--<div class="left"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
  </div> 
  <div class="right"></div>-->

  <div class="top_box1">
  		<h1>TUTORING INVOICE</h1><br />

  		<h2 style="font-weight: bold">INVOICE # <?=$invoice_info['invoice_num']?><br />

		DATE ISSUED: <?=$invoice_info['send_date']?><br />

		TUTORING SERVICES FOR MONTH OF: <?=$invoice_info['invoice_date']?></h2>
  </div>
  
    <div class="top_box2">
  		
  		<h2>Bill To:</h2>
		
        <p><?=$student_info['parent_name']?><br />
		<?=$student_info['student_name']?><br />
		<?=$student_info['street_address']?><br />
		<?=$student_info['city']?>, <?=$student_info['state']?><br />
		<?=$student_info['postcode']?>
		</p>
  </div>
  <?php if(count($package_details) > 0) { ?>  
  <table border="1" cellspacing="0" class="form1" width="100%" bordercolor="#000" style="border-collapse:collapse;">
    <tr class="clr">
        <td class="thead">PACKAGE NAME</td>
        <td class="thead">TOTAL HOURS</td>
        <td class="thead">DEDUCT HOURS</td>
        <td class="thead">LEFT HOURS</td>
    </tr>
	<?php foreach($package_details as $each_row) { ?>
	<tr>
        <td class="lt"><?=$each_row['package_name']?></td>
      	<td class="cen"><?=$each_row['total_hours']?></td>
        <td class="rt"><?=$each_row['deduct_hours']?></td>
        <td class="rt"><?=$each_row['left_hours']?></td>
    </tr>
	<?php } ?>
    </table>
	<?php } ?>
  <table border="1" cellspacing="0" class="form1" width="100%" bordercolor="#000" style="border-collapse:collapse;">
    <tr class="clr">
        <td class="thead">TUTOR</td>
        <td class="thead">DATE</td>
        <td class="thead">DURATION</td>
  		<?php if(isset($show_minimum_time) && $show_minimum_time==TRUE){?>
  			<td class="thead">DURATION CHARGED</td>
  		<?php } ?>
        <td class="thead">SESSION RATE</td>
        <td class="thead">TOTAL</td>
    </tr>
	<?php foreach($invoice_details as $each_row) { ?>
	<tr>
        <td class="lt"><?=$each_row['tutor_name']?></td>
      	<td class="cen"><?=$each_row['date']?></td>
        <td class="rt"><?=$each_row['duration']?></td>
        <?php if(isset($show_minimum_time) && $show_minimum_time==TRUE){?>
  			<td class="rt"><?=$each_row['min_charge_time']?></td>
  		<?php } ?>
        <td class="rt"><?=$each_row['rate']?></td>
        <td class="rt"><?=$each_row['total']?></td>
    </tr>
	<?php } ?>
      <tr>
       <td class="lt">&nbsp;</td>
        <td class="cen">&nbsp;</td>
        <td class="rt"></td>
        <?php if(isset($show_minimum_time) && $show_minimum_time==TRUE){?>
  			<td class="rt"></td>
  		<?php } ?>
        <td class="rt"><strong>TOTAL</strong></td>
        <td class="rt">$ <?=$invoice_info['total_amount']?></td>
      </tr>
    </table>
  
<!--  <div class="buttons">
    <table>
      <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      </tr>
    </table>
  </div>-->
  
  <div class="pay_btn">
    <table>
      <tr>
      <td align="left"><h3>PAY ONLINE <a href="<?=$paynow?>">HERE</a></h3></td>
      </tr>
    </table>
  </div><br />

  
   <div class="bot_box">
   
  		
  		<h2>Make Cheques Payable to:  </h2>
		
        <p><?=$config_name;?><br />
		<?=$config_address;?><br /><br />


*Please print and attach invoice to cheque.
		</p>
  </div>
  
</div>

<?php }?>
</div>
<div class="page_break_box" style="page-break-after:always;clear:both">
<?php if($is_print) {
		echo $footer;
	} else {
?>
<script>
	window.print();
</script>

<? } ?>