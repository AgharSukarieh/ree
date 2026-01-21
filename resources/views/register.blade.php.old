<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام السيرة الذاتية المتقدم - CV Registration System</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS Variables for Theme Management */
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --secondary-color: #e67e22;
            --secondary-dark: #d35400;
            --success-color: #27ae60;
            --error-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --background-color: #ffffff;
            --surface-color: #f8f9fa;
            --text-color: #2c3e50;
            --text-muted: #7f8c8d;
            --border-color: #dee2e6;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --shadow-hover: 0 4px 20px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --primary-color: #e05708;
            --primary-dark: #d35400;
            --secondary-color: #111314;
            --secondary-dark: #17405c;
            --background-color: #1a1a1a;
            --surface-color: #2d2d2d;
            --text-color: #ffffff;
            --text-muted: #bdc3c7;
            --border-color: #404040;
            --shadow: 0 2px 10px rgba(0,0,0,0.3);
            --shadow-hover: 0 4px 20px rgba(0,0,0,0.4);
        }

        /* Keep background fixed for dark theme */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #d35400, #d35400) !important;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #3498db, #3498db) !important;
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            transition: var(--transition);
            overflow-x: hidden;
        }

        /* Viewport meta tag support */
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Touch improvements for mobile */
        input, select, textarea, button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            touch-action: manipulation;
        }

        /* Checkbox specific fixes */
        input[type="checkbox"] {
            -webkit-appearance: checkbox !important;
            -moz-appearance: checkbox !important;
            appearance: checkbox !important;
            width: 18px !important;
            height: 18px !important;
            margin: 0 0.5rem 0 0 !important;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        /* Better touch targets */
        button, .control-btn, .add-btn {
            min-height: 44px;
            min-width: 44px;
        }
        
        .remove-btn {
            min-height: unset !important;
            min-width: unset !important;
        }

        /* Date input improvements for mobile */
        input[type="date"] {
            min-height: 44px;
            font-size: 16px;
        }

        /* Date input placeholder styling */
        input[type="date"]::-webkit-datetime-edit-text {
            color: var(--text-muted);
        }

        input[type="date"]::-webkit-datetime-edit-month-field,
        input[type="date"]::-webkit-datetime-edit-day-field,
        input[type="date"]::-webkit-datetime-edit-year-field {
            color: var(--text-muted);
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.7;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
            opacity: 1;
            color: rgba(30, 30, 30, 0.9);
        }

        /* Header glass effect enhancement */
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 50%, 
                rgba(255, 255, 255, 0.1) 100%);
            pointer-events: none;
            border-radius: 0;
        }

        /* Header hidden state */
        .header.hidden {
            transform: translateY(-100%);
            opacity: 0;
        }

        /* Header content styling */
        .header-content {
            position: relative;
            z-index: 1;
        }

        /* Header logo and controls */
        .header-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .header-logo:hover {
            color: var(--primary-color);
            transform: scale(1.05);
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-controls button {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-color);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .header-controls button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .back-to-top:active {
            transform: translateY(0);
        }

        /* Dark theme back to top button */
        [data-theme="dark"] .back-to-top {
            background: var(--primary-color);
            color: white;
        }

        [data-theme="dark"] .back-to-top:hover {
            background: #3498db;
        }

        /* Dark theme header glass effect */
        [data-theme="dark"] .header {
            background: rgba(26, 26, 26, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
            color: white;
        }

        [data-theme="dark"] .header::before {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.05) 0%, 
                rgba(255, 255, 255, 0.02) 50%, 
                rgba(255, 255, 255, 0.05) 100%);
        }
        
        [data-theme="dark"] .logo {
            color: white;
        }
        
        [data-theme="dark"] .control-btn {
            color: white;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: rgba(30, 30, 30, 0.9);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .header-controls {
            display: flex;
            gap: 1rem;
        }

        .control-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: rgba(30, 30, 30, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Main Container */
        .container {
            max-width: 900px;
            padding: 0 1.5rem;
            margin: 92px auto 0 auto;
        }

        /* Robot Section */
        .robot-section {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 1000;
            pointer-events: none;
        }
        
        /* Large screens (laptops) - Robot on left side, form on right */
        @media (min-width: 1201px) {
            .robot-section {
                left: 60px; /* Move robot more to the right */
                right: auto;
                transform: translateY(-50%);
            }
            
            /* Position form container on the right side */
            .container {
                margin-left: 320px; /* Increase space for robot on left side */
                margin-right: 40px; /* Add some margin from right edge */
                max-width: 1000px; /* Allow wider form container */
            }
            
            .form-container {
                margin-left: auto;
                margin-right: 0;
                max-width: 900px;
            }
        }
        
        /* Very large screens - adjust spacing */
        @media (min-width: 1600px) {
            .robot-section {
                left: 280px; /* Move robot more to the right for very large screens */
            }
            
            .container {
                margin-left: 560px; /* Increase space for robot */
                margin-right: 60px;
                max-width: 1200px;
            }
            
            .form-container {
                margin-left: auto;
                margin-right: 0;
                max-width: 1100px;
            }
        }

        .robot-section * {
            pointer-events: auto;
        }


        /* Form Container */
        .form-container {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Form Sections */
        .form-section {
            padding: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .section-icon {
            background: var(--primary-color);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .section-description {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Form Elements */
        label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .required {
            color: var(--error-color);
        }

        input, select, textarea {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-family: inherit;
            font-size: 1rem;
            background: var(--background-color);
            color: var(--text-color);
            transition: var(--transition);
            width: 100%;
        }

        /* Checkbox styling */
        input[type="checkbox"] {
            width: auto !important;
            margin-left: 0.5rem;
            margin-right: 0.5rem;
            transform: scale(1.2);
            cursor: pointer;
        }

        label {
            cursor: pointer;
        }

        label input[type="checkbox"] {
            margin: 0 0.5rem 0 0;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Profile Image Upload */
        .profile-image-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            border: 2px dashed var(--border-color);
            border-radius: var(--border-radius);
            background-color: var(--surface-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .profile-image-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.05);
        }

        .profile-image-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 3px solid var(--primary-color);
            box-shadow: 0 0 0 5px rgba(52, 152, 219, 0.2);
        }

        .profile-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image-preview i {
            font-size: 3rem;
            color: var(--text-muted);
        }

        .profile-image-text {
            color: var(--text-color);
            font-weight: 600;
            text-align: center;
        }

        .profile-image-input {
            display: none;
        }

        /* Dynamic Sections */
        .dynamic-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .dynamic-section.visible {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dynamic Items */
        .dynamic-container {
            margin-top: 1rem;
        }

        .dynamic-item {
            background: var(--background-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .remove-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--error-color);
            color: white;
            border: none;
            width: 25px !important;
            height: 25px !important;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .remove-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
        }

        .add-btn {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .add-btn:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        /* Submit Button */
        .submit-container {
            padding: 2rem;
            text-align: center;
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 1rem 3rem;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 200px;
            justify-content: center;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading Animation */
        .loading {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-hover);
            padding: 1rem 1.5rem;
            max-width: 400px;
            z-index: 1000;
            transform: translateX(100%);
            transition: var(--transition);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .notification-icon {
            font-size: 1.2rem;
            margin-top: 0.2rem;
        }

        .notification-success { border-left: 4px solid var(--success-color); }
        .notification-error { border-left: 4px solid var(--error-color); }
        .notification-warning { border-left: 4px solid var(--warning-color); }
        .notification-info { border-left: 4px solid var(--info-color); }

        .notification-success .notification-icon { color: var(--success-color); }
        .notification-error .notification-icon { color: var(--error-color); }
        .notification-warning .notification-icon { color: var(--warning-color); }
        .notification-info .notification-icon { color: var(--info-color); }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .notification-close {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.25rem;
            margin-left: auto;
        }

        /* Field Errors */
        .field-error {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error {
            border-color: var(--error-color) !important;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container {
                max-width: 900px;
                padding: 0 1.5rem;
            }
            
            .robot-section {
                right: 10px;
            }
        }

        @media (max-width: 992px) {
            .container {
                max-width: 750px;
                padding: 0 1rem;
            }
            
            .form-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }
            
            .header-content {
                padding: 0 1rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
                margin: 80px auto 0 auto; /* Less space for mobile header */
            }
            
            .robot-section {
                position: relative;
                top: auto;
                right: auto;
                transform: none;
                display: flex;
                justify-content: center;
                margin: 20px 0;
                order: -1;
            }

            .header {
                backdrop-filter: blur(15px) saturate(150%);
                -webkit-backdrop-filter: blur(15px) saturate(150%);
            }

            .header-content {
                padding: 0 1rem;
            }

            .header-controls button {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }

            .back-to-top {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .header-controls {
                flex-wrap: wrap;
                justify-content: center;
            }

            .form-section {
                padding: 1.5rem;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .section-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.3rem;
            }

            .submit-btn {
                width: 100%;
                padding: 1rem;
            }

            .profile-image-preview {
                width: 100px;
                height: 100px;
            }

            .profile-image-preview i {
                font-size: 2.5rem;
            }

            .control-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 0.5rem;
                margin: 70px auto 0 auto; /* Even less space for small mobile */
            }

            .header {
                backdrop-filter: blur(12px) saturate(120%);
                -webkit-backdrop-filter: blur(12px) saturate(120%);
            }

            .header-content {
                padding: 0 0.8rem;
            }

            .header-controls button {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }

            .back-to-top {
                bottom: 1rem;
                right: 1rem;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            input[type="date"] {
                min-height: 48px;
                font-size: 16px;
                padding: 0.8rem 1rem;
            }

            .form-section {
                padding: 1rem;
            }

            .section-title {
                font-size: 1.2rem;
            }

            .form-grid {
                gap: 0.8rem;
            }

            .profile-image-upload {
                padding: 1rem;
            }

            .profile-image-preview {
                width: 80px;
                height: 80px;
            }

            .profile-image-preview i {
                font-size: 2rem;
            }

            .header {
                padding: 0.5rem 0;
            }

            .logo {
                font-size: 1.2rem;
            }

            .control-btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }


            .dynamic-item {
                padding: 1rem;
            }

            .add-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .remove-btn {
                width: 20px !important;
                height: 20px !important;
                font-size: 0.7rem;
            }

            input, select, textarea {
                padding: 0.6rem 0.8rem;
                font-size: 0.9rem;
            }

            input[type="checkbox"] {
                width: 16px !important;
                height: 16px !important;
                transform: scale(1);
            }

            label {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 400px) {
            .container {
                padding: 0 0.3rem;
                margin: 60px auto 0 auto; /* Minimal space for very small screens */
            }

            .header {
                backdrop-filter: blur(10px) saturate(100%);
                -webkit-backdrop-filter: blur(10px) saturate(100%);
            }

            .header-content {
                padding: 0 0.5rem;
            }

            .header-controls button {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .back-to-top {
                bottom: 0.8rem;
                right: 0.8rem;
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }

            input[type="date"] {
                min-height: 46px;
                font-size: 15px;
                padding: 0.7rem 0.8rem;
            }

            .form-section {
                padding: 0.8rem;
            }

            .header-content {
                padding: 0 0.5rem;
            }

            .control-btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .form-grid {
                gap: 0.6rem;
            }

            .dynamic-item {
                padding: 0.8rem;
            }

            input, select, textarea {
                padding: 0.5rem 0.6rem;
                font-size: 0.85rem;
            }

            input[type="checkbox"] {
                width: 15px !important;
                height: 15px !important;
                transform: scale(1);
            }

            .add-btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        /* Landscape orientation for mobile */
        @media (max-height: 500px) and (orientation: landscape) {
            .header {
                padding: 0.5rem 0;
            }
            
            .container {
                margin: 0.5rem auto;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .section-header {
                margin-bottom: 1rem;
            }
        }

        /* Mobile device specific styles */
        .mobile-device .form-container {
            margin: 0.5rem;
            border-radius: 8px;
        }

        .mobile-device .form-section {
            padding: 1rem;
        }

        .mobile-device .header {
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .mobile-device .control-btn {
            min-height: 44px;
            min-width: 44px;
        }

        .mobile-device .add-btn {
            min-height: 44px;
            width: 100%;
        }

        .mobile-device .remove-btn {
            min-height: unset !important;
            min-width: unset !important;
        }

        .mobile-device .submit-btn {
            min-height: 48px;
            font-size: 1rem;
        }

        .mobile-device input[type="date"] {
            min-height: 48px;
            font-size: 16px;
            padding: 0.8rem 1rem;
        }

        /* iOS specific fixes */
        @supports (-webkit-touch-callout: none) {
            .mobile-device input,
            .mobile-device select,
            .mobile-device textarea {
                font-size: 16px;
            }
            
            .mobile-device input[type="checkbox"] {
                width: 20px !important;
                height: 20px !important;
                transform: scale(1.1);
            }
        }

        /* Android specific fixes */
        .mobile-device input:focus,
        .mobile-device select:focus,
        .mobile-device textarea:focus {
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
        }

        /* Smooth scrolling for mobile */
        .mobile-device {
            -webkit-overflow-scrolling: touch;
            overflow-scrolling: touch;
        }

        /* Better spacing for mobile forms */
        .mobile-device .form-grid {
            gap: 1rem;
        }

        .mobile-device .dynamic-item {
            margin-bottom: 1rem;
        }

        /* Improve readability on small screens */
        @media (max-width: 576px) {
            .mobile-device .section-title {
                line-height: 1.3;
            }
            
            .mobile-device .section-description {
                line-height: 1.4;
            }
            
            .mobile-device label {
                line-height: 1.3;
            }
        }

        /* RTL Support */
        [dir="rtl"] .notification {
            right: auto;
            left: 20px;
            transform: translateX(-100%);
        }

        [dir="rtl"] .notification.show {
            transform: translateX(0);
        }

        [dir="rtl"] .remove-btn {
            right: auto;
            left: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-file-alt"></i>
                <span data-ar="نظام السيرة الذاتية المتقدم" data-en="Advanced CV Registration System">نظام السيرة الذاتية المتقدم</span>
            </div>
            <div class="header-controls">
                <button class="control-btn" id="themeToggle">
                    <i class="fas fa-moon"></i>
                    <span data-ar="الوضع الداكن" data-en="Dark Mode">الوضع الداكن</span>
                </button>
                <button class="control-btn" id="langToggle">
                    <i class="fas fa-language"></i>
                    <span>English</span>
                </button>
                <a href="{{ route('home') }}" class="control-btn">
                    <i class="fas fa-home"></i>
                    <span data-ar="الرئيسية" data-en="Home">الرئيسية</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Robot Chat Component -->
        <div class="robot-section">
            @include('components.robot-chat')
        </div>
        
        <!-- Progress Bar -->

        <!-- Form Container -->
        <div class="form-container">
            <form id="cvForm" method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                @csrf
                <!-- Basic Personal Information -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المعلومات الشخصية الأساسية" data-en="Basic Personal Information">المعلومات الشخصية الأساسية</h2>
                            <p class="section-description" data-ar="أدخل معلوماتك الشخصية الأساسية" data-en="Enter your basic personal information">أدخل معلوماتك الشخصية الأساسية</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Profile Image Upload -->
                        <div class="form-group full-width">
                            <label for="profile_image">
                                <span data-ar="الصورة الشخصية" data-en="Profile Picture">الصورة الشخصية</span>
                            </label>
                            <div class="profile-image-upload" id="profileImageUploadArea">
                                <div class="profile-image-preview" id="profileImagePreview">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <p class="profile-image-text" data-ar="انقر لرفع الصورة أو اسحبها هنا" data-en="Click to upload or drag image here"></p>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="profile-image-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">
                                <span data-ar="الاسم الكامل" data-en="Full Name">الاسم الكامل</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name" required placeholder="أدخل اسمك الكامل" data-ar-placeholder="أدخل اسمك الكامل" data-en-placeholder="Enter your full name">
                        </div>

                        <div class="form-group">
                            <label for="jop_title">
                                <span data-ar="المسمى الوظيفي" data-en="Job Title">المسمى الوظيفي</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="jop_title" name="jop_title" required placeholder="مطور برمجيات، طبيب، مهندس" data-ar-placeholder="مطور برمجيات، طبيب، مهندس" data-en-placeholder="Software Developer, Doctor, Engineer">
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <span data-ar="رقم الهاتف" data-en="Phone Number">رقم الهاتف</span>
                            </label>
                            <input type="tel" id="phone" name="phone" placeholder="+966 50 123 4567" data-ar-placeholder="+966 50 123 4567" data-en-placeholder="+1 234 567 8900">
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <span data-ar="البريد الإلكتروني" data-en="Email Address">البريد الإلكتروني</span>
                            </label>
                            <input type="email" id="email" name="email" placeholder="example@email.com" data-ar-placeholder="example@email.com" data-en-placeholder="example@email.com">
                        </div>

                        <div class="form-group">
                            <label for="city">
                                <span data-ar="المدينة" data-en="City">المدينة</span>
                            </label>
                            <input type="text" id="city" name="city" placeholder="الرياض، جدة، الدمام" data-ar-placeholder="الرياض، جدة، الدمام" data-en-placeholder="Riyadh, Jeddah, Dammam">
                        </div>

                        <div class="form-group">
                            <label for="major">
                                <span data-ar="التخصص الرئيسي" data-en="Major Field">التخصص الرئيسي</span>
                                <span class="required">*</span>
                            </label>
                            <select id="major" name="major" required>
                                <option value="" data-ar="اختر التخصص" data-en="Select Major">اختر التخصص</option>
                                <option value="IT" data-ar="تقنية المعلومات" data-en="Information Technology">تقنية المعلومات</option>
                                <option value="Medicine" data-ar="الطب" data-en="Medicine">الطب</option>
                                <option value="Business" data-ar="إدارة الأعمال" data-en="Business">إدارة الأعمال</option>
                                <option value="Engineering" data-ar="الهندسة" data-en="Engineering">الهندسة</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="linkedin_profile">
                                <span data-ar="ملف LinkedIn" data-en="LinkedIn Profile">ملف LinkedIn</span>
                            </label>
                            <input type="url" id="linkedin_profile" name="linkedin_profile" placeholder="https://linkedin.com/in/yourprofile" data-ar-placeholder="https://linkedin.com/in/yourprofile" data-en-placeholder="https://linkedin.com/in/yourprofile">
                        </div>

                        <div class="form-group">
                            <label for="github_profile">
                                <span data-ar="ملف GitHub" data-en="GitHub Profile">ملف GitHub</span>
                            </label>
                            <input type="url" id="github_profile" name="github_profile" placeholder="https://github.com/yourusername" data-ar-placeholder="https://github.com/yourusername" data-en-placeholder="https://github.com/yourusername">
                        </div>

                        <div class="form-group full-width">
                            <label for="profile_summary">
                                <span data-ar="الملخص المهني" data-en="Professional Summary">الملخص المهني</span>
                            </label>
                            <textarea id="profile_summary" name="profile_summary" placeholder="اكتب ملخصاً مهنياً عن خبراتك ومهاراتك" data-ar-placeholder="اكتب ملخصاً مهنياً عن خبراتك ومهاراتك" data-en-placeholder="Write a professional summary about your experience and skills"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Languages -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-language"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="اللغات" data-en="Languages">اللغات</h2>
                            <p class="section-description" data-ar="أضف اللغات التي تتقنها" data-en="Add languages you speak">أضف اللغات التي تتقنها</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="languagesContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم اللغة" data-en="Language Name">اسم اللغة</span>
                                    </label>
                                    <input type="text" name="language_name[]" placeholder="العربية، الإنجليزية، الفرنسية" data-ar-placeholder="العربية، الإنجليزية، الفرنسية" data-en-placeholder="Arabic, English, French">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="مستوى الإتقان" data-en="Proficiency Level">مستوى الإتقان</span>
                                    </label>
                                    <select name="proficiency_level[]">
                                        <option value="Beginner" data-ar="مبتدئ" data-en="Beginner">مبتدئ</option>
                                        <option value="Intermediate" data-ar="لسي" data-en="Intermediate">متوسط</option>
                                        <option value="Advanced" data-ar="متقدم" data-en="Advanced">متقدم</option>
                                        <option value="Native" data-ar="لغة أم" data-en="Native">لغة أم</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeLanguage(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addLanguage()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة لغة" data-en="Add Language">إضافة لغة</span>
                    </button>
                </section>

                <!-- Soft Skills -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المهارات الشخصية" data-en="Soft Skills">المهارات الشخصية</h2>
                            <p class="section-description" data-ar="أضف مهاراتك الشخصية والاجتماعية" data-en="Add your personal and social skills">أضف مهاراتك الشخصية والاجتماعية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="softSkillsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                                    </label>
                                    <input type="text" name="soft_name[]" placeholder="التواصل، القيادة، العمل الجماعي" data-ar-placeholder="التواصل، القيادة، العمل الجماعي" data-en-placeholder="Communication, Leadership, Teamwork">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeSoftSkill(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addSoftSkill()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مهارة شخصية" data-en="Add Soft Skill">إضافة مهارة شخصية</span>
                    </button>
                </section>

                <!-- Experiences -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="الخبرات العملية" data-en="Work Experience">الخبرات العملية</h2>
                            <p class="section-description" data-ar="أضف خبراتك العملية والوظائف السابقة" data-en="Add your work experience and previous jobs">أضف خبراتك العملية والوظائف السابقة</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="experienceContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="المسمى الوظيفي" data-en="Job Title">المسمى الوظيفي</span>
                                    </label>
                                    <input type="text" name="title[]" placeholder="مطور برمجيات، مهندس، طبيب" data-ar-placeholder="مطور برمجيات، مهندس، طبيب" data-en-placeholder="Software Developer, Engineer, Doctor">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم الشركة" data-en="Company Name">اسم الشركة</span>
                                    </label>
                                    <input type="text" name="company[]" placeholder="شركة التقنية المتقدمة" data-ar-placeholder="شركة التقنية المتقدمة" data-en-placeholder="Advanced Technology Company">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="الموقع" data-en="Location">الموقع</span>
                                    </label>
                                    <input type="text" name="location[]" placeholder="الرياض، السعودية" data-ar-placeholder="الرياض، السعودية" data-en-placeholder="Riyadh, Saudi Arabia">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                                    </label>
                                    <input type="date" name="start_date[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                                    </label>
                                    <input type="date" name="end_date[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="وصف العمل" data-en="Job Description">وصف العمل</span>
                                    </label>
                                    <textarea name="description[]" placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-ar-placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-en-placeholder="Write a detailed description of your tasks and responsibilities"></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <input type="checkbox" name="is_internship[]" value="1">
                                        <span data-ar="تدريب تعاوني" data-en="Internship">تدريب تعاوني</span>
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeExperience(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addExperience()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة خبرة عملية" data-en="Add Work Experience">إضافة خبرة عملية</span>
                    </button>
                </section>

                <!-- Dynamic Sections Based on Major -->
                <!-- IT Skills -->
                <section class="form-section dynamic-section" id="itSkillsSection" data-majors="IT">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المهارات التقنية" data-en="Technical Skills">المهارات التقنية</h2>
                            <p class="section-description" data-ar="أضف مهاراتك في البرمجة والتقنية" data-en="Add your programming and technical skills">أضف مهاراتك في البرمجة والتقنية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="skillsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                                    </label>
                                    <input type="text" name="skill_name[]" placeholder="JavaScript, Python, React" data-ar-placeholder="JavaScript, Python, React" data-en-placeholder="JavaScript, Python, React">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                                    </label>
                                    <select name="category_id[]">
                                        <option value="1">Programming Languages</option>
                                        <option value="2">Web Development</option>
                                        <option value="3">Mobile Development</option>
                                        <option value="4">Database Management</option>
                                        <option value="5">DevOps & Cloud</option>
                                        <option value="6">Data Science & Analytics</option>
                                        <option value="7">Machine Learning & AI</option>
                                        <option value="8">Cybersecurity</option>
                                        <option value="9">UI/UX Design</option>
                                        <option value="10">Project Management</option>
                                        <option value="11">Quality Assurance</option>
                                        <option value="12">System Administration</option>
                                        <option value="13">Network Administration</option>
                                        <option value="14">Game Development</option>
                                        <option value="15">Blockchain & Cryptocurrency</option>
                                        <option value="16">IoT Development</option>
                                        <option value="17">AR/VR Development</option>
                                        <option value="18">Microservices Architecture</option>
                                        <option value="19">API Development</option>
                                        <option value="20">Version Control</option>
                                        <option value="21">Testing Frameworks</option>
                                        <option value="22">Performance Optimization</option>
                                        <option value="23">Code Review</option>
                                        <option value="24">Documentation</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeSkill(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addSkill()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مهارة تقنية" data-en="Add Technical Skill">إضافة مهارة تقنية</span>
                    </button>
                </section>

                <!-- IT Projects -->
                <section class="form-section dynamic-section" id="itProjectsSection" data-majors="IT">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المشاريع" data-en="Projects">المشاريع</h2>
                            <p class="section-description" data-ar="أضف مشاريعك التقنية والبرمجية" data-en="Add your technical and programming projects">أضف مشاريعك التقنية والبرمجية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="projectsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="عنوان المشروع" data-en="Project Title">عنوان المشروع</span>
                                    </label>
                                    <input type="text" name="project_title[]" placeholder="نظام إدارة المحتوى" data-ar-placeholder="نظام إدارة المحتوى" data-en-placeholder="Content Management System">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="التقنيات المستخدمة" data-en="Technologies Used">التقنيات المستخدمة</span>
                                    </label>
                                    <input type="text" name="technologies_used[]" placeholder="React, Node.js, MongoDB" data-ar-placeholder="React, Node.js, MongoDB" data-en-placeholder="React, Node.js, MongoDB">
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="وصف المشروع" data-en="Project Description">وصف المشروع</span>
                                    </label>
                                    <textarea name="description_project[]" placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-ar-placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-en-placeholder="Write a detailed description of the project and its objectives"></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="رابط المشروع" data-en="Project Link">رابط المشروع</span>
                                    </label>
                                    <input type="url" name="link[]" placeholder="https://github.com/username/project" data-ar-placeholder="https://github.com/username/project" data-en-placeholder="https://github.com/username/project">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeProject(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addProject()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مشروع" data-en="Add Project">إضافة مشروع</span>
                    </button>
                </section>

                <!-- Medicine Medical Skills -->
                <section class="form-section dynamic-section" id="medicalSkillsSection" data-majors="Medicine">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المهارات الطبية" data-en="Medical Skills">المهارات الطبية</h2>
                            <p class="section-description" data-ar="أضف مهاراتك الطبية المتخصصة" data-en="Add your specialized medical skills">أضف مهاراتك الطبية المتخصصة</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="medicalSkillsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم المهارة الطبية" data-en="Medical Skill Name">اسم المهارة الطبية</span>
                                    </label>
                                    <input type="text" name="medical_skill_name[]" placeholder="الجراحة، التشخيص، العلاج" data-ar-placeholder="الجراحة، التشخيص، العلاج" data-en-placeholder="Surgery, Diagnosis, Treatment">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="فئة المهارة الطبية" data-en="Medical Skill Category">فئة المهارة الطبية</span>
                                    </label>
                                    <select name="medical_category_id[]">
                                        <option value="1">Clinical Skills</option>
                                        <option value="2">Diagnostic Skills</option>
                                        <option value="3">Surgical Skills</option>
                                        <option value="4">Emergency Medicine</option>
                                        <option value="5">Pediatric Care</option>
                                        <option value="6">Geriatric Care</option>
                                        <option value="7">Mental Health</option>
                                        <option value="8">Radiology</option>
                                        <option value="9">Pathology</option>
                                        <option value="10">Pharmacology</option>
                                        <option value="11">Cardiology</option>
                                        <option value="12">Neurology</option>
                                        <option value="13">Oncology</option>
                                        <option value="14">Dermatology</option>
                                        <option value="15">Orthopedics</option>
                                        <option value="16">Ophthalmology</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeMedicalSkill(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addMedicalSkill()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مهارة طبية" data-en="Add Medical Skill">إضافة مهارة طبية</span>
                    </button>
                </section>

                <!-- Medical Research -->
                <section class="form-section dynamic-section" id="medicalResearchSection" data-majors="Medicine">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="الأبحاث الطبية" data-en="Medical Research">الأبحاث الطبية</h2>
                            <p class="section-description" data-ar="أضف أبحاثك ومنشوراتك الطبية" data-en="Add your medical research and publications">أضف أبحاثك ومنشوراتك الطبية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="medicalResearchContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="عنوان البحث" data-en="Research Title">عنوان البحث</span>
                                    </label>
                                    <input type="text" name="research_title[]" placeholder="دراسة حول علاج السكري" data-ar-placeholder="دراسة حول علاج السكري" data-en-placeholder="Study on Diabetes Treatment">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="سنة النشر" data-en="Publication Year">سنة النشر</span>
                                    </label>
                                    <input type="number" name="publication_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="وصف البحث" data-en="Research Description">وصف البحث</span>
                                    </label>
                                    <textarea name="research_description[]" placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-ar-placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-en-placeholder="Write a detailed description of the research and its results"></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="رابط البحث" data-en="Research Link">رابط البحث</span>
                                    </label>
                                    <input type="url" name="research_link[]" placeholder="https://research-link.com" data-ar-placeholder="https://research-link.com" data-en-placeholder="https://research-link.com">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeResearch(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addResearch()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة بحث طبي" data-en="Add Medical Research">إضافة بحث طبي</span>
                    </button>
                </section>

                <!-- Business Skills -->
                <section class="form-section dynamic-section" id="businessSkillsSection" data-majors="Business">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="مهارات الأعمال" data-en="Business Skills">مهارات الأعمال</h2>
                            <p class="section-description" data-ar="أضف مهاراتك في إدارة الأعمال والتسويق" data-en="Add your business management and marketing skills">أضف مهاراتك في إدارة الأعمال والتسويق</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="businessSkillsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                                    </label>
                                    <input type="text" name="business_skill_name[]" placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-ar-placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-en-placeholder="Project Management, Digital Marketing, Financial Analysis">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                                    </label>
                                    <select name="business_category_id[]">
                                        <option value="25">Legal Research</option>
                                        <option value="26">Case Analysis</option>
                                        <option value="27">Accounting Software</option>
                                        <option value="28">Financial Reporting</option>
                                        <option value="29">Business Strategy</option>
                                        <option value="30">Market Analysis</option>
                                        <option value="31">Human Resource Management</option>
                                        <option value="32">Teaching Skills</option>
                                        <option value="33">Educational Planning</option>
                                        <option value="34">Negotiation & Conflict Resolution</option>
                                        <option value="35">Leadership & Management</option>
                                        <option value="36">Project Coordination</option>
                                        <option value="37">Public Speaking</option>
                                        <option value="38">Time Management</option>
                                        <option value="39">Critical Thinking</option>
                                        <option value="24">Other</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeBusinessSkill(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addBusinessSkill()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مهارة أعمال" data-en="Add Business Skill">إضافة مهارة أعمال</span>
                    </button>
                </section>

                <!-- Business Core Competencies -->
                <section class="form-section dynamic-section" id="businessCompetenciesSection" data-majors="Business">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="الكفاءات الأساسية" data-en="Core Competencies">الكفاءات الأساسية</h2>
                            <p class="section-description" data-ar="أضف كفاءاتك في القيادة والتخطيط" data-en="Add your leadership and planning competencies">أضف كفاءاتك في القيادة والتخطيط</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="coreCompetenciesContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم الكفاءة" data-en="Competency Name">اسم الكفاءة</span>
                                    </label>
                                    <input type="text" name="competency_name[]" placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-ar-placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-en-placeholder="Leadership, Strategic Planning, Decision Making">
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="وصف الكفاءة" data-en="Competency Description">وصف الكفاءة</span>
                                    </label>
                                    <textarea name="competency_description[]" placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-ar-placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-en-placeholder="Write a detailed description of this competency and how you applied it"></textarea>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeCoreCompetency(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addCoreCompetency()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة كفاءة أساسية" data-en="Add Core Competency">إضافة كفاءة أساسية</span>
                    </button>
                </section>

                <!-- Business Interests -->
                <section class="form-section dynamic-section" id="businessInterestsSection" data-majors="Business">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="الاهتمامات التجارية" data-en="Business Interests">الاهتمامات التجارية</h2>
                            <p class="section-description" data-ar="أضف اهتماماتك في مجال الأعمال" data-en="Add your interests in business field">أضف اهتماماتك في مجال الأعمال</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="interestsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="اسم الاهتمام" data-en="Interest Name">اسم الاهتمام</span>
                                    </label>
                                    <input type="text" name="interest_name[]" placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-ar-placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-en-placeholder="Entrepreneurship, Investment, E-commerce">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeInterest(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addInterest()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة اهتمام" data-en="Add Interest">إضافة اهتمام</span>
                    </button>
                </section>

                <!-- Engineering Skills -->
                <section class="form-section dynamic-section" id="engineeringSkillsSection" data-majors="Engineering">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المهارات الهندسية" data-en="Engineering Skills">المهارات الهندسية</h2>
                            <p class="section-description" data-ar="أضف مهاراتك الهندسية والتقنية" data-en="Add your engineering and technical skills">أضف مهاراتك الهندسية والتقنية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="engineeringSkillsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                                    </label>
                                    <input type="text" name="engineering_skill_name[]" placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-ar-placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-en-placeholder="AutoCAD, SolidWorks, Structural Analysis">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                                    </label>
                                    <select name="engineering_category_id[]">
                                        <option value="8">CAD Software</option>
                                        <option value="9">3D Modeling</option>
                                        <option value="10">Simulation & Analysis</option>
                                        <option value="11">Technical Drawing</option>
                                        <option value="12">Manufacturing Tools</option>
                                        <option value="13">Control Systems</option>
                                        <option value="14">Building Information Modeling (BIM)</option>
                                        <option value="15">Robotics & Automation</option>
                                        <option value="16">Electrical Design Tools</option>
                                        <option value="24">Other</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeEngineeringSkill(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addEngineeringSkill()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مهارة هندسية" data-en="Add Engineering Skill">إضافة مهارة هندسية</span>
                    </button>
                </section>

                <!-- Education -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المؤهلات الأكاديمية" data-en="Academic Qualifications">المؤهلات الأكاديمية</h2>
                            <p class="section-description" data-ar="أضف مؤهلاتك الأكاديمية والتعليمية" data-en="Add your academic and educational qualifications">أضف مؤهلاتك الأكاديمية والتعليمية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="educationContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم الدرجة" data-en="Degree Name">اسم الدرجة</span>
                                    </label>
                                    <input type="text" name="degree_name[]" placeholder="بكالوريوس، ماجستير، دكتوراه" data-ar-placeholder="بكالوريوس، ماجستير، دكتوراه" data-en-placeholder="Bachelor's, Master's, PhD">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="مجال الدراسة" data-en="Field of Study">مجال الدراسة</span>
                                    </label>
                                    <input type="text" name="field_of_study[]" placeholder="علوم الحاسوب، الطب، الهندسة" data-ar-placeholder="علوم الحاسوب، الطب، الهندسة" data-en-placeholder="Computer Science, Medicine, Engineering">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم الجامعة" data-en="University Name">اسم الجامعة</span>
                                    </label>
                                    <input type="text" name="university_name[]" placeholder="جامعة الملك سعود" data-ar-placeholder="جامعة الملك سعود" data-en-placeholder="King Saud University">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="سنة البداية" data-en="Start Year">سنة البداية</span>
                                    </label>
                                    <input type="date" name="start_year[]" min="1950" max="2030" placeholder="2020" data-ar-placeholder="2020" data-en-placeholder="2020">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="سنة التخرج" data-en="End Year">سنة التخرج</span>
                                    </label>
                                    <input type="date" name="end_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeEducation(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addEducation()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مؤهل أكاديمي" data-en="Add Academic Qualification">إضافة مؤهل أكاديمي</span>
                    </button>
                </section>

                <!-- Certifications -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="الشهادات" data-en="Certifications">الشهادات</h2>
                            <p class="section-description" data-ar="أضف شهاداتك المهنية والتقنية" data-en="Add your professional and technical certifications">أضف شهاداتك المهنية والتقنية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="certificationsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم الشهادة" data-en="Certification Name">اسم الشهادة</span>
                                    </label>
                                    <input type="text" name="certifications_name[]" placeholder="AWS Certified, PMP, CISSP" data-ar-placeholder="AWS Certified, PMP, CISSP" data-en-placeholder="AWS Certified, PMP, CISSP">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="الجهة المانحة" data-en="Issuing Organization">الجهة المانحة</span>
                                    </label>
                                    <input type="text" name="issuing_org[]" placeholder="Amazon, PMI, ISC2" data-ar-placeholder="Amazon, PMI, ISC2" data-en-placeholder="Amazon, PMI, ISC2">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ الإصدار" data-en="Issue Date">تاريخ الإصدار</span>
                                    </label>
                                    <input type="date" name="issue_date[]" placeholder="تاريخ الإصدار" data-ar-placeholder="تاريخ الإصدار" data-en-placeholder="Issue Date">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ الانتهاء" data-en="Expiration Date">تاريخ الانتهاء</span>
                                    </label>
                                    <input type="date" name="expiration_date-disable" placeholder="تاريخ الانتهاء" data-ar-placeholder="تاريخ الانتهاء" data-en-placeholder="Expiration Date">
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="رابط الشهادة" data-en="Certificate Link">رابط الشهادة</span>
                                    </label>
                                    <input type="url" name="link_driver[]" placeholder="https://certificate-link.com" data-ar-placeholder="https://certificate-link.com" data-en-placeholder="https://certificate-link.com">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeCertification(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addCertification()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة شهادة" data-en="Add Certification">إضافة شهادة</span>
                    </button>
                </section>

                <!-- Memberships -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="العضويات المهنية" data-en="Professional Memberships">العضويات المهنية</h2>
                            <p class="section-description" data-ar="أضف عضوياتك في المنظمات المهنية" data-en="Add your memberships in professional organizations">أضف عضوياتك في المنظمات المهنية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="membershipsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم المنظمة" data-en="Organization Name">اسم المنظمة</span>
                                    </label>
                                    <input type="text" name="organization_name[]" placeholder="الجمعية السعودية للحاسب الآلي" data-ar-placeholder="الجمعية السعودية للحاسب الآلي" data-en-placeholder="Saudi Computer Society">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="نوع العضوية" data-en="Membership Type">نوع العضوية</span>
                                    </label>
                                    <input type="text" name="membership_type[]" placeholder="عضو عامل، عضو مؤسس" data-ar-placeholder="عضو عامل، عضو مؤسس" data-en-placeholder="Active Member, Founding Member">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                                    </label>
                                    <input type="date" name="start_date_membership[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                                    </label>
                                    <input type="date" name="end_date_membership[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="حالة العضوية" data-en="Membership Status">حالة العضوية</span>
                                    </label>
                                    <select name="membership_status[]">
                                        <option value="Active" data-ar="نشطة" data-en="Active">نشطة</option>
                                        <option value="Inactive" data-ar="غير نشطة" data-en="Inactive">غير نشطة</option>
                                        <option value="Expired" data-ar="منتهية" data-en="Expired">منتهية</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeMembership(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addMembership()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة عضوية" data-en="Add Membership">إضافة عضوية</span>
                    </button>
                </section>

                <!-- Activities -->
                <section class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="الأنشطة" data-en="Activities">الأنشطة</h2>
                            <p class="section-description" data-ar="أضف أنشطتك التطوعية والمجتمعية" data-en="Add your volunteer and community activities">أضف أنشطتك التطوعية والمجتمعية</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="activitiesContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>
                                        <span data-ar="عنوان النشاط" data-en="Activity Title">عنوان النشاط</span>
                                    </label>
                                    <input type="text" name="activity_title[]" placeholder="تطوع في جمعية خيرية" data-ar-placeholder="تطوع في جمعية خيرية" data-en-placeholder="Volunteer at Charity Organization">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="اسم المنظمة" data-en="Organization">اسم المنظمة</span>
                                    </label>
                                    <input type="text" name="organization[]" placeholder="جمعية البر الخيرية" data-ar-placeholder="جمعية البر الخيرية" data-en-placeholder="Al-Birr Charity Organization">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span data-ar="تاريخ النشاط" data-en="Activity Date">تاريخ النشاط</span>
                                    </label>
                                    <input type="date" name="activity_date[]" placeholder="تاريخ النشاط" data-ar-placeholder="تاريخ النشاط" data-en-placeholder="Activity Date">
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="وصف النشاط" data-en="Activity Description">وصف النشاط</span>
                                    </label>
                                    <textarea name="description_activity[]" placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-ar-placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-en-placeholder="Write a detailed description of the activity and your role in it"></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="رابط النشاط" data-en="Activity Link">رابط النشاط</span>
                                    </label>
                                    <input type="url" name="activity_link[]" placeholder="https://activity-link.com" data-ar-placeholder="https://activity-link.com" data-en-placeholder="https://activity-link.com">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeActivity(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addActivity()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة نشاط" data-en="Add Activity">إضافة نشاط</span>
                    </button>
                </section>

                <!-- IT Analytical Skills -->
                <section class="form-section dynamic-section" id="itAnalyticalSection" data-majors="IT">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h2 class="section-title" data-ar="المهارات التحليلية" data-en="Analytical Skills">المهارات التحليلية</h2>
                            <p class="section-description" data-ar="أضف مهاراتك في التحليل وحل المشكلات" data-en="Add your analysis and problem-solving skills">أضف مهاراتك في التحليل وحل المشكلات</p>
                        </div>
                    </div>

                    <div class="dynamic-container" id="analyticalSkillsContainer">
                        <div class="dynamic-item">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label>
                                        <span data-ar="اسم المهارة التحليلية" data-en="Analytical Skill Name">اسم المهارة التحليلية</span>
                                    </label>
                                    <input type="text" name="analytical_skill_name[]" placeholder="تحليل البيانات، حل المشكلات، التفكير النقدي" data-ar-placeholder="تحليل البيانات، حل المشكلات، التفكير النقدي" data-en-placeholder="Data Analysis, Problem Solving, Critical Thinking">
                                </div>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeAnalyticalSkill(this)" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-btn" onclick="addAnalyticalSkill()">
                        <i class="fas fa-plus"></i>
                        <span data-ar="إضافة مهارة تحليلية" data-en="Add Analytical Skill">إضافة مهارة تحليلية</span>
                    </button>
                </section>

                <!-- Submit Button -->
                <div class="submit-container">
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        <span data-ar="إرسال السيرة الذاتية" data-en="Submit CV">إرسال السيرة الذاتية</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

            <script>
        // Global variables
        let currentTheme = 'light';
        let currentLang = 'ar';
        let selectedMajor = '';

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeThemeToggle();
            initializeLanguageToggle();
            initializeImageUpload();
            initializeMajorSelection();
            initializeDynamicSections();
            initializeMobileOptimizations();
        });

        // Mobile optimizations
        function initializeMobileOptimizations() {
            // Prevent zoom on input focus for mobile
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        if (window.innerWidth < 768) {
                            this.style.fontSize = '16px';
                        }
                    });
                });
            }

            // Improve checkbox functionality
            initializeCheckboxes();

            // Initialize header and back to top functionality
            initializeHeaderAndBackToTop();

            // Improve touch scrolling
            document.body.style.webkitOverflowScrolling = 'touch';
            
            // Handle orientation change
            window.addEventListener('orientationchange', function() {
                setTimeout(function() {
                    window.scrollTo(0, 0);
                }, 100);
            });

            // Add mobile-specific classes
            if (window.innerWidth < 768) {
                document.body.classList.add('mobile-device');
            }

            // Handle resize
            window.addEventListener('resize', function() {
                if (window.innerWidth < 768) {
                    document.body.classList.add('mobile-device');
                } else {
                    document.body.classList.remove('mobile-device');
                }
            });
        }

        // Initialize header and back to top functionality
        function initializeHeaderAndBackToTop() {
            let lastScrollTop = 0;
            const header = document.querySelector('.header');
            const backToTop = document.getElementById('backToTop');
            const scrollThreshold = 100; // Show back to top after scrolling 100px

            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Hide/show header based on scroll direction
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down - hide header
                    header.classList.add('hidden');
                } else {
                    // Scrolling up - show header
                    header.classList.remove('hidden');
                }
                
                lastScrollTop = scrollTop;

                // Show/hide back to top button
                if (scrollTop > scrollThreshold) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });
        }

        // Scroll to top function
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Initialize checkboxes
        function initializeCheckboxes() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                // Add click event to label for better mobile experience
                const label = checkbox.closest('label');
                if (label) {
                    label.addEventListener('click', function(e) {
                        if (e.target !== checkbox) {
                            e.preventDefault();
                            checkbox.checked = !checkbox.checked;
                            checkbox.dispatchEvent(new Event('change'));
                        }
                    });
                }
                
                // Improve visual feedback
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        this.style.backgroundColor = 'var(--primary-color)';
                    } else {
                        this.style.backgroundColor = '';
                    }
                });
            });
        }

        // Theme Toggle Function
        function initializeThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }
        }

        function toggleTheme() {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
            
            // Update theme toggle button
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                const icon = themeToggle.querySelector('i');
                const text = themeToggle.querySelector('span');
                
                if (currentTheme === 'dark') {
                    icon.className = 'fas fa-sun';
                    text.textContent = currentLang === 'ar' ? 'الوضع الفاتح' : 'Light Mode';
                } else {
                    icon.className = 'fas fa-moon';
                    text.textContent = currentLang === 'ar' ? 'الوضع الداكن' : 'Dark Mode';
                }
            }
            
            console.log('Theme changed to:', currentTheme);
        }

        // Language Toggle Function
        function initializeLanguageToggle() {
            const langToggle = document.getElementById('langToggle');
            if (langToggle) {
                langToggle.addEventListener('click', toggleLanguage);
            }
        }

        function toggleLanguage() {
            currentLang = currentLang === 'ar' ? 'en' : 'ar';
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            
            // Update language toggle button
            const langToggle = document.getElementById('langToggle');
            if (langToggle) {
                const text = langToggle.querySelector('span');
                text.textContent = currentLang === 'ar' ? 'English' : 'العربية';
            }
            
            // Translate all elements with data-ar and data-en attributes
            const translatableElements = document.querySelectorAll('[data-ar][data-en]');
            translatableElements.forEach(element => {
                const arabicText = element.getAttribute('data-ar');
                const englishText = element.getAttribute('data-en');
                const textToUse = currentLang === 'ar' ? arabicText : englishText;
                
                // Special handling for buttons with icons (send button in robot)
                if (element.querySelector('svg')) {
                    // Find the span with text or create text node
                    const textSpan = element.querySelector('span');
                    if (textSpan) {
                        textSpan.textContent = textToUse;
                    } else {
                        // Update only text nodes, preserve SVG
                        const textNodes = Array.from(element.childNodes).filter(node => node.nodeType === Node.TEXT_NODE);
                        if (textNodes.length > 0) {
                            textNodes[0].textContent = textToUse;
                        }
                    }
                } else if (element.children.length > 0 && !element.classList.contains('text-line')) {
                    // Has children but not robot text-line, find first text node
                    const textNode = Array.from(element.childNodes).find(node => node.nodeType === Node.TEXT_NODE);
                    if (textNode) {
                        textNode.textContent = textToUse;
                    }
                } else {
                    // Safe to use innerHTML for robot text-lines and simple elements
                    element.innerHTML = textToUse;
                }
            });
            
            // Update form labels and placeholders
            updateFormTexts();
            
            // Dispatch custom event for robot component
            document.dispatchEvent(new CustomEvent('languageChanged', { 
                detail: { lang: currentLang } 
            }));
            
            console.log('Language changed to:', currentLang);
        }

        function updateFormTexts() {
            // Update form labels
            const labels = document.querySelectorAll('label');
            labels.forEach(label => {
                const span = label.querySelector('span[data-ar][data-en]');
                if (span) {
                    const arabicText = span.getAttribute('data-ar');
                    const englishText = span.getAttribute('data-en');
                    
                    if (currentLang === 'ar') {
                        span.textContent = arabicText;
                    } else {
                        span.textContent = englishText;
                    }
                }
            });
            
            // Update placeholders
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const arabicPlaceholder = input.getAttribute('data-ar-placeholder');
                const englishPlaceholder = input.getAttribute('data-en-placeholder');
                
                if (arabicPlaceholder && englishPlaceholder) {
                    if (currentLang === 'ar') {
                        input.placeholder = arabicPlaceholder;
                    } else {
                        input.placeholder = englishPlaceholder;
                    }
                }
            });
            
            // Update section titles and descriptions
            const sectionTitles = document.querySelectorAll('.section-title[data-ar][data-en]');
            sectionTitles.forEach(title => {
                const arabicText = title.getAttribute('data-ar');
                const englishText = title.getAttribute('data-en');
                
                if (currentLang === 'ar') {
                    title.textContent = arabicText;
                } else {
                    title.textContent = englishText;
                }
            });
            
            const sectionDescriptions = document.querySelectorAll('.section-description[data-ar][data-en]');
            sectionDescriptions.forEach(desc => {
                const arabicText = desc.getAttribute('data-ar');
                const englishText = desc.getAttribute('data-en');
                
                if (currentLang === 'ar') {
                    desc.textContent = arabicText;
                } else {
                    desc.textContent = englishText;
                }
            });
        }

        // Image Upload Function
        function initializeImageUpload() {
            const profileImageUploadArea = document.getElementById('profileImageUploadArea');
            const profileImageInput = document.getElementById('profile_image');
            
            if (profileImageUploadArea && profileImageInput) {
                profileImageUploadArea.addEventListener('click', () => {
                    profileImageInput.click();
                });
                
                profileImageUploadArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    profileImageUploadArea.style.borderColor = '#3498db';
                });
                
                profileImageUploadArea.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    profileImageUploadArea.style.borderColor = '';
                });
                
                profileImageUploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    profileImageUploadArea.style.borderColor = '';
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        handleImageUpload(files[0]);
                    }
                });
                
                profileImageInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleImageUpload(e.target.files[0]);
                    }
                });
            }
        }

        function handleImageUpload(file) {
            if (file && file.type.startsWith('image/')) {
                if (file.size > 5 * 1024 * 1024) {
                    alert(currentLang === 'ar' ? 'حجم الصورة كبير جداً. الحد الأقصى 5 ميجابايت' : 'Image size is too large. Maximum 5MB');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profileImagePreview');
                    if (preview) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview">`;
                    }
                };
                reader.readAsDataURL(file);
            } else {
                alert(currentLang === 'ar' ? 'يرجى اختيار ملف صورة صالح' : 'Please select a valid image file');
            }
        }

        // Major Selection Function
        function initializeMajorSelection() {
            const majorSelect = document.getElementById('major');
            if (majorSelect) {
                majorSelect.addEventListener('change', function() {
                    selectedMajor = this.value;
                    toggleDynamicSections();
                });
            }
        }

        function toggleDynamicSections() {
            console.log('toggleDynamicSections called with selectedMajor:', selectedMajor);
            
            // Hide all dynamic sections first
            const allDynamicSections = document.querySelectorAll('.dynamic-section');
            allDynamicSections.forEach(section => {
                section.style.display = 'none';
                section.classList.remove('visible');
                console.log('Hiding section:', section.id);
            });
            
            // Show sections that match the selected major
            if (selectedMajor) {
                const matchingSections = document.querySelectorAll(`[data-majors*="${selectedMajor}"]`);
                matchingSections.forEach(section => {
                    section.style.display = 'block';
                    section.classList.add('visible');
                    console.log('Showing section:', section.id, 'for major:', selectedMajor);
                });
            }
            
            console.log('Dynamic sections updated for major:', selectedMajor);
        }

        // Dynamic Sections Management
        function initializeDynamicSections() {
            // Initialize with no major selected
            toggleDynamicSections();
        }

        // Dynamic Item Functions
        function addLanguage() {
            const container = document.getElementById('languagesContainer');
            const newItem = createLanguageItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeLanguage(button) {
            const container = document.getElementById('languagesContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createLanguageItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم اللغة" data-en="Language Name">اسم اللغة</span>
                        </label>
                        <input type="text" name="language_name[]" placeholder="العربية، الإنجليزية، الفرنسية" data-ar-placeholder="العربية، الإنجليزية، الفرنسية" data-en-placeholder="Arabic, English, French">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="مستوى الإتقان" data-en="Proficiency Level">مستوى الإتقان</span>
                        </label>
                        <select name="proficiency_level[]">
                            <option value="Beginner" data-ar="مبتدئ" data-en="Beginner">مبتدئ</option>
                            <option value="Intermediate" data-ar="متوسط" data-en="Intermediate">متوسط</option>
                            <option value="Advanced" data-ar="متقدم" data-en="Advanced">متقدم</option>
                            <option value="Native" data-ar="لغة أم" data-en="Native">لغة أم</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeLanguage(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addSoftSkill() {
            const container = document.getElementById('softSkillsContainer');
            const newItem = createSoftSkillItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeSoftSkill(button) {
            const container = document.getElementById('softSkillsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createSoftSkillItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="اسم المهارة الشخصية" data-en="Soft Skill Name">اسم المهارة الشخصية</span>
                        </label>
                        <input type="text" name="soft_name[]" placeholder="التواصل، القيادة، العمل الجماعي" data-ar-placeholder="التواصل، القيادة، العمل الجماعي" data-en-placeholder="Communication, Leadership, Teamwork">
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeSoftSkill(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addExperience() {
            const container = document.getElementById('experienceContainer');
            if (container) {
                const newItem = createExperienceItem();
                container.appendChild(newItem);
                updateRemoveButtons();
            } else {
                console.error('experienceContainer not found');
            }
        }

        function removeExperience(button) {
            const container = document.getElementById('experienceContainer');
            if (container && container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createExperienceItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="المسمى الوظيفي" data-en="Job Title">المسمى الوظيفي</span>
                        </label>
                        <input type="text" name="title[]" placeholder="مطور برمجيات، مهندس، طبيب" data-ar-placeholder="مطور برمجيات، مهندس، طبيب" data-en-placeholder="Software Developer, Engineer, Doctor">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم الشركة" data-en="Company Name">اسم الشركة</span>
                        </label>
                        <input type="text" name="company[]" placeholder="شركة التقنية المتقدمة" data-ar-placeholder="شركة التقنية المتقدمة" data-en-placeholder="Advanced Technology Company">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="الموقع" data-en="Location">الموقع</span>
                        </label>
                        <input type="text" name="location[]" placeholder="الرياض، السعودية" data-ar-placeholder="الرياض، السعودية" data-en-placeholder="Riyadh, Saudi Arabia">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                        </label>
                        <input type="date" name="start_date[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                        </label>
                        <input type="date" name="end_date[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="وصف العمل" data-en="Job Description">وصف العمل</span>
                        </label>
                        <textarea name="description[]" placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-ar-placeholder="اكتب وصفاً مفصلاً عن مهامك ومسؤولياتك" data-en-placeholder="Write a detailed description of your tasks and responsibilities"></textarea>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <input type="checkbox" name="is_internship[]" value="1">
                            <span data-ar="تدريب تعاوني" data-en="Internship">تدريب تعاوني</span>
                        </label>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeExperience(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addSkill() {
            const container = document.getElementById('skillsContainer');
            const newItem = createSkillItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeSkill(button) {
            const container = document.getElementById('skillsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createSkillItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                        </label>
                        <input type="text" name="skill_name[]" placeholder="JavaScript, Python, React" data-ar-placeholder="JavaScript, Python, React" data-en-placeholder="JavaScript, Python, React">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                        </label>
                        <select name="category_id[]">
                            <option value="1" data-ar="برمجة" data-en="Programming">Programming</option>
                            <option value="2" data-ar="قواعد البيانات" data-en="Database">Database</option>
                            <option value="3" data-ar="تصميم" data-en="Design">Design</option>
                            <option value="4" data-ar="شبكات" data-en="Networking">Networking</option>
                            <option value="5" data-ar="أمن معلومات" data-en="Cybersecurity">Cybersecurity</option>
                            <option value="6" data-ar="ذكاء اصطناعي" data-en="Artificial Intelligence">Artificial Intelligence</option>
                            <option value="7" data-ar="تعلم آلة" data-en="Machine Learning">Machine Learning</option>
                            <option value="8" data-ar="تحليل بيانات" data-en="Data Analysis">Data Analysis</option>
                            <option value="9" data-ar="تطوير تطبيقات" data-en="App Development">App Development</option>
                            <option value="10" data-ar="تطوير ويب" data-en="Web Development">Web Development</option>
                            <option value="11" data-ar="إدارة مشاريع" data-en="Project Management">Project Management</option>
                            <option value="12" data-ar="أخرى" data-en="Other">Other</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeSkill(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addProject() {
            const container = document.getElementById('projectsContainer');
            const newItem = createProjectItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeProject(button) {
            const container = document.getElementById('projectsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createProjectItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="عنوان المشروع" data-en="Project Title">عنوان المشروع</span>
                        </label>
                        <input type="text" name="project_title[]" placeholder="نظام إدارة المحتوى" data-ar-placeholder="نظام إدارة المحتوى" data-en-placeholder="Content Management System">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="التقنيات المستخدمة" data-en="Technologies Used">التقنيات المستخدمة</span>
                        </label>
                        <input type="text" name="technologies_used[]" placeholder="React, Node.js, MongoDB" data-ar-placeholder="React, Node.js, MongoDB" data-en-placeholder="React, Node.js, MongoDB">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="رابط المشروع" data-en="Project Link">رابط المشروع</span>
                        </label>
                        <input type="url" name="link[]" placeholder="https://github.com/username/project" data-ar-placeholder="https://github.com/username/project" data-en-placeholder="https://github.com/username/project">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="وصف المشروع" data-en="Project Description">وصف المشروع</span>
                        </label>
                        <textarea name="description_project[]" placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-ar-placeholder="اكتب وصفاً مفصلاً عن المشروع وأهدافه" data-en-placeholder="Write a detailed description of the project and its objectives"></textarea>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeProject(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addAnalyticalSkill() {
            const container = document.getElementById('analyticalSkillsContainer');
            const newItem = createAnalyticalSkillItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeAnalyticalSkill(button) {
            const container = document.getElementById('analyticalSkillsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createAnalyticalSkillItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="اسم المهارة التحليلية" data-en="Analytical Skill Name">اسم المهارة التحليلية</span>
                        </label>
                        <input type="text" name="analytical_skill_name[]" placeholder="تحليل البيانات، حل المشكلات، التفكير النقدي" data-ar-placeholder="تحليل البيانات، حل المشكلات، التفكير النقدي" data-en-placeholder="Data Analysis, Problem Solving, Critical Thinking">
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeAnalyticalSkill(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addMedicalSkill() {
            const container = document.getElementById('medicalSkillsContainer');
            const newItem = createMedicalSkillItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeMedicalSkill(button) {
            const container = document.getElementById('medicalSkillsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createMedicalSkillItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم المهارة الطبية" data-en="Medical Skill Name">اسم المهارة الطبية</span>
                        </label>
                        <input type="text" name="medical_skill_name[]" placeholder="الجراحة، التشخيص، العلاج" data-ar-placeholder="الجراحة، التشخيص، العلاج" data-en-placeholder="Surgery, Diagnosis, Treatment">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="فئة المهارة الطبية" data-en="Medical Skill Category">فئة المهارة الطبية</span>
                        </label>
                        <select name="medical_category_id[]">
                            <option value="1" data-ar="جراحة" data-en="Surgery">Surgery</option>
                            <option value="2" data-ar="طب داخلي" data-en="Internal Medicine">Internal Medicine</option>
                            <option value="3" data-ar="أطفال" data-en="Pediatrics">Pediatrics</option>
                            <option value="4" data-ar="نساء وتوليد" data-en="Obstetrics & Gynecology">Obstetrics & Gynecology</option>
                            <option value="5" data-ar="أعصاب" data-en="Neurology">Neurology</option>
                            <option value="6" data-ar="قلب" data-en="Cardiology">Cardiology</option>
                            <option value="7" data-ar="عظام" data-en="Orthopedics">Orthopedics</option>
                            <option value="8" data-ar="عيون" data-en="Ophthalmology">Ophthalmology</option>
                            <option value="9" data-ar="أنف وأذن وحنجرة" data-en="ENT">ENT</option>
                            <option value="10" data-ar="جلدية" data-en="Dermatology">Dermatology</option>
                            <option value="11" data-ar="نفسية" data-en="Psychiatry">Psychiatry</option>
                            <option value="12" data-ar="أخرى" data-en="Other">Other</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeMedicalSkill(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addResearch() {
            const container = document.getElementById('researchContainer');
            const newItem = createResearchItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeResearch(button) {
            const container = document.getElementById('researchContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createResearchItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="عنوان البحث" data-en="Research Title">عنوان البحث</span>
                        </label>
                        <input type="text" name="research_title[]" placeholder="دراسة حول علاج السكري" data-ar-placeholder="دراسة حول علاج السكري" data-en-placeholder="Study on Diabetes Treatment">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="سنة النشر" data-en="Publication Year">سنة النشر</span>
                        </label>
                        <input type="number" name="publication_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="رابط البحث" data-en="Research Link">رابط البحث</span>
                        </label>
                        <input type="url" name="research_link[]" placeholder="https://research-link.com" data-ar-placeholder="https://research-link.com" data-en-placeholder="https://research-link.com">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="وصف البحث" data-en="Research Description">وصف البحث</span>
                        </label>
                        <textarea name="research_description[]" placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-ar-placeholder="اكتب وصفاً مفصلاً عن البحث ونتائجه" data-en-placeholder="Write a detailed description of the research and its results"></textarea>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeResearch(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addBusinessSkill() {
            const container = document.getElementById('businessSkillsContainer');
            const newItem = createBusinessSkillItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeBusinessSkill(button) {
            const container = document.getElementById('businessSkillsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createBusinessSkillItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم المهارة" data-en="Skill Name">اسم المهارة</span>
                        </label>
                        <input type="text" name="business_skill_name[]" placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-ar-placeholder="إدارة المشاريع، التسويق الرقمي، التحليل المالي" data-en-placeholder="Project Management, Digital Marketing, Financial Analysis">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="فئة المهارة" data-en="Skill Category">فئة المهارة</span>
                        </label>
                        <select name="business_category_id[]">
                            <option value="25" data-ar="بحث قانوني" data-en="Legal Research">Legal Research</option>
                            <option value="26" data-ar="تحليل حالة" data-en="Case Analysis">Case Analysis</option>
                            <option value="27" data-ar="برامج محاسبة" data-en="Accounting Software">Accounting Software</option>
                            <option value="28" data-ar="تقارير مالية" data-en="Financial Reporting">Financial Reporting</option>
                            <option value="29" data-ar="استراتيجية عمل" data-en="Business Strategy">Business Strategy</option>
                            <option value="30" data-ar="تحليل سوق" data-en="Market Analysis">Market Analysis</option>
                            <option value="31" data-ar="إدارة موارد بشرية" data-en="Human Resource Management">Human Resource Management</option>
                            <option value="32" data-ar="مهارات تدريس" data-en="Teaching Skills">Teaching Skills</option>
                            <option value="33" data-ar="تخطيط تعليمي" data-en="Educational Planning">Educational Planning</option>
                            <option value="34" data-ar="تفاوض وحل نزاعات" data-en="Negotiation & Conflict Resolution">Negotiation & Conflict Resolution</option>
                            <option value="35" data-ar="قيادة وإدارة" data-en="Leadership & Management">Leadership & Management</option>
                            <option value="36" data-ar="تنسيق مشاريع" data-en="Project Coordination">Project Coordination</option>
                            <option value="37" data-ar="تحدث عام" data-en="Public Speaking">Public Speaking</option>
                            <option value="38" data-ar="إدارة وقت" data-en="Time Management">Time Management</option>
                            <option value="39" data-ar="تفكير نقدي" data-en="Critical Thinking">Critical Thinking</option>
                            <option value="24" data-ar="أخرى" data-en="Other">Other</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeBusinessSkill(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addCoreCompetency() {
            const container = document.getElementById('coreCompetenciesContainer');
            const newItem = createCoreCompetencyItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeCoreCompetency(button) {
            const container = document.getElementById('coreCompetenciesContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createCoreCompetencyItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم الكفاءة" data-en="Competency Name">اسم الكفاءة</span>
                        </label>
                        <input type="text" name="competency_name[]" placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-ar-placeholder="القيادة، التخطيط الاستراتيجي، اتخاذ القرار" data-en-placeholder="Leadership, Strategic Planning موفقDecision Making">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="وصف الكفاءة" data-en="Competency Description">وصف الكفاءة</span>
                        </label>
                        <textarea name="competency_description[]" placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-ar-placeholder="اكتب وصفاً مفصلاً عن هذه الكفاءة وكيف طبقتها" data-en-placeholder="Write a detailed description of this competency and how you applied it"></textarea>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeCoreCompetency(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addInterest() {
            const container = document.getElementById('interestsContainer');
            const newItem = createInterestItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeInterest(button) {
            const container = document.getElementById('interestsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createInterestItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="اسم الاهتمام" data-en="Interest Name">اسم الاهتمام</span>
                        </label>
                        <input type="text" name="interest_name[]" placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-ar-placeholder="ريادة الأعمال، الاستثمار، التجارة الإلكترونية" data-en-placeholder="Entrepreneurship, Investment, E-commerce">
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeInterest(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addEngineeringSkill() {
            const container = document.getElementById('engineeringSkillsContainer');
            const newItem = createEngineeringSkillItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeEngineeringSkill(button) {
            const container = document.getElementById('engineeringSkillsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createEngineeringSkillItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="اسم المهارة الهندسية" data-en="Engineering Skill Name">اسم المهارة الهندسية</span>
                        </label>
                        <input type="text" name="engineering_skill_name[]" placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-ar-placeholder="AutoCAD، SolidWorks، تحليل الهياكل" data-en-placeholder="AutoCAD, SolidWorks, Structural Analysis">
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeEngineeringSkill(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addEducation() {
            const container = document.getElementById('educationContainer');
            const newItem = createEducationItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeEducation(button) {
            const container = document.getElementById('educationContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createEducationItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="الدرجة العلمية" data-en="Degree">الدرجة العلمية</span>
                        </label>
                        <input type="text" name="degree_name[]" placeholder="بكالوريوس، ماجستير، دكتوراه" data-ar-placeholder="بكالوريوس، ماجستير، دكتوراه" data-en-placeholder="Bachelor's, Master's, PhD">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="التخصص" data-en="Field of Study">التخصص</span>
                        </label>
                        <input type="text" name="field_of_study[]" placeholder="علوم الحاسوب، الطب، الهندسة" data-ar-placeholder="علوم الحاسوب، الطب، الهندسة" data-en-placeholder="Computer Science, Medicine, Engineering">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم الجامعة" data-en="University Name">اسم الجامعة</span>
                        </label>
                        <input type="text" name="university_name[]" placeholder="جامعة الملك سعود" data-ar-placeholder="جامعة الملك سعود" data-en-placeholder="King Saud University">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                        </label>
                        <input type="date" name="start_year[]" min="1950" max="2030" placeholder="2020" data-ar-placeholder="2020" data-en-placeholder="2020">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ التخرج" data-en="End Date">تاريخ التخرج</span>
                        </label>
                        <input type="date" name="end_year[]" min="1950" max="2030" placeholder="2024" data-ar-placeholder="2024" data-en-placeholder="2024">
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeEducation(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addCertification() {
            const container = document.getElementById('certificationsContainer');
            const newItem = createCertificationItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeCertification(button) {
            const container = document.getElementById('certificationsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createCertificationItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم الشهادة" data-en="Certification Name">اسم الشهادة</span>
                        </label>
                        <input type="text" name="certifications_name[]" placeholder="AWS Certified, PMP, CISSP" data-ar-placeholder="AWS Certified, PMP, CISSP" data-en-placeholder="AWS Certified, PMP, CISSP">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="الجهة المصدرة" data-en="Issuing Organization">الجهة المصدرة</span>
                        </label>
                        <input type="text" name="issuing_org[]" placeholder="Amazon, PMI, ISC2" data-ar-placeholder="Amazon, PMI, ISC2" data-en-placeholder="Amazon, PMI, ISC2">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ الإصدار" data-en="Issue Date">تاريخ الإصدار</span>
                        </label>
                        <input type="date" name="issue_date[]">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ الانتهاء" data-en="Expiration Date">تاريخ الانتهاء</span>
                        </label>
                        <input type="date" name="expiration_date[]">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="رابط الشهادة" data-en="Certificate Link">رابط الشهادة</span>
                        </label>
                        <input type="url" name="link_driver[]" placeholder="https://certificate-link.com" data-ar-placeholder="https://certificate-link.com" data-en-placeholder="https://certificate-link.com">
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeCertification(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addMembership() {
            const container = document.getElementById('membershipsContainer');
            const newItem = createMembershipItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeMembership(button) {
            const container = document.getElementById('membershipsContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createMembershipItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم المنظمة" data-en="Organization Name">اسم المنظمة</span>
                        </label>
                        <input type="text" name="organization_name[]" placeholder="الجمعية السعودية للحاسب الآلي" data-ar-placeholder="الجمعية السعودية للحاسب الآلي" data-en-placeholder="Saudi Computer Society">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="نوع العضوية" data-en="Membership Type">نوع العضوية</span>
                        </label>
                        <input type="text" name="membership_type[]" placeholder="عضو عامل، عضو مؤسس" data-ar-placeholder="عضو عامل، عضو مؤسس" data-en-placeholder="Active Member, Founding Member">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ البداية" data-en="Start Date">تاريخ البداية</span>
                        </label>
                        <input type="date" name="start_date[]" placeholder="تاريخ البداية" data-ar-placeholder="تاريخ البداية" data-en-placeholder="Start Date">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ النهاية" data-en="End Date">تاريخ النهاية</span>
                        </label>
                        <input type="date" name="end_date[]" placeholder="تاريخ النهاية" data-ar-placeholder="تاريخ النهاية" data-en-placeholder="End Date">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="حالة العضوية" data-en="Membership Status">حالة العضوية</span>
                        </label>
                        <select name="membership_status[]">
                            <option value="Active" data-ar="نشط" data-en="Active">نشط</option>
                            <option value="Inactive" data-ar="غير نشط" data-en="Inactive">غير نشط</option>
                            <option value="Expired" data-ar="منتهي الصلاحية" data-en="Expired">منتهي الصلاحية</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeMembership(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        function addActivity() {
            const container = document.getElementById('activitiesContainer');
            const newItem = createActivityItem();
            container.appendChild(newItem);
            updateRemoveButtons();
        }

        function removeActivity(button) {
            const container = document.getElementById('activitiesContainer');
            if (container.children.length > 1) {
                button.closest('.dynamic-item').remove();
                updateRemoveButtons();
            }
        }

        function createActivityItem() {
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <span data-ar="عنوان النشاط" data-en="Activity Title">عنوان النشاط</span>
                        </label>
                        <input type="text" name="activity_title[]" placeholder="تطوع في جمعية خيرية" data-ar-placeholder="تطوع في جمعية خيرية" data-en-placeholder="Volunteer at Charity Organization">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="اسم المنظمة" data-en="Organization">اسم المنظمة</span>
                        </label>
                        <input type="text" name="organization[]" placeholder="جمعية البر الخيرية" data-ar-placeholder="جمعية البر الخيرية" data-en-placeholder="Al-Birr Charity Organization">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="تاريخ النشاط" data-en="Activity Date">تاريخ النشاط</span>
                        </label>
                        <input type="date" name="activity_date[]">
                    </div>
                    <div class="form-group">
                        <label>
                            <span data-ar="رابط النشاط" data-en="Activity Link">رابط النشاط</span>
                        </label>
                        <input type="url" name="activity_link[]" placeholder="https://activity-link.com" data-ar-placeholder="https://activity-link.com" data-en-placeholder="https://activity-link.com">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>
                            <span data-ar="وصف النشاط" data-en="Activity Description">وصف النشاط</span>
                        </label>
                        <textarea name="description_activity[]" placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-ar-placeholder="اكتب وصفاً مفصلاً عن النشاط ودورك فيه" data-en-placeholder="Write a detailed description of the activity and your role in it"></textarea>
                    </div>
                </div>
                <button type="button" class="remove-btn" onclick="removeActivity(this)" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            return div;
        }

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const containers = document.querySelectorAll('.dynamic-container');
            containers.forEach(container => {
                const items = container.querySelectorAll('.dynamic-item');
                items.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-btn');
                    if (removeBtn) {
                        removeBtn.style.display = items.length > 1 ? 'flex' : 'none';
                    }
                });
            });
        }

        // Form submission
        function submitForm() {
            const form = document.getElementById('cvForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            if (form && submitBtn && loadingSpinner) {
                submitBtn.disabled = true;
                loadingSpinner.style.display = 'inline-block';
                
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message || (currentLang === 'ar' ? 'تم إنشاء الملف الشخصي بنجاح!' : 'Profile created successfully!'));
                        form.reset();
                        // Reset profile image preview
                        const preview = document.getElementById('profileImagePreview');
                        if (preview) {
                            preview.innerHTML = '<i class="fas fa-user"></i>';
                        }
                        // Reset dynamic sections
                        toggleDynamicSections();
                    } else {
                        showNotification('error', data.message || (currentLang === 'ar' ? 'حدث خطأ أثناء إنشاء الملف الشخصي' : 'An error occurred while creating the profile'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', currentLang === 'ar' ? 'حدث خطأ في الاتصال' : 'Connection error occurred');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    loadingSpinner.style.display = 'none';
                });
            }
        }

        // Show notification
        function showNotification(type, message) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            const icon = type === 'success' ? 'fas fa-check-circle' : 
                        type === 'error' ? 'fas fa-exclamation-circle' : 
                        type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="${icon} notification-icon"></i>
                    <div>
                        <div class="notification-title">${type === 'success' ? (currentLang === 'ar' ? 'نجح' : 'Success') : 
                                                      type === 'error' ? (currentLang === 'ar' ? 'خطأ' : 'Error') : 
                                                      type === 'warning' ? (currentLang === 'ar' ? 'تحذير' : 'Warning') : 
                                                      (currentLang === 'ar' ? 'معلومات' : 'Info')}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>

    <!-- Back to top button -->
    <button class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>
</body>
</html>
