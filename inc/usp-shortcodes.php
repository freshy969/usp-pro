<?php // USP Pro - Shortcodes

if (!defined('ABSPATH')) die();

if (usp_is_session_started() === false) session_start();

require_once(dirname(dirname(__FILE__)) .'/shortcodes/usp-cats.php');
require_once(dirname(dirname(__FILE__)) .'/shortcodes/usp-tags.php');
require_once(dirname(dirname(__FILE__)) .'/shortcodes/usp-tax.php');

/*
	Shortcode: Fieldset
		Displays opening/closing fieldset brackets
		Syntax: [usp_fieldset class="aaa,bbb,ccc"][...][#usp_fieldset]
		Attributes:
			class = classes comma-sep list (displayed as class="aaa bbb ccc") 
*/
if (!function_exists('usp_fieldset_open')) : 
function usp_fieldset_open($args) {
	
	$class = isset($args['class']) ? 'usp-fieldset,'. $args['class'] : 'usp-fieldset';
	
	$classes = usp_classes($class, 'fieldset');
	
	return '<fieldset class="'. $classes .'">'. "\n";
	
}
add_shortcode('usp_fieldset', 'usp_fieldset_open');
function usp_fieldset_close() { return '</fieldset>'. "\n"; }
add_shortcode('#usp_fieldset', 'usp_fieldset_close');
endif;

