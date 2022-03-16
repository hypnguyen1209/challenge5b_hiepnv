# challenge5b_hiepnv

Backend: Laravel
Frontend: Vue 3 + Pinia
## Yêu cầu:

Lập trình bằng framework Laravel, sử dụng DB MySQL để xây dựng
website quản lý thông tin sinh viên, tài liệu của 1 lớp học.

### Yêu cầu ứng dụng:

- Giao diện website rõ ràng, sạch đẹp (có sử dụng HTML, CSS để định dạng và thiết kế website) (1đ)

### Yêu cầu chức năng:

- Giáo viên có thể thêm, sửa, xóa các thông tin của sinh viên. Thông tin có các trường cơ bản gồm: tên đăng nhập, mật khẩu, họ tên, email, số điện thoại (1đ)

- Sinh viên sau khi đăng nhập được phép thay đổi các thông tin của mình, cho phép upload avatar từ file hoặc url; sinh viên không được phép thay đổi tên đăng nhập và họ tên (1đ).

- Một người dùng (giáo viên hoặc sinh viên) bất kỳ đc phép xem danh sách các người dùng trên website và xem thông tin chi tiết của một người dùng khác. Tại trang xem thông tin chi tiết của một người dùng có mục để lại tin nhắn cho người dùng đó, có thể sửa/xóa tin nhắn đã gửi (2đ).

- Chức năng giao bài, trả bài:
    - Giáo viên có thể upload file bài tập lên. Các sinh viên có thể xem danh sách bài tập và tải file bài tập về (1đ).
    - Sinh viên có thể upload bài làm tương ứng với bài tập được giao. Chỉ giáo viên mới nhìn thấy danh sách bài làm này (1đ).

- Tạo chức năng cho phép giáo viên tổ chức 1 trò chơi giải đố như sau:
    - Giáo viên tạo challenge, trong đó cần thực hiện: upload lên 1 file txt có nội dung là 1 bài thơ, văn,…, tên file được viết dưới định dạng không dấu và các từ cách nhau bởi 1 khoảng trắng. Sau đó nhập gợi ý về challenge và submit. (Đáp án chính là tên file mà giáo viên upload lên. Không lưu đáp án ra file, DB,…) (1đ)

### Cài đặt

##### Chạy file make_own.sh trong thư mục backend

Laravel chỉ có thể ghi log khi có user có quyền owner

```sh
chmod +x ./backend/make_own.sh
./backend/make_own.sh
```

##### Cài dặt docker và docker-compose

Build và deploy:

```sh
docker-compose up -d
```

#### Chạy 1 số lệnh trong docker container

Cài đặt các dependence cho laravel

```sh
docker exec -it php_challenge5b
> composer install
```

Chuyển owner về www-data và xóa cache config

```sh
docker exec -it php_challenge5b
> chown -R www-data:www-data *
> php artisan config:cache
```

Done.
Mở trình duyệt với địa chỉ. http://\<ip\>:110000