<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<?php foreach ($orders as $order) { ?>
<div style="page-break-after: always;">
  <h1><?php echo $text_invoice; ?></h1>
  <div class="div1">
    <table width="100%">
      <tr>
        <td><?php echo $order['store_name']; ?><br />
          <?php echo $order['store_address']; ?><br />
          <?php echo $text_telephone; ?> <?php echo $order['store_telephone']; ?><br />
          <?php echo $order['store_email']; ?><br />
          <?php echo $order['store_url']; ?></td>
        <td align="right" valign="top"><table>
            <tr>
              <td><b><?php echo $text_date_added; ?></b></td>
              <td><?php echo $order['date_added']; ?></td>
            </tr>
            <?php if ($order['invoice_id']) { ?>
            <tr>
              <td><b><?php echo $text_invoice_id; ?></b></td>
              <td><?php echo $order['invoice_id']; ?></td>
            </tr>
			<?php if ($order['invoice_date']) { ?>
			<tr>
              <td><b><?php echo $text_invoice_date; ?></b></td>
              <td><?php echo $order['invoice_date']; ?></td>
            </tr>
            <?php } ?>
			<?php } ?>
            <tr>
              <td><b><?php echo $text_order_id; ?></b></td>
              <td><?php echo $order['order_id']; ?></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </div>
  <table class="address">
    <tr class="heading">
      <td width="50%"><b><?php echo $text_to; ?></b></td>
      <td width="50%"><b><?php echo $column_product; ?></b></td>
    </tr>
    <tr>
      <td>
      	<?php echo $order['customer']['firstname']." ".$order['customer']['lastname']; ?><br/>
        <?php echo $order['customer']['address']; ?><br/>
        <?php echo $order['customer']['city']; ?><br/>
        <?php echo $order['customer']['state']; ?><br/>
        <?php echo $order['customer']['country']; ?><br/>
        <?php echo $order['customer']['email']; ?><br/>
        <?php echo $order['customer']['home_phone']; ?>, <?php echo $order['customer']['cell_phone']; ?>
      </td>
      <td><?php echo $order['package']['package_name']; ?><br/>
      	  <?php echo $order['package']['package_description']; ?></td>
    </tr>
    <?php foreach ($order['total'] as $total) { ?>
    <tr>
      <td align="right" colspan="2"><b><?php echo $total['title']; ?></b> <?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </table>
  <table class="product">
    <tr class="heading">
      <td><b><?php echo $column_comment; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order['comment']; ?></td>
    </tr>
  </table>
</div>
<?php } ?>
</body>
</html>