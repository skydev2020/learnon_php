<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_back; ?></span></a></div>
  </div>
  <div class="content">
        <table class="form">
          <tr>
            <td><?php echo $entry_date_added; ?></td>
            <td><?php echo $date_added; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_user_name; ?></td>
            <td><?php echo $user_name; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_group_name; ?></td>
            <td><?php echo $group_name; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_activity; ?></td>
            <td><?php echo $activity; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_activity_details; ?></td>
            <td><?php echo $activity_details; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_ip_address; ?></td>
            <td><?php echo $ip_address; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_platform; ?></td>
            <td><?php echo $platform; ?></td>
          </tr>
      </table>
</div>
<?php echo $footer; ?>