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

    /**
     * Constructor for the WordsFilter class.
     *
     * Initializes the class by adding actions to the admin menu and admin init, 
     * and a filter to the content if filter words data is available.
     *
     * @return void
     */
    function __construct(){
        add_action('admin_menu', array($this, 'ourMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
        if(get_option('filterWordsData')) add_filter('the_content', array($this, 'filterWords'));
    }

    /**
     * Registers the settings for the Words Filter plugin.
     *
     * This function is responsible for adding a settings section, registering a setting, 
     * and adding a settings field for the replacement text.
     *
     * @return void
     */
    function registerSettings(){
        add_settings_section( 'replacement-text-section', null, null, 'words-filter-option');
        register_setting( 'replacementFields', 'replacementText' );
        add_settings_field( 'replacement-text', 'Filtered Text', array($this, 'replacementFieldHTML'), 'words-filter-option', 'replacement-text-section');
    }

    /**
     * Displays the replacement text field HTML for the Words Filter plugin.
     *
     * This function is responsible for rendering the replacement text field, 
     * including the input field and a description.
     *
     * @return void
     */
    function replacementFieldHTML(){ ?>
        
        <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')) ?>">
        <p class="description">Leave blank to simply remove the words</p>
    <?php }

    /**
     * Replaces bad words in a given content with a replacement text.
     *
     * @param string $content The content to filter.
     * @return string The filtered content.
     */
    function filterWords($content){
        $badWords = explode(',', get_option('filterWordsData'));
        $badWordsTrimmed = array_map('trim', $badWords);
        $replacementText = esc_html(get_option('replacementText', '****'));
        return str_ireplace($badWordsTrimmed, $replacementText, $content);        
    }
    
    /**
     * Creates the menu and sub-menu pages for the Words Filter plugin.
     *
     * This function is responsible for adding the main menu page, two sub-menu pages, 
     * and enqueueing the necessary assets for the main page.
     * 
     * @return void
     */
    function ourMenu(){

        $mainPageHook = add_menu_page('ًWords Filter', 'Words Filter', 'manage_options', 'words-filter', array($this, 'wordsFilterPage'), plugin_dir_url(__FILE__) . 'icon.svg', 110);

        add_submenu_page('words-filter', 'Words to Filter', 'Words List', 'manage_options', 'words-filter', array($this, 'wordsFilterPage'));

        add_submenu_page('words-filter', 'Word Filter Options', 'Options', 'manage_options', 'words-filter-option', array($this, 'Wordsfilteroptions'));

        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    /**
     * Enqueues the necessary CSS styles for the main page of the Words Filter plugin.
     *
     * @return void
     */
    function mainPageAssets(){
        wp_enqueue_style('words-filter-styles', plugin_dir_url(__FILE__) . 'style.css');
    }

    /**
     * Handles the submission of filter words, verifying the nonce and user capabilities.
     *
     * If the submission is valid, updates the filter words option and displays a success message.
     * Otherwise, displays an error message.
     *
     * @return void
     */
    function justSubmitted(){
        if(wp_verify_nonce( $_POST['ourNonce'], 'saveFilterWords' ) && current_user_can( 'manage_options' )) {
            update_option('filterWordsData', sanitize_text_field( $_POST['filterWords'] ));
            ?>
                <div class="updated">
                    <p>Your filter words have been saved</p>
                </div>
            <?php
        } else {
            ?>
                <div class="error">
                    <p>Sorry, you don't have the premission to do that</p>
                </div>
            <?php
        }
    }

    /**
     * Displays the Words Filter page, including a form to input a comma-separated list of words to filter from the site content.
     *
     * The form includes a nonce field for security and a textarea to input the filter words. The function also checks if the form has been submitted and calls the justSubmitted method if it has.
     *
     * @return void
     */
    function wordsFilterPage(){ ?>
        <div class="wrap">
            <h1>Words Filter<h1>
                <?php if (isset($_POST['justsubmitted']) == "true") $this->justSubmitted(); ?>
                <form method="post">
                    <input type="hidden" name="justsubmitted" value="true">
                    <?php wp_nonce_field('saveFilterWords', 'ourNonce') ?>
                    <label for="filterWords">
                        <p> Enter a <strong>comma-sepratated</strong> list of words to filter from your site content</p>
                    </label>
                    <div class="word-filter__flex-container">
                        <textarea name="filterWords" id="filterWords" placeholder="bad, mean, awful" class="word-filter__textarea"><?php echo esc_textarea(get_option('filterWordsData')); ?></textarea>
                        </div>
                    <input type="submit" name="submit" value="Save Changes" id="submit" class="button button-primary">
                </form>
        </div>

    <?php }

    /**
     * Displays the Words Filter Options page, including a form to input the replacement fields.
     *
     * This function is responsible for rendering the options page, including any error messages,
     * the replacement fields form, and the submit button.
     *
     * @return void
     */
    function Wordsfilteroptions(){ ?>
        <div class="wrap">
            <h1>Words Filter Options</h1>
            <form action="options.php" method="POST">
                <?php
                    settings_errors();
                    settings_fields( 'replacementFields' );
                    do_settings_sections( 'words-filter-option' );
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

}

$wordsFilter = new WordsFilter();