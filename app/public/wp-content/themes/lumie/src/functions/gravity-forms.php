<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * Safely get and validate encrypted entry ID from $_GET parameter
 *
 * @return int|false Entry ID if valid, false otherwise
 */
function get_validated_entry_id() {
	if (!isset($_GET['entry']) || empty($_GET['entry'])) {
		return false;
	}

	// Verify nonce if present (for forms with nonce protection)
	if (isset($_GET['_wpnonce']) && !wp_verify_nonce($_GET['_wpnonce'], 'gf_entry_' . sanitize_text_field($_GET['entry']))) {
		return false;
	}

	$encrypted_entry = sanitize_text_field($_GET['entry']);
	$entry_id = encrypt_decrypt('decrypt', $encrypted_entry);

	// Validate that entry_id is numeric and exists
	if (!is_numeric($entry_id)) {
		return false;
	}

	return (int) $entry_id;
}

/* Gravity Forms anker */
add_filter('gform_confirmation_anchor', '__return_true');

// Slaat GF inzendingen in op zonder returns
// https://docs.gravityforms.com/gform_export_field_value/ --> 7
add_filter('gform_export_field_value', 'decode_export_values');
function decode_export_values($value) {
	$value = str_replace(["\n", "\t", "\r"], ' ', $value);
	return $value;
}

/**
 * Filters the next, previous and submit buttons.
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param string $button Contains the <input> tag to be filtered.
 * @param object $form Contains all the properties of the current form.
 *
 * @return string The filtered button.
 */
add_filter('gform_next_button', 'input_to_button', 10, 2);
add_filter('gform_previous_button', 'input_to_button', 10, 2);
add_filter('gform_submit_button', 'input_to_button', 10, 2);
function input_to_button($button, $form) {
	$dom = new DOMDocument();
	$dom->loadHTML($button);
	$input = $dom->getElementsByTagName('input')->item(0);
	if (!$input) {
		return $button;
	}
	$new_button = $dom->createElement('button');
	$new_button->appendChild($dom->createTextNode($input->getAttribute('value')));
	$input->removeAttribute('value');
	foreach ($input->attributes as $attribute) {
		$new_button->setAttribute($attribute->name, $attribute->value);
	}
	$input->parentNode->replaceChild($new_button, $input);

	return $dom->saveHtml($new_button);
}

// Translate default form confirmation
function gform_spam_notification_translation($translated_text, $text, $domain) {
	$translated_text = match ($translated_text) {
		'Thanks for contacting us! We will get in touch with you shortly.' => __('Er lijkt iets mis te gaan met de inzending... Probeer het nog eens of neem contact op per telefoon.', 'woocommerce'),
		'Bedankt voor je bericht! We zullen binnenkort contact met je opnemen.' => __('Er lijkt iets mis te gaan met de inzending... Probeer het nog eens of neem contact op per telefoon.', 'woocommerce'),
		default => $translated_text,
	};
	return $translated_text;
}
add_filter('gettext', 'gform_spam_notification_translation', 20, 3);

// Disable GF theme styling
add_filter('gform_disable_css', '__return_true');

// Shortcode to get a Gravity Forms field value from the entry parameter
function get_entry_value($atts) {
	$a = shortcode_atts([
		'field_id' => 0,
	], $atts);

	$entry_id = get_validated_entry_id();

	if (empty($entry_id) || !function_exists('gravity_form') || empty($a))
		return;

	$entry = GFAPI::get_entry($entry_id);

	if (is_wp_error($entry)) {
		return '';
	}

	return $entry[$a['field_id']] ?? '';
}
add_shortcode('get_entry_value', 'get_entry_value');

add_filter('gform_confirmation', 'add_encrypted_entry_id_to_confirmation_url', 10, 4);
function add_encrypted_entry_id_to_confirmation_url($confirmation, $form, $entry, $is_ajax) {
	if (is_array($confirmation) && isset($confirmation['redirect'])) {
		$entry_id_encrypted = encrypt_decrypt('encrypt', $entry['id']);
		$confirmation['redirect'] = add_query_arg('entry', $entry_id_encrypted, $confirmation['redirect']);
	}
	return $confirmation;
}

