<?php

/*
Plugin Name: Permanent Links
Description: This plugin enalbe you to insert permanent link into page templates.
Author: Ariel Braun
Version: 1.6
*/
define(PL_DOMAIN, 'pl_domain');
include 'Permalinks.php';
global $PL;
$PL=new Permalinks();

/**
 * @global Permalink $PL
 * @param array $args Including 'slug' => the unique name of the link and description => a description 
 */

function register_permanent_link($args)
{
    global $PL;
    $PL->addPermalink($args);
}
/**
 * 
 * @global Permalinks $PL
 * @param string $location The permanent slug this link is connected.
 * @param string $text Text within the link.
 * @param string $class Css class of the link tag.
 */
function wp_permanent_link($location, $text, $class)
{
    global $PL;
    foreach ($PL->links as $l){
        if($l['slug'] == $location){
            $link=$PL->get_link_by_slug($location);
            $link->render($text, $class);

        }
    }
}
?>
