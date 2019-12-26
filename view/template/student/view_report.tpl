<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
<div class="buttons"><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_tutor; ?></td>
          <td><?=$report_info['tutor_name']?></td>
        </tr>
        <tr>
          <td><?php echo $entry_grade; ?></td>
          <td><?=$report_info['grade']?></td>
        </tr>
        <tr>
          <td><?php echo $entry_subjects; ?></td>
          <td><?=stripslashes($report_info['subjects'])?></td>
        </tr>
        <tr>
          <td><?php echo $entry_student_prepared; ?></td>
          <td><?=($report_info['student_prepared'])?"Yes":"No"?></td>
        </tr>
        <tr>
          <td><?php echo $entry_questions_ready; ?></td>
          <td><?=($report_info['questions_ready'])?"Yes":"No"?></td>
        </tr>
        <tr>
          <td><?php echo $entry_pay_attention; ?></td>
          <td><?=($report_info['pay_attention'])?"Yes":"No"?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_weaknesses; ?></td>
          <td><?=nl2br($report_info['weaknesses'])?></td>
        </tr>
        <tr>
          <td><?php echo $entry_listen_to_suggestions; ?></td>
          <td><?=($report_info['listen_to_suggestions'])?"Yes":"No"?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_improvements; ?></td>
          <td><?=nl2br($report_info['improvements'])?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_other_comments; ?></td>
          <td><?=nl2br($report_info['other_comments'])?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>