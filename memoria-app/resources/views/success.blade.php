<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تم إنشاء السيرة الذاتية بنجاح</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 120px; height: 120px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 60px; height: 60px; left: 70%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 100px; height: 100px; left: 80%; animation-delay: 1s; }
        .particle:nth-child(5) { width: 40px; height: 40px; left: 50%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-100px) rotate(180deg); opacity: 1; }
        }

        /* Animated Top Bar */
        .top-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
            background-size: 300% 300%;
            animation: shimmer 3s ease-in-out infinite;
            z-index: 10;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Main Container */
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 90%;
            position: relative;
            z-index: 5;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Icon */
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: bounce 1s ease-in-out;
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3);
        }

        .success-icon i {
            color: white;
            font-size: 40px;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }

        /* Title */
        .title {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin-bottom: 30px;
            font-weight: 300;
        }

        /* QR Code Section */
        .qr-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto 15px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .qr-id {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* URL Section */
        .url-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .url-display {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            word-break: break-all;
            font-size: 14px;
            color: #333;
            border: 1px solid #ddd;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            margin: 8px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 140px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn i {
            margin-left: 8px;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        /* Button Grid */
        .button-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .button-full {
            grid-column: 1 / -1;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .notification.error {
            background: linear-gradient(135deg, #f44336, #d32f2f);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 20px;
            }

            .title {
                font-size: 24px;
            }

            .qr-code {
                width: 150px;
                height: 150px;
            }

            .button-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Top Bar -->
    <div class="top-bar"></div>

    <!-- Background Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Title -->
        <h1 class="title">🎉 تم إنشاء السيرة الذاتية بنجاح</h1>
        <p class="subtitle">يمكنك الآن مشاركة سيرتك الذاتية أو تحميل رمز QR</p>

        @if(isset($qr_id) && isset($qr_code_url) && isset($profile_url))
            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-code">
                    <img src="{{ $qr_code_url }}" alt="QR Code" />
                </div>
                <div class="qr-id">
                    QR ID: <span>{{ $qr_id }}</span>
                </div>
            </div>

            <!-- URL Section -->
            <div class="url-section">
                <div class="url-display">{{ $profile_url }}</div>
                <div class="button-grid">
                    <button class="btn btn-info" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i>
                        نسخ الرابط
                    </button>
                    <button class="btn btn-success" onclick="downloadQR()">
                        <i class="fas fa-download"></i>
                        تحميل QR
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-grid">
                <a href="{{ $profile_url }}" target="_blank" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    عرض السيرة الذاتية
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    الصفحة الرئيسية
                </a>
            </div>
        @else
            <!-- Error State -->
            <div style="color: rgba(255, 255, 255, 0.9); text-align: center; padding: 20px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #ff6b6b; margin-bottom: 15px;"></i>
                <h3>خطأ في تحميل البيانات</h3>
                <p>لم يتم العثور على بيانات السيرة الذاتية. يرجى المحاولة مرة أخرى.</p>
                <a href="{{ route('register') }}" class="btn btn-primary" style="margin-top: 20px;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للنموذج
                </a>
            </div>
        @endif
    </div>

    <!-- Notification Container -->
    <div id="notification" class="notification"></div>

    <script>
        const profileUrl = @json($profile_url ?? '');
        const qrCodeUrl = @json($qr_code_url ?? '');
        const qrId = @json($qr_id ?? '');

        // Copy URL to clipboard
        async function copyToClipboard() {
            if (!profileUrl) {
                showNotification('خطأ: لا يوجد رابط للنسخ', 'error');
                return;
            }

            try {
                await navigator.clipboard.writeText(profileUrl);
                showNotification('تم نسخ الرابط بنجاح!', 'success');
            } catch (error) {
                console.error('Error copying to clipboard:', error);
                
                // Fallback method
                const textArea = document.createElement('textarea');
                textArea.value = profileUrl;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                showNotification('تم نسخ الرابط بنجاح!', 'success');
            }
        }

        // Download QR Code
        async function downloadQR() {
            if (!qrCodeUrl) {
                showNotification('خطأ: لا يوجد رمز QR للتحميل', 'error');
                return;
            }

            try {
                const response = await fetch(qrCodeUrl);
                const blob = await response.blob();
                
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `qr_code_${qrId}.png`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                
                showNotification('تم تحميل رمز QR بنجاح!', 'success');
            } catch (error) {
                console.error('Error downloading QR code:', error);
                showNotification('خطأ في تحميل رمز QR', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>

