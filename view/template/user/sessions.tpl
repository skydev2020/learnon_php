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
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><?php if($sessions_controll) {?><a onclick="$('form').attr('action', '<?php echo $lock_sessions; ?>'); $('form').submit();" class="button"><span><?php echo $button_lock; ?></span></a><a onclick="$('form').attr('action', '<?php echo $unlock_sessions; ?>'); $('form').submit();" class="button"><span><?php echo $button_unlock; ?></span></a><?php } ?><a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?=$action?>" method="post" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'tutor_name') { ?>
              <a href="<?php echo $sort_tutor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tutor_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_tutor_name; ?>"><?php echo $column_tutor_name; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>                                
            <td class="left"><?php if ($sort == 'session_duration') { ?>
              <a href="<?php echo $sort_session_duration; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_session_duration; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_session_duration; ?>"><?php echo $column_session_duration; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'session_date') { ?>
              <a href="<?php echo $sort_session_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_session_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_session_date; ?>"><?php echo $column_session_date; ?></a>
              <?php } ?></td>
            <td class="center"><?php echo $column_date; ?></td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
			<td><input type="text" name="filter_tutor_name" value="<?php echo $filter_tutor_name; ?>" /></td>
            <td><input type="text" name="filter_student_name" value="<?php echo $filter_student_name; ?>" /></td>            
            <td><select name="filter_session_duration">
				<?php foreach($duration_array as $key=>$duration){
					if($filter_session_duration==$key){ ?>
					<option value="<?=$key?>" selected="selected"><?=$duration?></option>
				<?php }else{ ?>
					<option value="<?=$key?>"><?=$duration?></option>	 
				<?php }}?>
		          </select></td>
            <td><input type="text" name="filter_session_date" value="<?php echo $filter_session_date; ?>" size="12" class="date" /></td>
            <td><input type="text" name="filter_session_notes" value="<?php echo $filter_session_notes; ?>" size="12" class="date" /></td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($sessions) { ?>
          <?php foreach ($sessions as $session) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($session['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $session['session_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $session['session_id']; ?>" />
              <?php } ?></td>
			<td class="left"><?php echo $session['tutor_name']; ?> ( <?php echo $session['tutor_wage']; ?> )</td>
            <td class="left"><?php echo $session['student_name']; ?> ( <?php echo $session['base_invoice']; ?> )</td>            
            <td class="left"><?php echo $session['session_duration']; ?></td>
            <td class="left"><?php echo $session['session_date']; ?></td>
            <td class="left"><?php echo stripslashes($session['date']); ?></td>
            <td class="center"><?php foreach ($session['action'] as $action) { ?>
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
	url = 'index.php?route=user/sessions&token=<?php echo $token; ?>';
	
	var filter_session_duration = $('select[name=\'filter_session_duration\']').attr('value');
	if (filter_session_duration) {
		url += '&filter_session_duration=' + encodeURIComponent(filter_session_duration);
	}
	
	var filter_tutor_name = $('input[name=\'filter_tutor_name\']').attr('value');
	if (filter_tutor_name) {
		url += '&filter_tutor_name=' + encodeURIComponent(filter_tutor_name);
	}
	
	var filter_student_name = $('input[name=\'filter_student_name\']').attr('value');
	if (filter_student_name) {
		url += '&filter_student_name=' + encodeURIComponent(filter_student_name);
	}
	
	var filter_session_date = $('input[name=\'filter_session_date\']').attr('value');
	if (filter_session_date) {
		url += '&filter_session_date=' + encodeURIComponent(filter_session_date);
	}
	
	var filter_session_notes = $('input[name=\'filter_session_notes\']').attr('value');
	if (filter_session_notes) {
		url += '&filter_session_notes=' + encodeURIComponent(filter_session_notes);
	}
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-'});
});
//--></script>
<?php echo $footer; ?>