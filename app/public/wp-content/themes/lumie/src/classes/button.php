<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

class BlockButton {
	public $text;
	public $type;
	public $link;
	public $link_title;
	public $popup;
	public $button;

	/**
	 * @param string $text	The button text
	 */
	function __construct($text) {
		$this->text = $text;
		$this->type = '';
	}

	/**
	 * @return string
	 */
	function get_button() {
		return $this->button;
	}

	/**
	 * @param string $type	Can set the button to a different type
	 */
	function set_type($type) {
		$this->type = $type;
	}

	/**
	 * @param string $display Set the display class based on the field value
	 */

	function set_display($display) {
		$this->type .= match ($display) {
			'phone' => ' btn-display btn-display--phone',
			'desktop' => ' btn-display btn-display--desktop',
			default => '',
		};
	}

	/**
	 * @param string $link			The href attribute
	 * @param string $link_title	The title attribute
	 * @param string $link_target	The target attribute
	 */
	function set_link($link, $link_title, $link_target) {
		$this->link = $link;
		$this->link_title = $link_title;
		$target = $link_target == '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '';

		if (empty($this->link)) {
			$this->button = sprintf(
				'<button class="%s">%s</button>',
				esc_attr($this->type),
				wp_kses_post($this->text)
			);
		} else {
			$this->button = sprintf(
				'<a href="%s" title="%s" class="%s"%s>%s</a>',
				esc_url($this->link),
				esc_attr($this->link_title),
				esc_attr($this->type),
				$target,
				wp_kses_post($this->text)
			);
		}
	}

	/**
	 * @param string $popup	The name of the popup which should open on click
	 */
	function set_popup($popup) {
		add_global_popup($popup);
		$this->popup = $popup;

		$this->button = sprintf(
			'<div class="%s show-popup" data-popup="%s" role="button" tabindex="0" aria-haspopup="true" aria-label="%s">%s</div>',
			esc_attr($this->type),
			esc_attr($this->popup),
			sprintf(esc_attr__('Open %s', 'mbeffect'), esc_attr($this->popup)),
			wp_kses_post($this->text)
		);
	}
}

class BlockButtons {
	public $content = '';

	/**
	 * @param array $acf The buttons as provided by ACF
	 */
	function __construct(array $acf) {
		foreach ($acf as $value) {
			switch ($value['acf_fc_layout']) {
				case 'primary':
					$icon_before = '';
					$icon_after = '';

					if (!empty($value['singular_button']['icon_before']) && $value['singular_button']['icon_before'] != 'none') {
						$icon_before = $value['singular_button']['icon_before'];
					}
					if (!empty($value['singular_button']['icon_after']) && $value['singular_button']['icon_after'] != 'none') {
						$icon_after = $value['singular_button']['icon_after'];
					}

					$button = new BlockButton($icon_before . $value['singular_button']['button_text'] . $icon_after);
					$button->set_type('btn btn--primary');
					$button->set_display($value['singular_button']['display']);

					switch ($value['singular_button']['button_type']) {
						case 'link':
							$button->set_link(
								$value['singular_button']['button_link']['url'] ?? '',
								$value['singular_button']['button_link']['title'] ?? '',
								$value['singular_button']['button_link']['target'] ?? '_self',
							);
							break;

						case 'popup':
							$button->set_popup($value['singular_button']['button_popup']);
							break;
					}

					$this->content .= $button->get_button();
					break;

				case 'secondary':
					$icon_before = '';
					$icon_after = '';

					if (!empty($value['singular_button']['icon_before']) && $value['singular_button']['icon_before'] != 'none') {
						$icon_before = $value['singular_button']['icon_before'];
					}
					if (!empty($value['singular_button']['icon_after']) && $value['singular_button']['icon_after'] != 'none') {
						$icon_after = $value['singular_button']['icon_after'];
					}

					$button = new BlockButton($icon_before . $value['singular_button']['button_text'] . $icon_after);
					$button->set_type('btn btn--secondary');
					$button->set_display($value['singular_button']['display']);

					switch ($value['singular_button']['button_type']) {
						case 'link':
							$button->set_link(
								$value['singular_button']['button_link']['url'] ?? '',
								$value['singular_button']['button_link']['title'] ?? '',
								$value['singular_button']['button_link']['target'] ?? '_self',
							);
							break;

						case 'popup':
							$button->set_popup($value['singular_button']['button_popup']);
							break;
					}

					$this->content .= $button->get_button();
					break;


				case 'phone':
					$contact_details = get_field('contact_details', 'options');

					if (empty($contact_details['phone']) || empty($contact_details['phone_link']['url'])) break;

					$phone_link = sprintf(
						'<a href="%s" title="%s">%s</a>',
						esc_url($contact_details['phone_link']['url']),
						esc_attr($value['title_attr'] ?? ''),
						esc_html($contact_details['phone'])
					);

					$this->content .= '<div class="phone">';
					$this->content .= wp_kses_post(sprintf(__('of bel %s', 'mbeffect'), $phone_link));
					$this->content .= '</div>';
					break;
			}
		}
	}

	/**
	 * @return string
	 */
	function get_buttons() {
		return '<div class="buttons">' . $this->content . '</div>';
	}
}
