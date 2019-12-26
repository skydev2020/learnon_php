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
    <div style="float:left;"><h1 style="background-image: url('view/image/home.png');"><?php echo $heading_title; ?></h1></div><div style="float:right; text-align:right; padding:13px;"><?php echo $text_last_login; ?> <?php echo $last_login; ?></div>
  </div>
  <div class="content">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
      <div style="float: left; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_5_students; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 210px;">
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_student_name; ?></td>
              <td class="left"><?php echo $column_subjects; ?></td>
              <td class="center"><?php echo $column_date_added; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($mystudents) { ?>
            <?php foreach ($mystudents as $mystudent) { ?>
            <tr>
              <td class="left"><?php echo $mystudent['student_name']; ?></td>
              <td class="left"><?php $p=0;foreach($mystudent['subjects'] as $subject){if($p)echo ", ";echo stripslashes($subject['subjects_name']);$p++;} ?></td>
			  <td class="center"><?php echo $mystudent['date_added']; ?></td>
              <td class="right" width="110"><?php foreach ($mystudent['action'] as $key=>$action) { if($key)echo "<br />";?>
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
	
  </div>
</div>
<?php echo $footer; ?>