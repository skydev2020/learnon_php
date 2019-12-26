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
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>
			<td class="left"><?php if ($sort == 'tutor_name') { ?>
              <a href="<?php echo $sort_tutor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tutor_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_tutor_name; ?>"><?php echo $column_tutor_name; ?></a>
              <?php } ?></td>              
            <td class="left"><?php if ($sort == 'topic') { ?>
              <a href="<?php echo $sort_topic; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_topic; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_topic; ?>"><?php echo $column_topic; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'date_assigned') { ?>
              <a href="<?php echo $sort_date_assigned; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_assigned; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_assigned; ?>"><?php echo $column_date_assigned; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'date_due') { ?>
              <a href="<?php echo $sort_due_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_due_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_due_date; ?>"><?php echo $column_due_date; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($informations) { ?>
          <?php foreach ($informations as $information) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($information['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['essay_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['essay_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $information['student_name']; ?></td>
            <td class="left"><?php echo $information['tutor_name']; ?></td>
            <td class="left"><?php echo $information['topic']; ?></td>
            <td class="left"><?php echo $information['status']; ?></td>
            <td class="left"><?php echo $information['date_assigned']; ?></td>
            <td class="left"><?php echo $information['due_date']; ?></td>
            <td class="right"><?php foreach ($information['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>