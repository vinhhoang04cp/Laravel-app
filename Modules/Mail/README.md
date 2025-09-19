# Mail Module (Templates + Config)

Module quản lý Mail Template & Mail Config cho Laravel (cấu trúc `Modules/Mail`).

## Cài đặt
1. Copy thư mục `Modules/Mail` vào dự án Laravel của bạn.
2. Đăng ký ServiceProvider:
```php
Modules\Mail\App\Providers\MailServiceProvider::class,
```
3. Thêm PSR-4 autoload trong `composer.json`:
```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/"
    }
  }
}
```
Sau đó chạy:
```bash
composer dump-autoload
php artisan optimize:clear
```

4. Chạy migration & seeder:
```bash
php artisan migrate
php artisan db:seed --class="Modules\\Mail\\App\\Database\\Seeders\\MailModuleSeeder"
```

## API chính
- `GET    /api/v1/mail/templates`
- `GET    /api/v1/mail/templates/{id}`
- `POST   /api/v1/mail/templates`
- `PUT    /api/v1/mail/templates/{id}`
- `DELETE /api/v1/mail/templates/{id}`

- `GET    /api/v1/mail/configs`
- `GET    /api/v1/mail/configs/{id}`
- `POST   /api/v1/mail/configs`
- `PUT    /api/v1/mail/configs/{id}`
- `DELETE /api/v1/mail/configs/{id}`
- `POST   /api/v1/mail/configs/{id}/activate`

- `POST   /api/v1/mail/send-test` (body: to, template_code, data{}, config_id?)
- `POST   /api/v1/mail/preview`   (body: subject, body, data{})

## Ghi chú
- Template lưu DB, hỗ trợ mọi biến `{{ $var }}`. FE chỉ cần truyền `data` có key tương ứng.
- Mật khẩu SMTP được mã hoá bằng `Crypt` trước khi lưu DB.
- `PlaceholderExtractor` tự động trích xuất các biến từ subject/body để hiển thị gợi ý.
- Bổ sung middleware/permission theo RACL của dự án.
