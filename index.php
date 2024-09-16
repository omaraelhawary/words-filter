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
        add_menu_page('ًWords Filter', 'Words Filter', 'manage_options', 'words-filter', array($this, 'wordsFilterPage'), 'dashicons-text', 110);

        add_submenu_page('words-filter', 'Words to Filter', 'Words List', 'manage_options', 'words-filter', array($this, 'wordsFilterPage'));

        add_submenu_page('words-filter', 'Word Filter Options', 'Options', 'manage_options', 'words-filter-option', array($this, 'Wordsfilteroptions'));
    }

    function wordsFilterPage(){ ?>

<h1>Word Filter</h1>

<?php }

    function Wordsfilteroptions(){ ?>

<h1>Options page</h1>

<?php }

}

$wordsFilter = new WordsFilter();