/*
	Shortcode: Name
	Displays name input field
	Syntax: [usp_name class="aaa,bbb,ccc" placeholder="Your Name" label="Your Name" required="yes" max="99" fieldset="true"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_name')) : 
function usp_input_name($args) {
	
	$current_user = wp_get_current_user();
	
	if ($current_user->ID) $value = $current_user->user_login;
	elseif (isset($_SESSION['usp_form_session']['usp-name']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-name'];
	else $value = '';
	
	$value = apply_filters('usp_input_name_value', $value);
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	$field = 'usp_error_1';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-name,' . $args['class'];
	else $class = 'usp-input,usp-input-name';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	
	$max = usp_max_att($args, '999999');

	if (empty($label)) $content = '';
	else $content = '<label for="usp-name" class="usp-label usp-label-name">'. $label .'</label>'. "\n";
	
	$content .= '<input name="usp-name" id="usp-name" type="text" value="'. esc_attr($value) .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input type="hidden" name="usp-name-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_name', 'usp_input_name');
endif;

/*
	Shortcode: URL
	Displays URL input field
	Syntax: [usp_url class="aaa,bbb,ccc" placeholder="Your URL" label="Your URL" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_url')) : 
function usp_input_url($args) {
	if (isset($_SESSION['usp_form_session']['usp-url']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-url'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	$field = 'usp_error_2';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-url,' . $args['class'];
	else $class = 'usp-input,usp-input-url';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999999');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-url" class="usp-label usp-label-url">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-url" id="usp-url" type="text" value="'. esc_url($value) .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input type="hidden" name="usp-url-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_url', 'usp_input_url');
endif;

/*
	Shortcode: Title
	Displays title input field
	Syntax: [usp_title class="aaa,bbb,ccc" placeholder="Post Title" label="Post Title" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_title')) : 
function usp_input_title($args) {
	if (isset($_SESSION['usp_form_session']['usp-title']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-title'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	$field = 'usp_error_3';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-title,' . $args['class'];
	else $class = 'usp-input,usp-input-title';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999999');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-title" class="usp-label usp-label-title">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-title" id="usp-title" type="text" value="'. esc_attr($value) .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input type="hidden" name="usp-title-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_title', 'usp_input_title');
endif;

/*
	Shortcode: Captcha
	Displays captcha input field
	Syntax: [usp_captcha class="aaa,bbb,ccc" placeholder="Antispam Question" label="Antispam Question" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_captcha')) : 
function usp_input_captcha($args) {
	global $usp_general;
	$required = 'true'; // always required when included in form
	if (isset($_SESSION['usp_form_session']['usp-captcha']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-captcha'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	$field = 'usp_error_5';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-captcha,' . $args['class'];
	else $class = 'usp-input,usp-input-captcha';
	$classes = usp_classes($class, $field);

	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$max = usp_max_att($args, '999999');
	
	$recaptcha_public  = isset($usp_general['recaptcha_public'])  ? $usp_general['recaptcha_public']  : '';
	$recaptcha_private = isset($usp_general['recaptcha_private']) ? $usp_general['recaptcha_private'] : '';
	$recaptcha_version = isset($usp_general['recaptcha_version']) ? $usp_general['recaptcha_version'] : 'v1';
	
	if (!empty($recaptcha_public) && !empty($recaptcha_private)) {
		
		if ($recaptcha_version === 'v1') {
			
			$id = 'recaptcha_response_field';
			$captcha = '<script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k='. $recaptcha_public .'"></script>
			<noscript>
				<iframe src="https://www.google.com/recaptcha/api/noscript?k='. $recaptcha_public .'" height="300" width="500" frameborder="0"></iframe><br>
				<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
				<input type="hidden" name="recaptcha_response_field" id="'. $id .'" value="manual_challenge">
			</noscript>'. "\n";
			
		} elseif ($recaptcha_version === 'v2') {
			
			$captcha_params = apply_filters('usp_captcha_params', '');
			$captcha_atts   = apply_filters('usp_captcha_atts',   '');
			
			$id = 'g-recaptcha-response';
			$captcha = '<script src="https://www.google.com/recaptcha/api.js'. $captcha_params .'" async defer></script>
			<div class="g-recaptcha" '. $captcha_atts .' data-sitekey="'. $recaptcha_public .'"></div>'. "\n";
		}
	} else {
		
		$id = 'usp-captcha';
		$captcha = '<input name="usp-captcha" id="'. $id .'" type="text" value="'. esc_attr($value) .'" data-required="true" required="required" maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	}
	
	$captcha = apply_filters('usp_captcha_output', $captcha);
	
	if (empty($label)) $content = '';
	else $content  = '<label for="'. $id .'" class="usp-label usp-label-captcha">'. $label .'</label>'. "\n";
	
	if ($required == 'true') $required = '<input type="hidden" name="usp-captcha-required" value="1" />'. "\n";
	
	return $fieldset_before . $content . $captcha . $required . $fieldset_after;
}
add_shortcode('usp_captcha', 'usp_input_captcha');
endif;

/*
	Shortcode: Content
	Displays content textarea
	Syntax: [usp_content class="aaa,bbb,ccc" placeholder="Post Content" label="Post Content" required="yes" max="999" cols="3" rows="30" richtext="off"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		cols        = sets the number of columns for the textarea
		rows        = sets the number of rows for the textarea
		richtext    = specifies whether or not to use WP rich-text editor
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_content')) : 
function usp_input_content($args) {
	if (isset($_SESSION['usp_form_session']['usp-content']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-content'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	$field = 'usp_error_7';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-textarea,usp-input-content,' . $args['class'];
	else $class = 'usp-input,usp-textarea,usp-input-content';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999999');
	
	if (isset($args['cols']) && !empty($args['cols'])) $cols = trim(intval($args['cols']));
	else $cols = '30';
	
	if (isset($args['rows']) && !empty($args['rows'])) $rows = trim(intval($args['rows']));
	else $rows = '5';
	
	if (isset($args['richtext']) && !empty($args['richtext']) && ($args['richtext'] == 'on' || $args['richtext'] == 'yes' || $args['richtext'] == 'true')) $richtext = 'on';
	else $richtext = 'off';
	
	if (empty($label)) $content = '';
	else $content = '<label for="usp-content" class="usp-label usp-label-content">'. $label .'</label>'. "\n";
	if ($richtext == 'on') {
		$settings = array(
			'wpautop'          => true,          // enable rich text editor
			'media_buttons'    => true,          // enable add media button
			'textarea_name'    => 'usp-content', // name
			'textarea_rows'    => $rows,         // number of textarea rows
			'tabindex'         => '',            // tabindex
			'editor_css'       => '',            // extra CSS
			'editor_class'     => $classes,      // class
			'editor_height'    => '',            // editor height
			'teeny'            => false,         // output minimal editor config
			'dfw'              => false,         // replace fullscreen with DFW
			'tinymce'          => true,          // enable TinyMCE
			'quicktags'        => true,          // enable quicktags
			'drag_drop_upload' => true,          // drag-n-drop uploads
		);
		$settings = apply_filters('usp_wp_editor_settings', $settings);
		$value = apply_filters('usp_wp_editor_value', $value);
		ob_start(); // until get_wp_editor() is available..
		wp_editor($value, 'uspcontent', $settings);
		$get_wp_editor = ob_get_clean();
		$content .= $get_wp_editor;
	} else {
		$content .= '<textarea name="usp-content" id="usp-content" rows="'. $rows .'" cols="'. $cols .'" maxlength="'. $max .'" data-required="'. $required .'" '. $parsley .'placeholder="'. $placeholder .'" class="'. $classes .'"'. $custom .'>'. esc_html($value) .'</textarea>'. "\n";
	}
	if ($required == 'true') $content .= '<input type="hidden" name="usp-content-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_content', 'usp_input_content');
endif;

/*
	Shortcode: Excerpt
	Displays excerpt textarea
	Syntax: [usp_excerpt class="aaa,bbb,ccc" placeholder="Post Excerpt" label="Post Excerpt" required="yes" max="999" cols="3" rows="30" richtext="off"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		cols        = sets the number of columns for the textarea
		rows        = sets the number of rows for the textarea
		richtext    = specifies whether or not to use WP rich-text editor
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_excerpt')) : 
function usp_input_excerpt($args) {
	if (isset($_SESSION['usp_form_session']['usp-excerpt']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-excerpt'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	$field = 'usp_error_19';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-textarea,usp-input-excerpt,' . $args['class'];
	else $class = 'usp-input,usp-textarea,usp-input-excerpt';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999999');
	
	if (isset($args['cols']) && !empty($args['cols'])) $cols = trim(intval($args['cols']));
	else $cols = '30';
	
	if (isset($args['rows']) && !empty($args['rows'])) $rows = trim(intval($args['rows']));
	else $rows = '5';
	
	if (isset($args['richtext']) && !empty($args['richtext']) && ($args['richtext'] == 'on' || $args['richtext'] == 'yes' || $args['richtext'] == 'true')) $richtext = 'on';
	else $richtext = 'off';
	
	if (empty($label)) $content = '';
	else $content = '<label for="usp-excerpt" class="usp-label usp-label-excerpt">'. $label .'</label>'. "\n";
	if ($richtext == 'on') {
		$settings = array(
			'wpautop'          => true,          // enable rich text editor
			'media_buttons'    => true,          // enable add media button
			'textarea_name'    => 'usp-excerpt', // name
			'textarea_rows'    => $rows,         // number of textarea rows
			'tabindex'         => '',            // tabindex
			'editor_css'       => '',            // extra CSS
			'editor_class'     => $classes,      // class
			'editor_height'    => '',            // editor height
			'teeny'            => false,         // output minimal editor config
			'dfw'              => false,         // replace fullscreen with DFW
			'tinymce'          => true,          // enable TinyMCE
			'quicktags'        => true,          // enable quicktags
			'drag_drop_upload' => true,          // drag-n-drop uploads
		);
		$settings = apply_filters('usp_excerpt_editor_settings', $settings);
		$value = apply_filters('usp_excerpt_editor_value', $value);
		ob_start(); // until get_wp_editor() is available..
		wp_editor($value, 'uspexcerpt', $settings);
		$get_wp_editor = ob_get_clean();
		$content .= $get_wp_editor;
	} else {
		$content .= '<textarea name="usp-excerpt" id="usp-excerpt" rows="'. $rows .'" cols="'. $cols .'" maxlength="'. $max .'" data-required="'. $required .'" '. $parsley .'placeholder="'. $placeholder .'" class="'. $classes .'"'. $custom .'>'. esc_html($value) .'</textarea>'. "\n";
	}
	if ($required == 'true') $content .= '<input type="hidden" name="usp-excerpt-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_excerpt', 'usp_input_excerpt');
endif;

/*
	Shortcode: Files
	Displays file-upload input field
	Syntax: [usp_files class="aaa,bbb,ccc" placeholder="Upload File" label="Upload File" required="yes" max="99" link="Add another file" multiple="yes" key="single"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		link        = specifies text for the add-another input link (when displayed)
		multiple    = specifies whether to display single or multiple file input fields
		key         = key to use for custom field for this image
		types       = allowed file types (overrides global defaults)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default = true)
		preview_off = disable file preview: true
		files_min   = specifies minimum number of required files (overrides global defaults)
		files_max   = specifies maximum number of allowed files (overrides global defaults)
*/
if (!function_exists('usp_input_files')) : 
function usp_input_files($args) {
	global $usp_uploads;
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['files_max']) && is_numeric($args['files_max'])) $files_max = $args['files_max'];
	else $files_max = $usp_uploads['max_files'];
	
	if (isset($args['files_min']) && is_numeric($args['files_min'])) $files_min = $args['files_min'];
	else $files_min = $usp_uploads['min_files'];
	
	$files_max = (intval($files_max) < 0) ? 9999999 : $files_max;
	
	$files_min = (intval($files_min) > intval($files_max)) ? $files_max : $files_min;
	
	$files_min = (intval($files_min) < 1) ? 1 : $files_min;
	
	$field = 'usp_error_8';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-files,' . $args['class'];
	else $class = 'usp-input,usp-input-files';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	if (isset($args['preview_off'])) $preview = '<input type="hidden" name="usp-file-preview" value="1" />'. "\n";
	else $preview = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	if (isset($args['types'])) {
		$allow_types = trim($args['types']);
		$types = explode(",", $allow_types);
		$file_types = '';
		foreach ($types as $type) $file_types .= trim($type) . ',';
		$file_types = rtrim(trim($file_types), ',');
	} else {
		$file_types = '';
	}
	
	$required = usp_required($args);
	$max = usp_max_att($args, '999999');
	
	if ($required === 'true' && empty($files_min)) $files_min = '1';
	
	$key = 'single';
	if (isset($args['key']) && is_numeric($args['key'])) $key = $args['key'];
	
	$multiple = true;
	if (isset($args['multiple'])) {
		if ($args['multiple'] == 'no' || $args['multiple'] == 'false' || $args['multiple'] == 'off') $multiple = false;
	}
	$method = '';
	if (isset($args['method'])) {
		if ($args['method'] == 'yes' || $args['method'] == 'true' || $args['method'] == 'on' || $args['method'] == 'select') $method = ' multiple="multiple"';
	}
	if (isset($args['link']) && !empty($args['link'])) $link = trim($args['link']);
	else $link = 'Add another file';
	
	if (intval($files_max) !== 0) {
		
		$content = '<div id="usp-files-wrap" class="usp-files">'. "\n";
		
		if ($multiple) {
			
			$content .= empty($label) ? '' : '<label for="usp-files" class="usp-label usp-label-files">'. $label .'</label>'. "\n";
			
			$content .= '<div class="usp-input-wrap">'. "\n";
			
			if (empty($method)) {
				
				for ($i = 1; $i <= $files_min; $i++) {
					
					$content .= '<input name="usp-files[]" id="usp-files-'. $i .'" type="file" maxlength="'. $max .'" data-required="'. $required .'" data-file="'. $i .'" placeholder="'. $placeholder .'" class="'. $classes .' add-another multiple" '. $custom .'/>'. "\n";
				
				}
				
				if ((intval($files_min) < intval($files_max)) || (intval($files_min) === 1 && intval($files_max) < 0)) {
					
					$content .= '<div class="usp-add-another"><a href="#">'. $link .'</a></div>'. "\n";
					
				}
				
			} else {
				
				$content .= '<input name="usp-files[]" id="usp-files" type="file" maxlength="'. $max .'" data-required="'. $required .'" placeholder="'. $placeholder .'" class="'. $classes .' select-file multiple"'. $method .' '. $custom .'/>'. "\n";
				
			}
			
			$content .= '</div>'. "\n";
			$content .= '<input type="hidden" name="usp-file-limit" class="usp-file-limit" value="'. $files_max .'" />'. "\n";
			$content .= '<input type="hidden" name="usp-file-count" class="usp-file-count" value="'. $files_min .'" />'. "\n";
			
			if (!empty($file_types)) $content .= '<input type="hidden" name="usp-file-types" value="'. $file_types .'" />'. "\n";
			if ($required == 'true') $content .= '<input type="hidden" name="usp-files-required" value="'. $files_min .'" />'. "\n";
			
		} else {
			
			$content .= empty($label) ? '' : '<label for="usp-file-'. $key .'" class="usp-label usp-label-files usp-label-file usp-label-file-'. $key .'">'. $label .'</label>'. "\n";
			
			$method_class = empty($method) ? ' add-another single-file' : ' select-file single-file';
			
			$content .= '<input name="usp-file-'. $key .'" id="usp-file-'. $key .'" type="file" maxlength="'. $max .'" data-required="'. $required .'" placeholder="'. $placeholder .'" class="'. $classes .' usp-input-file usp-input-file-'. $key . $method_class .'" '. $custom .'/>'. "\n";
			
			$content .= '<input type="hidden" name="usp-file-key" value="'. $key .'" />'. "\n";
			
			if (!empty($file_types)) $content .= '<input type="hidden" name="usp-file-types" value="'. $file_types .'" />'. "\n";
			if ($required == 'true') $content .= '<input type="hidden" name="usp-file-required-'. $key .'" value="'. $files_min .'" />'. "\n";
			
		}
		
		$content .= $preview .'</div>'. "\n" .'<div class="usp-preview"></div>'. "\n";
		
	} else {
		
		return esc_html__('File uploads not allowed. Please check your settings or contact the site administrator.', 'usp-pro');
		
	}
	
	return $fieldset_before . $content . $fieldset_after;
	
}
add_shortcode('usp_files', 'usp_input_files');
endif;

