<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

class BlockPopup {
	public $popup_id;
	public $title;
	public $content;

	/**
	 * @param int $popup_id		The ID of the popup post
	 */
	function __construct($popup_id) {
		$this->popup_id = $popup_id;
		$this->title = get_the_title($popup_id);
		$this->content = get_field('content', $popup_id) ?? [];
	}

	/**
	 * @return void
	 */
	function get_popup() {
		if (!function_exists('gravity_form'))
			return;

		component('popup', [
			'popup_id' => $this->popup_id,
			'title' => $this->title,
			'content' => $this->content,
		]);
	}
}
