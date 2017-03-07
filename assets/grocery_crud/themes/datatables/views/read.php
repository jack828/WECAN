<?php

	$this->set_css($this->default_theme_path.'/datatables/css/datatables.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/datatables/js/datatables-edit.js');
	$this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>

<div class="x_panel">
  <div class="x_title">
    <h2><?php echo $this->l('list_record'); ?> <?php echo $subject?></h2>
    <div class="clearfix"></div>
  </div>
  <div class="x_content">
    <?php echo form_open($read_url, 'class="form-horizontal form-label-left" method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>

      <?php foreach($fields as $field) { ?>
        <div class='form-group' id="<?php echo $field->field_name; ?>_field_box">
          <label class='control-label col-md-3 col-sm-3 col-xs-12' id="<?php echo $field->field_name; ?>_display_as_box" for="<?php echo $field->field_name; ?>">
            <?php echo $input_fields[$field->field_name]->display_as; ?>
            <?php echo ($input_fields[$field->field_name]->required) ? "<span class='required'>*</span> " : "" ?> :
          </label>
          <div class='col-md-6 col-sm-6 col-xs-12' id="<?php echo $field->field_name; ?>_input_box">
            <?php echo $input_fields[$field->field_name]->input; ?>
          </div>
        </div>
      <?php }?>
          <!-- <input type="text" id="first-name" required="required" class="form-control col-md-7 col-xs-12"> -->

      <!-- Start of hidden inputs -->
      <?php
        foreach ($hidden_fields as $hidden_field) {
          echo $hidden_field->input;
        }
      ?>
      <!-- End of hidden inputs -->
      <?php if ($is_ajax) { ?>
        <input type="hidden" name="is_ajax" value="true" />
      <?php }?>

      <div class="ln_solid"></div>
      <div id='report-error' class='report-div error'></div>
      <div id='report-success' class='report-div success'></div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <input role='button' type='button' value='<?php echo $this->l('form_back_to_list'); ?>' class='btn btn-primary back-to-list' id="cancel-button" />
        </div>
      </div>
    </form>
  </div>
</div>

<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
