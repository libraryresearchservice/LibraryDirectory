<?php namespace Nrs\Librarydirectory\Models;

use Illuminate\Support\Facades\Input;

class QuickSort {
	
	public $after;
	public $before;
	public $directions = array('asc' => 'Ascending', 'desc' => 'Descending');
	public $order = 'order';
	public $orderBy = 'orderby';
	public $querystring = array();
	public $sortable = array();
	
	public function __construct() {
		
	}
	
	public function __toString() {
		ob_start();
		?>
        <form class="navbar-form navbar-right" id="sort-form" role="form">
            <div class="form-group">
                <?php
                if ( $this->before ) {
                    echo $this->before;
                }
				foreach ( $this->querystring as $k => $v ) {
					if ( $k == 'page' || $k == $this->orderBy || $k == $this->order ) {
						continue;
					}
					if ( is_array($v) ) {
						foreach ( $v as $v2 ) {
							?>
							<input type="hidden" name="<?php echo $k ?>[]" value="<?php echo $v2 ?>">
							<?php
						}
					} else {
						?>
						<input type="hidden" name="<?php echo $k ?>" value="<?php echo $v ?>">
						<?php
					}
				}
                ?>
                <select name="orderby" class="form-control">
                    <?php
                    foreach ( $this->sortable as $k => $v ) {
                        ?>
                        <option value="<?php echo $k ?>"<?php echo Input::get($this->orderBy) == $k ? ' selected' : '' ?>><?php echo $v ?></option>
                        <?php	
                    }
                    ?>
                </select>
                <select name="order" class="form-control">
                    <?php
                    foreach ( $this->directions as $k => $v ) {
                        ?>
                        <option value="<?php echo $k ?>"<?php echo Input::get($this->order) == $k ? ' selected' : '' ?>><?php echo $v ?></option>
                        <?php	
                    }
                    ?>
                </select>
                <?php
                if ( $this->after ) {
                    echo $this->after;
                }
				?>
            </div>
            <input class="btn btn-default" type="submit" value="Sort" />
        </form>
        <?php
		return ob_get_clean();
	}
	
	public function directions($arr = array(), $append = false) {
		if ( is_array($arr) ) {
			if ( $append ) {
				$this->directions = array_merge($this->directions, $arr);
			} else {
				$this->directions = $arr;	
			}
		}
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

	public function order($str) {
		if ( is_string($str) ) {
			$this->order = $str;
		}
		return $this;
	}

	public function orderBy($str) {
		if ( is_string($str) ) {
			$this->orderBy = $str;
		}
		return $this;
	}
	
	public function querystring($arr = array(), $append = false) {
		if ( is_array($arr) ) {
			if ( $append ) {
				$this->querystring = array_merge($this->querystring, $arr);
			} else {
				$this->querystring = $arr;	
			}
		}
		return $this;
	}
		
	public function sortable($arr = array(), $append = false) {
		if ( is_array($arr) ) {
			if ( $append ) {
				$this->sortable = array_merge($this->sortable, $arr);
			} else {
				$this->sortable = $arr;	
			}
		}
		return $this;
	}
	
}