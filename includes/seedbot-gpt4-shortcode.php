<?php
/**
 * SeedBot GPT-4 for WordPress - Shortcode Registration
 *
 * This file contains the code for registering the shortcode used
 * to display the SeedBot GPT-4 on the website.
 *
 * @package seedbot-gpt4
 */

function seedbot_gpt4_shortcode() {
    // Retrieve the bot name - Ver 1.0.0
    // Add styling to the bot to ensure that it is not shown before it is needed Ver 1.2.0
    $bot_name = esc_attr(get_option('seedbot_gpt4_bot_name', 'SeedBot GPT-4'));

    ob_start();
    ?>

	<script> 
		const myArr = ["Orange", "Banana", "Mango", "Kiwi"];
		console.log(myArr); 
				console.log($bot_name); 

</script>
    <div id="seedbot-gpt4" style="display:flex ;">
        <div id="seedbot-gpt4-header">
            <div id="seedbotTitle" class="title"><?php echo $bot_name; ?></div>
        </div>
        <div id="seedbot-gpt4-conversation"></div>
        <div id="seedbot-gpt4-input">
            <input type="text" id="seedbot-gpt4-message" placeholder="<?php echo esc_attr( 'Type your message...' ); ?>">
            <button id="seedbot-gpt4-submit">Send</button>
        </div>
    </div>
    <button id="seedbot-gpt4-open-btn">
        <i class="dashicons dashicons-format-chat"></i>
    </button>
    <?php
    return ob_get_clean();
}
add_shortcode('seedbot_gpt4', 'seedbot_gpt4_shortcode');

seedebug_to_console('see how far');
//update