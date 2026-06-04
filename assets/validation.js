// ===== REGISTER FORM VALIDATION =====
const registerForm = document.getElementById('registerForm');

if (registerForm) {
    registerForm.addEventListener('submit', function (e) {

        let name = document.getElementById('name').value.trim();
        let email = document.getElementById('email').value.trim();
        let password = document.getElementById('password').value.trim();
        let role = document.getElementById('role').value;

        if (name === '') {
            alert('Please enter your full name');
            e.preventDefault();
            return;
        }

        if (email === '') {
            alert('Please enter your email');
            e.preventDefault();
            return;
        }

        if (password === '') {
            alert('Please enter a password');
            e.preventDefault();
            return;
        }

        if (password.length < 6) {
            alert('Password must be at least 6 characters');
            e.preventDefault();
            return;
        }

        if (role === '') {
            alert('Please select a role');
            e.preventDefault();
            return;
        }

    });
}


// ===== ADD FOOD FORM VALIDATION =====
const addFoodForm = document.getElementById('addFoodForm');

if (addFoodForm) {
    addFoodForm.addEventListener('submit', function (e) {

        let foodName = document.getElementById('food_name').value.trim();
        let quantity = document.getElementById('quantity').value.trim();
        let location = document.getElementById('location').value.trim();
        let pickupTime = document.getElementById('pickup_time').value;

        if (foodName === '') {
            alert('Please enter food name');
            e.preventDefault();
            return;
        }

        if (quantity === '') {
            alert('Please enter quantity');
            e.preventDefault();
            return;
        }

        if (location === '') {
            alert('Please enter pickup location');
            e.preventDefault();
            return;
        }

        if (pickupTime === '') {
            alert('Please select pickup time');
            e.preventDefault();
            return;
        }

    });
}