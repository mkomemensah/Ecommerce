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

        if (password.length < 6) {
            document.getElementById('passError').textContent = 'Password must be at least 6 characters.';
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