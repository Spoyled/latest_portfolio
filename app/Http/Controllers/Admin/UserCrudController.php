<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD; // Correct facade import
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::setFromDb(); // Automatically set columns based on the database
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
        ]);
    
        CRUD::addField(['name' => 'name', 'type' => 'text']);
        CRUD::addField(['name' => 'email', 'type' => 'email']);
    
        // Call your custom method:
        $this->setupSaveOperation();
    }
    
    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'name'     => 'required|min:2',
            'email'    => 'required|email|unique:users,email,' . $this->crud->getCurrentEntryId(),
        ]);
    
        CRUD::addField(['name' => 'name', 'type' => 'text']);
        CRUD::addField(['name' => 'email', 'type' => 'email']);
    
        // Call your custom method:
        $this->setupSaveOperation();
    }
    




    protected function setupSaveOperation()
    {
    }



}
