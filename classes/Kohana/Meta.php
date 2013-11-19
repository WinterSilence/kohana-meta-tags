<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class for work with HTML meta tags. <br>
 * For get more info about meta tags visit [http://wikipedia.org/wiki/Meta_element](http://wikipedia.org/wiki/Meta_element).
 * 
 * @package    Kohana/Meta
 * @category   Base
 * @version    1.3
 * @author     WinterSilence <info@handy-soft.ru>
 * @copyright  2013 © handy-soft.ru
 * @license    MIT
 * @link       http://github.com/WinterSilence/kohana-meta-tags
 */
abstract class Kohana_Meta {

	/**
	 * @var  Meta  Class singleton
	 */
	protected static $_instance = NULL;

	/**
	 * @var  array  Configuration options
	 */
	protected $_cfg = array();

	/**
	 * @var  array  Meta tags
	 */
	protected $_tags = array();

	/**
	 * Get class instance and sets config properties
	 * 
	 * @param  array  $config  Optional configuration options
	 * @return Meta
	 */
	public static function instance(array $config = array())
	{
		// Create class instance
		if ( ! self::$_instance)
		{
			$class = get_called_class();
			self::$_instance = new $class;
		}
		// Sets new configuration option
		foreach ($config as $key => $value)
		{
			if (isset(self::$_instance->_cfg[$key]))
			{
				self::$_instance->_cfg[$key] = $value;
			}
		}
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
	 */
	public function set($name, $value = NULL)
	{
		if ( ! is_array($name))
		{
			$name = array($name => $value);
		}
		// Set tags
		foreach ($name as $tag => $value)
		{
			$tag = strtolower($tag);
			if ($tag != 'title')
			{
				if (isset($this->_tags[$tag]))
				{
					// Update meta tag
					$this->_tags[$tag]['content'] = $value;
				}
				else
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
		if (is_null($name))
		{
			// Get all nonempty tags
			return array_filter($this->_tags);
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
		if (is_null($name))
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
	 * @param   string  $file  Template(View) filename
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
	 * @param   mixed  $value  New title value
	 * @return  mixed
	 */
	public function title($value = NULL)
	{
		// Get title
		if (is_null($value))
		{
			return $this->get('title');
		}
		// Set title
		return $this->set('title', $value);
	}

	/**
	 * Utilized for reading data from inaccessible properties. 
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @return voide
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Get tags
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->get($name);
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
	protected function __clone() {}

	/**
	 * Wakeup method protected from external call
	 * 
	 * @return void
	 */
	protected function __wakeup() {}

} // End Meta