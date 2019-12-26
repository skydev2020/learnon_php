<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
      <table width="100%" cellspacing="0" cellpadding="6">
        <tr>
          <td align="left"><a href="<?=$student_hours?>"><?=$text_student_hours?></a></td>
        </tr>
        <tr>
          <td align="left"><a href="<?=$tutor_hours?>"><?=$text_tutor_hours?></a></td>
        </tr>
        <tr>
          <td align="left"><a href="<?=$monthly_statiscs?>"><?=$text_monthly_statiscs?></a></td>
        </tr>
        <tr>
          <td align="left"><a href="<?=$yearly_statiscs?>"><?=$text_yearly_statiscs?></a></td>
        </tr>
        
        <tr>
          <td align="left"><a href="<?=$monthly_payroll_export?>"><?=$text_monthly_payroll_export?></a></td>
        </tr>
      </table>
  </div>
</div>
<?php echo $footer; ?>