<?php
/*
 * Plugin Name: SeedBot GPT4
 * Plugin URI:  https://github.com/qev254/seedbot-gpt4
 * Description: A simple plugin to add a SeedBot GPT4 chatbot to your WordPress Website.
 * Version:     1.0.0
 * Author:      joxdigital.com
 * Author URI:  https://www.joxdigital.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *  
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * You should have received a copy of the GNU General Public License
 * along with SeedBot GPT4. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 * 
 */

// If this file is called directly, die.
defined('WPINC') || die;

// If this file is called directly, die.
if (!defined('ABSPATH')) {
    die();
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/seedbot-gpt4-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/seedbot-gpt4-shortcode.php';

// Diagnostics On or Off - Ver 1.4.2
update_option('seedbot_diagnostics', 'Off');

// Enqueue plugin scripts and styles
function seedbot_gpt4_enqueue_scripts()
{
    // Ensure the Dashicons font is properly enqueued - Ver 1.1.0
    wp_enqueue_style('dashicons');
    wp_enqueue_style('seedbot-gpt4-css', plugins_url('assets/css/seedbot-gpt4.css', __FILE__));
    wp_enqueue_script('seedbot-gpt4-js', plugins_url('assets/js/seedbot-gpt4.js', __FILE__), array('jquery'), '1.0', true);

    // Ver 1.4.1
    // Enqueue the seedbot-gpt4-local.js file
    wp_enqueue_script('seedbot-gpt4-local', plugins_url('assets/js/seedbot-gpt4-local.js', __FILE__), array('jquery'), '1.0', true);
    $seedbot_settings = array(
        'seedbot_bot_name' => esc_attr(get_option('seedbot_bot_name')),
        'seedbot_initial_greeting' => esc_attr(get_option('seedbot_initial_greeting')),
        'seedbot_subsequent_greeting' => esc_attr(get_option('seedbot_subsequent_greeting')),
        'seedGPTBotStatus' => esc_attr(get_option('seedGPTBotStatus')),
        'seedbot_disclaimer_setting' => esc_attr(get_option('seedbot_disclaimer_setting')),
        'seedbot_max_tokens_setting' => esc_attr(get_option('seedbot_max_tokens_setting')),
        'seedbot_width_setting' => esc_attr(get_option('seedbot_width_setting')),
    );
    wp_localize_script('seedbot-gpt4-local', 'seedbotSettings', $seedbot_settings);

    wp_localize_script('seedbot-gpt4-js', 'seedbot_gpt4_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'api_key' => esc_attr(get_option('seedbot_api_key')),
    ));
    echo "<script>console.log('{$seedbot_settings[0]}' );</script>";
    
    // echo "<script>console.log('tesooot' );</script>";
}
add_action('wp_enqueue_scripts', 'seedbot_gpt4_enqueue_scripts');

// Handle Ajax requests
function seedbot_gpt4_send_message()
{
    // Get the save API key
    $api_key = esc_attr(get_option('seedbot_api_key'));
    // Get the saved model from the settings or default to gpt-3.5-turbo
    $model = esc_attr(get_option('seedbot_model_choice', 'gpt-3.5-turbo'));
    // Max tokens - Ver 1.4.2
    $max_tokens = esc_attr(get_option('seedbot_max_tokens_setting', 150));
    // Send only clean text via the API
    $message = sanitize_text_field($_POST['message']);

    // Check API key and message
    if (!$api_key || !$message) {
        wp_send_json_error('Invalid API key or message');
    }

    // Send message to SeedBot GPT4 API
    $response = seedbot_gpt4_call_api($api_key, $message);

    // Return response
    wp_send_json_success($response);
}

// Add link to seedbot-gpt4 options - setting page
function seedbot_gpt4_plugin_action_links($links)
{
    $settings_link = '<a href="../wp-admin/options-general.php?page=seedbot-gpt4">' . __('Settings', 'seedbot-gpt4') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_action('wp_ajax_seedbot_gpt4_send_message', 'seedbot_gpt4_send_message');
add_action('wp_ajax_nopriv_seedbot_gpt4_send_message', 'seedbot_gpt4_send_message');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'seedbot_gpt4_plugin_action_links');

// Call the SeedBot GPT4 API
function seedbot_gpt4_call_api($api_key, $message)
{
    // Diagnostics = Ver 1.4.2
    $seedbot_diagnostics = esc_attr(get_option('seedbot_diagnostics', 'Off'));

    // The current SeedBot GPT4 API URL endpoint for gpt-3.5-turbo and gpt-4
    $api_url = 'https://api.openai.com/v1/chat/completions';

    $headers = array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type' => 'application/json',
    );

    // Select the OpenAI Model
    // Get the saved model from the settings or default to "gpt-3.5-turbo"
    $model = esc_attr(get_option('seedbot_model_choice', 'gpt-3.5-turbo'));
    // Max tokens - Ver 1.4.2
    $max_tokens = intval(esc_attr(get_option('seedbot_max_tokens_setting', '150')));

    $body = array(
        'model' => $model,
        'max_tokens' => $max_tokens,
        'temperature' => 0.5,

        'messages' => array(array('role' => 'user', 'content' => $message)),
    );

    $args = array(
        'headers' => $headers,
        'body' => json_encode($body),
        'method' => 'POST',
        'data_format' => 'body',
        'timeout' => 50, // Increase the timeout values to 15 seconds to wait just a bit longer for a response from the engine
    );

    $response = wp_remote_post($api_url, $args);

    // Handle any errors that are returned from the chat engine
    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message() . ' Please check Settings for a valid API key or your OpenAI account for additional information.';
    }

    // Return json_decode(wp_remote_retrieve_body($response), true);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($response_body['choices']) && !empty($response_body['choices'])) {
        // Handle the response from the chat engine
        return $response_body['choices'][0]['message']['content'];
    } else {
        // Handle any errors that are returned from the chat engine
        return 'Error: Unable to fetch response from SeedBot GPT4 API. Please check Settings for a valid API key or your OpenAI account for additional information.';
    }
}

function enqueue_greetings_script()
{
    wp_enqueue_script('greetings', plugin_dir_url(__FILE__) . 'assets/js/greetings.js', array('jquery'), null, true);

    $greetings = array(
        'initial_greeting' => esc_attr(get_option('seedbot_gpt4_initial_greeting', 'Hello! How can I help you today?')),
        'subsequent_greeting' => esc_attr(get_option('seedbot_gpt4_subsequent_greeting', 'Hello again! How can I help you?')),
    );

    wp_localize_script('greetings', 'greetings_data', $greetings);
}
add_action('wp_enqueue_scripts', 'enqueue_greetings_script');
