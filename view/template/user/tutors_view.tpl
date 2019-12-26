<?php echo $header; ?>
<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
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
          <td><span class="required">*</span> <?php echo $entry_username; ?></td>
          <td><?php echo $username; ?>
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><?php echo $firstname; ?>
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><?php echo $lastname; ?>
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><?php echo $home_phone; ?>
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_cellphone; ?></td>
          <td><?php echo $cell_phone; ?>
            <?php if ($error_cellphone) { ?>
            <span class="error"><?php echo $error_cellphone; ?></span>
            <?php } ?></td>
        </tr>
       
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><?php echo $address; ?>
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><?php echo $city; ?>
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><?php echo $state; ?>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><?php echo $pcode; ?>
            <?php if ($error_pcode) { ?>
            <span class="error"><?php echo $error_pcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td>
			  <?php echo $country; ?>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><?php echo $email; ?>
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_notes; ?></td>
          <td><?php echo $users_note; ?>
            <?php if ($error_notes) { ?>
            <span class="error"><?php echo $error_notes; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_post_secondary_education; ?></td>
          <td><?php echo $post_secondary_education; ?>
            <?php if ($error_post_secondary_education) { ?>
            <span class="error"><?php echo $error_post_secondary_education; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_subjects_studied; ?></td>
          <td><?php echo $subjects_studied; ?>
            <?php if ($error_subjects_studied) { ?>
            <span class="error"><?php echo $error_subjects_studied; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_courses_available; ?></td>
          <td><?php echo $courses_available; ?>
            <?php if ($error_courses_available) { ?>
            <span class="error"><?php echo $error_courses_available; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_previous_experience; ?></td>
          <td><?php echo $previous_experience; ?>
            <?php if ($error_previous_experience) { ?>
            <span class="error"><?php echo $error_previous_experience; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_cities; ?></td>
          <td><?php echo $cities; ?>
            <?php if ($error_cities) { ?>
            <span class="error"><?php echo $error_cities; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_references; ?></td>
          <td><?php echo $references; ?>
            <?php if ($error_references) { ?>
            <span class="error"><?php echo $error_references; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_gender; ?></td>
          <td><?php echo $gender; ?>
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
        <tr>
          <td><?php echo $entry_approved; ?></td>
          <td><select name="approved" >
              <?php if ($approved) { ?>
              <option value="0"><?php echo $text_disabled; ?></option>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <?php } else { ?>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <option value="1"><?php echo $text_enabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>        
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="status">
              <?php if ($status) { ?>
              <option value="0"><?php echo $text_disabled; ?></option>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <?php } else { ?>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <option value="1"><?php echo $text_enabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>