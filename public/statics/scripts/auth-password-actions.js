// password input elements
const passNewInput = document.getElementById('user-pass-new');
const allPassInputs = document.querySelectorAll('.pass-input');
const showHideToggle = document.querySelectorAll('.show-hide-btn');
const passRequirementsContainer = document.getElementById('pass-requirements-container');
// password requirements node elements
let section, strong, unorderedList, listItemLength, listItemLetters, listItemSymbols, listItemAlpha;


// user password validation
const userPasswordValidation = input => {
    if (input.length >= 8) listItemLength.style.color = '#5a9d39';
    else listItemLength.style.color = '#7e7e7e';

    if (/(?=.*[A-Z])(?=.*[a-z])/.test(input)) listItemLetters.style.color = '#5a9d39';
    else listItemLetters.style.color = '#7e7e7e';

    if (/(?=.*[0-9_])/.test(input)) listItemSymbols.style.color = '#5a9d39';
    else listItemSymbols.style.color = '#7e7e7e';

    if (input && !/[^\w]/.test(input)) listItemAlpha.style.color = '#5a9d39';
    else listItemAlpha.style.color = '#7e7e7e';
};

// hide input error message
const hideErrorMessage = element => element ? element.style.display = 'none' : null;

passNewInput && passNewInput.addEventListener('click', () => {
    // generate user password requirements
    if (!document.getElementById('user-pass-requirements')) {
        section = document.createElement('section');
        section.setAttribute('id', 'user-pass-requirements');
        section.setAttribute('class', 'input-requirements');

        strong = document.createElement('strong');
        strong.innerHTML = 'Create a password that:';

        unorderedList = document.createElement('ul');
        unorderedList.setAttribute('id', 'user-pass-requirements-list');

        listItemLength = document.createElement('li');
        listItemLength.setAttribute('id', 'password-length');
        listItemLength.innerHTML = 'contains at least 8 characters';
        unorderedList.appendChild(listItemLength);

        listItemLetters = document.createElement('li');
        listItemLetters.setAttribute('id', 'password-letters');
        listItemLetters.innerHTML = 'contains both lower (a-z) and upper case letters (A-Z)';
        unorderedList.appendChild(listItemLetters);

        listItemSymbols = document.createElement('li');
        listItemSymbols.setAttribute('id', 'password-symbols');
        listItemSymbols.innerHTML = 'contains at least one number (0-9) or underscore symbol';
        unorderedList.appendChild(listItemSymbols);

        listItemAlpha = document.createElement('li');
        listItemAlpha.setAttribute('id', 'password-alpha-num');
        listItemAlpha.innerHTML = 'does not contain non-alphanumeric symbols';
        unorderedList.appendChild(listItemAlpha);

        section.appendChild(strong);
        section.appendChild(unorderedList);

        passRequirementsContainer && passRequirementsContainer.appendChild(section);
    }

    userPasswordValidation(passNewInput.value);
});
passNewInput && passNewInput.addEventListener('input', () => userPasswordValidation(passNewInput.value));
passNewInput && passNewInput.addEventListener('blur', () => section && section.remove());


allPassInputs && allPassInputs.forEach(input => {
    input.addEventListener('click', () => hideErrorMessage(input.previousElementSibling));
});


// show/hide user password input
showHideToggle && showHideToggle.forEach(toggle => {
    toggle.addEventListener('click', event => {
        if (event.target.classList.contains('hidden')) {
            event.target.classList.replace('hidden', 'visible');
            event.target.previousElementSibling.type = 'text';
        } else {
            event.target.classList.replace('visible', 'hidden');
            event.target.previousElementSibling.type = 'password';
        }
    })
});