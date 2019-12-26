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
            <td class="left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>       
			<td class="left"><?php if ($sort == 'subjects') { ?>
              <a href="<?php echo $sort_subjects; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_subjects; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_subjects; ?>"><?php echo $column_subjects; ?></a>
              <?php } ?></td>                                   
            <td class="right"><?php if ($sort == 'price_usa') { ?>
              <a href="<?php echo $sort_price_usa; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price_usa; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_price_usa; ?>"><?php echo $column_price_usa; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'price_can') { ?>
              <a href="<?php echo $sort_price_can; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price_can; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_price_can; ?>"><?php echo $column_price_can; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'price_alb') { ?>
              <a href="<?php echo $sort_price_alb; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price_alb; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_price_alb; ?>"><?php echo $column_price_alb; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($informations) { ?>
          <?php foreach ($informations as $information) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($information['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['package_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['package_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $information['name']; ?></td>
			<td class="left"><?php echo $information['subjects']; ?></td>            
            <td class="right"><?php echo $curr_symbol; ?><?php echo $information['price_usa']; ?></td>
            <td class="right"><?php echo $curr_symbol; ?><?php echo $information['price_can']; ?></td>
            <td class="right"><?php echo $curr_symbol; ?><?php echo $information['price_alb']; ?></td>            
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