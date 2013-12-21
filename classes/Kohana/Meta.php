<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class for work with HTML meta tags. 
 * For get more info about meta tags visit [http://wikipedia.org/wiki/Meta_element](http://wikipedia.org/wiki/Meta_element).
 * 
 * @package    Meta
 * @category   Base
 * @version    1.5
 * @author     WinterSilence <info@handy-soft.ru>
 * @author     Samuel Demirdjian
 * @copyright  2013 © handy-soft.ru
 * @license    MIT
 * @link       http://github.com/WinterSilence/kohana-meta-tags
 */
abstract class Kohana_Meta {

	/**
	 * Uses in title method
	 */
	const TITLE_REPLACE = 0;
	const TITLE_UNSHIFT = 1;
	const TITLE_PREPEND = 1; // Same as unshift
	const TITLE_PUSH    = 2;
	const TITLE_APPEND  = 2; // Same as push

	/**
	 * @var  Meta  Class singleton
	 */
	protected static $_instance = NULL;

	/**
	 * @var  array  Configuration options
	 */
	protected $_cfg = array();

	/**
	 * @var  array  Meta tags attributes
	 */
	protected $_tags = array();

	/**
	 * Get class instance and sets config properties
	 * 
	 * @param  array  $config  Configuration options [optional]
	 * @return Meta
	 * @uses   Arr::merge
	 */
	public static function instance(array $config = array())
	{
		// Create class instance
		if (self::$_instance === NULL)
		{
			$class = get_called_class();
			self::$_instance = new $class;
		}
		// Sets new configuration option
		self::$_instance->_cfg = Arr::merge(self::$_instance->_cfg, $config);
		// Return instance
		return self::$_instance;
	}

	/**
	 * Load configuration and default tags
	 *
	 * @return void
	 * @uses   Kohana
	 * @uses   Config
	 * @uses   Config_Group
	 */
	protected function __construct()
	{
		$this->_cfg = Kohana::$config->load('meta');
		$this->load_from_config($this->_cfg['tags_config_groups']);
	}

	/**
	 * Load tags from config
	 * 
	 *     Meta::instance()->load_from_config('cms.meta_tags');
	 *     Meta::instance()->load_from_config(array('meta_tags', 'blog.meta'));
	 * 
	 * @param  string|array  $group  Config name or an array of them
	 * @return Meta
	 * @uses   Kohana
	 * @uses   Config
	 * @uses   Config_Group
	 */
	public function load_from_config($group)
	{
		$tags = array();
		// Merge configs data
		foreach ( (array) $group as $name)
		{
			$config = Kohana::$config->load($name);
			if ($config instanceof Config_Group)
			{
				$config = $config->as_array();
			}
			$tags = array_merge($tags, (array) $config);
		}
		// Set tags
		$this->set($tags);
		// Return self
		return $this;
	}

	/**
	 * Set tags
	 * 
	 * @param  string|array  $name   Name tag or array tags
	 * @param  string        $value  Content attribute
	 * @return Meta
	 * @uses   Arr::is_array
	 * @uses   UTF8::strtolower
	 */
	public function set($name, $value = NULL)
	{
		if ( ! Arr::is_array($name))
		{
			$name = array($name => $value);
		}
		// Set tags
		foreach ($name as $tag => $value)
		{
			$tag = UTF8::strtolower($tag);
			if ($tag !== 'title')
			{
				if (isset($this->_tags[$tag]))
				{
					// Update meta tag
					$this->_tags[$tag]['content'] = $value;
				}
				elseif ($this->_cfg['hide_empty'] !== TRUE OR ! empty($value))
				{
					// Add meta tag
					$group = in_array($tag, $this->_cfg['http-equiv']) ? 'http-equiv' : 'name';
					$this->_tags[$tag] = array($group => $tag, 'content' => $value);
				}
			}
			else
			{
				// Set title tag
				$this->_tags[$tag] =  $value;
			}
		}
		return $this;
	}

	/**
	 * Get tags
	 * 
	 * @param  string  $name
	 * @return mixed
	 */
	public function get($name = NULL)
	{
		if ($name === NULL)
		{
			// Get all nonempty tags
			return $this->_tags;
		}
		elseif (isset($this->_tags[$name]))
		{
			// Get tag
			return $this->_tags[$name];
		}
	}

	/**
	 * Delete tags
	 * 
	 * @param  string|array  $name
	 * @return Meta
	 */
	public function delete($name = NULL)
	{
		if ($name === NULL)
		{
			$this->_tags = array();
		}
		else
		{
			foreach ( (array) $name as $tag)
			{
				unset($this->_tags[$tag]);
			}
		}
		return $this;
	}

	/**
	 * Render template(View) with meta data.
	 * 
	 * @param   string  $file  Template [View] filename
	 * @return  string
	 * @uses    View
	 */
	public function render($file = NULL)
	{
		return View::factory($this->_cfg['template'])
			->set('tags', $this->get())
			->render($file);
	}

	/**
	 * Wrapper for get\set title tag
	 * 
	 * @param   mixed    $value   New title value
	 * @param   integer  $method  Action type for title array
	 * @return  mixed
	 */
	public function title($title = NULL, $method = self::TITLE_REPLACE)
	{
		// Acts as getter if $title is null
		if ($title === NULL)
		{
			return $this->get('title');
		}
		// Acts as setter
		$new_title = (array) $title;
		$old_title = (array) $this->get('title');
		switch ($method)
		{
			case self::TITLE_UNSHIFT:
				// Merge, the new one will be prepended (like array_unshift)
				$this->set('title', array_merge($new_title, $old_title));
				break;
			case self::TITLE_PUSH:
				// Merge, the new one will be appended (like array_push)
				$this->set('title', array_merge($old_title, $new_title));
				break;
			default: // Case Meta::TITLE_REPLACE:
				// Replace
				$this->set('title', $new_title);
		}
		return $this;
	}

	/**
	 * Utilized for reading data from inaccessible properties. 
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Get tags
	 * 
	 *     Meta::instance()->title = array('Shop name', 'Category');
	 *     array_push(Meta::instance()->title, 'Product 123');
	 *     // result: array('Shop name', 'Category', 'Product 123');
	 * 
	 * @param  string $name
	 * @return mixed
	 */
	public function & __get($name)
	{
		return $this->_tags[$name];
	}

	/**
	 * Check isset tag
	 * 
	 * @param  string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset($this->_tags[$name]);
	}

	/**
	 * Delete tags
	 * 
	 * @param  string $name
	 * @return bool
	 */
	public function __unset($name)
	{
		$this->delete($name);
	}

	/**
	 * Allows a class to decide how it will react when it is treated like a string.
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Clone method protected from external call
	 * 
	 * @return void
	 */
	public function __clone()
	{
		throw new Kohana_Exception('Cloning of Meta objects is forbidden');
	}

	/**
	 * Wakeup method protected from external call
	 * 
	 * @return void
	 */
	public function __wakeup()
	{
		throw new Kohana_Exception('Wakeup of Meta objects is forbidden');
	}

} // End Meta