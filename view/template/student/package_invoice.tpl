<?php if($is_print) {
		echo $header;
	} else {
?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<? } ?>
<div class="box">
  
<!--<div class="left"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
  </div> 
  <div class="right"></div>-->

  <?php if($is_print) {?>
  <div class="buttons" style="text-align:right"><a href="<?=$print_invoice?>" target="_blank" class="button"><span><?php echo $button_print; ?></span></a></div>  
  <?php } ?>
  <div class="top_box1">
  		<h1>TUTORING INVOICE</h1><br />

  		<h2 style="font-weight: bold">INVOICE # <?=$invoice_info['invoice_num']?><br />

		DATE ISSUED: <?=$invoice_info['send_date']?></h2>
  </div>
  
    <div class="top_box2">
  		
  		<h2>Bill To:</h2>
		
        <p><?=$student_info['parent_name']?><br />
		<?=$student_info['student_name']?><br />
		<?=$student_info['street_address']?><br />
		<?=$student_info['city']?>, <?=$student_info['state']?><br />
		<?=$student_info['postcode']?>
		</p>
  </div>
  <?php if(count($package_detail) > 0) { ?>  
  <table border="1" cellspacing="0" class="form1" width="100%" bordercolor="#000" style="border-collapse:collapse;">
    <tr class="clr">
        <td class="thead">PACKAGE NAME</td>
        <td class="thead">TOTAL HOURS</td>
    </tr>
	<tr>
        <td class="lt"><?=$package_detail['package_name']?></td>
      	<td class="cen"><?=$package_detail['total_hours']?></td>
    </tr>
    </table>
	<?php } ?>  
</div>

   <div class="bot_box">		
        <p><?=$config_name;?><br />
		<?=$config_address;?><br /><br />
		</p>
  </div>

</div>
<?php if($is_print) {
		echo $footer;
	} else {
?>
<script>
	window.print();
</script>
<? } ?>