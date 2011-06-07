<?php
/**
 * Renders the theme options. This file is to be loaded only for the admin screen
 */

function create_title($value) {
	echo '<h2 class="suf-header-1">'.$value['name']."</h2>\n";
}

function create_suf_header_2($value) {
	echo '<h3 class="suf-header-2">'.$value['name']."</h3>\n";
}

function create_suf_header_3($value) {
	echo '<h3 class="suf-header-3">'.$value['name']."</h3>\n";
}

function create_opening_tag($value) {
	$group_class = "";
	if (isset($value['grouping'])) {
		$group_class = "suf-grouping-{$value['grouping']} suf-grouping ";
	}
	echo '<div class="'.$group_class.'suf-section fix">'."\n";
	if (isset($value['name'])) {
		echo "<h3>" . $value['name'] . "</h3>\n";
	}
	if (isset($value['desc'])) {
		echo $value['desc']."<br/>";
	}
	if (isset($value['note'])) {
		echo "<span class=\"note\">".$value['note']."</span><br/>";
	}
}

function create_closing_tag() {
	echo "</div><!-- suf-section -->\n";
}

function create_suf_grouping($value) {
	echo "<div class='{$value['category']}-grouping suf-section fix'>\n";
	echo "<h3 class='suf-group-handler'><a class='toggler' href='#'>&ndash;</a>".$value['name']."</h3>\n";
	echo "<div class='{$value['category']}-body fix'>\n";
	echo "<div class='{$value['category']}-lhs suf-grouping-lhs'>\n";
	if (isset($value['desc'])) echo $value['desc']."<br/>";
	echo "</div>\n";
	echo "<div class='{$value['category']}-rhs suf-grouping-rhs'>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
}

function create_section_for_text($value) {
	global $suffusion_options;
	create_opening_tag($value);
	$text = "";
	if (!isset($suffusion_options[$value['id']])) {
		$text = $value['std'];
	}
	else {
		$text = $suffusion_options[$value['id']];
		$text = stripslashes($text);
		$text = esc_attr($text);
	}

	echo '<input type="text" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$text.'" />'."\n";
	if (isset($value['hint'])) {
		echo " &laquo; ".$value['hint']."<br/>\n";
	}
	create_closing_tag();
}

function create_section_for_textarea($value) {
	global $suffusion_options;
	create_opening_tag($value);
	echo '<textarea name="'.$value['id'].'" type="textarea" cols="" rows="">'."\n";
	if (isset($suffusion_options[$value['id']]) && $suffusion_options[$value['id']] != "") {
		$text = stripslashes($suffusion_options[$value['id']]);
		$text = esc_attr($text);
		echo $text;
	}
	else {
		echo $value['std'];
	}
	echo '</textarea>';
	if (isset($value['hint'])) {
		echo " &laquo; ".$value['hint']."<br/>\n";
	}
	create_closing_tag();
}

function create_section_for_select($value) {
	global $suffusion_options;
	create_opening_tag($value);
	echo '<select name="'.$value['id'].'" id="'.$value['id'].'">'."\n";
	foreach ($value['options'] as $option_value => $option_text) {
		echo "<option ";
		if (isset($suffusion_options[$value['id']]) && $suffusion_options[$value['id']] == $option_value) {
			echo ' selected="selected"';
		}
		elseif (!isset($suffusion_options[$value['id']])&& $option_value == $value['std']) {
			echo ' selected="selected"';
		}
		echo " value='$option_value' >".$option_text."</option>\n";
	}
	echo "</select>\n";
	create_closing_tag();
}

