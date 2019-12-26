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
          <td><span class="required">*</span> <?php echo $entry_tutor_name; ?></td>
          <td><select name="tutors_id">
		  	<option value="">-  Select -</option>
			<?php foreach($all_tutors as $tutor){
			  if($tutor['user_id']==$tutors_id){
			 ?>
			<option value="<?=$tutor['user_id']?>" selected="selected"><?=$tutor['name']?></option>
			<? }else{?>
			<option value="<?=$tutor['user_id']?>"><?=$tutor['name']?></option>
			<?php }}?>
			</select>
            <?php if ($error_tutor_name) { ?>
            <span class="error"><?php echo $error_tutor_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_student_name; ?></td>
          <td><select name="students_id" onchange="getStudentRate(this.value);">
		  	<option value="">-  Select -</option>
			<?php foreach($all_students as $student){ 
			  if($student['user_id']==$students_id){
			 ?>
			<option value="<?=$student['user_id']?>" selected="selected"><?=$student['name']?></option>
			<? }else{?>
			<option value="<?=$student['user_id']?>"><?=$student['name']?></option>
			<?php }}?>
			</select>
            <?php if ($error_student_name) { ?>
            <span class="error"><?php echo $error_student_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_wage; ?></td>
          <td><input type="text" name="base_wage" value="<?php echo $base_wage; ?>" /> per hour
            <?php if ($error_wage) { ?>
            <span class="error"><?php echo $error_wage; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_invoice; ?>  <div id="loader" style="float:right;display:none;"><img src="view/image/loading.gif" /></div></td>
          <td><input type="text" name="base_invoice" id="base_invoice" value="<?php echo $base_invoice; ?>" /> per hour
            <?php if ($error_invoice) { ?>
            <span class="error"><?php echo $error_invoice; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_subjects; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($all_subjects as $subject_id => $subject_name) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($subject_id, $all_subject_ids)) { ?>
                <input type="checkbox" name="subjects[]" value="<?php echo $subject_id; ?>" checked="checked" />
                <?php echo stripslashes($subject_name); ?>
                <?php } else { ?>
                <input type="checkbox" name="subjects[]" value="<?php echo $subject_id; ?>" />
                <?php echo stripslashes($subject_name); ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="active">
              <?php if ($active) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			  <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else if($active=="") { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			  <option value="0"><?php echo $text_disabled; ?></option>
              <?php }else { ?>
			  <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select><input type="hidden" name="previous_status" value="<?=$previous_status?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_status_by_tutor; ?></td>
          <td><?php echo $status_by_tutor; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_status_by_student; ?></td>
          <td><?php echo $status_by_student; ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function getStudentRate(student_id) {
	$('#loader').show();
	$.ajax({
	   type: "GET",
	   url: "index.php?route=student/assignment/getStudentRate",
	   data: "token=<?=$token?>&user_id="+student_id,
	   success: function(msg){
//	     alert( "Data Saved: " + msg );
		 if(msg != "" && msg != "0")	   
	   	 	$('#base_invoice').attr('value', msg); 
	   	 	
	     $('#loader').hide();
	   }
 	});
}
//--></script>
<?php echo $footer; ?>