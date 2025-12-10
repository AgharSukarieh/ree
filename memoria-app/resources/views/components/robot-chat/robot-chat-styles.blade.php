<style>
.robot-chat-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        min-height: 400px;
    }
    
    .robot {
        position: relative;
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
    }
    
    /* إخفاء الصورة في الحالة العادية */
    .robot::before {
        display: none !important;
    }
    
    @keyframes lightBeam {
        0%, 100% {
            opacity: 0.6;
            transform: translateX(-50%) scale(1);
        }
        50% {
            opacity: 1;
            transform: translateX(-50%) scale(1.2);
        }
    }
    
    /* Prompt Input Container */
    .prompt-input-container {
        position: absolute;
        bottom: -500px;
        left: 50%;
        transform: translateX(-50%);
        width: 95%;
        max-width: 700px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        z-index: 1000;
    }
    
    .prompt-input-container.show {
        opacity: 1;
        visibility: visible;
        bottom: -280px;
    }
    
    .input-wrapper {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        background:hsl(184, 93.60%, 30.60%);
        border: none;
        border-radius: 12px;
        padding: 15px 25px;
        box-shadow: 
            0 10px 30px rgba(0, 0, 0, 0.2),
            inset 0 1px 2px rgba(255, 255, 255, 0.4),
            0 0 10px rgba(117, 239, 248, 0.3);
        transition: all 0.3s ease;
        min-height: 160px;
        width: 100%;
    }
    
    .input-wrapper:focus-within {
        border-color: rgba(0, 240, 255, 0.6);
        box-shadow: 
            0 15px 40px rgba(0, 0, 0, 0.3),
            inset 0 2px 4px rgba(255, 255, 255, 0.5),
            0 0 15px rgba(117, 239, 248, 0.4);
        transform: scale(1.02);
    }
    
    #promptInput {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: #000000;
        font-size: 14px;
        font-weight: 600;
        padding: 15px 20px;
        width: 100%;
        min-height: 120px;
        max-height: 200px;
        resize: none;
        font-family: inherit;
        line-height: 1.4;
    }
    
    #promptInput::placeholder {
        color: rgba(255, 255, 255, 0.6);
        font-style: italic;
        font-size: 14px;
    }
    
    .input-buttons {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 8px;
        margin-top: 6px;
    }
    
    .send-button {
        background: linear-gradient(135deg, 
            rgba(0, 240, 255, 0.8) 0%, 
            rgba(0, 200, 255, 0.6) 100%);
        border: none;
        border-radius: 8px;
        flex: 1;
        min-height: 27px;
        height: 27px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 
            0 3px 10px rgba(0, 240, 255, 0.4),
            inset 0 1px 2px rgba(255, 255, 255, 0.3);
    }
    
    .send-button svg {
        flex-shrink: 0;
        order: 1;
        transform: rotate(-29deg);
    }
    
    .send-button span {
        order: 2;
    }
    
    .send-button:hover {
        background: linear-gradient(135deg, 
            rgba(0, 240, 255, 1) 0%, 
            #84e4ff 100%);
        transform: scale(1.1);
        box-shadow: 
            0 6px 20px rgba(0, 240, 255, 0.6),
            inset 0 1px 2px rgba(255, 255, 255, 0.4);
    }
    
    .send-button:active {
        transform: scale(0.95);
    }
    
    .close-button {
        background: linear-gradient(135deg, 
            rgba(255, 100, 100, 0.8) 0%, 
            rgba(255, 80, 80, 0.6) 100%);
        border: none;
        border-radius: 50%;
        min-width: 27px;
        width: 27px;
        min-height: 27px;
        height: 27px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        box-shadow: 
            0 3px 10px rgba(255, 100, 100, 0.4),
            inset 0 1px 2px rgba(255, 255, 255, 0.3);
    }
    
    .close-button:hover {
        background: linear-gradient(135deg, 
            rgba(255, 100, 100, 1) 0%, 
            rgba(255, 80, 80, 0.8) 100%);
        transform: scale(1.1);
        box-shadow: 
            0 6px 20px rgba(255, 100, 100, 0.6),
            inset 0 1px 2px rgba(255, 255, 255, 0.4);
    }
    
    .close-button:active {
        transform: scale(0.95);
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-25px);
        }
    }
    
    /* animation للطيران في الشاشات الكبيرة */
    @keyframes floatLarge {
        0%, 100% {
            transform: scale(0.7) translateY(0px) rotate(0deg);
        }
        25% {
            transform: scale(0.7) translateY(-15px) rotate(0deg);
        }
        50% {
            transform: scale(0.7) translateY(-25px) rotate(0deg);
        }
        75% {
            transform: scale(0.7) translateY(-15px) rotate(0deg);
        }
    }
    
    /* .robot-shadow {
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
    } */
    
    /* @keyframes shadowPulse {
        0%, 100% {
            transform: translateX(-50%) scale(1);
            opacity: 0.4;
        }
        50% {
            transform: translateX(-50%) scale(0.7);
            opacity: 0.6;
        }
    } */
    
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
        overflow: hidden; /* منع العيون البيضاء من الخروج */
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
    
    
    /* فقاعة الدردشة الزجاجية المحسنة والعصرية */
    .chat-bubble {
        position: absolute;
        left: -420px;
        top: 15%;
        transform: translateY(-50%);
        min-width: 380px;
        background: linear-gradient(135deg, 
            rgba(255, 255, 255, 0.25) 0%, 
            rgba(255, 255, 255, 0.15) 30%,
            rgba(255, 255, 255, 0.08) 70%,
            rgba(255, 255, 255, 0.05) 100%);
        color: #ffffff;
        padding: 35px 45px;
        border-radius: 25px;
        font-size: 24px;
        font-weight: 700;
        backdrop-filter: blur(25px) saturate(200%) brightness(1.3);
        -webkit-backdrop-filter: blur(25px) saturate(200%) brightness(1.3);
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow: 
            0 15px 50px rgba(0, 0, 0, 0.3),
            inset 0 2px 4px rgba(255, 255, 255, 0.6),
            0 0 30px rgba(0, 240, 255, 0.4),
            0 0 60px rgba(0, 200, 255, 0.2);
        opacity: 0;
        animation: fadeInBubbleSmooth 2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        cursor: pointer;
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    
    .chat-bubble::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, 
            rgba(255, 255, 255, 0.3) 0%, 
            transparent 20%,
            transparent 40%,
            rgba(0, 240, 255, 0.1) 60%,
            transparent 80%,
            rgba(255, 255, 255, 0.2) 100%);
        border-radius: 25px;
        pointer-events: none;
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0%, 100% {
            opacity: 0.5;
        }
        50% {
            opacity: 1;
        }
    }
    
    .chat-bubble:hover {
        transform: translateY(-50%) scale(1.08);
        background: linear-gradient(135deg, 
            rgba(255, 255, 255, 0.35) 0%, 
            rgba(255, 255, 255, 0.25) 30%,
            rgba(255, 255, 255, 0.15) 70%,
            rgba(255, 255, 255, 0.1) 100%);
        box-shadow: 
            0 20px 70px rgba(0, 0, 0, 0.4),
            inset 0 3px 6px rgba(255, 255, 255, 0.7),
            0 0 40px rgba(0, 240, 255, 0.6),
            0 0 80px rgba(0, 200, 255, 0.3),
            0 0 120px rgba(0, 150, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.6);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .chat-bubble::after {
        content: '';
        position: absolute;
        right: -15px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 12px 0 12px 15px; /* تقليل طول الذيل */
        border-color: transparent transparent transparent rgba(255, 255, 255, 0.15);
        filter: drop-shadow(3px 0 3px rgba(0, 0, 0, 0.1));
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
    
    @keyframes fadeInBubbleSmooth {
        0% {
            opacity: 0;
            transform: translateY(-50%) translateX(40px) scale(0.8);
            filter: blur(5px);
        }
        30% {
            opacity: 0.6;
            transform: translateY(-50%) translateX(20px) scale(0.9);
            filter: blur(2px);
        }
        60% {
            opacity: 0.9;
            transform: translateY(-50%) translateX(5px) scale(0.95);
            filter: blur(1px);
        }
        100% {
            opacity: 1;
            transform: translateY(-50%) translateX(0) scale(1);
            filter: blur(0px);
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
        overflow: visible;
        padding: 0 20px;
        left: 0;
        right: 0;
        transform: none; /* إزالة التحويل */
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
            width: 100%;
            transform: scale(0.8);
        }
        20% {
            opacity: 1;
            transform: scale(1);
        }
        70% {
            opacity: 1;
            width: 100%;
            transform: scale(1);
        }
        80% {
            opacity: 0.8;
            width: 100%;
            transform: scale(0.95);
        }
        90% {
            opacity: 0.4;
            width: 100%;
            transform: scale(0.9);
        }
        100% {
            opacity: 0;
            width: 100%;
            transform: scale(0.8);
        }
    }
    
    /* Continuous loop animation */
    @keyframes continuousLoop {
        0% {
            opacity: 0;
            width: 100%;
            transform: scale(0.8);
        }
        15% {
            opacity: 1;
            transform: scale(1);
        }
        75% {
            opacity: 1;
            width: 100%;
            transform: scale(1);
        }
        85% {
            opacity: 0;
            width: 100%;
            transform: scale(0.9);
        }
        100% {
            opacity: 0;
            width: 100%;
            transform: scale(0.8);
        }
    }
    
    /* شاشات اللابتوب الكبيرة */
    @media (max-width: 1600px) and (min-width: 1401px) {
        .robot-head {
            width: 140px;
            height: 82px;
        }
        
        .robot-face {
            width: 112px;
            height: 62px;
            top: 10px;
        }
        
        .robot-body {
            width: 150px;
            height: 210px;
        }
        
        .arm {
            width: 34px;
            height: 165px;
        }
        
        .arm.left {
            left: -41px;
            transform-origin: 17px 14px;
        }
        
        .arm.right {
            right: -41px;
            transform-origin: 17px 14px;
        }
        
        .chat-bubble {
            right: -210px;
            min-width: 190px;
            font-size: 14px;
            padding: 18px 22px;
        }
        
        .eye {
            width: 33px;
            height: 20px;
            top: 25px;
        }
        
        .eye.left {
            left: 17px;
        }
        
        .eye.right {
            right: 17px;
        }
        
    }

    /* شاشات اللابتوب المتوسطة */
    @media (max-width: 1400px) and (min-width: 1201px) {
        .robot-head {
            width: 110px;
            height: 64px;
        }
        
        .robot-face {
            width: 88px;
            height: 48px;
            top: 8px;
        }
        
        .robot-body {
            width: 120px;
            height: 165px;
        }
        
        .arm {
            width: 27px;
            height: 130px;
        }
        
        .arm.left {
            left: -32px;
            transform-origin: 13px 11px;
        }
        
        .arm.right {
            right: -32px;
            transform-origin: 13px 11px;
        }
        
        .chat-bubble {
            left: -160px;
            min-width: 145px;
            font-size: 12px;
            padding: 14px 18px;
        }
        
        .eye {
            width: 26px;
            height: 16px;
            top: 20px;
        }
        
        .eye.left {
            left: 13px;
        }
        
        .eye.right {
            right: 13px;
        }
        
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .robot-head {
            width: 90px;
            height: 52px;
        }
        
        .robot-face {
            width: 72px;
            height: 40px;
            top: 6px;
        }
        
        .robot-body {
            width: 98px;
            height: 135px;
        }
        
        .arm {
            width: 22px;
            height: 105px;
        }
        
        .arm.left {
            left: -27px;
            transform-origin: 11px 9px;
        }
        
        .arm.right {
            right: -27px;
            transform-origin: 11px 9px;
        }
        
        .chat-bubble {
            right: -135px;
            min-width: 120px;
            font-size: 10px;
            padding: 12px 14px;
        }
        
        .eye {
            width: 21px;
            height: 13px;
            top: 16px;
        }
        
        .eye.left {
            left: 10px;
        }
        
        .eye.right {
            right: 10px;
        }
        
    }
    
    /* Responsive Design for Different Devices */
    
    /* iPhone 12 Pro Max & Similar (414-428px width) - Base Design */
    @media (max-width: 428px) and (min-width: 414px) {
        .prompt-input-container {
            width: 95% !important;
            max-width: 400px !important;
        }
        
        .input-wrapper {
            padding: 15px 25px !important;
            min-height: 160px !important;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                inset 0 1px 2px rgba(255, 255, 255, 0.4),
                0 0 10px rgba(117, 239, 248, 0.3) !important;
        }
        
        .input-wrapper:focus-within {
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.3),
                inset 0 2px 4px rgba(255, 255, 255, 0.5),
                0 0 15px rgba(117, 239, 248, 0.4) !important;
        }
        
        #promptInput {
            font-size: 9px !important;
            min-height: 80px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .send-button {
            flex: 1 !important;
            min-height: 26px !important;
            height: 26px !important;
            font-size: 10px !important;
        }
        
        .close-button {
            min-width: 26px !important;
            width: 26px !important;
            min-height: 26px !important;
            height: 26px !important;
        }
    }
    
    /* Samsung Galaxy A15 & Similar (393-410px width) */
    @media (max-width: 410px) and (min-width: 393px) {
        /* إخفاء الصورة في الحالة العادية */
        .robot::before {
            display: none !important;
        }
        
        .prompt-input-container {
            width: 95% !important;
            max-width: 390px !important;
        }
        
        .input-wrapper {
            padding: 15px 25px !important;
            min-height: 160px !important;
            width: 100% !important;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                inset 0 1px 2px rgba(255, 255, 255, 0.4),
                0 0 10px rgba(117, 239, 248, 0.3) !important;
        }
        
        .input-wrapper:focus-within {
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.3),
                inset 0 2px 4px rgba(255, 255, 255, 0.5),
                0 0 15px rgba(117, 239, 248, 0.4) !important;
        }
        
        #promptInput {
            font-size: 9px !important;
            min-height: 80px !important;
            width: 100% !important;
            padding: 15px 20px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .input-buttons {
            width: 100% !important;
            gap: 6px !important;
            margin-top: 5px !important;
        }
        
        .send-button {
            flex: 1 !important;
            min-height: 25px !important;
            height: 25px !important;
            font-size: 9px !important;
        }
        
        .close-button {
            min-width: 25px !important;
            width: 25px !important;
            min-height: 25px !important;
            height: 25px !important;
        }
        
        /* تقليل عرض الصورة تحت الروبوت لتتناسب مع عرض الـ box */
        .robot-chat-container.fly-to-center .robot::after {
            width: 180px !important;
            height: 90px !important;
            bottom: -38px !important;
            left: 50% !important;
        }
    }
    
    /* Standard Android Phones (375-392px) */
    @media (max-width: 392px) and (min-width: 375px) {
        .prompt-input-container {
            width: 94% !important;
            max-width: 370px !important;
        }
        
        .input-wrapper {
            padding: 14px 23px !important;
            min-height: 175px !important;
            width: 100% !important;
            box-shadow: 
                0 9px 28px rgba(0, 0, 0, 0.19),
                inset 0 1px 2px rgba(255, 255, 255, 0.38),
                0 0 9px rgba(117, 239, 248, 0.28) !important;
        }
        
        .input-wrapper:focus-within {
            box-shadow: 
                0 14px 38px rgba(0, 0, 0, 0.28),
                inset 0 2px 4px rgba(255, 255, 255, 0.48),
                0 0 14px rgba(117, 239, 248, 0.38) !important;
        }
        
        #promptInput {
            font-size: 8.5px !important;
            min-height: 78px !important;
            width: 100% !important;
            padding: 14px 19px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .input-buttons {
            width: 100% !important;
            gap: 5px !important;
            margin-top: 5px !important;
        }
        
        .send-button {
            flex: 1 !important;
            min-height: 24px !important;
            height: 24px !important;
            font-size: 8.5px !important;
        }
        
        .close-button {
            min-width: 24px !important;
            width: 24px !important;
            min-height: 24px !important;
            height: 24px !important;
        }
    }
    
    /* Smaller Phones (360-374px) */
    @media (max-width: 374px) and (min-width: 360px) {
        /* إخفاء الصورة في الحالة العادية */
        .robot::before {
            display: none !important;
        }
        
        .prompt-input-container {
            width: 93% !important;
            max-width: 350px !important;
        }
        
        .input-wrapper {
            padding: 13px 21px !important;
            min-height: 167px !important;
            width: 101% !important;
            box-shadow: 
                0 8px 26px rgba(0, 0, 0, 0.18),
                inset 0 1px 2px rgba(255, 255, 255, 0.36),
                0 0 8px rgba(117, 239, 248, 0.26) !important;
        }
        
        .input-wrapper:focus-within {
            box-shadow: 
                0 13px 36px rgba(0, 0, 0, 0.26),
                inset 0 2px 4px rgba(255, 255, 255, 0.46),
                0 0 13px rgba(117, 239, 248, 0.36) !important;
        }
        
        #promptInput {
            font-size: 8px !important;
            min-height: 76px !important;
            width: 100% !important;
            padding: 13px 18px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .input-buttons {
            width: 100% !important;
            gap: 4px !important;
            margin-top: 4px !important;
        }
        
        .send-button {
            flex: 1 !important;
            min-height: 23px !important;
            height: 23px !important;
            font-size: 8px !important;
        }
        
        .close-button {
            min-width: 23px !important;
            width: 23px !important;
            min-height: 23px !important;
            height: 23px !important;
        }
        
        /* تقليل عرض الصورة والتوهج للشاشات 740x360 */
        .robot-chat-container.fly-to-center .robot::after {
            width: 160px !important;
            height: 80px !important;
            bottom: -35px !important;
            left: 50% !important;
            filter: drop-shadow(0 0 20px rgba(117, 239, 248, 0.6)) !important;
        }
    }
    
    /* Very Small Phones (below 360px) */
    @media (max-width: 359px) {
        .prompt-input-container {
            width: 92% !important;
            max-width: 330px !important;
        }
        
        .input-wrapper {
            padding: 12px 19px !important;
            min-height: 145px !important;
            width: 100% !important;
            box-shadow: 
                0 7px 24px rgba(0, 0, 0, 0.17),
                inset 0 1px 2px rgba(255, 255, 255, 0.34),
                0 0 7px rgba(117, 239, 248, 0.24) !important;
        }
        
        .input-wrapper:focus-within {
            box-shadow: 
                0 12px 34px rgba(0, 0, 0, 0.24),
                inset 0 2px 4px rgba(255, 255, 255, 0.44),
                0 0 12px rgba(117, 239, 248, 0.34) !important;
        }
        
        #promptInput {
            font-size: 7.5px !important;
            min-height: 74px !important;
            width: 100% !important;
            padding: 12px 17px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .input-buttons {
            width: 100% !important;
            gap: 4px !important;
            margin-top: 4px !important;
        }
        
        .send-button {
            flex: 1 !important;
            min-height: 22px !important;
            height: 22px !important;
            font-size: 7.5px !important;
        }
        
        .close-button {
            min-width: 22px !important;
            width: 22px !important;
            min-height: 22px !important;
            height: 22px !important;
        }
    }
    
    @media (max-width: 768px) {
        .robot-chat-container {
            min-height: 120px;
        }
        
        .robot-head {
            width: 75px;
            height: 44px;
            margin-bottom: 10px;
        }
        
        .robot-face {
            width: 60px;
            height: 34px;
            top: 5px;
        }
        
        .robot-body {
            width: 82px;
            height: 105px;
        }
        
        .arm {
            width: 18px;
            height: 82px;
            top: 22px;
        }
        
        .arm.left {
            left: -21px;
            transform-origin: 9px 7px;
        }
        
        .arm.right {
            right: -21px;
            transform-origin: 9px 7px;
        }
        
        .chat-bubble {
            right: -110px;
            min-width: 95px;
            font-size: 10px;
            padding: 9px 12px;
            min-height: 50px;
        }
        
        
        .eye {
            width: 21px;
            height: 13px;
            top: 16px;
        }
        
        .eye.left {
            left: 10px;
        }
        
        .eye.right {
            right: 10px;
        }
        
        .face-shine {
            top: 6px;
            left: 12px;
            width: 18px;
            height: 9px;
        }
        
        .body-shine {
            top: 15px;
            left: 15px;
            width: 30px;
            height: 48px;
        }
    }
    
    @media (max-width: 480px) {
        .robot-chat-container {
            min-height: 90px;
        }
        
        .robot-head {
            width: 55px;
            height: 32px;
            margin-bottom: 6px;
        }
        
        .robot-face {
            width: 44px;
            height: 24px;
            top: 4px;
        }
        
        .robot-body {
            width: 60px;
            height: 80px;
        }
        
        .arm {
            width: 14px;
            height: 62px;
            top: 18px;
        }
        
        .arm.left {
            left: -17px;
            transform-origin: 7px 5px;
        }
        
        .arm.right {
            right: -17px;
            transform-origin: 7px 5px;
        }
        
        .chat-bubble {
            right: -75px;
            min-width: 65px;
            font-size: 8px;
            padding: 6px 9px;
            min-height: 40px;
        }
        
        
        .eye {
            width: 15px;
            height: 10px;
            top: 12px;
        }
        
        .eye.left {
            left: 7px;
        }
        
        .eye.right {
            right: 7px;
        }
        
        .face-shine {
            top: 4px;
            left: 9px;
            width: 12px;
            height: 6px;
        }
        
        .body-shine {
            top: 11px;
            left: 11px;
            width: 21px;
            height: 34px;
        }
    }
    
    @media (max-width: 360px) {
        .robot-chat-container {
            min-height: 80px;
        }
        
        .robot-head {
            width: 42px;
            height: 24px;
            margin-bottom: 5px;
        }
        
        .robot-face {
            width: 34px;
            height: 20px;
            top: 2px;
        }
        
        .robot-body {
            width: 48px;
            height: 60px;
        }
        
        .arm {
            width: 11px;
            height: 47px;
            top: 14px;
        }
        
        .arm.left {
            left: -13px;
            transform-origin: 5px 4px;
        }
        
        .arm.right {
            right: -13px;
            transform-origin: 5px 4px;
        }
        
        .chat-bubble {
            right: -60px;
            min-width: 55px;
            font-size: 7px;
            padding: 5px 8px;
            min-height: 35px;
        }
        
        
        .eye {
            width: 12px;
            height: 8px;
            top: 10px;
        }
        
        .eye.left {
            left: 5px;
        }
        
        .eye.right {
            right: 5px;
        }
        
        .face-shine {
            top: 4px;
            left: 8px;
            width: 9px;
            height: 4px;
        }
        
        .body-shine {
            top: 9px;
            left: 9px;
            width: 17px;
            height: 26px;
        }
    }
    
    /* شاشات كبيرة جداً */
    @media (min-width: 1600px) {
        .robot-head {
            width: 160px;
            height: 92px;
        }
        
        .robot-face {
            width: 128px;
            height: 68px;
            top: 12px;
        }
        
        .robot-body {
            width: 176px;
            height: 240px;
        }
        
        .arm {
            width: 38px;
            height: 192px;
        }
        
        .arm.left {
            left: -46px;
            transform-origin: 19px 16px;
        }
        
        .arm.right {
            right: -46px;
            transform-origin: 19px 16px;
        }
        
        .chat-bubble {
            left: -240px;
            min-width: 220px;
            font-size: 14px;
            padding: 20px 24px;
        }
        
        .eye {
            width: 36px;
            height: 22px;
            top: 26px;
        }
        
        .eye.left {
            left: 19px;
        }
        
        .eye.right {
            right: 19px;
        }
    }
    
    /* Large screens (laptops) - Robot smaller on left, chat bubble on right */
    @media (min-width: 1201px) and (max-width: 1599px) {
        .robot-head {
            width: 120px;
            height: 70px;
        }
        
        .robot-face {
            width: 96px;
            height: 53px;
            top: 8px;
        }
        
        .robot-body {
            width: 130px;
            height: 180px;
        }
        
        .arm {
            width: 28px;
            height: 142px;
        }
        
        .arm.left {
            left: -34px;
            transform-origin: 14px 12px;
        }
        
        .arm.right {
            right: -34px;
            transform-origin: 14px 12px;
        }
        
        .chat-bubble {
            left: 150px; /* Move chat bubble to the right side of robot */
            right: auto;
            top: 15%; /* رفع الشات بوكس قليلاً للشاشات الكبيرة */
            min-width: 180px;
            font-size: 12px;
            padding: 16px 20px;
            border-radius: 20px;
        }
        
        .chat-bubble::after {
            content: '';
            position: absolute;
            left: -15px; /* Arrow pointing left instead of right */
            right: auto;
            top: 35%; /* توجيه الذيل نحو وجه الروبوت */
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 12px 15px 12px 0; /* تقليل طول الذيل */
            border-color: transparent rgba(255, 255, 255, 0.15) transparent transparent;
            filter: drop-shadow(-3px 0 3px rgba(0, 0, 0, 0.1));
        }
        
        .eye {
            width: 28px;
            height: 17px;
            top: 20px;
        }
        
        .eye.left {
            left: 14px;
        }
        
        .eye.right {
            right: 14px;
        }
        
    }
    
    /* تابلت أفقي */
    @media (max-width: 992px) {
        .robot-chat-container {
            min-height: 180px;
        }
        
        .robot-head {
            width: 110px;
            height: 64px;
        }
        
        .robot-face {
            width: 88px;
            height: 48px;
            top: 8px;
        }
        
        .robot-body {
            width: 120px;
            height: 165px;
        }
        
        .arm {
            width: 27px;
            height: 130px;
        }
        
        .arm.left {
            left: -32px;
            transform-origin: 13px 11px;
        }
        
        .arm.right {
            right: -32px;
            transform-origin: 13px 11px;
        }
        
        .chat-bubble {
            left: -160px;
            min-width: 145px;
            font-size: 12px;
            padding: 14px 18px;
        }
        
        .eye {
            width: 26px;
            height: 16px;
            top: 20px;
        }
        
        .eye.left {
            left: 13px;
        }
        
        .eye.right {
            right: 13px;
        }
        
    }
    
    /* موبايل كبير */
    @media (max-width: 576px) {
        .container {
            padding: 0 0.5rem;
            margin: -27px auto 0 auto;
        }
        
        .robot-chat-container {
            min-height: 130px;
            padding: 10px 3px;
        }
        
        .robot-head {
            width: 65px;
            height: 38px;
            margin-bottom: 8px;
        }
        
        .robot-face {
            width: 52px;
            height: 29px;
            top: 4px;
        }
        
        .robot-body {
            width: 72px;
            height: 95px;
        }
        
        .arm {
            width: 15px;
            height: 74px;
            top: 17px;
        }
        
        .arm.left {
            left: -19px;
            transform-origin: 7px 6px;
        }
        
        .arm.right {
            right: -19px;
            transform-origin: 7px 6px;
        }
        
        .chat-bubble {
            right: -95px;
            min-width: 85px;
            font-size: 9px;
            padding: 8px 10px;
            min-height: 42px;
            border-radius: 10px;
        }
        
        .chat-bubble::after {
            border-width: 8px 10px 8px 0;
            left: -8px;
        }
        
        
        .eye {
            width: 17px;
            height: 11px;
            top: 12px;
        }
        
        .eye::before {
            width: 6px;
            height: 4px;
        }
        
        .eye.left {
            left: 8px;
        }
        
        .eye.right {
            right: 8px;
        }
        
        .face-shine {
            top: 4px;
            left: 8px;
            width: 13px;
            height: 6px;
        }
        
        .body-shine {
            top: 11px;
            left: 11px;
            width: 23px;
            height: 32px;
        }
        
        .robot-shadow {
            bottom: -42px;
            width: 65px;
            height: 14px;
        }
    }
    
    /* موبايل صغير */
    @media (max-width: 400px) {
        .robot-chat-container {
            min-height: 110px;
        }
        
        .robot-head {
            width: 50px;
            height: 29px;
            margin-bottom: 5px;
        }
        
        .robot-face {
            width: 40px;
            height: 23px;
            top: 3px;
        }
        
        .robot-body {
            width: 55px;
            height: 75px;
        }
        
        .arm {
            width: 12px;
            height: 60px;
            top: 14px;
        }
        
        .arm.left {
            left: -14px;
            transform-origin: 6px 5px;
        }
        
        .arm.right {
            right: -14px;
            transform-origin: 6px 5px;
        }
        
        .chat-bubble {
            right: -70px;
            min-width: 65px;
            font-size: 7px;
            padding: 6px 8px;
            min-height: 38px;
        }
        
        .eye {
            width: 13px;
            height: 8px;
            top: 9px;
        }
        
        .eye.left {
            left: 7px;
        }
        
        .eye.right {
            right: 7px;
        }
        
    }
    
    /* تصميم للشاشات الكبيرة (Desktops & Large Tablets) */
    @media (min-width: 1025px) and (max-width: 1200px) {
        .robot-chat-container {
            right: 33px !important;
            left: auto !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container {
            left: -785px !important;
            right: auto !important;
        }
        
        .chat-bubble {
            position: absolute !important;
            left: 150px !important;
            right: auto !important;
            top: 15% !important;
            transform: translateY(-50%) !important;
            min-width: 250px !important;
            max-width: 320px !important;
            font-size: 15px !important;
            padding: 16px 24px !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble {
            left: auto !important;
            right: 150px !important;
        }
        
        .chat-bubble::after {
            right: -15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble::after {
            left: -15px !important;
            right: auto !important;
        }
    }
    
    /* تصميم عام للشاشات الكبيرة */
    .robot-chat-container {
        position: fixed;
        top: 84%;
        right: 20px;
        transform: translateY(-50%);
        z-index: 1000;
        pointer-events: none;
    }
    
    /* تصميم الروبوت للشاشات الكبيرة واللابتوب (> 1200px) */
    @media (min-width: 1201px) {
        .robot-chat-container {
            position: fixed !important;
            top: 50% !important;
            left: 20px !important;
            right: auto !important;
            bottom: auto !important;
            transform: translateY(-50%) rotate(0deg) !important;
            z-index: 1000 !important;
            pointer-events: none !important;
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            align-items: center !important;
            gap: 40px !important;
            width: auto !important;
            height: auto !important;
            min-height: auto !important;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }
        
        /* للغة الإنجليزية: الروبوت على يمين الفورم */
        html:not([dir="rtl"]) .robot-chat-container {
            left: calc(50% + 25% + 20px) !important;
            right: auto !important;
        }
        
        /* الروبوت بشكل مستقيم تماماً مع animation الطيران - حجم أصغر */
        .robot {
            position: relative !important;
            pointer-events: auto !important;
            flex-shrink: 0 !important;
            animation: floatLarge 4s ease-in-out infinite !important;
        }
        
        /* إلغاء دوران الرأس */
        .robot-head {
            transform: rotate(0deg) !important;
            animation: none !important;
        }
        
        /* إلغاء دوران الجسم */
        .robot-body {
            transform: rotate(0deg) !important;
            animation: none !important;
        }
        
        /* السماح للعيون بالحركة باستخدام CSS variables */
        .robot .eye {
            transform: rotate(0deg) !important;
            animation: eyeBlink 6s infinite, eyeGlow 2.5s ease-in-out infinite !important;
            overflow: hidden !important; /* منع العيون البيضاء من الخروج */
        }
        
        .robot .eye.left {
            transform: rotate(0deg) !important;
        }
        
        .robot .eye.right {
            transform: rotate(0deg) !important;
        }
        
        /* السماح للعيون بالحركة في ::before - بدون !important للسماح بالحركة */
        .robot .eye::before {
            transform: translate(calc(-50% + var(--moveX, 0)), calc(-50% + var(--moveY, 0)));
        }
        
        /* إلغاء دوران الذراعين */
        .robot .arm {
            transform: rotate(0deg) !important;
            animation: none !important;
        }
        
        .robot .arm.left {
            transform: rotate(0deg) !important;
            animation: none !important;
        }
        
        .robot .arm.right {
            transform: rotate(0deg) !important;
            animation: none !important;
        }
        
        /* chatBubble بجانب الروبوت - حجم أصغر وبعيد أكثر */
        .chat-bubble {
            position: relative !important;
            left: clamp(75px, 15vw, 167px) !important;
            right: auto !important;
            top: clamp(1px, -15vh, -180px) !important;
            transform: scale(0.8) !important;
            min-width: 220px !important;
            max-width: 280px !important;
            font-size: 13px !important;
            padding: 14px 20px !important;
            pointer-events: auto !important;
            cursor: pointer !important;
            align-self: flex-start !important;
            margin-top: -60px !important;
        }
        
        /* للغة العربية: إبعاد chat-bubble أكثر إلى اليسار */
        html[dir="rtl"] .chat-bubble {
            left: clamp(95px, 18vw, 178px) !important;
            right: auto !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble {
            left: clamp(75px, 15vw, 167px) !important;
            right: auto !important;
            /* margin-left: 80px !important; بعيد أكثر عن وجه الروبوت باتجاه اليسار للغة الإنجليزية */
        }
        
        .chat-bubble::after {
            left: -15px !important;
            right: auto !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            border-width: 12px 15px 12px 0 !important;
            border-color: transparent rgba(255, 255, 255, 0.15) transparent transparent !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble::after {
            left: -15px !important;
            right: auto !important;
            border-width: 12px 15px 12px 0 !important;
            border-color: transparent rgba(255, 255, 255, 0.15) transparent transparent !important;
        }
    }
    
    /* جعل form-section في نصف الشاشة للشاشات الكبيرة */
    @media (min-width: 1201px) {
        /* للغة الإنجليزية: form-section في نصف الشاشة */
        html:not([dir="rtl"]) body .container {
            max-width: 50% !important;
            width: 50% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        
        html:not([dir="rtl"]) body .form-container {
            margin-left: 0 !important;
            margin-right: auto !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        
        /* للغة العربية: form-section في نصف الشاشة */
        html[dir="rtl"] body .container,
        body .container {
            max-width: 50% !important;
            width: 50% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        
        html[dir="rtl"] body .form-container,
        body .form-container {
            max-width: 100% !important;
            width: 100% !important;
        }
        
        /* التأكد من أن form-section نفسه في نصف الشاشة */
        body .form-section {
            max-width: 100% !important;
            width: 100% !important;
        }
        
        /* إظهار prompt-input-container تحت الروبوت عند النقر */
        .prompt-input-container {
            position: absolute !important;
            bottom: -200px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 95% !important;
            max-width: 400px !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            z-index: 1000 !important;
        }
        
        .prompt-input-container.show {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            bottom: -180px !important;
        }
    }
    
    /* تعديل موضع prompt-input-container في الشاشات الكبيرة */
    @media (min-width: 1201px) {
        .prompt-input-container.show {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            bottom: -275px !important;
            left: -27% !important;
        }
    }
    
    /* CSS للشاشات الكبيرة - إظهار prompt-input-container */
    @media (min-width: 1201px) {
        .input-wrapper {
            padding: 20px 30px !important;
            min-height: 200px !important;
            width: 100% !important;
            /* max-width: 224% !important; */
            min-width: 237% !important;
        }
        
        #promptInput {
            font-size: 18px !important;
            min-height: 150px !important;
            max-height: 250px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .send-button {
            height: 40px !important;
            min-height: 40px !important;
            font-size: 16px !important;
            padding: 0 25px !important;
        }
        
        .close-button {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
        }
    }
    
    /* للشاشات الكبيرة (1201px وأكثر) - robot-section للغة الإنجليزية فقط */
    @media (min-width: 1201px) {
        html:not([dir="rtl"]) .robot-section {
            left: 91% !important;
            right: auto !important;
            transform: translateY(-50%) rotate(8deg) !important;
        }
        
        /* robot-section في النصف فقط عندما يكون الروبوت مفتوحاً */
        .robot-section.robot-opened {
            left: 50% !important;
            right: auto !important;
            transform: translateY(-50%) !important;
        }
        
        html:not([dir="rtl"]) .robot-section.robot-opened {
            left: 50% !important;
            right: auto !important;
            transform: translateY(-50%) !important;
        }
        
        /* Overlay غامق كامل عند النقر على الروبوت */
        .robot-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(0, 0, 0, 0.85) !important;
            z-index: 999 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transition: opacity 0.3s ease, visibility 0.3s ease !important;
        }
        
        .robot-overlay.show {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }
        
        /* الروبوت في منتصف الشاشة عند النقر */
        .robot-chat-container.robot-centered {
            left: 50% !important;
            right: auto !important;
            top: 50% !important;
            bottom: auto !important;
            transform: translate(-50%, -50%) rotate(0deg) !important;
            z-index: 1001 !important;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container.robot-centered {
            left: 50% !important;
            right: auto !important;
            transform: translate(-50%, -50%) rotate(0deg) !important;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }
        
        /* Shadow تحت الروبوت في المنتصف - استخدام الصورة */
        .robot-chat-container.robot-centered .robot-shadow {
            position: absolute !important;
            bottom: -90px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 200px !important;
            height: 135px !important;
            background-image: url('/images/Polygon%202.png') !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            filter: drop-shadow(0 0 30px rgba(117, 239, 248, 0.8)) !important;
            opacity: 0 !important;
            display: none !important;
            z-index: -1 !important;
        }
        
        /* للغة الإنجليزية: التأكد من أن shadow في المنتصف */
        html:not([dir="rtl"]) .robot-chat-container.robot-centered .robot-shadow {
            left: 50% !important;
            width: 405px !important;
            height: 195px !important;
            bottom: -147px !important;
        }
        
        /* للغة العربية: التأكد من أن shadow في المنتصف */
        html[dir="rtl"] .robot-chat-container.robot-centered .robot-shadow {
            left: 53% !important;
            width: 406px !important;
            height: 163px !important;
            bottom: -128px !important;
        }
        
        .robot-chat-container.robot-centered .robot-shadow {
            opacity: 1 !important;
            display: block !important;
        }
        
        /* تعديل موضع prompt-input-container تحت الروبوت في المنتصف */
        .robot-chat-container.robot-centered .prompt-input-container {
            position: absolute !important;
            bottom: -322px !important;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            width: 119% !important;
            max-width: 500px !important;
            z-index: 1002 !important;
        }
        
        /* للغة الإنجليزية: prompt-input-container في المنتصف */
        html:not([dir="rtl"]) .robot-chat-container.robot-centered .prompt-input-container {
            left: -35% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
        }
        
        /* للغة العربية: prompt-input-container في المنتصف */
        html[dir="rtl"] .robot-chat-container.robot-centered .prompt-input-container {
            left: 137% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
        }
        
        .robot-chat-container.robot-centered .prompt-input-container.show {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }
    }
    
    /* للشاشات الكبيرة جداً (1600px وأكثر) */
    @media (min-width: 1600px) {
        .robot-section {
            left: 125px !important;
        }
        
        /* robot-section في النصف فقط عندما يكون الروبوت مفتوحاً */
        .robot-section.robot-opened {
            left: 50% !important;
        }
        
        /* للغة الإنجليزية: التأكد من أن الروبوت على يمين الفورم */
        html:not([dir="rtl"]) .robot-chat-container {
            left: calc(50% + 25% + 20px) !important;
            right: auto !important;
        }
    }
    
    @keyframes robotSpin {
        0% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(180deg); }
        100% { transform: scale(1.2) rotate(360deg); }
    }
    
    /* تصميم خاص للشاشات 820x1180 (iPad Portrait) */
    @media (min-width: 815px) and (max-width: 825px) {
        .robot-chat-container {
            position: fixed !important;
            right: -20px !important;
            left: auto !important;
            bottom: 40px !important;
            top: auto !important;
            rotate: -12deg !important;
            z-index: 1000 !important;
            transform: scale(1.15) !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container {
            left: -764px !important;
            right: auto !important;
            rotate: 12deg !important;
        }
        
        .chat-bubble {
            position: absolute !important;
            left: auto !important;
            right: 130px !important;
            top: 25% !important;
            transform: translateY(-50%) !important;
            min-width: 240px !important;
            max-width: 300px !important;
            font-size: 14px !important;
            padding: 16px 22px !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble {
            left: 130px !important;
            right: auto !important;
        }
        
        /* عند الطيران للمنتصف */
        .robot-chat-container.fly-to-center {
            left: 50% !important;
            right: auto !important;
            top: 42% !important;
            bottom: auto !important;
            transform: translate(-50%, -50%) !important;
            rotate: 0deg !important;
            z-index: 9999 !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center {
            left: 50% !important;
            right: auto !important;
            top: 42% !important;
            bottom: auto !important;
            transform: translate(-50%, -50%) !important;
            rotate: 0deg !important;
        }
        
        /* إظهار الذراعين عند الطيران */
        .robot-chat-container.fly-to-center .arm.left,
        .robot-chat-container.fly-to-center .arm.right {
            display: block !important;
        }
        
        .robot-chat-container.fly-to-center .robot {
            transform: scale(1.9) rotateY(360deg) !important;
        }
        
        .robot-chat-container.fly-to-center .robot::after {
            width: 280px !important;
            height: 170px !important;
            bottom: -48px !important;
            left: 58% !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center .robot::after {
            left: 50% !important;
        }
        
        /* الـ input للشاشة الكبيرة */
        .prompt-input-container {
            bottom: -220px !important;
        }
        
        .input-wrapper {
            padding: 20px 28px !important;
            min-height: 220px !important;
            width: 85% !important;
            max-width: 550px !important;
        }
        
        #promptInput {
            font-size: 18px !important;
            min-height: 160px !important;
            max-height: 280px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .send-button {
            height: 40px !important;
            min-height: 40px !important;
            font-size: 16px !important;
            padding: 0 24px !important;
        }
        
        .close-button {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
        }
    }
    
    /* تصميم خاص للأجهزة اللوحية (Tablets & iPad) */
    @media (min-width: 769px) and (max-width: 814px), (min-width: 826px) and (max-width: 1024px) {
        .robot-chat-container {
            position: fixed !important;
            left: -30px !important;
            bottom: 30px !important;
            top: auto !important;
            rotate: 15deg !important;
            z-index: 1000 !important;
        }
        
        /* للغة الإنجليزية */
        html:not([dir="rtl"]) .robot-chat-container {
            left: auto !important;
            right: -30px !important;
            bottom: 30px !important;
            rotate: -15deg !important;
        }
        
        .robot {
            transform: scale(1.1) !important;
        }
        
        .chat-bubble {
            position: absolute !important;
            left: 120px !important;
            right: auto !important;
            top: 30% !important;
            transform: translateY(-50%) !important;
            min-width: 220px !important;
            max-width: 280px !important;
            font-size: 14px !important;
            padding: 14px 20px !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble {
            left: auto !important;
            right: 120px !important;
        }
        
        .chat-bubble::after {
            right: -15px !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble::after {
            left: -15px !important;
            right: auto !important;
        }
        
        /* تأثير الطيران للـ tablets */
        .robot-chat-container.fly-to-center {
            left: 50% !important;
            top: 45% !important;
            transform: translate(-50%, -50%) !important;
            rotate: 0deg !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center {
            left: 50% !important;
            top: 45% !important;
        }
        
        .robot-chat-container.fly-to-center .robot {
            transform: scale(1.8) rotateY(360deg) !important;
        }
        
        .robot-chat-container.fly-to-center .robot::after {
            width: 250px !important;
            height: 160px !important;
            bottom: -50px !important;
            left: 58% !important;
        }
        
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center .robot::after {
            left: 50% !important;
        }
        
        /* الـ input للـ tablets */
        .prompt-input-container {
            bottom: -200px !important;
        }
        
        .input-wrapper {
            padding: 18px 26px !important;
            min-height: 200px !important;
            width: 90% !important;
            max-width: 500px !important;
        }
        
        #promptInput {
            font-size: 17px !important;
            min-height: 140px !important;
            max-height: 240px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .send-button {
            height: 38px !important;
            min-height: 38px !important;
            font-size: 15px !important;
            padding: 0 22px !important;
        }
        
        .close-button {
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            min-height: 38px !important;
        }
    }
    
    /* تغيير موقع الروبوت للهواتف */
    @media (max-width: 768px) {
        .robot {
            transform: none !important; /* منع تحريك الروبوت بالماوس */
        }
        
        .robot-chat-container {
            position: fixed !important;
            /* اختر أحد هذه الخيارات: */
            
            /* الخيار 1: الزاوية السفلية اليمنى */
            left: -690px !important;
            /* right: 20px !important; */
            bottom: 20px !important;
            top: 250px !important;
            rotate: 20deg !important;
            /* الخيار 2: الزاوية السفلية اليسرى */
            /* left: 20px !important;
            right: auto !important;
            bottom: 20px !important;
            top: auto !important; */
            
            /* الخيار 3: منتصف الجانب الأيمن */
            /* left: auto !important;
            right: 20px !important;
            top: 50% !important;
            bottom: auto !important;
            transform: translateY(-50%) !important; */
            
            width: auto !important;
            max-width: auto !important;
            overflow: visible !important;
            z-index: 1000 !important;
        }
        
        .chat-bubble {
            position: relative !important;
            left: 149px !important;
            right: auto !important;
            top: -235px !important;
            transform: none !important;
            margin: 10px auto !important;
            max-width: 45% !important; /* زيادة العرض قليلاً */
            color: #667eea !important; /* لون خلفية الصفحة */
            font-size: 10px !important; /* حجم خط أصغر للشاشات الصغيرة */
            padding: 8px 12px !important; /* تحسين المساحة الداخلية */
        }
        
        /* تصميم معكوس للغة الإنجليزية */
        html:not([dir="rtl"]) .robot-chat-container {
            left: auto !important;
            right: -190px !important;
            bottom: 20px !important;
            top: 250px !important;
            rotate: -20deg !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble {
            left: auto !important;
            right: 85px !important;
            top: -147px !important;
        }
        
        /* تعديل اتجاه الذيل للغة الإنجليزية */
        html:not([dir="rtl"]) .chat-bubble::after {
            left: auto !important;
            right: 20px !important;
            border-width: 12px 15px 12px 0 !important;
            border-color: transparent rgba(255, 255, 255, 0.15) transparent transparent !important;
        }
        
        /* تعديل الذراعين للغة الإنجليزية - إظهار الذراع الأيسر وإخفاء الأيمن */
        html:not([dir="rtl"]) .arm.left {
            display: block !important;
        }
        
        html:not([dir="rtl"]) .arm.right {
            display: none !important;
        }
        
        /* تحسينات إضافية للشاشات الصغيرة */
        .chat-bubble .text-line {
            font-size: 8px !important; /* حجم خط أصغر للنص */
            line-height: 1.2 !important; /* تقليل المسافة بين الأسطر */
            padding: 1px 3px !important; /* تقليل المساحة الداخلية */
            white-space: normal !important; /* السماح بكسر السطر */
            overflow: visible !important; /* إظهار النص كاملاً */
            max-width: none !important; /* إزالة القيود على العرض */
            word-wrap: break-word !important; /* كسر الكلمات الطويلة */
            word-break: break-all !important; /* كسر أي كلمة طويلة */
        }
        
        /* تحديد حركة العيون في الهواتف */
        .eye::before {
            max-width: 10px !important; /* تقليل حجم العين المتحركة */
            max-height: 6px !important;
        }
        
        /* منع خروج العيون من الإطار الأزرق */
        .robot-face {
            overflow: hidden !important;
        }
        
        /* تقليل مساحة حركة العيون */
        .eye {
            overflow: hidden !important;
        }
        
        /* تأثير الطيران السلس للروبوت */
        .robot-chat-container.fly-to-center {
            left: 50% !important;
            top: 40% !important;
            bottom: auto !important;
            right: auto !important;
            transform: translate(-50%, -50%) !important;
            rotate: 0deg !important;
            z-index: 9999 !important;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }
        
        /* تأكد من أن الطيران يعمل للغة الإنجليزية أيضاً */
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center {
            left: 50% !important;
            top: 40% !important;
            bottom: auto !important;
            right: auto !important;
            transform: translate(-50%, -50%) !important;
            rotate: 0deg !important;
            z-index: 9999 !important;
        }
        
        /* إظهار الذراعين عندما يكون الروبوت في المنتصف */
        .robot-chat-container.fly-to-center .arm.left,
        .robot-chat-container.fly-to-center .arm.right {
            display: block !important;
        }
        
        /* إظهار الذراع اليمين للغة الإنجليزية عند الطيران */
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center .arm.right {
            display: block !important;
        }
        
        /* تحريك الشادو شوي لليسار للإنجليزية */
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center .robot::after {
            left: 50% !important;
            width: 265px !important;
            height: 160px !important;
        }
        
        .robot-chat-container.fly-to-center .robot {
            animation: none !important;
            transform: scale(1.5) rotateY(360deg) !important;
            transition: transform 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }
        
        /* إخفاء الظل الأصلي وإضافة الضوء الخفيف */
        .robot-chat-container.fly-to-center .robot-shadow {
            opacity: 0 !important;
        }
        
        .robot-chat-container.fly-to-center .robot::after {
            content: '' !important;
            position: absolute !important;
            bottom: -41px !important;
            left: 58% !important;
            transform: translateX(-50%) !important;
            width: 327px !important;
            height: 146px !important;
            background-image: url('/images/Polygon%202.png') !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            filter: drop-shadow(0 0 30px rgba(117, 239, 248, 0.8)) !important;
            animation: lightGlow 2s ease-in-out infinite alternate !important;
            z-index: -1 !important;
        }
        
        @keyframes lightGlow {
            0% {
                opacity: 0.6;
                transform: translateX(-50%) scale(1);
                filter: blur(30px);
            }
            50% {
                opacity: 1;
                transform: translateX(-50%) scale(1.3);
                filter: blur(35px);
            }
            100% {
                opacity: 0.8;
                transform: translateX(-50%) scale(1.1);
                filter: blur(25px);
            }
        }
        
        /* إخفاء الشات بابل عند الطيران */
        .robot-chat-container.fly-to-center .chat-bubble {
            opacity: 0 !important;
            pointer-events: none !important;
            transition: opacity 0.3s ease !important;
        }
        
        /* إظهار الـ input عند الطيران */
        .robot-chat-container.fly-to-center .prompt-input-container {
            opacity: 1 !important;
            visibility: visible !important;
            bottom: -160px !important;
        }
        
        /* تحسين الـ input للشاشات الصغيرة */
        .prompt-input-container {
            width: 300% !important;
            max-width: 1550px !important;
           
        }
        
        .input-wrapper {
            padding: 6px 12px !important;
        }
        
        #promptInput {
            font-size: 16px !important;
            padding: 12px 15px !important;
            min-height: 120px !important;
            max-height: 220px !important;
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        .send-button {
            width: 35px !important;
            height: 35px !important;
        }
    }
    
    /* تحسينات خاصة لشاشات Samsung Galaxy A15 */
    @media (max-width: 412px) and (min-width: 360px) {
        /* إخفاء الصورة في الحالة العادية */
        .robot::before {
            display: none !important;
        }
        
        /* موقع الروبوت للعربي */
        .robot-chat-container {
            left: -590px !important;
            bottom: 25px !important;
            top: 250px !important;
            rotate: 18deg !important;
        }
        
        /* موقع الروبوت للإنجليزي */
        html:not([dir="rtl"]) .robot-chat-container {
            left: auto !important;
            right:  -120px !important;
            bottom: 25px !important;
            top: 250px !important;
            rotate: -12deg !important;
        }
        
        .chat-bubble {
            left: 80px !important;
            top: -140px !important;
            max-width: 50% !important; /* عرض أكبر للشاشات المتوسطة */
            font-size: 9px !important;
            padding: 6px 10px !important;
        }
        
        html:not([dir="rtl"]) .chat-bubble {
            left: auto !important;
            right: 75px !important;
            top: -135px !important;
        }
        
        .chat-bubble .text-line {
            font-size: 8px !important;
            line-height: 1.1 !important;
            padding: 1px 2px !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            word-break: break-all !important;
        }
        
        /* تقليل عرض الشادو لـ Galaxy A15 */
        .robot-chat-container.fly-to-center .robot::after {
            width: 220px !important;
            height: 140px !important;
            bottom: -30px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
        }
        
        /* ضبط الشادو للإنجليزي في Galaxy A15 */
        html:not([dir="rtl"]) .robot-chat-container.fly-to-center .robot::after {
            left: 50% !important;
            width: 200px !important;
            height: 135px !important;
            bottom: -35px !important;
        }
    }
    
    /* تحسينات خاصة لشاشات iPhone 12 Pro Max */
    @media (max-width: 428px) and (min-width: 413px) {
    
        
        .chat-bubble {
            left: 90px !important; /* تحريك البوكس لليمين قليلاً */
            top: -140px !important; /* رفع البوكس قليلاً */
            max-width: 48% !important;
            font-size: 9px !important;
            padding: 8px 12px !important;
        }
        
        .chat-bubble .text-line {
            font-size: 8px !important;
            line-height: 1.2 !important;
            padding: 1px 3px !important;
        }
        
        /* الحفاظ على التصميم الأصلي للصورة تحت الروبوت */
        .robot-chat-container.fly-to-center .robot::after {
            width: 300px !important;
            height: 150px !important;
            bottom: -45px !important;
            left: 58% !important;
            filter: drop-shadow(0 0 30px rgba(117, 239, 248, 0.8)) !important;
        }
    }
    
    /* موبايل قديم صغير جداً */
    @media (max-width: 320px) {
        .robot-chat-container {
            min-height: 90px;
        }
        
        .robot-head {
            width: 38px;
            height: 22px;
            margin-bottom: 4px;
        }
        
        .robot-face {
            width: 30px;
            height: 17px;
            top: 2px;
        }
        
        .robot-body {
            width: 42px;
            height: 57px;
        }
        
        .arm {
            width: 9px;
            height: 45px;
            top: 11px;
        }
        
        .arm.left {
            left: -11px;
            transform-origin: 4px 4px;
        }
        
        .arm.right {
            right: -11px;
            transform-origin: 4px 4px;
        }
        
        .chat-bubble {
            right: -55px;
            min-width: 50px;
            font-size: 6px;
            padding: 4px 6px;
            min-height: 30px;
            border-radius: 8px;
        }
        
        .chat-bubble .text-line {
            font-size: 5px !important; /* حجم خط أصغر للشاشات الصغيرة جداً */
            line-height: 1.2 !important;
            padding: 1px 3px !important;
        }
        
        .chat-bubble::after {
            border-width: 6px 8px 6px 0;
            left: -6px;
        }
        
        .eye {
            width: 10px;
            height: 6px;
            top: 7px;
        }
        
        .eye.left {
            left: 5px;
        }
        
        .eye.right {
            right: 5px;
        }
        
        
        .robot-shadow {
            bottom: -27px;
            width: 38px;
            height: 8px;
        }
    }
<style>
/* Responsive positioning using vw/vh units */
.robot-chat-container {
    position: fixed !important;
    z-index: 1000 !important;
    pointer-events: none !important;
    /* Default positioning using vw/vh */
    left: -4vw !important;
    bottom: 4vh !important;
}

.robot-chat-container .robot {
    pointer-events: auto !important;
}

.robot-chat-container .chat-bubble {
    pointer-events: auto !important;
}

/* English mode positioning */
html:not([dir="rtl"]) .robot-chat-container {
    left: auto !important;
    right: -4vw !important;
}

/* Small screens (≤430px) */
@media (max-width: 430px) {
    .robot-chat-container {
        left: -6vw !important;
        bottom: 3vh !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -6vw !important;
        left: auto !important;
    }
}

/* Medium screens (431px - 768px) */
@media (min-width: 431px) and (max-width: 768px) {
    .robot-chat-container {
        left: -4vw !important;
        bottom: 4vh !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -4vw !important;
        left: auto !important;
    }
}

/* Large screens (≥1025px) */
@media (min-width: 1025px) {
    .robot-chat-container {
        left: -4vw !important;
        bottom: 5vh !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -4vw !important;
        left: auto !important;
    }
}

/* iPhone 12 Pro Max reference positioning */
@media (max-width: 428px) and (min-width: 413px) {
    .robot-chat-container {
        left: -30px !important;
        bottom: 30px !important;
        transform: rotate(15deg) !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -30px !important;
        left: auto !important;
        transform: rotate(-15deg) !important;
    }
}

/* iPad Mini and below - specific positioning */
@media (max-width: 768px) and (min-width: 600px) {
    .robot-chat-container {
        left: -20px !important;
        bottom: 20px !important;
        transform: rotate(15deg) !important;
        scale: 1.0 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -20px !important;
        left: auto !important;
        transform: rotate(-15deg) !important;
        scale: 1.0 !important;
    }
}

/* iPad Mini specific (744x1024) */
@media (max-width: 768px) and (min-width: 744px) {
    .robot-chat-container {
        left: -30px !important;
        bottom: 30px !important;
        transform: rotate(12deg) !important;
        scale: 1.1 !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -30px !important;
        left: auto !important;
        transform: rotate(-12deg) !important;
        scale: 1.1 !important;
    }
    
    /* Chat bubble positioning for iPad Mini */
    .chat-bubble {
        left: 120px !important;
        top: -180px !important;
        font-size: 16px !important;
        padding: 12px 16px !important;
        min-width: 200px !important;
        max-width: 250px !important;
    }
    
    html:not([dir="rtl"]) .chat-bubble {
        right: 120px !important;
        left: auto !important;
        top: -180px !important;
    }
}

/* iPhone 14 Pro Max (430x932) */
@media (max-width: 430px) and (min-width: 430px) and (max-height: 932px) and (min-height: 932px) {
    .robot-chat-container {
        left: -28px !important;
        bottom: 28px !important;
        transform: rotate(14deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -28px !important;
        left: auto !important;
        transform: rotate(-14deg) !important;
    }
}

/* iPad Mini (768x1024) */
@media (max-width: 768px) and (min-width: 768px) and (max-height: 1024px) and (min-height: 1024px) {
    .robot-chat-container {
        left: -35px !important;
        bottom: 35px !important;
        transform: rotate(11deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -35px !important;
        left: auto !important;
        transform: rotate(-11deg) !important;
    }
}

/* iPad Air 820x1180 specific */
@media (max-width: 820px) and (min-width: 820px) and (max-height: 1180px) and (min-height: 1180px) {
    .robot-chat-container {
        left: -25px !important;
        bottom: 25px !important;
        transform: rotate(13deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -25px !important;
        left: auto !important;
        transform: rotate(-13deg) !important;
    }
    
    /* Chat bubble positioning for iPad Air 820x1180 */
    .chat-bubble {
        left: 125px !important;
        top: -180px !important;
        font-size: 16px !important;
        padding: 12px 16px !important;
        min-width: 200px !important;
        max-width: 250px !important;
    }
    
    html:not([dir="rtl"]) .chat-bubble {
        right: 125px !important;
        left: auto !important;
        top: -180px !important;
    }
}

/* iPad Pro (1024x1366) */
@media (max-width: 1024px) and (min-width: 1024px) and (max-height: 1366px) and (min-height: 1366px) {
    .robot-chat-container {
        left: -45px !important;
        bottom: 45px !important;
        transform: rotate(9deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -45px !important;
        left: auto !important;
        transform: rotate(-9deg) !important;
    }
}

/* iPad Pro 12.9" (912x1368) */
@media (max-width: 912px) and (min-width: 912px) and (max-height: 1368px) and (min-height: 1368px) {
    .robot-chat-container {
        left: -50px !important;
        bottom: 50px !important;
        transform: rotate(8deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -50px !important;
        left: auto !important;
        transform: rotate(-8deg) !important;
    }
}

/* Galaxy Tab (540x720) */
@media (max-width: 540px) and (min-width: 540px) and (max-height: 720px) and (min-height: 720px) {
    .robot-chat-container {
        left: -30px !important;
        bottom: 30px !important;
        transform: rotate(12deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -30px !important;
        left: auto !important;
        transform: rotate(-12deg) !important;
    }
}

/* Galaxy Tab S (853x1280) */
@media (max-width: 853px) and (min-width: 853px) and (max-height: 1280px) and (min-height: 1280px) {
    .robot-chat-container {
        left: -40px !important;
        bottom: 40px !important;
        transform: rotate(10deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -40px !important;
        left: auto !important;
        transform: rotate(-10deg) !important;
    }
}

/* Surface Pro (1024x600) */
@media (max-width: 1024px) and (min-width: 1024px) and (max-height: 600px) and (min-height: 600px) {
    .robot-chat-container {
        left: -35px !important;
        bottom: 35px !important;
        transform: rotate(11deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -35px !important;
        left: auto !important;
        transform: rotate(-11deg) !important;
    }
}

/* Laptop (1280x800) */
@media (max-width: 1280px) and (min-width: 1280px) and (max-height: 800px) and (min-height: 800px) {
    .robot-chat-container {
        left: -60px !important;
        bottom: 60px !important;
        transform: rotate(7deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -60px !important;
        left: auto !important;
        transform: rotate(-7deg) !important;
    }
}

/* iPad Air specific (820px width) */
@media (max-width: 820px) and (min-width: 820px) {
    .robot-chat-container {
        left: -30px !important;
        bottom: 30px !important;
        transform: rotate(12deg) !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -30px !important;
        left: auto !important;
        transform: rotate(-12deg) !important;
    }
    
    /* Chat bubble positioning for iPad Air */
    .chat-bubble {
        left: 130px !important;
        top: -185px !important;
        font-size: 16px !important;
        padding: 12px 16px !important;
        min-width: 200px !important;
        max-width: 250px !important;
    }
    
    html:not([dir="rtl"]) .chat-bubble {
        right: 130px !important;
        left: auto !important;
        top: -185px !important;
    }
}

/* Medium tablets */
@media (max-width: 1024px) and (min-width: 769px) {
    .robot-chat-container {
        left: -40px !important;
        bottom: 40px !important;
        transform: rotate(10deg) !important;
        scale: 1.2 !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -40px !important;
        left: auto !important;
        transform: rotate(-10deg) !important;
        scale: 1.2 !important;
    }
    
    /* Chat bubble positioning for medium tablets */
    .chat-bubble {
        left: 150px !important;
        top: -200px !important;
        font-size: 18px !important;
        padding: 14px 18px !important;
        min-width: 220px !important;
        max-width: 280px !important;
    }
    
    html:not([dir="rtl"]) .chat-bubble {
        right: 150px !important;
        left: auto !important;
        top: -200px !important;
    }
}

/* Responsive scaling for all other screens based on iPhone 12 Pro Max */
@media (max-width: 412px) {
    .robot-chat-container {
        left: calc(-355px * var(--scale-factor, 1)) !important;
        bottom: calc(30px * var(--scale-factor, 1)) !important;
        transform: rotate(calc(15deg * var(--scale-factor, 1))) !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: calc(-30px * var(--scale-factor, 1)) !important;
        left: auto !important;
        transform: rotate(calc(-15deg * var(--scale-factor, 1))) !important;
    }
}

/* Large screens (1025px+) */
@media (min-width: 1025px) {
    .robot-chat-container {
        left: -60px !important;
        bottom: 60px !important;
        transform: rotate(8deg) !important;
        scale: 1.3 !important;
        z-index: 1000 !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container {
        right: -60px !important;
        left: auto !important;
        transform: rotate(-8deg) !important;
        scale: 1.3 !important;
    }
    
    /* Chat bubble positioning for large screens */
    .chat-bubble {
        left: 180px !important;
        top: -220px !important;
        font-size: 20px !important;
        padding: 16px 20px !important;
        min-width: 250px !important;
        max-width: 320px !important;
    }
    
    html:not([dir="rtl"]) .chat-bubble {
        right: 180px !important;
        left: auto !important;
        top: -220px !important;
    }
}

/* Chat bubble responsive sizing using vw/vh */
.chat-bubble {
    position: absolute !important;
    font-size: clamp(12px, 2.5vw, 18px) !important;
    padding: clamp(8px, 1.5vw, 12px) clamp(10px, 2vw, 16px) !important;
    min-width: clamp(150px, 30vw, 250px) !important;
    max-width: clamp(200px, 40vw, 300px) !important;
    border-radius: clamp(8px, 1.5vw, 12px) !important;
}

/* Chat bubble positioning using vw/vh */
.chat-bubble {
    left: clamp(80px, 15vw, 150px) !important;
    top: clamp(-120px, -15vh, -180px) !important;
}

html:not([dir="rtl"]) .chat-bubble {
    /* right: clamp(80px, 15vw, 150px) !important; */
    left: auto !important;
    top: clamp(-120px, -15vh, -180px) !important;
}

/* إخفاء chat-bubble لما يكون الروبوت مفتوح */
.robot-chat-container.fly-to-center .chat-bubble {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
}

/* Additional chat bubble positioning */
.chat-bubble {
    left: clamp(75px, 15vw, 150px) !important;
    top: clamp(1px, -15vh, -180px) !important;
}

html:not([dir="rtl"]) .chat-bubble {
    /* right: clamp(60px, 20vw, 150px) !important; */
    left: auto !important;
    top: clamp(3px, -15vh, -180px) !important;
}

@media (max-width: 428px) and (min-width: 413px) {
    .robot-chat-container {
        left: -384px !important;
        bottom: 30px !important;
        transform: rotate(15deg) !important;
    }
}

@media (max-width: 428px) and (min-width: 413px) {
    .robot-chat-container.fly-to-center .robot::after {
        width: 300px !important;
        height: 150px !important;
        bottom: -111px !important;
        left: 58% !important;
        filter: drop-shadow(0 0 30px rgba(117, 239, 248, 0.8)) !important;
    }
}

@media (max-width: 768px) {
    .robot-chat-container.fly-to-center .prompt-input-container {
        opacity: 1 !important;
        visibility: visible !important;
        bottom: -240px !important;
    }
}

@media (max-width: 768px) {
    .robot-chat-container.fly-to-center {
        left: 50% !important;
        top: 29% !important;
        bottom: auto !important;
        right: auto !important;
        transform: translate(-50%, -50%) !important;
        rotate: 0deg !important;
        z-index: 9999 !important;
        transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
    }
    
    html:not([dir="rtl"]) .robot-chat-container.fly-to-center {
        left: 50% !important;
        top: 29% !important;
        bottom: auto !important;
        right: auto !important;
        transform: translate(-50%, -50%) !important;
        rotate: 0deg !important;
        z-index: 9999 !important;
        transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
    }
}

@media (max-width: 428px) and (min-width: 413px) {
    .robot-chat-container.fly-to-center .robot::after {
        width: 250px !important;
        height: 150px !important;
        bottom: -111px !important;
        left: 58% !important;
        filter: drop-shadow(0 0 30px rgba(117, 239, 248, 0.8)) !important;
    }
}

@media (max-width: 768px) {
    .robot-chat-container.fly-to-center .prompt-input-container {
        opacity: 1 !important;
        visibility: visible !important;
        bottom: -222px !important;
    }
}

@media (max-width: 412px) and (min-width: 360px) {
    html:not([dir="rtl"]) .robot-chat-container.fly-to-center .robot::after {
        left: 50% !important;
        width: 200px !important;
        height: 135px !important;
        bottom: -90px !important;
    }
    
    .robot-chat-container.fly-to-center .robot::after {
        left: 50% !important;
        width: 200px !important;
        height: 135px !important;
        bottom: -90px !important;
    }
}

@media (max-width: 430px) and (min-width: 430px) and (max-height: 932px) and (min-height: 932px) {
    .robot-chat-container {
        left: -395px !important;
        bottom: 28px !important;
        transform: rotate(14deg) !important;
        z-index: 1000 !important;
    }
}

@media (max-width: 768px) {
    .robot-chat-container.fly-to-center .robot::after {
        content: '' !important;
        position: absolute !important;
        bottom: -103px !important;
        left: 53% !important;
        transform: translateX(-50%) !important;
        width: 231px !important;
        height: 146px !important;
        background-image: url(/images/Polygon%202.png) !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        filter: drop-shadow(0 0 30px rgba(117, 239, 248, 0.8)) !important;
        animation: lightGlow 2s ease-in-out infinite alternate !important;
        z-index: -1 !important;
    }
}

@media (max-width: 768px) and (min-width: 768px) and (max-height: 1024px) and (min-height: 1024px) {
    .robot-chat-container {
        left: -706px !important;
        bottom: 35px !important;
        transform: rotate(11deg) !important;
        z-index: 1000 !important;
    }
}

@media (max-width: 820px) and (min-width: 820px) and (max-height: 1180px) and (min-height: 1180px) {
    .robot-chat-container {
        left: -706px !important;
        bottom: 35px !important;
        transform: rotate(11deg) !important;
        z-index: 1000 !important;
    }
}
</style>
