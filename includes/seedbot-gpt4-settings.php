<?php
/**
 * SeedBot GPT4 - Settings Page
 *
 * This file contains the code for the SeedBot GPT4 settings page.
 * It allows users to configure the API key and other parameters
 * required to access the ChatGPT API from their own account.
 *
 * @package seedbot-gpt4
 */

// If this file is called directly, die.
if (!defined('ABSPATH')) {
    die();
}

function seedbot_gpt4_settings_page()
{
    add_options_page('SeedBot GPT4 Settings', 'SeedBot GPT4', 'manage_options', 'seedbot', 'seedbot_gpt4_settings_page_html');
}
add_action('admin_menu', 'seedbot_gpt4_settings_page');

// Settings page HTML - Ver 1.3.0
function seedbot_gpt4_settings_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'api_model';

    if (isset($_GET['settings-updated'])) {
        add_settings_error('seedbot_gpt4_messages', 'seedbot_gpt4_message', 'Settings Saved', 'updated');
    }

    // REMOVED Ver 1.3.0
    // settings_errors('seedbot_gpt4_messages');
    
    ?>
    <div class="wrap">
        <h1><span class="dashicons dashicons-format-chat"></span> <?php echo esc_html(get_admin_page_title()); ?></h1>

        <!-- Message Box - Ver 1.3.0 -->
        <div id="message-box-container"></div>

        <!-- Message Box - Ver 1.3.0 -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const seedbotSettingsForm = document.getElementById('seedbot-settings-form');
                // Read the start status - Ver 1.4.1
                const seedbotStartStatusInput = document.getElementById('seedGPTBotStatus');
                const reminderCount = localStorage.getItem('reminderCount') || 0;

                if (reminderCount < 5) {
                    const messageBox = document.createElement('div');
                    messageBox.id = 'rateReviewMessageBox';
                    messageBox.innerHTML = `
                    <div id="rateReviewMessageBox" style="background-color: white; border: 1px solid black; padding: 10px; position: relative;">
                        <div class="message-content" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Still Testing. Thank you!</span>
                            <button id="closeMessageBox" class="dashicons dashicons-dismiss" style="background: none; border: none; cursor: pointer; outline: none; padding: 0; margin-left: 10px;"></button>
                            
                        </div>
                    </div>
                    `;

                    document.querySelector('#message-box-container').insertAdjacentElement('beforeend', messageBox);

                    document.getElementById('closeMessageBox').addEventListener('click', function() {
                        messageBox.style.display = 'none';
                        localStorage.setItem('reminderCount', parseInt(reminderCount, 10) + 1);
                    });
                }
            });
        </script>
    
    <script>
    jQuery(document).ready(function($) {
        var seedbotSettingsForm = document.getElementById('seedbot-settings-form');

        if (seedbotSettingsForm) {

            seedbotSettingsForm.addEventListener('submit', function() {

                // Get the input elements by their ids
                const seedbotNameInput = document.getElementById('seedbot_bot_name');
                const seedbotInitialGreetingInput = document.getElementById('seedbot_initial_greeting');
                const seedbotSubsequentGreetingInput = document.getElementById('seedbot_subsequent_greeting');
                const seedbotStartStatusInput = document.getElementById('seedGPTBotStatus');
                const seedbotDisclaimerSettingInput = document.getElementById('seedbot_disclaimer_setting');
                // New options for max tokens and width - Ver 1.4.2
                const seedbotMaxTokensSettingInput = document.getElementById('seedbot_max_tokens_setting');
                const seedbotWidthSettingInput = document.getElementById('seedbot_width_setting');

                // Update the local storage with the input values, if inputs exist
                if(seedbotNameInput) localStorage.setItem('seedbot_bot_name', seedbotNameInput.value);
                if(seedbotInitialGreetingInput) localStorage.setItem('seedbot_initial_greeting', seedbotInitialGreetingInput.value);
                if(seedbotSubsequentGreetingInput) localStorage.setItem('seedbot_subsequent_greeting', seedbotSubsequentGreetingInput.value);
                if(seedbotStartStatusInput) localStorage.setItem('seedGPTBotStatus', seedbotStartStatusInput.value);
                if(seedbotDisclaimerSettingInput) localStorage.setItem('seedbot_disclaimer_setting', seedbotDisclaimerSettingInput.value);
                if(seedbotMaxTokensSettingInput) localStorage.setItem('seedbot_max_tokens_setting', seedbotMaxTokensSettingInput.value);
                if(seedbotWidthSettingInput) localStorage.setItem('seedbot_width_setting', seedbotWidthSettingInput.value);
            });
        }
    });
