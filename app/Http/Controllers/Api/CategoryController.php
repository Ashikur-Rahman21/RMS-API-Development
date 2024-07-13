<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Trait\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\CategoryCollection;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $all_categories = Category::all();
        return CategoryCollection::make($all_categories);
    }


    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create($validated);
        return CategoryResource::make($category);
    }


    public function show(string $categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            return CategoryResource::make($category);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Reservation cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
       
    }
    public function update(UpdateCategoryRequest $request, string $categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $validated = $request->validated();            
            $category->update($validated);
            return new CategoryResource($category);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Reservation cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(string $categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $category->delete();
            return $this->success(message: 'Reservation successfully deleted.', statusCode: Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Reservation cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
