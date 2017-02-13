<?php
class Photonic_Options_Manager {
	var $options, $tab, $tab_options, $reverse_options, $shown_options, $option_defaults, $allowed_values, $hidden_options, $nested_options, $displayed_sections;
	var $option_structure, $previous_displayed_section, $file, $tab_name;

	function Photonic_Options_Manager($file) {
		global $photonic_setup_options, $photonic_generic_options, $photonic_flickr_options, $photonic_picasa_options, $photonic_500px_options, $photonic_smugmug_options;
		$options_page_array = array(
			'generic-options.php' => $photonic_generic_options,
			'flickr-options.php' => $photonic_flickr_options,
			'picasa-options.php' => $photonic_picasa_options,
			'500px-options.php' => $photonic_500px_options,
			'smugmug-options.php' => $photonic_smugmug_options,
		);

		$tab_name_array = array(
			'generic-options.php' => 'Generic Options',
			'flickr-options.php' => 'Flickr Options',
			'picasa-options.php' => 'Picasa Options',
			'500px-options.php' => '500px Options',
			'smugmug-options.php' => 'SmugMug Options',
		);

		$this->file = $file;
		$this->tab = 'generic-options.php';
		if (isset($_REQUEST['tab']) && array_key_exists($_REQUEST['tab'], $options_page_array)) {
			$this->tab = $_REQUEST['tab'];
		}

		$this->tab_options = $options_page_array[$this->tab];
		$this->tab_name = $tab_name_array[$this->tab];
		$this->options = $photonic_setup_options;
		$this->reverse_options = array();
		$this->nested_options = array();
		$this->displayed_sections = 0;
		$this->option_structure = $this->get_option_structure();

		$all_options = get_option('photonic_options');
		if (!isset($all_options)) {
			$this->hidden_options = array();
		}
		else {
			$this->hidden_options = $all_options;
		}

		foreach ($this->tab_options as $option) {
			if (isset($option['id'])) {
				$this->shown_options[] = $option['id'];
				if (isset($this->hidden_options[$option['id']])) unset($this->hidden_options[$option['id']]);
			}
		}

		foreach ($photonic_setup_options as $option) {
			if (isset($option['category']) && !isset($this->nested_options[$option['category']])) {
				$this->nested_options[$option['category']] = array();
			}

			if (isset($option['id'])) {
				$this->reverse_options[$option['id']] = $option['type'];
				if (isset($option['std'])) {
					$this->option_defaults[$option['id']] = $option['std'];
				}
				if (isset($option['options'])) {
					$this->allowed_values[$option['id']] = $option['options'];
				}
				if (isset($option['grouping'])) {
					if (!isset($this->nested_options[$option['grouping']])) {
						$this->nested_options[$option['grouping']] = array();
					}
					$this->nested_options[$option['grouping']][] = $option['id'];
				}
			}
		}

		add_action('wp_ajax_photonic_admin_upload_file', array(&$this, 'admin_upload_file'));
		add_action('wp_ajax_photonic_authenticate_oauth', array(&$this, 'authenticate_oauth'));
	}

