<?php echo $header; ?>
<script type="text/javascript">
    $(function() {
        $("#telephone").mask("999 999 9999");
        $("#cellphone").mask("999 999 9999");
		$("#postcode").mask("*** ***");
		
		$("#postcode").focusout(function() {
			$("#postcode").val($("#postcode").val("").toUpperCase());
		});
    });
	
</script>
<div id="content"><br />
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title_tutor; ?></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="create">
      <p><?php echo $text_account_already; ?></p>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_details; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
        <tr>
      		<td colspan="2">
      			<h3 style="width:95%;color:#668CBF" ><?php echo $entry_header; ?></h3>
      		</td>
      	</tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
            <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
              <?php if ($error_firstname) { ?>
              <span class="error"><?php echo $error_firstname; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
            <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
              <?php if ($error_lastname) { ?>
              <span class="error"><?php echo $error_lastname; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
            <td><input type="text" name="home_phone" id="telephone" value="<?php echo $home_phone; ?>" />
              <?php if ($error_telephone) { ?>
              <span class="error"><?php echo $error_telephone; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_cellphone; ?></td>
            <td><input type="text" name="cell_phone" id="cellphone" value="<?php echo $cell_phone; ?>" />
              <?php if ($error_cellphone) { ?>
              <span class="error"><?php echo $error_cellphone; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_address; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
            <td><input type="text" name="address" size="50" value="<?php echo $address; ?>" />
              <?php if ($error_address_1) { ?>
              <span class="error"><?php echo $error_address_1; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_city; ?></td>
            <td><input type="text" name="city" value="<?php echo $city; ?>" />
              <?php if ($error_city) { ?>
              <span class="error"><?php echo $error_city; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
            <td><select name="state">
            	<?php /* ?>
                <option value=""><b>---Select A Province---</b></option>
                <option <?php if($state=="Alberta" || $state=='AB')echo "selected";?>>Alberta</option>
                <option <?php if($state=="British ColumbiaCana")echo "selected";?>>British ColumbiaCana</option>
                <option <?php if($state=="Manitoba")echo "selected";?>>Manitoba</option>
                <option <?php if($state=="New Brunswick")echo "selected";?>>New Brunswick</option>
                <option <?php if($state=="Ontario")echo "selected";?>>Ontario</option>
                <option <?php if($state=="Quebec")echo "selected";?>>Quebec</option>
                <option <?php if($state=="Saskatchewan")echo "selected";?>>Saskatchewan</option>
                <option value=""><b>---Select A State---</b></option>
                <option <?php if($state=="Illinois")echo "selected";?>>Illinois</option>
                <option <?php if($state=="Indiana")echo "selected";?>>Indiana</option>
                <option <?php if($state=="Massachusetts")echo "selected";?>>Massachusetts</option>
                <option <?php if($state=="Michigan")echo "selected";?>>Michigan</option>
                <option <?php if($state=="New Jersey")echo "selected";?>>New Jersey</option>
                <option <?php if($state=="New York")echo "selected";?>>New York</option>
                <option <?php if($state=="North Carolina")echo "selected";?>>North Carolina</option>
                <option <?php if($state=="Ohio")echo "selected";?>>Ohio</option>
                <option <?php if($state=="Pennsylvania")echo "selected";?>>Pennsylvania</option>
                <option <?php if($state=="Virginia")echo "selected";?>>Virginia</option>
                <option <?php if($state=="Washington")echo "selected";?>>Washington</option>
                <option <?php if($state=="Wisconsin")echo "selected";?>>Wisconsin</option>
                <?php */ ?>
                <?php echo $list_states; ?>
              </select>
              <?php if ($error_zone) { ?>
              <span class="error"><?php echo $error_zone; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
            <td><input type="text" name="pcode" id="postcode" value="<?php echo $pcode; ?>" />
              <?php if ($error_pcode) { ?>
              <span class="error"><?php echo $error_pcode; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_country; ?></td>
            <td><select name="country">
            	<option value="" >-- Select Country --</option>
            	<?php echo $list_country;?>
            	<?php /* ?>
                <option value="" >-- Select Country --</option>
                <option <?php if($country=="Canada")echo "selected";?>>Canada</option>
                <option <?php if($country=="USA")echo "selected";?>>USA</option>
                <?php */ ?>
              </select>
              <?php if ($error_country) { ?>
              <span class="error"><?php echo $error_country; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_password; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_password; ?></td>
            <td><input type="password" name="password" value="<?php echo $password; ?>"  />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
            <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
              <?php if ($error_confirm) { ?>
              <span class="error"><?php echo $error_confirm; ?></span>
              <?php  } ?></td>
          </tr>
        </table>
      </div>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_newsletter; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_notes; ?><br />
              <textarea name="users_note" rows="4" cols="60"><?php echo $users_note; ?></textarea>
              <?php if ($error_notes) { ?>
              <span class="error"><?php echo $error_notes; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_post_secondary_education; ?><br />
              <textarea name="post_secondary_education" rows="4" cols="60"><?php echo $post_secondary_education; ?></textarea>
              <?php if ($error_post_secondary_education) { ?>
              <span class="error"><?php echo $error_post_secondary_education; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_subjects_studied; ?><br />
              <textarea name="subjects_studied" rows="4" cols="60"><?php echo $subjects_studied; ?></textarea>
              <?php if ($error_subjects_studied) { ?>
              <span class="error"><?php echo $error_subjects_studied; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_courses_available; ?><br />
              <textarea name="courses_available" rows="4" cols="60"><?php echo $courses_available; ?></textarea>
              <?php if ($error_courses_available) { ?>
              <span class="error"><?php echo $error_courses_available; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_previous_experience; ?><br />
              <textarea name="previous_experience" rows="4" cols="60"><?php echo $previous_experience; ?></textarea>
              <?php if ($error_previous_experience) { ?>
              <span class="error"><?php echo $error_previous_experience; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_cities; ?><br />
              <textarea name="cities" rows="4" cols="60"><?php echo $cities; ?></textarea>
              <?php if ($error_cities) { ?>
              <span class="error"><?php echo $error_cities; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><span class="required">*</span> <?php echo $entry_references; ?><br />
              <textarea name="references" rows="4" cols="60"><?php echo $references; ?></textarea>
              <?php if ($error_references) { ?>
              <span class="error"><?php echo $error_references; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td valign="top"><?php echo $entry_gender; ?></td>
            <td><select name="gender">
                <option value="">Select One</option>
                <option <?php if($gender=="Male")echo "selected";?>>Male</option>
                <option <?php if($gender=="Female")echo "selected";?>>Female</option>
              </select>
              <?php if ($error_gender) { ?>
              <span class="error"><?php echo $error_gender; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td valign="top"><span class="required">*</span> <?php echo $entry_certified_teacher; ?></td>
            <td><input name="certified_teacher" type="radio" value="1" <?php if($certified_teacher=="1")echo "checked";?> />
              Yes
              <input name="certified_teacher" type="radio" value="2" <?php if($certified_teacher=="2")echo "checked";?> />
              No
              <?php if ($error_certified_teacher) { ?>
              <span class="error"><?php echo $error_certified_teacher; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td valign="top"><span class="required">*</span> <?php echo $entry_criminal_conviction; ?></td>
            <td><input name="criminal_conviction" type="radio" value="1" <?php if($criminal_conviction=="1")echo "checked";?> />
              Yes
              <input name="criminal_conviction" type="radio" value="2" <?php if($criminal_conviction=="2")echo "checked";?> />
              No
              <?php if ($error_criminal_conviction) { ?>
              <span class="error"><?php echo $error_criminal_conviction; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td valign="top"><span class="required">*</span> <?php echo $entry_background_check; ?></td>
            <td><input name="background_check" type="radio" value="1" <?php if($background_check=="1")echo "checked";?> />
              Yes
              <input name="background_check" type="radio" value="2" <?php if($background_check=="2")echo "checked";?> />
              No
              <?php if ($error_background_check) { ?>
              <span class="error"><?php echo $error_background_check; ?></span>
              <?php } ?>
            </td>
          </tr>
        </table>
      </div>

      <div class="buttons">
        <table>
        <tr>
          <td></td>
          <td><a onclick="$('#create').submit();" class="button"><span><?php echo $button_continue; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></td>
        </tr>
        </table>
      </div>
	  <input type="hidden" name="step" value="1" />
    </form>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 