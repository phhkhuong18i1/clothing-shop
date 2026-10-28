# Atelier No. 8 — Cửa hàng thời trang

Website danh mục thời trang xây dựng bằng Laravel, Blade và Tailwind CSS. Dự án có cửa hàng dành cho khách và khu vực quản trị riêng, chạy bằng Docker Compose, PHP-FPM, Nginx và MySQL. Cửa hàng hỗ trợ xem sản phẩm, danh mục cha–con, khu vực sale, tin tức, lọc danh mục, tìm kiếm và gallery ảnh; chưa có giỏ hàng hoặc thanh toán trực tuyến.

## Yêu cầu

- Docker Engine và Docker Compose v2. Người dùng WSL có thể dùng Docker Desktop với tính năng tích hợp WSL.
- PHP 8.2 trở lên và Composer 2 cùng các extension cần thiết đã có trong image development; không cần cài PHP/Composer trên WSL.
- Node.js 18 trở lên và npm trên WSL/máy chủ để biên dịch giao diện khi cần.

## Chạy dự án trên WSL

Sao chép cấu hình mẫu, sau đó thay mật khẩu cơ sở dữ liệu bằng giá trị riêng cho máy local. Giữ `DB_DATABASE` trùng với `MYSQL_DATABASE`, `DB_USERNAME` trùng với `MYSQL_USER` và `DB_PASSWORD` trùng với `MYSQL_PASSWORD`. Tệp `.env` đã được Git bỏ qua và không được đưa vào image.

```sh
cp .env.example .env
npm ci
npm run build
docker compose -f compose.yaml -f compose.dev.yaml up --build -d
docker compose -f compose.yaml -f compose.dev.yaml exec app composer install
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan key:generate --force
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan migrate --seed
```

Mở <http://localhost:8080>. Trang đăng nhập quản trị ở `/admin/login`. Để tạo admin bằng seeder, điền `ADMIN_NAME`, `ADMIN_EMAIL` và `ADMIN_PASSWORD` trong `.env` (mật khẩu tối thiểu 12 ký tự), rồi chạy riêng:

```sh
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan db:seed --class=AdminUserSeeder
```

Seeder không được gọi tự động từ `DatabaseSeeder`, không ghi đè mật khẩu admin đã tồn tại và không tự nâng quyền tài khoản thường. Không commit `.env`. Có thể dùng `php artisan store:make-admin` nếu muốn tạo admin bằng lời nhắc mật khẩu tương tác.

Cấu hình development gắn mã nguồn WSL vào container PHP; Composer chạy bên trong container và thư mục `vendor` được bind-mount từ WSL để VS Code có thể phân tích các class Laravel. Cache, ảnh tải lên và MySQL vẫn dùng volume riêng. Chỉnh sửa Blade/PHP trực tiếp trong WSL; OPcache development tự kiểm tra thay đổi tệp. Sau khi chỉnh CSS hoặc JavaScript, chạy lại `npm run build`. Có thể dùng `npm run dev` để bật Vite HMR; dự án không chạy một container Node thường trực.

Một số lệnh Artisan thường dùng:

```sh
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan about
docker compose -f compose.yaml -f compose.dev.yaml exec app composer install
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan migrate
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan db:seed
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan optimize:clear
```

MySQL 8.4 chỉ được mở ra máy local tại `127.0.0.1:3307` trong cấu hình development. Ứng dụng kết nối bằng hostname Compose `mysql:3306`; cấu hình production không công khai cổng MySQL.

## Triển khai lên VPS Linux

`compose.yaml` là cấu hình production: không gắn mã nguồn vào container, cài dependency production, biên dịch tài nguyên, tắt debug, tự khởi động lại khi lỗi và lưu dữ liệu MySQL/ảnh bằng volume riêng. Mặc định Nginx chỉ lắng nghe trên `127.0.0.1:8080` để reverse proxy trên VPS có thể kết nối.

Tạo `.env` trên VPS, đặt quyền `600`, rồi cấu hình:

- `APP_KEY` riêng và `APP_URL=https://ten-mien-cua-ban.vn`.
- Mật khẩu mạnh cho `MYSQL_ROOT_PASSWORD` và tài khoản ứng dụng; đồng bộ các giá trị `DB_*` với `MYSQL_*`.
- `APP_ENV=production`, `APP_DEBUG=false`, `APP_LOCALE=vi`.
- `APP_BIND_ADDRESS=127.0.0.1`, `APP_PORT=8080` khi proxy chạy cùng VPS.

Tạo khóa Laravel bằng `openssl rand -base64 32`, thêm tiền tố `base64:` rồi lưu vào `.env`. Không đổi khóa sau khi đã có dữ liệu mã hóa hoặc người dùng. Sau đó triển khai:

```sh
chmod 600 .env
docker compose config
docker compose build --pull
docker compose up -d
docker compose ps
docker compose exec app php artisan migrate --force
docker compose exec app php artisan store:make-admin
```