</script>


        <h2 class="nav-tab-wrapper">
            <a href="?page=seedbot&tab=api_model" class="nav-tab <?php echo $active_tab == 'api_model' ? 'nav-tab-active' : ''; ?>">API/Model</a>
            <a href="?page=seedbot&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
            <!-- Coming Soon in Ver 2.0.0 -->
            <!-- <a href="?page=seedbot&tab=premium" class="nav-tab <?php echo $active_tab == 'premium' ? 'nav-tab-active' : ''; ?>">Premium</a> -->
            <a href="?page=seedbot&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Support</a>
        </h2>

        <!-- Updated id - Ver 1.4.1 -->
        <form id="seedbot-settings-form" action="options.php" method="post">
            <?php
            if ($active_tab == 'settings') {
                settings_fields('seedbot_gpt4_settings');
                do_settings_sections('seedbot_gpt4_settings');
            } elseif ($active_tab == 'api_model') {
                settings_fields('seedbot_gpt4_api_model');
                do_settings_sections('seedbot_gpt4_api_model');
            // Coming Soon in Ver 2.0.0
            // } elseif ($active_tab == 'premium') {
            //     settings_fields('seedbot_gpt4_premium');
            //     do_settings_sections('seedbot_gpt4_premium');
            } elseif ($active_tab == 'support') {
                settings_fields('seedbot_gpt4_support');
                do_settings_sections('seedbot_gpt4_support');
            }
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <!-- Added closing tags for body and html - Ver 1.4.1 -->
    </body>
    </html>
    <?php
}

