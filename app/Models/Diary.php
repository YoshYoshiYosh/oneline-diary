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
            if ($postData['imageBase64']) self::saveImageFile($newDiary->id, $postData['imageBase64']);
    
            DB::commit();

            return true;
        } catch (\Exception $e) {
            Log::error("Error at createNewDiary: " . $e->getMessage());
            DB::rollBack();
            
            return false;
        }
    }

    public static function saveImageFile(int $diaryId, string $imageBase64)
    {
        $directory = "diary_images/{$diaryId}";
    
        // メタデータを削除して、画像ファイルに変換
        $imageBase64RemovedMeta = substr($imageBase64, strpos($imageBase64, ',') + 1);
        $imageData = base64_decode($imageBase64RemovedMeta);
    
        Storage::disk('public')->put("{$directory}/image.jpg", $imageData);
    }

    protected $dates = ['created_at', 'updated_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d H:i');
    }
}
