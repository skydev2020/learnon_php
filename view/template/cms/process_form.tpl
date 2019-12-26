<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="language">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_payment_date; ?></td>
            <td><select name="payment_date" id="payment_date">
                <option value="0"><?php echo $text_select; ?></option>
                <?php foreach ($all_dates as $each_date) { ?>
                <?php if ($each_date['value'] == $payment_date) { ?>
                <option value="<?php echo $each_date['value']; ?>" selected="selected"><?php echo $each_date['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $each_date['value']; ?>"><?php echo $each_date['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                <?php if ($error_payment_date) { ?>
                <span class="error"><?php echo $error_payment_date; ?></span>
                <?php } ?></td>
          </tr>
      </table>
	  <?php if(!empty($payment_date)) { ?>      
	  <div id="process_steps">
      <table class="form">
      <tr>
            <td align="center"><input type="checkbox" name="process[]" value="collect_hours" <?=(in_array('collect_hours', $billing_process)) ? 'checked':'';?> <?=($processing) ? 'disabled':''; ?> /></td>
            <td><?php echo $entry_collect_hourse; ?></td>
            <td><?php if(!empty($collect_hours)) { ?><div id="collect_hours">Done</div><?php } ?></td>
          </tr>
          <?php if(!empty($collect_hours)) { ?>
          <tr>
            <td align="center">&nbsp;</td>
            <td><!-- <input type="checkbox" name="process_approved_hours" value="1" checked />  --> Official Submitted Hours ( <?=$total_approved_hours;?> )          
          		<!-- <input type="checkbox" name="process_notapproved_hours" value="1" /> Not Submitted Hours ( <?=$total_notapproved_hours;?> )
          		 <a href="javascript:void(0)">View Hours</a> --> </td>
            <td></td>
          </tr>
          <?php } ?>
		  <tr>
            <td align="center"><input type="checkbox" name="process[]" value="generate_invoices" <?=(in_array('generate_invoices', $billing_process)) ? 'checked':'';?> <?=($processing) ? 'disabled':''; ?> /></td>
            <td><?php echo $entry_generate_invoices; ?></td>
            <td><?php if(!empty($generate_invoices)) { ?><div id="generate_invoices">Done</div><?php } ?></td>
          </tr>
          <?php if(!empty($generate_invoices)) { ?>
          <tr>
            <td align="center">&nbsp;</td>
            <td>Invoice Generated ( <?=$total_invoice_generated;?> )          
          		Invoice Updated ( <?=$total_invoice_updated;?> )
          		 <!-- | <a href="javascript:void(0)">View Invoice</a> --> </td>
            <td></td>
          </tr>
          <?php } ?>
		  <tr>
            <td align="center"><input type="checkbox" name="process[]" value="send_invoices" <?=(in_array('send_invoices', $billing_process)) ? 'checked':'';?> <?=($processing) ? 'disabled':''; ?> /></td>
            <td><?php echo $entry_send_invoices; ?></td>
            <td><?php if(!empty($send_invoices)) { ?><div id="send_invoices">Done</div><?php } ?></td>
          </tr>
          <?php if(!empty($send_invoices)) { ?>
          <tr>
            <td align="center">&nbsp;</td>
            <td>Total Invoice Locked ( <?=$total_invoice_lock;?> ) Total Invoice Sent ( <?=$total_invoice_sent;?> ) <!-- | <a href="javascript:void(0)">View Sent Invoice</a> --></td>
            <td></td>
          </tr>
          <?php } ?>
		  <tr>
            <td align="center"><input type="checkbox" name="process[]" value="generate_paycheques" <?=(in_array('generate_paycheques', $billing_process)) ? 'checked':'';?> <?=($processing) ? 'disabled':''; ?> /></td>
            <td><?php echo $entry_generate_paycheques; ?></td>
            <td><?php if(!empty($generate_paycheques)) { ?><div id="generate_paycheques">Done</div><?php } ?></td>
          </tr>
          <?php if(!empty($generate_paycheques)) { ?>
          <tr>
            <td align="center">&nbsp;</td>
            <td>Paycheques Generated ( <?=$total_paycheques_generated;?> )          
          		Paycheques Updated ( <?=$total_paycheques_updated;?> )
          		 <!-- | <a href="javascript:void(0)">View Paycheques</a> --></td>
            <td></td>
          </tr>
          <?php } ?>          
		  <tr>
            <td align="center"><input type="checkbox" name="process[]" value="send_paycheques" <?=(in_array('send_paycheques', $billing_process)) ? 'checked':'';?> <?=($processing) ? 'disabled':''; ?> /></td>
            <td><?php echo $entry_send_paycheques; ?></td>
            <td><?php if(!empty($send_paycheques)) { ?><div id="send_paycheques">Done</div><?php } ?></td>
          </tr>
          <?php if(!empty($send_paycheques)) { ?>
          <tr>
            <td align="center">&nbsp;</td>
            <td>Total Invoice Locked ( <?=$total_paycheques_lock;?> ) Total Invoice Sent ( <?=$total_paycheques_sent;?> ) <!-- | <a href="javascript:void(0)">View Sent Paycheques</a> --></td>
            <td></td>
          </tr>
          <?php } ?>
		  
		  <?php if(!empty($finished)) { ?>
		  <tr>
            <td align="center">&nbsp;</td>
            <td><?php echo $entry_finished; ?></td>
            <td><div id="finished"></div></td>
          </tr>
          <?php } ?>
        <tr>
            <td align="center">&nbsp;</td>
          <td><?php if ($error_process) { ?>
                <span class="error"><?php echo $error_process; ?></span>
                <?php } ?></td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </div>
		<?php } ?>        
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function start_process() {
	var ides="";
	$('#process_steps :checked').each(function() {
		if(ides == "")
			ides = $(this).attr('value');
		else
			ides += ","+ $(this).attr('value');
	});
	
	//alert(ides);
	
	if(ides == "") {
		alert('Please select at least one Step!');
	} else {
				
	}
}
//--></script>
<?php echo $footer; ?>