function create_section_for_multi_select($value) {
	global $suffusion_options;
	create_opening_tag($value);
	echo '<ul class="suf-checklist" id="'.$value['id'].'-chk" >'."\n";
	if (isset($value['std'])) {
		$consolidated_value = $value['std'];
	}
	if (isset($suffusion_options[$value['id']])) {
		$consolidated_value = $suffusion_options[$value['id']];
	}
	if (!isset($consolidated_value)) {
		$consolidated_value = "";
	}
	$consolidated_value = trim($consolidated_value);
	$exploded = array();
	if ($consolidated_value != '') {
		$exploded = explode(',', $consolidated_value);
	}
	foreach ($value['options'] as $option_value => $option_list) {
		$checked = " ";
		if ($consolidated_value) {
			foreach ($exploded as $idx => $checked_value) {
				if ($checked_value == $option_value) {
					$checked = " checked='checked' ";
					break;
				}
			}
		}
		echo "<li>\n";
		$depth = 0;
		if (isset($option_list['depth'])) {
			$depth = $option_list['depth'];
		}
		echo '<input type="checkbox" name="'.$value['id']."_".$option_value.'" value="true" '.$checked.' class="depth-'.($depth+1).' suf-options-checkbox-'.$value['id'].'" />'.$option_list['title']."\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "<div class='suf-multi-select-button-panel'>\n";
	echo "<input type='button' name='".$value['id']."-button-all' value='Select All' class='button-all suf-multi-select-button' />\n";
	echo "<input type='button' name='".$value['id']."-button-none' value='Select None' class='button-none suf-multi-select-button' />\n";
	echo "</div>\n";
	if (isset($suffusion_options[$value['id']])) {
		$set_value = $suffusion_options[$value['id']];
	}
	else {
		$set_value = "";
	}
	echo '<input type="hidden" name="'.$value['id'].'" id="'.$value['id'].'" value="'.$set_value.'"/>'."\n";
	create_closing_tag();
}

function create_section_for_radio($value) {
	global $suffusion_options;
	create_opening_tag($value);
	foreach ($value['options'] as $option_value => $option_text) {
		$option_value = stripslashes($option_value);
		$checked = ' ';
		if (isset($suffusion_options[$value['id']]) && stripslashes($suffusion_options[$value['id']]) == $option_value) {
			$checked = ' checked="checked" ';
		}
		else if (!isset($suffusion_options[$value['id']]) && $value['std'] == $option_value){
			$checked = ' checked="checked" ';
		}
		else {
			$checked = ' ';
		}
		echo '<div class="suf-radio"><input type="radio" name="'.$value['id'].'" value="'.$option_value.'" '.$checked."/>".$option_text."</div>\n";
	}
	create_closing_tag();
}

function create_section_for_checkbox($value) {
	global $suffusion_options;
	create_opening_tag($value);
	if($suffusion_options[$value['id']]) {
		$checked = "checked=\"checked\"";
	}
	else {
		$checked = "";
	}
	echo '<input type="checkbox" name="'.$value['id'].'" id="'.$value['id'].'" value="true" '.$checked."/>\n";
	create_closing_tag();
}

function create_section_for_color_picker($value) {
	global $suffusion_options;
	create_opening_tag($value);
	$color_value = "";
	if (!isset($suffusion_options[$value['id']])) {
		$color_value = $value['std'];
	}
	else {
		$color_value = $suffusion_options[$value['id']];
	}

	echo '<div class="color-picker">'."\n";
	echo '<input type="text" id="'.$value['id'].'" name="'.$value['id'].'" value="'.$color_value.'" class="color" /> &laquo; Click to select color<br/>'."\n";
	echo "<strong>Default: <font color='".$value['std']."'> ".$value['std']."</font></strong> (You can copy and paste this into the box above)\n";
	echo "</div>\n";
	create_closing_tag();
}

function create_section_for_sortable_list($value) {
	global $suffusion_options;
	create_opening_tag($value);
	if (!isset($suffusion_options[$value['id']])) {
		$list_order = $value['std'];
	}
	else {
		$list_order = $suffusion_options[$value['id']];
	}
	if (is_array($list_order)) { // The order has not been set. These are the default values
		$list_order_array = $list_order;
		$keys = array();
		foreach ($list_order_array as $list_item) {
			$keys[count($keys)] = $list_item['key'];
		}
		$list_order = implode(',',$keys);
	}
	else { // The order may have been set. We need to reconcile any additions / deletions from the standard list.
		$defaults = $value['std'];
		$default_array = array();
		foreach ($defaults as $list_item) {
			$default_array[$list_item['key']] = $list_item;
		}
		$keys = explode(',',$list_order);
		$clean_keys = array();
		$list_order_array = array();
		foreach ($keys as $key) {
			if (isset($default_array[$key])) {
				$list_item = $default_array[$key];
				$clean_keys[] = $key;
				$list_order_array[] = $list_item;
			}
		}
		foreach ($defaults as $list_item) {// Checking for additions
			if (!in_array($list_item['key'], $clean_keys)) {
				$clean_keys[] = $list_item['key'];
				$list_order_array[] = $list_item;
			}
		}
		$list_order = implode(',', $clean_keys);
	}
?>
	<script type="text/javascript">
	$j = jQuery.noConflict();
	$j(document).ready(function() {
		$j("#<?php echo $value['id']; ?>-ui").sortable({
			update: function(){
				$j('input#<?php echo $value['id']; ?>').val($j("#<?php echo $value['id']; ?>-ui").sortable('toArray'));
			}
		});
		$j("#<?php echo $value['id']; ?>-ui").disableSelection();
	});
	</script>
<?php
	echo "<ul id='".$value['id']."-ui' name='".$value['id']."-ui' class='suf-sort-list'>\n";
	foreach ($list_order_array as $list_item) {
		echo "<li id='".$list_item['key']."' class='suf-sort-list-item'>".$list_item['value']."</li>";
	}
	echo "</ul>\n";
	echo "<input id='".$value['id']."' name='".$value['id']."' type='hidden' value='$list_order'/>";
	create_closing_tag();
}

function create_section_for_slider($value) {
	global $suffusion_options;
	create_opening_tag($value);
	$options = $value['options'];
	if (!isset($suffusion_options[$value['id']])) {
		$default = $value['std'];
	}
	else {
		$default = $suffusion_options[$value['id']];
	}
?>
	<script type="text/javascript">
	$j = jQuery.noConflict();
	$j(document).ready(function() {
		$j("#<?php echo $value['id']; ?>-slider").slider({
			range: "<?php echo $options['range']; ?>",
			value: <?php echo (int)$default; ?>,
			min: <?php echo $options['min']; ?>,
			max: <?php echo $options['max']; ?>,
			step: <?php echo $options['step']; ?>,
			slide: function(event, ui) {
				$j("input#<?php echo $value['id']; ?>").val(ui.value);
			}
		});

	});
	</script>

	<div class='slider'>
		<p>
			<input type="text" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $default; ?>" class='slidertext' /> <?php echo $options['unit'];?>
		</p>
		<div id="<?php echo $value['id']; ?>-slider"  style="width:<?php echo $options['size'];?>;"></div>
	</div>
<?php
	create_closing_tag();
}

function create_section_for_background($value) {
	global $suffusion_options;
	create_opening_tag($value);
	$options = $value['options'];
	if (!isset($suffusion_options[$value['id']])) {
		$default = $value['std'];
		$default_txt = "";
		foreach ($value['std'] as $opt => $opt_val) {
			$default_txt .= $opt."=".$opt_val.";";
		}
	}
	else {
		$default_txt = $suffusion_options[$value['id']];
		$default = $default_txt;
		$vals = explode(";", $default);
		$default = array();
		foreach ($vals as $val) {
			$pair = explode("=", $val);
			if (isset($pair[0]) && isset($pair[1])) {
				$default[$pair[0]] = $pair[1];
			}
			else if (isset($pair[0]) && !isset($pair[1])) {
				$default[$pair[0]] = "";
			}
		}
	}
	$repeats = array("repeat" => "Repeat horizontally and vertically",
		"repeat-x" => "Repeat horizontally only",
		"repeat-y" => "Repeat vertically only",
		"no-repeat" => "Do not repeat");

	$positions = array("top left" => "Top left",
		"top center" => "Top center",
		"top right" => "Top right",
		"center left" => "Center left",
		"center center" => "Middle of the page",
		"center right" => "Center right",
		"bottom left" => "Bottom left",
		"bottom center" => "Bottom center",
		"bottom right" => "Bottom right");

	foreach ($value['options'] as $option_value => $option_text) {
		$checked = ' ';
		if ($suffusion_options[$value['id']] == $option_value) {
			$checked = ' checked="checked" ';
		}
		else if (!isset($suffusion_options[$value['id']]) && $value['std'] == $option_value){
			$checked = ' checked="checked" ';
		}
		else {
			$checked = ' ';
		}
		echo '<div class="suf-radio"><input type="radio" name="'.$value['id'].'" value="'.$option_value.'" '.$checked."/>".$option_text."</div>\n";
	}
?>
	<div class='suf-background-options'>
	<table class='opt-sub-table'>
        <col class='opt-sub-table-cols'/>
        <col class='opt-sub-table-cols'/>
		<tr>
			<td valign='top'>
				<div class="color-picker-group">
					<strong>Background Color:</strong><br/>
					<input type="radio" name="<?php echo $value['id']; ?>-colortype" value="transparent" <?php if ($default['colortype'] == 'transparent') { echo ' checked="checked" ';} ?>/> Transparent / No color<br/>
					<input type="radio" name="<?php echo $value['id']; ?>-colortype" value="custom" <?php if ($default['colortype'] == 'custom') { echo ' checked="checked" ';} ?>/> Custom
					<input type="text" id="<?php echo $value['id']; ?>-bgcolor" name="<?php echo $value['id']; ?>-bgcolor" value="<?php echo $default['color']; ?>" class="color" /><br/>
					Default: <span color='<?php echo $default['color']; ?>"'> <?php echo $default['color']; ?> </span>
				</div>
			</td>
			<td valign='top'>
				<strong>Image URL:</strong><br/>
				<input type="text" id="<?php echo $value['id']; ?>-bgimg" name="<?php echo $value['id']; ?>-bgimg" value="<?php echo $default['image']; ?>" />
			</td>
		</tr>

		<tr>
			<td valign='top'>
				<strong>Image Position:</strong><br/>
				<select name="<?php echo $value['id']; ?>-position" id="<?php echo $value['id']; ?>-position" >
			<?php
				foreach ($positions as $option_value => $option_text) {
					echo "<option ";
					if ($default['position'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>

			<td valign='top'>
				<strong>Image Repeat:</strong><br/>
				<select name="<?php echo $value['id']; ?>-repeat" id="<?php echo $value['id']; ?>-repeat" >
			<?php
				foreach ($repeats as $option_value => $option_text) {
					echo "<option ";
					if ($default['repeat'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>
		<tr>
			<td valign='top' colspan='2'>
				<script type="text/javascript">
				$j = jQuery.noConflict();
				$j(document).ready(function() {
					$j("#<?php echo $value['id']; ?>-transslider").slider({
						range: "min",
						value: <?php echo (int)$default['trans']; ?>,
						min: 0,
						max: 100,
						step: 1,
						slide: function(event, ui) {
							$j("input#<?php echo $value['id']; ?>-trans").val(ui.value);
							$j("#<?php echo $value['id']; ?>").val('color=' + $j("#<?php echo $value['id']; ?>-bgcolor").val() + ';' +
																   'colortype=' + $j("input[name=<?php echo $value['id']; ?>-colortype]:checked").val() + ';' +
																   'image=' + $j("#<?php echo $value['id']; ?>-bgimg").val() + ';' +
																   'position=' + $j("#<?php echo $value['id']; ?>-position").val() + ';' +
																   'repeat=' + $j("#<?php echo $value['id']; ?>-repeat").val() + ';' +
																   'trans=' + $j("#<?php echo $value['id']; ?>-trans").val() + ';'
									);
						}
					});
				});
				</script>

				<div class='slider'>
					<p>
						<strong>Layer Transparency (not for IE):</strong>
						<input type="text" id="<?php echo $value['id']; ?>-trans" name="<?php echo $value['id']; ?>-trans" value="<?php echo $default['trans']; ?>" class='slidertext' />
					</p>
					<div id="<?php echo $value['id']; ?>-transslider" class='transslider'></div>
				</div>
			</td>
		</tr>
	</table>
	<input type='hidden' id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $default_txt; ?>" />
	</div>
<?php
	create_closing_tag();
}

function create_section_for_border($value) {
	global $suffusion_options;
	create_opening_tag($value);
	if (!isset($suffusion_options[$value['id']])) {
		$default = $value['std'];
		$default_txt = "";
		foreach ($value['std'] as $edge => $edge_val) {
			$default_txt .= $edge.'::';
			foreach ($edge_val as $opt => $opt_val) {
				$default_txt .= $opt . "=" . $opt_val . ";";
			}
			$default_txt .= "||";
		}
	}
	else {
		$default_txt = $suffusion_options[$value['id']];
		$default = $default_txt;
		$edge_array = explode('||', $default);
		$default = array();
		if (is_array($edge_array)) {
			foreach ($edge_array as $edge_vals) {
				if (trim($edge_vals) != '') {
					$edge_val_array = explode('::', $edge_vals);
					if (is_array($edge_val_array) && count($edge_val_array) > 1) {
						$vals = explode(';', $edge_val_array[1]);
						$default[$edge_val_array[0]] = array();
						foreach ($vals as $val) {
							$pair = explode("=", $val);
							if (isset($pair[0]) && isset($pair[1])) {
								$default[$edge_val_array[0]][$pair[0]] = $pair[1];
							}
							else if (isset($pair[0]) && !isset($pair[1])) {
								$default[$edge_val_array[0]][$pair[0]] = "";
							}
						}
					}
				}
			}
		}
	}
	$edges = array('top' => 'Top', 'right' => 'Right', 'bottom' => 'Bottom', 'left' => 'Left');
	$styles = array("none" => "No border",
		"hidden" => "Hidden",
		"dotted" => "Dotted",
		"dashed" => "Dashed",
		"solid" => "Solid",
		"double" => "Double",
		"grove" => "Groove",
		"ridge" => "Ridge",
		"inset" => "Inset",
		"outset" => "Outset");

	$border_width_units = array("px" => "Pixels (px)", "em" => "Em");

	foreach ($value['options'] as $option_value => $option_text) {
		$checked = ' ';
		if ($suffusion_options[$value['id']] == $option_value) {
			$checked = ' checked="checked" ';
		}
		else if (!isset($suffusion_options[$value['id']]) && $value['std'] == $option_value){
			$checked = ' checked="checked" ';
		}
		else {
			$checked = ' ';
		}
		echo '<div class="suf-radio"><input type="radio" name="'.$value['id'].'" value="'.$option_value.'" '.$checked."/>".$option_text."</div>\n";
	}
?>
	<div class='suf-border-options'>
	<?php
		foreach ($edges as $edge => $edge_text) {
	?>
	<table class='opt-sub-table'>
        <col class='opt-sub-table-cols'/>
        <col class='opt-sub-table-cols'/>
		<tr>
			<th colspan='2'><?php echo $edge_text." border"; ?></th>
		</tr>
		<tr>
			<td colspan='2'>Set style to "No Border" if you don't want a border.</td>
		</tr>
		<tr>
			<td valign='top'>
				<div class="color-picker-group">
					<strong>Color:</strong><br/>
					<input type="radio" name="<?php echo $value['id'].'-'.$edge; ?>-colortype" value="transparent" <?php if ($default[$edge]['colortype'] == 'transparent') { echo ' checked="checked" ';} ?>/> Transparent / No color<br/>
					<input type="radio" name="<?php echo $value['id'].'-'.$edge; ?>-colortype" value="custom" <?php if ($default[$edge]['colortype'] == 'custom') { echo ' checked="checked" ';} ?>/> Custom
					<input type="text" id="<?php echo $value['id'].'-'.$edge; ?>-color" name="<?php echo $value['id']; ?>-color" value="<?php echo $default[$edge]['color']; ?>" class="color" /><br/>
					Default: <span color='<?php echo $default[$edge]['color']; ?>"'> <?php echo $default[$edge]['color']; ?> </span>
				</div>
			</td>

			<td valign='top'>
				<strong>Border Style:</strong><br/>
				<select name="<?php echo $value['id'].'-'.$edge; ?>-style" id="<?php echo $value['id'].'-'.$edge; ?>-style" >
			<?php
				foreach ($styles as $option_value => $option_text) {
					echo "<option ";
					if (isset($default[$edge]) && isset($default[$edge]['style']) && $default[$edge]['style'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>

		<tr>
			<td valign='top'>
				<strong>Border Width:</strong><br/>
				<input type="text" id="<?php echo $value['id'].'-'.$edge; ?>-border-width" name="<?php echo $value['id'].'-'.$edge; ?>-border-width" value="<?php echo $default[$edge]['border-width']; ?>" /><br/>
			</td>
			<td valign='top'>
				<strong>Border Width Units:</strong><br/>
				<select name="<?php echo $value['id'].'-'.$edge; ?>-border-width-type" id="<?php echo $value['id'].'-'.$edge; ?>-border-width-type" >
			<?php
				foreach ($border_width_units as $option_value => $option_text) {
					echo "<option ";
					if ($default[$edge]['border-width-type'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>
	</table>
	<?php
		}
	?>
	<input type='hidden' id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $default_txt; ?>" />
	</div>
<?php
	create_closing_tag();
}

function create_section_for_font($value) {
	global $suffusion_options, $font_faces;
	create_opening_tag($value);
	$options = $value['options'];
	if (!isset($suffusion_options[$value['id']])) {
		$default = $value['std'];
		$default_txt = "";
		foreach ($value['std'] as $opt => $opt_val) {
			$default_txt .= $opt."=".stripslashes($opt_val).";";
		}
	}
	else {
		$default_txt = $suffusion_options[$value['id']];
		$default = $default_txt;
		$vals = explode(";", $default);
		$default = array();
		foreach ($vals as $val) {
			$pair = explode("=", $val);
			if (isset($pair[0]) && isset($pair[1])) {
				$default[$pair[0]] = stripslashes($pair[1]);
			}
			else if (isset($pair[0]) && !isset($pair[1])) {
				$default[$pair[0]] = "";
			}
		}
	}
	$font_size_types = array("pt" => "Points (pt)", "px" => "Pixels (px)", "%" => "Percentages (%)", "em" => "Em");
	$font_styles = array("normal" => "Normal", "italic" => "Italic", "oblique" => "Oblique", "inherit" => "Inherit");
	$font_variants = array("normal" => "Normal", "small-caps" => "Small Caps", "inherit" => "Inherit");
	$font_weights = array("normal" => "Normal", "bold" => "Bold", "bolder" => "Bolder", "lighter" => "Lighter", "inherit" => "Inherit");
?>
	<div class='suf-font-options'>
	<table class='opt-sub-table'>
        <col class='opt-sub-table-cols'/>
        <col class='opt-sub-table-cols'/>
		<tr>
			<td valign='top'>
				<div class="color-picker-group">
					<strong>Font Color:</strong><br/>
					<input type="text" id="<?php echo $value['id']; ?>-color" name="<?php echo $value['id']; ?>-color" value="<?php echo $default['color']; ?>" class="color" /><br/>
					Default: <span color='<?php echo $default['color']; ?>"'> <?php echo $default['color']; ?> </span>
				</div>
			</td>
			<td valign='top'>
				<strong>Font Face:</strong><br/>
				<select name="<?php echo $value['id']; ?>-font-face" id="<?php echo $value['id']; ?>-font-face" >
			<?php
				foreach ($font_faces as $option_value => $option_text) {
					echo "<option ";
					if (stripslashes($default['font-face']) == stripslashes($option_value)) {
						echo ' selected="selected"';
					}
					echo " value=\"".stripslashes($option_value)."\" >".$option_value."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>

		<tr>
			<td valign='top'>
				<strong>Font Size:</strong><br/>
				<input type="text" id="<?php echo $value['id']; ?>-font-size" name="<?php echo $value['id']; ?>-font-size" value="<?php echo $default['font-size']; ?>" /><br/>
			</td>
			<td valign='top'>
				<strong>Font Size Type:</strong><br/>
				<select name="<?php echo $value['id']; ?>-font-size-type" id="<?php echo $value['id']; ?>-font-size-type" >
			<?php
				foreach ($font_size_types as $option_value => $option_text) {
					echo "<option ";
					if ($default['font-size-type'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>

		<tr>
			<td valign='top'>
				<strong>Font Style:</strong><br/>
				<select name="<?php echo $value['id']; ?>-font-style" id="<?php echo $value['id']; ?>-font-style" >
			<?php
				foreach ($font_styles as $option_value => $option_text) {
					echo "<option ";
					if ($default['font-style'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
			<td valign='top'>
				<strong>Font Variant:</strong><br/>
				<select name="<?php echo $value['id']; ?>-font-variant" id="<?php echo $value['id']; ?>-font-variant" >
			<?php
				foreach ($font_variants as $option_value => $option_text) {
					echo "<option ";
					if ($default['font-variant'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>

		<tr>
			<td valign='top' colspan='2'>
				<strong>Font Weight:</strong><br/>
				<select name="<?php echo $value['id']; ?>-font-weight" id="<?php echo $value['id']; ?>-font-weight" >
			<?php
				foreach ($font_weights as $option_value => $option_text) {
					echo "<option ";
					if ($default['font-weight'] == $option_value) {
						echo ' selected="selected"';
					}
					echo " value='$option_value' >".$option_text."</option>\n";
				}
			?>
				</select>
			</td>
		</tr>
	</table>
	<input type='hidden' id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php echo stripslashes($default_txt); ?>" />
	</div>
<?php
	create_closing_tag();
}

function create_section_for_blurb($value) {
	create_opening_tag($value);
	create_closing_tag();
}

function create_section_for_button($value) {
	create_opening_tag($value);
	$category = $value['parent'];
	echo "<input name=\"".$value['id']."\" type=\"button\" value=\"".$value['std']."\" class=\"button\" onclick=\"submit_form(this, document.forms['form-$category'])\" />\n";
	create_closing_tag();
}

function get_option_structure($options) {
	$option_structure = array();
	foreach ($options as $value) {
		switch ($value['type']) {
			case "title":
				$option_structure[$value['category']] = array();
				$option_structure[$value['category']]['slug'] = $value['category'];
				$option_structure[$value['category']]['name'] = $value['name'];
				$option_structure[$value['category']]['children'] = array();
				$option_structure[$value['category']]['parent'] = null;
				break;
			case "sub-section-2":
			case "sub-section-3":
				$option_structure[$value['parent']]['children'][$value['category']] = $value['name'];

				$option_structure[$value['category']] = array();
				$option_structure[$value['category']]['slug'] = $value['category'];
				$option_structure[$value['category']]['name'] = $value['name'];
				$option_structure[$value['category']]['children'] = array();
				if (isset($value['parent'])) $option_structure[$value['category']]['parent'] = $value['parent'];
				if (isset($value['buttons'])) $option_structure[$value['category']]['buttons'] = $value['buttons'];
				break;
			default:
				$option_structure[$value['parent']]['children'][$value['name']] = $value['name'];
		}
	}
	return $option_structure;
}

function get_options_html($option_structure, $selected = "theme-selection", $echo = false) {
	echo "<div class='htab-container fix'>\n";
	echo "<ul class='htabs'>\n";
	foreach ($option_structure as $l1) {
		if (!isset($l1['parent']) || $l1['parent'] == null) {
			foreach ($l1['children'] as $l2slug => $l2name) {
				echo "<li class='htab-$l2slug level-2'>".$l2name."</li>\n";
			}
		}
	}
	echo "</ul>\n";
	echo "<div class='donate'>\n";
	echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="submit" id="paypal-submit" >';
	echo '<input type="hidden" name="cmd" value="_s-xclick"/>';
	echo '<input type="hidden" name="hosted_button_id" value="9018267"/>';
	//echo '<input type="image" src="http://www.aquoid.com/images/Coffee.png" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border: none;"/>';
	echo '<input type="submit" name="submit" value="Like Suffusion? Buy me a coffee!" class="cbutton" />';
	echo '<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"/>';
	echo '</form>';
	echo "</div>\n";

	echo "</div>\n";
	echo "<div class='content-holder fix'>\n";
	foreach ($option_structure as $l1) {
		if (!isset($l1['parent']) || $l1['parent'] == null) {
			foreach ($l1['children'] as $l2slug => $l2name) {
				get_options_html_for_group($option_structure, 'theme-selection', $l2slug, false);
			}
		}
	}
	echo "</div>\n";
}

function get_options_html_for_group($option_structure, $selected = "theme-selection", $group, $echo = false) {
	global $themename, $shortname, $options, $spawned_options, $theme_name, $suf_theme_definitions;
	echo "<div class='suf-options suf-options-$group'>";
	foreach ($option_structure as $l1) {
		if (!isset($l1['parent']) || $l1['parent'] == null) {
			foreach ($l1['children'] as $l2slug => $l2name) {
				if ($group == $l2slug) {
					echo "<h1>$l2name</h1>\n";
				}
			}
		}
	}
	echo "<ul class='tabs'>";
	foreach ($option_structure as $l1) {
		if (!isset($l1['parent']) || $l1['parent'] == null) {
			foreach ($l1['children'] as $l2slug => $l2name) {
				if ($group == $l2slug) {
					foreach ($option_structure[$l2slug]['children'] as $l3slug => $l3name) {
						echo "<li class='$l3slug'><a class=\"$l3slug level-3\">".$l3name."</a></li>\n";
					}
				}
			}
		}
	}
	echo "</ul>";
	foreach ($option_structure as $option) {
		if (!isset($option['parent']) || $option['parent'] == null) {
			foreach ($option['children'] as $l2slug => $l2name) {
				if ($group == $l2slug) {
					foreach ($option_structure[$l2slug]['children'] as $category => $l3name) {
						create_form($themename, $shortname, $options, $spawned_options, $theme_name, $suf_theme_definitions, $category);
					}
				}
			}
		}
	}
	echo "</div>";
//	return $ret;
}

function get_options_for_category($options, $category) {
	$ret = array();
	if ($category == "all") {
		return $options;
	}
	foreach ($options as $value) {
		if (isset($value['parent']) && $value['parent'] == $category) {
			$ret[count($ret)] = $value;
		}
	}
	return $ret;
}

function get_spawned_options_for_category($options, $spawned_options, $category) {
	$ret = array();
	if ($category == "all") {
		return $spawned_options;
	}
	foreach ($options as $optionvalue) {
		if (isset($optionvalue['parent']) && $optionvalue['parent'] == $category) {
			foreach ($spawned_options as $value) {
				if (isset($optionvalue['id']) && $value['parent'] == $optionvalue['id']) {
					if (!in_array($value, $ret)) {
						$ret[count($ret)] = $value;
					}
				}
			}
		}
	}
	return $ret;
}

function create_form($themename, $shortname, $options, $spawned_options, $theme_name, $suf_theme_definitions, $category = "all") {
	$filtered_options = get_options_for_category($options, $category);
	echo "<div class=\"$category main-content\" id='$category'>\n";
	$option_structure = get_option_structure($options);
	echo '<h3 class="suf-header-2">'.$option_structure[$category]['name']."</h3>\n";
	echo "<form method=\"post\" name=\"form-$category\">\n";
	wp_nonce_field($category."-suffusion", $category.'-wpnonce');
	foreach ($filtered_options as $value) {
		switch ( $value['type'] ) {
			case "title":
				create_title($value);
				break;

			case "sub-section-2":
				create_suf_header_2($value);
				break;

			case "sub-section-3":
				create_suf_header_3($value);
				break;

			case "sub-section-4":
				create_suf_grouping($value);
				break;

			case "text";
				create_section_for_text($value);
				break;

			case "textarea":
				create_section_for_textarea($value);
				break;

			case "select":
				create_section_for_select($value);
				break;

			case "multi-select":
				create_section_for_multi_select($value);
				break;

			case "radio":
				create_section_for_radio($value);
				break;

			case "checkbox":
				create_section_for_checkbox($value);
				break;

			case "color-picker":
				create_section_for_color_picker($value);
				break;

			case "sortable-list":
				create_section_for_sortable_list($value);
				break;

			case "slider":
				create_section_for_slider($value);
				break;

			case "background":
				create_section_for_background($value);
				break;

			case "border":
				create_section_for_border($value);
				break;

			case "font":
				create_section_for_font($value);
				break;

			case "blurb":
				create_section_for_blurb($value);
				break;

			case "button":
				create_section_for_button($value);
				break;
		}
	}

	if (isset($option_structure[$category]['buttons']) && $option_structure[$category]['buttons'] == 'special-buttons') {
		echo "<input type=\"hidden\" name=\"formaction\" value=\"default\" />\n";
		echo "<input type=\"hidden\" name=\"formcategory\" value=\"$category\" />\n";
	}
	else if (!isset($option_structure[$category]['buttons']) || $option_structure[$category]['buttons'] != 'no-buttons') {
		echo "<div class=\"suf-button-bar\">\n";
		echo "<h2>Save / Reset</h2>\n";
		echo "<input name=\"save\" type=\"button\" value=\"Save changes on this page\" class=\"button\" onclick=\"submit_form(this, document.forms['form-$category'])\" />\n";
		echo "<input name=\"reset\" type=\"button\" value=\"Reset page to default options\" class=\"button\" onclick=\"submit_form(this, document.forms['form-$category'])\" />\n";
		echo "<input name=\"reset_all\" type=\"button\" value=\"Reset all pages to default options\" class=\"button\" onclick=\"submit_form(this, document.forms['form-$category'])\" />\n";
		echo "<input type=\"hidden\" name=\"formaction\" value=\"default\" />\n";
		echo "<input type=\"hidden\" name=\"formcategory\" value=\"$category\" />\n";
		echo "</div><!-- suf-button-bar -->\n";
	}
	echo "</form>\n";

	echo "</div><!-- main content -->\n";
}

function process_custom_type_option($option, $section, $suffusion_custom_type, $custom_type_name) {
	if (is_array($option)) {
		$required = "";
		if (isset($option['reqd'])) {
			$required = " <span class='note'>[Required *]</span> ";
		}
		switch ($option['type']) {
			case 'text':
				echo "<td>".$option['desc'].$required."</td>";
				if ($section != null) {
					if (isset($option['name']) && isset($suffusion_custom_type[$section][$option['name']])) {
						echo "<td><input name='{$custom_type_name}[$section][".$option['name']."]' type='text' value=\"".$suffusion_custom_type[$section][$option['name']]."\"/></td>";
					}
					else {
						echo "<td><input name='{$custom_type_name}[$section][".$option['name']."]' type='text' value=\"\"/></td>";
					}
				}
				else {
					if (isset($option['name']) && isset($suffusion_custom_type[$option['name']])) {
						echo "<td><input name='{$custom_type_name}[".$option['name']."]' type='text' value=\"".$suffusion_custom_type[$option['name']]."\"/></td>";
					}
					else {
						echo "<td><input name='{$custom_type_name}[".$option['name']."]' type='text' value=\"\"/></td>";
					}
				}
				break;

			case 'checkbox':
?>
		<td colspan='2'>
		<?php
				if ($section != null) {
		?>
			<input name='<?php echo $custom_type_name; ?>[<?php echo $section; ?>][<?php echo $option['name'];?>]' type='checkbox' value='1' <?php if (isset($suffusion_custom_type[$section][$option['name']])) checked('1', $suffusion_custom_type[$section][$option['name']]); ?> />
		<?php
				}
				else {
		?>
			<input name='<?php echo $custom_type_name; ?>[<?php echo $option['name'];?>]' type='checkbox' value='1' <?php if (isset($suffusion_custom_type[$option['name']])) checked('1', $suffusion_custom_type[$option['name']]); ?> />
		<?php
				}
		?>
			&nbsp;&nbsp;<?php echo $option['desc'].$required;?>
		</td>
<?php
		        break;

			case 'select':
?>
		<td><?php echo $option['desc'].$required;?></td>
		<td>
		<?php
				if ($section != null) {
					if (!isset($suffusion_custom_type[$section][$option['name']]) || $suffusion_custom_type[$section][$option['name']] == null) {
						$value = $option['std'];
					}
					else {
						$value = $suffusion_custom_type[$section][$option['name']];
					}
		?>
			<select name='<?php echo $custom_type_name; ?>[<?php echo $section; ?>][<?php echo $option['name'];?>]' >
		<?php
					foreach ($option['options'] as $dd_value => $dd_display) {
		?>
				<option value='<?php echo $dd_value;?>' <?php if ($value == $dd_value) { echo " selected='selected' "; } ?>><?php echo $dd_display; ?></option>
		<?php
					}
		?>

			</select>
		<?php
				}
				else {
					if (!isset($suffusion_custom_type[$option['name']]) || $suffusion_custom_type[$option['name']] == null) {
						$value = $option['std'];
					}
					else {
						$value = $suffusion_custom_type[$option['name']];
					}
		?>
			<select name='<?php echo $custom_type_name; ?>[<?php echo $option['name'];?>]' >
		<?php
					foreach ($option['options'] as $dd_value => $dd_display) {
		?>
				<option value='<?php echo $dd_value;?>' <?php if ($value == $dd_value) { echo " selected='selected' "; } ?>><?php echo $dd_display; ?></option>
		<?php
					}
		?>

			</select>
		<?php
				}
		?>
		</td>
<?php
		        break;
		}
	}
}
?>