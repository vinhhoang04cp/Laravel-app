<a href="{{ route('shareholders.edit', $id) }}" class="btn btn-sm btn-primary mr-1">
    <i class="fas fa-edit"></i> Sửa
</a>

<button class="btn btn-sm btn-danger btn-delete-shareholder"
        data-id="{{ $id }}"
        data-name="{{ $name }}">
    <i class="fas fa-trash"></i> Xoá
</button>

<button class="btn btn-sm btn-success btn-invite-shareholder"
        data-id="{{ $id }}"
        data-name="{{ $name }}">
    <i class="fas fa-envelope"></i> Mời đăng ký
</button>