Migration không tự chạy khi container khởi động. `migrate --force` chỉ áp dụng migration còn thiếu, không xóa dữ liệu. Không chạy `migrate:fresh` hoặc xóa volume trong các lần cập nhật thông thường.

### HTTPS và reverse proxy

Nginx đi kèm chỉ phục vụ HTTP. Hãy giữ cổng upstream trên loopback và kết thúc HTTPS tại reverse proxy của VPS. Chuyển tiếp `Host` cùng các header `X-Forwarded-*`, bao gồm giao thức HTTPS; chỉ tin cậy các địa chỉ proxy có quyền truy cập Nginx. Proxy tới `http://127.0.0.1:8080`. Không công khai MySQL ra Internet.

### Sao lưu và cập nhật

Sao lưu cả cơ sở dữ liệu lẫn ảnh đã tải lên; lưu bản sao ngoài các volume Compose và giới hạn quyền đọc:

```sh
mkdir -p backups
chmod 700 backups
umask 077
docker compose exec -T mysql sh -c 'exec mysqldump --single-transaction -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' > "backups/mysql-$(date +%F-%H%M%S).sql"
docker compose exec -T app tar -czf - -C storage/app public > "backups/anh-san-pham-$(date +%F-%H%M%S).tar.gz"
```

Khi khôi phục, hãy tạo bản sao mới của dữ liệu hiện tại trước và tạm dừng nhận thay đổi trên cửa hàng:

```sh
docker compose stop nginx app
docker compose exec -T mysql sh -c 'exec mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < backups/mysql-backup.sql
cat backups/anh-san-pham.tar.gz | docker compose exec -T app tar -xzf - -C storage/app
docker compose start app nginx
```

Khi cập nhật ứng dụng, sao lưu trước, cập nhật mã nguồn, build và thay container ứng dụng/Nginx. Volume MySQL và ảnh vẫn được giữ nguyên:

```sh
docker compose build --pull app nginx
docker compose up -d app nginx
docker compose exec app php artisan migrate --force
```

Không dùng `docker compose down -v` khi cập nhật. Nếu đổi cấu hình môi trường, xóa hoặc tạo lại cache Laravel phù hợp. Không commit `.env` hoặc bản sao lưu.

## Ảnh sản phẩm và dữ liệu demo

Seeder tạo sẵn danh mục tiếng Việt, sản phẩm mới, sản phẩm bán chạy và gallery nhiều ảnh cho từng sản phẩm. Ảnh demo được tải từ Unsplash CDN nên cần kết nối Internet; hãy thay bằng ảnh sản phẩm mà cửa hàng có quyền sử dụng trước khi triển khai thật. Ảnh do quản trị viên tải lên chấp nhận JPEG, PNG hoặc WebP, tối đa 5 MB mỗi ảnh và tối đa sáu ảnh cho một sản phẩm. Tên tệp được tạo an toàn và ảnh được lưu trên Laravel public disk trong volume `storage_data`.

Danh mục con được tạo/sửa trong **Quản trị → Danh mục** bằng trường “Danh mục cha”. Chỉ danh mục gốc có thể được chọn làm cha, nên cấu trúc hiện tại hỗ trợ hai cấp và tránh vòng lặp. Giá ưu đãi của sản phẩm thấp hơn giá gốc sẽ tự động đưa sản phẩm vào trang Sale. Bài viết được quản lý trong **Quản trị → Tin tức**; chỉ bài đã đăng và tới ngày đăng mới xuất hiện công khai. Ảnh bìa tin tức chấp nhận JPEG, PNG hoặc WebP, tối đa 5 MB.

Tên shop, thông tin liên hệ, mạng xã hội và link Zalo/Messenger được lưu trong **Quản trị → Cài đặt cửa hàng**. Các biến `STORE_NAME`, `STORE_EMAIL`, `STORE_PHONE`, `STORE_ADDRESS`, `STORE_INSTAGRAM`, `STORE_ZALO_URL` và `STORE_MESSENGER_URL` trong `.env.example` chỉ dùng để khởi tạo dữ liệu demo; hãy thay bằng thông tin thật trong admin trước khi đưa website cho khách sử dụng.

Trong admin, mục **Slider trang chủ** cho phép thêm, sửa, ẩn/hiện và sắp xếp slide; ảnh tải lên được kiểm tra định dạng và dung lượng trước khi lưu. Mục **Cài đặt cửa hàng** quản lý tên shop, liên hệ, mạng xã hội, URL Zalo/Messenger và nội dung nhãn sản phẩm nổi bật/bán chạy/Sale. Các mục này yêu cầu đăng nhập admin.

## Kiểm tra

```sh
docker compose -f compose.yaml -f compose.dev.yaml config --quiet
docker compose -f compose.yaml -f compose.dev.yaml build
docker compose -f compose.yaml -f compose.dev.yaml exec app php artisan test
npm run build
```

Kiểm tra trạng thái dịch vụ bằng `docker compose ps`. Trước khi chạy migration production, hãy kiểm tra `.env` và sao lưu dữ liệu.
