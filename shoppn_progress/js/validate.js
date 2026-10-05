// Keep these rules in sync with validate_password() in core/core.php.
const COMMON_PASSWORDS = [
    '12345678', '123456789', '1234567890', '87654321', '11111111', '00000000',
    'password', 'password1', 'password12', 'password123', 'passw0rd', 'p@ssw0rd',
    'qwerty123', 'qwertyui', 'qwertyuiop', 'abc12345', 'abcd1234', 'a1b2c3d4',
    'iloveyou', 'iloveyou1', 'welcome1', 'welcome123', 'admin123', 'letmein123',
    'changeme', 'monkey123', 'football1', 'dragon123'
];

function checkPassword(password) {
    if (password.length < 8) {
        return 'Password must be at least 8 characters.';
    }

    const isSequence = /^\d+$/.test(password) &&
        ('01234567890123456789'.includes(password) || '98765432109876543210'.includes(password));

    if (COMMON_PASSWORDS.includes(password.toLowerCase()) || /^(.)\1+$/.test(password) || isSequence) {
        return 'That password is too easy to guess. Choose something less predictable.';
    }

    if (!/[A-Za-z]/.test(password) || !/\d/.test(password)) {
        return 'Password must include at least one letter and one number.';
    }

    return '';
}

const registerForm = document.getElementById('registerForm');

if (registerForm) {

    registerForm.addEventListener('submit', function (event) {

        let valid = true;

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('pass').value;
        const country = document.getElementById('country').value;
        const city = document.getElementById('city').value.trim();
        const contact = document.getElementById('contact').value.trim();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9+\-\s]{7,15}$/;

        document.getElementById('nameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('passError').textContent = '';
        document.getElementById('countryError').textContent = '';
        document.getElementById('cityError').textContent = '';
        document.getElementById('contactError').textContent = '';

        if (name === '') {
            document.getElementById('nameError').textContent = 'Full name is required.';
            valid = false;
        }

        if (!emailRegex.test(email)) {
            document.getElementById('emailError').textContent = 'Enter a valid email address.';
            valid = false;
        }

        const passwordError = checkPassword(password);
        if (passwordError) {
            document.getElementById('passError').textContent = passwordError;
            valid = false;
        }

        if (country === '') {
            document.getElementById('countryError').textContent = 'Please select a country.';
            valid = false;
        }

        if (city === '') {
            document.getElementById('cityError').textContent = 'City is required.';
            valid = false;
        }

        if (!phoneRegex.test(contact)) {
            document.getElementById('contactError').textContent = 'Enter a valid contact number.';
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
}