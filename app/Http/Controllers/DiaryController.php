<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diaries = Diary::whereIsActive(true)->orderBy('created_at', 'desc')->paginate(Diary::PAGINATION_COUNT);
        return view('diaries.index', compact('diaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diaries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|max:255',
            'imageBase64' => 'string|nullable'
        ]);
        $isSuccessed = Diary::createNewDiary($validatedData);

        // リダイレクト先と処理結果メッセージを変数に代入する
        $redirectRoute = 'diaries.create';
        $flashMessage = $isSuccessed ? ['success' => '日記を保存しました！'] : ['error' => '日記の保存に失敗しました。'];
        
        return redirect()->route($redirectRoute)->with($flashMessage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        $imageExists = $diary->hasImage();
        return view('diaries.edit', compact('diary', 'imageExists'));
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'content' => 'required|max:255',
            'imageBase64' => 'string|nullable',
            'willRemove' => 'nullable'
        ]);
    
        $isSuccessed = Diary::updateDiary($validatedData);
    
        // 処理結果メッセージを変数に代入する
        if ($isSuccessed) {
            $request->session()->flash('success', '日記を編集しました！');
        } else {
            $request->session()->flash('error', '日記の編集に失敗しました。');
        }
    
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Diary $diary)
    {
        // 削除処理
        $isSuccessed = $diary->deleteDiary();

        if ($isSuccessed) {
            $request->session()->flash('success', '日記を削除しました。');
        } else {
            $request->session()->flash('error', '日記の削除に失敗しました。');
        }
    
        // 削除後のリダイレクト先
        return redirect()->route('diaries.index');
    }
}
