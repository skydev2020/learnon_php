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
    <h1 style="background-image: url('view/image/customer.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><?php if($parent_switch) { ?><a onclick="location = '<?php echo $parent_switch; ?>'" class="button"><span><?php echo $button_parent_switch; ?></span></a><?php } ?><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <!-- <td class="left"><?php if ($sort == 'c.email') { ?>
              <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
              <?php } ?></td> -->
			<td class="left"><?php if ($sort == 'city') { ?>
              <a href="<?php echo $sort_city; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_city; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_city; ?>"><?php echo $column_city; ?></a>
              <?php } ?></td>
			<td class="left"><?php if ($sort == 'subjects') { ?>
              <a href="<?php echo $sort_subjects; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_subjects; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_subjects; ?>"><?php echo $column_subjects; ?></a>
              <?php } ?></td>
            <td class="left"><?php echo $column_student_status; ?></td>
            <td class="left"><?php if ($sort == 'c.date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>              
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($customers) { ?>
          <?php foreach ($customers as $customer) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($customer['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $customer['user_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $customer['user_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $customer['name']; ?></td>
            <!-- <td class="left"><?php echo $customer['email']; ?></td> -->
            <td class="left"><?php echo $customer['city']; ?></td>
            <td class="left"><?php echo $customer['subjects']; ?></td>
            <td class="left"><?php echo $customer['student_status']; ?></td>
            <td class="left"><?php echo $customer['date_added']; ?></td>
            <td class="right"><?php foreach ($customer['action'] as $action) { ?>
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
	url = 'index.php?route=user/students&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
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
	
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>