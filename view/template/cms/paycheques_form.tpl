<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<input type='hidden' name='user_id' value = '<?php echo $user_id; ?>'/>
	<input type='hidden' name='user_email' value = '<?php echo $user_email; ?>'/>
	<input type='hidden' name='src' value = '<?php echo $src; ?>'/>
    <table class="form">
      <tr>
        <td><?php echo $entry_tutor_name; ?></td>
        <td><?php echo $tutor_name; ?></td>
      </tr>
      <tr>
        <td><?php echo $text_tutor_address; ?></td>
        <td><?php echo $tutor_address; ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_paycheque_num; ?></td>
        <td><input name="paycheque_num" type="text" id="paycheque_num" value="<?php echo $paycheque_num; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_paycheque_date; ?></td>
        <td><input name="paycheque_date" type="text" id="paycheque_date" size="12" value="<?php echo $paycheque_date; ?>" class="calender1" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_num_of_sessions; ?></td>
        <td><input name="num_of_sessions" type="text" id="num_of_sessions" value="<?php echo $num_of_sessions; ?>" />
		  	<?php if ($error_num_of_sessions) { ?>
	        <span class="error"><?php echo $error_num_of_sessions; ?></span>
	        <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_total_hours; ?></td>
        <td><input name="total_hours" type="text" id="total_hours" value="<?php echo $total_hours; ?>" />
		  	<?php if ($error_total_hours) { ?>
	        <span class="error"><?php echo $error_total_hours; ?></span>
	        <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_num_of_essays; ?></td>
        <td><input name="num_of_essays" type="text" id="num_of_essays" value="<?php echo $num_of_essays; ?>" />
		  	<?php if ($error_num_of_essays) { ?>
	        <span class="error"><?php echo $error_num_of_essays; ?></span>
	        <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_essays_amount; ?></td>
        <td><input name="essays_amount" type="text" id="essays_amount" value="<?php echo $essays_amount; ?>" />
		  	<?php if ($error_essays_amount) { ?>
	        <span class="error"><?php echo $error_essays_amount; ?></span>
	        <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_raise_amount; ?></td>
        <td><input name="raise_amount" type="text" id="raise_amount" value="<?php echo $raise_amount; ?>" />
		  	<?php if ($error_raise_amount) { ?>
	        <span class="error"><?php echo $error_raise_amount; ?></span>
	        <?php } ?></td>
      </tr>
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
        <td valign="top"><?php echo $entry_paycheque_notes; ?></td>
        <td><textarea name="paycheque_notes" cols="50" rows="5"><?=$paycheque_notes?></textarea></td>
      </tr>
      <tr>
        <td><?php echo $entry_send_date; ?></td>
        <td><?php echo $pay_date; ?></td>
      </tr>
	  <tr>
		<td><?php echo $entry_paycheque_status; ?></td>
		<td>
		<select name="paycheque_status" id="paycheque_status">
				<option value="Hold For Approval"<?php if($paycheque_status == "Hold For Approval"){?> selected="selected"<?php }?>>Hold For Approval</option>
				<option value="Paid"<?php if($paycheque_status == "Paid"){?> selected="selected"<?php }?>>Paid</option>
		  </select><input type="hidden" name="paycheque_status_pre" value="<?=$paycheque_status_pre?>" /></td>
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
<?php echo $footer; ?>