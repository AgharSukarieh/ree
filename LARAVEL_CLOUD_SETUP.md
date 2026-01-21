# 🚀 إعداد Laravel Cloud - دليل شامل

## ⚠️ المشكلة: 403 Forbidden

إذا كنت تواجه خطأ 403 Forbidden، اتبع هذا الدليل خطوة بخطوة.

## 📋 الخطوة 1: إعدادات Laravel Cloud الأساسية

### 1.1 Root Directory (الأهم!)

1. اذهب إلى Laravel Cloud Dashboard
2. اختر مشروعك
3. اذهب إلى **Settings** → **General**
4. ابحث عن **Root Directory** أو **Application Root**
5. **أدخل**: `memoria-app`
6. **احفظ الإعدادات**

**⚠️ بدون هذا الإعداد، Laravel Cloud لن يجد ملفات المشروع!**

### 1.2 Web Directory / Public Directory

في بعض الحالات، قد تحتاج إلى تحديد:
- **Web Directory**: `memoria-app/public`
- أو اتركه فارغاً (سيستخدم `public` تلقائياً)

## 📋 الخطوة 2: متغيرات البيئة (Environment Variables)

اذهب إلى **Environment Variables** في Laravel Cloud وأضف:

### المتغيرات الأساسية (مطلوبة):

```env
APP_NAME=Memoria
APP_ENV=production
APP_DEBUG=false
APP_URL=https://miemo-master-mjjg1m.laravel.cloud
APP_KEY=base64:your-generated-key-here
```

**كيفية توليد APP_KEY:**
```bash
php artisan key:generate --show
```
انسخ المفتاح وأضفه في Environment Variables.

### قاعدة البيانات:

```env
DB_CONNECTION=mysql
DB_HOST=your-db-host-from-laravel-cloud
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

**ملاحظة:** بيانات قاعدة البيانات موجودة في Laravel Cloud → Databases

### Cloudinary:

```env
CLOUDINARY_URL=cloudinary://629249255372626:DQATNuq02hWbcKW33DY_xRlnQTI@dozvsu2rp
```

## 📋 الخطوة 3: إعادة النشر

1. بعد تعديل الإعدادات، اذهب إلى **Deployments**
2. اضغط على **Redeploy** أو **Deploy Now**
3. انتظر حتى يكتمل النشر

## 📋 الخطوة 4: التحقق من الروتات

بعد النشر، جرب هذه الروابط:

1. **Route اختبار**: 
   ```
   https://miemo-master-mjjg1m.laravel.cloud/test-laravel
   ```
   يجب أن ترى JSON response يؤكد أن Laravel يعمل.

2. **الصفحة الرئيسية**:
   ```
   https://miemo-master-mjjg1m.laravel.cloud/
   ```

3. **Dashboard**:
   ```
   https://miemo-master-mjjg1m.laravel.cloud/dashboard
   ```

## 🔧 حل المشاكل

### إذا استمر خطأ 403:

#### 1. تحقق من Terminal في Laravel Cloud:

```bash
cd memoria-app
php artisan route:list
```

يجب أن ترى قائمة بجميع الروتات.

#### 2. تحقق من الصلاحيات:

```bash
cd memoria-app
chmod -R 775 storage bootstrap/cache
ls -la storage bootstrap/cache
```

#### 3. مسح الكاش:

```bash
cd memoria-app
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 4. ربط Storage:

```bash
cd memoria-app
php artisan storage:link
```

#### 5. تحقق من السجلات:

اذهب إلى **Logs** في Laravel Cloud وابحث عن أخطاء محددة.

## ✅ قائمة التحقق النهائية

قبل أن تطلب المساعدة، تأكد من:

- [ ] Root Directory = `memoria-app` في Settings → General
- [ ] `APP_URL` = `https://miemo-master-mjjg1m.laravel.cloud` (مطابق تماماً)
- [ ] `APP_KEY` موجود ومولّد
- [ ] `APP_DEBUG=false`
- [ ] قاعدة البيانات متصلة بشكل صحيح
- [ ] تم إعادة النشر بعد تعديل الإعدادات
- [ ] جربت `/test-laravel` route

## 📝 ملاحظات مهمة

1. **Laravel Cloud يستخدم nginx** - ملف `.htaccess` لا يؤثر
2. **Root Directory مهم جداً** - بدونها لن يعمل التطبيق
3. **APP_URL يجب أن يكون مطابق تماماً** - لا تضع `/` في النهاية
4. **بعد أي تعديل في Environment Variables** - أعد النشر

## 🆘 إذا لم تحل المشكلة

1. تحقق من **Logs** في Laravel Cloud
2. تأكد من أن جميع الملفات موجودة في `memoria-app/`
3. تأكد من أن `composer.json` و `composer.lock` موجودان في جذر المستودع
4. تأكد من أن `laravel.json` موجود في جذر المستودع

---

**بعد تطبيق هذه الخطوات، يجب أن يعمل التطبيق بشكل صحيح!**

