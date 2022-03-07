<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::post('/get_roles', 'Auth\RoleController@getAll')->name('roles');
Route::post('/get_parent_employee', 'Auth\EmployeeController@getParent')->name('parent_employee');
Route::post('/getEmployee', 'Auth\EmployeeController@getEmployee')->name('getEmployee');
Route::post('/loc', 'Auth\RegisterController@getLocation')->name('getLocation');

Route::middleware('auth:employee')->group(function () {
    Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

    Route::name('attendance.')->prefix('attendance')->group(function () {
        // Route::post('/table', 'Employee\Master\ProjectController@dataTable')->name('table');
        Route::post('/condition/update', 'Auth\AttendanceController@c19')->name('c19')->middleware('permission:read-attendance');
        Route::get('/condition/show/{id}', 'Auth\AttendanceController@c19Show')->name('c19Show')->middleware('permission:read-attendance');
        Route::get('/export/{date}', 'Auth\AttendanceController@exportExcel')->name('export')->middleware('permission:report-attendance');
        Route::get('/export-new/{date}', 'Auth\AttendanceController@exportNew')->name('exportNew')->middleware('permission:report-attendance');
        Route::get('/export-new2/{date}', 'Auth\AttendanceController@exportNew2')->name('exportNew')->middleware('permission:report-attendance');
        Route::get('/exportEmp/{date}', 'Auth\AttendanceController@exportEmployee')->name('exportEmp')->middleware('permission:read-emp-attendance');
        Route::get('/report', 'Auth\AttendanceController@report')->name('report')->middleware('permission:report-attendance');
        Route::resource('', 'Auth\AttendanceController', ['parameters' => ['' => 'id']])->middleware('permission:read-attendance');
        Route::post('/dataTables', 'Auth\AttendanceController@dataTables')->name('dataTables')->middleware('permission:read-attendance');
        Route::post('/store', 'Auth\AttendanceController@store')->name('store')->middleware('permission:read-attendance');
        Route::get('/employee', 'Auth\AttendanceController@show')->name('employee')->middleware('permission:read-emp-attendance');
        Route::post('/table_emp', 'Auth\AttendanceController@table_emp')->name('table_emp')->middleware('permission:read-emp-attendance');
        Route::get('/location/{location}', 'Auth\AttendanceController@location')->name('location')->middleware('permission:read-attendance');
        Route::post('/getLocation', 'Auth\AttendanceController@getLocation')->name('getLocation')->middleware('permission:read-attendance');

        Route::name('task.')->prefix('task')->group(function () {
            Route::get('/form/{id_attendance}', 'Auth\AttendanceTaskController@form')->name('form')->middleware('permission:create-daily-task');
            Route::get('/showTask/{id}', 'Auth\AttendanceTaskController@showTask')->name('showTask')->middleware('permission:read-emp-attendance');
            Route::put('/store', 'Auth\AttendanceTaskController@store')->name('store')->middleware('permission:create-daily-task');
            Route::get('/show/{id}', 'Auth\AttendanceTaskController@show')->name('show')->middleware('permission:create-daily-task');
        });
    });

    Route::name('config.')->prefix('config')->group(function () {
        Route::name('permission.')->prefix('permission')->group(function () {
            Route::get('/', 'Auth\PermissionController@index')->name('index')->middleware('permission:read-permissions');
            Route::get('/list', 'Auth\PermissionController@list')->name('list')->middleware('permission:read-permissions');
            Route::get('/create', 'Auth\PermissionController@create')->name('create');
            Route::post('/store', 'Auth\PermissionController@store')->name('store');
            Route::get('/edit/{permission_id}', 'Auth\PermissionController@edit')->name('edit');
            Route::post('/update/{permission_id}', 'Auth\PermissionController@update')->name('update');
            Route::delete('/remove/{permission_id}', 'Auth\PermissionController@remove')->name('remove');
        });

        Route::name('role.')->prefix('role')->group(function () {
            Route::get('/', 'Auth\RoleController@index')->name('index')->middleware('permission:read-roles');
            Route::get('/list', 'Auth\RoleController@list')->name('list')->middleware('permission:read-roles');
            Route::get('/create', 'Auth\RoleController@create')->name('create');
            Route::post('/store', 'Auth\RoleController@store')->name('store');
            Route::post('/update', 'Auth\RoleController@update')->name('update');
            Route::get('/edit/{role_id}', 'Auth\RoleController@edit')->name('edit');
            Route::delete('/remove/{role_id}', 'Auth\RoleController@remove')->name('remove');
        });

        Route::name('menu.')->prefix('menu')->group(function () {
            Route::get('/', 'Auth\MenuController@index')->name('index')->middleware('permission:read-menu');
            Route::get('/create', 'Auth\MenuController@create')->name('create');
            Route::get('/list', 'Auth\MenuController@list')->name('list')->middleware('permission:read-menu');
            Route::post('/store', 'Auth\MenuController@store')->name('store');
            Route::post('/update', 'Auth\MenuController@update')->name('update');
            Route::get('/edit/{acc_id}', 'Auth\MenuController@edit')->name('edit');
            Route::delete('/remove/{acc_id}', 'Auth\MenuController@remove')->name('remove');
        });

        Route::name('department.')->prefix('department')->group(function () {
            Route::get('/', 'Auth\DepartmentController@index')->name('index')->middleware('permission:read-deaprtement');
            Route::get('/create', 'Auth\DepartmentController@create')->name('create');
            Route::get('/dataTables', 'Auth\DepartmentController@dataTables')->name('dataTables')->middleware('permission:read-deaprtement');
            Route::post('/store', 'Auth\DepartmentController@store')->name('store');
            Route::post('/update/{code}', 'Auth\DepartmentController@update')->name('update');
            Route::get('/edit/{code}', 'Auth\DepartmentController@edit')->name('edit');
            Route::delete('/destroy/{code}', 'Auth\DepartmentController@destroy')->name('destroy');
        });
    });

    Route::name('adm.')->prefix('adm')->group(function () {
        //Letter
        Route::name('letter.')->prefix('letter')->group(function () {
            Route::get('/{tab_id}', 'Administrator\LetterController@index')->name('index')->middleware('permission:read-req-letter');
        });
        Route::post('/listLetter_PKS', 'Administrator\LetterController@listLetter_PKS')->name('listLetter_PKS')->middleware('permission:read-req-letter');
        Route::post('/listLetter_IM', 'Administrator\LetterController@listLetter_IM')->name('listLetter_IM')->middleware('permission:read-req-letter');
        Route::post('/listLetter_mrkt', 'Administrator\LetterController@listLetter_mrkt')->name('listLetter_mrkt')->middleware('permission:read-req-letter');
        Route::post('/listLetter_it', 'Administrator\LetterController@listLetter_it')->name('listLetter_it')->middleware('permission:read-req-letter');
        Route::post('/listLetter_hr', 'Administrator\LetterController@listLetter_hr')->name('listLetter_hr')->middleware('permission:read-req-letter');
        Route::post('/listLetter_sales', 'Administrator\LetterController@listLetter_sales')->name('listLetter_sales')->middleware('permission:read-req-letter');
        Route::post('/listLetter_out', 'Administrator\LetterController@listLetter_out')->name('listLetter_out')->middleware('permission:read-req-letter');
        Route::post('/listLetter_in', 'Administrator\LetterController@listLetter_in')->name('listLetter_in')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_PKS', 'Administrator\LetterController@MylistLetter_PKS')->name('MylistLetter_PKS')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_IM', 'Administrator\LetterController@MylistLetter_IM')->name('MylistLetter_IM')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_mrkt', 'Administrator\LetterController@MylistLetter_mrkt')->name('MylistLetter_mrkt')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_it', 'Administrator\LetterController@MylistLetter_it')->name('MylistLetter_it')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_hr', 'Administrator\LetterController@MylistLetter_hr')->name('MylistLetter_hr')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_sales', 'Administrator\LetterController@MylistLetter_sales')->name('MylistLetter_sales')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_out', 'Administrator\LetterController@MylistLetter_out')->name('MylistLetter_out')->middleware('permission:read-req-letter');
        Route::post('/MylistLetter_in', 'Administrator\LetterController@MylistLetter_in')->name('MylistLetter_in')->middleware('permission:read-req-letter');
        Route::get('/create_pks', 'Administrator\LetterController@create_pks')->name('create_pks');
        Route::get('/create_ops', 'Administrator\LetterController@create_ops')->name('create_ops');
        Route::get('/create_mrkt', 'Administrator\LetterController@create_mrkt')->name('create_mrkt');
        Route::get('/create_it', 'Administrator\LetterController@create_it')->name('create_it');
        Route::get('/create_hr', 'Administrator\LetterController@create_hr')->name('create_hr');
        Route::get('/create_sales', 'Administrator\LetterController@create_sales')->name('create_sales');
        Route::get('/create_out', 'Administrator\LetterController@create_out')->name('create_out');
        Route::get('/create_in', 'Administrator\LetterController@create_in')->name('create_in');
        Route::post('/store_pks', 'Administrator\LetterController@store_pks')->name('store_pks');
        Route::post('/store_ops', 'Administrator\LetterController@store_ops')->name('store_ops');
        Route::post('/store_mrkt', 'Administrator\LetterController@store_mrkt')->name('store_mrkt');
        Route::post('/store_it', 'Administrator\LetterController@store_it')->name('store_it');
        Route::post('/store_hr', 'Administrator\LetterController@store_hr')->name('store_hr');
        Route::post('/store_sales', 'Administrator\LetterController@store_sales')->name('store_sales');
        Route::post('/store_out', 'Administrator\LetterController@store_out')->name('store_out');
        Route::post('/store_in', 'Administrator\LetterController@store_in')->name('store_in');
        Route::get('/upload_PKS/{id}', 'Administrator\LetterController@upload_PKS')->name('upload_PKS');
        Route::post('/upload_PKS/proses', 'Administrator\LetterController@store_upload_PKS')->name('store_upload_PKS');
        Route::get('/downloadPKS/{file_name}', 'Administrator\LetterController@downloadPKS')->name('downloadPKS');
        Route::get('/upload_OPS/{id}', 'Administrator\LetterController@upload_OPS')->name('upload_OPS');
        Route::post('/upload_OPS/proses', 'Administrator\LetterController@store_upload_OPS')->name('store_upload_OPS');
        Route::get('/downloadOPS/{file_name}', 'Administrator\LetterController@downloadOPS')->name('downloadOPS');
        Route::get('/upload_mrkt/{id}', 'Administrator\LetterController@upload_mrkt')->name('upload_mrkt');
        Route::post('/upload_mrkt/proses', 'Administrator\LetterController@store_upload_mrkt')->name('store_upload_mrkt');
        Route::get('/download_mrkt/{file_name}', 'Administrator\LetterController@download_mrkt')->name('download_mrkt');
        Route::get('/upload_sales/{id}', 'Administrator\LetterController@upload_sales')->name('upload_sales');
        Route::post('/upload_sales/proses', 'Administrator\LetterController@store_upload_sales')->name('store_upload_sales');
        Route::get('/download_sales/{file_name}', 'Administrator\LetterController@download_sales')->name('download_sales');
        Route::get('/upload_out/{id}', 'Administrator\LetterController@upload_out')->name('upload_out');
        Route::post('/upload_out/proses', 'Administrator\LetterController@store_upload_out')->name('store_upload_out');
        Route::get('/download_out/{file_name}', 'Administrator\LetterController@download_out')->name('download_out');
        Route::get('/upload_in/{id}', 'Administrator\LetterController@upload_in')->name('upload_in');
        Route::post('/upload_in/proses', 'Administrator\LetterController@store_upload_in')->name('store_upload_in');
        Route::get('/download_in/{file_name}', 'Administrator\LetterController@download_in')->name('download_in');
    });

    Route::name('mst.')->prefix('mst')->namespace('Mst')->group(function () {
        Route::name('vehicle.')->prefix('vehicle')->group(function () {
            Route::get('/', 'MstVehicleController@index')->name('index')->middleware('permission:read-mst-vehicle');
            Route::get('/create', 'MstVehicleController@create')->name('create');
            Route::post('/get-driver', 'MstVehicleController@getDriver')->name('get.driver');
            Route::get('/list', 'MstVehicleController@list')->name('list')->middleware('permission:read-mst-vehicle');
            Route::get('/list-driver', 'MstVehicleController@listDriver')->name('listDriver');
            Route::post('/store', 'MstVehicleController@store')->name('store');
            Route::post('/update', 'MstVehicleController@update')->name('update');
            Route::get('/edit/{id}', 'MstVehicleController@edit')->name('edit');
            Route::delete('/remove/{id}', 'MstVehicleController@remove')->name('remove');
            Route::get('/edit-driver/{id}', 'MstVehicleController@editDriver')->name('editDriver');
            Route::post('/update-driver', 'MstVehicleController@updateDriver')->name('updateDriver');
            Route::delete('/remove-driver/{id}', 'MstVehicleController@removeDriver')->name('removeDriver');
        });

        Route::name('employee.')->prefix('employee')->group(function () {
            Route::get('/', 'EmployeesController@index')->name('index');
            Route::get('/dataTables', 'EmployeesController@dataTables')->name('dataTables');
            // Route::resource('', 'EmployeesController', ['parameters' => ['' => 'id']]);
            Route::get('/show/{id}', 'EmployeesController@show')->name('show');
            Route::get('/create', 'EmployeesController@create')->name('create');
            Route::post('/update', 'EmployeesController@update')->name('update');
            // Route::post('/store', 'EmployeesController@store')->name('store');
            // Route::get('/edit/{acc_id}', 'MenuController@edit')->name('edit');
            // Route::delete('/remove/{acc_id}', 'EmployeesController@remove')->name('remove');
        });
        Route::name('shift.')->prefix('shift')->group(function () {
            Route::get('/', 'ShiftController@index')->name('index');
            Route::get('/create', 'ShiftController@create')->name('create');
            Route::post('/store', 'ShiftController@store')->name('store');
            Route::get('/dataTables', 'ShiftController@dataTables')->name('dataTables');
            Route::get('/set', 'ShiftController@set')->name('set');
            Route::post('/dateRange', 'ShiftController@dateRange')->name('dateRange');
            Route::post('/setShift', 'ShiftController@setShift')->name('setShift');
        });

        Route::name('holiday.')->prefix('holiday')->group(function () {
            Route::post('/search', 'MstHolidayController@search')->name('search');
            Route::resource('', 'MstHolidayController', ['parameters' => ['' => 'id']]);
        });
    });

    //General Affair
    Route::name('ga.')->prefix('ga')->namespace('Ga')->group(function () {
        //Vehicle
        Route::name('vehicle.')->prefix('vehicle')->group(function () {
            Route::get('/home/{tab_id}', 'VehicleController@home')->name('home')->middleware('permission:read-req-vehicle');
            Route::post('/list', 'VehicleController@list')->name('list')->middleware('permission:read-req-vehicle');
            Route::post('/list-driver', 'VehicleController@listDriver')->name('listDriver')->middleware('permission:read-req-vehicle');
            Route::post('/get-driver', 'VehicleController@getDriver')->name('get.driver');
            Route::get('/list/admin', 'VehicleController@listAdmin')->name('list.admin');
            Route::get('/create', 'VehicleController@create')->name('create')->middleware('permission:create-req-vehicle');
            Route::post('/store', 'VehicleController@store')->name('store');
            Route::get('/edit/{id}', 'VehicleController@edit')->name('edit');
            Route::post('/update', 'VehicleController@update')->name('update');
            Route::get('/set-schedule/{id}', 'VehicleController@setSchedule')->name('set.schedule');
            Route::post('/store-schedule', 'VehicleController@storeSchedule')->name('store.schedule');
            Route::delete('/remove/{id}', 'VehicleController@remove')->name('remove');
        });

        Route::name('room.')->prefix('room')->group(function () {
            Route::post('/search', 'ReqRoomController@search')->name('search');
            Route::post('/getArea', 'ReqRoomController@getArea')->name('getArea');
            Route::post('/getBuilding', 'ReqRoomController@getBuilding')->name('getBuilding');
            Route::post('/getCapacity', 'ReqRoomController@getCapacity')->name('getCapacity');
            Route::resource('', 'ReqRoomController', ['parameters' => ['' => 'id']]);
        });
        Route::name('asset.')->prefix('asset')->group(function () {
            Route::resource('', 'AssetController', ['parameters' => ['' => 'id']]);
            Route::post('/dataTables', 'AssetController@dataTables')->name('dataTables');
            Route::post('/uploadImgAsset', 'AssetController@storeUploadImage')->name('uploadImageAsset');
            Route::post('/getmodeltype', 'AssetController@getTypeModel')->name('getmodeltype');
            Route::get('/checkout/{id}', 'AssetController@checkout')->name('checkout');
            Route::put('/checkout/store', 'AssetController@checkoutstore')->name('checkout.store');
            Route::get('/checkin/{id}', 'AssetController@checkin')->name('checkin');
            Route::put('/checkin/store', 'AssetController@checkinStore')->name('checkin.store');
            Route::get('/department/create', 'AssetController@createDepartment')->name('department.create');
            Route::put('/department/store', 'AssetController@storeDepartment')->name('department.store');
            Route::get('/user/create', 'AssetController@createUserAsset')->name('user.create');
            Route::put('/user/store', 'AssetController@storeUserAsset')->name('user.store');
            Route::post('/getUserDepartment', 'AssetController@getUserDepartment')->name('getuserdepartment');
            Route::post('/getDepartmentByUser', 'AssetController@getDepartmentByUser')->name('getdepartmentbyuser');
        });

    });

    //Permission
    Route::name('permission.')->prefix('permission')->namespace('Permission')->group(function () {
        //Vehicle
        Route::name('regular.')->prefix('regular')->group(function () {
            Route::get('/', 'RegularController@index')->name('index');
            Route::post('/dataTables', 'RegularController@dataTables')->name('dataTables');
            Route::get('/create', 'RegularController@create')->name('create');
            Route::post('/store', 'RegularController@store')->name('store');
            Route::post('/cancel', 'RegularController@cancel')->name('cancel');
            Route::get('/approve/{id}', 'RegularController@approve')->name('approve');
            Route::post('/reject', 'RegularController@reject')->name('reject');
            Route::get('/show/{id}', 'RegularController@show')->name('show');
            Route::get('/notes/{type}/{id}', 'RegularController@notes')->name('notes');
        });
        Route::name('sick.')->prefix('sick')->group(function () {
            Route::get('/', 'SickController@index')->name('index');
            Route::post('/dataTables', 'SickController@dataTables')->name('dataTables');
            Route::get('/create', 'SickController@create')->name('create');
            Route::post('/store', 'SickController@store')->name('store');
            Route::post('/cancel', 'SickController@cancel')->name('cancel');
            Route::get('/approve/{id}', 'SickController@approve')->name('approve');
            Route::post('/reject', 'SickController@reject')->name('reject');
            Route::get('/show/{id}', 'SickController@show')->name('show');
            Route::get('/notes/{type}/{id}', 'SickController@notes')->name('notes');
            Route::get('/downloadFile/{name}', 'SickController@downloadFile')->name('downloadFile');
        });
        Route::name('leave.')->prefix('leave')->group(function () {
            Route::get('/', 'LeaveController@index')->name('index');
            Route::post('/dataTables', 'LeaveController@dataTables')->name('dataTables');
            Route::get('/create', 'LeaveController@create')->name('create');
            Route::post('/store', 'LeaveController@store')->name('store');
            Route::post('/cancel', 'LeaveController@cancel')->name('cancel');
            Route::get('/approve/{id}', 'LeaveController@approve')->name('approve');
            Route::post('/reject', 'LeaveController@reject')->name('reject');
            Route::get('/show/{id}', 'LeaveController@show')->name('show');
            Route::get('/notes/{type}/{id}', 'LeaveController@notes')->name('notes');
            Route::get('/downloadFile/{name}', 'LeaveController@downloadFile')->name('downloadFile');

            Route::get('/special', 'LeaveController@special')->name('special');
            Route::post('/dataTablesSpecial', 'LeaveController@dataTableSpecial')->name('dataTableSpecial');
            Route::post('/storeSpecial', 'LeaveController@storeSpecial')->name('storeSpecial');
        });
    });

    //ER
    Route::name('er.')->prefix('er')->namespace('Er')->group(function () {
        //Vehicle
        Route::name('jo.')->prefix('jo')->group(function () {
            Route::name('employeement.')->prefix('employeement')->group(function () {
                Route::get('/', 'JoEmployeementController@index')->name('index');
                Route::post('/list', 'JoEmployeementController@list')->name('list');
                Route::post('/list/confirm', 'JoEmployeementController@listConfirmation')->name('listConfirmation');
                Route::post('/confirm', 'JoEmployeementController@confirmation')->name('confirmation');
                Route::post('/store', 'JoEmployeementController@store')->name('store');
                Route::get('/employeed', 'JoEmployeementController@employeed')->name('employeed');
                Route::post('/employeed/list', 'JoEmployeementController@employeedList')->name('employeedList');
                Route::get('/edit/{id}', 'JoEmployeementController@edit')->name('edit');
                Route::post('/update', 'JoEmployeementController@update')->name('update');
                Route::get('/release/{id}', 'JoEmployeementController@release')->name('release');
                Route::post('/release', 'JoEmployeementController@releaseProcess')->name('releaseProcess');
                Route::get('/cancel/{id}', 'JoEmployeementController@cancel')->name('cancel');
                Route::get('/reset-password/{id}', 'JoEmployeementController@resetPassword')->name('resetPassword');
            });
        });
        Route::name('project.')->prefix('project')->group(function () {
            Route::post('/department', 'ProjectReportController@getDepartment')->name('department');
            Route::post('/roles', 'ProjectShiftController@getRoles')->name('roles');
            Route::post('/employees', 'ProjectShiftController@getEmployees')->name('employees');

            Route::name('shift.')->prefix('shift')->group(function () {
                Route::get('/', 'ProjectShiftController@index')->name('index');
                Route::post('/dataTable', 'ProjectShiftController@dataTable')->name('dataTable');
                Route::get('/create', 'ProjectShiftController@create')->name('create');
                Route::post('/store', 'ProjectShiftController@store')->name('store');
                Route::get('/set', 'ProjectShiftController@set')->name('set');
                Route::post('/dateRange', 'ProjectShiftController@dateRange')->name('dateRange');
                Route::post('/setShift', 'ProjectShiftController@setShift')->name('setShift');
                Route::get('/location', 'ProjectShiftController@location')->name('location');
                Route::post('/dataTable_loc', 'ProjectShiftController@dataTable_loc')->name('dataTable_loc');
                Route::get('/create_location', 'ProjectShiftController@create_location')->name('create_location');
                Route::post('/store_loc', 'ProjectShiftController@store_loc')->name('store_loc');
                Route::get('/edit_loc/{id}', 'ProjectShiftController@edit_loc')->name('edit_loc');
                Route::post('/update_loc', 'ProjectShiftController@update_loc')->name('update_loc');
            });
            Route::name('roles.')->prefix('roles')->group(function () {
                Route::get('/list', 'ProjectRoleController@list')->name('list');
                Route::get('/dataTables', 'ProjectRoleController@dataTables')->name('dataTables');
                Route::post('/update', 'ProjectRoleController@update')->name('update');
                Route::get('/edit/{role_id}', 'ProjectRoleController@edit')->name('edit');
            });
            Route::name('report.')->prefix('report')->group(function () {
                Route::get('/attendances', 'ProjectReportController@attendances')->name('attendances');
                Route::get('/export-attendances/{company}/{project}/{date}', 'ProjectReportController@exportAttendances')->name('exportAttendances');
            });
            Route::name('permission.')->prefix('permission')->group(function () {
                Route::get('/', 'ProjectPermissionController@index')->name('index');
                Route::post('/empPermission', 'ProjectPermissionController@empPermission')->name('empPermission');
                Route::post('/empUpdate', 'ProjectPermissionController@empUpdate')->name('empUpdate');
            });
            Route::name('attendance.')->prefix('attendance')->group(function () {
                Route::get('/', 'ProjectAttendanceController@index')->name('index');
                Route::post('/getProject', 'ProjectAttendanceController@getProject')->name('getProject');
                Route::post('/dataTable', 'ProjectAttendanceController@dataTable')->name('dataTable');
                Route::get('/exportEmp/{date}/{project_code}', 'ProjectAttendanceController@exportEmployee')->name('exportEmp');
            });
            Route::name('group.')->prefix('group')->group(function () {
                Route::resource('', 'ProjectGroupingController', ['parameters' => ['' => 'id']]);
                Route::post('/datatables', 'ProjectGroupingController@datatables')->name('datatables');
                Route::post('/getData', 'ProjectGroupingController@getData')->name('getData');

            });
        });
    });

    //Payroll
    Route::name('payroll.')->prefix('payroll')->group(function () {
        Route::name('variables.')->prefix('variables')->group(function () {
                Route::post('/group/store', 'Payroll\VariablesController@storeGroup')->name('storeGroup');
                Route::get('/group', 'Payroll\VariablesController@group')->name('group');
                Route::post('/datatables', 'Payroll\VariablesController@datatables')->name('datatables');
                Route::resource('', 'Payroll\VariablesController', ['parameters' => ['' => 'id']]);
        });
        Route::name('project.')->prefix('project')->group(function () {
            Route::delete('/deletePayroll/{id}', 'Payroll\ProjectPayrollController@destroyPayroll')->name('destroyPayroll');
            Route::get('/spt/{id}', 'Payroll\ProjectPayrollController@spt')->name('spt');
            Route::post('/downloadPaylist', 'Payroll\ProjectPayrollController@downloadPaylist')->name('downloadPaylist');
            Route::post('/downloadPaylistEmployee', 'Payroll\ProjectPayrollController@downloadPaylistEmployee')->name('downloadPaylistEmployee');
            Route::post('/updateDate', 'Payroll\ProjectPayrollController@updateDate')->name('updateDate');
            Route::get('/pattern/{id}', 'Payroll\ProjectPayrollController@pattern')->name('pattern');
            Route::post('/storeAddVariable', 'Payroll\ProjectPayrollController@storeAddVariable')->name('storeAddVariable');
            Route::post('/useIt', 'Payroll\ProjectPayrollController@useIt')->name('useIt');
            Route::post('/recentUse', 'Payroll\ProjectPayrollController@recentUse')->name('recentUse');
            Route::get('/payslip/{id}', 'Payroll\ProjectPayrollController@payslip')->name('payslip');
            Route::get('/payroll/{id}', 'Payroll\ProjectPayrollController@payroll')->name('payroll');
            Route::delete('/destroyVariable/{id}', 'Payroll\ProjectPayrollController@destroyVariable')->name('destroyVariable');
            Route::post('/storeGrid', 'Payroll\ProjectPayrollController@storeGrid')->name('storeGrid');
            Route::post('/datatablesEmp', 'Payroll\ProjectPayrollController@datatablesEmp')->name('datatablesEmp');
            Route::post('/datatables', 'Payroll\ProjectPayrollController@datatables')->name('datatables');
            Route::resource('', 'Payroll\ProjectPayrollController', ['parameters' => ['' => 'id']]);
        });
    });
});

