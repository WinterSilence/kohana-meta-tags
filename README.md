##Meta tags module for Kohana framework 3.х

###Installation and setup:
Unzip the archive and place the contents into a directory with modules(`DOCROOT/modules/`). 
If necessary, copy `config/meta.php` in `APPPATH/config/meta.php` and change it.

###Basic usage:
Instance class. Optional, you can set new config options.
~~~
$meta = Meta::instance($config);
~~~
Set tag:
~~~
$meta->set('content-language', I18n::$lang);
$meta->description = 'description text';
~~~
Sets title content as string or array.
~~~
$meta->set('title', array('Site name', 'Page name'));
$meta->set('title', 'Site name - Page name');
~~~
Set tags:
~~~
$meta->set(array('author' => 'WinterSilence', 'generator' => 'Kohana 3.3'));
~~~
Get tag:
~~~
$meta->get('description');
$tag_content = $meta->description;
~~~
Get all tag:
~~~
$all_tags = $meta->get();
~~~
Unset:
~~~
unset($meta->description);
~~~
Isset:
~~~
if (isset($meta->description))
{
	// ...
}
~~~
Render\Display:
~~~
echo $meta->render($view_filename);
echo $meta; // called __toString()
~~~
Or add in your template meta modules subview:
~~~
<?php echo View::factory('meta') ?>
~~~

###Helpers:
Parses meta tags from document:
~~~
$tags = Meta::parse($url);
~~~

###License:
Copyright (c) 2013 handy-soft.ru

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