<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_upload; ?></span></a>
	<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
	</div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $text_income_file_name; ?></td>
          <td><input type="file" name="income_file" id="income_file" /> </td>
		  <td><?php if ($error_income_file) { ?>
            <span class="error"><?php echo $error_income_file; ?></span>
          <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_expense_file_name; ?></td>
          <td><input type="file" id="expense_file" name="expense_file" /></td>
		  <td><?php if ($error_expense_file) { ?>
            <span class="error"><?php echo $error_expense_file; ?></span>
           <?php } ?></td>
        </tr>       
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
function pick_this() {
	var expense = $("#all_expenses").attr('value');
	$("#expense_name").attr('value', expense);
}

$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>