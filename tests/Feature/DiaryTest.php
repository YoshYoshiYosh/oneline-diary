<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Diary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DiaryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateNewDiary()
    {
        Storage::fake('public');

        $response = $this->post('/diaries', [
            'content' => 'test',
            'imageBase64' => ""
        ]);

        $response->assertRedirect('/diaries/create');
        $response->assertSessionHas('success', '日記を保存しました！');
        $this->assertCount(1, Diary::all());
    }

    public function testCreateNewDiaryWithImage()
    {
        Storage::fake('public');

        $response = $this->post('/diaries', [
            'content' => 'This is a test diary entry.',
            'imageBase64' => base64_encode(UploadedFile::fake()->image('image.jpg')->size(100))
        ]);

        $response->assertRedirect('/diaries/create');
        $response->assertSessionHas('success', '日記を保存しました！');
        $this->assertCount(1, Diary::all());
    }

    public function testCreateNewDiaryFailure()
    {
        $response = $this->post('/diaries', [
            'content' => '',
            'imageBase64' => base64_encode(UploadedFile::fake()->image('image.jpg')->size(100))
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertCount(0, Diary::all());
    }
}