	function render_options_page() {
?>
	<div class="photonic-wrap">
		<div class="photonic-tabbed-options">
			<div class="photonic-header-nav">
				<div class="photonic-header-nav-top fix">
					<h2 class='photonic-header-1'>Photonic</h2>
					<div class='donate fix'>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal-submit" >
							<input type="hidden" name="cmd" value="_s-xclick"/>
							<input type="hidden" name="hosted_button_id" value="9018267"/>
							<ul>
								<li class='announcements'><a href='http://www.aquoid.com/news'>Announcements</a></li>
								<li class='support'><a href='http://www.aquoid.com/forum'>Support Forum</a></li>
								<li class='coffee'><input type='submit' name='submit' value='Like Photonic? Buy me a coffee!' /></li>
							</ul>
							<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
						</form>
					</div><!-- donate -->
				</div>
				<div class="photonic-options-header-bar fix">
					<ul class='photonic-options-header-bar'>
						<li><a class='photonic-load-page <?php if ($this->tab == 'generic-options.php') echo 'current-tab'; ?>' id='photonic-options-generic' href='?page=photonic-options-manager&amp;tab=generic-options.php'><span class="icon">&nbsp;</span> Generic Options</a></li>
						<li><a class='photonic-load-page <?php if ($this->tab == 'flickr-options.php') echo 'current-tab'; ?>' id='photonic-options-flickr' href='?page=photonic-options-manager&amp;tab=flickr-options.php'><span class="icon">&nbsp;</span> Flickr</a></li>
						<li><a class='photonic-load-page <?php if ($this->tab == 'picasa-options.php') echo 'current-tab'; ?>' id='photonic-options-picasa' href='?page=photonic-options-manager&amp;tab=picasa-options.php'><span class="icon">&nbsp;</span> Picasa</a></li>
						<li><a class='photonic-load-page <?php if ($this->tab == '500px-options.php') echo 'current-tab'; ?>' id='photonic-options-500px' href='?page=photonic-options-manager&amp;tab=500px-options.php'><span class="icon">&nbsp;</span> 500px</a></li>
						<li><a class='photonic-load-page <?php if ($this->tab == 'smugmug-options.php') echo 'current-tab'; ?>' id='photonic-options-smugmug' href='?page=photonic-options-manager&amp;tab=smugmug-options.php'><span class="icon">&nbsp;</span> SmugMug</a></li>
					</ul>
				</div>
			</div>
<?php
		$option_structure = $this->get_option_structure();
		$group = substr($this->tab, 0, stripos($this->tab, '.'));

		echo "<div class='photonic-options photonic-options-$group' id='photonic-options'>";
		echo "<div class='photonic-options-page-header fix'>\n";
		echo "<h1>{$this->tab_name}</h1>\n";
		echo "</div><!-- photonic-options-page-header -->\n";

		echo "<ul class='photonic-section-tabs'>";
		foreach ($option_structure as $l1_slug => $l1) {
			echo "<li><a href='#$l1_slug'>" . $l1['name'] . "</a></li>\n";
		}
		echo "</ul>";

		do_settings_sections($this->file);
		echo "</form>\n";
		echo "</div><!-- main-content -->\n";

		echo "</div><!-- /#photonic-options -->\n";
?>
		</div><!-- /#photonic-tabbed-options -->
	</div>
<?php
	}

	function init() {
		foreach ($this->option_structure as $slug => $option) {
			register_setting('photonic_options-'.$slug, 'photonic_options', array(&$this, 'validate_options'));
			add_settings_section($slug, "", array(&$this, "create_settings_section"), $this->file);
			$this->add_settings_fields($slug, $this->file);
		}
	}

	function validate_options($options) {
		foreach ($options as $option => $option_value) {
			if (isset($this->reverse_options[$option])) {
				//Sanitize options
				switch ($this->reverse_options[$option]) {
					// For all text type of options make sure that the eventual text is properly escaped.
					case "text":
					case "textarea":
					case "slider":
					case "color-picker":
					case "background":
					case "border":
					case "font":
					case "upload":
						$options[$option] = esc_attr($option_value);
						break;

					case "select":
					case "radio":
						if (isset($this->allowed_values[$option])) {
							if (!array_key_exists($option_value, $this->allowed_values[$option])) {
								$options[$option] = $this->option_defaults[$option];
							}
						}
				        break;

					case "multi-select":
						$selections = explode(',', $option_value);
						$final_selections = array();
						foreach ($selections as $selection) {
							if (array_key_exists($selection, $this->allowed_values[$option])) {
								$final_selections[] = $selection;
							}
						}
						$options[$option] = implode(',', $final_selections);
						break;

					case "sortable-list":
						$selections = explode(',', $option_value);
						$final_selections = array();
						$master_list = $this->option_defaults[$option]; // Sortable lists don't have their values in ['options']
						foreach ($selections as $selection) {
							if (array_key_exists($selection, $master_list)) {
								$final_selections[] = $selection;
							}
						}
						$options[$option] = implode(',', $final_selections);
						break;

					case "checkbox":
						if (!in_array($option_value, array('on', 'off', 'true', 'false')) && isset($this->option_defaults[$option])) {
							$options[$option] = $this->option_defaults[$option];
						}
						break;
				}
			}
		}

		/* The Settings API does an update_option($option, $value), overwriting the $photonic_options array with the values on THIS page
		 * This is problematic because all options are stored in a single array, but are displayed on different options pages.
		 * Hence the overwrite kills the options from the other pages.
		 * So this is a workaround to include the options from other pages as hidden fields on this page, so that the array gets properly updated.
		 * The alternative would be to separate options for each page, but that would cause a migration headache for current users.
		 */
		if (isset($this->hidden_options) && is_array($this->hidden_options)) {
			foreach ($this->hidden_options as $hidden_option => $hidden_value) {
				if (strlen($hidden_option) >= 7 && (substr($hidden_option, 0, 7) == 'submit-' || substr($hidden_option, 0, 6) == 'reset-')) {
					continue;
				}
				$options[$hidden_option] = esc_attr($hidden_value);
			}
		}

		foreach ($this->nested_options as $section => $children) {
			if (isset($options['submit-'.$section])) {
				$options['last-set-section'] = $section;
				if (substr($options['submit-'.$section], 0, 9) == 'Save page' || substr($options['submit-'.$section], 0, 10) == 'Reset page') {
					global $photonic_options;
					foreach ($this->nested_options as $inner_section => $inner_children) {
						if ($inner_section != $section) {
							foreach ($inner_children as $inner_child) {
								if (isset($photonic_options[$inner_child])) {
									$options[$inner_child] = $photonic_options[$inner_child];
								}
							}
						}
					}

					if (substr($options['submit-'.$section], 0, 10) == 'Reset page') {
						unset($options['submit-'.$section]);
						// This is a reset for an individual section. So we will unset the child fields.
						foreach ($children as $child) {
							unset($options[$child]);
						}
					}
					unset($options['submit-'.$section]);
				}
				else if (substr($options['submit-'.$section], 0, 12) == 'Save changes') {
					unset($options['submit-'.$section]);
				}
				else if (substr($options['submit-'.$section], 0, 13) == 'Reset changes') {
					unset($options['submit-'.$section]);
					// This is a reset for all options in the sub-menu. So we will unset all child fields.
					foreach ($this->nested_options as $section => $children) {
						foreach ($children as $child) {
							unset($options[$child]);
						}
					}
				}
				else if (substr($options['submit-'.$section], 0, 6) == 'Delete') {
					return;
				}
				break;
			}
		}
		return $options;
	}

