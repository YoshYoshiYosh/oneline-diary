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

    public function testDiaryIndexWithPagination()
    {
        Diary::factory()->count(10)->create();
    
        $response = $this->get('/diaries');
    
        $response->assertStatus(200);
    
        // 1ページに表示される日記の数を確認
        $diariesOnFirstPage = $response->viewData('diaries')->items();
        $this->assertCount(5, $diariesOnFirstPage);
    }

    public function testDiaryIndexSecondPage()
    {
        Diary::factory()->count(8)->create();
    
        // 2ページ目
        $response = $this->get('/diaries?page=2');
    
        $response->assertStatus(200);
    
        // 2ページに表示される日記の数を確認
        $diariesOnSecondPage = $response->viewData('diaries')->items();
        $this->assertCount(3, $diariesOnSecondPage);
    }
}