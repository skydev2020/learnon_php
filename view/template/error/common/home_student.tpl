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
    <h1 style="background-image: url('view/image/home.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
      <div style="float: left; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_overview; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; ">
          <table border="0" cellpadding="2" style="width: 100%;">
            <tr>
              <td width="40%"><?php echo $text_last_login; ?></td>
              <td align="right"><?php echo $last_login; ?></td>
			</tr>
            <tr>
              <td><?php echo $text_total_report_card; ?></td>
              <td align="right"><?php echo $total_report_card; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_students; ?></td>
              <td align="right"><?php echo $total_tutors; ?></td>
            </tr>
            <!-- <tr>
              <td><?php echo $text_total_notifications; ?></td>
              <td align="right"><?php echo $total_notifications; ?></td>
            </tr> -->
            <tr>
              <td><?php echo $text_active_status; ?></td>
              <td align="right"><?php echo $active_status; ?></td>
            </tr>
            <tr>
              <td colspan="2" align="right">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="left">
              	<div class="buttons">
              		<?php foreach($all_status as $each_status) { ?>
              			<a class="button" href="<?=$each_status['link'];?>"><span><?=$each_status['text'];?></span></a> - <?=$each_status['help'];?><br /><br />              		
              		<?php } ?>              		              		
              	</div></td>
            </tr>
          </table>
        </div>
      </div>
	  
      <div style="float: right; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold; height:20px;"><div style="float:left; width:200px;"><?php echo $text_latest_notifications; ?></div><div style="float:right;width:60px;"><a href="index.php?route=cms/notifications&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
              <td class="left"><?php echo $column_notification_from; ?></td>
              <td class="left"><?php echo $column_subject; ?></td>
              <td class="center"><?php echo $column_date_send; ?></td>
                <td class="right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($notifications) { ?>
              <?php foreach ($notifications as $notification) { ?>
              <tr>
                <td class="left"><?php echo $notification['notification_from']; ?> [<?php echo $notification['group_name']; ?>]</td>
                <td class="left"><?php echo $notification['subject']; ?></td>
                <td class="center"><?php echo $notification['date_send']; ?></td>
                <td class="right"><?php foreach ($notification['action'] as $action) { ?>
                  [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
	  
    </div>
    
	<br />
    <div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
      <div style="float: left; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_5_invoices; ?><div style="float:right;width:60px;"><a href="index.php?route=student/invoice&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 210px;">
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_invoice_num; ?></td>
              <td class="left"><?php echo $column_invoice_date; ?></td>
              <td class="center"><?php echo $column_total_amount; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($myinvoices) { ?>
            <?php foreach ($myinvoices as $myinvoice) { ?>
            <tr>          
              <td class="left"><?php echo $myinvoice['invoice_num']; ?></td>
              <td class="left"><?php echo $myinvoice['invoice_date']; ?></td>
			  <td class="center"><?php echo $myinvoice['total_amount']; ?></td>
              <td class="right"><?php foreach ($myinvoice['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        </div>
      </div>
      
      <div style="float: right; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_5_students; ?><div style="float:right;width:60px;"><a href="index.php?route=student/mytutors&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 210px;">
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_tutor_name; ?></td>
              <td class="left"><?php echo $column_status; ?></td>
              <td class="center"><?php echo $column_date_added; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($mytutors) { ?>
            <?php foreach ($mytutors as $mytutor) { ?>
            <tr>
              <td class="left"><?php echo $mytutor['tutor_name']; ?></td>
              <td class="left"><?php echo $mytutor['status']; ?></td>
			  <td class="center"><?php echo $mytutor['date_added']; ?></td>
              <td class="right"><?php foreach ($mytutor['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        </div>
      </div>
    
    </div>
    
    
	
  </div>
</div>
<?php echo $footer; ?>
