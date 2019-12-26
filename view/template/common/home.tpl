<?php echo $header; ?>
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
			
			<?php if ($combined_data) { ?>
			<?php foreach ($combined_data as $datan) { 
				if($datan['type'] == 'notification') {
			?>
				<tr>
				<td class="left"><?php echo $datan['notification_from']; ?> [<?php echo $datan['group_name']; ?>]</td>
				<td class="left"><?php echo $datan['subject']; ?></td>
				<td class="center"><?php echo $datan['date_send']; ?></td>
				<td class="right"><?php foreach ($datan['action'] as $action) { ?>
				  [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
				  <?php } ?></td>
				</tr>
			<?php 
				} else { 
			?>
				<tr>
				<td class="left"><?php echo $datan['name']; ?></td>
				<td class="left">New Registration</td>
				<td class="center"><?php echo $datan['date_added']; ?></td>
				<td class="right"><?php foreach ($datan['action'] as $action) { ?>
				  [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
				  <?php } ?></td>
				</tr>
			<?php
				}	
			}  
			} else { ?>
		  <tr>
			<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
		  </tr>
		  <?php } ?>
			
			
			
			
		<?php /* 	
			
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
		 */ ?>	  
			  
            </tbody>
          </table>
        </div>
		
		
      </div>
	  
      <div style="float: right; width: 49%;">
			
			<div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_overview; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 210px;">
          <table border="0" cellpadding="2" style="width: 100%;">
            <tr bgcolor="#F5F5F5">
              <td width="46%"><?php echo $text_last_login; ?></td>
              <td align="right"><?php echo $last_login; ?></td>
			</tr>
            <tr bgcolor="#F5F5F5">
              <td><?php echo $text_total_students; ?></td>
              <td align="right"><?php echo $total_students; ?></td>
            </tr>
            <tr bgcolor="#F5F5F5">
              <td><?php echo $text_total_tutors; ?></td>
              <td align="right"><?php echo $total_tutors; ?></td>
            </tr>
            <tr bgcolor="#F5F5F5">
              <td><?php echo $text_received_class; ?></td>
              <td align="right"><?php echo $total_received_class; ?>%</td>
            </tr>
             <tr bgcolor="#F5F5F5">
              <td><?php echo $text_total_students_curryear; ?></td>
              <td align="right"><?php echo $total_students_curryear; ?></td>
            </tr> 
             <tr bgcolor="#F5F5F5">
              <td><?php echo $text_total_tutors_curryear; ?></td>
              <td align="right"><?php echo $total_tutors_curryear; ?></td>
            </tr> 
            <tr bgcolor="#F5F5F5">
              <td><?php echo $text_received_class_curryear; ?></td>
              <td align="right"><?php echo $total_received_class_curryear; ?>%</td>
            </tr> 
          </table>
        </div>
			
			
        
      </div>
	  
    </div>


<?php /* 		
<div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
	<noscript>
      <div style="float: left; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_10_sessions; ?><div style="float:right;width:60px;"><a href="index.php?route=user/students&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 210px;">
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_student_name; ?></td>
              <td class="center"><?php echo $column_session_date; ?></td>
              <td class="left"><?php echo $column_session_duration; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($sessions) { ?>
            <?php foreach ($sessions as $session) { ?>
            <tr>
              <td class="left"><?php echo $session['student_name']; ?></td>
              <td class="center"><?php echo $session['session_date']; ?></td>
              <td class="left"><?php switch($session['session_duration']){case "0.5":echo "30 Minutes";break;case "1.0":echo "1 Hour";break;case "1.5":echo "1 Hour + 30 Minutes";break;case "2.0":echo "2 Hours";break;case "2.5":echo "2 Hours + 30 Minutes";break;case "3.0":echo "3 Hours";break;case "3.5":echo "3 Hours + 30 Minutes";break;case "4.0":echo "4 Hours";break;case "4.5":echo "4 Hours + 30 Minutes";break;case "5.0":echo "5 Hours";break;}; ?></td>
              <td class="right"><?php foreach ($session['action'] as $action) { ?>
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
	  </noscript>
	  
	  <div style="float: left; width: 49%;">
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_user_registrations; ?><div style="float:right;width:60px;"><a href="index.php?route=user/students&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; ">
          <table class="list">
            <thead>
              <tr>
              <td class="left"><?php echo $column_user_name; ?></td>
              <td class="center"><?php echo $column_date_registration; ?></td>
                <td class="right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($users) { ?>
              <?php foreach ($users as $user) { ?>
              <tr>
                <td class="left"><?php echo $user['name']; ?></td>
                <td class="center"><?php echo $user['date_added']; ?></td>
                <td class="right"><?php foreach ($user['action'] as $action) { ?>
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
        <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_5_assignments; ?><div style="float:right;width:60px;"><a href="index.php?route=tutor/assignment&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 210px;">
          <table class="list">
            <thead>
              <tr>
				<td class="left"><?php echo $column_tutor_name; ?></td>
                <td class="left"><?php echo $column_student_name; ?></td>
                <td class="center"><?php echo $column_date_added; ?></td>
                <td class="right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($mystudents) { ?>
              <?php foreach ($mystudents as $mystudent) { ?>
              <tr>
                <td class="left"><?php echo $mystudent['tutor_name']; ?></td>
                <td class="left"><?php echo $mystudent['student_name']; ?></td>
                <td class="center"><?php echo $mystudent['date_added']; ?></td>
                <td class="right"><?php foreach ($mystudent['action'] as $action) { ?>
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
	
	
    <div>
      <div style="background: #668EC1; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_10_orders; ?><div style="float:right;width:60px;"><a href="index.php?route=sale/order&token=<?=$token?>" style="color:#fff;"><?=$text_view_all?></a></div></div>
      <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
        <table class="list">
          <thead>
            <tr>
              <td class="right"><?php echo $column_order; ?></td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="left"><?php echo $column_type; ?></td>
              <td class="left"><?php echo $column_method; ?></td>
              <td class="left"><?php echo $column_status; ?></td>
              <td class="left"><?php echo $column_date_placed; ?></td>
              <td class="right"><?php echo $column_total; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="right"><?php echo $order['order_id']; ?></td>
              <td class="left"><?php echo $order['name']; ?></td>
              <td class="left"><?php echo $order['type']; ?></td>
              <td class="left"><?php echo $order['method']; ?></td>              
              <td class="left"><?php echo $order['status']; ?></td>
              <td class="left"><?php echo $order['date_added']; ?></td>
              <td class="right"><?php echo $order['total']; ?></td>
              <td class="right"><?php foreach ($order['action'] as $action) { ?>
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
	<br />    
    

    <div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
      
	  
    </div>
	
  </div>
 */ ?>	 
</div>
<?php echo $footer; ?>