/*
	Shortcode: Remember
	Displays "remember me" button
	Syntax: [usp_remember class="aaa,bbb,ccc" label="Remember me"]
	Attributes:
		class       = comma-sep list of classes
		label       = text for input label (set checked/unchecked in USP Settings)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_remember')) : 
function usp_remember($args) {
	global $usp_general;
	if ($usp_general['sessions_default']) $checked = ' checked';
	else $checked = '';
	
	if (isset($_COOKIE['remember'])) $checked = ' checked';
	elseif (isset($_COOKIE['forget'])) $checked = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['class'])) $class = 'usp-remember,usp-input,usp-input-remember,' . $args['class'];
	else $class = 'usp-remember,usp-input,usp-input-remember';
	$classes = usp_classes($class, 'remember');
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	if (isset($args['label']) && !empty($args['label'])) $label_text = trim($args['label']);
	else $label_text = esc_html__('Remember me', 'usp-pro');
	$label = '<label for="usp-remember" class="usp-label usp-label-remember">'. $label_text .'</label>'. "\n";
	
	$content = '';
	$content .= '<input name="usp-remember" id="usp-remember" type="checkbox" data-required="true" class="'. $classes .'" value="1"'. $checked .' '. $custom .'/> '. $label;
	
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_remember', 'usp_remember');
endif;

/*
	Shortcode: Submit
	Displays submit button
	Syntax: [usp_submit class="aaa,bbb,ccc" value="Submit Post"]
	Attributes:
		class       = comma-sep list of classes
		value       = text to display on submit button
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_submit_button')) : 
function usp_submit_button($args) {
	if (isset($args['class'])) $class = 'usp-submit,' . $args['class'];
	else $class = 'usp-submit';
	$classes = usp_classes($class, 'submit');
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];

	if (isset($args['value']) && !empty($args['value'])) $value = trim($args['value']);
	else $value = esc_html__('Submit Post', 'usp-pro');

	return $fieldset_before . '<input type="submit" class="'. $classes .'" value="'. $value .'" '. $custom .'/>'. "\n" . $fieldset_after;
}
add_shortcode('usp_submit', 'usp_submit_button');
endif;

/*
	Shortcode: Email
	Displays email input field
	Syntax: [usp_email class="aaa,bbb,ccc" placeholder="Your Email" label="Your Email" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_email')) : 
function usp_input_email($args) {
	
	$current_user = wp_get_current_user();
	
	if ($current_user->user_email) $value = $current_user->user_email;
	elseif (isset($_SESSION['usp_form_session']['usp-email']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-email'];
	else $value = '';
	
	$value = apply_filters('usp_input_email_value', $value);
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	$field = 'usp_error_9';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-email,' . $args['class'];
	else $class = 'usp-input,usp-input-email';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999999');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-email" class="usp-label usp-label-email">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-email" id="usp-email" type="email" value="'. esc_attr($value) .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input type="hidden" name="usp-email-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_email', 'usp_input_email');
endif;

/*
	Shortcode: Email Subject
	Displays email subject input field
	Syntax: [usp_subject class="aaa,bbb,ccc" placeholder="Email Subject" label="Email Subject" required="yes" max="99"]
	Attributes:
		class       = comma-sep list of classes
		placeholder = text for input placeholder
		label       = text for input label
		required    = specifies if input is required (data-required attribute)
		max         = sets maximum number of allowed characters (maxlength attribute)
		custom      = any attributes or custom code
		fieldset    = enable auto-fieldset: true, false, or custom class name for fieldset (default true)
*/
if (!function_exists('usp_input_subject')) : 
function usp_input_subject($args) {
	if (isset($_SESSION['usp_form_session']['usp-subject']) && isset($_COOKIE['remember'])) $value = $_SESSION['usp_form_session']['usp-subject'];
	else $value = '';
	
	if (isset($args['custom'])) $custom = sanitize_text_field($args['custom']) .' ';
	else $custom = '';
	
	$field = 'usp_error_10';
	
	$placeholder = usp_placeholder($args, $field);
	$label = usp_label($args, $field);
	
	if (isset($args['class'])) $class = 'usp-input,usp-input-subject,'. $args['class'];
	else $class = 'usp-input,usp-input-subject';
	$classes = usp_classes($class, $field);
	
	if (isset($args['fieldset'])) $fieldset_custom = sanitize_text_field($args['fieldset']);
	else $fieldset_custom = '';
	
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after = $fieldset['fieldset_after'];
	
	$required = usp_required($args);
	if ($required == 'true') $parsley = 'required="required" ';
	else $parsley = '';
	$max = usp_max_att($args, '999999');

	if (empty($label)) $content = '';
	else $content  = '<label for="usp-subject" class="usp-label usp-label-subject">'. $label .'</label>'. "\n";
	$content .= '<input name="usp-subject" id="usp-subject" type="text" value="'. esc_attr($value) .'" data-required="'. $required .'" '. $parsley .'maxlength="'. $max .'" placeholder="'. $placeholder .'" class="'. $classes .'" '. $custom .'/>'. "\n";
	if ($required == 'true') $content .= '<input type="hidden" name="usp-subject-required" value="1" />'. "\n";
	return $fieldset_before . $content . $fieldset_after;
}
add_shortcode('usp_subject', 'usp_input_subject');
endif;

/*
	Shortcode: Reset form button
	Returns the markup for a reset-form button
	Syntax: [usp_reset_button class="aaa,bbb,ccc" value="Reset form" url="http://example.com/usp-pro/submit/"]
	Attributes:
		class  = comma-sep list of classes
		value  = text for input placeholder
		url    = full URL that the form is displayed on (not the form URL, unless you want to redirect there)
		custom = any attributes or custom code
*/
if (!function_exists('usp_reset_button')) : 
function usp_reset_button($args) {
	if (isset($args['class'])) $class = 'usp-reset-button,' . $args['class'];
	else $class = 'usp-reset-button';
	$classes = usp_classes($class, 'reset');
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	if (isset($args['value']) && !empty($args['value'])) $value = trim($args['value']);
	else $value = 'Reset form';
	
	if (isset($args['url']) && !empty($args['url'])) $url = trim($args['url']);
	else $url = '#please-check-shortcode';
	
	$href = get_option('permalink_structure') ? $url .'?usp_reset_form=true"' : $url .'&usp_reset_form=true';
	
	$content = '<div class="'. $classes .'"><a href="'. esc_url($href) .'"'. $custom .'>'. $value .'</a></div>'. "\n";
	return $content;
}
add_shortcode('usp_reset_button', 'usp_reset_button');
endif;

