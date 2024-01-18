@extends('layouts.app')

@section('content')
<div class="container rounded bg-white py-5 px-3">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form action="{{ route('diaries.store') }}" method="POST">
        @csrf
        <div class="mb-2">
            <h2 for="diary-content" class="form-label">今日の一行日記</h2>
        </div>
        <div class="mb-3">
            <textarea id="diary-content" name="content" rows="2" maxlength="255" class="form-control w-50" placeholder="日記をここに書きましょう！"></textarea>
        </div>
        <div class="mb-3">
            <input type="file" id="diary-image" class="form-control wid-350" accept="image/jpeg, image/jpg">
            <button type="button" id="remove-image" class="btn btn-danger mt-2 d-none">画像を削除</button>
        </div>
        <input type="hidden" name="imageBase64" id="image-base64">
        <div class="mb-4">
            <button type="submit" id="save-diary-button" class="btn btn-primary">日記を保存する</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textArea = document.getElementById('diary-content')
        const saveButton = document.getElementById('save-diary-button')
        const imageInput = document.getElementById('diary-image')
        const imageBase64Input = document.getElementById('image-base64')
        const removeImageButton = document.getElementById('remove-image')

        saveButton.disabled = true
        removeImageButton.hidden = true

        // 画像ファイルをBase64に変換
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0]
            if (file) {
                const reader = new FileReader()
                reader.onload = function(e) {
                    imageBase64Input.value = e.target.result
                    console.log('imageBase64Input')
                    console.log(imageBase64Input)
                }
                reader.readAsDataURL(file)
            }
        })

        // 画像を削除するボタンのイベントリスナー
        removeImageButton.addEventListener('click', function() {
            imageBase64Input.value = ''
            imageInput.value = ''
            removeImageButton.classList.remove("d-inline")
            removeImageButton.classList.add("d-none")
        })

        // textareaの内容が変更されたときのイベントリスナー
        textArea.addEventListener('input', function() {
            saveButton.disabled = !this.value.trim()
        })

        imageInput.addEventListener('change', function() {
            removeImageButton.classList.remove("d-none")
            removeImageButton.classList.add("d-inline")
        })
    });
</script>
@endsection