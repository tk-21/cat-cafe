<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

//ユーザー登録画面表示
    public function create()
    {
        return view('admin.users.create');
    }

    //ユーザー登録処理

//    作成したフォームリクエストを使用する
    public function store(StoreUserRequest $request)
    {
//        バリデーション済みのデータを取得
        $validated = $request->validated();
//        imageカラムにはアップロードしたファイルのパスを
        $validated['image'] = $request->file('image')->store('users', 'public');
//        passwordカラムにはハッシュ化したパスワードを設定
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

//        再度登録画面を表示
        return back()->with('success', 'ユーザーを登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
