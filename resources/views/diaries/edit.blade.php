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
    <form action="{{ route('diaries.update', $diary) }}" method="POST" id="diary-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $diary->id }}">
        <div class="mb-2">
            <h2 for="diary-content" class="form-label">一行日記を編集</h2>
        </div>
        <div class="mb-3">
            <textarea id="diary-content" name="content" rows="2" maxlength="255" class="form-control w-50" placeholder="日記をここに書きましょう！">{{ $diary->content }}</textarea>
        </div>
        <div class="mb-3">
            <label for="diary-image" class="custom-file-upload">ファイルを選択</label>
            <input type="file" id="diary-image"  accept="image/jpeg, image/jpg" style="display: none;"/>
            <div class="mt-3">
                @if ($imageExists)
                    <img id="image-preview" src="{{ asset('storage/diary_images/' . $diary->id . '/image.jpg') }}" style="width: 100px;">
                @endif
                <button type="button" id="remove-image" class="btn btn-danger mt-2 d-none">画像を削除</button>
            </div>
        </div>
        <input type="hidden" name="imageBase64" id="image-base64">
        <div class="mb-4">
            <button type="submit" id="save-diary-button" class="btn btn-primary">日記を編集する</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('diary-form')
        const textArea = document.getElementById('diary-content')
        const saveButton = document.getElementById('save-diary-button')
        const imageInput = document.getElementById('diary-image')
        const imageBase64Input = document.getElementById('image-base64')
        const removeImageButton = document.getElementById('remove-image')
        const imagePreview = document.getElementById('image-preview')
        let hasImageOnServer = false
        
        saveButton.disabled = false

        if (imagePreview) {
            hasImageOnServer = true
            removeImageButton.classList.add("d-inline")
            removeImageButton.classList.remove("d-none")
        }

        // 画像ファイルをBase64に変換
        imageInput.addEventListener('change', function(event) {
            // 以前のプレビューがあれば削除
            const imgPreview = document.getElementById('image-preview')
            if (imgPreview) {
                imgPreview.remove()
            }
            
            const file = event.target.files[0]
            if (file) {
                const reader = new FileReader()
                reader.onload = function(e) {
                    imageBase64Input.value = e.target.result

                    // プレビュー用の要素を作成
                    const imgPreview = document.createElement('img')
                    imgPreview.id = 'image-preview'
                    imgPreview.src = e.target.result
                    imgPreview.style.width = '100px'
                    imgPreview.style.marginRight = '10px'
                    removeImageButton.parentNode.insertBefore(imgPreview, removeImageButton)

                    // 画像削除フラグ用の隠しフィールドを削除
                    const hiddenField = document.querySelector('input[name="willRemove"]')
                    if (hiddenField) {
                        hiddenField.remove()
                    }
                }
                reader.readAsDataURL(file)
            }
        })

        // 画像を削除するボタンのイベントリスナー
        removeImageButton.addEventListener('click', function() {
            imageBase64Input.value = ''
            imageInput.value = ''

            // プレビューを削除
            const imgPreview = document.getElementById('image-preview')
            if (imgPreview) {
                imgPreview.remove()
            }
            
            removeImageButton.classList.remove("d-inline")
            removeImageButton.classList.add("d-none")

            // 画像を削除した状態でフォーム送信されたら、サーバー上の画像を削除する
            const hiddenField = document.createElement('input')
            hiddenField.type = 'hidden'
            hiddenField.name = 'willRemove'
            hiddenField.value = true
            form.appendChild(hiddenField)
        })

        // textareaの内容が変更されたときのイベントリスナー
        textArea.addEventListener('input', function() {
            saveButton.disabled = !this.value.trim()
        })

        imageInput.addEventListener('change', function() {
            removeImageButton.classList.remove("d-none")
            removeImageButton.classList.add("d-inline")
        })
    })
</script>
@endsection