add_action('wp_footer', 'enqueue_datalayer_script_on_redirect');
function enqueue_datalayer_script_on_redirect() {
	$entry_id = get_validated_entry_id();

	if (!$entry_id) return;

	if (!class_exists('GFAPI')) return;

	$entry = GFAPI::get_entry($entry_id);

	if (is_wp_error($entry)) return;

	$form = GFAPI::get_form($entry['form_id']);
	$email_fields = [];

	foreach ($form['fields'] as $field) {
		if ($field->type !== 'email') continue;

		$field_id = $field->id;
		$label = $field->label;
		$value = rgar($entry, $field_id);

		$email_fields[] = [
			'id' => $field_id,
			'label' => $label,
			'value' => $value
		];
	}

	if (empty($email_fields)) return;

	$datalayer = [
		'event' => 'formSubmission',
		'formId' => $form['id'],
		'formName' => $form['title'],
		'entryIdEncrypted' => encrypt_decrypt('encrypt', $entry_id),
		'emailFields' => $email_fields
	];

	$datalayer_json = json_encode($datalayer);
	$storage_key = 'formSubmission_' . $entry_id;

	echo "
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				if (!localStorage.getItem('$storage_key')) {
					window.dataLayer = window.dataLayer || [];
					var dataLayerData = $datalayer_json;
					window.dataLayer.push(dataLayerData);
					localStorage.setItem('$storage_key', 'true');
				}
			});
		</script>
	";
}


add_filter('gform_custom_merge_tags', 'custom_merge_tags', 10, 4);
function custom_merge_tags($merge_tags, $form_id, $fields, $element_id) {
	$merge_tags[] = [
		'label' => __('Formulier velden ({form_fields exclude="1,3,4"})', 'mbeffect'),
		'tag' => '{form_fields}',
	];

	$merge_tags[] = [
		'label' => __('Reply mail heading', 'mbeffect'),
		'tag' => '{reply_heading}',
	];

	$merge_tags[] = [
		'label' => __('Reply mail footer', 'mbeffect'),
		'tag' => '{reply_footer}',
	];

	return $merge_tags;
}