/*
	Shortcode: CC Message
	Returns the CC message
	Syntax: [usp_cc class="aaa,bbb,ccc" text=""]
	Attributes:
		class  = comma-sep list of classes
		text   = custom cc message (overrides default)
		custom = any attributes or custom code
*/
if (!function_exists('usp_cc')) : 
function usp_cc($args) {
	global $usp_admin;
	if (isset($args['class'])) $class = 'usp-contact-cc,' . $args['class'];
	else $class = 'usp-contact-cc';
	$classes = usp_classes($class, 'carbon');
	
	if (isset($args['custom'])) $custom = ' '. sanitize_text_field($args['custom']);
	else $custom = '';
	
	if (isset($usp_admin['contact_cc_note'])) $default = $usp_admin['contact_cc_note'];
	if (isset($args['text']) && !empty($args['text'])) $text = trim($args['text']);
	else $text = $default;
	
	$content = '<div class="'. $classes .'"'. $custom .'>'. $text .'</div>'. "\n";
	return $content;
}
add_shortcode('usp_cc', 'usp_cc');
endif;

/*
	Shortcode: Custom Redirect
	Redirects to specified URL on successful form submission
	Syntax: [usp_redirect url="http://example.com/" custom="class='example classes' data-role='custom'"]
	Attributes:
		url    = any complete/full URL
		custom = any custom attribute(s) using single quotes
*/
if (!function_exists('usp_redirect')) : 
function usp_redirect($args) {
	if (isset($args['custom']) && !empty($args['custom'])) $custom = ' '. stripslashes(trim($args['custom']));
	else $custom = '';

	if (isset($args['url']) && !empty($args['url'])) $url = esc_url(trim($args['url']));
	else $url = '';
	
	if (!empty($url)) return '<input type="hidden" name="usp-redirect" value="'. $url .'"'. $custom .' />'. "\n";
	else return '<!-- please check URL shortcode attribute -->'. "\n";
}
add_shortcode('usp_redirect', 'usp_redirect');
endif;

/*
	Shortcode: Agree to Terms Checkbox
	Returns HTML/CSS/JS for a required checkbox
	Syntax: [usp_agree label="" toggle="" terms="" custom="" style="" script="" alert="" class="" fieldset=""]
	Attributes:
		label    = (optional) label text for the required checkbox (default: I agree to the terms)
		toggle   = (optional) text used for the toggle-terms link (default: Show/hide terms)
		terms    = (optional) text used for the terms information (default: Put terms here.)
		custom   = (optional) custom attributes for the required checkbox (default: none)
		style    = (optional) custom CSS for the required checkbox (default: none)
		script   = (optional) custom JavaScript for the required checkbox (default: none)
		alert    = (optional) enable the JavaScript alert: enter text to enable or leave blank to disable (default: blank)
		class    = (optional) custom classes for the required checkbox (default: none)
		fieldset = (optional) enable auto-fieldset: true, false, or custom class name (default true)
*/
if (!function_exists('usp_required')) : 
function usp_agree($args) {
	
	$field = 'usp_error_18';
	
	$label = usp_label($args, $field);
	
	$value =(isset($_SESSION['usp_form_session']['usp-agree']) && isset($_COOKIE['remember'])) ? $_SESSION['usp_form_session']['usp-agree'] : '';
	
	$checked = ($value === 'on') ? 'checked' : '';
	
	$toggle = (isset($args['toggle']) && !empty($args['toggle'])) ? sanitize_text_field($args['toggle']) : esc_html__('Show/hide terms', 'usp-pro');
	$terms  = (isset($args['terms'])  && !empty($args['terms']))  ? sanitize_text_field($args['terms'])  : esc_html__('Put terms here.', 'usp-pro');
	$terms  = str_replace('{', '<', $terms);
	$terms  = str_replace('}', '>', $terms);
	
	$custom = isset($args['custom']) ? sanitize_text_field($args['custom']) : '';
	$style  = isset($args['style'])  ? sanitize_text_field($args['style'])  : '';
	$script = isset($args['script']) ? sanitize_text_field($args['script']) : '';
	$alert  = isset($args['alert'])  ? sanitize_text_field($args['alert'])  : '';
	
	$alert_script = (!empty($alert)) ? '$(".usp-submit").click(function(){ if (!$(".usp-input-agree").prop("checked")) { alert("'. $alert .'"); return false; } });' : '';
	
	$class = isset($args['class']) ? 'usp-agree,'. sanitize_text_field($args['class']) : 'usp-agree';
	$classes = usp_classes($class, $field);
	
	$fieldset_custom = isset($args['fieldset']) ? sanitize_text_field($args['fieldset']) : ''; 
	$fieldset = usp_fieldset($fieldset_custom);
	$fieldset_before = $fieldset['fieldset_before'];
	$fieldset_after  = $fieldset['fieldset_after'];
	
	$content  = (!empty($style)) ? '<style>'. $style .'</style>'. "\n" : '';
	$content .= '<script>jQuery(document).ready(function($){ $(".usp-agree-terms").hide();'. $alert_script . $script .' });</script>'. "\n";
	$content .= '<div class="'. $classes .'">'. "\n";
	$content .= '<input name="usp-agree" id="usp-agree" type="checkbox" required="required" data-required="true" class="usp-input usp-input-agree" '. $custom .' '. $checked .' /> ';
	$content .= '<label for="usp-agree" class="usp-label usp-label-agree">'. $label .'</label>'. "\n";
	$content .= '<input type="hidden" name="usp-agree-required" value="1" />'. "\n";
	$content .= '<div class="usp-agree-toggle">'. $toggle .'</div>'. "\n";
	$content .= '<div class="usp-agree-terms">'. $terms .'</div>'. "\n";
	$content .= '</div>'. "\n";
	
	return $fieldset_before . $content . $fieldset_after;
	
}
add_shortcode('usp_agree', 'usp_agree');
endif;

