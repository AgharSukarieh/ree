<!-- Popper.js CDN -->
<script src="https://unpkg.com/@popperjs/core@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const robot = document.querySelector('.robot');
    const eyes = document.querySelectorAll('.eye');
    const chatBubble = document.getElementById('chatBubble');
    const promptInputContainer = document.getElementById('promptInputContainer');
    const promptInput = document.getElementById('promptInput');
    const sendButton = document.getElementById('sendPrompt');
    const closeButton = document.getElementById('closePrompt');
    
    if (!robot || !eyes.length || !chatBubble) return;
    
    // Get messages from the HTML data attributes
    const textLines = chatBubble.querySelectorAll('.text-line');
    
    let currentMessageIndex = 0;
    let isAnimating = false;
    let messageInterval;
    
    // Function to get current language text from a line
    function getLineText(line) {
        const currentLang = document.documentElement.lang || 'ar';
        const arText = line.getAttribute('data-ar');
        const enText = line.getAttribute('data-en');
        return currentLang === 'ar' ? arText : enText;
    }
    
    function showNextMessage() {
        if (isAnimating) return;
        isAnimating = true;
        
        textLines.forEach(line => {
            line.style.animation = 'none';
            line.style.opacity = '0';
            line.style.transform = 'translateY(20px)';
        });
        
        setTimeout(() => {
            // Show only the current message line with language support
            textLines.forEach((line, index) => {
                if (index === currentMessageIndex) {
                    line.innerHTML = getLineText(line);
                    line.style.animation = 'continuousLoop 4s ease-in-out forwards';
                } else {
                    line.style.opacity = '0';
                }
            });
            
            currentMessageIndex = (currentMessageIndex + 1) % textLines.length;
            
            setTimeout(() => {
                isAnimating = false;
                showNextMessage(); // Continue to next message
            }, 4000); // Increased duration for better readability
        }, 300); // Reduced transition time
    }
    
    function startMessaging() {
        if (messageInterval) clearInterval(messageInterval);
        setTimeout(showNextMessage, 1500); // Slightly delayed start
    }
    
    function stopMessaging() {
        if (messageInterval) clearInterval(messageInterval);
        isAnimating = false;
    }
    
    // Start continuous messaging
    startMessaging();
    
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
        
        // تحديد مساحة الحركة حسب حجم الشاشة
        // تقليل الحركة لضمان بقاء العيون البيضاء داخل العيون الزرقاء
        // العيون الزرقاء: 55px × 35px، العيون البيضاء: 15px × 10px
        // الحد الأقصى للحركة: (55-15)/2 = 20px للأفق، (35-10)/2 = 12.5px للعمودي
        const maxMoveX = window.innerWidth <= 768 ? 3 : 8; // حركة أقل للهواتف
        const maxMoveY = window.innerWidth <= 768 ? 2 : 6; // حركة عمودية أقل
        const moveXLeft = Math.cos(angleLeft) * maxMoveX;
        const moveYLeft = Math.sin(angleLeft) * maxMoveY;
        const moveXRight = Math.cos(angleRight) * maxMoveX;
        const moveYRight = Math.sin(angleRight) * maxMoveY;
        
        eyeLeft.style.setProperty('--moveX', `${moveXLeft}px`);
        eyeLeft.style.setProperty('--moveY', `${moveYLeft}px`);
        eyeRight.style.setProperty('--moveX', `${moveXRight}px`);
        eyeRight.style.setProperty('--moveY', `${moveYRight}px`);
    }
    
    document.addEventListener('mousemove', (e) => {
        // تحديث موضع العيون في جميع الشاشات
        updateEyePosition(e.clientX, e.clientY);
        
        // منع حركة الروبوت في الهواتف والشاشات الكبيرة
        if (window.innerWidth <= 768 || window.innerWidth >= 1201) return;
        
        const x = (e.clientX / window.innerWidth - 0.5) * 15;
        const y = (e.clientY / window.innerHeight - 0.5) * 15;
        
        robot.style.transform = `translate(${x}px, ${y}px)`;
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
    
    // Popper.js configuration for responsive robot positioning
    let robotPopper = null;
    
    // iPhone 12 Pro Max reference dimensions (428x781)
    const IPHONE_12_PRO_MAX = {
        width: 428,
        height: 781,
        robotPosition: {
            left: -30,
            bottom: 30,
            rotate: 15
        }
    };
    
    // Simple and effective robot positioning using vw/vh units
    function positionRobot() {
        const robotContainer = document.querySelector('.robot-chat-container');
        if (!robotContainer) return;
        
        const currentWidth = window.innerWidth;
        const currentHeight = window.innerHeight;
        
        // للشاشات الكبيرة، لا نطبق أي positioning (يتم التحكم بها عبر CSS)
        if (currentWidth >= 1201) {
            // التأكد من أن الروبوت مستقيم تماماً
            robotContainer.style.transform = 'translateY(-50%) rotate(0deg)';
            robotContainer.style.top = '50%';
            robotContainer.style.bottom = 'auto';
            
            // للغة العربية: الروبوت على اليسار
            if (document.documentElement.dir === 'rtl' || !document.documentElement.dir) {
                robotContainer.style.left = '20px';
                robotContainer.style.right = 'auto';
            } else {
                // للغة الإنجليزية: الروبوت على يمين الفورم
                // الفورم في 50% من الشاشة وموسّط، لذا يبدأ من 25% وينتهي عند 75%
                // نضع الروبوت بعد الفورم مباشرة
                const formContainer = document.querySelector('.container');
                if (formContainer) {
                    const formRect = formContainer.getBoundingClientRect();
                    const formRight = formRect.right;
                    const robotLeft = formRight + 20; // 20px بعد الفورم
                    robotContainer.style.left = robotLeft + 'px';
                    robotContainer.style.right = 'auto';
                } else {
                    // Fallback: استخدام calc
                    robotContainer.style.left = 'calc(50% + 25% + 20px)';
                    robotContainer.style.right = 'auto';
                }
            }
            
            // التأكد من أن الروبوت نفسه مستقيم
            const robot = document.querySelector('.robot');
            if (robot) {
                robot.style.transform = 'rotate(0deg)';
            }
            return;
        }
        
        // Calculate viewport-based positioning
        let leftVw, bottomVh, rotate;
        
        if (currentWidth <= 430) {
            // Small phones - use vw/vh for better scaling
            leftVw = -6; // -6vw
            bottomVh = 3; // 3vh
            rotate = 15;
        } else if (currentWidth <= 768) {
            // Medium phones/tablets
            leftVw = -4; // -4vw
            bottomVh = 4; // 4vh
            rotate = 12;
        } else if (currentWidth <= 1024) {
            // Large tablets
            leftVw = -4; // -4vw
            bottomVh = 4; // 4vh
            rotate = 10;
        } else {
            // Laptops/desktops (أقل من 1201px)
            leftVw = -4; // -4vw
            bottomVh = 5; // 5vh
            rotate = 8;
        }
        
        // Apply positioning using vw/vh units
        robotContainer.style.left = `${leftVw}vw`;
        robotContainer.style.bottom = `${bottomVh}vh`;
        robotContainer.style.transform = `rotate(${rotate}deg)`;
        
        // For English mode
        if (document.documentElement.dir !== 'rtl') {
            robotContainer.style.left = 'auto';
            robotContainer.style.right = `${Math.abs(leftVw)}vw`;
            robotContainer.style.transform = `rotate(${-rotate}deg)`;
        }
    }
    
    // Initialize robot positioning
    positionRobot();
    
    // Update position on resize
    window.addEventListener('resize', positionRobot);
    
    // Update position on orientation change
    window.addEventListener('orientationchange', () => {
        setTimeout(positionRobot, 500);
    });
    
    // تفاعل الطيران السلس للهواتف والتابلت
    function handleRobotClick() {
        // للشاشات الكبيرة واللابتوب (1201px وأكثر)
        if (window.innerWidth >= 1201) {
            const robotContainer = document.querySelector('.robot-chat-container');
            const robotOverlay = document.getElementById('robotOverlay');
            
            if (!robotContainer || !robotOverlay) {
                console.error('Robot container or overlay not found');
                return;
            }
            
            // التحقق إذا كان الروبوت في المنتصف بالفعل - إذا كان كذلك، نعيده لمكانه
            if (robotContainer.classList.contains('robot-centered')) {
                // إغلاق overlay
                robotOverlay.classList.remove('show');
                
                // إزالة class المنتصف
                robotContainer.classList.remove('robot-centered');
                
                // إزالة class من robot-section
                const robotSection = document.querySelector('.robot-section');
                if (robotSection) {
                    robotSection.classList.remove('robot-opened');
                }
                
                // إعادة الروبوت لمكانه الأصلي
                robot.style.animation = 'floatLarge 4s ease-in-out infinite';
                robot.style.transform = '';
                
                // إعادة إظهار chat-bubble
                chatBubble.style.opacity = '1';
                chatBubble.style.pointerEvents = 'auto';
                chatBubble.style.visibility = 'visible';
                stopMessaging();
                startMessaging();
                
                // إغلاق صندوق الإدخال
                hidePromptInput();
            } else {
                // فتح: إظهار overlay غامق أولاً
                robotOverlay.classList.add('show');
                
                // إخفاء chat-bubble
                chatBubble.style.opacity = '0';
                chatBubble.style.pointerEvents = 'none';
                chatBubble.style.visibility = 'hidden';
                stopMessaging();
                
                // نقل الروبوت إلى منتصف الشاشة مع transition سلس
                robotContainer.style.transition = 'all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                robotContainer.classList.add('robot-centered');
                
                // إضافة class للـ robot-section لجعله في النصف
                const robotSection = document.querySelector('.robot-section');
                if (robotSection) {
                    robotSection.classList.add('robot-opened');
                }
                
                // جعل الروبوت مستقيم (إزالة animation الطيران)
                robot.style.animation = 'none';
                robot.style.transform = 'scale(0.7) rotate(0deg)';
                
                // إظهار shadow تحت الروبوت بعد انتقال الروبوت
                setTimeout(() => {
                    const robotShadow = document.querySelector('.robot-shadow');
                    if (robotShadow) {
                        robotShadow.style.opacity = '1';
                        robotShadow.style.display = 'block';
                    }
                }, 400);
                
                // إظهار صندوق الإدخال تحته بعد انتقال الروبوت
                setTimeout(() => {
                    showPromptInput();
                }, 500);
            }
        } else {
            // الشاشات الصغيرة والتابلت (1024px وأقل)
            const robotContainer = document.querySelector('.robot-chat-container');
            const robotShadow = document.querySelector('.robot-shadow');
            
            if (robotContainer.classList.contains('fly-to-center')) {
                // لا نغلق الـ input عند النقر على الروبوت
                // فقط نعيد الرسائل
                chatBubble.style.opacity = '1';
                chatBubble.style.pointerEvents = 'auto';
                stopMessaging();
                startMessaging();
            } else {
                // طيران الروبوت إلى المنتصف
                robotContainer.classList.add('fly-to-center');
                
                // إخفاء الشات بابل تدريجياً
                chatBubble.style.opacity = '0';
                chatBubble.style.pointerEvents = 'none';
                stopMessaging();
                
                // إظهار الـ input بعد الطيران
                setTimeout(() => {
                    showPromptInput();
                }, 800);
            }
        }
    }
    
    // وظائف الـ input
    function showPromptInput() {
        if (promptInputContainer) {
            promptInputContainer.classList.add('show');
            setTimeout(() => {
                if (promptInput) {
                    promptInput.focus();
                }
            }, 500);
        }
    }
    
    function hidePromptInput() {
        if (promptInputContainer) {
            promptInputContainer.classList.remove('show');
        }
    }
    
    function closePromptInput() {
        hidePromptInput();
        
        // للشاشات الكبيرة: إزالة overlay وإعادة الروبوت لمكانه
        if (window.innerWidth >= 1201) {
            const robotContainer = document.querySelector('.robot-chat-container');
            const robotOverlay = document.getElementById('robotOverlay');
            
            // إزالة overlay
            if (robotOverlay) {
                robotOverlay.classList.remove('show');
            }
            
            // إزالة class المنتصف وإعادة الروبوت لمكانه
            if (robotContainer) {
                robotContainer.classList.remove('robot-centered');
            }
            
            // إزالة class من robot-section
            const robotSection = document.querySelector('.robot-section');
            if (robotSection) {
                robotSection.classList.remove('robot-opened');
            }
            
            // إخفاء shadow
            const robotShadow = document.querySelector('.robot-shadow');
            if (robotShadow) {
                robotShadow.style.opacity = '0';
                robotShadow.style.display = 'none';
            }
            
            // إعادة الروبوت لمكانه الأصلي
            robot.style.animation = 'floatLarge 4s ease-in-out infinite';
            robot.style.transform = '';
            
            // إعادة إظهار chat-bubble وإعادة animation الطيران
            chatBubble.style.opacity = '1';
            chatBubble.style.visibility = 'visible';
            chatBubble.style.pointerEvents = 'auto';
            stopMessaging();
            startMessaging();
        } else {
            // إعادة الروبوت إلى مكانه الأصلي للشاشات الصغيرة
            const robotContainer = document.querySelector('.robot-chat-container');
            if (robotContainer && robotContainer.classList.contains('fly-to-center')) {
                robotContainer.classList.remove('fly-to-center');
                setTimeout(() => {
                    robot.style.animation = 'float 4s ease-in-out infinite';
                    const robotShadow = document.querySelector('.robot-shadow');
                    if (robotShadow) robotShadow.style.opacity = '1';
                    chatBubble.style.opacity = '1';
                    chatBubble.style.pointerEvents = 'auto';
                    stopMessaging();
                    startMessaging();
                }, 100);
            }
        }
    }
    
    async function sendPrompt() {
        const prompt = promptInput.value.trim();
        if (!prompt) {
            return;
        }

        // تعطيل الزر أثناء المعالجة
        let originalText = '';
        if (sendButton) {
            sendButton.disabled = true;
            sendButton.style.opacity = '0.6';
            sendButton.style.cursor = 'not-allowed';
            const spanElement = sendButton.querySelector('span');
            if (spanElement) {
                originalText = spanElement.textContent;
                spanElement.textContent = 'جاري المعالجة...';
            }
        }

        try {
            console.log('Sending prompt to OpenAI:', prompt);
            
            // إرسال الطلب إلى API
            const response = await fetch('{{ route("openai.fill-form") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    prompt: prompt
                })
            });

            const data = await response.json();

            if (data.success) {
                console.log('=== OpenAI Response ===');
                console.log(JSON.stringify(data.data, null, 2));
                
                // طباعة في الكونسول بشكل منسق
                console.group('📋 Form Data from OpenAI');
                console.log('Major:', data.data.major);
                console.log('Name:', data.data.name);
                console.log('Job Title:', data.data.jop_title);
                console.log('Languages:', data.data.languages?.length || 0);
                console.log('Soft Skills:', data.data.softSkills?.length || 0);
                console.log('Experiences:', data.data.experiences?.length || 0);
                console.log('Education:', data.data.education?.length || 0);
                console.log('Certifications:', data.data.certifications?.length || 0);
                console.log('Activities:', data.data.activities?.length || 0);
                if (data.data.itSkills) {
                    console.log('IT Skills:', data.data.itSkills.length);
                }
                if (data.data.itProjects) {
                    console.log('IT Projects:', data.data.itProjects.length);
                }
                console.log('Full Data:', data.data);
                console.groupEnd();
                
                // مسح الـ input
                promptInput.value = '';
                
                // إغلاق نافذة الإدخال
                if (promptInputContainer) {
                    promptInputContainer.classList.remove('show');
                }
                
                // ملء الفورم تلقائياً من بيانات AI
                if (typeof fillFormFromOpenAI === 'function') {
                    fillFormFromOpenAI(data.data);
                } else {
                    console.error('fillFormFromOpenAI function not found');
                    // Fallback: توجيه المستخدم إلى صفحة النتيجة
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert('تم تعبئة الفورم بنجاح! تحقق من الكونسول لرؤية البيانات.');
                    }
                }
            } else {
                console.error('Error:', data.message);
                alert('حدث خطأ: ' + (data.message || 'فشل في تعبئة الفورم'));
            }
        } catch (error) {
            console.error('Error sending prompt:', error);
            alert('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
        } finally {
            // إعادة تفعيل الزر
            if (sendButton) {
                sendButton.disabled = false;
                sendButton.style.opacity = '1';
                sendButton.style.cursor = 'pointer';
                const spanElement = sendButton.querySelector('span');
                if (spanElement) {
                    spanElement.textContent = originalText || 'تعبئة الفورم';
                }
            }
        }
    }
    
    
    // إضافة أحداث للـ input
    if (sendButton) {
        sendButton.addEventListener('click', sendPrompt);
    }
    
    if (closeButton) {
        closeButton.addEventListener('click', closePromptInput);
    }
    
    if (promptInput) {
        promptInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendPrompt();
            }
        });
        
        // منع إغلاق الـ input عند النقر عليه
        promptInput.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    
    // إضافة أحداث النقر للروبوت والشات بابل
    robot.addEventListener('click', function(e) {
        e.stopPropagation(); // منع إغلاق popup عند النقر على الروبوت
        handleRobotClick();
    });
    robot.addEventListener('touchend', function(e) {
        e.stopPropagation(); // منع إغلاق popup عند النقر على الروبوت
        handleRobotClick();
    });
    
    // منع إغلاق الـ input عند النقر على الـ input-wrapper
    const inputWrapper = document.querySelector('.input-wrapper');
    if (inputWrapper) {
        inputWrapper.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    chatBubble.addEventListener('click', handleRobotClick);
    chatBubble.addEventListener('touchend', handleRobotClick);
    
    // إغلاق popup عند النقر على overlay (للشاشات الكبيرة فقط)
    const robotOverlay = document.getElementById('robotOverlay');
    if (robotOverlay) {
        robotOverlay.addEventListener('click', function(e) {
            if (window.innerWidth >= 1201) {
                e.stopPropagation();
                const robotContainer = document.querySelector('.robot-chat-container');
                if (robotContainer && robotContainer.classList.contains('robot-centered')) {
                    // إغلاق popup
                    robotOverlay.classList.remove('show');
                    robotContainer.classList.remove('robot-centered');
                    
                    // إزالة class من robot-section
                    const robotSection = document.querySelector('.robot-section');
                    if (robotSection) {
                        robotSection.classList.remove('robot-opened');
                    }
                    
                    // إخفاء shadow
                    const robotShadow = document.querySelector('.robot-shadow');
                    if (robotShadow) {
                        robotShadow.style.opacity = '0';
                        robotShadow.style.display = 'none';
                    }
                    
                    // إعادة الروبوت لمكانه الأصلي
                    robot.style.animation = 'floatLarge 4s ease-in-out infinite';
                    robot.style.transform = '';
                    
                    // إعادة إظهار chat-bubble
                    chatBubble.style.opacity = '1';
                    chatBubble.style.pointerEvents = 'auto';
                    chatBubble.style.visibility = 'visible';
                    stopMessaging();
                    startMessaging();
                    
                    // إغلاق صندوق الإدخال إذا كان مفتوح
                    hidePromptInput();
                }
            }
        });
    }
    
    // Update robot messages when language changes
    document.addEventListener('languageChanged', function() {
        // Update the current visible message
        textLines.forEach((line, index) => {
            if (line.style.opacity !== '0' && line.style.opacity !== '') {
                line.innerHTML = getLineText(line);
            }
        });
        // تحديث موضع الروبوت عند تغيير اللغة
        setTimeout(() => {
            positionRobot();
        }, 100);
    });
    
    // مراقبة تغييرات dir attribute لتحديث موضع الروبوت
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'dir') {
                setTimeout(() => {
                    positionRobot();
                }, 100);
            }
        });
    });
    
    // بدء مراقبة html element
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['dir']
    });
});
</script>
