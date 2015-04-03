<?php

/**
 *	Forms are built using class FormElement in combination with
 *	data in tables `form_elements` and `form_groups`.  The following 
 *	functions are used by form elements for displaying and/or
 *	manipulating data.
 */

/**
 *	Get contact type name from model
 */
function contactDisplayContactType($val, $model) {
	return $model->contact_type_names;
}

/**
 *	Display a link to a library
 */
function contactDisplayLibraryName($val, $model) {
	$library = $model;
	$static = false;	
	$out = '';
	if ( $library ) {
		$out = '<a class="open-new" href="'.url().'/library?organization_id='.($library->organization_id.'">'.$library->organization_name).'</a>';
		$out .= ' / ';
		$out .= '<a class="open-new" href="'.url().'/library/view/'.$library->id.'">'.($library->name ? $library->name : 'Main/Central Location').'</a>';
	}
	return $out;
}
/**
 *	Display a link to an organization's libraries
 */
 
function contactDisplayOrganizationName($val, $model) {
	$out = false;
	if ( $model instanceof Nrs\Librarydirectory\models\Contact ) {
		$out = '<a class="open-new" href="'.url().'/library?organization_id='.($model->organization_id.'">'.$model->organization_name).'</a>';
	}
	return $out;
}

/**
 *	Select a contact type
 */
function contactFormContactType($name, $val = false, $defaultValue = false) {
	$out = '';
	$ids = explode(',', $val);
	$out .= '<div class="checkbox">';
	foreach ( contactTypes() as $k => $v ) {
		$checked = in_array($k, $ids) ? ' checked' : '';
		$out .= '<label><input type="checkbox" name="contact_type['.$k.']" value="'.$k.'"'.$checked.'> '.$v['name'].'</label> &nbsp; &nbsp;';	
	}
	$out .= '</div>';
	return $out;
}

/**
 *	Select an organization
 */
function contactFormOrganizationName($name, $val = false, $defaultValue = false) {
	$out = '<select class="form-control" name="organization_id" id="organization_id">';
	$out .= '<option value="">...select...</option>';
	foreach ( Organization::select('id', 'name')->orderBy('name')->get() as $v ) {
		$s = ( $val == $v->id ) || ( !$val && $defaultValue == $v->id ) ? ' selected' : false;
		$out .= '<option value="'.$v->id.'"'.$s.'>'.$v->name.'</option>';
	}
	$out .= '</select>';
	return $out;
}

/**
 *	Data used to build the quick sort form for list of contacts
 */
function contactSortables() {
	return array(
		'name'			=> 'Name',
		'title'			=> 'Title',
		'organization'	=> 'Organization',
		'type'			=> 'Type'
	);	
}

/**
 *	Display session/flash messages
 */
function displayFlashMessages($errors) {
	if ( Session::has('fail') ) {
		echo '<div class="alert alert-danger" role="alert">'.Session::get('fail').'</div>';
	}
	if ( Session::has('success') ) {
		echo '<div class="alert alert-success" role="alert">'.Session::get('success').'</div>';
	}
	foreach ( $errors->all() as $error ) {
		echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
	}
}

function displayFormElement($element, $record = false, $isPublicEdit = false, $allowDisable = false) {
	if ( $element['is_hidden'] == 1 || $element['is_active'] == 0 ) {
		return false;
	}
	$code = $element['code'];
	$defaultValue = $element['default_value'];
	$name = $element['name'];
	$type = $element['data_type'];
	$value = false;
	if ( $record && isset($record->{strtolower($element['code'])}) ) {
		$value = $record->{strtolower($element['code'])};
	}
	$length = $element['input_size'] > 0 ? $element['input_size'] : 6;
	if ( $isPublicEdit ) {
		$length = $length + 1;	
	}
	$disabled = false;
	if ( $allowDisable ) {
		if ( !$value ) {
			$disabled = true;	
		}
	}
	ob_start();
	?>
	<div class="form-group<?php echo $disabled ? ' disabled' : '' ?>">
		<label class="col-sm-3 control-label" for="<?php echo $code ?>"><?php echo $name != '' ? $name : $code ?></label>
		<div class="col-sm-<?php echo $length ?>">
		<?php
		if ( $type == 'text') {
			?>
			<input type="text" class="form-control" name="<?php echo $code ?>" id="<?php echo $code ?>" value="<?php echo $value ?>">
			<?php
		} else if ( $type == 'textarea' ) {
			?>
			<textarea name="<?php echo $code ?>" class="form-control" id="<?php echo $code ?>" rows="4"><?php echo $value ?></textarea>
			<?php
		} else if ( $type == 'bool' ) {
			?>
			<select name="<?php echo $code ?>" class="form-control" id="<?php echo $code ?>">
				<?php
				foreach ( array('' => '...select', 1 => 'Yes', 0 => 'No') as $k1 => $v1 ) {
					$selected = false;
					if ( is_numeric($value) && $value == $k1 ) {
						$selected = true;
					} else if ( $defaultValue != '' && $k1 == $defaultValue ) {
						$selected = true;
					}
					?>
					<option value="<?php echo $k1 ?>"<?php echo $selected ? ' selected' : '' ?>><?php echo $v1 ?></option>
					<?php	
				}
				?>
			</select>
			<?php
		} else if ( substr($type, 0, 4) == 'text' ) {
			?>
			<input type="text" class="form-control" name="<?php echo $code ?>" id="<?php echo $code ?>" value="<?php echo $value ?>" size="<?php echo $length ?>">
			<?php
		} else if ( substr($type, 0, 4) == 'func' ) {
			$function = str_replace('func', '', $type);
			if ( function_exists($function) ) {
				echo $function($code, $value, $defaultValue);	
			}
		}
		?>
		</div>
	</div>
	<?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
}

