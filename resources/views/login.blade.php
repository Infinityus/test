<!DOCTYPE html>
<html>

<head>
    <title>Pyops Login</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .login-box {
            width: 350px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border: none;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Pyops Login</h2>

        @if (session('error'))
            <p style="color:red;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="/login">
            @csrf

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>
    </div>

</body>

</html>
