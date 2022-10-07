<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Cat;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    // ブログ一覧画面
    public function index()
    {
//        記事をすべて取得
        $blogs = Blog::latest('updated_at')->simplePaginate(10);

        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    // ブログ投稿画面
    public function create()
    {
        return view('admin.blogs.create');
    }

    // ブログ投稿処理
    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();
        $validated['image'] = $request->file('image')->store('blogs', 'public');
        Blog::create($validated);

        // 登録が終わったら完了画面とともに一覧画面へリダイレクト
        return to_route('admin.blogs.index')->with('success', 'ブログを投稿しました');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    // 指定したIDのブログ編集画面
    public function edit(Blog $blog)
    {
//        カテゴリーをすべて取得
        $categories = Category::all();
//        Cat内の全レコードを取得しviewで使えるようにする
        $cats = Cat::all();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' => $cats]);
    }

    //　ブログ更新
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);

//        バリデーション後のデータを取得
        $updateData = $request->validated();

//        画像を変更する場合
        if ($request->has('image')) {
//            変更前の画像削除
            Storage::disk('public')->delete($blog->image);
//            変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }
//        外部キーをセットしてリレーションデータの更新
        $blog->category()->associate($updateData['category_id']);
//        検証された値をセットしてリレーションデータの更新
        $blog->cats()->sync($updateData['cats']);

        $blog->update($updateData);

        return to_route('admin.blogs.index')->with('success', 'ブログを更新しました');
    }

    // 指定したIDのブログの削除処理
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
//        不要になった画像は削除しておく
        Storage::disk('public')->delete($blog->image);

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');
    }
}
