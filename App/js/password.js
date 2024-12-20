import $ from "jquery";

document.addEventListener('DOMContentLoaded', function () {
    initializePasswordValidation();
});

// Password validation
function initializePasswordValidation() {
    const patterns = { //Define the patterns for each requirement
        minLength: /.{6,13}/, //At least 6 and at most 13 characters
        lowercase: /[a-z]/, //At least one lowercase letter
        uppercase: /[A-Z]/, //At least one uppercase letter
        number: /\d/, //At least one number
        special: /[$@$!%*?&-.,]/, //At least one special character
        noSpaces: /^[^\s']+$/ //No spaces or single quotes
    };

    function validatePassword(password) {
        return {
            minLength: patterns.minLength.test(password), //Check if the password has at least 6 and at most 13 characters
            lowercase: patterns.lowercase.test(password), //Check if the password has at least one lowercase letter
            uppercase: patterns.uppercase.test(password), //Check if the password has at least one uppercase letter
            number: patterns.number.test(password), //Check if the password has at least one number
            special: patterns.special.test(password), //Check if the password has at least one special character
            noSpaces: patterns.noSpaces.test(password) //Check if the password has no spaces or single quotes
        };
    }

    function updatePasswordFeedback(results, messageContainer) { //Update the password feedback
        const messages = {
            minLength: 'Entre 6 y 13 caracteres', //Message for the minimum length requirement
            lowercase: 'Al menos una minúscula', //Message for the lowercase requirement
            uppercase: 'Al menos una mayúscula', //Message for the uppercase requirement
            number: 'Al menos un número', //Message for the number requirement
            special: 'Al menos un carácter especial ($@!%*?&-.,)', //Message for the special requirement
            noSpaces: 'Sin espacios ni comillas simples' //Message for the no spaces requirement
        };

        let html = '<ul class="text-sm mt-2">'; //Create the HTML for the password feedback
        for (const [requirement, passed] of Object.entries(results)) { //For each requirement, run the code
            const color = passed ? 'text-green-600' : 'text-red-600'; //Set the color of the requirement
            const icon = passed ? '✔' : '✗'; //Set the icon of the requirement
            html += `<li class="${color}"><span class="mr-2">${icon}</span>${messages[requirement]}</li>`; //Add the requirement to the HTML
        }
        html += '</ul>';

        messageContainer.html(html); //Add the HTML to the message container

        const allPassed = Object.values(results).every(result => result); //Check if all requirements are met
        return allPassed; //Return true if all requirements are met, false otherwise
    }

    function handlePasswordValidation() { //Handle the password validation
        const password = $(this).val(); //Get the password
        const results = validatePassword(password); //Validate the password
        const messageContainer = $(this).siblings('.password-requirements'); //Get the message container

        if (messageContainer.length === 0) { //If the message container doesn't exist, create it
            $(this).after('<div class="password-requirements"></div>'); //Add the message container to the password input
        }

        const allPassed = updatePasswordFeedback(results, $(this).siblings('.password-requirements')); //Update the password feedback

        $(this)
            .removeClass("border-red-500 border-green-500") // Remove previous classes
            .addClass(allPassed ? "border-green-500" : "border-red-500"); // Add class based on result

        $("#btnEnviar").prop("disabled", !allPassed); //Enable or disable the button
    }

    function handleEditPasswordValidation() { //Handle the edit password validation
        const password = $(this).val(); //Get the password
        const messageContainer = $(this).siblings('.password-requirements'); //Get the message container

        if (messageContainer.length === 0) { //If the message container doesn't exist, create it
            $(this).after('<div class="password-requirements"></div>'); //Add the message container to the password input
        }

        if (password === "") { //If the password is empty, run the code
            $(this).removeClass("border-red-500 border-green-500"); //Remove the red or green border
            messageContainer.html(""); //Remove the message
            $("button[type='submit']").prop("disabled", false); //Enable the button
            return; 
        }

        const results = validatePassword(password); //Validate the password
        const allPassed = updatePasswordFeedback(results, messageContainer); //Update the password feedback

        $(this)
            .removeClass("border-red-500 border-green-500") //Remove the red or green border
            .addClass(allPassed ? "border-green-500" : "border-red-500"); //Add class based on result

        $("button[type='submit']").prop("disabled", !allPassed); //Enable or disable the button
    }

    $('#password').on('keyup', handlePasswordValidation); //Add event listener to the password input
    $('input[id^="edit-password-"]').on('keyup', handleEditPasswordValidation); //Add event listener to the edit password input
}
