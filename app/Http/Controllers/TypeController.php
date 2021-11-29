<?php

namespace App\Http\Controllers;

use App\Type;
use App\Article;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        $articles = Article::all();
        return view('type.index', ['types'=>$types, 'articles'=> $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("type.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $articlesNew = $request->articlesNew;

        $type = new Type;

        $type->title = $request->typeTitle;
        $type->description = $request->typeDescription;
        $type->save();

        return redirect()->route("type.index");
    }

    public function storeAjax(Request $request) {


        $type = new Type();

        $input = [                 //ivedami laukeliu pavadinimai, kurie bus validuojami
            'typeTitle' => $request->typeTitle,
            'typeDescription' => $request->typeDescription,
        ];

        $rules = [             // validacijos taisykles/reikalavimai
            'typeTitle' => 'required|min:3|max:20',
            'typeDescription' => 'min:20',
        ];

        $validator = Validator::make($input, $rules);

        // jeigu validacija praeina:
        if($validator->passes()) {
            $type->title= $request->typeTitle;
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
        $articles = $type->typeArticles;
        $articlesCount = $articles->count();

        return view("type.show",['type' => $type, 'articles'=>$articles, 'articlesCount'=> $articlesCount]);
    }

    public function showAjax(Type $type) {

        $success = [
            'success' => 'Type recieved successfully',
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
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

    public function editAjax(Type $type) {

        $success = [
            'success' => 'Type recieved successfully',
            'typeId' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
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
        //
    }


    public function updateAjax(Request $request, Type $type) {
        $input = [            // redaguojami input
            'typeTitle' => $request->typeTitle,
            'typeDescription' => $request->typeDescription,
        ];

        $rules = [                //taisykles/ reikalavimai validacijai
            'typeTitle' => 'required|min:3|max:20',
            'typeDescription' => 'min:15',
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $type->title = $request->typeTitle;
            $type->description = $request->typeDescription;

            $type->save();

            $success = [
                'success' => 'Type update successfully',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //
    }

    // Sukuriama nauja funkcija- pasirenkami ir istrinami Type
    public function destroySelected(Request $request) {

        $checkedTypes = $request->checkedTypes;

        $messages = array();

        $errorsuccess = array();

        // Perduodami Type ID reiksmes

        foreach($checkedTypes as $typeId) {
            // surandamas Type ID
            $type = Type::find($typeId);
            $articles_count = $type->typeArticles->count();

            $deleteAction = $type->delete();

                if($deleteAction) {
                    $errorsuccess[] = 'success';
                    $messages[] = "Type ".$typeId." deleted successfully"; // parodoma koks Type istrintas
                } else {
                    $messages[] = "Something went wrong";
                    $errorsuccess[] = 'danger';
                }
        }

        $success = [
            'success' => $checkedTypes,
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }
}







