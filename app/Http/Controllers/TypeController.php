<?php

namespace App\Http\Controllers;

use App\Article;
use App\Type;
use Illuminate\Http\Request;
use Validator;

class TypeController extends Controller
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
        return view('type.index',['types'=> $types, 'articles'=> $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $articles = Article::all();
        return view("type.create", ['articles'=> $articles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $articleNew = $request->articleNew;

        if($articleNew == "1") {
            $article = new Article;
            $article->title =  $request->articleTitle;
            $article->description = $request->articleDescription;

            $article->save();

            $articleId = $article->id;
        } else {
            $articleId = $request->typeArticle;
        }


        $type = new Type;

        $type->title = $request->typeTitle;
        $type->description = $request->typeDescription;
        $type->article_id = $articleId;

        $type->save();

        return redirect()->route('type.index');
    }

    public function storeAjax(Request $request) {


        $type = new Type;

        $input = [
            'typeTitle' => $request->typeTitle,
            'typeDescription' => $request->typeDescription,

        ];//ka mes ivedama, laukeliu pavadinimai kuriuos validuosim

        $rules = [
            'typeTitle' => 'required|min:3',
            'typeDescription' => 'min:15',

        ]; //taisykles

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $type->title = $request->typeTitle;
            $type->description = $request->typeDescription;


            $type->save();

            $success = [
                'success' => 'Type added successfully',
                'typeId' => $type->id,
                'typeTitle' => $type->title,
                'typeDescription' => $type->description,

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
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return view("type.show", ['type' => $type]);
    }

    public function showAjax(Type $type) {

        $success = [
            'success' => 'Type recieved successfully',
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description
        ];

        $success_json = response()->json($success);

        return $success_json;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        return view("type.edit", ['type'=> $type]);
    }

    //gauti informacija apie klienta i edit modal forma
    public function editAjax(Type $type) {
        $success = [
            'success' => 'Type recieved successfully',
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description
        ];

        $success_json = response()->json($success);

        return $success_json;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {


    }

    //kuri atnaujintus duomenis patalpis i duomenu baze
    public function updateAjax(Request $request, Type $type) {
        $input = [
            'typeTitle' => $request->typeTitle,
            'typeDescription' => $request->typeDescription

        ];//ka mes ivedama, laukeliu pavadinimai kuriuos validuosim

        $rules = [
            'typeTitle' => 'required|min:3',
            'typeDescription' => 'min:15'
        ]; //taisykles

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $type->title = $request->typeTitle;
            $type->description = $request->typeDescription;


            $type->save();

            $success = [
                'success' => 'Type update successfully',
                'typeId' => $type->id,
                'typeTitle' => $type->title,
                'typeDescription' => $type->description
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
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return redirect()->route("type.index");
    }

    public function destroyAjax(Type $type)
    {
        $article_id = $type->article_id;

        $type->delete();

        $typesLeft = type::where('article_id', $article_id)->get() ;
        $typesCount = $typesLeft->count();

        //sekmes nesekmes zinute
        $success = [
            "success" => "The Type deleted successfuly",
            "typesCount" => $typesCount
        ];
        $success_json = response()->json($success);

        return $success_json;
    }

    public function searchAjax(Request $request) {

        $searchValue = $request->searchField;

        $types = Type::query()
            ->where('title', 'like', "%{$searchValue}%")
            ->orWhere('description', 'like', "%{$searchValue}%")
            ->get();


        if($searchValue == '' || count($types)!= 0) {

            $success = [
                'success' => 'Found '.count($types),
                'types' => $types
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

        $article_id = $request->article_id;

        if($article_id == 'all') {
            $types = Type::orderBy($sortCol, $sortOrder)->get();
        } else {
            $types = Type::where('article_id', $article_id)->orderBy($sortCol, $sortOrder)->get();
        }


        foreach ($types as $type) {
            $type['articleTitle'] = $type->typeArtical->title;
        }

        $types_count = count($types);


        if ($types_count == 0) {
            $error = [
                'error' => 'There are no types',
            ];

            $error_json = response()->json($error);
            return $error_json;
        }


        $success = [
            'success' => 'Types sorted successfuly',
            'types' => $types
        ];

        $success_json = response()->json($success);

        return $success_json;

    }

    public function filterAjax(Request $request) {

        $article_id = $request->article_id;

        if($article_id == 'all') {
            $types = Type::all();
        } else {
            $types = Type::all()->where('article_id', $article_id);
        }

        foreach ($types as $type) {
            $type['articleTitle'] = $type->typeArticle->title;
        }

        $types_count = count($types);

        if ($types_count == 0) {
            $error = [
                'error' => 'There are no types',
            ];

            $error_json = response()->json($error);
            return $error_json;
        }

        $success = [
            'success' => 'Types filtered successfuly',
            'types' => $types
        ];

        $success_json = response()->json($success);

        return $success_json;
    }
}
