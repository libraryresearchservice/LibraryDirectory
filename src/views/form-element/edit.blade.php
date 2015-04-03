@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li><a href="<?php echo url() ?>/admin/form-element">Form Elements</a></li>
	<li class="active">Edit Form Element</li>
    
@stop

@section('adminContent')

<form class="form-horizontal" id="edit-staff" action="<?php echo url() ?>/admin/form-element/save" method="post" role="form">
    <input type="hidden" name="id" value="<?php echo $element->id ?>">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $element->name ?>">
        </div>
        <div class="form-group">
            <label for="column">Column</label>
            <input type="text" name="code" id="code" class="form-control" value="<?php echo $element->code ?>">
            <?php
			if ( !in_array($element->code, $columns) ) {
				?>
                <br /><div class="alert alert-danger">The column specified above does not exist in table `<?php echo $table ?>`. It must be manually created before this element will work.</div>
                <?php	
			}
			?>
        </div>
        <div class="form-group">
            <label for="data_type">Data Type</label>
            <input type="text" name="data_type" id="data_type" class="form-control" value="<?php echo $element->data_type ?>">
            <?php
			if ( substr($element->data_type, 0, 4) == 'func' && !function_exists(substr($element->data_type, 4)) ) {
				?>
                <br /><div class="alert alert-danger">The function specified above does not exist</div>
                <?php	
			}
			?>
        </div>
        <div class="form-group">
            <label for="display_type">Display Type</label>
            <input type="text" name="display_type" id="display_type" class="form-control" value="<?php echo $element->display_type ?>">
            <?php
			if ( substr($element->display_type, 0, 4) == 'func' && !function_exists(substr($element->display_type, 4)) ) {
				?>
                <br /><div class="alert alert-danger">The function specified above does not exist</div>
                <?php	
			}
			?>
        </div>
        <div class="form-group">
            <label for="input_size">Input Size</label>
            <select class="form-control" name="input_size" id="input_size">
                <option value="">...select...</option>
				<?php
                foreach ( range(0, 12) as $v ) {
                    ?>
                    <option value="<?php echo $v ?>"<?php echo $element->input_size == $v ? ' selected' : '' ?>><?php echo $v ?></option>
                    <?php	
                }
                ?>
            </select>
            <?php
			if ( $element->form_name == '' ) {
				?>
                <br /><div class="alert alert-danger">The element must be assigned to a form in order for it to be displayed</div>
                <?php	
			}
			?>
        </div>
        <div class="form-group">
            <label for="form_name">Form</label>
            <select class="form-control" name="form_name" id="form_name">
                <option value="">...select...</option>
				<?php
                foreach ( $formNames as $v ) {
                    ?>
                    <option value="<?php echo $v ?>"<?php echo $element->form_name == $v ? ' selected' : '' ?>><?php echo ucwords($v) ?></option>
                    <?php	
                }
                ?>
            </select>
            <?php
			if ( $element->form_name == '' ) {
				?>
                <br /><div class="alert alert-danger">The element must be assigned to a form in order for it to be displayed</div>
                <?php	
			}
			?>
        </div>
        <div class="form-group">
            <label for="group_id">Group</label>
            <select class="form-control" name="group_id" id="group_id">
                <option value="">...select...</option>
				<?php
                foreach ( $groups as $v ) {
                    ?>
                    <option value="<?php echo $v->id ?>"<?php echo $element->group_id == $v->id ? ' selected' : '' ?>><?php echo $v->name ?></option>
                    <?php	
                }
                ?>
            </select>
            <?php
			if ( $element->group_id ==0 ) {
				?>
                <br /><div class="alert alert-danger">The element must be part of a group in order for it to be displayed on the form</div>
                <?php	
			}
			?>
        </div>
        <div class="form-group">
        	<label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?php echo $element->description ?></textarea>
        </div>
        <div class="form-group">
            <label for="element_order">Order</label>
            <input type="text" name="element_order" id="element_order" class="form-control" value="<?php echo $element->element_order ?>">
        </div>
        <div class="form-group">
            <label for="element_order">Default Value</label>
            <input type="text" name="default_value" id="default_value" class="form-control" value="<?php echo $element->default_value ?>">
        </div>
        <div class="form-group">
            <label for="is_hidden">Hidden</label>
            <select class="form-control" name="is_hidden" id="is_hidden">
                <?php
                foreach ( array(0 => 'No', 1 => 'Yes') as $k => $v ) {
                    ?>
                    <option value="<?php echo $k ?>"<?php echo $element->is_hidden == $k ? ' selected' : '' ?>><?php echo $v ?></option>
                    <?php	
                }
                ?>
            </select>
        </div>      
        <div class="form-group">
            <label for="is_active">Active</label>
            <select class="form-control" name="is_active" id="is_active">
                <?php
                foreach ( array(0 => 'No', 1 => 'Yes') as $k => $v ) {
                    ?>
                    <option value="<?php echo $k ?>"<?php echo $element->is_active == $k ? ' selected' : '' ?>><?php echo $v ?></option>
                    <?php	
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="is_active">Can be edited by public</label>
            <select class="form-control" name="is_public" id=is_public">
                <?php
                foreach ( array(0 => 'No', 1 => 'Yes') as $k => $v ) {
                    ?>
                    <option value="<?php echo $k ?>"<?php echo $element->is_public == $k ? ' selected' : '' ?>><?php echo $v ?></option>
                    <?php	
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Save">
        </div>
    </div>   
</form>

@stop