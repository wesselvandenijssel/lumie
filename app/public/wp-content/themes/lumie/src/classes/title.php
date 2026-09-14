<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

class BlockTitle {
	public $title;
	public $type;
	public $look = ['main-title'];
	public $subtitle = "";
	public $suptitle = "";
	public $classes = "";

	/**
	 * @param string $title	The title text
	 */
	function __construct($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	function getTitle() {
		return '
			<div class="titles' . $this->classes . '">
				' . $this->suptitle . '
				<' . $this->type . ' class="' . implode(' ', $this->look) . '">' . $this->title . '</' . $this->type . '>
				' . $this->subtitle . '
			</div>
		';
	}

	/**
	 * Set the subtitle with the provided text and block data for the generation of the heading element.
	 *
	 * @param string $subtitle The subtitle text.
	 */
	function setSubtitle($subtitle) {
		$this->subtitle = '<h3 class="subtitle h3">' . $subtitle . '</h3>';
	}

	/**
	 * Set the suptitle with the provided text and block data for the generation of the heading element.
	 *
	 * @param string $suptitle The suptitle text.
	 */
	function setSuptitle($suptitle) {
		$this->suptitle = '<h3 class="suptitle h3">' . $suptitle . '</h3>';
	}

	function setCentered() {
		$this->classes .= " text-center";
	}

	/**
	 * Set an extra class to give the title a different appearance (like h1, h2, ...).
	 *
	 * @param string $look The extra class for the title.
	 */
	function setLook($look) {
		$this->look[] = $look;
	}

	/**
	 * Set the element type of the title (e.g., h1, h2).
	 *
	 * @param string $type The HTML element type for the title.
	 */
	function setType($type) {
		$this->type = $type;
	}

	/**
	 * Set an extra class for the 'titles' div.
	 *
	 * @param string $class The extra class for the 'titles' div.
	 */
	function setClass($class) {
		$this->classes .= " " . $class;
	}
}
