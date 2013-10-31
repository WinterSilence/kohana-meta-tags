<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract RESTful controller for work with Meta class. 
 *
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2009-2012 Kohana Team
 * @license    http://kohanaframework.org/license
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
	 * @var  string 
	 */
	protected $_method = '';

	/**
	 * @var  Meta
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
		$this->_method = Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method());
		
		if ( ! isset($this->_actions[$this->_method]))
		{
			$this->request->action('invalid');
		}
		else
		{
			$this->request->action($this->_actions[$this->_method]);
		}
		
		$this->_meta = Meta::instance();
		
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
		if (in_array($this->_method, array(Request::POST, Request::DELETE)))
		{
			$this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');
		}
		
		$this->response->headers('content-type', 'application/json; charset='.Kohana::$charset);
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
	 * 
	 * 
	 * @return void
	 */
	public function action_get()
	{
		$tag = $this->request->param('tag');
		
		if (is_null($tag))
		{
			$data = $this->_meta->get();
		}
		else( ! isset($this->_meta[$tag]))
		{
			$this->response->status(404);
		}
		else
		{
			$data = $this->_meta->get($tag);
		}
		
		if (isset($data))
		{
			$this->response->body(json_encode($data));
		}
	}

	/**
	 * 
	 * 
	 * @return void
	 */
	public function action_set()
	{
		$this->_meta->set($this->request->post());
	}
	
	/**
	 * 
	 * 
	 * @return void
	 */
	public function action_delete()
	{
		$tag = $this->request->param('tag');
		
		if ( ! isset($this->_meta[$tag]))
		{
			$this->response->status(404);
		}
		else
		{
			$this->_meta->delete($tag);
		}
	}

} // End Controller_REST_Meta