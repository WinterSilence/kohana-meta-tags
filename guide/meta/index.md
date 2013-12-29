# Install

To enable, open your `APPPATH/bootstrap.php` file and modify the call to [Kohana::modules] by including the meta module like so:

~~~
Kohana::modules(array(
	...
	'meta' => MODPATH.'meta', // Meta tags
	...
));
~~~

Next, you will then need to [configure](config) the module.