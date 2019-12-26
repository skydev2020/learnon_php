<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
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
            <td><?php echo $entry_student_name; ?></td>
            <td><?php echo $student_name; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_topic; ?></td>
            <td><?php echo $topic; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_description; ?></td>
            <td><?php echo nl2br($description); ?></td>
        </tr>
          <tr>
            <td valign="top"><?php echo $entry_format; ?></td>
            <td><?=$format?></td>
        </tr>
        <tr>
          <td><?php echo $entry_tutor_price; ?></td>
          <td>$<?php echo $tutor_price; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_assigned; ?></td>
          <td><?php echo $date_assigned; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_completed; ?></td>
          <td><input name="date_completed" type="text" id="date_completed" value="<?php echo $date_completed; ?>" class="calender" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_due_date; ?></td>
		  <td><?php echo $due_date; ?></td>
		  </tr>
          <tr>
            <td valign="top"><?php echo $entry_status; ?></td>
            <td><select name="status" id="status">
            		<option value="FALSE"><?php echo $text_select; ?></option>
	                <?php foreach ($all_status as $each_row) { ?>
	                <?php if ($each_row['status_id'] == $status) { ?>
	                <option value="<?php echo $each_row['status_id']; ?>" selected="selected"><?php echo $each_row['status_name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $each_row['status_id']; ?>"><?php echo $each_row['status_name']; ?></option>
	                <?php } ?>
	                <?php } ?>
              </select></td>
          </tr>	
			<tr>
            <td valign="top">Assignments Uploaded:</td>
            <td><?php foreach ($attachments_info as $each_row) { 
					echo "<a href='http://www.learnon.ca/download.php?essay=".$each_row['essay_id']."&assign=".$each_row['assignment_name']."'>".$each_row['assignment_name']."</a> &nbsp;&nbsp;&nbsp; <a title='delete' href='http://www.learnon.ca/delete.php?essay=".$each_row['essay_id']."&assign=".$each_row['assignment_name']."&token=".$_GET['token']."&essay_id=".$_GET['essay_id']."'>X</a> </br>";
	                } ?>
              </td>
          </tr>	
		  
      </table>
	  </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.calender').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>