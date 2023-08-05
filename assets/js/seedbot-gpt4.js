jQuery(document).ready(function ($) {

    // Logging for Diagnostics - Ver 1.4.2
    var seedbot_gpt4_diagnostics = localStorage.getItem('seedbot_gpt4_diagnostics') || 'On';
    localStorage.setItem('seedbot_gpt4_diagnostics', seedbot_gpt4_diagnostics); // Set if not set

    var seedbot_gpt4_messageInput = $('#seedbot-gpt4-message');
    var seedbot_gpt4_conversation = $('#seedbot-gpt4-conversation');
    var seedbot_gpt4_submitButton = $('#seedbot-gpt4-submit');

    // Set bot width with the default Narrow or from setting Wide - Ver 1.4.2
    var seedbot_gpt4_width_setting = localStorage.getItem('seedbot_gpt4_width_setting') || 'Narrow';

    var seedbot_gpt4 = $('#seedbot-gpt4'); // Updated variable name
    if (seedbot_gpt4_width_setting === 'Wide') {
        seedbot_gpt4.addClass('wide'); // Updated variable name
    } else {
        seedbot_gpt4.removeClass('wide'); // Updated variable name
    }

    // For Diagnostics Logs
    if (seedbotSettings.seedbot_gpt4_diagnostics === 'On') { // Consider updating this line as well
        console.log('FUNCTION: seedbot-gpt4.js');
        console.log('seedbot_gpt4_messageInput: ' + seedbot_gpt4_messageInput);
        console.log('seedbot_gpt4_conversation' + seedbot_gpt4_conversation);
        console.log('seedbot_gpt4_submitButton: ' + seedbot_gpt4_submitButton);
        console.log('seedbot_gpt4: ' + seedbot_gpt4); // Updated variable name
        console.log('seedbot_gpt4_width_setting: ' + seedbot_gpt4_width_setting);
    }

    var seedbot_gpt4_OpenButton = $('#seedbot-gpt4-open-btn');
    // Use 'open' for an open chatbot or 'closed' for a closed chatbot - Ver 1.1.0
    var seedbot_gpt4_start_status = 'closed';

    // Initially hide the chatbot - Ver 1.1.0
    seedbot_gpt4.hide(); // Updated variable name
    seedbot_gpt4_OpenButton.show();

    var seedbot_gpt4_Container = $('<div></div>').addClass('seedbot-gpt4-container');
    var seedbot_gpt4_CollapseBtn = $('<button></button>').addClass('seedbot-gpt4-collapse-btn').addClass('dashicons dashicons-format-chat'); // Add a collapse button
    var seedbot_gpt4_Collapsed = $('<div></div>').addClass('seedbot-gpt4-collapsed'); // Add a collapsed chatbot icon dashicons-format-chat f125

    // Support variable greetings based on setting - Ver 1.1.0
    var seedbot_gpt4_initialGreeting = localStorage.getItem('seedbot_gpt4_initial_greeting') || 'Hello! How can I help you today?';
    localStorage.setItem('seedbot_gpt4_initial_greeting', seedbot_gpt4_initialGreeting);
    var seedbot_gpt4_subsequentGreeting = localStorage.getItem('seedbot_gpt4_subsequent_greeting') || 'Hello again! How can I help you?';
    localStorage.setItem('seedbot_gpt4_subsequent_greeting', seedbot_gpt4_subsequentGreeting);
    // Handle disclaimer - Ver 1.4.1
    var seedbot_gpt4_disclaimer_setting = localStorage.getItem('seedbot_gpt4_disclaimer_setting') || 'Yes';

    // Append the collapse button and collapsed chatbot icon to the chatbot container
    seedbot_gpt4_Container.append(seedbot_gpt4_CollapseBtn);
    seedbot_gpt4_Container.append(seedbot_gpt4_Collapsed);

    // Add initial greeting to the chatbot
    seedbot_gpt4_conversation.append(seedbot_gpt4_Container);

    function seedbot_gpt4_initializeChatbot() {
        var seedbot_gpt4_diagnostics = localStorage.getItem('seedbot_gpt4_diagnostics') || 'Off';
        var seedbot_gpt4_isFirstTime = !localStorage.getItem('seedbot_gpt4_ChatBotOpened');
        var seedbot_gpt4_initialGreeting;
        // Remove any legacy conversations that might be store in local storage for increased privacy - Ver 1.4.2
        localStorage.removeItem('seedbot_gpt4_conversation');

        if (seedbot_gpt4_isFirstTime) {
            seedbot_gpt4_initialGreeting = localStorage.getItem('greetings_data.initial_greeting') || 'Hello! How can I help you today?';

            // Logging for Diagnostics - Ver 1.4.2
            if (seedbot_gpt4_diagnostics === 'On') {
                console.log("seedbot_gpt4_initialGreeting" + seedbot_gpt4_initialGreeting); // Updated variable name
            }
 
            // Don't append thegreeting if it's already in the conversation
            if (seedbot_gpt4_conversation.text().includes(seedbot_gpt4_initialGreeting)) {
                return;
            }

            seedbot_gpt4_appendMessage(seedbot_gpt4_initialGreeting, 'bot', 'initial-greeting');
            localStorage.setItem('seedbot_gpt4_ChatBotOpened', 'true');
            // Save the conversation after the initial greeting is appended - Ver 1.2.0
            sessionStorage.setItem('seedbot_gpt4_conversation', seedbot_gpt4_conversation.html());

        } else {

            seedbot_gpt4_initialGreeting = localStorage.getItem('seedbot_gpt4_subsequent_greeting') || 'Hello again! How can I help you?';

            // Logging for Diagnostics - Ver 1.4.2
            if (seedbot_gpt4_diagnostics === 'On') {
                console.log("initialGreeting" + seedbot_gpt4_initialGreeting); // Updated variable name
            }

            // Don't append the greeting if it's already in the conversation
            if (seedbot_gpt4_conversation.text().includes(seedbot_gpt4_initialGreeting)) {
                return;
            }

            seedbot_gpt4_appendMessage(seedbot_gpt4_initialGreeting, 'bot', 'initial-greeting');
            localStorage.setItem('seedbot_gpt4_ChatBotOpened', 'true');
        }
    }

    // Add chatbot header, body, and other elements - Ver 1.1.0
    var seedbot_gpt4_chatbotHeader = $('<div></div>').addClass('seedbot-header');
    seedbot_gpt4.append(seedbot_gpt4_chatbotHeader);

    // Fix for Ver 1.2.0
    seedbot_gpt4_chatbotHeader.append(seedbot_gpt4_CollapseBtn);
    seedbot_gpt4_chatbotHeader.append(seedbot_gpt4_Collapsed);

    // Attach the click event listeners for the collapse button and collapsed chatbot icon
    seedbot_gpt4_CollapseBtn.on('click', seedbot_gpt4_toggleChatbot);
    seedbot_gpt4_Collapsed.on('click', seedbot_gpt4_toggleChatbot);
    seedbot_gpt4_OpenButton.on('click', seedbot_gpt4_toggleChatbot);

    function seedbot_gpt4_appendMessage(message, sender, cssClass) {
        var messageElement = $('<div></div>').addClass('chat-message');
        var textElement = $('<span></span>').text(message);

        // Add initial greetings if first time
        if (cssClass) {
            textElement.addClass(cssClass);
        }

        if (sender === 'user') {
            messageElement.addClass('user-message');
            textElement.addClass('user-text');
        } else if (sender === 'bot') {
            messageElement.addClass('bot-message');
            textElement.addClass('bot-text');
        } else {
            messageElement.addClass('error-message');
            textElement.addClass('error-text');
        }

        messageElement.append(textElement);
        seedbot_gpt4_conversation.append(messageElement);

        // Add space between user input and bot response
        if (sender === 'user' || sender === 'bot') {
            var spaceElement = $('<div></div>').addClass('message-space');
            seedbot_gpt4_conversation.append(spaceElement);
        }

        // Ver 1.2.4
        // seedbot_gpt4_conversation.scrollTop(seedbot_gpt4_conversation[0].scrollHeight);
        seedbot_gpt4_conversation[0].scrollTop = seedbot_gpt4_conversation[0].scrollHeight;

        // Save the conversation locally between bot sessions - Ver 1.2.0
        sessionStorage.setItem('seedbot_gpt4_conversation', seedbot_gpt4_conversation.html());

    }

    function seedbot_gpt4_showTypingIndicator() {
        var typingIndicator = $('<div></div>').addClass('typing-indicator');
        var dot1 = $('<span>.</span>').addClass('typing-dot');
        var dot2 = $('<span>.</span>').addClass('typing-dot');
        var dot3 = $('<span>.</span>').addClass('typing-dot');
        
        typingIndicator.append(dot1, dot2, dot3);
        seedbot_gpt4_conversation.append(typingIndicator);
        seedbot_gpt4_conversation.scrollTop(seedbot_gpt4_conversation[0].scrollHeight);
    }

    function seedbot_gpt4_removeTypingIndicator() {
        $('.typing-indicator').remove();
    }

    seedbot_gpt4_submitButton.on('click', function () {
        var message = seedbot_gpt4_messageInput.val().trim();
        var seedbot_gpt4_disclaimer_setting = localStorage.getItem('seedbot_gpt4_disclaimer_setting') || 'Yes';

        if (!message) {
            return;
        }
            
        seedbot_gpt4_messageInput.val('');
        seedbot_gpt4_appendMessage(message, 'user');
        console.log(message);

        $.ajax({
            url: seedbot_gpt4_params.ajax_url,
            method: 'POST',
            data: {
                action: 'seedbot_gpt4_send_message',
                message: message,
            },
            beforeSend: function () {
                seedbot_gpt4_showTypingIndicator();
                seedbot_gpt4_submitButton.prop('disabled', true);
            },
            success: function (response) {
                seedbot_gpt4_removeTypingIndicator();
                if (response.success) {
                    let botResponse = response.data;
                    const prefix_a = "As an AI language model, ";
                    const prefix_b = "I am an AI language model and ";

                    if (botResponse.startsWith(prefix_a) && seedbot_gpt4_disclaimer_setting === 'No') {
                        botResponse = botResponse.slice(prefix_a.length);
                    } else if (botResponse.startsWith(prefix_b) && seedbot_gpt4_disclaimer_setting === 'No') {
                        botResponse = botResponse.slice(prefix_b.length);
                    }
                                    
                    seedbot_gpt4_appendMessage(botResponse, 'bot');
                } else {
                    seedbot_gpt4_appendMessage('Error: ' + response.data, 'error');
                }
            },
            error: function () {
                seedbot_gpt4_removeTypingIndicator();
                seedbot_gpt4_appendMessage('Error: Unable to send message', 'error');
            },
            complete: function () {
                seedbot_gpt4_removeTypingIndicator();
                seedbot_gpt4_submitButton.prop('disabled', false);
            },
        });
    });

    seedbot_gpt4_messageInput.on('keydown', function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            seedbot_gpt4_submitButton.click();
        }
    });

    // Add the seedbot_gpt4_toggleChatbot() function - Ver 1.1.0
    function seedbot_gpt4_toggleChatbot() {
        if (seedbot_gpt4.is(':visible')) {
            seedbot_gpt4.hide();
            seedbot_gpt4_OpenButton.show();
            localStorage.setItem('seedbot_gpt4_ChatBotStatus', 'closed');
            // Clear the conversation when the chatbot is closed - Ver 1.2.0
            // Keep the conversation when the chatbot is closed - Ver 1.2.4
            // sessionStorage.removeItem('seedbot_gpt4_conversation');
        } else {
            seedbot_gpt4.show();
            seedbot_gpt4_OpenButton.hide();
            localStorage.setItem('seedbot_gpt4_ChatBotStatus', 'open');
            seedbot_gpt4_loadConversation();
            seedbot_gpt4_scrollToBottom();
        }
    }

    // Add this function to maintain the chatbot status across page refreshes and sessions - Ver 1.1.0 and updated for Ver 1.4.1
    function seedbot_gpt4_loadChatbotStatus() {
        const seedbot_gpt4_ChatBotStatus = localStorage.getItem('seedbot_gpt4_ChatBotStatus');
        // const seedbot_gpt4_ChatBotStatus = localStorage.getItem('seedbot_gpt4_start_status');
        
        // If the chatbot status is not set in local storage, use seedbot_gpt4_start_status
        if (seedbot_gpt4_ChatBotStatus === null) {
            if (seedbot_gpt4_start_status === 'closed') {
                seedbot_gpt4.hide();
                seedbot_gpt4_OpenButton.show();
            } else {
                seedbot_gpt4.show();
                seedbot_gpt4_OpenButton.hide();
                // Load the conversation when the chatbot is shown on page load
                seedbot_gpt4_loadConversation();
                seedbot_gpt4_scrollToBottom();
            }
        } else if (seedbot_gpt4_ChatBotStatus === 'closed') {
            if (seedbot_gpt4.is(':visible')) {
                seedbot_gpt4.hide();
                seedbot_gpt4_OpenButton.show();
            }
        } else if (seedbot_gpt4_ChatBotStatus === 'open') {
            if (seedbot_gpt4.is(':hidden')) {
                seedbot_gpt4.show();
                seedbot_gpt4_OpenButton.hide();
                seedbot_gpt4_loadConversation();
                seedbot_gpt4_scrollToBottom();
            }
        }
    }

    // Add this function to scroll to the bottom of the conversation - Ver 1.2.1
    function seedbot_gpt4_scrollToBottom() {
        setTimeout(() => {
            // Logging for Diagnostics - Ver 1.4.2
            if (seedbot_gpt4_diagnostics === 'On') {
                console.log("Scrolling to bottom");
                console.log("Scroll height: " + seedbot_gpt4_conversation[0].scrollHeight);
            }
            seedbot_gpt4_conversation.scrollTop(seedbot_gpt4_conversation[0].scrollHeight);
        }, 100);  // delay of 100 milliseconds    
    }
   
    // Load conversation from local storage if available - Ver 1.2.0
    function seedbot_gpt4_loadConversation() {
        var seedbot_gpt4_storedConversation = sessionStorage.getItem('seedbot_gpt4_conversation');
        if (seedbot_gpt4_storedConversation) {
            seedbot_gpt4_conversation.append(seedbot_gpt4_storedConversation);
            // Use setTimeout to ensure seedbot_gpt4_scrollToBottom is called after the conversation is rendered
            setTimeout(seedbot_gpt4_scrollToBottom, 0);
        } else {
            seedbot_gpt4_initializeChatbot();
        }
    }

    // Call the seedbot_gpt4_loadChatbotStatus function here - Ver 1.1.0
    seedbot_gpt4_loadChatbotStatus(); 

    // Load the conversation when the chatbot is shown on page load - Ver 1.2.0
    // Let the convesation stay persistent in session storage for increased privacy - Ver 1.4.2
    // seedbot_gpt4_loadConversation();

});
