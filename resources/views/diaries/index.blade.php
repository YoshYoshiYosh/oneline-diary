@extends('layouts.app')

@section('content')
<div class="container rounded bg-white py-5 px-3">
    <div class="mb-4">
        <h2 class="form-label">振り返りましょう！</h2>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @foreach ($diaries as $diary)
        <div class="mb-4 bg-gray py-3 px-4 rounded">
            <div class="mb-2">
                <span>{{ $diary->created_at }}</span>
                <a class="btn-dark-yellow p-1 px-2 ml-2" href="{{ route('diaries.edit', $diary) }}">編集</a>
                <form action="{{ route('diaries.destroy', $diary) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="pb-2 px-2 btn-dark-red" onclick="return confirm('本当に削除しますか？');">削除</button>
                </form>
            </div>
            <div class="diary-entry">
                <img 
                    src="{{ asset('storage/diary_images/' . $diary->id . '/image.jpg') }}" 
                    alt="Diary Image" 
                    style="width: 100px;" 
                    onerror="this.onerror=null; this.src='{{ asset('dummy.jpg') }}';"
                >
                <span style="margin-left: 5px;">{{ $diary->content }}</span>
            </div>
        </div>
    @endforeach
    {{ $diaries->links() }}
    @if (count($diaries) == 0)
        <p>まだ日記がないようです。<a href="/diaries/create" class="text-underline text-black">書いてみましょう！</a></p>
    @endif

</div>
@endsection