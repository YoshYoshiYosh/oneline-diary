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
        //
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
     * Display the specified resource.
     */
    public function show(Diary $diary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
        //
    }
}
