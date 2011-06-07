<?php
/**
 * Core class for Suffusion. This holds the options for the theme.
 *
 * @package Suffusion
 * @subpackage Functions
 */
class Suffusion {
	var $context;

	function init() {
	}

	function get_context() {
		if (is_array($this->context)) {
			return $this->context;
		}

		$this->context = array();
		if (is_front_page()) {
			$this->context[] = 'home';
		}

		if (is_home()) {
			$this->context[] = 'blog';
		}

		if (is_singular()) {
			global $post;
			$this->context[] = 'singular';
			$this->context[] = "{$post->post_type}";
			if ($post->post_type == 'page') {
				$page_template = get_page_template();
				if (strlen($page_template) > strlen(TEMPLATEPATH)) {
					$page_template = substr($page_template, strlen(TEMPLATEPATH) + 1);
				}
				$this->context[] = $page_template;
			}
		}
		else if (is_archive()) {
			$this->context[] = 'archive';
			if (is_date()) {
				$this->context[] = 'date';
			}
			else if (is_category()) {
				$this->context[] = 'taxonomy';
				$this->context[] = 'category';
			}
			else if (is_tag()) {
				$this->context[] = 'taxonomy';
				$this->context[] = 'tag';
			}
			else if (is_author()) {
				$this->context[] = 'author';
			}
		}
		else if (is_search()) {
			$this->context[] = 'search';
		}
		else if (is_404()) {
			$this->context[] = '404';
		}

		return $this->context;
	}
}

?>