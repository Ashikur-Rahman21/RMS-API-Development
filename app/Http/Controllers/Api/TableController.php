<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Table;
use App\Trait\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TableRequest;
use App\Http\Resources\Api\TableResource;
use App\Http\Resources\Api\TableCollection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TableController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        return TableCollection::make($tables);
    }
     
   
     
    public function  store(TableRequest $request)
    {
        $tables = Table::create($request->validated());
        $tableResource = TableResource::make($tables);

        return $this->success(
            message:"Table created successfully.", 
            data:$tableResource, 
            statusCode:Response::HTTP_CREATED
        );  
    }


    /**
     * Display the specified resource.
     */
    public function show(string $tableId)
    {
        try{
            return TableResource::make(Table::findOrFail($tableId));
        } catch(ModelNotFoundException $exception){
            return $this->error(
                message:"Table not found.",
                statusCode:Response::HTTP_NOT_FOUND
            );
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(TableRequest $request, string $tableId)
    {
       try{
            $tables = Table::findOrFail($tableId);
            $tables->update($request->validated());
            $tableResource = new TableResource($tables);

            return $this->success(
                message: "Table update successfully.",
                data: $tableResource,
                statusCode: Response::HTTP_CREATED
            );
       } catch (ModelNotFoundException $exception){
            return $this->error(
                message: "Table not found.",
                statusCode: Response::HTTP_NOT_FOUND
            );
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $tableId)
    {
        try
        { 
            $tables = Table::findOrFail($tableId);
            $tables->delete();
            return $this->success(
                message: "Table successfully delete.",
                statusCode: Response::HTTP_OK
            );
        } catch(Exception $e){
            return $this->error(
                message: "Table not found.",
                statusCode: Response::HTTP_NOT_FOUND
            );
        }
}
}