add_filter('gform_replace_merge_tags', 'replace_form_fields_merge_tag', 10, 7);
function replace_form_fields_merge_tag($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
	// Check if the merge tag {form_fields} is present in the text
	// and extract the exclude parameter if it exists (e.g., {form_fields exclude="1,2,3"}).
	preg_match('/\{form_fields(?:\s+exclude="([^"]*)")?\}/', $text, $matches);
	$merge_tag = $matches[0] ?? '{form_fields}';
	$excluded_ids = [];

	if (!empty($matches[1])) {
		$excluded_ids = array_map('intval', array_map('trim', explode(',', $matches[1])));
	}

	if ((strpos($text, $merge_tag) === false && strpos($text, '{reply_heading}') === false && strpos($text, '{reply_footer}') === false) || empty($entry) || empty($form)) {
		return $text;
	}

	// Define styles for the table and table cells
	$td_style = 'padding:10px 25px;word-break:break-word;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#000000';
	$td_style_bold = $td_style . ';font-weight:bold';

	$form_fields = '';
	foreach ($form['fields'] as $field) {
		$field_id = $field['id'];

		if (in_array($field_id, $excluded_ids)) {
			continue;
		}

		$field_value = rgar($entry, $field_id);

		if (is_array($field_value)) {
			$field_value = implode(', ', $field_value);
		}

		if (empty($field_value) && empty($field['choices']) && empty($field['inputs'])) continue;

		$form_fields .= '<tr><td align="left" style="' . $td_style_bold . '">' . esc_html($field['label']) . '</td></tr>';

		// Regular field value handling
		if (!empty($field_value)) {
			// Handle for file uploads
			if ($field['type'] === 'fileupload') {
				$field_value = '<a href="' . esc_url($field_value) . '" target="_blank">' . esc_html(basename($field_value)) . '</a>';
			} else {
				$field_value = esc_html($field_value);
			}
			$form_fields .= '<tr><td align="left" style="' . $td_style . '">' . $field_value . '</td></tr>';
		}
		// Special handling for checkboxes, multi-choice, and selected choices
		elseif (($field['type'] === 'checkbox' || $field['type'] === 'multi_choice') && !empty($field['choices'])) {
			$checked = $field->get_value_export($entry);
			$field_values = explode(', ', $checked);

			if (!empty($field_values)) {
				$form_fields .= '<tr><td align="left" style="' . $td_style . '">' . esc_html(implode(', ', $field_values)) . '</td></tr>';
			}
		}
		// Special handling for radio buttons
		elseif (!empty($field['choices']) && !empty($field['choices']['isSelected'])) {
			$form_fields .= '<tr><td align="left" style="' . $td_style . '">' . esc_html($field['choices']['text']) . '</td></tr>';
		}
		// Special handling for inputs (like name and address fields)
		elseif (!empty($field['inputs'])) {
			$input_values = [];
			foreach ($field['inputs'] as $input) {
				$input_value = rgar($entry, $input['id']);
				if (!empty($input_value)) {
					$input_values[] = esc_html($input_value);
				}
			}
			if (!empty($input_values)) {
				$form_fields .= '<tr><td align="left" style="' . $td_style . '">' . esc_html(implode(', ', $input_values)) . '</td></tr>';
			}
		}
	}

	// Add support for {reply_heading} and {reply_footer}
	$logo = get_field('logo', 'options') ?? '';
	$company_name = get_bloginfo('name');
	$contact_details_cf = get_field('contact_details', 'options') ?? [];

	$reply_heading_html = '
	<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

		<head>
		<title>Bevestiging van uw bericht</title>
		<!--[if !mso]><!-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!--<![endif]-->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style type="text/css">
			#outlook a {
			padding: 0;
			}

			body {
			margin: 0;
			padding: 0;
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
			}

			table,
			td {
			border-collapse: collapse;
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
			}

			img {
			border: 0;
			height: auto;
			line-height: 100%;
			outline: none;
			text-decoration: none;
			-ms-interpolation-mode: bicubic;
			}

			p {
			display: block;
			margin: 13px 0;
			}
		</style>
		<!--[if mso]>
				<noscript>
				<xml>
				<o:OfficeDocumentSettings>
				<o:AllowPNG/>
				<o:PixelsPerInch>96</o:PixelsPerInch>
				</o:OfficeDocumentSettings>
				</xml>
				</noscript>
				<![endif]-->
		<!--[if lte mso 11]>
				<style type="text/css">
				.mj-outlook-group-fix { width:100% !important; }
				</style>
				<![endif]-->
		<!--[if !mso]><!-->
		<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
		<style type="text/css">
			@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
		</style>
		<!--<![endif]-->
		<style type="text/css">
			@media only screen and (min-width:480px) {
			.mj-column-per-100 {
				width: 100% !important;
				max-width: 100%;
			}
			}
		</style>
		<style media="screen and (min-width:480px)">
			.moz-text-html .mj-column-per-100 {
			width: 100% !important;
			max-width: 100%;
			}
		</style>
		<style type="text/css">
			@media only screen and (max-width:480px) {
			table.mj-full-width-mobile {
				width: 100% !important;
			}

			td.mj-full-width-mobile {
				width: auto !important;
			}
			}
		</style>
		</head>

		<body style="word-spacing:normal;">
			<!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
			<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;margin:0px auto;max-width:600px;">
				<tbody>
				<tr>
					<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
					<!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
						<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
						<tbody>
							<tr>
							<td align="left" style="padding:10px 25px;word-break:break-word;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#000000;">
							';

	$reply_footer_html = '';

	if (!empty($contact_details_cf['phone']) && !empty($contact_details_cf['phone_link'])) {

		$reply_footer_html = '
							<tr>
								<td align="left" style="padding:10px 25px;padding-top:20px;word-break:break-word;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:15px;line-height:1;text-align:left;color:#000000">
									Wilt u liever direct contact? Bel ons gerust via <b><a href="' . esc_url($contact_details_cf['phone_link']['url']) . '" title="' . esc_attr($contact_details_cf['phone_link']['title']) . '">' . esc_html($contact_details_cf['phone']) . '</a></b>.
								</td>
							</tr>';
	}

	$reply_footer_html .= '
							<tr>
							<td align="left" style="padding:10px 25px;padding-top:10px;word-break:break-word;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:15px;line-height:1;text-align:left;color:#000000">
							Met vriendelijke groet,<br/>';

	if (!empty($company_name)) {
		$reply_footer_html .= esc_html($company_name);
	}


	$reply_footer_html .= '</td>
							</tr>';

	if (!empty($logo)) {

		$logo_path = get_attached_file($logo);
		$logo_base64 = '';

		if (file_exists($logo_path)) {
			$logo_mime = mime_content_type($logo_path);

			if ($logo_mime === 'image/svg+xml') {
				$logo_data = file_get_contents($logo_path);
				$logo_base64 = 'data:' . $logo_mime . ';base64,' . base64_encode($logo_data);
			} else {
				$logo_base64 = wp_get_attachment_url($logo);
			}
		}

		$reply_footer_html .= '
							<tr>
							<td align="center" style="font-size:0px;padding:10px 25px;padding-top:10px;word-break:break-word;">
								<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
								<tbody>
									<tr>
									<td style="width:100px;">
										<img alt="Logo" height="auto" src="' . wp_kses_post($logo_base64) . '" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="100" />
									</td>
									</tr>';
	}
	$reply_footer_html .= '
								</tbody>
								</table>
							</td>
							</tr>
						</tbody>
						</table>
					<!--[if mso | IE]></td></tr></table><![endif]-->
					</td>
				</tr>
				</tbody>
			</table>
			<!--[if mso | IE]></td></tr></table><![endif]-->
		</body>

</html>';

	$text = str_replace('{reply_heading}', $reply_heading_html, $text);
	$text = str_replace('{reply_footer}', $reply_footer_html, $text);

	return str_replace($merge_tag, $form_fields, $text);
}
