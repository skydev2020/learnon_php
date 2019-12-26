<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/information.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
      <table class="list">
        <thead>
          <tr>            
            <td class="left"><?php echo $column_click_month; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($results) { ?>
          <?php foreach ($results as $result) { ?>
          <tr>
            <td class="left"><a href="<?=$pay_link?>&paycheque_id=<?=$result['paycheque_id']?>&month=<?php echo date('m',strtotime($result['paycheque_date'])); ?>&year=<?php echo date('Y',strtotime($result['paycheque_date'])); ?>"><?php echo date('F Y',strtotime($result['paycheque_date'])); ?></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
  </div>
</div>
<?php echo $footer; ?>