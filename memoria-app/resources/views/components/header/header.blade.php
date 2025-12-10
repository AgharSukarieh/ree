{{-- Header Component --}}
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

