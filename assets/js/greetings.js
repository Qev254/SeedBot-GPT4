jQuery(document).ready(function ($) {
    // Define your greetings_data object with initial and subsequent greetings
    var greetings_data = {
        initial_greeting: 'Hello y! How can I assist you today?',
        subsequent_greeting: 'Hello again! How can I help you?',
    };

    // Store the greetings in the local storage
    localStorage.setItem('seedbot_gpt4_initial_greeting', greetings_data.initial_greeting);
    localStorage.setItem('seedbot_gpt4_subsequent_greeting', greetings_data.subsequent_greeting);
});
//update