<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
	<div class="buttons">
		<a href="<?=$print_invoice?>" target="_blank" class="button">
			<span><?php echo $button_print; ?></span>
		</a>
		<a onclick="$('#form').submit();" class="button">
			<span><?php echo $button_save; ?></span>
		</a>
		<a onclick="location = '<?php echo $cancel; ?>';" class="button">
			<span><?php echo $button_cancel; ?></span>
		</a>
	</div>
  </div>
  <div class="content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<input type='hidden' name='user_id' value = '<?php echo $user_id; ?>'/>
	<input type='hidden' name='src' value = '<?php echo $src; ?>'/>
    <table class="form">
      <tr>
        <td><?php echo $entry_student_name; ?></td>
        <td><a href="<?=$student_link;?>"><?php echo $student_name; ?></a> ( <?php echo $student_id; ?> ) </td>
      </tr>
      <tr>
        <td><?php echo $entry_invoice_num; ?></td>
        <td><?php echo $invoice_num; ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_invoice_date; ?></td>
        <td><input name="invoice_date" type="text" id="invoice_date" size="12" value="<?php echo $invoice_date; ?>" class="calender1" /></td>
      </tr>
      <?php foreach($student_packages as $key => $each_package) { ?>
      <tr>
        <td><?php echo $entry_package." ".($key+1)." ".$entry_hour_left; ?></td>
        <td><input name="packages[<?=$each_package['order_id']?>]" type="text" id="packages_<?=$key?>" size="12" value="<?php echo $each_package['left_hours']; ?>" /> <?=$each_package['package_name']?> ( <a href="<?=$each_package['view_details']?>" target="_blank">view details</a> ) </td>
      </tr>
      <?php } ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_num_of_sessions; ?></td>
        <td><input name="num_of_sessions" type="text" id="num_of_sessions" value="<?php echo $num_of_sessions; ?>" />
		  	<?php if ($error_num_of_sessions) { ?>
	        <span class="error"><?php echo $error_num_of_sessions; ?></span>
	        <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_total_hours; ?></td>
        <td><input name="total_hours" type="text" id="total_hours" value="<?php echo $total_hours; ?>" />
		  	<?php if ($error_total_hours) { ?>
	        <span class="error"><?php echo $error_total_hours; ?></span>
	        <?php } ?></td>
      </tr>
      <?php if(!empty($hour_charged)) { ?>
      <tr>
        <td><?php echo $entry_hour_charged; ?></td>
        <td><?php echo $hour_charged; ?></td>
      </tr>      
      <?php } ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_total_amount; ?></td>
        <td><input name="total_amount" type="text" id="total_amount" value="<?php echo $total_amount; ?>" />
		  	<?php if ($error_total_amount) { ?>
	        <span class="error"><?php echo $error_total_amount; ?></span>
	        <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_paid_amount; ?></td>
        <td><input name="paid_amount" type="text" id="paid_amount" value="<?php echo $paid_amount; ?>" /></td>
      </tr>
      <tr>
        <td valign="top"><?php echo $entry_invoice_notes; ?></td>
        <td><textarea name="invoice_notes" cols="50" rows="5"><?=$invoice_notes?></textarea></td>
      </tr>
      <tr>
        <td><?php echo $entry_send_date; ?></td>
        <td><?php echo $pay_date; ?></td>
      </tr>
	  <tr>
		<td><?php echo $entry_invoice_status; ?></td>
		<td>
		<select name="invoice_status" id="invoice_status">
			  <?php if(!empty($invoice_status)) { ?>
          	  <option value="<?=$invoice_status?>" selected="selected"><?=$invoice_status?></option>
          	  <option value="0">--Select One--</option>
          	  <?php } else { ?>
          	  <option value="0">--Select One--</option>
              <?php } ?>
              <option value="Reminder Sent">Reminder Sent</option>
              <option value="Payment Due">Payment Due</option>
              <option value="Paid">Paid</option>
              <option value="Hold For Approval">Hold For Approval</option>
		  </select><input type="hidden" name="invoice_status_pre" value="<?=$invoice_status_pre?>" /></td>
	  </tr>
	  	<tr>
            <td><?php echo $entry_invoice_mail; ?></td>
            <td><textarea name="invoice_mail" id="invoice_mail"><?php echo $invoice_mail; ?></textarea>
              <?php if (isset($error_invoice_mail)) { ?>
              <span class="error"><?php echo $error_invoice_mail; ?></span>
              <?php } ?></td>
        </tr>	
    </table>
  </form>
</div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.calender1').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('invoice_mail', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script>
<?php echo $footer; ?>