<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <?php echo $text_payable; ?><br /><br />
  <?php echo $payable; ?><br />
  <?php echo $text_address; ?>
  <?php echo $address; ?><br />
  <br />
  <?php echo $text_payment; ?><br /><br /><br />
  </div>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a id="checkout" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<script type="text/javascript"><!--
$('#checkout').click(function() {
	$.ajax({ 
		type: 'GET',
		url: 'index.php?route=payment_student/cheque/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script>
