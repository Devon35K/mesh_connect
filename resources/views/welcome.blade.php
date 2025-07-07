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

        <button>LOGIN</button>
    </div>
</div>

</body>
</html>