/*
	Shortcode: Custom Fields
	Displays custom input and textarea fields
	Syntax: [usp_custom_field form="x" id="y"]
	Template tag: usp_custom_field(array('form'=>'y', 'id'=>'x'));
	Attributes:
		id   = id of custom field (1-9)
		form = id of custom post type (usp_form)
	Notes:
		shortcode must be used within USP custom post type
		template tag may be used anywhere in the theme template
*/
if (!class_exists('USP_Custom_Fields')) {
	class USP_Custom_Fields {
		function __construct() { 
			add_shortcode('usp_custom_field', array(&$this, 'usp_custom_field')); 
		}
		function usp_custom_field($args) {
			global $usp_advanced;
			
			if (isset($args['id']) && !empty($args['id'])) $id = $args['id'];
			else return esc_html__('error:usp_custom_field:1:', 'usp-pro') . $args['id'];
			
			if (isset($args['form']) && !empty($args['form'])) $form = usp_get_form_id($args['form']);
			else return esc_html__('error:usp_custom_field:2:', 'usp-pro') . $args['form'];
			
			$custom_fields = get_post_custom($form);
			if (is_null($custom_fields) || empty($custom_fields)) return esc_html__('error:usp_custom_field:3:', 'usp-pro') . $custom_fields;
			
			$custom_merged = usp_merge_custom_fields();
			$custom_arrays = usp_custom_field_string_to_array();
			$custom_prefix = $usp_advanced['custom_prefix'];
			
			if (empty($custom_prefix)) $custom_prefix = 'prefix_';
			
			foreach ($custom_fields as $key => $value) {
				
				$key = trim($key);
				if ('_' == $key{0}) continue;
				if ($key !== '[usp_custom_field form="'. $args['form'] .'" id="'. $id .'"]') continue;
				
				if (preg_match("/usp_custom_field/i", $key)) {
					
					$atts = explode("|", $value[0]);
					
					$get_value    = $this->usp_custom_field_cookies($id, $value);
					$default_atts = $this->usp_custom_field_defaults($id, $get_value);
					$field_atts   = $this->usp_custom_field_atts($atts, $default_atts);
					
					if (empty($field_atts)) return esc_html__('error:usp_custom_field:4:', 'usp-pro') . $field_atts;
						
					$fieldset = usp_fieldset_custom($field_atts['fieldset'], $field_atts['field_class']);
					
					if ((in_array($field_atts['name'], $custom_merged)) || (stripos($field_atts['name'], $custom_prefix) !== false)) $prefix = '';
					else $prefix = 'usp-custom-';
					
					$checked = ''; $selected = ''; $class = ''; $class_label = ''; $label_custom = '';
					
					if (!empty($field_atts['checked']))      $checked      = ' checked="checked"';
					if (!empty($field_atts['selected']))     $selected     = ' selected="selected"';
					if (!empty($field_atts['class']))        $class        = $field_atts['class'] .' ';
					if (!empty($field_atts['label_class']))  $class_label  = $field_atts['label_class'] .' ';
					if (!empty($field_atts['label_custom'])) $label_custom = ' '. $field_atts['label_custom'];
					
					$multiple = ''; $select_array = ''; $multiple_enable = array('multiple','true','yes','on');
					
					if (in_array($field_atts['multiple'], $multiple_enable)) {
						$multiple = ' multiple="multiple"';
						$select_array = '[]';
					}
					
					if     (in_array($field_atts['name'], $custom_arrays['required'])) $field_atts['data-required'] = 'true';
					elseif (in_array($field_atts['name'], $custom_arrays['optional'])) $field_atts['data-required'] = 'false';
					
					$field_hidden = ''; $parsley = '';
					
					if ($field_atts['data-required'] == 'true') {
						if (!empty($field_atts['checkboxes']) && empty($multiple)) {
							unset($field_atts['data-required']);
						} else {
							if ($field_atts['field'] !== 'input_file') {
								$field_hidden = '<input type="hidden" name="'. $prefix . $field_atts['name'] .'-required" value="1" />'. "\n";
							}
						}
						$parsley = ' required="required"'; 
					} else {
						if ($field_atts['data-required'] == 'null') {
							unset($field_atts['data-required']);
						}
					}
					
					
					
					$label_for = !is_numeric($field_atts['for']) ? $prefix . $field_atts['for'] : $prefix . $field_atts['name'];
					
					$max_att   = (isset($field_atts['max']) && !empty($field_atts['max'])) ? ' max="'. $field_atts['max'] .'"' : '';
					
					$error      = $this->usp_custom_field_errors($id, $field_atts, $custom_prefix);
					$checkboxes = $this->usp_custom_fields_checkboxes($id, $field_atts, $prefix, $select_array);
					$radio      = $this->usp_custom_fields_radio($field_atts, $prefix);
					$options    = $this->usp_custom_fields_select($field_atts);
					$files      = $this->usp_custom_fields_files($field_atts, $prefix, $class_label, $label_custom, $error);
					
					
					
					//
					switch ($field_atts['field']) {
						case 'input':
							$field_start = '<input name="'. $prefix . $field_atts['name'] .'" id="'. $label_for .'"'. $max_att .' ';
							$field_end   = 'class="'. $error . $class .'usp-input usp-input-custom usp-form-'. $form .'"'. $checked . $selected . $parsley .' />';
							$label_class = 'class="'. $class_label .'usp-label usp-label-input usp-label-custom usp-form-'. $form;
						break;
						case 'textarea':
							
							$get_wp_editor = $this->usp_custom_field_wp_editor($field_atts, $error, $form, $prefix);
							
							if (!empty($get_wp_editor)) {
								
								$field_start = $get_wp_editor;
								$field_end   = '';
								
								unset($field_atts['type'], $field_atts['value'], $field_atts['rte_id'], $field_atts['placeholder'], $field_atts['data-required'], $field_atts['data-richtext']);
								
							} else {
									
								$field_start = '<textarea name="'. $prefix . $field_atts['name'] .'" id="'. $label_for .'"'. $max_att .' ';
								$field_end   = 'class="'. $error . $class .'usp-input usp-textarea usp-form-'. $form .'" rows="'. $field_atts['rows'] .'" cols="'. $field_atts['cols'] .'"'. $parsley .'>'. $field_atts['value'] .'</textarea>';
								
								unset($field_atts['type'], $field_atts['value']);
								
							}
							
							$label_class = 'class="'. $class_label .'usp-label usp-label-textarea usp-label-custom usp-form-'. $form;
							
						break;
						case 'select':
							$field_start = '<select name="'. $prefix . $field_atts['name'] . $select_array .'" id="'. $label_for .'" ';
							$field_end   = 'class="'. $error . $class .'usp-input usp-select usp-form-'. $form .'"'. $parsley . $multiple .'>'. $options .'</select>';
							$label_class = 'class="'. $class_label .'usp-label usp-label-select usp-label-custom usp-form-'. $form;
							unset($field_atts['type'], $field_atts['value'], $field_atts['multiple'], $field_atts['placeholder']);
						break;
						case 'input_checkbox':
							$field_start = '<div class="'. $error . $class .'usp-input usp-checkboxes usp-form-'. $form .'">';
							$field_end   = $checkboxes .'</div>';
							$label_class = '';
							unset($field_atts['type'], $field_atts['value'], $field_atts['multiple'], $field_atts['placeholder'], $field_atts['data-required']);
						break;
						case 'input_radio':
							$field_start = '<div class="'. $error . $class .'usp-input usp-radio usp-form-'. $form .'">';
							$field_end   = $radio .'</div>';
							$label_class = '';
							unset($field_atts['type'], $field_atts['value'], $field_atts['placeholder'], $field_atts['data-required']);
						break;
						case 'input_file':
							$field_start = '<div id="'. $prefix . $field_atts['name'] .'-files" class="'. $class .'usp-files">'. $files['start'];
							$field_end   = $files['end'] .'</div>'. "\n". '<div class="usp-preview"></div>';
							$label_class = '';
							unset($field_atts['type'], $field_atts['value']);
						break;
						default:
							return esc_html__('error:usp_custom_field:5:', 'usp-pro') . $field_atts['field'];
						break;
					}
					//
					
					
					
					if ($field_atts['field'] == 'input_checkbox' || $field_atts['field'] == 'input_radio' || $field_atts['field'] == 'input_file') $label = '';
					else $label = '<label for="'. $label_for .'" '. $label_class .'"'. $label_custom . '>'. $field_atts['label'] .'</label>' . "\n";
					
					if (isset($field_atts['label']) && $field_atts['label'] == 'null') $label = '';
					if (isset($field_atts['placeholder']) && $field_atts['placeholder'] == 'null') unset($field_atts['placeholder']);
					
					$field_atts = $this->usp_custom_field_unset($field_atts);
					
					$attributes = '';
					foreach ($field_atts as $att_name => $att_value) $attributes .= $att_name .'="'. $att_value .'" ';
					
					$content = $label . $field_start . $attributes . $field_end . "\n" . $field_hidden;
					
					$return = $fieldset['fieldset_before'] . $content . $fieldset['fieldset_after'];
					return apply_filters('usp_custom_field_data', $return);
				}
			}
		}
		function usp_custom_field_cookies($id, $value) {
			$get_value = '';
			if (isset($_COOKIE['remember'])) {
				preg_match("/name#([0-9a-z_-]+)/i",         $value[0], $name);
				preg_match("/checkboxes#([0-9a-z: _-]+)/i", $value[0], $checkbox);
				
				if (!empty($name[1])) {
					if (isset($_SESSION['usp_form_session']['usp-custom-'. $name[1]])) {
						$get_value = $_SESSION['usp_form_session']['usp-custom-'. $name[1]];
					
					} elseif (isset($_SESSION['usp_form_session'][$name[1]])) {
						$get_value = $_SESSION['usp_form_session'][$name[1]];
					}
				} else {
					if (isset($_SESSION['usp_form_session']['usp-custom-'. $id])) {
						$get_value = $_SESSION['usp_form_session']['usp-custom-'. $id];
					
					} elseif (!empty($checkbox[1])) {
						$get_value = array();
						$checkbox_array = explode(":", $checkbox[1]);
						
						foreach ($checkbox_array as $key => $value) {
							$value = trim($value);
							$checkbox_value = strtolower(trim(str_replace(' ', '_', $value)));
							
							if (isset($_SESSION['usp_form_session']['usp-custom-'. $checkbox_value])) {
								$get_value[] = $_SESSION['usp_form_session']['usp-custom-'. $checkbox_value];
							}
						}
					}
				}
			}
			return apply_filters('usp_custom_field_cookies', $get_value);
		}
		function usp_custom_field_defaults($id, $get_value) {
			global $usp_uploads;
			/*
				Notes:
					form fields:   input, textarea, select, input_checkbox, input_radio, input_file
					form atts:     autocomplete, novalidate, 
					
					input types:   text, checkbox, password, radio, file, url, search, email, tel, month, week, time, datetime, datetime-local, color, date, range, number
					
					input atts:    autocomplete, autofocus, form, formaction, formenctype, formmethod, formnovalidate, formtarget, height, width, list, min, max, multiple, 
					               pattern, placeholder, required, step, value, type, src, size, readonly, name, maxlength, disabled, checked, alt, align, accept
					
					textarea atts: autofocus, cols, disabled, form, name, placeholder, readonly, required, rows, wrap
					select atts:   autofocus, disabled, form, multiple, name, required, size
					option atts:   value, label, selected, disabled
					checkbox atts: name, disabled, form, type, checked, value, autofocus, required
					radio atts:    name, disabled, form, type, checked, value, required 
					
					mime types:    audio/*, video/*, image/*
					
				Infos:
					https://plugin-planet.com/usp-pro-shortcodes/#custom-fields
					http://iana.org/assignments/media-types
			*/
			$default_atts = array(
				// general atts
				'field'              => 'input',                           // type of field
				'type'               => 'text',                            // type of input, valid when field = input
				'name'               => $id,                               // field name, should equal for attribute
				'value'              => $get_value,                        // field value
				'data-required'      => 'true',                            // required + data-required atts
				'placeholder'        => esc_html__('Example Input ', 'usp-pro') . $id, // placeholder
				'class'              => '',                                // field class
				'checked'            => '',                                // checked attribute
				'multiple'           => '',                                // enable multiple selections
				
				// fieldset atts
				'field_class'        => '', // custom field class
				'fieldset'           => '', // use null to disable fieldset
				
				// label atts
				'label'              => esc_html__('Example Label ', 'usp-pro') . $id, // label text
				'for'                => $id, // for att should equal name att
				'label_class'        => '',  // custom label class
				'label_custom'       => '',  // custom attribute(s)
				
				// custom atts
				'custom_1'           => '', // custom_1#attribute:value
				'custom_2'           => '', // custom_2#attribute:value
				'custom_3'           => '', // custom_3#attribute:value
				'custom_4'           => '', // custom_4#attribute:value
				'custom_5'           => '', // custom_5#attribute:value
				
				// textarea atts
				'rows'               => '3',  // number of rows
				'cols'               => '30', // number of columns
				
				// select atts
				'options'            => '', // list of select options
				'option_select'      => '', // list of selected options
				'option_default'     => esc_html__('Please select', 'usp-pro'), // default option
				'selected'           => '', // general selected attribute
				
				// checkbox atts
				'checkboxes'         => '', // list of checkbox values
				'checkboxes_checked' => '', // list of selected checkboxes
				'checkboxes_req'     => '', // list of required checkboxes
				
				// radio atts
				'radio_inputs'       => '', // list of radio inputs
				'radio_checked'      => '', // the selected input
				
				// files atts
				'accept'             => '', // mime types
				'types'              => '', // accepted file types
				'method'             => '', // blank = "Add another", select = dropdown menu
				'link'               =>  esc_html__('Add another file', 'usp-pro'), // link text
				'files_min'          => $usp_uploads['min_files'],      // min number of files
				'files_max'          => $usp_uploads['max_files'],      // max number of files
				'preview_off'        => '',
				'max'                => '', // max length of files value
			);
			return apply_filters('usp_custom_field_atts_default', $default_atts);
		}
		function usp_custom_field_atts($atts, $default_atts) {
			foreach ($atts as $att) {
				$a = explode("#", $att); // eg: $a[0] = field, $a[1] = input
				if ($a[0] == 'atts' && $a[1] == 'defaults') continue; // use defaults
				if (isset($a[0])) $user_att_names[]  = $a[0];
				if (isset($a[1])) $user_att_values[] = $a[1];
			}
			if (!empty($user_att_names) && !empty($user_att_values)) $user_atts = array_combine($user_att_names, $user_att_values);
			else $user_atts = $default_atts;
			
			$field_atts = wp_parse_args($user_atts, $default_atts);
			
			if (isset($user_att_names)) unset($user_att_names);
			if (isset($user_att_values)) unset($user_att_values);
			
			$custom_att_names = array();
			$custom_att_values = array();
			
			foreach ($field_atts as $key => $value) {
				
				if (preg_match("/^custom_/i", $key)) {
					$b = explode(":", $value);
					if (isset($b[0])) $custom_att_names[]  = $b[0];
					if (isset($b[1])) $custom_att_values[] = $b[1];
					if (isset($field_atts[$key])) unset($field_atts[$key]);
				}
			}
			
			foreach ($custom_att_names as $key => $value) {
				if (is_null($value) || empty($value)) unset($custom_att_names[$key]);
			}
			foreach ($custom_att_values as $key => $value) {
				if (is_null($value) || empty($value)) unset($custom_att_values[$key]);
			}
			
			if (!empty($custom_att_names) && !empty($custom_att_values)) $custom_atts = array_combine($custom_att_names, $custom_att_values);
			else $custom_atts = array();
			
			$field_atts = wp_parse_args($custom_atts, $field_atts);
			if (isset($custom_att_names)) unset($custom_att_names);
			if (isset($custom_att_values)) unset($custom_att_values);
			
			return apply_filters('usp_custom_field_atts', $field_atts);
		}
		function usp_custom_field_wp_editor($field_atts, $error, $form, $prefix) {
			$get_wp_editor = '';
			
			$class = $error . $field_atts['class'] .'usp-input usp-textarea usp-form-'. $form;
			
			$rte_id = (isset($field_atts['rte_id'])) ? 'uspcustom'. $field_atts['rte_id'] : 'uspcustom';
			
			if (isset($field_atts['data-richtext']) && $field_atts['data-richtext'] == 'true') {
				$settings = array(
					'wpautop'          => true,                              // enable rich text editor
					'media_buttons'    => true,                              // enable add media button
					'textarea_name'    => $prefix . $field_atts['name'],     // name
					'textarea_rows'    => $field_atts['rows'],               // number of textarea rows
					'tabindex'         => '',                                // tabindex
					'editor_css'       => '',                                // extra CSS
					'editor_class'     => $class,                            // class
					'editor_height'    => '',                                // editor height
					'teeny'            => false,                             // output minimal editor config
					'dfw'              => false,                             // replace fullscreen with DFW
					'tinymce'          => true,                              // enable TinyMCE
					'quicktags'        => true,                              // enable quicktags
					'drag_drop_upload' => true,                              // drag-n-drop uploads
				);
				$settings = apply_filters('usp_custom_editor_settings', $settings);
				$value = apply_filters('usp_custom_editor_value', $field_atts['value']);
				ob_start(); // until get_wp_editor() is available..
				wp_editor($value, $rte_id, $settings);
				$get_wp_editor = ob_get_clean();
				return apply_filters('usp_custom_field_wp_editor', $get_wp_editor);
			}
		}
		function usp_custom_field_errors($id, $field_atts, $custom_prefix) {
			
			$error = '';
			
			$field = isset($field_atts['name']) ? $field_atts['name'] : 'undefined';
			
			wp_parse_str(wp_strip_all_tags($_SERVER['QUERY_STRING']), $vars);
			
			if ($vars) {
				
				foreach ($vars as $key => $value) {
					
					// CUSTOM PREFIX
					
					if (preg_match("/^usp_error_". preg_quote($custom_prefix) ."([0-9a-z_-]+)?$/i", $key, $match)) {
						
						if ($custom_prefix . $match[1] === $field) {
							
							$error = 'usp-error-field usp-error-custom-prefix ';
							
						}
						
					} elseif (preg_match("/^usp_error_8([a-z])?--". preg_quote($custom_prefix) ."([0-9a-z_-]+)--([0-9]+)$/i", $key, $match)) {
						
						if ($custom_prefix . $match[2] === $field) {
							
							$error = 'usp-error-field usp-error-custom-prefix usp-error-file ';
							
						}
						
					// CUSTOM CUSTOM
					
					} elseif (preg_match("/^usp_ccf_error_". preg_quote($field) ."$/i", $key)) {
						
						$error = 'usp-error-field usp-error-custom-custom ';
						
						
					} elseif (preg_match("/^usp_error_8([a-z])?--". preg_quote($field) ."--([0-9]+)$/i", $key)) {
						
						$error = 'usp-error-field usp-error-custom-custom usp-error-file ';
						
						
					// CUSTOM FIELDS
					
					} elseif (preg_match("/^usp_error_custom_([0-9a-z_-]+)$/i", $key, $match)) {
						
						if (($match[1] === $id) || ($match[1] === $field)) {
							
							$error = 'usp-error-field usp-error-custom ';
							
						}
						
					} elseif (preg_match("/^usp_error_8([a-z])?--usp_custom_file_([0-9]+)--([0-9]+)$/i", $key, $match)) {
						
						if (($match[2] === $id) || ($match[2] === $field)) {
							
							$error = 'usp-error-field usp-error-custom usp-error-file ';
							
						}
						
					// USER REGISTER
					
					} elseif (preg_match("/^usp_error_([a-g]+)$/i", $key, $match)) {
						 
						$user_fields = array('a'=>'nicename', 'b'=>'displayname', 'c'=>'nickname', 'd'=>'firstname', 'e'=>'lastname', 'f'=>'description', 'g'=>'password');
						
						foreach ($user_fields as $k => $v) {
							
							if ($v === $field && $k === $match[1]) {
								
								$error = 'usp-error-field usp-error-register ';
								
							}
							
						}
						
					// META & MISC.
					
					} elseif (preg_match("/^usp_error_(11|12|13|15|16|17)$/i", $key, $match)) {
						
						$user_fields = array('11'=>'alt', '12'=>'caption', '13'=>'desc', '15'=>'format', '16'=>'mediatitle', '17'=>'filename');
						
						foreach ($user_fields as $k => $v) {
							
							if ((strpos($field, $v) !== false) && (strpos($k, $match[1]) !== false)) {
								
								$error = 'usp-error-field usp-error-meta ';
								
							}
							
						}
						
					} 
					
				}
				
			}
			
			return apply_filters('usp_custom_field_errors', $error);
			
		}
		function usp_custom_field_unset($field_atts) {
			if (!empty($field_atts)) {
				unset(
					$field_atts['field'], 
					$field_atts['accept'], 
					$field_atts['name'], 
					$field_atts['checked'], 
					$field_atts['selected'], 
					$field_atts['class'], 
					$field_atts['label_class'], 
					$field_atts['rows'], 
					$field_atts['cols'],
					$field_atts['for'], 
					$field_atts['label_custom'], 
					$field_atts['label'], 
					$field_atts['field_class'],
					$field_atts['options'],
					$field_atts['option_default'], 
					$field_atts['option_select'],
					$field_atts['checkboxes'],
					$field_atts['checkboxes_checked'],
					$field_atts['checkboxes_req'],
					$field_atts['radio_inputs'],
					$field_atts['radio_checked'],
					$field_atts['fieldset'],
					$field_atts['types'],
					$field_atts['method'],
					$field_atts['link'],
					$field_atts['files_min'],
					$field_atts['files_max'],
					$field_atts['multiple'],
					$field_atts['preview_off'],
					$field_atts['max'],
					$field_atts['desc']
				);
			}
			return apply_filters('usp_custom_field_unset', $field_atts);
		}
		function usp_custom_fields_select($field_atts) {
			$options = '';
			if (!empty($field_atts['options']) && $field_atts['field'] == 'select') {
				$options_array = explode(":", $field_atts['options']);
				foreach ($options_array as $option) {
					$option = trim($option);
					$option_value = strtolower(trim(str_replace(' ', '_', $option)));
					$option_value = apply_filters('usp_custom_fields_select_value', $option_value);
					
					$selected = false;
					$option_selected = '';
					if (isset($field_atts['option_select']) && strtolower($option) == strtolower($field_atts['option_select'])) $selected = true;
					if (isset($field_atts['value'])) {
						$value = $field_atts['value'];
						if (is_array($value)) {
							foreach ($value as $att) {
								if ($att == $option_value) $selected = true;
							}
						} else {
							if ($value == $option_value) $selected = true;
						}
					}
					if ($selected) $option_selected = ' selected="selected"';
					if ($option == 'null' && isset($field_atts['option_default'])) {
						$option = $field_atts['option_default'];
						$option_value = '';
					}
					$options .= '<option value="'. $option_value .'"'. $option_selected .'>'. $option .'</option>' . "\n";
				}
				$options = "\n" . $options;
			}
			return apply_filters('usp_custom_fields_select', $options);
		}
		function usp_custom_fields_checkboxes($id, $field_atts, $prefix, $select_array) {
			
			$checkboxes = ''; 
			
			if ($field_atts['field'] !== 'input_checkbox') return $checkboxes;
			
			$checkboxes_array = !empty($field_atts['checkboxes'])         ? explode(":", $field_atts['checkboxes'])         : array();
			$required_single  = !empty($field_atts['checkboxes_req'])     ? explode(":", $field_atts['checkboxes_req'])     : array();
			$checked_array    = !empty($field_atts['checkboxes_checked']) ? explode(":", $field_atts['checkboxes_checked']) : array();
			
			$desc = (isset($field_atts['desc']) && !empty($field_atts['desc'])) ? "\n" .'<div class="usp-label">'. $field_atts['desc'] .'</div>' : '';
			
			$required = array();
			
			foreach ($checkboxes_array as $checkbox) {
				
				$checkbox_value = strtolower(trim(str_replace(' ', '_', trim($checkbox))));
				$checkbox_value = apply_filters('usp_custom_fields_checkbox_value', $checkbox_value);
				
				if (!empty($select_array)) {
					
					$name = (!empty($field_atts['name'])) ? esc_attr($field_atts['name']) : esc_html__('undefined', 'usp-pro');
					$suffix = '['. $checkbox_value .']';
					
				} else {
					
					$name = $checkbox_value;
					$suffix = '';
					
				}
				
				$check = false; 
				
				if (!empty($checked_array)) {
					
					$checked_array = array_map('strtolower', $checked_array);
					if (in_array(strtolower($checkbox), $checked_array)) $check = true;
					
				}
				
				$checked = ($check) ? ' checked="checked"' : '';
				
				$req_att = '';
				
				if (!empty($required_single)) {
					
					$required_single = array_map('strtolower', $required_single);
					
					if (in_array(strtolower($checkbox), $required_single)) {
						
						$required[] = '<input type="hidden" name="'. $prefix . $name .'-required" value="1" />' . "\n";
						$req_att = ' required="required"';
					}
				}
				
				$checkboxes .= '<label for="usp-checkbox-'. $checkbox_value .'-'. $id .'">';
				$checkboxes .= '<input name="'. $prefix . $name . $suffix .'" ';
				$checkboxes .= 'id="usp-checkbox-'. $checkbox_value .'-'. $id .'" type="checkbox" ';
				$checkboxes .= 'value="'. $checkbox_value .'"'. $req_att . $checked .' /> '. $checkbox;
				$checkboxes .= '</label>'. "\n";
				
			}
			
			$checkboxes = $desc . "\n" . $checkboxes;
			foreach ($required as $require) $checkboxes .= $require;
			
			return apply_filters('usp_custom_fields_checkbox', $checkboxes);
			
		}
		function usp_custom_fields_radio($field_atts, $prefix) {
			$radios = '';
			if ($field_atts['field'] == 'input_radio') {
				$checked = array();
				$radio_array = array();
				if (isset($field_atts['radio_checked']) && !empty($field_atts['radio_checked'])) $radio_checked = strtolower(trim(str_replace(' ', '_', $field_atts['radio_checked'])));
				if (isset($field_atts['radio_inputs'])  && !empty($field_atts['radio_inputs']))  $radio_array   = explode(":", $field_atts['radio_inputs']);
				
				$desc = (isset($field_atts['desc']) && !empty($field_atts['desc'])) ? "\n" .'<div class="usp-label">'. $field_atts['desc'] .'</div>' : '';
				
				foreach ($radio_array as $radio) {
					$radio = trim($radio);
					
					$radio_value = strtolower(trim(str_replace(' ', '_', $radio)));
					$radio_value = apply_filters('usp_custom_fields_radio_value', $radio_value);
					
					if (!empty($field_atts['name'])) $name = $field_atts['name'];
					else $name = esc_html__('undefined', 'usp-pro');
					
					$checked = '';
					if (!empty($field_atts['value'])) {
						if ($radio_value == strtolower($field_atts['value'])) $checked = ' checked="checked"';
						
					} elseif (!empty($radio_checked)) {
						if ($radio_value == $radio_checked) $checked = ' checked="checked"';
					}
					$radios .= '<label for="usp-radio-'. $radio_value .'">';
					$radios .= '<input name="'. $prefix . $name .'" id="usp-radio-'. $radio_value .'" type="radio" value="'. $radio_value .'"'. $checked .' /> '. $radio;
					$radios .= '</label>' . "\n";
				}
				$radios = $desc . "\n" . $radios;
			}
			return apply_filters('usp_custom_fields_radio', $radios);
		}
		function usp_custom_fields_files($field_atts, $prefix, $class_label, $label_custom, $error) {
			$files = array();
			if ($field_atts['field'] == 'input_file') {
				
				$name     = $field_atts['name'];
				$link     = $field_atts['link'];
				$label    = $field_atts['label'];
				$method   = $field_atts['method'];
				$multiple = $field_atts['multiple'];
				
				if ($prefix == 'usp-custom-') $prefix = 'usp_custom_file_';
				
				if (!empty($field_atts['class'])) $class = $field_atts['class'] .' ';
				else $class = '';
				
				if (!empty($field_atts['max'])) $max = ' maxlength="'. $field_atts['max'] .'"';
				else $max = '';
				
				if (!empty($field_atts['accept'])) $accept = ' accept="'. $field_atts['accept'] .'"';
				else $accept = '';
				
				if (!empty($field_atts['files_max'])) $files_max = "\n" .'<input type="hidden" name="'. $prefix . $name .'-limit" class="usp-file-limit" value="'. $field_atts['files_max'] .'" />';
				else $files_max = '';
				
				if (!empty($field_atts['types'])) $files_type = "\n" .'<input type="hidden" name="'. $prefix . $name .'-types" value="'. $field_atts['types'] .'" />';
				else $files_type = '';
				
				if (!empty($field_atts['preview_off'])) $preview = "\n" .'<input type="hidden" name="'. $prefix . $name .'-preview" value="1" />';
				else $preview = '';
				
				$for = !is_numeric($field_atts['for']) ? $prefix . $field_atts['for'] : $prefix . $field_atts['name'];
				
				$files_min = '';
				if ($field_atts['data-required'] == 'true') {
					if (empty($field_atts['files_min']) && intval($field_atts['files_min']) < 1) $files_min = '1';
					else $files_min = $field_atts['files_min'];
					$files_min = "\n" .'<input type="hidden" name="'. $prefix . $name .'-required" value="'. $files_min .'" />';
				}
				
				$files_count = "\n" .'<input type="hidden" name="'. $prefix . $name .'-count" class="usp-file-count" value="1" />';
				
				if (empty($method)) {
					$input_id = '';
					$data_file = ' data-file="1"';
					$class_method = ' add-another';
					$add_another = "\n" .'<div class="usp-add-another"><a href="#">'. $link .'</a></div>';
					$is_multiple = false;
				} else {
					$input_id = ' id="'. $prefix . $name .'-multiple-files"';
					$data_file = '';
					$class_method = ' select-file';
					$add_another = '';
					$is_multiple = true;
				}
				
				$multiple_enable = array('multiple', 'true', 'yes', 'on');
				if (empty($multiple) || in_array($multiple, $multiple_enable)) {
					
					$class_label = ' class="'. $class_label    .'usp-label usp-label-files usp-label-custom"';
					$class_input = ' class="'. $class . $error .'usp-input usp-input-files usp-input-custom'. $class_method .' multiple"';
					
					$input_wrap_open = "\n" .'<div class="usp-input-wrap">';
					$input_wrap_close = "\n" .'</div>';
					$select = '[]';
					
					if ($is_multiple) $multiple_att = ' multiple="multiple"';
					else              $multiple_att = '';
				} else {
					$class_label = ' class="'. $class_label    .'usp-label usp-label-files usp-label-custom usp-label-file usp-label-file-single"';
					$class_input = ' class="'. $class . $error .'usp-input usp-input-files usp-input-custom usp-input-file usp-input-file-single'. $class_method .' single-file"';
					
					$input_wrap_open = '';
					$input_wrap_close = '';
					$select = '';
					
					$multiple_att = '';
					if (!$is_multiple) {
						$add_another = '';
						$data_file = '';
					}
					$input_id = '';
					$files_max = '';
					$files_count = '';	
				}
				
				$files['start']  = "\n" .'<label for="'. $for .'"'. $class_label . $label_custom .'>'. $label .'</label>';
				$files['start'] .= $input_wrap_open . "\n" .'<input name="'. $prefix . $name . $select .'" id="'. $prefix . $name .'" ';
				$files['start'] .= 'type="file"'. $class_input . $input_id . $multiple_att . $accept . $max . $data_file .' ';
				
				$files['end'] = '/>'. $add_another . $input_wrap_close . $files_max . $files_count . $files_type . $files_min . $preview . "\n";
				
				$files['id'] = $prefix . $name;
			}
			return apply_filters('usp_custom_fields_files', $files);
		}
	}
}