// Register settings
function seedbot_gpt4_settings_init()
{

    // API/Model settings tab - Ver 1.3.0
    register_setting('seedbot_gpt4_api_model', 'seedbot_api_key');
    register_setting('seedbot_gpt4_api_model', 'seedbot_model_choice');
    // Max Tokens setting options - Ver 1.4.2
    register_setting('seedbot_gpt4_api_model', 'seedbot_max_tokens_setting');

    add_settings_section(
        'seedbot_gpt4_api_model_section',
        'API/Model Settings',
        'seedbot_gpt4_api_model_section_callback',
        'seedbot_gpt4_api_model'
    );

    add_settings_field(
        'seedbot_api_key',
        'SeedBot GPT4 API Key',
        'seedbot_gpt4_api_key_callback',
        'seedbot_gpt4_api_model',
        'seedbot_gpt4_api_model_section'
    );

    add_settings_field(
        'seedbot_model_choice',
        'SeedBot GPT4 Model Choice',
        'seedbot_gpt4_model_choice_callback',
        'seedbot_gpt4_api_model',
        'seedbot_gpt4_api_model_section'
    );
    
    // Setting to adjust in small increments the number of Max Tokens - Ver 1.4.2
    add_settings_field(
        'seedbot_max_tokens_setting',
        'SeedBot GPT4 Maximum Tokens Setting',
        'seedbot_gpt4_max_tokens_setting_callback',
        'seedbot_gpt4_api_model',
        'seedbot_gpt4_api_model_section'
    );


    // Settings settings tab - Ver 1.3.0
    register_setting('seedbot_gpt4_settings', 'seedbot_bot_name');
    register_setting('seedbot_gpt4_settings', 'seedGPTBotStatus');
    register_setting('seedbot_gpt4_settings', 'seedbot_initial_greeting');
    register_setting('seedbot_gpt4_settings', 'seedbot_subsequent_greeting');
    // Option to remove the OpenAI disclaimer - Ver 1.4.1
    register_setting('seedbot_gpt4_settings', 'seedbot_disclaimer_setting');
    // Option to select narrow or wide chatboat - Ver 1.4.2
    register_setting('seedbot_gpt4_settings', 'seedbot_width_setting');

    add_settings_section(
        'seedbot_gpt4_settings_section',
        'Settings',
        'seedbot_gpt4_settings_section_callback',
        'seedbot_gpt4_settings'
    );

    add_settings_field(
        'seedbot_bot_name',
        'Bot Name',
        'seedbot_gpt4_bot_name_callback',
        'seedbot_gpt4_settings',
        'seedbot_gpt4_settings_section'
    );

    add_settings_field(
        'seedGPTBotStatus',
        'Start Status',
        'seedbot_gpt4_seedGPTBotStatus_callback',
        'seedbot_gpt4_settings',
        'seedbot_gpt4_settings_section'
    );

    add_settings_field(
        'seedbot_initial_greeting',
        'Initial Greeting',
        'seedbot_gpt4_initial_greeting_callback',
        'seedbot_gpt4_settings',
        'seedbot_gpt4_settings_section'
    );

    add_settings_field(
        'seedbot_subsequent_greeting',
        'Subsequent Greeting',
        'seedbot_gpt4_subsequent_greeting_callback',
        'seedbot_gpt4_settings',
        'seedbot_gpt4_settings_section'
    );

    // Option to remove the OpenAI disclaimer - Ver 1.4.1
    add_settings_field(
        'seedbot_disclaimer_setting',
        'Include "As an AI language model" disclaimer',
        'seedbot_disclaimer_setting_callback',
        'seedbot_gpt4_settings',
        'seedbot_gpt4_settings_section'
    );

    // Option to change the width of the bot from narrow to wide - Ver 1.4.2
    add_settings_field(
        'seedbot_width_setting',
        'Chatbot Width Setting',
        'seedbot_width_setting_callback',
        'seedbot_gpt4_settings',
        'seedbot_gpt4_settings_section'
    );

    // Premium settings tab - Ver 1.3.0
    register_setting('seedbot_gpt4_premium', 'seedbot_premium_key');

    add_settings_section(
        'seedbot_gpt4_premium_section',
        'Premium Settings',
        'seedbot_gpt4_premium_section_callback',
        'seedbot_gpt4_premium'
    );

    add_settings_field(
        'seedbot_premium_key',
        'Premium Options',
        'seedbot_gpt4_premium_key_callback',
        'seedbot_gpt4_premium',
        'seedbot_gpt4_premium_section'
    );

    // Support settings tab - Ver 1.3.0
    register_setting('seedbot_gpt4_support', 'seedbot_support_key');

    add_settings_section(
        'seedbot_gpt4_support_section',
        'Support',
        'seedbot_gpt4_support_section_callback',
        'seedbot_gpt4_support'
    );
        
}

add_action('admin_init', 'seedbot_gpt4_settings_init');

// API/Model settings section callback - Ver 1.3.0
function seedbot_gpt4_api_model_section_callback($args)
{
    ?>
    <p>Configure settings for the SeedBot GPT4 plugin by adding your API key and selection the GPT model of your choice.</p>
    <p>This plugin requires an API key from OpenAI to function. You can obtain an API key by signing up at <a href="https://platform.openai.com/account/api-keys" target="_blank">https://platform.openai.com/account/api-keys</a>.</p>
    <p>More information about SeedBot GPT4 models and their capability can be found at <a href="https://platform.openai.com/docs/models/overview" taget="_blank">https://platform.openai.com/docs/models/overview</a>.</p>
    <p>Enter your SeedBot GPT4 API key below and select the OpenAI model of your choice.</p>
    <?php
}

// Settings section callback - Ver 1.3.0
function seedbot_gpt4_settings_section_callback($args)
{
    ?>
    <p>Configure settings for the SeedBot GPT4 plugin, including the bot name, start status, and greetings.</p>
    <?php
}

