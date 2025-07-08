<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mesh Connect Co-Working Space</title>
    <link rel="icon" href="{{ asset('images/mesh_icon.png') }}" sizes="any">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<header>
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px; vertical-align: middle;">
 
    </div>
    <nav>
        <a href="#">HOME</a>
        <a href="#">SPACE</a>
        <a href="#">ABOUT US</a>
    </nav>
</header>

<div class="container">
    <div class="left-content">
        <h2>Welcome to Mesh<br>Connect<br>Coworking Space</h2>
        <p>Experience a professional and creative shared workspace that is designed to inspire and connect like-minded individuals, teams, and businesses.</p>
    </div>

    <div class="login-card">
        <h3>WELCOME</h3>
        <p>Please login to get started.</p>

        <label for="username">User Name</label>
        <input type="text" id="username" placeholder="Enter User Name">

        <label for="password">Password/Coupon</label>
        <input type="password" id="password" placeholder="Enter Password / Coupon">

        <button id="login-button" onclick="validateLogin()">LOGIN</button>
        <p id="error-message" style="color: red; text-align: center; margin-top: 10px;"></p>
        <p id="success-message" style="color: green; text-align: center; margin-top: 10px;"></p>
    </div>
</div>

<script>
    function validateLogin() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        const loginButton = document.getElementById('login-button');

        errorMessage.textContent = '';
        successMessage.textContent = '';

        if (username.trim() === '' || password.trim() === '') {
            errorMessage.textContent = 'Username and Password cannot be empty.';
            return;
        }

        // Show loading state
        loginButton.innerHTML = 'LOGGING IN...';
        loginButton.disabled = true;

        // Simulate API call
        setTimeout(() => {
            // Reset button
            loginButton.innerHTML = 'LOGIN';
            loginButton.disabled = false;

            // Simulate success/failure (actual login logic is out of scope)
            if (username === 'user' && password === 'password') {
                successMessage.textContent = 'Login successful! Redirecting...';
                // alert('Login successful (simulated).');
                // Redirect or perform further actions here
            } else {
                errorMessage.textContent = 'Invalid username or password.';
                // alert('Login failed (simulated).');
            }
        }, 1500); // Simulate 1.5 second delay
    }
</script>

</body>
</html>