	function get_option_structure() {
		if (isset($this->option_structure)) {
			return $this->option_structure;
		}
		$options = $this->tab_options;
		$option_structure = array();
		foreach ($options as $value) {
			switch ($value['type']) {
				case "title":
					$option_structure[$value['category']] = array();
					$option_structure[$value['category']]['slug'] = $value['category'];
					$option_structure[$value['category']]['name'] = $value['name'];
					$option_structure[$value['category']]['children'] = array();
					break;
				case "section":
			//		$option_structure[$value['parent']]['children'][$value['category']] = $value['name'];

					$option_structure[$value['category']] = array();
					$option_structure[$value['category']]['slug'] = $value['category'];
					$option_structure[$value['category']]['name'] = $value['name'];
					$option_structure[$value['category']]['children'] = array();
					if (isset($value['help'])) $option_structure[$value['category']]['help'] = $value['help'];
					if (isset($value['buttons'])) $option_structure[$value['category']]['buttons'] = $value['buttons'];
					break;
				default:
//					$option_structure[$value['grouping']]['children'][$value['name']] = $value['name'];
					if (isset($value['id'])) {
						$option_structure[$value['grouping']]['children'][$value['id']] = $value['name'];
					}
			}
		}
		return $option_structure;
	}

	function evaluate_conditions($conditions) {
		// Operators: NOT, OR, AND, NOR, NAND. XOR is too complex
		if (isset($conditions['operator'])) {
			$operator = $conditions['operator'];
		}
		else {
			$operator = 'OR';
		}
		$nested_conditions = $conditions['conditions'];
		if (isset($nested_conditions['operator'])) {
			return $this->evaluate_conditions($nested_conditions);
		}
		else {
			$evals = array();
			foreach ($nested_conditions as $variable => $check_value) {
				$photonic_variable = 'photonic_'.$variable;
				global $$photonic_variable;
				$actual_value = $$photonic_variable;

				if ($operator == 'NOT') {
					return $actual_value != $check_value;
				}
				else {
					$evals[] = $actual_value == $check_value ? 1 : 0;
				}
			}
			return $this->array_join_boolean($evals, $operator);
		}
	}

