# ImageProxy Plugin

This plugins allows you to requests for inline images on html messages on roundcube through a [imageproxy](https://github.com/willnorris/imageproxy) instance. It is useful for forcing such requests to be made on https, to modify images on the run.

I am specially using it in conjunction to a serviceworker plugin (soon to be released) to allow access to linked images on a pure https instance. To avoid misuse, this plugins only works with a imageproxy instance configured to use *Signed Requests*.


## How I'm using it

I run a local imageproxy instance on localhost, configured to cache on a local redis instance, something like this:

	imageproxy -addr localhost:8080 --cache redis://localhost/ -verbose -signatureKey VeryLongSignaturestring

On the roundcube hosting vhost, 

        ProxyPass /imageproxy        http://localhost:8080
        ProxyPassReverse /imageproxy http://localhost:8080


On the plugin configuration,

	$config['imageproxy_url'] = 'https://webmail.example.com/imageproxy';
	$config['imageproxy_signature'] = 'VeryLongSignaturestring';



