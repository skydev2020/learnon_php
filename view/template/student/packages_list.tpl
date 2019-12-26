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
  </div>
  <div class="content">
 
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>              
            <td class="right"><?php if ($sort == 'hours') { ?>
              <a href="<?php echo $sort_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_hours; ?>"><?php echo $column_hours; ?></a>
              <?php } ?></td>
			<td class="right"><?php if ($sort == 'price') { ?>
              <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
              <?php } ?></td>              
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($informations) { ?>
          <?php foreach ($informations as $information) { ?>
          <tr>
            <td class="left"><?php echo $information['name']; ?></td>
            <td class="right"><?php echo $information['hours']; ?></td>
            <td class="right"><?php echo $curr_symbol; ?><?php echo $information['price']; ?></td>
            <td class="right"><?php foreach ($information['action'] as $action) { ?>
              [ <a href="javascript:void(0)" onclick="alert('*** Buying Packages Online is Currently not Working ***')"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colh2="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
	<style>
	#pck
	{
		    margin-left: 140px;

	}
	</style>
	<h1>*** Buying Packages Online is Currently not Working ***</h1>
	<h2>To Pay For a Package You Can:-</h2>
	<h3>1. Send an email transfer to:- info@LearnOn.ca</h3>
	<h3>2. Send a cheque to:- </h3><div id="pck"><h4>LearnOn! Tutorial</h4><h4>#432 North Service Road West</h4><h4>OakVille,ON  L6M 2Y1</h4><h4>Canada</h4></div>
	<h3>3. To pay with credit card:- http://www.learnon.ca/pay_invoice</h3> <h4>Enter The Student's Name In The Invoice Box</h4>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>