Route::get('/setup', function () {
    $now = date('Y-m-d H:i:s');
    $empl = new App\Models\Auth\Employee();
    $empl->email = 'hendro.purwadi@mitracomm.com';
    $empl->name = 'Hendro Purwadi';
    $empl->password = Illuminate\Support\Facades\Hash::make('12345678');
    $empl->created_at = $now;
    $empl->save();
    dump($empl);

    $bod = new \App\Models\Auth\Department();
    $bod->code = 'BOD';
    $bod->name = 'Board of Director';
    $bod->created_at = $now;
    $bod->save();
    dump($bod);

    $itpsm = new \App\Models\Auth\Department();
    $itpsm->code = 'ITPSM';
    $itpsm->parent_code = $bod->code;
    $itpsm->name = 'IT, PRESALES & ME';
    $itpsm->created_at = $now;
    $itpsm->save();
    dump($itpsm);

    $itdev = new \App\Models\Auth\Department();
    $itdev->code = 'ITDEV';
    $itdev->parent_code = $itpsm->code;
    $itdev->name = 'IT Development';
    $itdev->created_at = $now;
    $itdev->save();

    $ad = new \App\Models\Auth\Role();
    $ad->department_code = $bod->code;
    $ad->name = 'DIRECTOR';
    $ad->display_name = 'Associate Director';
    $ad->created_at = $now;
    $ad->save();
    dump($ad);

    $vp = new \App\Models\Auth\Role();
    $vp->department_code = $itpsm->code;
    $vp->parent_id = $ad->id;
    $vp->name = 'VP-ITPSM';
    $vp->display_name = 'VP - IT, PRESALES & ME';
    $vp->created_at = $now;
    $vp->save();
    dump($vp);

    $mgr = new \App\Models\Auth\Role();
    $mgr->department_code = $itdev->code;
    $mgr->parent_id = $vp->id;
    $mgr->name = 'ITDEV-MGR';
    $mgr->display_name = 'Manager - IT Development';
    $mgr->created_at = $now;
    $mgr->save();
    dump($mgr);

    $stf = new \App\Models\Auth\Role();
    $stf->department_code = $itdev->code;
    $stf->parent_id = $mgr->id;
    $stf->name = 'ITDEV-STF';
    $stf->display_name = 'Staff - IT Development';
    $stf->created_at = $now;
    $stf->save();
    dump($stf);

    $empl->attachRole($vp);
});

Route::get('/empl', function () {
    // dd(\Illuminate\Support\Facades\Auth::guard('employee')->user()->id());
});