/**
 *	Form element columns that can be sorted
 */
function formElementSortables() {
	return array(
		'name'			=> 'Name',
		'code' 			=> 'Column',
		'is_hidden'		=> 'Hidden',
		'is_active'		=> 'Active',
		'element_order'	=> 'Order'
	);	
}
			
/**
 *	Select a library
 */
function formLibraries($name, $val = false, $defaultValue = false) {
	$libs = Library::select(libraryColumns())->joinOrganization()->orderBy('libraries.name')->get()->toArray();
	$libs = array('' => array('name' => '...select...', 'id' => false, 'organization_name' => false)) + $libs;
	$out = '<div class="row"><div class="col-md-12 form-inline"><select class="form-control" id="'.$name.'" name="'.$name.'">';
	foreach ( $libs as $k => $v ) {
		$out .= '<option value="'.$v['id'].'"'.($val == $v['id'] ? ' selected' : '').'>'.($v['name'] ? $v['name'] : $v['organization_name']).'</option>';	
	}
	$out .= '</select> &nbsp;<a class="staff-modal dynamic-lib-select" href="'.url().'/library/edit/"><i class="fa fa-pencil-square-o"></i></a></div></div>';	
	return $out;	
}

/**
 *	Get organization's name from model
 */
function formContactOrganizationName($val, $model) {
	return $model->organization_name;
}

function libraryAddressHeaderLinkToMap($model) {
	return;
	if ( is_numeric($model->latitude) && is_numeric($model->longitude) ) {
		?>
		<div class="row">
			<div class="col-md-12">
				<a class="btn btn-default open-new" href="<?php echo url() ?>/map?id=<?php echo $model->id ?>"><img src="<?php echo url() ?>/img/map.png" alt="View Result on Map" /> &nbsp;Map</a>
			</div>
		</div>
		<?php
	}
}

/**
 *	Get library's name from model
 */
function libraryDisplayName($val, $model) {
	return $model->name;
}

/**
 *	Get organization's name from model
 */
function libraryDisplayOrganizationName($val, $model) {
	return $model->organization_name;
}

/**
 *	Shortcut to get library type name using its id
 */
function libraryDisplayTypes($val) {
	$types = libraryTypes();
	return isset($types[$val]) ? $types[$val]['name'] : $val;
}

function libraryDisplayUrl($val) {
	if ( $val && $val != '' ) {
		if ( substr($val, 0, 7) != 'http://' && substr($val, 0, 8) != 'https://' ) {
			$val = 'http://'.$val;	
		}
		return '<a href="'.$val.'">'.$val.'</a>';
	}
	return $val;
}

/**
 *	Select a county
 */
function libraryFormCounties($name, $val = false, $defaultValue = false) {
	$counties = County::lists('name', 'name');
	$counties = array('' => '...select...') + $counties;
	$out = '<div class="row"><div class="col-md-4"><select class="form-control" name="'.$name.'">';
	foreach ( $counties as $k => $v ) {
		$out .= '<option value="'.$k.'"'.($val && strtolower($val) == strtolower($k) ? ' selected="selected"' : '').'>'.$v.'</option>';	
	}
	$out .= '</select></div></div>';	
	return $out;
}

/**
 *	Select an organization, and/or edit/create
 */
function libraryFormOrganizations($name, $val = false, $defaultValue = false) {
	$orgs = Organization::orderBy('name')->get()->toArray();
	$orgs = array('' => array('name' => '...select...', 'alt_name' => false, 'id' => false)) + $orgs;
	if ( Auth::check() ) {
		$out = '<div class="row"><div class="col-md-12 form-inline"><select class="form-control" id="'.$name.'" name="'.$name.'">';
		foreach ( $orgs as $k => $v ) {
			$out .= '<option value="'.$v['id'].'"'.($val == $v['id'] ? ' selected' : '').'>'.$v['name'].($v['alt_name'] ? '('.$v['alt_name'].')' : '').'</option>';	
		}
		$out .= '</select> &nbsp;<a class="staff-modal dynamic-org-select" href="'.url().'/admin/organization/edit/"><i class="fa fa-pencil-square-o"></i></a></div></div>';	
	} else {
		$out = '<input type="text" class="form-control" name="edit_organization_name" value="'.(isset($orgs[$val]) ? $orgs[$val]['name'] : '').'">';	
	}
	return $out;	
}