/*
	Template Tag: Custom Fields
	Displays custom input and textarea fields
	Syntax: [usp_custom_field id=x]
	Template tag: usp_custom_field(array('id'=>'x', 'form'=>'y'));
	Attributes:
		id   = id of custom field (1-9)
		form = id of custom post type (usp_form)
	Notes:
		shortcode must be used within USP custom post type
		template tag may be used anywhere in the theme template
*/
if (!function_exists('usp_custom_field')) : 
function usp_custom_field($args) {
	$USP_Custom_Fields = new USP_Custom_Fields();
	echo $USP_Custom_Fields->usp_custom_field($args);
}
endif;


/*
	Shortcode: USP Form
		Displays the specified USP Form by id attribute
		Syntax: [usp_form id="1" class="aaa,bbb,ccc"]
		Attributes:
			id    = id of form (post id or slug)
			class = classes comma-sep list (displayed as class="aaa bbb ccc") 
*/
if (!function_exists('usp_form')) : 
function usp_form($args) {
	
	global $usp_advanced;
	
	if (isset($args['id']) && !empty($args['id'])) {
		$id = usp_get_form_id($args['id']);
	} else {
		return esc_html__('error:usp_form:1:', 'usp-pro') . $args['id'];
	}
	
	$title = '';
	$widget_before = '';
	$widget_after = '';
	
	if (isset($args['widget']) && $args['widget']) {
		
		if (isset($args['title'])) $title = '<h2 class="widget-title">'. sanitize_text_field($args['title']) .'</h2>';
		
		$widget_before = '<section id="usp-pro-widget-'. $id .'" class="widget widget_usp_pro">';
		$widget_after  = '</section>';
		
	}
	
	$class = (isset($args['class']) && !empty($args['class'])) ? 'usp-pro,usp-form-'. $id .','. $args['class'] : 'usp-pro,usp-form-'. $id;
	$classes = usp_classes($class, 'form');
	
	$content   = get_post($id, ARRAY_A);
	$args      = array('classes' => $classes, 'id' => $id);
	$success   = isset($_GET['usp_success']) ? true : false;
	$form_wrap = usp_form_wrap($args, $success);
	
	if (get_post_type() !== 'usp_form') {
		
		if ($success && !$usp_advanced['success_form']) {
			return $widget_before . $title . $form_wrap['form_before'] . $form_wrap['form_after'] . $widget_after;
		} else {
			return $widget_before . $title . $form_wrap['form_before'] . do_shortcode($content['post_content']) . $form_wrap['form_after'] . $widget_after;
		}
		
	} else {
		
		return;
		
	}
}
add_shortcode('usp_form', 'usp_form');
endif;


