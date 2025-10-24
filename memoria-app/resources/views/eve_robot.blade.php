<!-- <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>روبوت EVE - فقاعة دردشة زجاجية</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        
        .container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .robot {
            position: relative;
            animation: float 4s ease-in-out infinite;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-25px);
            }
        }
        
        .robot-shadow {
            position: absolute;
            bottom: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 30px;
            background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(15px);
            animation: shadowPulse 4s ease-in-out infinite;
        }
        
        @keyframes shadowPulse {
            0%, 100% {
                transform: translateX(-50%) scale(1);
                opacity: 0.4;
            }
            50% {
                transform: translateX(-50%) scale(0.7);
                opacity: 0.6;
            }
        }
        
        /* الرأس */
        .robot-head {
            width: 240px;
            height: 140px;
            background: linear-gradient(145deg, #d5ddf5, #c5d1ed);
            border-radius: 50% 50% 48% 48%;
            position: relative;
            margin-bottom: 20px;
            box-shadow: 
                inset -8px -8px 15px rgba(0, 0, 0, 0.08),
                inset 8px 8px 15px rgba(255, 255, 255, 0.5),
                0 10px 30px rgba(0, 0, 0, 0.15);
            animation: headTilt 6s ease-in-out infinite;
        }
        
        @keyframes headTilt {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-2deg); }
            75% { transform: rotate(2deg); }
        }
        
        .robot-head::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 30px;
            width: 50px;
            height: 25px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            filter: blur(8px);
        }
        
        /* الوجه الداكن */
        .robot-face {
            width: 190px;
            height: 105px;
            background: linear-gradient(145deg, #1a1f3a, #0d1221);
            border-radius: 50% 50% 48% 48%;
            position: absolute;
            top: 18px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 
                0 8px 25px rgba(0, 0, 0, 0.6),
                inset 0 -5px 15px rgba(0, 0, 0, 0.5),
                inset 0 3px 10px rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }
        
        .face-shine {
            position: absolute;
            top: 15px;
            left: 25px;
            width: 45px;
            height: 20px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            filter: blur(5px);
        }
        
        /* العيون */
        .eye {
            width: 55px;
            height: 35px;
            background: linear-gradient(135deg, #00f0ff, #00d4e8);
            border-radius: 50%;
            position: absolute;
            top: 40px;
            animation: eyeBlink 6s infinite, eyeGlow 2.5s ease-in-out infinite;
            box-shadow: 
                0 0 20px rgba(0, 240, 255, 0.7),
                0 0 40px rgba(0, 240, 255, 0.4),
                inset -3px -3px 8px rgba(0, 0, 0, 0.3),
                inset 3px 3px 8px rgba(255, 255, 255, 0.6);
            transform: rotate(-8deg);
            --moveX: 0;
            --moveY: 0;
        }
        
        .eye::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 15px;
            height: 10px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            filter: blur(1px);
            transform: translate(calc(-50% + var(--moveX)), calc(-50% + var(--moveY)));
            transition: transform 0.1s ease-out;
        }
        
        .eye.left {
            left: 28px;
        }
        
        .eye.right {
            right: 28px;
            transform: rotate(8deg);
        }
        
        @keyframes eyeBlink {
            0%, 48%, 52%, 100% {
                transform: scaleY(1) rotate(-8deg);
            }
            50% {
                transform: scaleY(0.05) rotate(-8deg);
            }
        }
        
        .eye.right {
            animation: eyeBlinkRight 6s infinite, eyeGlow 2.5s ease-in-out infinite;
        }
        
        @keyframes eyeBlinkRight {
            0%, 48%, 52%, 100% {
                transform: scaleY(1) rotate(8deg);
            }
            50% {
                transform: scaleY(0.05) rotate(8deg);
            }
        }
        
        @keyframes eyeGlow {
            0%, 100% {
                box-shadow: 
                    0 0 20px rgba(0, 240, 255, 0.7),
                    0 0 40px rgba(0, 240, 255, 0.4),
                    inset -3px -3px 8px rgba(0, 0, 0, 0.3);
            }
            50% {
                box-shadow: 
                    0 0 30px rgba(0, 240, 255, 0.9),
                    0 0 50px rgba(0, 240, 255, 0.6),
                    0 0 70px rgba(0, 240, 255, 0.3),
                    inset -3px -3px 8px rgba(0, 0, 0, 0.3);
            }
        }
        
        /* الجسم */
        .robot-body {
            width: 260px;
            height: 360px;
            background: linear-gradient(145deg, #d5ddf5, #c5d1ed);
            border-radius: 48% 48% 55% 55%;
            position: relative;
            box-shadow: 
                inset -10px -10px 20px rgba(0, 0, 0, 0.08),
                inset 10px 10px 20px rgba(255, 255, 255, 0.5),
                0 15px 40px rgba(0, 0, 0, 0.2);
        }
        
        .body-shine {
            position: absolute;
            top: 40px;
            left: 40px;
            width: 80px;
            height: 120px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), transparent);
            border-radius: 50%;
            filter: blur(15px);
        }
        
        /* الأيدي */
        .arm {
            position: absolute;
            top: 45px;
            width: 58px;
            height: 280px;
        }
        
        .arm-shape {
            width: 100%;
            height: 100%;
            background: linear-gradient(165deg, 
                #e8edf7 0%, 
                #d5ddf5 15%,
                #c5d1ed 40%,
                #b8c5e5 70%,
                #a8b5dd 100%);
            border-radius: 50% 50% 50% 50% / 15% 15% 85% 85%;
            box-shadow: 
                -6px 8px 25px rgba(0, 0, 0, 0.2),
                inset 4px 4px 12px rgba(255, 255, 255, 0.5),
                inset -4px -4px 12px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .arm-shape::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 15px;
            width: 22px;
            height: 80px;
            background: linear-gradient(180deg, 
                rgba(255, 255, 255, 0.3) 0%,
                rgba(255, 255, 255, 0.15) 50%,
                transparent 100%);
            border-radius: 20px;
            filter: blur(6px);
        }
        
        .arm-shape::after {
            content: '';
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 35px;
            height: 35px;
            background: radial-gradient(circle at 30% 30%, 
                rgba(255, 255, 255, 0.4) 0%,
                rgba(255, 255, 255, 0.15) 40%,
                transparent 70%);
            border-radius: 50%;
        }
        
        .arm-highlight {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.2) 0%,
                transparent 40%);
            border-radius: 50% 50% 50% 50% / 15% 15% 85% 85%;
            pointer-events: none;
        }
        
        .arm.left {
            left: -68px;
            transform-origin: 29px 25px;
            animation: armSwingLeft 3.5s ease-in-out infinite;
        }
        
        .arm.right {
            right: -68px;
            transform-origin: 29px 25px;
            animation: armPointRight 2.5s ease-in-out infinite;
        }
        
        @keyframes armSwingLeft {
            0%, 100% {
                transform: rotate(18deg);
            }
            50% {
                transform: rotate(28deg);
            }
        }
        
        @keyframes armPointRight {
            0%, 100% {
                transform: rotate(-55deg);
            }
            50% {
                transform: rotate(-48deg);
            }
        }
        
        /* موجات طاقة */
        .energy-ring {
            position: absolute;
            border: 2px solid rgba(0, 240, 255, 0.2);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .energy-ring:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: ringPulse 3s ease-out infinite;
        }
        
        .energy-ring:nth-child(2) {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: ringPulse 3s ease-out infinite 1s;
        }
        
        @keyframes ringPulse {
            0% {
                width: 280px;
                height: 280px;
                opacity: 1;
            }
            100% {
                width: 500px;
                height: 500px;
                opacity: 0;
            }
        }
        
        /* فقاعة الدردشة الزجاجية المحسنة */
        .chat-bubble {
            position: absolute;
            right: -380px;
            top: 30%;
            transform: translateY(-50%);
            min-width: 340px;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.15) 0%, 
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0.05) 100%);
            color: #ffffff;
            padding: 30px 40px;
            border-radius: 20px;
            font-size: 22px;
            font-weight: 600;
            backdrop-filter: blur(20px) saturate(180%) brightness(1.2);
            -webkit-backdrop-filter: blur(20px) saturate(180%) brightness(1.2);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.2),
                inset 0 1px 1px rgba(255, 255, 255, 0.4),
                0 0 20px rgba(100, 200, 255, 0.3);
            opacity: 0;
            animation: fadeInBubble 1.2s ease-out forwards;
            cursor: pointer;
            transition: all 0.4s ease;
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .chat-bubble::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.2) 0%, 
                transparent 30%,
                transparent 70%,
                rgba(255, 255, 255, 0.1) 100%);
            border-radius: 20px;
            pointer-events: none;
        }
        
        .chat-bubble:hover {
            transform: translateY(-50%) scale(1.08);
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.25) 0%, 
                rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0.1) 100%);
            box-shadow: 
                0 15px 50px rgba(0, 0, 0, 0.3),
                inset 0 1px 1px rgba(255, 255, 255, 0.5),
                0 0 30px rgba(100, 200, 255, 0.5);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .chat-bubble::after {
            content: '';
            position: absolute;
            left: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 15px 20px 15px 0;
            border-color: transparent rgba(255, 255, 255, 0.15) transparent transparent;
            filter: drop-shadow(-3px 0 3px rgba(0, 0, 0, 0.1));
        }
        
        .chat-bubble .bubble-dots {
            position: absolute;
            top: 12px;
            right: 30px;
            display: flex;
            gap: 8px;
        }
        
        .chat-bubble .bubble-dots span {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: dotsPulse 1.5s infinite ease-in-out;
        }
        
        .chat-bubble .bubble-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .chat-bubble .bubble-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes dotsPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
        }
        
        @keyframes fadeInBubble {
            from {
                opacity: 0;
                transform: translateY(-50%) translateX(60px);
            }
            to {
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }
        }
        
        .text-line {
            position: absolute;
            opacity: 0;
            text-align: center;
            width: 100%;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            animation: typewriter 2.5s ease-in-out forwards;
            white-space: nowrap;
            overflow: hidden;
            padding: 0 20px;
        }
        
        .text-line:nth-child(1) {
            animation-delay: 0.5s;
        }
        
        .text-line:nth-child(2) {
            animation-delay: 3.5s;
        }
        
        .text-line:nth-child(3) {
            animation-delay: 6.5s;
        }
        
        .text-line:nth-child(4) {
            animation-delay: 9.5s;
        }
        
        .text-line:nth-child(5) {
            animation-delay: 12.5s;
        }
        
        @keyframes typewriter {
            0% {
                opacity: 0;
                width: 0;
                transform: translateY(20px);
            }
            20% {
                opacity: 1;
                transform: translateY(0);
            }
            80% {
                opacity: 1;
                width: 100%;
                transform: translateY(0);
            }
            100% {
                opacity: 0;
                width: 100%;
                transform: translateY(-20px);
            }
        }
        
        @media (max-width: 768px) {
            .robot-head {
                width: 200px;
                height: 120px;
            }
            
            .robot-face {
                width: 160px;
                height: 90px;
            }
            
            .robot-body {
                width: 220px;
                height: 300px;
            }
            
            .arm {
                width: 48px;
                height: 230px;
            }
            
            .arm.left {
                left: -58px;
                transform-origin: 24px 20px;
            }
            
            .arm.right {
                right: -58px;
                transform-origin: 24px 20px;
            }
            
            .chat-bubble {
                right: -300px;
                min-width: 280px;
                font-size: 18px;
                padding: 25px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="robot">
            <div class="robot-shadow"></div>
            <div class="energy-ring"></div>
            <div class="energy-ring"></div>
            
            <div class="robot-head">
                <div class="robot-face">
                    <div class="face-shine"></div>
                    <div class="eye left"></div>
                    <div class="eye right"></div>
                </div>
            </div>
            
            <div class="robot-body">
                <div class="body-shine"></div>
                
                <div class="arm left">
                    <div class="arm-shape">
                        <div class="arm-highlight"></div>
                    </div>
                </div>
                
                <div class="arm right">
                    <div class="arm-shape">
                        <div class="arm-highlight"></div>
                    </div>
                </div>
            </div>
            
            <div class="chat-bubble" id="chatBubble">
                <div class="bubble-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="text-line">Hello 👋</div>
                <div class="text-line">I am Memoria</div>
                <div class="text-line">Can I help you?</div>
                <div class="text-line">Press me 👆</div>
                <div class="text-line">Let's chat! 💬</div>
            </div>
        </div>
    </div>
    
    <script>
        const robot = document.querySelector('.robot');
        const eyes = document.querySelectorAll('.eye');
        const chatBubble = document.getElementById('chatBubble');
        
        // تتبع حركة العيون مع الماوس أو اللمس
        function updateEyePosition(clientX, clientY) {
            const eyeLeft = document.querySelector('.eye.left');
            const eyeRight = document.querySelector('.eye.right');
            
            if (!eyeLeft || !eyeRight) return;
            
            const eyeLeftRect = eyeLeft.getBoundingClientRect();
            const eyeRightRect = eyeRight.getBoundingClientRect();
            
            const eyeLeftCenterX = eyeLeftRect.left + eyeLeftRect.width / 2;
            const eyeLeftCenterY = eyeLeftRect.top + eyeLeftRect.height / 2;
            const eyeRightCenterX = eyeRightRect.left + eyeRightRect.width / 2;
            const eyeRightCenterY = eyeRightRect.top + eyeRightRect.height / 2;
            
            const angleLeft = Math.atan2(clientY - eyeLeftCenterY, clientX - eyeLeftCenterX);
            const angleRight = Math.atan2(clientY - eyeRightCenterY, clientX - eyeRightCenterX);
            
            const maxMove = 12;
            const moveXLeft = Math.cos(angleLeft) * maxMove;
            const moveYLeft = Math.sin(angleLeft) * maxMove;
            const moveXRight = Math.cos(angleRight) * maxMove;
            const moveYRight = Math.sin(angleRight) * maxMove;
            
            eyeLeft.style.setProperty('--moveX', `${moveXLeft}px`);
            eyeLeft.style.setProperty('--moveY', `${moveYLeft}px`);
            eyeRight.style.setProperty('--moveX', `${moveXRight}px`);
            eyeRight.style.setProperty('--moveY', `${moveYRight}px`);
        }
        
        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 15;
            const y = (e.clientY / window.innerHeight - 0.5) * 15;
            
            robot.style.transform = `translate(${x}px, ${y}px)`;
            updateEyePosition(e.clientX, e.clientY);
        });
        
        document.addEventListener('touchmove', (e) => {
            const touch = e.touches[0];
            updateEyePosition(touch.clientX, touch.clientY);
        });
        
        document.addEventListener('touchstart', (e) => {
            const touch = e.touches[0];
            updateEyePosition(touch.clientX, touch.clientY);
        });
        
        // رمشة عشوائية للعيون
        function randomBlink() {
            const blinkTime = Math.random() * 5000 + 3000;
            setTimeout(() => {
                eyes.forEach(eye => {
                    eye.style.animation = 'none';
                    setTimeout(() => {
                        eye.style.animation = '';
                    }, 10);
                });
                randomBlink();
            }, blinkTime);
        }
        
        randomBlink();
        
        // تفاعل عند النقر
        robot.addEventListener('click', () => {
            robot.style.transition = 'transform 0.3s ease';
            robot.style.transform = 'scale(1.1)';
            
            setTimeout(() => {
                robot.style.transform = 'scale(1)';
            }, 300);
        });
        
        chatBubble.addEventListener('click', () => {
            chatBubble.style.transition = 'transform 0.2s ease';
            chatBubble.style.transform = 'translateY(-50%) scale(0.95)';
            
            setTimeout(() => {
                chatBubble.style.transform = 'translateY(-50%) scale(1)';
            }, 200);
        });
    </script>
</body>
</html> -->