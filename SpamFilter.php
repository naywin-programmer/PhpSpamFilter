<?php

/* Created By Nay Win */

class SpamFilter
{
	protected static $instance = null;

	protected $matched_urls = array();

	protected $matched_keywords = array();

	protected $urls = array();

	protected $keywords = array();
	
	protected $default_filter_size = array('urls' => 1, 'keyword_if_url_match' => 1, 'keywords' => 2);

	public static function init()
	{
		if(static::$instance === null) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	public static function setData($key = null, $value = array())
	{
		$instance = static::init();

		// $key - urls, keywords
		if(property_exists($instance, $key)) {
			// var_dump("$key property don\'t exist in SpamFilter Class");
			$instance->$key = array_unique(array_merge($instance->$key, $value));
		}

		return $instance;
	}

	public function train($text)
	{
		$matched_urls = array_filter($this->urls, function($each) use ($text) {
			if(stristr($text, $each)) {
				return true;
			}
		});

		$matched_keywords = array_filter($this->keywords, function($each) use ($text) {
			if(stristr($text, $each)) {
				return true;
			}
		});

		$this->matched_urls = $matched_urls;
		$this->matched_keywords = $matched_keywords;

		return $this;
	}

	public function fullResult()
	{
		return array(
			'matched_urls' => $this->matched_urls,
			'matched_keywords' => $this->matched_keywords,
			'count_of_matched_urls' => sizeof($this->matched_urls),
			'count_of_matched_keywords' => sizeof($this->matched_keywords)
		);
	}

	public static function result($text, $filter_size = null)
	{
		$instance = static::init();
		
		if(!$filter_size || !is_array($filter_size)) {
            		$filter_size = $instance->default_filter_size;
        	}
		
		$full_result = $instance->train($text)->fullResult();

		if($full_result['count_of_matched_urls'] >= $filter_size['urls']) {
			if($full_result['count_of_matched_keywords'] >= $filter_size['keyword_if_url_match']) {
				return true;
			}
		}

		if($full_result['count_of_matched_keywords'] >= $filter_size['keywords']) {
			return true;
		}

		return false;
	}

	public static function resultWithValue($text)
	{
		$instance = static::init();
		$train = $instance->train($text);
		return array_merge(
			array('result' => $train->result($text)), 
			$train->fullResult()
		);
	}
}
