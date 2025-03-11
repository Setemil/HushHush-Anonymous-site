<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HushHush - Create Account</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <a href="index.php"><i class="fas fa-arrow-left"></i>Back to homepage</a>
        <form action="signup-process.php" method="post" class="form">
            <h1 class="form-header">Sign Up</h1>
            <label for="password">Nickname: </label>
            <input type="text" name="name" placeholder="Nickname">
            <label for="email">Email: </label>
            <input type="email" name="email" placeholder="Email">
            <label for="password">Password: </label>
            <input type="password" name="password" placeholder="Password">
            <label for="confirm-password">Confirm-password: </label>
            <input type="password" name="confirm-password" placeholder="Retype Password">
            <input type="submit" value="Create Account">
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
    </div>
</body>
</html>