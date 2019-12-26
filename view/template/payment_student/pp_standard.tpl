<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($testmode) { ?>
  <div class="warning"><?php echo $text_testmode; ?></div>
<?php } ?>
</form>
<form action="<?php echo $action; ?>" method="post" id="checkout">
  <?php 
  print_r($fields);
  foreach ($fields as $key => $value) { ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
  <?php } ?>
</form>
<form>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript">
var click_once = 1;
<!--
function confirmSubmit() {
	if(click_once) {
		$.ajax({
			type: 'GET',
			url: 'index.php?route=payment_student/pp_standard/confirm',
			success: function() {
				if (<?php echo (float)$total; ?>) {
					$('#checkout').submit();
				} else {
					location = '<?php echo $continue; ?>';
				}
			}
		});
	
		click_once = 0;
	}
}
//--></script>