/**
 *	Select a school type
 */
function libraryFormSchoolTypes($val = false) {
	$arr = array(
		''										=> 'Not a School',
		'Charter School'						=> 'Charter School',
		'Combined Junior/Senior High School'	=> 'Combined Junior/Senior High School',
		'Elementary School'						=> 'Elementary School',
		'High School'							=> 'High School',
		'Junior High School'					=> 'Junior High School',
		'K-8'									=> 'K-8',
		'Middle School'							=> 'Middle School',
		'School (Other)'						=> 'School (Other)'
	);
	$out = '<div class="row"><div class="col-md-6"><select class="form-control" name="specifictype">';
	foreach ( $arr as $k => $v ) {
		$out .= '<option value="'.$v.'"'.($val != '' && $val == $k ? ' selected' : '').'>'.$v.'</option>';	
	}
	$out .= '</select></div></div>';	
	return $out;
}

/**
 *	Select a state
 */
function libraryFormStates($name, $val = false, $defaultValue = false) {
	$states = array('' => '...select...') + State::lists('name', 'id');
	ob_start();
	?>
	<div class="row">
        <div class="col-md-4">
        	<select class="form-control" name=""<?php echo $name ?>">
			<?php
            foreach ( $states as $k => $v ) {
                $s = false;	
                if ( $val && $val == $k ) {
                    $s = true;	
                } else if ( $defaultValue == $k ) {
                    $s = true;
                }
                ?>
                <option value="<?php echo $k.'"'.($s ? ' selected' : '') ?>><?php echo $v ?></option>
                <?php
            }
            ?>
            </select>
        </div>
	</div>
	<?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;	
}

/**
 *	Select a library type
 */
function libraryFormTypes($name, $val = false, $defaultValue = false) {
	$codes = libraryTypes();
	ob_start();
	?>
	<div class="row">
        <div class="col-md-4">
            <select class="form-control" name="'.$name.'">
                <option value="">...select...</option>
                <?php
                foreach ( $codes as $k => $v ) {
                    ?>
                    <option value="<?php echo $k ?>"<?php echo ($val == $k ? ' selected' : '') ?>><?php echo $v['name'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>	
    <?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;	
}

/**
 *	Library columsn that can be sorted
 */
function librarySortables() {
	return array(
		'organization'	=> 'Organization', 
		'library' 		=> 'Library', 
		'library_type'	=> 'Type',
		'city' 			=> 'City',  
		'county' 		=> 'County'
	);
}

/**
 *	An extremely simple (and naive) method for attaching a daily token to forms.
 *	Ideally this is appended to the form using Javascript, giving us a way to 
 *	ensure that the form is submitted by a human on an actual date.
 */
function simpleAntiSpamToken() {
	return sha1(date('Y-m-d', time()).csrf_token());	
}

/**
 *	Sort directions used by quick sort forms.
 */
function sortDirections() {
	return array(
		'asc'	=> 'Ascending',
		'desc'	=> 'Descending'
	);	
}

/**
 *	A ridiculously overthought way to convert organization/library 
 *	names to title case while preserving certain acronymns.
 */
function titleCase($string) {
	if ( $string === NULL ) {
		return $string;	
	}
	$word_splitters = array(' ', '-', '(', '/');
	$lowercase_exceptions = array();
	$uppercase_exceptions = array('CCPL', 'PPLD', 'LD', 'RLD', 'PCCLD', 'PLD', 'LD/', 'MCPLD', 'JCPL', 'GCPLD', 'DPLD', 'DPL', 'ALD', 'PL', 'PRPLD', 'DCSPL');
 
	$string = strtolower($string);
	foreach ($word_splitters as $delimiter) { 
		$words = explode($delimiter, $string); 
		$newwords = array(); 
		foreach ($words as $word) { 
			$count = strlen(preg_replace('![^a-zA-Z]+!', '', $word));
			if ( stristr($word, '-') !== false || $count == 1 || $count == 2 ) {
				if ( $count == 1 || $count == 2 || $count == 3 ) {
					$word = strtoupper($word);
				} else {
					$word = $word;
				}
			} else {
				if (in_array(strtoupper($word), $uppercase_exceptions))
					$word = strtoupper($word);
				else
				if (!in_array($word, $lowercase_exceptions))
					$word = ucfirst($word); 
			}
			$newwords[] = $word;
		}
		if (in_array(strtolower($delimiter), $lowercase_exceptions))
			$delimiter = strtolower($delimiter);
 
		$string = join($delimiter, $newwords); 
	} 
	return $string; 
}

/**
 *	User access control.
 *	- guest = can view
 *	- update = can edit
 *	- admin = all rights
 */
function userLevels() {
	return array('guest', 'update', 'admin');	
}

/**
 *	User columsn that can be sorted
 */
function userSortables() {
	return array(
		'name'		=> 'Name',
		'username'	=> 'Username',
		'level'		=> 'Level'
	);	
}