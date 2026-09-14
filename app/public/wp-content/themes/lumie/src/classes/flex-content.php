<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

class FlexContent {
	public $content;

	/**
	 * @return string
	 */
	function getContent() {
		return $this->content;
	}

	/**
	 * @param string $html	The HTML
	 */
	function setContent($html) {
		$this->content .= modify_video_attributes($html);
	}

	/**
	 * @param string $html	The HTML
	 */
	function setFoldContent($html) {
		$this->content = '<div class="fold-content">';
		$this->content .= modify_video_attributes($html);
		$this->content .= '</div>';
		$this->content .= '<div class="fold-content-trigger">' . esc_html__('Lees meer', 'mbeffect') . '</div>';
	}

	/**
	 * @param string $quote The quote HTML
	 * @param string $author (optional) The name of the person being quoted
	 */
	function setQuote($quote, $author = '') {
		if (empty($quote)) return;

		$this->content .= '<blockquote class="content-quote">';
		$this->content .= '<div class="content-quote__text">' . wp_kses_post($quote) . '</div>';

		if (!empty($author)) {
			$this->content .= '<cite class="content-quote__author">&ndash; ' . esc_html($author) . '</cite>';
		}

		$this->content .= '</blockquote>';
	}

	/**
	 * @param int	$image		The image id
	 * @param array $thumbnail	(optional) The thumbnail for the image
	 */
	function setImage($image, $thumbnail = 'full') {
		$this->content = '
			<div class="image">
				' . wp_get_attachment_image($image, $thumbnail, false, ['loading' => 'lazy']) . '
			</div>
		';
	}

	/**
	 * @param array	$logos		The logos
	 * @param bool	$is_swiper	(optional) If the logos should be in a swiper
	 */
	function setLogos($logos, $is_swiper = false) {
		ob_start();

		component('logo-wrapper', [
			'logos' => $logos,
			'swiper' => $is_swiper,
		]);

		$this->content .= ob_get_clean();
	}

	/**
	 * @param array	$accordions		The accordions
	 */
	function setAccordions($accordions) {
		if (empty($accordions)) return;

		$this->content = '<div class="accordions">';

		// Only one accordion can be open by default, matching the single-expand behaviour of the frontend script
		$has_open_accordion = false;

		foreach ($accordions as $index => $accordion) {
			if (empty($accordion['question']) || empty($accordion['answer'])) continue;

			// Generate unique IDs using uniqid() to prevent conflicts across multiple accordion blocks
			$unique_id = uniqid('accordion-');
			$question_id = $unique_id . '-question';
			$answer_id = $unique_id . '-answer';

			$is_open = !$has_open_accordion && !empty($accordion['open_by_default']);
			if ($is_open) $has_open_accordion = true;

			$this->content .= '<div class="accordion' . ($is_open ? ' accordion--active' : '') . '">';

			$this->content .= '<div class="accordion__question" role="button" tabindex="0" aria-expanded="' . ($is_open ? 'true' : 'false') . '" aria-controls="' . esc_attr($answer_id) . '" id="' . esc_attr($question_id) . '">';
			$this->content .= wp_kses_post($accordion['question']);
			$this->content .= '</div>';

			$this->content .= '<div class="accordion__answer"' . ($is_open ? ' style="display: block;"' : '') . ' id="' . esc_attr($answer_id) . '" role="region" aria-labelledby="' . esc_attr($question_id) . '">';
			$this->content .= wp_kses_post($accordion['answer']);
			$this->content .= '</div>';

			$this->content .= '</div>';
		}

		$this->content .= '</div>';
	}

	/**
	 * Renders a label/value list (opening hours, specs) as a definition list.
	 *
	 * @param array		$specifications The rows, each with a label and a value
	 * @param string	$title (optional) Small uppercase heading above the list
	 */
	function setSpecifications($specifications, $title = '') {
		if (empty($specifications)) return;

		$this->content .= '<div class="specifications">';

		if (!empty($title)) {
			$this->content .= '<p class="specifications__title">' . esc_html($title) . '</p>';
		}

		$this->content .= '<dl class="specifications__list">';

		foreach ($specifications as $specification) {
			if (empty($specification['label']) && empty($specification['value'])) continue;

			$this->content .= '<div class="specifications__row">';
			$this->content .= '<dt class="specifications__label">' . esc_html($specification['label'] ?? '') . '</dt>';
			$this->content .= '<dd class="specifications__value">' . esc_html($specification['value'] ?? '') . '</dd>';
			$this->content .= '</div>';
		}

		$this->content .= '</dl>';
		$this->content .= '</div>';
	}

	/**
	 * @param int	$form_id		The form ID
	 */
	function setForm($form_id) {
		if (!function_exists('gravity_form')) return;

		$this->content = gravity_form($form_id, false, false, false, null, true, 0, false);
	}

	/**
	 * @param int	$person		The person ID
	 */
	function setPerson($person) {
		ob_start();

		component('contact-person', [
			'team_member_ID' => $person,
		]);

		$this->content .= ob_get_clean();
	}

	/**
	 * @param array $button	The button array
	 */
	function setButtons($buttons) {
		$buttons = new BlockButtons($buttons);
		$this->content = $buttons->get_buttons();
	}
}
