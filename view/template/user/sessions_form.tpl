<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_tutor_student; ?></td>
          <td>
		    <select name="tutors_to_students_id">
		    <option value=""> Select </option>
			<?php foreach($assignments as $assignment){
				if($assignment['tutors_to_students_id']==$tutors_to_students_id){ ?>
			<option value="<?=$assignment['tutors_to_students_id']?>" selected="selected"><?=$assignment['tutor_name']?> =>  
			<?=$assignment['student_name']?></option>
			<?php }else{ ?>
			<option value="<?=$assignment['tutors_to_students_id']?>"><?=$assignment['tutor_name']?> => <?=$assignment['student_name']?></option>	 
			<?php }}?>
			</select>
            <?php if ($error_student) { ?>
            <span class="error"><?php echo $error_student; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_session_date; ?></td>
          <td><input type="text" name="session_date" value="<?php echo $session_date; ?>" size="12" id="date" />
            <?php if ($error_session_date) { ?>
            <span class="error"><?php echo $error_session_date; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_session_duration; ?></td>
          <td>
		         <select name="session_duration">
				<?php foreach($duration_array as $key=>$duration){
					if($session_duration==$key){ ?>
				<option value="<?=$key?>" selected="selected"><?=$duration?></option>
				<?php }else{ ?>
				<option value="<?=$key?>"><?=$duration?></option>	 
				<?php }}?>
		          </select>
            <?php if ($error_session_duration) { ?>
            <span class="error"><?php echo $error_session_duration; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_session_notes; ?></td>
          <td><textarea name="session_notes" cols="50" rows="10"><?php echo stripslashes($session_notes); ?></textarea></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//-->
</script>
<?php echo $footer; ?>