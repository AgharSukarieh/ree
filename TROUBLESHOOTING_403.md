# حل مشكلة 403 Forbidden على Laravel Cloud

## 🔍 التحقق من الإعدادات الأساسية

### 1. Root Directory (الأهم!)
في Laravel Cloud:
- اذهب إلى **Settings** → **General**
- تأكد من أن **Root Directory** مضبوط على: `memoria-app`
- احفظ الإعدادات
- أعد النشر (Redeploy)

### 2. متغيرات البيئة الأساسية
تأكد من وجود هذه المتغيرات في **Environment Variables**:

```
APP_NAME=Memoria
APP_ENV=production
APP_DEBUG=false
APP_URL=https://miemo-master-mjjg1m.laravel.cloud
APP_KEY=base64:your-generated-key-here
```

**ملاحظة مهمة**: `APP_URL` يجب أن يكون مطابقاً تماماً لرابط التطبيق!

### 3. قاعدة البيانات
تأكد من إعدادات قاعدة البيانات:
```
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

## 🔧 حلول سريعة

### الحل 1: إعادة النشر
1. اذهب إلى **Deployments**
2. اضغط على **Redeploy** أو **Deploy Now**

### الحل 2: تشغيل الأوامر في Terminal
1. اذهب إلى **SSH** أو **Terminal** في Laravel Cloud
2. شغل الأوامر التالية:

```bash
cd memoria-app
chmod -R 775 storage bootstrap/cache
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### الحل 3: التحقق من السجلات
1. اذهب إلى **Logs** في Laravel Cloud
2. ابحث عن أخطاء محددة
3. راجع الخطأ وطبّق الحل المناسب

## ✅ قائمة التحقق

- [ ] Root Directory = `memoria-app`
- [ ] `APP_KEY` موجود ومولّد بشكل صحيح
- [ ] `APP_URL` مطابق لرابط التطبيق
- [ ] `APP_DEBUG=false`
- [ ] قاعدة البيانات متصلة بشكل صحيح
- [ ] مجلدات `storage` و `bootstrap/cache` لها صلاحيات الكتابة
- [ ] تم تشغيل `php artisan storage:link`
- [ ] تم تشغيل migrations

## 🆘 إذا استمرت المشكلة

1. تحقق من **Logs** في Laravel Cloud
2. تأكد من أن جميع الملفات موجودة في `memoria-app/`
3. تأكد من أن `composer.json` و `composer.lock` موجودان في جذر المستودع
4. تأكد من أن `laravel.json` موجود في جذر المستودع

