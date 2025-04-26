<!DOCTYPE html>
<html>

<head>
    <title>Welcome to the Vision Voice System</title>
</head>

<body>
    <h1>Welcome {{ $name }} To Vision Voice</h1>
    <p>Your account has been created successfully.</p>
    <p>Your login credentials are as follows:</p>
    <ul>
        <li>Email: {{ $email }}</li>
        <li>Password: {{ $password }}</li>
    </ul>
    <p>Please log in and update your information.</p>
</body>

</html>