	function array_join_boolean($conditions, $operator) {
		if (count($conditions) == 1) {
			return $conditions[0];
		}
		else {
			$first = $conditions[0];
			$rest = array_slice($conditions, 1);
			if ($operator == 'AND') {
				$result =  $first * $this->array_join_boolean($rest, $operator);
				return $result != 0;
			}
			else if ($operator == 'NOR') {
				$result = $first + $this->array_join_boolean($rest, $operator);
				return $result == 0;
			}
			else if ($operator == 'NAND') {
				$result = $first * $this->array_join_boolean($rest, $operator);
				return $result == 0;
			}
			else { // Everything else is treated as OR
				$result = $first + $this->array_join_boolean($rest, $operator);
				return $result != 0;
			}
		}
	}

	function add_settings_fields($section, $page) {
		$ctr = 0;
		foreach ($this->tab_options as $value) {
			if (isset($value['conditional']) && true === $value['conditional']) {
				$show = true;
				if (isset($value['conditions'])) {
					$conditions = $value['conditions'];
					$show = $this->evaluate_conditions($conditions);
				}
				if (!$show) {
					continue;
				}
			}
			$ctr++;
			switch ($value['type']) {
				case "section":
					add_settings_field('', '', array(&$this, "create_title"), $page, $section, $value);
					break;

				case "blurb";
					add_settings_field($value['grouping'].'-'.$ctr, '', array(&$this, "create_section_for_blurb"), $page, $value['grouping'], $value);
					break;

				case "text";
					add_settings_field($value['id'], '', array(&$this, "create_section_for_text"), $page, $value['grouping'], $value);
					break;

				case "textarea";
					add_settings_field($value['id'], '', array(&$this, "create_section_for_textarea"), $page, $value['grouping'], $value);
					break;

				case "select":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_select"), $page, $value['grouping'], $value);
					break;

				case "radio":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_radio"), $page, $value['grouping'], $value);
//					add_settings_field($value['id'], '', array(&$this, "create_section_for_radio"), $parent, 'default', $value);
					break;

				case "slider":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_slider"), $page, $value['grouping'], $value);
//					add_settings_field($value['id'], '', array(&$this, "create_section_for_slider"), $parent, $section, $value);
					break;

				case "color-picker":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_color_picker"), $page, $value['grouping'], $value);
					break;

				case "checkbox":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_checkbox"), $page, $value['grouping'], $value);
					break;

				case "border":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_border"), $page, $value['grouping'], $value);
					break;

				case "background":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_background"), $page, $value['grouping'], $value);
					break;

				case "padding":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_padding"), $page, $value['grouping'], $value);
					break;

				case "ajax-button":
					add_settings_field($value['id'], '', array(&$this, "create_section_for_ajax_button"), $page, $value['grouping'], $value);
					break;

				case 'oauth-authorize':
					add_settings_field($value['id'], '', array(&$this, "create_section_for_oauth_authorization"), $page, $value['grouping'], $value);
					break;
			}
		}
	}

	function create_title($value) {
		//echo '<h2 class="photonic-header-1">'.$value['name']."</h2>\n";
	}

	function create_section_for_radio($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		foreach ($value['options'] as $option_value => $option_text) {
			$option_value = stripslashes($option_value);
			if (isset($photonic_options[$value['id']])) {
				$checked = checked(stripslashes($photonic_options[$value['id']]), $option_value, false);
			}
			else {
				$checked = checked($value['std'], $option_value, false);
			}
			echo '<div class="photonic-radio"><label><input type="radio" name="photonic_options['.$value['id'].']" value="'.$option_value.'" '.$checked."/>".$option_text."</label></div>\n";
		}
		$this->create_closing_tag($value);
	}

	function create_section_for_text($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		if (!isset($photonic_options[$value['id']])) {
			$text = $value['std'];
		}
		else {
			$text = $photonic_options[$value['id']];
			$text = stripslashes($text);
			$text = esc_attr($text);
		}

		echo '<input type="text" name="photonic_options['.$value['id'].']" value="'.$text.'" />'."\n";
		if (isset($value['hint'])) {
			echo " &laquo; ".$value['hint']."<br />\n";
		}
		$this->create_closing_tag($value);
	}

	function create_section_for_textarea($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		echo '<textarea name="photonic_options['.$value['id'].']" cols="" rows="">'."\n";
		if (isset($photonic_options[$value['id']]) && $photonic_options[$value['id']] != "") {
			$text = stripslashes($photonic_options[$value['id']]);
			$text = esc_attr($text);
			echo $text;
		}
		else {
			echo $value['std'];
		}
		echo '</textarea>';
		if (isset($value['hint'])) {
			echo " &laquo; ".$value['hint']."<br />\n";
		}
		$this->create_closing_tag($value);
	}

