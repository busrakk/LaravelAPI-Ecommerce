<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\CategoryRepository;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $response = $this->categoryRepository->getAll();

        if($response){
            return response()->json([
                'data' => $response,
                'success' => true,
                'status' => 'success'
            ]);
        }
    }

  
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'bail|required|max:191',
            'name' => 'bail|required|max:191',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
                'status' => 'validation-error'
            ]);
        }

        $data = [
            'slug' => $request->slug,
            'name' => $request->name,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'status' => Category::STATUS_ACTIVE
        ];

        try{
            $response = $this->categoryRepository->insert($data);

            if($response){
                return response()->json([
                    'success' => true,
                    'message' => 'Category Insert Successfuly',
                    'status' => 'success'
                ]);
            }
        }catch(\Throwable $th){
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => false
            ]); 
        }

    }


    public function show($id)
    {
        //
    }

  
    public function edit($id)
    {
        //
    }

 
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'bail|required|max:191',
            'name' => 'bail|required|max:191',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
                'status' => 'validation-error'
            ]);
        }

        $data = [
            'slug' => $request->slug,
            'name' => $request->name,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'status' => $request->status == true ? '1':'0'
        ];

        try {
            $response = $this->categoryRepository->update($id, $data);

            if($response){
                return response()->json([
                    'success' => true,
                    'message' => 'Category Update Successfuly',
                    'status' => 'success'
                ]);
            }
        }catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => false
            ]);
        }
    }

  
    public function destroy($id)
    {
        try {
            $response = $this->categoryRepository->delete($id);
            if($response){
                return response()->json([
                    'success' => true,
                    'message' => 'Category Deleted Successfuly',
                    'status' => 'success'
                ]);
            }
        }catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => false
            ]);
        }
    }

    public function find($id)
    {
        $response = $this->categoryRepository->getById($id);

        if($response)
        {
            return response()->json([
                'data' => $response,
                'success' => true,
                'status' => 'success'
            ]);
        }
    }
}
