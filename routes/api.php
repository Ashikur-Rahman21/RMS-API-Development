<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Api\OrderStatusController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\SalesReportController;
use App\Http\Controllers\Api\WaitingListController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\Api\ReservationReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('api.login');
    Route::post('/register', 'register')->name('api.register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout')->name('api.logout');
    });
});

Route::post('/available-tables', [BookingController::class, 'availableTable']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    /*--------------------------------------
        User Route
    ----------------------------------------*/
    Route::apiResource('/users', UsersController::class)->except(['update']);
    Route::post('/users/{id}', [UsersController::class, 'update']);
    Route::get('/user/profile',[UsersController::class,'viewProfile']);
    Route::post('/user/profile/update', [UsersController::class, 'updateProfile']);
    // change password route
    Route::post('/user/password/check', [UsersController::class, 'checkOldPassword']);
    Route::post('/user/password/change', [UsersController::class, 'changePassword']);

    /*--------------------------------------
        Role permission Route 
    ----------------------------------------*/
    Route::controller(RolePermissionController::class)->group(function () {
        Route::get('/roles', 'index');
        Route::get('/permissions', 'permissions');
        Route::post('/role', 'storeRole');
        Route::get('/role/{role}', 'editRole');
        Route::post('/role/{id}', 'updateRole');
        Route::delete('/role/{id}', 'destroy');
    }); 

    /*--------------------------------------
        Order maangement Route
    ----------------------------------------*/
    Route::apiResource('/order', OrderController::class);
    Route::get('/invoice/generate/{id}', [OrderController::class, 'invoiceGenerate']);

    /*--------------------------------------
        Supplier Management Route
    ----------------------------------------*/
    Route::apiResource('/suppliers', SuppliersController::Class);
    Route::apiResource('/customers', CustomersController::Class);


    /*--------------------------------------
        Inventory Management Route
    ----------------------------------------*/
    Route::get('/inventory/index', [InventoryController::class, 'index']);
    Route::get('/inventory/alert', [InventoryController::class, 'alertInventory']);
    Route::get('/alert/inventory/list', [InventoryController::class, 'alertInventoryList']);
    Route::get('/inventory/report', [InventoryController::class, 'inventoryReport']);

    /*--------------------------------------
    Category Route
    ----------------------------------------*/
    Route::apiResource('/categories', CategoryController::class);

    /*--------------------------------------
        Menu Item Route
    ----------------------------------------*/
    Route::apiResource('/menu-items', MenuItemController::class);

    // TODO: @nasir recheck Validation issue And Exception Handling
    Route::apiResource('/order', OrderController::class);
    Route::get('/invoice/generate/{id}', [OrderController::class, 'invoiceGenerate']);

    /*--------------------------------------
        Reservations Route
    ----------------------------------------*/
    Route::apiResource('/reservations', ReservationController::class);
    Route::apiResource('/reservation/waiting', WaitingListController::class);

    Route::post('/booking', [BookingController::class, 'store']);
    Route::apiResource('/tables', TableController::class);

    Route::get('/orders/{id}/status', [OrderStatusController::class, 'getOrderStatus']);        
    Route::patch('/orders/{id}/status', [OrderStatusController::class, 'updateStatus'])->middleware('CheckUserType:admin,employee');

    Route::get('/sales-report', [SalesReportController::class, 'salesReport']);
    Route::get('/reservations-report', [ReservationReportController::class, 'reservationReport']);
});