	function create_section_for_select($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		echo '<select name="photonic_options['.$value['id'].']">'."\n";
		foreach ($value['options'] as $option_value => $option_text) {
			echo "<option ";
			if (isset($photonic_options[$value['id']])) {
				selected($photonic_options[$value['id']], $option_value);
			}
			else {
				selected($value['std'], $option_value);
			}
			echo " value='$option_value' >".$option_text."</option>\n";
		}
		echo "</select>\n";
		$this->create_closing_tag($value);
	}

	/**
	 * Renders an option whose type is "slider". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	function create_section_for_slider($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		$options = $value['options'];
		if (!isset($photonic_options[$value['id']])) {
			$default = $value['std'];
		}
		else {
			$default = $photonic_options[$value['id']];
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
				<input type="text" id="<?php echo $value['id']; ?>" name="photonic_options[<?php echo $value['id']; ?>]" value="<?php echo $default; ?>" class='slidertext' /> <?php echo $options['unit'];?>
			</p>
			<div id="<?php echo $value['id']; ?>-slider"  style="width:<?php echo $options['size'];?>;"></div>
		</div>
	<?php
		$this->create_closing_tag($value);
	}

	function create_section_for_color_picker($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		if (!isset($photonic_options[$value['id']])) {
			$color_value = $value['std'];
		}
		else {
			$color_value = $photonic_options[$value['id']];
		}
		if (substr($color_value, 0, 1) != '#') {
			$color_value = "#$color_value";
		}

		echo '<div class="color-picker">'."\n";
		echo '<input type="text" id="'.$value['id'].'" name="photonic_options['.$value['id'].']" value="'.$color_value.'" class="color color-'.$value['id'].'" /> <br/>'."\n";
		echo "<strong>Default: ".$value['std']."</strong> (You can copy and paste this into the box above)\n";
		echo "</div>\n";
		$this->create_closing_tag($value);
	}

	function create_settings_section($section) {
		$option_structure = $this->option_structure;
		if ($this->displayed_sections != 0) {
			echo "</form>\n";
			echo "</div><!-- main-content -->\n";
		}

		echo "<div id='{$section['id']}' class='photonic-options-panel'> <!-- main-content -->\n";
		echo "<form method=\"post\" action=\"options.php\" id=\"photonic-options-form-{$section['id']}\" class='photonic-options-form'>\n";
		echo '<h3>' . $option_structure[$section['id']]['name'] . "</h3>\n";

		/*
		 * We store all options in one array, but display them across multiple pages. Hence we need the following hack.
		 * We are registering the same setting across multiple pages, hence we need to pass the "page" parameter to options.php.
		 * Otherwise options.php returns an error saying "Options page not found"
		 */
		echo "<input type='hidden' name='page' value='" . esc_attr($_REQUEST['page']) . "' />\n";
		if (!isset($_REQUEST['tab'])) {
			$tab = 'theme-options-intro.php';
		}
		else {
			$tab = esc_attr($_REQUEST['tab']);
		}
		echo "<input type='hidden' name='tab' value='" . $tab . "' />\n";

