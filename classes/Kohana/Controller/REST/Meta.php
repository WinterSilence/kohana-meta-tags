<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * RESTful controller for work with Meta class. 
 * 
 * @package    Kohana/Meta
 * @category   Controllers
 * @version    1.3
 * @author     WinterSilence <info@handy-soft.ru>
 * @copyright  2013 © handy-soft.ru
 * @license    MIT
 * @link       http://github.com/WinterSilence/kohana-meta-tags
 */
abstract class Kohana_Controller_REST_Meta extends Controller {

	/**
	 * @var  array  REST types
	 */
	protected $_actions = array(
		Request::GET    => 'get',
		Request::POST   => 'set',
		Request::DELETE => 'delete',
	);

	/**
	 * @var  string  Request method
	 */
	protected $_method = '';

	/**
	 * @var  Meta  Meta class singleton
	 */
	protected $_meta = NULL;

	/**
	 * Checks the requested method against the available methods. If the method
	 * is supported, sets the request action from the map. If not supported,
	 * the "invalid" action will be called.
	 *
	 * @return void
	 */
	public function before()
	{
		// Detect and set method
		$this->_method = Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method());
		// Detect and change action
		if ( ! isset($this->_actions[$this->_method]))
		{
			$this->request->action('invalid');
		}
		else
		{
			$this->request->action($this->_actions[$this->_method]);
		}
		// Get Meta instance
		$this->_meta = Meta::instance();
		// Call parent
		parent::before();
	}

	/**
	 * Automatically executed after the controller action. 
	 * Can be used to apply transformation to the request response, 
	 * add extra output, and execute other custom code.
	 *
	 * @return void
	 */
	public function after()
	{
		// Sets response headers
		if (in_array($this->_method, array(Request::POST, Request::DELETE)))
		{
			$this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');
		}
		$this->response->headers('content-type', 'application/json; charset='.Kohana::$charset);
		// Call parent
		parent::after();
	}

	/**
	 * Sends a 405 "Method Not Allowed" response and a list of allowed actions.
	 *
	 * @return void
	 */
	public function action_invalid()
	{
		// Send the "Method Not Allowed" response
		$this->response->status(405)->headers('Allow', implode(', ', array_keys($this->_actions)));
	}

	/**
	 * Get tag(s)
	 * 
	 * @return void
	 */
	public function action_get()
	{
		// Set tag name
		$tag = $this->request->param('tag');
		if (is_null($tag))
		{
			// Get all tags
			$data = $this->_meta->get();
		}
		elseif ( ! isset($this->_meta[$tag]))
		{
			// Tags not found
			$this->response->status(404);
		}
		else
		{
			// Get tag attributes
			$data = $this->_meta->get($tag);
		}
		// Set response as JSON array
		if (isset($data))
		{
			$this->response->body(json_encode($data));
		}
	}

	/**
	 * Set tag(s)
	 * 
	 * @return void
	 */
	public function action_set()
	{
		$this->_meta->set($this->request->post());
	}
	
	/**
	 * Delete tag(s)
	 * 
	 * @return void
	 */
	public function action_delete()
	{
		// Set tag name
		$tag = $this->request->param('tag');
		if (is_null($tag))
		{
			// Delete all tags
			$this->_meta->delete();
		}
		elseif ( ! isset($this->_meta[$tag]))
		{
			// Tags not found
			$this->response->status(404);
		}
		else
		{
			// Delete tag
			$this->_meta->delete($tag);
		}
	}

}
