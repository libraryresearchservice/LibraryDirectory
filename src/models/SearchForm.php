<?php namespace Nrs\Librarydirectory\Models;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class SearchForm {

	protected $action;
	protected $after;
	protected $before;
	protected $ignore = array();
	protected $method;
	protected $show = array();
	protected $url;
	
	public function __construct() {
		$this->url = Request::url();
	}
	
	public function __toString() {
		ob_start();
		$action = '';
		$method = '';
		if ( $this->action ) {
			$action = ' action="'.$this->action.'"';
		}
		if ( $this->method ) {
			$method = ' method="'.$this->method.'"';
		}
		?>
        <form id="advanced-filter" role="form"<?php echo $action.$method ?>>
            <?php
			if ( $this->before ) {
				echo $this->before;
			}
			?>
            <div class="form-group">
                <label for="keyword">Keyword</label>
                <input type="text" class="form-control" name="keyword" id="keyword" value="<?php echo Input::get('keyword', false) ?>">
            </div>
            <?php
			if ( !in_array('type', $this->ignore) ) {
				?>
                <label>Type</label>
                <div class="checkbox">
                    <?php
                    $type = Input::get('library-type', array());
                    $type = !is_array($type) ? array($type) : $type;
                    foreach ( libraryTypes() as $k => $v ) {
                        $append = false;
                        if ( Request::segment(1) == 'map' ) {
                            $append = 'img/marker'.$k.'.png';
                        }
                        ?>
                        <label><input type="checkbox" name="library-type[]" value="<?php echo $k ?>"<?php echo in_array($k, $type) ? ' checked' : '' ?>> &nbsp;<?php echo $v['name'] ?> <?php echo $append ? ' &nbsp;<img src="'.$append.'" alt="Map icon for '.$v['name'].' libraries">' : '' ?></label><br />
                        <?php	
                    }
                    ?>
                </div>
                <?php
			}
			if ( in_array('contact-type', $this->show) ) {
				?>
                <label>Contact Type</label>
                <div class="checkbox">
                    <?php
                    $type = Input::get('contact-type', array());
                    $type = !is_array($type) ? array($type) : $type;
                    foreach ( contactTypes() as $k => $v ) {
                        ?>
                        <label><input type="checkbox" name="contact-type[]" value="<?php echo $k ?>"<?php echo in_array($k, $type) ? ' checked' : '' ?>><?php echo $v['name'] ?></label><br />
                        <?php	
                    }
                    ?>
                </div>
                <div class="form-group">
                	<?php $contactTypeBool = Input::get('contact-type-bool', 'any') ?>
                    <label><input type="radio" name="contact-type-bool" value="any"<?php echo $contactTypeBool == 'any' ? ' checked' : '' ?> /> Any</label> &nbsp; &nbsp;<label><input type="radio" name="contact-type-bool" value="all"<?php echo $contactTypeBool == 'all' ? ' checked' : '' ?> /> All</label> 
                </div>
				<?php
			}
			if ( !in_array('city', $this->ignore) ) {
				?>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city" value="<?php echo Input::get('city', false) ?>">
                </div>
                <?php
			}
			if ( !in_array('county', $this->ignore) ) {
				?>
                <div class="form-group">
                    <label for="county">County</label>
                    <input type="text" class="form-control" name="county" id="county" value="<?php echo Input::get('county', false) ?>">
                </div>
                <?php
			}
			if ( !in_array('distance', $this->ignore) ) {
				?>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label  for="county">Zip code</label><br />
                        <input type="text" class="form-control" name="zipcode-near" id="zipcode-near" value="<?php echo Input::get('zipcode-near', false) ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="county">Distance</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="radius" id="radius" value="<?php echo Input::get('radius', false) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
			}
			if ( !in_array('near-me', $this->ignore) ) {
				?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="checkbox">
                            <label><input type="checkbox" name="near-me" id="near-me"<?php echo Input::get('near-me') ? ' checked' : '' ?>> &nbsp;Near Me</label>
                        	<?php
							if ( is_numeric(Input::get('latitude')) ) {
								?>
                                <input type="hidden" name="latitude" id="latitude" value="<?php echo Input::get('latitude') ?>" />
                                <?php
							}
							if ( is_numeric(Input::get('longitude')) ) {
								?>
                                <input type="hidden" name="longitude" id="longitude" value="<?php echo Input::get('longitude') ?>" />
                                <?php
							}
							?>
                        </div>
                    </div>
                </div>
                <?php
			}
			?>
            <input class="btn btn-primary" type="submit" id="advanced-filter-submit" value="Search"> <a href="<?php echo $this->url ?>" class="btn btn-default pull-right">Reset</a>
        </form>
        <?php
		return ob_get_clean();
	}

	public function action($str) {
		$this->action = $str;
		return $this;	
	}
	
	public function after($str) {
		if ( is_string($str) ) {
			$this->after = $str;
		}
		return $this;
	}
		
	public function before($str) {
		if ( is_string($str) ) {
			$this->before = $str;
		}
		return $this;
	}
		
	public function ignore($arr = array(), $append = true) {
		if ( is_array($arr) ) {
			if ( $append ) {
				$this->ignore = array_merge($this->ignore, $arr);
			} else {
				$this->ignore = $arr;	
			}
		}
		return $this;
	}

	public function method($str) {
		$this->method = $str;
		return $this;	
	}
		
	public function show($arr = array(), $append = true) {
		if ( is_array($arr) ) {
			if ( $append ) {
				$this->show = array_merge($this->show, $arr);
			} else {
				$this->show = $arr;	
			}
		}
		return $this;
	}
	
}