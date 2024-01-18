<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'content'
    ];

    const PAGINATION_COUNT = 5;

    public static function createNewDiary(array $postData)
    {
        DB::beginTransaction();
        
        try {
            $newDiary = self::create([
                'content' => $postData["content"]
            ]);
    
            if (!$newDiary) throw new \Exception("Diary creation failed");
    
            // 画像データが添付されている場合のみ、画像ファイルを保存する
            if ($postData['imageBase64']) $newDiary->saveImageFile($postData['imageBase64']);
    
            DB::commit();

            return true;
        } catch (\Exception $e) {
            Log::error("Error at createNewDiary: " . $e->getMessage());
            DB::rollBack();
            
            return false;
        }
    }

    public static function updateDiary(array $postData)
    {
        DB::beginTransaction();
        
        try {
            $targetDiary = self::find($postData["id"]);
    
            if (!$targetDiary) throw new \Exception("Diary update failed");

            $targetDiary->content = $postData["content"];

            $targetDiary->save();
    
            // 画像データが添付されている場合のみ、画像ファイルを保存する
            if ($postData['imageBase64']) {
                $targetDiary->saveImageFile($postData['imageBase64']);
            } else if (isset($postData['willRemove'])) {
                $targetDiary->deleteImageFile();
            }
    
            DB::commit();

            return true;
        } catch (\Exception $e) {
            Log::error("Error at updateDiary: " . $e->getMessage());
            DB::rollBack();
            
            return false;
        }
    }

    public function deleteDiary()
    {
        try {
            $this->update(["is_active" => false]);
            return true;
        } catch (\Exception $e) {
            Log::error("Error at deleteDiary: " . $e->getMessage());
            
            return false;
        }
    }

    public function saveImageFile(string $imageBase64)
    {
        $directory = "diary_images/{$this->id}";
    
        // メタデータを削除して、画像ファイルに変換
        $imageBase64RemovedMeta = substr($imageBase64, strpos($imageBase64, ',') + 1);
        $imageData = base64_decode($imageBase64RemovedMeta);
    
        Storage::disk('public')->put("{$directory}/image.jpg", $imageData);
    }

    public function deleteImageFile()
    {
        $directory = "diary_images/{$this->id}";
    
        // 指定されたディレクトリの画像ファイルを削除
        Storage::disk('public')->delete("{$directory}/image.jpg");
    }

    protected $dates = ['created_at', 'updated_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d H:i');
    }

    public function hasImage() {
        return Storage::disk('public')->exists('diary_images/' . $this->id . '/image.jpg');
    }
}
