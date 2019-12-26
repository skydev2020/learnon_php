<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
	<!-- <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a> -->
	</div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $income_name; ?></td>
          <td><input type="text" name="name" id="expense_name" value="<?php echo $name; ?>" />	
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $income_date; ?></td>
          <td><input type="text" id="date" name="date" value="<?php echo $date; ?>" />
            <?php if ($error_date) { ?>
            <span class="error"><?php echo $error_date; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $income_amount; ?></td>
          <td><input type="text" name="amount" value="<?php echo $amount; ?>" />
            <?php if ($error_amount) { ?>
            <span class="error"><?php echo $error_amount; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $income_detail; ?></td>
          <td><textarea type="text" name="detail" cols="50" rows="5"><?php echo $detail; ?></textarea>
            <?php if ($error_detail) { ?>
            <span class="error"><?php echo $error_detail; ?></span>
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