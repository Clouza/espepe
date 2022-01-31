<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="./assets/css/auth.css">
</head>

<body>
    <form action="" method="get">
        <div class="form-group">
            <h1>Zaa Portals</h1>
            <small>Have account? <a href="./app/views/login.php">Sign in</a></small>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" required tabindex="1">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" required tabindex="2">
        </div>
        <div class="form-group">
            <label for="rPassword">Re-enter Password</label>
            <input type="password" id="rPassword" required tabindex="3">
        </div>
        <button type="button">Sign up</button>
    </form>
</body>

</html>