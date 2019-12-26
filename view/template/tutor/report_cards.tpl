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
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?=$action?>" method="post" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>                                
            <td class="left"><?php if ($sort == 'grade') { ?>
              <a href="<?php echo $sort_grade; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_grade; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_grade; ?>"><?php echo $column_grade; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'subjects') { ?>
              <a href="<?php echo $sort_subjects; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_subjects; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_subjects; ?>"><?php echo $column_subjects; ?></a>
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
            <td><input type="text" name="filter_grade" value="<?php echo $filter_grade; ?>" /></td>
            <td><input type="text" name="filter_subjects" value="<?php echo $filter_subjects; ?>" /></td>
            <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($reportcards) { ?>
          <?php foreach ($reportcards as $reportcard) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($reportcard['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $reportcard['progress_reports_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $reportcard['progress_reports_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $reportcard['student_name']; ?></td>
            <td class="left"><?php echo $reportcard['grade']; ?></td>
            <td class="left"><?php echo stripslashes($reportcard['subjects']); ?></td>
            <td class="left"><?php echo $reportcard['date_added']; ?></td>
            <td class="center"><?php foreach ($reportcard['action'] as $action) { ?>
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
	url = 'index.php?route=tutor/report_cards&token=<?php echo $token; ?>';
	
	var filter_grade = $('input[name=\'filter_grade\']').attr('value');
	if (filter_grade) {
		url += '&filter_grade=' + encodeURIComponent(filter_grade);
	}
	
	var filter_student_name = $('input[name=\'filter_student_name\']').attr('value');
	if (filter_student_name) {
		url += '&filter_student_name=' + encodeURIComponent(filter_student_name);
	}
	
	var filter_subjects = $('input[name=\'filter_subjects\']').attr('value');
	if (filter_subjects) {
		url += '&filter_subjects=' + encodeURIComponent(filter_subjects);
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