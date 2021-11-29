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

        if($typeNew == "1") {
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


        $article = new Article();

        $input = [                 //ivedami laukeliu pavadinimai, kurie bus validuojami
            'articleTitle' => $request->articleTitle,
            'articleDescription' => $request->articleDescription,
            'articleType' => $request->articleType
        ];

        $rules = [             // validacijos taisykles/reikalavimai
            'articleTitle' => 'required|min:3|max:20',
            'articleDescription' => 'min:20',
            'articleType' => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        // jeigu validacija praeina:
        if($validator->passes()) {
            $article->title= $request->articleTitle;
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
        $input = [            // redaguojami input
            'articleTitle' => $request->articleTitle,
            'articleDescription' => $request->articleDescription,
            'articleType' => $request->articleType
        ];

        $rules = [                //taisykles/ reikalavimai validacijai
            'articleTitle' => 'required|min:3|max:20',
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

// Sukuriama nauja funkcija- pasirenkami ir istrinami Type
    public function destroySelected(Request $request) {

        $checkedArticles = $request->checkedArticles;

        $messages = array();

        $errorsuccess = array();

        foreach($checkedArticles as $articleId) {

            $article = Article::find($articleId);


            $deleteAction = $article->delete();

                if($deleteAction) {
                    $errorsuccess[] = 'success';
                    $messages[] = "Article ".$articleId." deleted successfully";
                } else {
                    $messages[] = "Something went wrong";
                    $errorsuccess[] = 'danger';
                }
        }

        $success = [
            'success' => $checkedArticles,
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }
}







