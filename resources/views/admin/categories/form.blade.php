@extends('layouts.admin')
@section('title', $category->exists ? 'Sửa danh mục' : 'Thêm danh mục')
@section('content')
<div class="mb-8"><a href="{{ route('admin.categories.index') }}" class="eyebrow underline underline-offset-4">← Danh mục</a><p class="eyebrow mt-7">Cấu trúc bộ sưu tập</p><h1 class="mt-2 font-display text-4xl">{{ $category->exists ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới' }}</h1></div>
<form method="POST" enctype="multipart/form-data" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-2xl border border-stone-300 bg-white p-5 sm:p-8">@csrf @if($category->exists)@method('PUT')@endif
    <label class="block"><span class="eyebrow">Tên danh mục *</span><input class="form-control mt-2" name="name" maxlength="120" required value="{{ old('name', $category->name) }}"></label>
    <label class="mt-6 block"><span class="eyebrow">Danh mục cha</span><select class="form-control mt-2" name="parent_id"><option value="">Danh mục gốc</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected((string) old('parent_id', $category->parent_id) === (string) $parent->id)>{{ $parent->name }}</option>@endforeach</select><span class="mt-2 block text-xs text-stone-500">Danh mục con sẽ xuất hiện bên dưới danh mục cha trên cửa hàng.</span></label>
    <label class="mt-6 block"><span class="eyebrow">Mô tả</span><textarea class="form-control mt-2" name="description" rows="5" maxlength="2000">{{ old('description', $category->description) }}</textarea></label>
    <div class="mt-6">
        <label class="block"><span class="eyebrow">Ảnh danh mục</span>
            @if($category->image_url)<img src="{{ $category->image_url }}" alt="Ảnh danh mục {{ $category->name }}" class="mt-3 aspect-[4/3] w-full max-w-sm rounded-md border border-stone-200 object-cover">@endif
            <input class="form-control mt-3" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <span class="mt-2 block text-xs text-stone-500">JPEG, PNG hoặc WebP · tối đa 5 MB. Ảnh sẽ hiển thị trên thẻ danh mục ở trang chủ.</span>
            @error('image')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror
        </label>
        @if($category->image_path)<label class="mt-3 flex items-center gap-2 text-xs text-stone-600"><input type="checkbox" name="remove_image" value="1" class="rounded border-stone-400 text-ink focus:ring-clay"><span>Xóa ảnh danh mục hiện tại</span></label>@endif
    </div>
    <div class="mt-7 flex flex-wrap gap-3"><button class="button-primary">{{ $category->exists ? 'Lưu thay đổi' : 'Tạo danh mục' }}</button><a class="button-outline" href="{{ route('admin.categories.index') }}">Hủy</a></div>
</form>
@endsection
