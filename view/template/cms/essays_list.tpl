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
    <div class="buttons"><a onclick="location = '<?php echo $upload; ?>'" class="button"><span><?php echo $button_upload; ?></span></a><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
		<!-- Softronikx Technologies -->
          <tr>
		  	<td colspan="3">Search <input type="text" name="filter_all" value="<?php echo $filter_all; ?>" /> 
			</td>
		  	<td colspan="8" align="right" height="38">
			</td>
		  </tr>
		<!-- End of Code -->  	  
		  <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'assignment_num') { ?>
              <a href="<?php echo $sort_assignment_num; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_assignment_num; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_assignment_num; ?>"><?php echo $column_assignment_num; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>
			<td class="left"><?php if ($sort == 'tutor_name') { ?>
              <a href="<?php echo $sort_tutor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tutor_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_tutor_name; ?>"><?php echo $column_tutor_name; ?></a>
              <?php } ?></td>              
            <td class="left"><?php if ($sort == 'topic') { ?>
              <a href="<?php echo $sort_topic; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_topic; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_topic; ?>"><?php echo $column_topic; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
			  
			<td class="left"><?php if ($sort == 'price_paid') { ?>
              <a href="<?php echo $sort_price_paid; ?>" class="<?php echo strtolower($order); ?>">Price</a>
              <?php } else { ?>
              <a href="<?php echo $sort_price_paid; ?>">Price Paid</a>
              <?php } ?></td>
			  
			<td class="left"><?php if ($sort == 'paid_to_tutor') { ?>
              <a href="<?php echo $paid_to_tutor; ?>" class="<?php echo strtolower($order); ?>">Paid to Tutor</a>
              <?php } else { ?>
              <a href="<?php echo $paid_to_tutor; ?>">Paid to Tutor</a>
              <?php } ?></td>
			  
            <td class="left"><?php if ($sort == 'date_assigned') { ?>
              <a href="<?php echo $sort_date_assigned; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_assigned; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_assigned; ?>"><?php echo $column_date_assigned; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'date_due') { ?>
              <a href="<?php echo $sort_due_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_due_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_due_date; ?>"><?php echo $column_due_date; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
		<!-- Softronikx Technologies -->
		 <tr class="filter">
		 
            <td></td>
            <td><input style="width:100px;" type="text" name="filter_assignment_num" value="<?php echo $filter_assignment_num; ?>" /></td>
            <td><input style="width:100px;"  type="text" name="filter_student_name" value="<?php echo $filter_student_name; ?>" /></td>     
            <td><input style="width:100px;"  type="text" name="filter_tutor_name" value="<?php echo $filter_tutor_name; ?>" /></td>
            <td><input style="width:100px;"  type="text" name="filter_topic" value="<?php echo $filter_topic; ?>" /></td>
            <td><select style="width:100px;"  name="filter_status">
                <option value="*"></option>
                <?php foreach($assignment_status as $key => $each_status) { ?>
                <?php if ($key == $filter_status) { ?>                
                <option value="<?=$key?>" selected="selected"><?php echo $each_status; ?></option>
                <?php } else { ?>
                <option value="<?=$key?>"><?php echo $each_status; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>  
			  
			<td><input style="width:100px;"  type="text" name="filter_price_paid" value="<?php echo $filter_price_paid; ?>" /></td>     
            <td><input style="width:100px;"  type="text" name="filter_paid_to_tutor" value="<?php echo $filter_paid_to_tutor; ?>" /></td> 
			
            <td>
			Date From: &nbsp; &nbsp; <input style="width:100px;"  type="text" name="filter_date_assigned" value="<?php echo $filter_date_assigned; ?>" size="12" id="date_assigned" />
			<br>Date To: &nbsp; &nbsp; <input style="width:100px;"  type="text" name="filter_date_to_assigned" value="<?php echo $filter_date_to_assigned; ?>" size="12" id="date_to_assigned" />
			</td>
			
			<td>
			Date From: &nbsp; &nbsp; <input style="width:100px;"  type="text" name="filter_date_completed" value="<?php echo $filter_date_completed; ?>" size="12" id="date_completed" />
			<br>Date To: &nbsp; &nbsp; <input style="width:100px;"  type="text" name="filter_date_to_completed" value="<?php echo $filter_date_to_completed; ?>" size="12" id="date_to_completed" />
			</td>            
			<td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>  
	
		<!-- End of Code -->
          <?php if ($informations) { ?>
          <?php foreach ($informations as $information) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($information['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['essay_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['essay_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $information['assignment_num']; ?></td>
            <td class="left" style="max-width: 100px;" ><?php echo $information['student_name']; ?></td>
            <td class="left"><?php echo $information['tutor_name']; ?></td>
            <td class="left"><?php echo $information['topic']; ?></td>
            <td class="left"><?php echo $information['status']; ?></td>
			<td class="left"><?php echo $information['owed']; ?></td>
            <td class="left"><?php echo $information['paid']; ?></td>
            <td class="left"><?php echo $information['date_assigned']; ?></td>
            <td class="left"><?php echo $information['due_date']; ?></td>
            <td class="right"><?php foreach ($information['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=cms/essays&token=<?php echo $token; ?>';
	
	var filter_all = $('input[name=\'filter_all\']').attr('value');
	if (filter_all) {
		url += '&filter_all=' + encodeURIComponent(filter_all);
	} else {
	
	var filter_student_name = $('input[name=\'filter_student_name\']').attr('value');
	if (filter_student_name) {
		url += '&filter_student_name=' + encodeURIComponent(filter_student_name);
	}
	
	var filter_tutor_name = $('input[name=\'filter_tutor_name\']').attr('value');
	if (filter_tutor_name) {
		url += '&filter_tutor_name=' + encodeURIComponent(filter_tutor_name);
	}
	
	var filter_topic = $('input[name=\'filter_topic\']').attr('value');
	if (filter_topic) {
		url += '&filter_topic=' + encodeURIComponent(filter_topic);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);		
	}	
	
	var filter_date_assigned = $('input[name=\'filter_date_assigned\']').attr('value');
	if (filter_date_assigned) {
		url += '&filter_date_assigned=' + encodeURIComponent(filter_date_assigned);
	}	
	
	var filter_date_completed = $('input[name=\'filter_date_completed\']').attr('value');
	if (filter_date_completed) {
		url += '&filter_date_completed=' + encodeURIComponent(filter_date_completed);
	}
	
	var filter_date_to_assigned = $('input[name=\'filter_date_to_assigned\']').attr('value');
	if (filter_date_to_assigned) {
		url += '&filter_date_to_assigned=' + encodeURIComponent(filter_date_to_assigned);
	}	
	
	var filter_date_to_completed = $('input[name=\'filter_date_to_completed\']').attr('value');
	if (filter_date_to_completed) {
		url += '&filter_date_to_completed=' + encodeURIComponent(filter_date_to_completed);
	}
	
	var filter_assignment_num = $('input[name=\'filter_assignment_num\']').attr('value');
	if (filter_assignment_num) {
		url += '&filter_assignment_num=' + encodeURIComponent(filter_assignment_num);
	}
	
	var filter_price_paid = $('input[name=\'filter_price_paid\']').attr('value');
	if (filter_price_paid) {
		url += '&filter_price_paid=' + encodeURIComponent(filter_price_paid);
	}
	
	var filter_paid_to_tutor = $('input[name=\'filter_paid_to_tutor\']').attr('value');
	if (filter_paid_to_tutor) {
		url += '&filter_paid_to_tutor=' + encodeURIComponent(filter_paid_to_tutor);
	}
	
	}
	
	location = url;
}
function exportdata() {
	url = 'index.php?route=cms/essays/export&token=<?php echo $token; ?>';
	
	var filter_all = $('input[name=\'filter_all\']').attr('value');
	if (filter_name) {
		url += '&filter_all=' + encodeURIComponent(filter_all);
	} else { 
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_city = $('input[name=\'filter_city\']').attr('value');
	if (filter_city) {
		url += '&filter_city=' + encodeURIComponent(filter_city);
	}
	
	var filter_subjects = $('input[name=\'filter_subjects\']').attr('value');
	if (filter_subjects) {
		url += '&filter_subjects=' + encodeURIComponent(filter_subjects);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	var filter_approved = $('select[name=\'filter_approved\']').attr('value');
	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var student_list = $('input[name=\'student_list\']').attr('value');
	if ($('input[name=\'student_list\']').attr('checked')) {
		url += '&student_list=' + encodeURIComponent(student_list);
	}
	
	var student_emails = $('input[name=\'student_emails\']').attr('value');
	if ($('input[name=\'student_emails\']').attr('checked')) {
		url += '&student_emails=' + encodeURIComponent(student_emails);
	}
	
	var where_heared = $('input[name=\'where_heared\']').attr('value');
	if ($('input[name=\'where_heared\']').attr('checked')) {
		url += '&where_heared=' + encodeURIComponent(where_heared);
	}
	
	var contract = $('input[name=\'contract\']').attr('value');
	if ($('input[name=\'contract\']').attr('checked')) {
		url += '&contract=' + encodeURIComponent(contract);
	}
	}
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_assigned').datepicker({dateFormat: 'yy-mm-dd'});
	$('#date_completed').datepicker({dateFormat: 'yy-mm-dd'});
	$('#date_to_assigned').datepicker({dateFormat: 'yy-mm-dd'});
	$('#date_to_completed').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

<?php echo $footer; ?>