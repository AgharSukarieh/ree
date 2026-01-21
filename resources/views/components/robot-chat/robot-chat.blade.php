<div class="robot-chat-container">
    <div class="robot">
        <div class="robot-shadow"></div>
        
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
            <div class="text-line" data-ar="مرحباً 👋" data-en="Hello 👋">مرحباً 👋</div>
            <div class="text-line" data-ar="أنا ميموريا" data-en="I'm Memoria">أنا ميموريا</div>
            <div class="text-line" data-ar="مساعدك الذكي" data-en="Your Smart Assistant">مساعدك الذكي</div>
            <div class="text-line" data-ar="اضغط عليّ 👆" data-en="Click Me 👆">اضغط عليّ 👆</div>
            <div class="text-line" data-ar="لنتحدث! 💬" data-en="Let's Talk! 💬">لنتحدث! 💬</div>
        </div>
        
        <!-- Input للـ prompts عندما يكون الروبوت في المنتصف -->
        <div class="prompt-input-container" id="promptInputContainer">
            <div class="input-wrapper">
                <textarea id="promptInput" data-ar-placeholder="انا محمد طالب هندسة برمجيات ادرس بجامعة البلقاء التطبيقيه حاب اتخصص ب فلتر موبايل ابلكيشين فروند ايند عندي سكيلز تاكنيكل جافا و دارت و جيت هاب بعرف انجليزي وعربي واخدت كورس جافا من ميتا واخدت كورس بايثون من علي بابا" data-en-placeholder="I am Mohammed, a software engineering student at Al-Balqa Applied University. I want to specialize in Flutter mobile application front-end. I have technical skills in Java, Dart, and GitHub. I know English and Arabic. I took a Java course from Meta and a Python course from Alibaba." placeholder="انا محمد طالب هندسة برمجيات ادرس بجامعة البلقاء التطبيقيه حاب اتخصص ب فلتر موبايل ابلكيشين فروند ايند عندي سكيلز تاكنيكل جافا و دارت و جيت هاب بعرف انجليزي وعربي واخدت كورس جافا من ميتا واخدت كورس بايثون من علي بابا" autocomplete="off" rows="3"></textarea>
                <div class="input-buttons">
                    <button id="sendPrompt" class="send-button">
                        <span data-ar="تعبئة الفورم" data-en="Fill Form">تعبئة الفورم</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                    <button id="closePrompt" class="close-button">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay للشاشات الكبيرة عند النقر على الروبوت -->
<div class="robot-overlay" id="robotOverlay"></div>


{{-- Include Styles --}}
@include('components.robot-chat.robot-chat-styles')

{{-- Include Scripts --}}
@include('components.robot-chat.robot-chat-scripts')
