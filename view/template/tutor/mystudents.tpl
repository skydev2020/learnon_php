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
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <form action="<?=$action?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>                          
            <td class="left"><?php echo $column_subjects; ?></td>
            <td class="left"><?php if ($sort == 'status_by_tutor') { ?>
              <a href="<?php echo $sort_status_by_tutor; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status_by_tutor; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status_by_tutor; ?>"><?php echo $column_status_by_tutor; ?></a>
              <?php } ?></td>       
            <td class="left"><?php if ($sort == 'date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_student_name" value="<?php echo $filter_student_name; ?>" /></td>
            <td></td>          
			<td><select name="filter_status_by_tutor">
                <option value="*"></option>
                <?php if ($filter_status_by_tutor == "Active") { ?>
                <option value="Active" selected="selected">Active</option>
                <?php } else { ?>
                <option value="Active">Active</option>
                <?php } ?>
                <?php if ($filter_status_by_tutor == "Stop Tutoring") { ?>
                <option value="Stop Tutoring" selected="selected">Stop Tutoring</option>
                <?php } else { ?>
                <option value="Stop Tutoring">Stop Tutoring</option>
                <?php } ?>
                <!--
                <?php if ($filter_status_by_tutor == "Done Tutoring") { ?>
                <option value="Done Tutoring" selected="selected">Done Tutoring</option>
                <?php } else { ?>
                <option value="Done Tutoring">Done Tutoring</option>
                <?php } ?>
                <?php if ($filter_status_by_tutor == "No More Tutoring") { ?>
                <option value="No More Tutoring" selected="selected">No More Tutoring</option>
                <?php } else { ?>
                <option value="No More Tutoring">No More Tutoring</option>
                <?php } ?> -->
              </select></td> 
            <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($mystudents) { ?>
          <?php foreach ($mystudents as $assignment) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($assignment['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $assignment['tutors_to_students_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $assignment['tutors_to_students_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $assignment['student_name']; ?></td>
            <td class="left"><?php $p=0;foreach($assignment['subjects'] as $subject){if($p)echo ", ";echo stripslashes($subject['subjects_name']);$p++;} ?></td>
			<td class="left"><?php echo $assignment['status_by_tutor']; ?></td>
            <td class="left"><?php echo $assignment['date_added']; ?></td>
            <td class="center"><?php foreach ($assignment['action'] as $key=>$action) {if($key==2){echo '<br />';} ?>
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
	url = 'index.php?route=tutor/mystudents&token=<?php echo $token; ?>';
	
	var filter_student_name = $('input[name=\'filter_student_name\']').attr('value');
	
	if (filter_student_name) {
		url += '&filter_student_name=' + encodeURIComponent(filter_student_name);
	}
	
	var filter_status_by_tutor = $('select[name=\'filter_status_by_tutor\']').attr('value');
	
	if (filter_status_by_tutor != '*') {
		url += '&filter_status_by_tutor=' + encodeURIComponent(filter_status_by_tutor); 
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