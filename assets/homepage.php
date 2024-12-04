<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link sa iyong CSS file -->
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('images/hcc.jpg'); /* Palitan ito ng tamang path sa background image */
            background-size: cover;
            background-position: center;
            min-height: 100vh; /* Full screen height */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            font-family: 'Poppins', sans-serif; /* Clean, modern font */
            position: relative;
        }

        /* Gradient overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(150, 102, 255, 0.9), rgba(0, 0, 128, 0.7), rgba(150, 0, 80, 0.6));
            z-index: -1;
        }

        .navbar {
            width: 100%;
            padding: 15px 50px;
            display: flex;
            justify-content: flex-end;
            position: absolute;
            top: 0;
            right: 0;
        }

        .login-button {
            background-color: rgba(255, 255, 255, 0.15);
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 50px;
            transition: all 0.3s ease;
            backdrop-filter: blur(0px); /* Glassmorphism effect */
            margin-top: 30px; /* Adjusted margin for better positioning */
        }

        .login-button:hover {
            background-color: #A2AD9C;
            color: #0066ff;
            border-color: #0066ff;
        }

        .main-message {
            background: rgba(255, 255, 255, 0.2);
            padding: 50px;
            font-size: 40px;
            text-align: center;
            color: white;
            border-radius: 20px;
            backdrop-filter: blur(5px); /* Glassmorphism effect */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 85%;
            letter-spacing: 3px;
            text-transform: uppercase;
            animation: fadeIn 4.0s ease-in-out;
            margin-top: 270px; /* Adjusted margin for better positioning */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .main-message {
                font-size: 28px;
                padding: 30px;
            }
            .login-button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="login.php" class="login-button">Log In</a>
    </div>
    <div class="main-message">Happy Birthday po Sir Ronel Evan Peralta ILOVeYOU!</div>
</body>
</html>
