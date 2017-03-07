<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover" id="<?php echo $unique_hash; ?>">
	<thead>
		<tr>
			<?php foreach($columns as $column){?>
				<th><?php echo $column->display_as; ?></th>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<th class='actions'><?php echo $this->l('list_actions'); ?></th>
			<?php }?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $num_row => $row){ ?>
		<tr id='row-<?php echo $num_row?>'>
			<?php foreach($columns as $column){?>
				<td><?php echo $row->{$column->field_name}?></td>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td class='actions'>
				<?php // Custom buttons
				if(!empty($row->action_urls)){
					foreach($row->action_urls as $action_unique_id => $action_url){
						$action = $actions[$action_unique_id];
				?>
						<a href="<?php echo $action_url; ?>" class="edit_button btn btn-xs btn-danger" role="button">
							<span class="fa <?php echo $action->css_class; ?> <?php echo $action_unique_id;?>"></span>
              &nbsp;<?php echo $action->label; ?>
						</a>
				<?php }
				}
				?>
				<?php if(!$unset_read){?>
					<a href="<?php echo $row->read_url?>" class="edit_button btn btn-xs btn-dark" role="button">
						<span class="fa fa-eye"></span>
						&nbsp;<?php echo $this->l('list_view'); ?>
					</a>
				<?php }?>

				<?php if(!$unset_edit){?>
					<a href="<?php echo $row->edit_url?>" class="edit_button btn btn-xs btn-dark" role="button">
						<span class="fa fa-pencil"></span>
						&nbsp;<?php echo $this->l('list_edit'); ?>
					</a>
				<?php }?>
				<?php if(!$unset_delete){?>
					<a onclick = "javascript: return delete_row('<?php echo $row->delete_url?>', '<?php echo $num_row?>')"
						href="javascript:void(0)" class="delete_button btn btn-xs btn-danger" role="button">
						<span class="fa fa-trash"></span>
						&nbsp;<?php echo $this->l('list_delete'); ?>
					</a>
				<?php }?>
			</td>
			<?php }?>
		</tr>
		<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<?php foreach($columns as $column){?>
				<th><input type="text" name="<?php echo $column->field_name; ?>" placeholder="<?php echo $this->l('list_search').' '.$column->display_as; ?>" class="search_<?php echo $column->field_name; ?>" /></th>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th>
					<button class="btn btn-xs floatR refresh-data" role="button" data-url="<?php echo $ajax_list_url; ?>">
						<i class="ui-button-icon-primary fa fa-refresh"></i>
					</button>
					<a href="javascript:void(0)" role="button" class="clear-filtering btn btn-xs floatR">
					<a href="javascript:void(0)" role="button" class="clear-filtering btn btn-sm btn-default floatR">
						<i class="fa fa-repeat"></i>
						<?php echo $this->l('list_clear_filtering');?>
					</a>
				</th>
			<?php }?>
		</tr>
	</tfoot>
</table>
