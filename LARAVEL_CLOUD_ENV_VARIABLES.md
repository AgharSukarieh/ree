# 🔐 متغيرات البيئة المطلوبة لـ Laravel Cloud - مشروع Memoria

## 📋 قائمة المتغيرات الكاملة

انسخ هذه المتغيرات وأضفها في **Laravel Cloud → Settings → Environment Variables**:

```env
APP_NAME="Memoria"

APP_ENV=production

APP_DEBUG=false

APP_URL="https://memoria-master-ihn1qf.laravel.cloud"

LOG_CHANNEL=laravel-cloud-socket

LOG_STDERR_FORMATTER=Monolog\Formatter\JsonFormatter

DB_CONNECTION=mysql

DB_HOST=db-a0c6457a-9359-413c-afeb-404f9298d329.us-east-2.db.laravel.cloud

DB_PORT=3306

DB_DATABASE=main

DB_USERNAME=say3nmqlscsxcuyi

DB_PASSWORD=FbDJTghQY5FcZuk6pfNh

SESSION_DRIVER=cookie

CACHE_STORE=database

SCHEDULE_CACHE_DRIVER=database

VITE_APP_NAME="${APP_NAME}"
```

**⚠️ مهم:** يجب إنشاء قاعدة بيانات منفصلة لمشروع memoria لأن قاعدة البيانات المشتركة تحتوي على جدول `users` ببنية مختلفة. راجع ملف `CREATE_SEPARATE_DATABASE.md` للتعليمات.

## 📝 متغيرات إضافية قد تحتاجها

### Cloudinary (إذا كنت تستخدمه):
```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```
**ملاحظة:** استخدم بيانات Cloudinary الخاصة بك

### OpenAI (إذا كنت تستخدمه):
```env
OPENAI_API_KEY=your-openai-api-key-here
```
**ملاحظة:** استخدم مفتاح OpenAI الخاص بك من حسابك

### APP_KEY (مهم جداً!)
```env
APP_KEY=base64:5aXe7dbDTcAAiUqbc+lLTz4IiHamCAt+SoAHjqbZfR8=
```

## 🚀 خطوات الإضافة

1. **اذهب إلى Laravel Cloud Dashboard**
   - افتح: https://cloud.laravel.com
   - اختر مشروعك: **miemo**

2. **افتح Environment Variables**
   - من القائمة الجانبية: **Settings** → **Environment Variables**

3. **أضف كل متغير على حدة**
   - اضغط **Add Variable**
   - أدخل **Key** و **Value**
   - احفظ

4. **أعد النشر**
   - اذهب إلى **Deployments**
   - اضغط **Redeploy** أو **Deploy Now**

## ⚠️ ملاحظات مهمة

- **APP_KEY** يجب أن يكون موجوداً ومولّداً
- **APP_URL** يجب أن يكون مطابق تماماً (مع علامات الاقتباس)
- **DB_PASSWORD** حساس - لا تشاركه
- بعد إضافة المتغيرات، **يجب إعادة النشر**

## ✅ التحقق من الإعدادات

بعد إضافة المتغيرات وإعادة النشر، تحقق من:

1. **الاتصال بقاعدة البيانات:**
   ```bash
   php artisan db:show
   ```

2. **تشغيل Migrations:**
   ```bash
   php artisan migrate
   ```

3. **مسح الكاش:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## 🔗 روابط مفيدة

- Laravel Cloud Dashboard: https://cloud.laravel.com
- تطبيق Memoria: https://memoria-master-ihn1qf.laravel.cloud

