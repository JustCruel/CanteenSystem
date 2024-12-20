<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('assets/images/canteen.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(64, 224, 208, 0.8), rgba(75, 0, 130, 0.6), rgba(255, 20, 147, 0.6));
            z-index: -1;
            filter: blur(3px);
            animation: animateBg 10s infinite alternate;
        }

        @keyframes animateBg {
            0% { filter: blur(3px); }
            100% { filter: blur(5px); }
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
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        .login-button:hover {
            background-color: rgba(255, 255, 255, 0.25);
            color: white;
            border-color: white;
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
        }

        .main-message {
            background: rgba(255, 255, 255, 0.2);
            padding: 60px;
            font-size: 44px;
            text-align: center;
            color: white;
            font-weight: bold; 
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
            max-width: 80%;
            letter-spacing: 3px;
            text-transform: uppercase;
            animation: slideIn 1.5s ease-in-out, pulse 3s infinite;
            margin-top: 150px;
        }

        .sub-message {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            font-weight: bold; 
            margin-top: 20px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .features {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
            flex-wrap: wrap;
        }

        .feature-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin: 0 20px;
            width: 200px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .feature-box img {
            width: 100px;
            height: 80px;
            margin-bottom: 15px;
        }

        .feature-box p {
            color: white;
            font-size: 18px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 1024px) { /* Tablets and smaller screens */
            .navbar {
                padding: 15px 30px;
            }

            .main-message {
                font-size: 36px;
                padding: 40px;
                margin-top: 100px;
            }

            .sub-message {
                font-size: 18px;
            }

            .login-button {
                padding: 10px 20px;
                font-size: 16px;
            }

            .feature-box {
                width: 160px;
                padding: 15px;
            }

            .feature-box img {
                width: 90px;
                height: 70px;
            }
        }

        @media (max-width: 768px) { /* Smartphones */
            .navbar {
                padding: 10px 20px;
            }

            .main-message {
                font-size: 30px;
                padding: 30px;
                margin-top: 80px;
            }

            .sub-message {
                font-size: 16px;
            }

            .login-button {
                padding: 8px 15px;
                font-size: 14px;
            }

            .features {
                flex-direction: column;
            }

            .feature-box {
                width: 160px;
                padding: 12px;
            }

            .feature-box img {
                width: 80px;
                height: 60px;
            }
        }

        @media (max-width: 480px) { /* Small screens like small phones */
            .main-message {
                font-size: 24px;
                padding: 20px;
                margin-top: 60px;
            }

            .sub-message {
                font-size: 14px;
            }

            .login-button {
                padding: 6px 12px;
                font-size: 12px;
            }

            .feature-box {
                width: 140px;
                padding: 10px;
            }

            .feature-box img {
                width: 70px;
                height: 50px;
            }
        }
           /* Existing Styles here... */

           .vision-mission {
        width: 90%;
        max-width: 1000px;
        margin: 50px auto;
        padding: 40px;
        background: linear-gradient(135deg, rgba(64, 224, 208, 0.8), rgba(75, 0, 130, 0.6), rgba(255, 20, 147, 0.6)); 
        color: white;
        text-align: center;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        line-height: 1.8;
        font-size: 18px;
        backdrop-filter: blur(5px);
    }

    .vision-mission h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 28px;
        margin-bottom: 15px;
        color: white; /* White color for headings */
        text-transform: uppercase;
    }

    .vision-mission p {
    color: white; /* Text color set to white */
    font-size: 18px; /* Font size for readability */
    line-height: 1.8; /* Adjusts line spacing for better readability */
    margin-bottom: 20px; /* Adds spacing between paragraphs */
    text-align: center; /* Center aligns the text */
}
 .about-us {
        width: 90%;
        max-width: 1000px;
        margin: 50px auto;
        padding: 40px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-align: center;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        line-height: 1.8;
        font-size: 18px;
        backdrop-filter: blur(5px);
    }

    .about-us h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 28px;
        margin-bottom: 15px;
        color: white;
        text-transform: uppercase;
    }

    .about-us p {
        margin-bottom: 20px;
        color:white;
    }

    @media (max-width: 768px) {
        .about-us {
            padding: 30px;
            font-size: 16px;
        }

        .about-us h2 {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .about-us {
            padding: 20px;
            font-size: 14px;
        }

        .about-us h2 {
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        .vision-mission {
            padding: 30px;
            font-size: 16px;
        }

        .vision-mission h2 {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .vision-mission {
            padding: 20px;
            font-size: 14px;
        }

        .vision-mission h2 {
            font-size: 20px;
        }
    }

        .scroll-down {
            margin-top: 30px;
            text-align: center;
        }

        .scroll-down a {
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .scroll-down a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo {
    width: 50px; /* Adjust the size of the logo */
    height: auto;
    margin-right: 20px; /* Space between logo and login button */
}

    </style>
</head>
<body>
<div class="navbar">
    <img src="assets/images/logo.png" alt="Logo" class="logo">
    <a href="login.php" class="login-button">Log In</a>
</div>

<div class="navbar">
        <a href="login.php" class="login-button">Log In</a>
    </div>
    <div class="main-message">Welcome To Canteen Management System</div>
    <div class="sub-message">Effortlessly Manage Your Canteen Transactions</div>

    <div class="features">
        <div class="feature-box">
            <img src="assets/images/Fastpayment.jpg" alt="Fast Payments">
            <p>Fast Payments</p>
        </div>
        <div class="feature-box">
            <img src="assets/images/Easy.jpg" alt="Order Tracking">
            <p>Easy Order Tracking</p>
        </div>
        <div class="feature-box">
            <img src="assets/images/Compre.jpg" alt="Reports">
            <p>Comprehensive Reports</p>
        </div>
    </div>

 <!-- Scroll Down Section -->
<div class="scroll-down">
    <a href="javascript:void(0)" id="toggleVisionButton">Vision & Mission</a>
    <a href="javascript:void(0)" id="toggleAboutButton">About Us</a>
</div>

<div id="vision-mission" class="vision-mission">
    <h2>Vision</h2>
    <p>
    To be a leading canteen management system that improves operational efficiency, enhances customer satisfaction, and fosters a seamless, enjoyable canteen experience for all users at Holy Cross College Sta. Rosa N.E. Inc.
    </p>
    <h2>Mission</h2>
    <p>
    To provide innovative and efficient solutions that modernize canteen operations, streamline payments through cashless systems, enable real-time inventory tracking, and generate comprehensive reports. Our mission is to create a user-friendly platform that improves service delivery for students, canteen staff, and faculty members, ensuring a smoother and more efficient canteen experience.
    </p>
</div>

<div id="about-us" class="about-us" style="display: none;">
    <h2>About Us</h2>
    <p>
    The Web-Based Canteen Management System at Holy Cross College Sta. Rosa N.E., Inc. aims to modernize and improve the efficiency of canteen operations, addressing the challenges posed by outdated manual processes. With a user-friendly interface and seamless transaction system, we seek to enhance the overall experience for students,faculty members  and canteen staff. This system minimizes long wait times, reduces operational bottlenecks, and streamlines the ordering and payment process. By integrating technology into canteen services, we are committed to providing a smooth and enjoyable experience for all users, while also improving service quality, transaction accuracy, and operational efficiency.
    </p>
</div>

<script>
    // JavaScript to toggle the Vision and Mission section
    const toggleVisionButton = document.getElementById('toggleVisionButton');
    const visionMission = document.getElementById('vision-mission');
    const toggleAboutButton = document.getElementById('toggleAboutButton');
    const aboutUs = document.getElementById('about-us');

    toggleVisionButton.addEventListener('click', () => {
        // Toggle the Vision and Mission section
        if (visionMission.style.display === 'none' || visionMission.style.display === '') {
            visionMission.style.display = 'block';
            toggleVisionButton.textContent = 'Hide Vision & Mission'; // Update button text
            visionMission.scrollIntoView({ behavior: 'smooth' }); // Smooth scroll to section

            // Hide About Us section if open
            aboutUs.style.display = 'none';
            toggleAboutButton.textContent = 'About Us'; // Reset About Us button text
        } else {
            visionMission.style.display = 'none';
            toggleVisionButton.textContent = 'Vision & Mission'; // Reset button text
        }
    });

    toggleAboutButton.addEventListener('click', () => {
        // Toggle the About Us section
        if (aboutUs.style.display === 'none' || aboutUs.style.display === '') {
            aboutUs.style.display = 'block';
            toggleAboutButton.textContent = 'Hide About Us'; // Update button text
            aboutUs.scrollIntoView({ behavior: 'smooth' }); // Smooth scroll to section

            // Hide Vision and Mission section if open
            visionMission.style.display = 'none';
            toggleVisionButton.textContent = 'Vision & Mission'; // Reset Vision & Mission button text
        } else {
            aboutUs.style.display = 'none';
            toggleAboutButton.textContent = 'About Us'; // Reset button text
        }
    });
</script>

</body>
</html>
