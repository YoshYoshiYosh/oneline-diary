@extends('layouts.app')

@section('content')
<div class="container rounded bg-white py-5 px-3">
    <div class="mb-4">
        <h2 class="form-label">振り返りましょう！</h2>
    </div>
    @foreach ($diaries as $diary)
        <div class="mb-4 bg-gray py-3 px-4 rounded">
            <div class="mb-2">
                <span>{{ $diary->created_at }}</span>
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

</div>
@endsection