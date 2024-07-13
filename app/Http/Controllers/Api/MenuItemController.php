<?php

namespace App\Http\Controllers\Api;

use App\Models\MenuItem;
use App\Trait\ApiResponse;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\MenuItemResource;
use App\Http\Resources\Api\MenuItemCollection;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\StoreMenuItemRequest;
use App\Http\Requests\Api\UpdateMenuItemRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MenuItemController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $all_menu_items = MenuItem::with('category')->get();
        return MenuItemCollection::make($all_menu_items);
    }


    public function store(StoreMenuItemRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $imageName = Str::uuid() . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('uploads', $imageName, 'public');
            $validated['images'] = $imageName;
        }

        $menu_item = MenuItem::create($validated);
        return MenuItemResource::make($menu_item);
    }


    public function show(string $menuItemId)
    {
        try {
            $menu_item = MenuItem::with('category')->findOrFail($menuItemId);
            return MenuItemResource::make($menu_item);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Menu Item cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        } 
    }
    public function update(UpdateMenuItemRequest $request, string $menuItemId)
    {
        try {
            $menu_item = MenuItem::findOrFail($menuItemId);

            $validated = $request->validated();       
            
            if ($request->hasFile('images')) {
                $image = $request->file('images');
                $imageName = Str::uuid() . '-' . time() . '.' . $image->getClientOriginalExtension();

                if(file_exists($menu_item->images)){
                    Storage::delete($menu_item->images);
                }

                $image->storeAs('uploads', $imageName, 'public');
                $validated['images'] = $imageName;
            }

            $menu_item->update($validated);
            return new MenuItemResource($menu_item);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Menu Item cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(string $menuItemId)
    {
        try {
            $menu_item = MenuItem::findOrFail($menuItemId);
            $menu_item->delete();
            return $this->success(message: 'Menu Item successfully deleted.', statusCode: Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'Menu Item cannot be found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
