<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HushHush - Log back in</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <a href="index.php"><i class="fas fa-arrow-left"></i>Back to homepage</a>
        <form action="login-process.php" method="post" class="form">
            <h1 class="form-header">Log In</h1>
            <label for="email">Email: </label>
            <input type="email" name="email" placeholder="Email">
            <label for="password">Password: </label>
            <input type="password" name="password" placeholder="Password">
            <input type="submit" value="Create Account">
        </form>
        <p class="login-link">Dont have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>