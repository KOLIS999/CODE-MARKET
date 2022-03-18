<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use function GuzzleHttp\Promise\all;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'massage'   => 'Get Cetegory Order By Desc',
            'data'      => Category::orderBy('time', 'DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_category' => ['required', 'min:4', 'unique:categories']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $post = Category::create($request->all());
            return response()->json([
                'massage'   => 'Created Succsessfully',
                'data'      => $post
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'massage'   => 'Created Filed' . $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Category::findOrFail($id);
        return response()->json([
            'massage'   => 'Show get by id',
            'data'      => $show
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $show = Category::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name_category' => ['required', 'min:4'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $put = $show->update($request->all());
            return response()->json([
                'massage'   => ' Updeted Successfully',
                'data'      => $put
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'massage'   => 'Updated Filed' . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $show = Category::findOrFail($id);
        try {
            $delete = $show->delete();
            return response()->json([
                'massage'   => 'Deleted Successsfully',
                'data'      => $delete
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'massage'   => 'Deleted filed' . $e->errorInfo
            ]);
        }
    }
}
