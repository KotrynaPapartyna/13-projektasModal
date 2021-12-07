<?php

namespace App\Http\Controllers;

use App\Article;
use App\Type;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $articles = Article::all();

        $types = Type::all();
        return view('article.index',['articles'=> $articles, 'types'=> $types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        return view("article.create", ['types'=> $types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeNew = $request->typeNew;

        if($typeNew == "1") { // koks kintamojo tipas ateina is checkbox jei jis pazymetas? 1 tekstas
            $type = new Type;
            $type->title =  $request->typeTitle;
            $type->description = $request->typeDescription;

            $type->save();

            $typeId = $type->id;
        } else {
            $typeId = $request->articleType;
        }


        $article = new Article;

        $article->title = $request->articleTitle;
        $article->description = $request->articleDescription;
        $article->type_id = $typeId;

        $article->save();

        return redirect()->route('article.index');
    }

    public function storeAjax(Request $request) {


        $article = new Article;

        $input = [
            'articleTitle' => $request->articleTitle,
            'articleDescription' => $request->articleDescription,
            'articleType' => $request->articleType
        ];

        $rules = [
            'articleTitle' => 'required|min:3',
            'articleDescription' => 'min:15',
            'articleType' => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $article->title = $request->articleTitle;
            $article->description = $request->articleDescription;
            $article->type_id = $request->articleType;

            $article->save();

            $success = [
                'success' => 'Article added successfully',
                'articleId' => $article->id,
                'articleTitle' => $article->title,
                'articleDescription' => $article->description,
                'articleType' => $article->articleType->title
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view("article.show", ['article' => $article]);
    }

    public function showAjax(Article $article) {

        $success = [
            'success' => 'Article recieved successfully',
            'articleId' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleType' => $article->articleType->title
        ];

        $success_json = response()->json($success);

        return $success_json;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        return view("article.edit", ['article'=> $article]);
    }


    public function editAjax(Article $article) {
        $success = [
            'success' => 'Article recieved successfully',
            'articleId' => $article->id,
            'articleTitle' => $article->title,
            'articleDescription' => $article->description,
            'articleType' => $article->type_id
        ];

        $success_json = response()->json($success);

        return $success_json;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {


    }


    public function updateAjax(Request $request, Article $article) {
        $input = [
            'articleTitle' => $request->articleTitle,
            'articleDescription' => $request->articleDescription,
            'articleType' => $request->articleType
        ];

        $rules = [
            'articleTitle' => 'required|min:3',
            'articleDescription' => 'min:15',
            'articleType' => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $article->title= $request->articleTitle;
            $article->description = $request->articleDescription;
            $article->type_id = $request->articleType;

            $article->save();

            $success = [
                'success' => 'Article update successfully',
                'articleId' => $article->id,
                'articleTitle' => $article->title,
                'articleDescription' => $article->description,
                'articleType' => $article->articleType->title
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route("article.index");
    }

    public function destroyAjax(Article $article)
    {

        $type_id = $article->type_id;

        $article->delete();

        $articlesLeft = Article::where('type_id', $type_id)->get() ;
        $articlesCount = $articlesLeft->count();

        $success = [
            "success" => "The Article deleted successfuly",
            "articlesCount" => $articlesCount
        ];

        $success_json = response()->json($success);

        return $success_json;
    }

    public function searchAjax(Request $request) {

        $searchValue = $request->searchField;


        $articles = Article::query()
            ->where('title', 'like', "%{$searchValue}%")
            ->orWhere('description', 'like', "%{$searchValue}%")
            ->get();

        foreach ($articles as $article) {
            $article['typeTitle'] = $article->articleType->title;
        }

        if($searchValue == '' || count($articles)!= 0) {

            $success = [
                'success' => 'Found '.count($articles),
                'articles' => $articles
            ];

            $success_json = response()->json($success);


            return $success_json; //yra musu sekmes pranesimas
        }

        $error = [
            'error' => 'No results are found'
        ];

        $errors_json = response()->json($error);

        return $errors_json;

    }

    public function indexAjax(Request $request) {

        $sortCol = $request->sortCol;

        $sortOrder = $request->sortOrder;

        $type_id = $request->type_id;

        if($type_id == 'all') {
            $articles = Article::orderBy($sortCol, $sortOrder)->get();
        } else {
            $articles = Article::where('type_id', $type_id)->orderBy($sortCol, $sortOrder)->get();
        }


        foreach ($articles as $article) {
            $article['typeTitle'] = $article->articleType->title;
        }

        $articles_count = count($articles);


        if ($articles_count == 0) {
            $error = [
                'error' => 'There are no articles',
            ];

            $error_json = response()->json($error);
            return $error_json;
        }


        $success = [
            'success' => 'Articles sorted successfuly',
            'articles' => $articles
        ];

        $success_json = response()->json($success);

        return $success_json;

    }

    public function filterAjax(Request $request) {

        $type_id = $request->type_id;

        if($type_id == 'all') {
            $articles = Article::all();
        } else {
            $articles = Article::all()->where('type_id', $type_id);
        }

        foreach ($articles as $article) {
            $article['typeTitle'] = $article->articleType->title;
        }

        $articles_count = count($articles);

        if ($articles_count == 0) {
            $error = [
                'error' => 'There are no articles',
            ];

            $error_json = response()->json($error);
            return $error_json;
        }

        $success = [
            'success' => 'Articles filtered successfuly',
            'articles' => $articles
        ];

        $success_json = response()->json($success);

        return $success_json;



    }
}
