////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*** user panel navidation **/
// side panel navigation buttons
const profileBtn = document.getElementById('profile-btn');
const settingsBtn = document.getElementById('settings-btn');
// main content sections
const profileSection = document.getElementById('profile-panel');
const settingsSection = document.getElementById('settings-panel');

let currentSection;


// displaying profile section panel when URL is '/user-panel/profile'
if (window.location.href.includes('/user-panel/profile')) {
    profileSection.style.display = 'block';
    currentSection = profileSection;
}
// displaying profile section panel on click and set URL to '/user-panel/profile'
profileBtn.addEventListener('click', () => {
    window.history.pushState('profile', '', '/user-panel/profile');
    currentSection.style.display = 'none';
    profileSection.style.display = 'block';
    currentSection = profileSection;
});

// displaying settings section panel when URL is '/user-panel/settings'
if (window.location.href.includes('/user-panel/settings')) {
    settingsSection.style.display = 'block';
    currentSection = settingsSection;
}
// displaying settings section panel on click and set URL to '/user-panel/settings'
settingsBtn.addEventListener('click', () => {
    window.history.pushState('settings', '', '/user-panel/settings');
    currentSection.style.display = 'none';
    settingsSection.style.display = 'block';
    currentSection = settingsSection;
});


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*** user panel section controller **/
const locationInput = document.getElementById('user-location-input');
const locationHints = document.getElementById('location-live-hints');


const setLocationInputValue = location => {
    locationInput.value = location.city;
    locationHints.innerHTML = '';
};

const generateDropdown = items => {
    const dropdownList = document.createElement('ul');

    items.forEach((item, index) => {
        if (index < 10) {
            const dropdownItem = document.createElement('li');
            dropdownItem.innerHTML = `${item.city} (pow. ${item.county})`;
            dropdownItem.addEventListener('click', () => setLocationInputValue(item));
            dropdownList.appendChild(dropdownItem);
        }
    });

    locationHints.innerHTML = '';
    locationHints.appendChild(dropdownList);
};

const getLocationsList = event => {
    const value = event.target.value;

    if (value.length > 2) {
        const xhr = new XMLHttpRequest();

        xhr.onload = () => xhr.status === 200 && generateDropdown(JSON.parse(xhr.responseText));
        xhr.open('GET', 'http://mvc-javascript-php-service.pl/api/locations?q=' + value, true);
        xhr.send();
    } else {
        locationHints.innerHTML = '';
    }
};

locationInput.addEventListener('input', event => getLocationsList(event));
locationInput.addEventListener('click', event => getLocationsList(event));


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*** user settings panel actions **/

const delFormSubmitGroup = document.getElementById('del-form-submit-group');
const delFormBtn = document.getElementById('delete-btn');

delFormBtn.addEventListener('click', event => {
    event.target.remove();

    const input = document.createElement('input');

    input.setAttribute('type', 'submit');
    input.setAttribute('id', 'delete-submit-btn');
    input.setAttribute('class', 'auth-form-submit-btn');
    input.setAttribute('value', 'Confirm');

    delFormSubmitGroup.appendChild(input);
});