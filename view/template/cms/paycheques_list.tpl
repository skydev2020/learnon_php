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
		
		<!-- Softronikx Technologies -->
		<a onclick="$('form').attr('action', '<?php echo $mark_paid; ?>'); $('form').submit();" class="button">
		<span><?php echo $button_mark_paid; ?></span></a>
		 <!-- End of Code -->
		 
		<a onclick="$('form').attr('action', '<?php echo $lock_sessions; ?>'); $('form').submit();" class="button">
		<span><?php echo $button_lock; ?></span></a>
		<a onclick="$('form').attr('action', '<?php echo $unlock_sessions; ?>'); $('form').submit();" class="button">
		<span><?php echo $button_unlock; ?></span></a>
		<a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button">
		<span><?php echo $button_delete; ?></span></a><?php } ?>
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
		  	<td colspan="9" align="right" height="38">
  	<input type="checkbox" name="tutor_name" value="yes" /> Tutor Name <input type="checkbox" name="address" value="yes" /> Address <input type="checkbox" name="total_amount" value="yes" /> Total Amount <input type="checkbox" name="total_hours" value="yes" /> Total Hours <input type="checkbox" name="date_added" value="yes"  /> Date Added <input type="checkbox" name="paid_amount" value="yes"  /> Paid Amount <input type="checkbox" name="pay_date" value="yes"  /> Pay Date <input type="checkbox" name="send_date" value="yes"  /> Date Sent <input type="checkbox" name="status" value="yes"  /> Status <a onclick="exportdata();" class="button"><span>Export</span></a>
			</td>
		  </tr>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'tutor_name') { ?>
              <a href="<?php echo $sort_tutor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tutor_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_tutor_name; ?>"><?php echo $column_tutor_name; ?></a>
              <?php } ?></td>
            <!--
            <td class="right"><?php if ($sort == 'total_hours') { ?>
              <a href="<?php echo $sort_total_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_hours; ?>"><?php echo $column_total_hours; ?></a>
              <?php } ?></td>
             -->
            <td class="right"><?php echo $column_total_sessions; ?></td>            
            <td class="right"><?php echo $column_total_hours; ?></td>
            <td class="right"><?php echo $column_raise_amount; ?></td>
            <td class="right"><?php if ($sort == 'total_amount') { ?>
              <a href="<?php echo $sort_total_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_amount; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_amount; ?>"><?php echo $column_total_amount; ?></a>
              <?php } ?></td>
            <td class="center"><?php if ($sort == 'paycheque_date') { ?>
              <a href="<?php echo $sort_paycheque_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_paycheque_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_paycheque_date; ?>"><?php echo $column_paycheque_date; ?></a>
              <?php } ?></td>
           <td class="left"><?php if ($sort == 'paycheque_status') { ?>
              <a href="<?php echo $sort_paycheque_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_paycheque_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_paycheque_status; ?>"><?php echo $column_paycheque_status; ?></a>
              <?php } ?></td> 
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_tutor_name" value="<?php echo $filter_tutor_name; ?>" /></td>   
            <td><!-- <input type="text" name="filter_total_amount" value="<?php echo $filter_total_amount; ?>" /> --></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
			<td><!-- <input type="text" name="filter_total_hours" value="<?php echo $filter_total_hours; ?>" /> --></td>
			<td><input type="text" name="filter_paycheque_date" value="<?php echo $filter_paycheque_date; ?>" size="12" id="date" /></td>
			<td>
				<select name="filter_paycheque_status" id="filter_paycheque_status">
					<?php if(!empty($filter_paycheque_status)) { ?>
	          	  	<option value="<?=$filter_paycheque_status?>" selected="selected"><?=$filter_paycheque_status?></option>
	          	  	<option value=""></option>
	          	  	<?php } else { ?>
	          	  	<option value=""></option>
	              	<?php } ?>
	              	<option value="Paid">Paid</option>
	              	<option value="Hold For Approval">Hold For Approval</option>
              	</select>			
			</td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($paycheques) { ?>
          <?php foreach ($paycheques as $paycheque) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($paycheque['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $paycheque['paycheque_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $paycheque['paycheque_id']; ?>" />
              <?php } ?></td>
			<td class="left"><?php echo $paycheque['tutor_name']; ?></td>            
            <td class="right"><?php echo $paycheque['total_sessions']; ?></td>
            <td class="right"><?php echo $paycheque['total_hours']; ?></td>
            <td class="right"><?php echo $paycheque['raise_amount']; ?></td>
            <td class="right"><?php echo $paycheque['total_amount']; ?></td>            
            <td class="center"><?php echo $paycheque['paycheque_date']; ?></td>
			<td class="left"><?php echo $paycheque['paycheque_status']; ?></td>
            <td class="right"><?php foreach ($paycheque['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
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
	var url = 'index.php?route=cms/paycheque&token=<?php echo $token; ?>';
	
	var filter_paycheque_num = $('input[name=\'filter_paycheque_num\']').attr('value');
	if (filter_paycheque_num) {
		url += '&filter_paycheque_num=' + encodeURIComponent(filter_paycheque_num);
	}
	
	var filter_tutor_name = $('input[name=\'filter_tutor_name\']').attr('value');
	if (filter_tutor_name) {
		url += '&filter_tutor_name=' + encodeURIComponent(filter_tutor_name);
	}
	
	var filter_paycheque_date = $('input[name=\'filter_paycheque_date\']').attr('value');
	if (filter_paycheque_date) {
		url += '&filter_paycheque_date=' + encodeURIComponent(filter_paycheque_date);
	}
	var filter_total_hours = $('input[name=\'filter_total_hours\']').attr('value');
	if (filter_total_hours) {
		url += '&filter_total_hours=' + encodeURIComponent(filter_total_hours);
	}
	var filter_total_amount = $('input[name=\'filter_total_amount\']').attr('value');
	if (filter_total_amount) {
		url += '&filter_total_amount=' + encodeURIComponent(filter_total_amount);
	}
	var filter_total_hours = $('input[name=\'filter_total_hours\']').attr('value');
	if (filter_total_hours) {
		url += '&filter_total_hours=' + encodeURIComponent(filter_total_hours);
	}
	
	var filter_paycheque_status = $('select[name=\'filter_paycheque_status\']').attr('value');
	
	if (filter_paycheque_status) {
		url += '&filter_paycheque_status=' + encodeURIComponent(filter_paycheque_status);
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
function exportdata() {
	url = 'index.php?route=cms/paycheque/export&token=<?php echo $token; ?>';
	
	var filter_tutor_name = $('input[name=\'filter_tutor_name\']').attr('value');
	if (filter_tutor_name) {
		url += '&filter_tutor_name=' + encodeURIComponent(filter_tutor_name);
	}
	
	var filter_total_amount = $('input[name=\'filter_total_amount\']').attr('value');
	if (filter_total_amount) {
		url += '&filter_total_amount=' + encodeURIComponent(filter_total_amount);
	}
	
	var filter_total_hours = $('input[name=\'filter_total_hours\']').attr('value');
	if (filter_total_hours) {
		url += '&filter_total_hours=' + encodeURIComponent(filter_total_hours);
	}	
	
	var filter_paycheque_date = $('input[name=\'filter_paycheque_date\']').attr('value');
	if (filter_paycheque_date) {
		url += '&filter_paycheque_date=' + encodeURIComponent(filter_paycheque_date); 
	}	
	
	var filter_paycheque_status = $('select[name=\'filter_paycheque_status\']').attr('value');
	if (filter_paycheque_status) {
		url += '&filter_paycheque_status=' + encodeURIComponent(filter_paycheque_status);
	}	

	
	var tutor_name = $('input[name=\'tutor_name\']').attr('value');
	if ($('input[name=\'tutor_name\']').attr('checked')) {
		url += '&tutor_name=' + encodeURIComponent(tutor_name);
	}
	
	var address = $('input[name=\'address\']').attr('value');
	if ($('input[name=\'address\']').attr('checked')) {
		url += '&address=' + encodeURIComponent(address);
	}
	
	var total_amount = $('input[name=\'total_amount\']').attr('value');
	if ($('input[name=\'total_amount\']').attr('checked')) {
		url += '&total_amount=' + encodeURIComponent(total_amount);
	}
	
	var total_hours = $('input[name=\'total_hours\']').attr('value');
	if ($('input[name=\'total_hours\']').attr('checked')) {
		url += '&total_hours=' + encodeURIComponent(total_hours);
	}
	
	var date_added = $('input[name=\'date_added\']').attr('value');
	if ($('input[name=\'date_added\']').attr('checked')) {
		url += '&date_added=' + encodeURIComponent(date_added);
	}
	var paid_amount = $('input[name=\'paid_amount\']').attr('value');
	if ($('input[name=\'paid_amount\']').attr('checked')) {
		url += '&paid_amount=' + encodeURIComponent(paid_amount);
	}
	var pay_date = $('input[name=\'pay_date\']').attr('value');
	if ($('input[name=\'pay_date\']').attr('checked')) {
		url += '&pay_date=' + encodeURIComponent(pay_date);
	}
	var send_date = $('input[name=\'send_date\']').attr('value');
	if ($('input[name=\'send_date\']').attr('checked')) {
		url += '&send_date=' + encodeURIComponent(send_date);
	}
	var status = $('input[name=\'status\']').attr('value');
	if ($('input[name=\'status\']').attr('checked')) {
		url += '&status=' + encodeURIComponent(status);
	}
	
//	alert(url);
	
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