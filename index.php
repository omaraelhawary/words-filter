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

        $mainPageHook = add_menu_page('ًWords Filter', 'Words Filter', 'manage_options', 'words-filter', array($this, 'wordsFilterPage'), plugin_dir_url(__FILE__) . 'icon.svg', 110);

        add_submenu_page('words-filter', 'Words to Filter', 'Words List', 'manage_options', 'words-filter', array($this, 'wordsFilterPage'));

        add_submenu_page('words-filter', 'Word Filter Options', 'Options', 'manage_options', 'words-filter-option', array($this, 'Wordsfilteroptions'));

        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets(){
        wp_enqueue_style('words-filter-styles', plugin_dir_url(__FILE__) . 'style.css');
    }


    function wordsFilterPage(){ ?>

<div class="wrap">
    <h1>Words Filter<h1>
            <form action="" method="post">
                <label for="filterWords">
                    <p> Enter a <strong>comma-sepratated</strong> list of words to filter from your site content</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="filterWords" id="filterWords" placeholder="bad, mean, awful"
                        class="wprd-filter__textarea"></textarea>
                </div>
                <input type="submit" name="submit" value="Save Changes" id="submit" class="button button-primary">
            </form>
</div>

<?php }

    function Wordsfilteroptions(){ ?>

<h1>Options page</h1>

<?php }

}

$wordsFilter = new WordsFilter();