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
    <h1 style="background-image: url('view/image/information.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
			<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="lect"><?php if ($sort == 'notification_from') { ?>
              <a href="<?php echo $sort_notification_from; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_notification_from; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_notification_from; ?>"><?php echo $column_notification_from; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'subject') { ?>
              <a href="<?php echo $sort_subject; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_subject; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_subject; ?>"><?php echo $column_subject; ?></a>
              <?php } ?></td>
            <td class="center"><?php if ($sort == 'date_send') { ?>
              <a href="<?php echo $sort_date_send; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_send; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_send; ?>"><?php echo $column_date_send; ?></a>
              <?php } ?></td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($informations) { ?>
          <?php foreach ($informations as $information) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($information['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['notification_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['notification_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $information['notification_from']; ?> [<?php echo $information['group_name']; ?>]</td>
            <td class="left"><?php echo $information['subject']; ?></td>
            <td class="center"><?php echo $information['date_send']; ?></td>
            <td class="center"><?php foreach ($information['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>