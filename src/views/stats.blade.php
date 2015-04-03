@extends('librarydirectory::master')

@section('content')

<!-- stats -->

<div class="row">
	<div class="col-md-3">
		<form id="stats">
			<h3>Library Meta</h3>
			<?php
            $i = 1;
			foreach ( $crosstab->config as $k => $v ) {
                ?>
                <div class="form-group">
                    <label for="axis<?php echo $k ?>"><?php echo strtoupper($k) ?> axis</label>
                    <select id="axis<?php echo $k ?>" class="form-control" name="axis<?php echo $k ?>">
                        <?php
                        if ( $i > 1 ) {
                        	?>
                            <option value="">...select...</option>
							<?php
						}
                        foreach ( $v as $k2 => $v2 ) {
                            $selected = Input::get('axis'.$k, false) == $k2 ? ' selected' : '';
							// Default to Library Type x Total
							if ( $i == 1 && !isset($ii) && !Input::get('axis'.$k, false) && $k2 == 'library-type' ) {
								$selected = ' selected';
								$ii = true;
							}
							?>
                            <option value="<?php echo $k2 ?>"<?php echo $selected ?>><?php echo $v2['title'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <?php
				$i++;
            }
            ?>
            <div class="clearfix"></div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>
    <div class="col-md-9">
    	<style type="text/css">
		.stat-table-wrap {
			height: 100%;
			overflow: auto;
			width: 100%;	
		}
		</style>
		<div class="stat-table-wrap">
			<?php
            if ( $crosstab->isSingleDimension() ) {
                if ( sizeof($table['rows']) < 6 ) {
                    ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="header_1"></th>
                                <?php
                                foreach ( $table['rows'] as $k => $v ) {
                                    ?>
                                    <th class="header_1"><?php echo $k != '' ? $k : '<em>No Data</em>' ?></th>
                                    <?php
                                }
                                ?>
                                <th class="header_1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total</td>
                                <?php
                                $total = 0;
                                foreach ( $table['rows'] as $k => $v ) {
                                    $val = '-';
                                    if ( is_array($v) ) {
                                        $val = current($v);
                                        $total += $val;
                                    }
                                    ?>
                                    <td><?php echo $val ?></td>
                                    <?php	
                                }
                                ?>
                                <td><?php echo $total ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                } else {
                    ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="header_1"></th>
                                <th class="header_1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ( $table['rows'] as $k => $v ) {
                                ?>
                                <tr>
                                    <td><?php echo $k != '' ? $k : '<em>No Data</em>' ?></td>
                                    <?php
                                    $val = '-';
                                    if ( is_array($v) ) {
                                        $val = current($v);
                                        $total += $val;
                                    }
                                    ?>
                                    <td><?php echo $val ?></td>
                                </tr>
                                <?php	
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
            } else {
                ?>
                <table class="table table-striped">
                    <thead>
                        <?php
                        $i = 1;
                        foreach ( $table['headers'] as $k => $v ) {
                            ?>
                            <tr>
                                <th class="header_<?php echo $i ?>"></th>
                                <?php
                                foreach ( $v as $k1 => $v1 ) {
                                    $val = $v1 ? $v1 : 'No Data';
                                    ?>
                                    <th class="header_<?php echo $i ?>" colspan="<?php echo $table['colspans'][$k] ?>"><?php echo $val ?></th>
                                    <?php	
                                }
                                if ( $i == 1 && sizeof($v) > 1 ) {
                                    ?>
                                    <th class="header_<?php echo $i ?>" rowspan="<?php echo sizeof($table['headers']) ?>">Total</th>
                                    <?php	
                                }
                                ?>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        foreach ( $table['rows'] as $k => $v ) {
                            ?>
                            <tr>
                                <th class="header-row"><?php echo $k != '' ? $k : '<em>- No Data -</em>' ?></th>
                                <?php
                                if ( !isset($tableax) ) {
                                    $max = sizeof($v);	
                                }
                                $i = 1;
                                foreach ( $v as $k1 => $v1 ) {
                                    ?>
                                    <td<?php echo $i == $max ? ' class="row_total"' : '' ?>><?php echo number_format($v1) ?></td>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <?php
                        foreach ( $table['footers'] as $k => $v ) {
                            ?>
                            <tr>
                                <th class="header-row"><?php echo ucwords($k) ?></th>
                                <?php
                                foreach ( $v as $k1 => $v1 ) {
                                    ?>
                                    <th><?php echo  number_format($v1) ?></th>
                                    <?php	
                                }
                                if ( sizeof($v) > 1 ) {
                                    ?>
                                    <th class="header-row"><?php echo number_format(array_sum($v)) ?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tfoot>
                </table>
                <?php
            }
            ?>
    	</div>
    </div>
</div>

<?php
if ( Auth::check() ) {
	?>
    <div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
    
            </div>
        </div>
    </div>
    <?php	
}
?>

@stop