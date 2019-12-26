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
    <h1 style="background-image: url('view/image/information.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons">
	<?php if($sessions_controll) {?>
		<a onclick="$('form').attr('action', '<?php echo $print_all_invoices; ?>'); $('form').submit();" class="button">
			<span><?php echo $button_print_all_invoices; ?></span>
		</a>
	
		<a onclick="$('form').attr('action', '<?php echo $apply_late_fee; ?>'); $('form').submit();" class="button">
			<span><?php echo $button_late_fee; ?></span>
		</a>
		
		<a onclick="$('form').attr('action', '<?php echo $lock_sessions; ?>'); $('form').submit();" class="button">
			<span><?php echo $button_lock; ?></span>
		</a>
		
		<a onclick="$('form').attr('action', '<?php echo $unlock_sessions; ?>'); $('form').submit();" class="button">
			<span><?php echo $button_unlock; ?></span>
		</a>
		
		<a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button">
			<span><?php echo $button_delete; ?></span>
		</a>
		
	<?php } ?>
	</div>    
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
	  <!-- Softronikx Technologies -->
		<input type='hidden' name='user_id' value = '<?php echo $user_id; ?>'/>
		<input type='hidden' name='src' value = '<?php echo $src; ?>'/>
	  <!-- End of Code -->
	
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="center"><?php if ($sort == 'invoice_num') { ?>
              <a href="<?php echo $sort_invoice_num; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice_num; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_num; ?>"><?php echo $column_invoice_num; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>             
            <td class="right"><?php if ($sort == 'total_amount') { ?>
              <a href="<?php echo $sort_total_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_amount; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_amount; ?>"><?php echo $column_total_amount; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'total_hours') { ?>
              <a href="<?php echo $sort_total_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_hours; ?>"><?php echo $column_total_hours; ?></a>
              <?php } ?></td>
            <td class="center"><?php if ($sort == 'invoice_date') { ?>
              <a href="<?php echo $sort_invoice_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_date; ?>"><?php echo $column_invoice_date; ?></a>
              <?php } ?></td>
           <td class="left"><?php if ($sort == 'invoice_status') { ?>
              <a href="<?php echo $sort_invoice_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_status; ?>"><?php echo $column_invoice_status; ?></a>
              <?php } ?></td> 
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_invoice_num" value="<?php echo $filter_invoice_num; ?>" /></td>
            <td><input type="text" name="filter_student_name" value="<?php echo $filter_student_name; ?>" /></td>   
            <td><!-- <input type="text" name="filter_total_amount" value="<?php echo $filter_total_amount; ?>" /> --></td>
			<td><!-- <input type="text" name="filter_total_hours" value="<?php echo $filter_total_hours; ?>" /> --></td>
			<td><input type="text" name="filter_invoice_date" value="<?php echo $filter_invoice_date; ?>" size="12" id="date" /></td>  
			<td>
				<select name="filter_invoice_status" id="filter_invoice_status">
					<?php if(!empty($filter_invoice_status)) { ?>
	          	  	<option value="<?=$filter_invoice_status?>" selected="selected"><?=$filter_invoice_status?></option>
	          	  	<option value=""></option>
	          	  	<?php } else { ?>
	          	  	<option value=""></option>
	              	<?php } ?>
	              	<option value="Reminder Sent">Reminder Sent</option>
	              	<option value="Payment Due">Payment Due</option>
	              	<option value="Paid">Paid</option>
	              	<option value="Hold For Approval">Hold For Approval</option>
					<option value="Unpaid">Unpaid</option> <!-- Last option added by Softronikx Technologies -->
              	</select>			
			</td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($invoices) { ?>
          <?php foreach ($invoices as $invoice) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($invoice['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $invoice['invoice_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $invoice['invoice_id']; ?>" />
              <?php } ?></td>
            <td class="center"><?php echo $invoice['invoice_num']; ?></td>
			<td class="left"><?php echo $invoice['student_name']; ?></td>
            <td class="right"><?php echo $invoice['total_amount']; ?></td>
            <td class="right"><?php echo $invoice['total_hours']; ?></td>
            <td class="center"><?php echo $invoice['invoice_date']; ?></td>
			<td class="left"><?php echo $invoice['invoice_status']; ?></td>
            <td class="right"><?php foreach ($invoice['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	var url = 'index.php?route=cms/invoices&token=<?php echo $token; ?>';
	
	var filter_invoice_num = $('input[name=\'filter_invoice_num\']').attr('value');
	if (filter_invoice_num) {
		url += '&filter_invoice_num=' + encodeURIComponent(filter_invoice_num);
	}
	var filter_invoice_date = $('input[name=\'filter_invoice_date\']').attr('value');
	if (filter_invoice_date) {
		url += '&filter_invoice_date=' + encodeURIComponent(filter_invoice_date);
	}
	var filter_total_hours = $('input[name=\'filter_total_hours\']').attr('value');
	if (filter_total_hours) {
		url += '&filter_total_hours=' + encodeURIComponent(filter_total_hours);
	}
	var filter_total_amount = $('input[name=\'filter_total_amount\']').attr('value');
	if (filter_total_amount) {
		url += '&filter_total_amount=' + encodeURIComponent(filter_total_amount);
	}
	var filter_student_name = $('input[name=\'filter_student_name\']').attr('value');
	if (filter_student_name) {
		url += '&filter_student_name=' + encodeURIComponent(filter_student_name);
	}
	var filter_invoice_status = $('select[name=\'filter_invoice_status\']').attr('value');
	
	if (filter_invoice_status) {
		url += '&filter_invoice_status=' + encodeURIComponent(filter_invoice_status);
	}	
	var filter_total_hours = $('input[name=\'filter_total_hours\']').attr('value');
	if (filter_total_hours) {
		url += '&filter_total_hours=' + encodeURIComponent(filter_total_hours);
	}
	
	<!-- Softronikx Technologies -->
	var user_id = $('input[name=\'user_id\']').attr('value');
	if (user_id) {
		url += '&user_id=' + encodeURIComponent(user_id);
	}
	
	var src = $('input[name=\'src\']').attr('value');
	if (src) {
		url += '&src=' + encodeURIComponent(src);
	}
	<!-- End of Code by Softronikx -->
	
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-'});
});
//--></script>
<?php echo $footer; ?>