// Premium settings section callback - Ver 1.3.0
function seedbot_gpt4_premium_section_callback($args)
{
    ?>
    <p>Enter your premium key here.</p>
    <?php
}

// Support settings section callback - Ver 1.3.0
function seedbot_gpt4_support_section_callback($args)
{
    ?>
    <div>
	<h3>Description</h3>
    <p>SeedBot GPT4 for WordPress is a plugin that allows you to effortlessly integrate OpenAI&#8217;s GPT-4 model into your website, providing a powerful, AI-driven chatbot for enhanced user experience and personalized support.</p>
    <p>SeedBot GPT4 is a conversational AI platform that uses natural language processing and machine learning algorithms to interact with users in a human-like manner. It is designed to answer questions, provide suggestions, and engage in conversations with users. SeedBot GPT4 is important because it can provide assistance and support to people who need it, especially in situations where human support is not available or is limited. It can also be used to automate customer service, reduce response times, and improve customer satisfaction.</p>
    <p>SeedBot GPT4 leverages the OpenAI platform using the GPT-4 model brings it to life within your WordPress Website.</p>
    <p><b>Important Note:</b> This plugin requires an API key from OpenAI to function correctly. You can obtain an API key by signing up at <a href="https://platform.openai.com/account/api-keys" rel="nofollow ugc" target="_blank">https://platform.openai.com/account/api-keys</a>.<p>
    </div>
    <?php
}

// API key field callback
function seedbot_gpt4_api_key_callback($args)
{
    $api_key = esc_attr(get_option('seedbot_api_key'));
    ?>
    <input type="text" id="seedbot_api_key" name="seedbot_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
    <?php
}

// Model choice
function seedbot_gpt4_model_choice_callback($args)
{
    // Get the saved seedbot_model_choice value or default to "gpt-3.5-turbo"
    $model_choice = esc_attr(get_option('seedbot_model_choice', 'gpt-3.5-turbo'));
    ?>
    <select id="seedbot_model_choice" name="seedbot_model_choice">
        <!-- Allow for gpt-4 in Ver 1.4.2 -->
        <option value="<?php echo esc_attr('gpt-4'); ?>" <?php selected($model_choice, 'gpt-4'); ?>><?php echo esc_html('gpt-4'); ?></option>
        <option value="<?php echo esc_attr('gpt-3.5-turbo'); ?>" <?php selected($model_choice, 'gpt-3.5-turbo'); ?>><?php echo esc_html('gpt-3.5-turbo'); ?></option>
    </select>
    <?php
}

// SeedBot GPT4 Name
function seedbot_gpt4_bot_name_callback($args)
{
    $bot_name = esc_attr(get_option('seedbot_bot_name', 'SeedBot GPT4'));
    // seedebug_to_console($bot_name);
?>
    <input type="text" id="seedbot_bot_name" name="seedbot_bot_name" value="<?php echo esc_attr($bot_name); ?>" class="regular-text">
    <?php
    
	seedebug_to_console('name loaded in settings');

}

function seedbot_gpt4_seedGPTBotStatus_callback($args)
{
    $start_status = esc_attr(get_option('seedGPTBotStatus', 'closed'));
    ?>
    <select id="seedGPTBotStatus" name="seedGPTBotStatus">
        <option value="open" <?php selected($start_status, 'open'); ?>><?php echo esc_html('Open'); ?></option>
        <option value="closed" <?php selected($start_status, 'closed'); ?>><?php echo esc_html('Closed'); ?></option>
    </select>
    <?php
}

function seedbot_gpt4_initial_greeting_callback($args)
{
    $initial_greeting = esc_attr(get_option('seedbot_initial_greeting', 'Hello! How can I help you today?'));
    ?>
    <textarea id="seedbot_initial_greeting" name="seedbot_initial_greeting" rows="2" cols="50"><?php echo esc_textarea($initial_greeting); ?></textarea>
    <?php
}

