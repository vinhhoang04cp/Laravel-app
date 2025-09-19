#!/bin/bash
# Thư mục chứa file PDF hoặc DOCX đã gen ra
FOLDER="/Users/cuongphan/Desktop/abb/rest/storage/app/ballots/dai_hoi_co_dong_nam_2025"

# Kiểm tra danh sách máy in
echo "Danh sách máy in khả dụng:"
lpstat -p -d

# Hỏi người dùng chọn máy in
read -p "Nhập tên máy in muốn sử dụng: " PRINTER

# In từng file PDF/DOCX trong thư mục
for file in "$FOLDER"/*; do
    echo "Đang in: $file ..."
    lp -d "$PRINTER" "$file"
done

echo "✅ Hoàn thành in tất cả file trong $FOLDER"
