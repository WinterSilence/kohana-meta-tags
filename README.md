##Meta tags module for Kohana framework 3.х

[!!!] In this version added RESTful controller(+ route in init.php) for work with Meta class.

###Installation and setup:
Unzip the archive and place the contents into a directory with modules(`DOCROOT/modules/`). 
If necessary, copy `config/meta.php` in `APPPATH/config/meta.php` and change it.

###Basic usage:
*Instance class* 
Optional, you can set new config options.
~~~
$meta = Meta::instance($config);
~~~
*Set tag*:
~~~
$meta->set('content-language', I18n::$lang);
$meta->description = 'description text';
~~~
*Set tags*:
~~~
$meta->set(array('author' => 'WinterSilence', 'generator' => 'Kohana 3.3'));
~~~
*Get tag*:
~~~
$meta->get('description');
$tag_content = $meta->description;
~~~
*Get all tag*:
~~~
$all_tags = $meta->get();
~~~
*Unset*:
~~~
unset($meta->description);
~~~
*Isset*:
~~~
if (isset($meta->description))
{
	// ...
}
~~~
*Title tag*:
Meta class have method-wrapper for get\set title tag.
~~~
$title = $meta->title();
// For set title use string or array
$meta->title('Site name - Page name');
$meta->title(array('Site name', 'Page name'));
~~~
*Display\Render tags*:
Add in your template(View) subview of meta module. 
Module includes 2 subviews, but you can create a custom version.
Full\default version:
~~~
<?php echo View::factory('meta/full') ?>
~~~
Light\alternative version:
<?php echo View::factory('meta/easy') ?>
~~~
As alternative: 
forcibly call render method (uses for sets nonstandard template)
~~~
<?php echo Meta::instance()->render($view_filename) ?>
~~~
or display Meta object as string (called magic method __toString())
~~~
<?php echo Meta::instance() ?>
~~~

###Hint:
For parse meta tags from document use function `get_meta_tags`.
For gets more info visit [http://php.net/manual/function.get-meta-tags.php](http://php.net/manual/function.get-meta-tags.php).
~~~
// Assuming the above tags are at www.example.com
$tags = get_meta_tags('http://www.example.com/');
// Notice how the keys are all lowercase now, and how . was replaced by _ in the key.
echo $tags['author'];       // name
echo $tags['keywords'];     // php documentation
echo $tags['description'];  // a php manual
echo $tags['geo_position']; // 49.33;-86.59
~~~

###License:
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.