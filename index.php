<?php 
/*
    Plugin Name: Words Filter
    Description: Replace a list of words.
    Version: 1.0
    Author: Omar ElHawary
    Author URI: https://www.linkedin.com/in/omaraelhawary/
*/

if(!defined('ABSPATH')) exit;

class WordsFilter {
    function __construct(){
        add_action('admin_menu', array($this, 'ourMenu'));
    }
    function ourMenu(){
        add_menu_page('ًWords Filters', 'Words Filters', 'manage_options', 'words-filter', array($this, 'wordFilterPage'), 'dashicons-text', 110);
    }

    function wordFilterPage(){ ?>

<h1>Word Filter</h1>

<?php }

}

$wordsFilter = new WordsFilter();