		settings_fields("photonic_options-{$section['id']}");
		if (!isset($option_structure[$section['id']]['buttons']) ||
				($option_structure[$section['id']]['buttons'] != 'no-buttons' && $option_structure[$section['id']]['buttons'] != 'special-buttons')) {
			echo "<div class=\"photonic-button-toggler fix\"><a href='#' class='photonic-button-toggler-{$section['id']}'><span class='photonic-button-toggler-{$section['id']}'>Save / Reset</span></a></div>\n";
			echo "<div class=\"photonic-button-bar photonic-button-bar-{$section['id']}\" title='Save / Reset'>\n";
			echo "<h2 class='fix'><a href='#'><img src='".plugins_url('/include/images/remove.png', __FILE__)."' alt='Close' /></a>Save / Reset</h2>\n";
			echo "<input name=\"photonic_options[submit-{$section['id']}]\" type='submit' value=\"Save page '".esc_attr($option_structure[$section['id']]['name'])."'\" class=\"button photonic-button-section\" />\n";
			echo "<input name=\"photonic_options[submit-{$section['id']}]\" type='submit' value=\"Reset page '".esc_attr($option_structure[$section['id']]['name'])."'\" class=\"button photonic-button-section\" />\n";
			echo "<input name=\"photonic_options[submit-{$section['id']}]\" type='submit' value=\"Delete all options\" class=\"button photonic-button-all\" />\n";
			echo "</div><!-- photonic-button-bar -->\n";
		}
		$this->displayed_sections++;
		$this->previous_displayed_section = $section['id'];
	}

	function create_section_for_blurb($value) {
		$this->create_opening_tag($value);
		$this->create_closing_tag($value);
	}

	/**
	 * Renders an option whose type is "checkbox". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	function create_section_for_checkbox($value) {
		global $photonic_options;
		$checked = '';
		if (isset($photonic_options[$value['id']])) {
			$checked = checked(stripslashes($photonic_options[$value['id']]), 'on', false);
		}
		$this->create_opening_tag($value);
		echo '<label><input type="checkbox" name="photonic_options['.$value['id'].']" '.$checked."/>{$value['desc']}</label>\n";
		$this->create_closing_tag($value);
	}

	/**
	 * Renders an option whose type is "border". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	function create_section_for_border($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		$original = $value['std'];
		if (!isset($photonic_options[$value['id']])) {
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
			$default_txt = $photonic_options[$value['id']];
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
			if (isset($photonic_options[$value['id']])) {
				$checked = checked($photonic_options[$value['id']], $option_value, false);
			}
			else {
				$checked = checked($value['std'], $option_value, false);
			}
			echo '<div class="photonic-radio"><input type="radio" name="'.$value['id'].'" value="'.$option_value.'" '.$checked."/>".$option_text."</div>\n";
		}
	?>
		<div class='photonic-border-options'>
			<p>For any edge set style to "No Border" if you don't want a border.</p>
			<table class='opt-sub-table-5'>
				<col class='opt-sub-table-col-51'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>

				<tr>
					<th scope="col">&nbsp;</th>
					<th scope="col">Border Style</th>
					<th scope="col">Color</th>
					<th scope="col">Border Width</th>
					<th scope="col">Border Width Units</th>
				</tr>

		<?php
			foreach ($edges as $edge => $edge_text) {
		?>
			<tr>
				<th scope="row"><?php echo $edge_text." border"; ?></th>
				<td valign='top'>
					<select name="<?php echo $value['id'].'-'.$edge; ?>-style" id="<?php echo $value['id'].'-'.$edge; ?>-style" >
				<?php
					foreach ($styles as $option_value => $option_text) {
						echo "<option ";
						if (isset($default[$edge]) && isset($default[$edge]['style'])) {
							selected($default[$edge]['style'], $option_value);
						}
						echo " value='$option_value' >".$option_text."</option>\n";
					}
				?>
					</select>
				</td>

				<td valign='top'>
					<div class="color-picker-group">
						<input type="radio" name="<?php echo $value['id'].'-'.$edge; ?>-colortype" value="transparent" <?php checked($default[$edge]['colortype'], 'transparent'); ?> /> Transparent / No color<br/>
						<input type="radio" name="<?php echo $value['id'].'-'.$edge; ?>-colortype" value="custom" <?php checked($default[$edge]['colortype'], 'custom'); ?>/> Custom
						<input type="text" id="<?php echo $value['id'].'-'.$edge; ?>-color" name="<?php echo $value['id']; ?>-color" value="<?php echo $default[$edge]['color']; ?>" class="color" /><br />
						Default: <span> <?php echo $original[$edge]['color']; ?> </span>
					</div>
				</td>

				<td valign='top'>
					<input type="text" id="<?php echo $value['id'].'-'.$edge; ?>-border-width" name="<?php echo $value['id'].'-'.$edge; ?>-border-width" value="<?php echo $default[$edge]['border-width']; ?>" /><br />
				</td>

				<td valign='top'>
					<select name="<?php echo $value['id'].'-'.$edge; ?>-border-width-type" id="<?php echo $value['id'].'-'.$edge; ?>-border-width-type" >
				<?php
					foreach ($border_width_units as $option_value => $option_text) {
						echo "<option ";
						selected($default[$edge]['border-width-type'], $option_value);
						echo " value='$option_value' >".$option_text."</option>\n";
					}
				?>
					</select>
				</td>
			</tr>
		<?php
			}
		?>
			</table>
		<input type='hidden' id="<?php echo $value['id']; ?>" name="photonic_options[<?php echo $value['id']; ?>]" value="<?php echo $default_txt; ?>" />
		</div>
	<?php
		$this->create_closing_tag($value);
	}

	/**
	 * Renders an option whose type is "background". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	function create_section_for_background($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		$original = $value['std'];
		if (!isset($photonic_options[$value['id']])) {
			$default = $value['std'];
			$default_txt = "";
			foreach ($value['std'] as $opt => $opt_val) {
				$default_txt .= $opt."=".$opt_val.";";
			}
		}
		else {
			$default_txt = $photonic_options[$value['id']];
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
			if (isset($photonic_options[$value['id']])) {
				$checked = checked($photonic_options[$value['id']], $option_value, false);
			}
			else {
				$checked = checked($value['std'], $option_value, false);
			}
			echo '<div class="photonic-radio"><input type="radio" name="'.$value['id'].'" value="'.$option_value.'" '.$checked."/>".$option_text."</div>\n";
		}
	?>
		<div class='photonic-background-options'>
		<table class='opt-sub-table'>
	        <col class='opt-sub-table-cols'/>
	        <col class='opt-sub-table-cols'/>
			<tr>
				<td valign='top'>
					<div class="color-picker-group">
						<strong>Background Color:</strong><br />
						<input type="radio" name="<?php echo $value['id']; ?>-colortype" value="transparent" <?php checked($default['colortype'], 'transparent'); ?> /> Transparent / No color<br/>
						<input type="radio" name="<?php echo $value['id']; ?>-colortype" value="custom" <?php checked($default['colortype'], 'custom'); ?>/> Custom
						<input type="text" id="<?php echo $value['id']; ?>-bgcolor" name="<?php echo $value['id']; ?>-bgcolor" value="<?php echo $default['color']; ?>" class="color" /><br />
						Default: <span> <?php echo $original['color']; ?> </span>
					</div>
				</td>
				<td valign='top'>
					<strong>Image URL:</strong><br />
					<?php $this->display_upload_field($default['image'], $value['id']."-bgimg", $value['id']."-bgimg"); ?>
				</td>
			</tr>

			<tr>
				<td valign='top'>
					<strong>Image Position:</strong><br />
					<select name="<?php echo $value['id']; ?>-position" id="<?php echo $value['id']; ?>-position" >
				<?php
					foreach ($positions as $option_value => $option_text) {
						echo "<option ";
						selected($default['position'], $option_value);
						echo " value='$option_value' >".$option_text."</option>\n";
					}
				?>
					</select>
				</td>

				<td valign='top'>
					<strong>Image Repeat:</strong><br />
					<select name="<?php echo $value['id']; ?>-repeat" id="<?php echo $value['id']; ?>-repeat" >
				<?php
					foreach ($repeats as $option_value => $option_text) {
						echo "<option ";
						selected($default['repeat'], $option_value);
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
		<input type='hidden' id="<?php echo $value['id']; ?>" name="photonic_options[<?php echo $value['id']; ?>]" value="<?php echo $default_txt; ?>" />
		</div>
	<?php
		$this->create_closing_tag($value);
	}

	/**
	 * Renders an option whose type is "background". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	function create_section_for_padding($value) {
		global $photonic_options;
		$this->create_opening_tag($value);
		if (!isset($photonic_options[$value['id']])) {
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
			$default_txt = $photonic_options[$value['id']];
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
		$padding_units = array("px" => "Pixels (px)", "em" => "Em");

		foreach ($value['options'] as $option_value => $option_text) {
			if (isset($photonic_options[$value['id']])) {
				$checked = checked($photonic_options[$value['id']], $option_value, false);
			}
			else {
				$checked = checked($value['std'], $option_value, false);
			}
			echo '<div class="photonic-radio"><input type="radio" name="'.$value['id'].'" value="'.$option_value.'" '.$checked."/>".$option_text."</div>\n";
		}
	?>
		<div class='photonic-padding-options'>
			<table class='opt-sub-table-5'>
				<col class='opt-sub-table-col-51'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>

				<tr>
					<th scope="col">&nbsp;</th>
					<th scope="col">Padding</th>
					<th scope="col">Padding Units</th>
				</tr>

		<?php
			foreach ($edges as $edge => $edge_text) {
		?>
			<tr>
				<th scope="row"><?php echo $edge_text." padding"; ?></th>
				<td valign='top'>
					<input type="text" id="<?php echo $value['id'].'-'.$edge; ?>-padding" name="<?php echo $value['id'].'-'.$edge; ?>-padding" value="<?php echo $default[$edge]['padding']; ?>" /><br />
				</td>

				<td valign='top'>
					<select name="<?php echo $value['id'].'-'.$edge; ?>-padding-type" id="<?php echo $value['id'].'-'.$edge; ?>-padding-type" >
				<?php
					foreach ($padding_units as $option_value => $option_text) {
						echo "<option ";
						selected($default[$edge]['padding-type'], $option_value);
						echo " value='$option_value' >".$option_text."</option>\n";
					}
				?>
					</select>
				</td>
			</tr>
		<?php
			}
		?>
			</table>
		<input type='hidden' id="<?php echo $value['id']; ?>" name="photonic_options[<?php echo $value['id']; ?>]" value="<?php echo $default_txt; ?>" />
		</div>
	<?php
		$this->create_closing_tag($value);
	}

	function create_section_for_ajax_button($value) {
		$this->create_opening_tag($value);
		//echo "<a href='' "
		$this->create_closing_tag($value);
	}

	function create_section_for_oauth_authorization($value) {
		$this->create_opening_tag($value);
		echo "<a class='button oauth-authorize smugmug' href='".$value['link']."'>".$value['std']."</a>";
		$this->create_closing_tag($value);
	}

	/**
	 * Creates the opening markup for each option.
	 *
	 * @param  $value
	 * @return void
	 */
	function create_opening_tag($value) {
		echo "<div class='photonic-section fix'>\n";
		if (isset($value['name'])) {
			echo "<h3>" . $value['name'] . "</h3>\n";
		}
		if (isset($value['desc']) && $value['type'] != 'checkbox') {
			echo $value['desc']."<br />";
		}
		if (isset($value['note'])) {
			echo "<span class=\"note\">".$value['note']."</span><br />";
		}
	}

	/**
	 * Creates the closing markup for each option.
	 *
	 * @param $value
	 * @return void
	 */
	function create_closing_tag($value) {
		echo "</div><!-- photonic-section -->\n";
	}

	/**
	 * This method displays an upload field and button. This has been separated from the create_section_for_upload method,
	 * because this is used by the create_section_for_background as well.
	 *
	 * @param $upload
	 * @param $id
	 * @param $name
	 * @param null $hint
	 * @return void
	 */
	function display_upload_field($upload, $id, $name, $hint = null) {
		echo '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$upload.'" />'."\n";
		if ($hint != null) {
			echo " &laquo; ".$hint."<br />\n";
		}

		echo '<div class="upload-buttons">';
		$hide = empty($upload) ? '' : 'hidden';
		echo '<span class="button image_upload_button '.$hide.'" id="upload_'.$id.'">Upload Image</span>';

		$hide = !empty($upload) ? '' : 'hidden';
		echo '<span class="button image_reset_button '. $hide.'" id="reset_'.$id.'">Reset</span>';
		echo '</div>' . "\n";

		if(!empty($upload)){
			echo "<div id='photonic-preview-$id'>\n";
			echo "<p><strong>Preview:</strong></p>\n";
		    echo '<img class="photonic-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
			echo "</div>";
		}
	}

	/**
	 * Called when you upload a file for option type "upload". This is an AJAX call.
	 *
	 * @return void
	 */
	function admin_upload_file() {
		$save_type = $_POST['type'];
		if ($save_type == 'upload') {
			$data = $_POST['data']; // Acts as the name
			$filename = $_FILES[$data];
			$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

			$override['test_form'] = false;
			$override['action'] = 'wp_handle_upload';
			$uploaded_file = wp_handle_upload($filename, $override);

			$image_id = substr($data, 7);

			if (!empty($uploaded_file['error'])) {
				echo 'Upload Error: ' . $uploaded_file['error'];
			}
			else {
				$this->options[$image_id] = $uploaded_file['url'];
				echo $uploaded_file['url'];
			}
		}
		elseif ($save_type == 'image_reset') {
			$data = $_POST['data'];
			$image_id = substr($data, 6);
			if (isset($this->options[$image_id])) unset($this->options[$image_id]);
		}
		die();
	}

	function authenticate_oauth() {
		//
	}
}
?>