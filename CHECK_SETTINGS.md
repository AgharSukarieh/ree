# ✅ قائمة التحقق من الإعدادات - حل مشكلة 403

## 🔴 إعدادات Laravel Cloud (الأهم!)

### 1. Root Directory
**يجب أن يكون مضبوطاً بشكل صحيح!**

في Laravel Cloud:
1. اذهب إلى **Settings** → **General**
2. ابحث عن **Root Directory** أو **Application Root**
3. **يجب أن يكون**: `memoria-app`
4. **لا تتركه فارغاً!**
5. احفظ الإعدادات

### 2. Web Directory / Public Directory
في بعض الحالات، قد تحتاج إلى تحديد:
- **Web Directory**: `memoria-app/public`
- أو **Public Directory**: `public`

### 3. متغيرات البيئة (Environment Variables)

تأكد من وجود هذه المتغيرات **بالضبط**:

```env
APP_NAME=Memoria
APP_ENV=production
APP_DEBUG=false
APP_URL=https://miemo-master-mjjg1m.laravel.cloud
APP_KEY=base64:... (يجب أن يكون موجوداً ومولّداً)
```

**⚠️ مهم جداً**: 
- `APP_URL` يجب أن يكون **مطابق تماماً** لرابط التطبيق
- لا تضع `/` في النهاية
- استخدم `https://` وليس `http://`

### 4. قاعدة البيانات

```env
DB_CONNECTION=mysql
DB_HOST=... (من Laravel Cloud)
DB_PORT=3306
DB_DATABASE=... (من Laravel Cloud)
DB_USERNAME=... (من Laravel Cloud)
DB_PASSWORD=... (من Laravel Cloud)
```

## 🔧 خطوات الحل

### الخطوة 1: تحقق من Root Directory
```
Settings → General → Root Directory = memoria-app
```

### الخطوة 2: تحقق من APP_URL
```
Environment Variables → APP_URL = https://miemo-master-mjjg1m.laravel.cloud
```

### الخطوة 3: أعد النشر
```
Deployments → Redeploy
```

### الخطوة 4: شغّل في Terminal (إذا استمرت المشكلة)
```bash
cd memoria-app
php artisan route:list
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan config:cache
```

## 🐛 إذا استمرت المشكلة

1. **تحقق من Logs** في Laravel Cloud
2. **تحقق من أن جميع الملفات موجودة**:
   - `memoria-app/public/index.php`
   - `memoria-app/routes/web.php`
   - `memoria-app/app/Http/Controllers/WebController.php`

3. **جرب route بسيط**:
   أضف في `routes/web.php`:
   ```php
   Route::get('/test', function() {
       return 'Test works!';
   });
   ```
   ثم افتح: `https://miemo-master-mjjg1m.laravel.cloud/test`

4. **تحقق من الصلاحيات**:
   ```bash
   cd memoria-app
   ls -la storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

## 📝 ملاحظات مهمة

- Laravel Cloud يستخدم **nginx** وليس Apache
- ملف `.htaccess` لا يؤثر على nginx
- المشكلة الأكثر شيوعاً هي **Root Directory** غير مضبوط
- تأكد من أن `APP_URL` صحيح تماماً

