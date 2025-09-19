<a href="{{ route('congresses.shareholders', $id) }}" class="btn btn-sm btn-warning mr-1">
    <i class="voyager-eye"></i> <span>Xem danh sách</span>
</a>

<a href="{{ route('congresses.edit', $id) }}" class="btn btn-sm btn-primary mr-1">
    <i class="voyager-edit"></i> <span>Sửa</span>
</a>

<button class="btn btn-sm btn-danger btn-delete-congress"
        data-id="{{ $id }}"
        data-name="{{ $name }}">
    <i class="voyager-trash"></i> <span>Xoá</span>
</button>
