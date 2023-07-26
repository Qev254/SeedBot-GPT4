jQuery(document).ready(function ($) {

    function seedbot_gpt4_localize() {
        // Access the variables passed from PHP using the seedbotSettings object - Ver 1.4.1
        var seedbotName = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedbot_bot_name) ? seedbotSettings.seedbot_bot_name : 'SeedBot GPT4';
        var seedbotInitialGreeting = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedbot_initial_greeting) ? seedbotSettings.seedbot_initial_greeting : 'Hello! How can I help you today?';
        var seedbotSubsequentGreeting = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedbot_subsequent_greeting) ? seedbotSettings.seedbot_subsequent_greeting : 'Hello again! How can I help you?';
        var seedbotStartStatus = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedGPTBotStatus) ? seedbotSettings.seedGPTBotStatus : 'closed';
        var seedbotDisclaimerSetting = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedbot_disclaimer_setting) ? seedbotSettings.seedbot_disclaimer_setting : 'Yes';
        var seedbotMaxTokensSetting = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedbot_max_tokens_setting) ? seedbotSettings.seedbot_max_tokens_setting : '150';
        var seedbotWidthSetting = (typeof seedbotSettings !== 'undefined' && seedbotSettings.seedbot_width_setting) ? seedbotSettings.seedbot_width_setting : 'Narrow';

        // Get the input elements
        var seedbotNameInput = document.getElementById('seedbot_bot_name');
        var seedbotInitialGreetingInput = document.getElementById('seedbot_initial_greeting');
        var seedbotSubsequentGreetingInput = document.getElementById('seedbot_subsequent_greeting');
        var seedbotStartStatusInput = document.getElementById('seedGPTBotStatus');
        var seedbotDisclaimerSettingInput = document.getElementById('seedbot_disclaimer_setting');
        var seedbotMaxTokensSettingInput = document.getElementById('seedbot_max_tokens_setting');
        var seedbotWidthSettingInput = document.getElementById('seedbot_width_setting');

        if (seedbotNameInput) {
            seedbotNameInput.addEventListener('change', function () {
                localStorage.setItem('seedbot_bot_name', this.value);
            });
        }

        if (seedbotInitialGreetingInput) {
            seedbotInitialGreetingInput.addEventListener('change', function () {
                localStorage.setItem('seedbot_initial_greeting', this.value);
            });
        }

        if (seedbotSubsequentGreetingInput) {
            seedbotSubsequentGreetingInput.addEventListener('change', function () {
                localStorage.setItem('seedbot_subsequent_greeting', this.value);
            });
        }

        if (seedbotStartStatusInput) {
            seedbotStartStatusInput.addEventListener('change', function () {
                localStorage.setItem('seedGPTBotStatus', this.options[this.selectedIndex].value);
            });
        }

        if (seedbotDisclaimerSettingInput) {
            seedbotDisclaimerSettingInput.addEventListener('change', function () {
                localStorage.setItem('seedbot_disclaimer_setting', this.options[this.selectedIndex].value);
            });
        }

        if (seedbotMaxTokensSettingInput) {
            seedbotMaxTokensSettingInput.addEventListener('change', function () {
                localStorage.setItem('seedbot_max_tokens_setting', this.options[this.selectedIndex].value);
            });
        }

        if (seedbotWidthSettingInput) {
            seedbotWidthSettingInput.addEventListener('change', function () {
                localStorage.setItem('seedbot_width_setting', this.options[this.selectedIndex].value);
            });
        }

        // Update the localStorage values when the form is submitted - Ver 1.4.1
        // seedbot-settings-form vs. your-form-id
        var seedbotSettingsForm = document.getElementById('seedbot-settings-form');

        if (seedbotSettingsForm) {
            seedbotSettingsForm.addEventListener('submit', function (event) {

                event.preventDefault(); // Prevent form submission

                const seedbotNameInput = document.getElementById('seedbot_bot_name');
                const seedbotInitialGreetingInput = document.getElementById('seedbot_initial_greeting');
                const seedbotSubsequentGreetingInput = document.getElementById('seedbot_subsequent_greeting');
                const seedbotStartStatusInput = document.getElementById('seedGPTBotStatus');
                const seedbotDisclaimerSettingInput = document.getElementById('seedbot_disclaimer_setting');
                const seedbotMaxTokensSettingInput = document.getElementById('seedbot_max_tokens_setting');
                const seedbotWidthSettingInput = document.getElementById('seedbot_width_setting');

                if (seedbotNameInput) {
                    localStorage.setItem('seedbot_bot_name', seedbotNameInput.value);
                }

                if (seedbotInitialGreetingInput) {
                    localStorage.setItem('seedbot_initial_greeting', seedbotInitialGreetingInput.value);
                }

                if (seedbotSubsequentGreetingInput) {
                    localStorage.setItem('seedbot_subsequent_greeting', seedbotSubsequentGreetingInput.value);
                }

                if (seedbotStartStatusInput) {
                    localStorage.setItem('seedGPTBotStatus', seedbotStartStatusInput.value);
                }

                if (seedbotDisclaimerSettingInput) {
                    localStorage.setItem('seedbot_disclaimer_setting', seedbotDisclaimerSettingInput.value);
                }

                if (seedbotMaxTokensSettingInput) {
                    localStorage.setItem('seedbot_max_tokens_setting', seedbotMaxTokensSettingInput.value);
                }

                if (seedbotWidthSettingInput) {
                    localStorage.setItem('seedbot_width_setting', seedbotWidthSettingInput.value)
                }

            });
        }
    }

    seedbot_gpt4_localize();

    console.log ("test");

});
