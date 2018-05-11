<?php

/**
 * Image Proxy
 *
 * Plugin to enable the use of the go imageproxy inside roundcube.
 * It by default only supports the signed request format.
 *
 * @version @package_version@
 * @license GNU GPLv3+
 * @author evandrofisico
 * @website http://roundcube.net
 */
class imageproxy extends rcube_plugin
{
    public $task = 'mail';


    /**
     * Plugin initilization.
     */
    function init()
    {
        $rcube = rcube::get_instance();
        $this->add_hook('message_part_after', array($this, 'message_part_after'));
    }

    function message_part_after($args)
    {
        if ($args['type'] == 'html') {
            $this->load_config();

            $rcube = rcube::get_instance();
            if (!$rcube->config->get('imageproxy_url', false)) {
                return $args;
            }

	    preg_match_all('@src="http([^"]+)"@', $args['body'], $imagesUrl);
	    $srcurl = array_pop($imagesUrl);
	    foreach($srcurl as $imgurl){
		$newpath = $this->image_proxy_path("http".$imgurl);
		$args["body"] = str_replace("http".$imgurl,$newpath,$args["body"]);
	    }
        }
        return $args;
    }

    function image_proxy_path($url){
        $rcube = rcube::get_instance();
	$proxypath = $rcube->config->get('imageproxy_url');
	$signature = $rcube->config->get('imageproxy_signature');
	$imghs = str_replace(array('+','/'),array('-','_'),base64_encode(hash_hmac("sha256",$url,$signature,true)));
	return $proxypath."/s".$imghs."/".$url;
    }
}