function seedbot_gpt4_subsequent_greeting_callback($args)
{
    $subsequent_greeting = esc_attr(get_option('seedbot_subsequent_greeting', 'Hello again! How can I help you?'));
    ?>
    <textarea id="seedbot_subsequent_greeting" name="seedbot_subsequent_greeting" rows="2" cols="50"><?php echo esc_textarea($subsequent_greeting); ?></textarea>
    <?php
}

// Option to remove SeedBot GPT4 disclaimer - Ver 1.4.1
function seedbot_disclaimer_setting_callback($args)
{
    $seedbot_disclaimer_setting = esc_attr(get_option('seedbot_disclaimer_setting', 'Yes'));
    ?>
    <select id="seedbot_disclaimer_setting" name="seedbot_disclaimer_setting">
        <option value="Yes" <?php selected($seedbot_disclaimer_setting, 'Yes'); ?>><?php echo esc_html('Yes'); ?></option>
        <option value="No" <?php selected($seedbot_disclaimer_setting, 'No'); ?>><?php echo esc_html('No'); ?></option>
    </select>
    <?php
}

// Max Tokens choice - Ver 1.4.2
function seedbot_gpt4_max_tokens_setting_callback($args) {
    // Get the saved chatgpt_max_tokens_setting or default to 150
    $max_tokens = esc_attr(get_option('seedbot_gpt4_max_tokens_setting', '150'));
    ?>
    <select id="seedbot_gpt4_max_tokens_setting" name="seedbot_gpt4_max_tokens_setting">
        <option value="<?php echo esc_attr( '100' ); ?>" <?php selected( $max_tokens, '100' ); ?>><?php echo esc_html( '100' ); ?></option>
        <option value="<?php echo esc_attr( '150' ); ?>" <?php selected( $max_tokens, '150' ); ?>><?php echo esc_html( '150' ); ?></option>
        <option value="<?php echo esc_attr( '200' ); ?>" <?php selected( $max_tokens, '200' ); ?>><?php echo esc_html( '200' ); ?></option>
        <option value="<?php echo esc_attr( '250' ); ?>" <?php selected( $max_tokens, '250' ); ?>><?php echo esc_html( '250' ); ?></option>
        <option value="<?php echo esc_attr( '300' ); ?>" <?php selected( $max_tokens, '300' ); ?>><?php echo esc_html( '300' ); ?></option>
        <option value="<?php echo esc_attr( '350' ); ?>" <?php selected( $max_tokens, '350' ); ?>><?php echo esc_html( '350' ); ?></option>
        <option value="<?php echo esc_attr( '400' ); ?>" <?php selected( $max_tokens, '400' ); ?>><?php echo esc_html( '400' ); ?></option>
        <option value="<?php echo esc_attr( '450' ); ?>" <?php selected( $max_tokens, '450' ); ?>><?php echo esc_html( '450' ); ?></option>
        <option value="<?php echo esc_attr( '500' ); ?>" <?php selected( $max_tokens, '500' ); ?>><?php echo esc_html( '500' ); ?></option>
    </select>
    <?php
}

// Option to change the width of the bot from narrow to wide - Ver 1.4.2
function seedbot_width_setting_callback($args)
{
    $seedbot_width_setting = esc_attr(get_option('seedbot_width_setting', 'narrow'));
    ?>
    <select id="seedbot_width_setting" name="seedbot_width_setting">
        <option value="narrow" <?php selected($seedbot_width_setting, 'narrow'); ?>><?php echo esc_html('Narrow'); ?></option>
        <option value="wide" <?php selected($seedbot_width_setting, 'wide'); ?>><?php echo esc_html('Wide'); ?></option>
    </select>
    <?php
}

// Premium Key - Ver 1.3.0
function seedbot_gpt4_premium_key_callback($args) {
    $premium_key = esc_attr(get_option('seedbot_premium_key'));
    ?>
    <input type="text" id="seedbot_premium_key" name="seedbot_premium_key" value="<?php echo esc_attr( $premium_key ); ?>" class="regular-text">
    <?php
}

//update