<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
</head>
<body>
    <header style="background-color: #343a40; color: white; padding: 10px; text-align: center;">
        <h2>Welcome to Vaguy</h2>
    </header>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
    <footer style="background-color: #f8f9fa; padding: 20px; text-align: center;">
        <p>Thank you for choosing our services!</p>
        <p>&copy; {{ date('Y') }} Vaguy . All rights reserved.</p>
    </footer>
</body>
</html>
