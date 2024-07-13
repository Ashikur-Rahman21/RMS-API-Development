<?php

namespace App\Http\Controllers\Api;

use App\Models\WaitingList;
use App\Trait\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WaitingListRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Api\WaitingListResource;
use App\Http\Resources\Api\WaitingListCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WaitingListController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waitingList = WaitingList::all();
        return WaitingListCollection::make($waitingList);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(WaitingListRequest $request)
    {
        $waitingList = WaitingList::create($request->validated());
        return WaitingListResource::make($waitingList);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $waitingListId)
    {
        try {
            return WaitingListResource::make(WaitingList::findOrFail($waitingListId));
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: "Waiting list cannot be found.", statusCode: Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(WaitingListRequest $request, $waitingListId)
    {
        try {
            $waitingList = WaitingList::findOrFail($waitingListId);
            $waitingList->update($request->validated());

            return new WaitingListResource($waitingList);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: "Waiting list cannot be found.", statusCode: Response::HTTP_NOT_FOUND);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $waitingListId)
    {
        try {
            $waitingList = WaitingList::findOrFail($waitingListId);
            $waitingList->delete();

            return $this->success(message: "Waiting list successfully deleted.", statusCode: Response::HTTP_OK);
        } catch(ModelNotFoundException $exception){
            return $this->error(message: "Waiting list cannot be found.", statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
