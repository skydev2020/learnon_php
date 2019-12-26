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
        
       <!-- Commented by Softronikx
	   <tr>
          <td><span class="required">*</span> <?php echo $entry_date; ?></td>
          <td colspan="2"><input type="text" id="date" name="date" value="<?php echo $date; ?>" />
            <?php if ($error_date) { ?>
            <span class="error"><?php echo $error_date; ?></span>
            <?php } ?></td>
        </tr>
		-->
		
        <tr>
          <td><?php echo $entry_name; ?></td>
          <td><?php echo $entry_amount; ?></td>
          <td><?php echo $entry_detail; ?></td>
		   <td><?php echo $entry_date; ?></td>
        </tr>
        <?php foreach($all_expenses as $key => $each_expense) {?>
        <tr>
          <td><?php echo $each_expense; ?> <input name="all_expenses[]" type="hidden" id="all_expenses[]" value="<?php echo $each_expense; ?>" /></td>
          <td><input name="all_amounts[<?=$key?>]" type="text" id="all_amounts[]" value="<?php echo $all_amounts[$key]; ?>" /></td>
          <td><input name="all_details[<?=$key?>]" type="text" id="all_details[]" value="<?php echo $all_details[$key]; ?>" size="50" /></td>
		  <!-- Softronikx Technologies -->
		  <td><input name="all_dates[<?=$key?>]" type="text" id="all_dates[<?=$key?>]" value="" size="50" class="date"/></td>
        </tr>
        <?php } ?>
        <tr>
          <td colspan="3"><div id="addhere"></div></td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="2"><label>
          	<div class="buttons"><a onclick="addmore();" class="button"><span>Add New Expense</span></a></div>            
          </label></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--

function addmore() {
	
	var new_row = '<table class="form">' +
		'<tr>' +
          '<td><input name="all_expenses[]" type="text" id="all_expenses[]" value="" /></td>' +
          '<td><input name="all_amounts[]" type="text" id="amount[]" value="" /></td>' +
          '<td><input name="all_details[]" type="text" id="detail[]" value="" size="50" /></td>' +
		  '<td><input name="all_dates[]" type="text" id="all_dates[]" value="" class="date" size="20" /></td>' +
        '</tr>' +
		'</table>';
	
//	alert(new_row);
	
	$("#addhere").append(new_row);
}
		
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.date').datepicker({dateFormat: 'yy-mm-dd'}); <!-- Softronikx Technologies -->
});
//--></script>
<?php echo $footer; ?>