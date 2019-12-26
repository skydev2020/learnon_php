<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
        <table class="form">
          <tr>
            <td><?php echo $entry_date_send; ?></td>
            <td><?php echo $date_send; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_mail_to; ?></td>
            <td><?php echo $mail_to; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_mail_from; ?></td>
            <td><?php echo $mail_from; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_subject; ?></td>
            <td><?php echo $subject; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_message; ?></td>
            <td><?php echo $message; ?></td>
          </tr>
      </table>
</div>
<?php echo $footer; ?>