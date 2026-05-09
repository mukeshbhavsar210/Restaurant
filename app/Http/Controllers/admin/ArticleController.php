<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ArticleController extends Controller implements HasMiddleware 
{
    public static function middleware(): array {
        return [
                new Middleware('permission:view articles', only: ['index']),
                new Middleware('permission:edit articles', only: ['edit']),
                new Middleware('permission:create articles', only: ['create']),
                new Middleware('permission:delete articles', only: ['destroy']),
            ];
        }
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $articles = Article::latest()->paginate(25);

        return view("admin.articles.list", [
            'articles' => $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        //$permissions = Permission::orderBy('name','ASC')->get();
        return view("admin.articles.create", [
           
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'title' => 'required|min:5',
            'author' => 'required|min:5'
        ]);        

        if($validator->passes()){
            $article = new Article();
            $article->title = $request->title;
            $article->text = $request->text;
            $article->author = $request->author;
            $article->save();
           
            return redirect()->route('articles.index')->with('success','Article added successfully.');
        } else {
            return redirect()->route('articles.create')->withInput()->withErrors($validator);
        }
    }

  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);

        return view("admin.articles.edit", [
            'article' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [ 
            'title' => 'required|min:5',
            'author' => 'required|min:5'
        ]);        

        if($validator->passes()){
            $article->title = $request->title;
            $article->text = $request->text;
            $article->author = $request->author;
            $article->save();
           
            return redirect()->route('articles.index')->with('success','Article updated successfully.');
        } else {
            return redirect()->route('articles.edit',$id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){
        $id = $request->id;

        $article = Article::findOrFail($id);

        if($article == null){
            session()->flash('error','Article not found');
            return response()->json([
                'status' => false
            ]);
        }

        $article->delete();

        session()->flash('success','Article deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
