<?php echo $header; ?>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAMlk1Zpbm-7FIaKcOqmPqehSXSHJzY39kjtPSoOon7YbeUAwwjhRyEbbYE-9Y-GyOpEOcx9Rx3ehoQA" type="text/javascript"></script>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <table class="form">
      <tr>
        <td align="right"><?php echo $column_student_name; ?>:</td>
        <td><?php echo $student_info['student_name']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_email; ?></td>
        <td><?php echo $student_info['email']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_wage; ?></td>
        <td><?php echo $student_info['base_wage']; ?> per hour</td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_subjects; ?></td>
        <td><?php $p=0;foreach($arrsubids as $subject){if($p)echo ", ";echo stripslashes($subject['subjects_name']);$p++;} ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_telephone; ?></td>
        <td><?php echo $student_info['home_phone']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_cellphone; ?></td>
        <td><?php echo $student_info['cell_phone']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $text_address; ?>:</td>
        <td><div style="width:200px; height:200px; float:left;"><?php echo $student_info['faddress']; ?><br />
            <a href="javascript:void(0)" onclick="document.getElementById('map_canvas').style.visibility='visible';return false;">View address on google map</a></div>
          <div style="border:1px solid #000; width:480px; height:200px; float:right; visibility:hidden;" id="map_canvas"></div></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $column_date_added; ?>:</td>
        <td><?php echo $date_added; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $column_status_by_tutor; ?>:</td>
        <td><?php echo $student_info['status_by_tutor']; ?></td>
      </tr>
    </table>
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      </tr>
    </table>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
var map1 = null;
var geoXml1; 
var geocoder1 = null;
function initialize1(map_canvas) {
  if (GBrowserIsCompatible()) {
	map1 = new GMap2(document.getElementById(map_canvas));
	map1.setCenter(new GLatLng(37.4419, -122.1419), 11);
	geocoder1 = new GClientGeocoder();
  }
}

function showAddress1(address, zoom) {
  if (geocoder1) {
	geocoder1.getLatLng(
	  address,
	  function(point) {
		if (!point) {
		  alert(address + " not found");
		} else {
		  map1.setCenter(point, zoom);
		  var marker = new GMarker(point);
		  map1.addControl(new GLargeMapControl());
		  map1.addOverlay(marker);
		  map1.openInfoWindow(map1.getCenter(),
                   document.createTextNode(address));

		}
	  }
	);
  }
} 
initialize1("map_canvas"); showAddress1("<?php echo $student_info['address'].", ".$student_info['city'].", ".$student_info['state']." ".$student_info['pcode'].", ".$student_info['country']; ?>", 14);
</script>
