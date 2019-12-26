  <h1>LearnOn Client Agreement</h1>    
	  <table>
        <tr>
          <td><?php echo $entry_username; ?></td>
		  <td>&nbsp;</td>
          <td><?php echo $username; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
          <td>IP Address</td>
		  <td></td>
          <td><?php echo $ip; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
          <td height="40px">Signup Date</td>
		  <td></td>
          <td><?php echo $date_added; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_firstname; ?></td>
		  <td></td>
          <td><?php echo $firstname; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_lastname; ?></td>
		  <td></td>
          <td><?php echo $lastname; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_telephone; ?></td>
		  <td></td>
          <td><?php echo $home_phone; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_cellphone; ?></td>
		  <td>&nbsp;&nbsp;&nbsp;</td>
          <td><?php echo $cell_phone; ?></td>
        </tr>
       <tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_address_1; ?></td>
		  <td></td>
          <td><?php echo $address; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_city; ?></td>
		  <td></td>
          <td><?php echo $city; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_zone; ?></td>
		  <td></td>
          <td><?php echo $state; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_postcode; ?></td>
		  <td></td>
          <td><?php echo $pcode; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_country; ?></td>
		  <td></td>
          <td><?php echo $country; ?></td>
        </tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
        <tr>
          <td height="40px"><?php echo $entry_email; ?></td>
		  <td>&nbsp;</td>
          <td><?php echo $email; ?></td>
        </tr>
        </table>
		<?php 
		$str = array("<u1:p>", "</u1:p>", "<o:p>","</o:p>","clear: both");
		$replace   = array("", "", "","","");
		echo str_replace($str, $replace,html_entity_decode($agreement, ENT_COMPAT, 'UTF-8')); ?>