<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use App\Models\Role;
//use App\Models\User;
use App\Models\Honestee\VueCodeGen\User;
//use App\Models\Honestee\VueCodeGen\Role;
use App\Models\Honestee\VueCodeGen\Code;

use Spatie\Permission\Models\Role;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Artisan;
use Request;
use DB;
use URL;
use Redirect;
//use Illuminate\Support\Facades\Route;


use Spatie\Multitenancy\Models\Tenant;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $fields = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if($data['role'] = "school-owner")
            $fields['website'] = 'unique:Spatie\Multitenancy\Models\Tenant,database';

        return Validator::make($data, $fields);
    }

    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $roleName = $data['role'];
        if($roleName == 'school-owner')
            $this->createTenantInfo($data['website']);
        else{
            $code = Code::where("value", $data['pincode'])->first();
            $this->exitForInvalidCode($code);

            $roleName = $code->user_type;

        }
            //$roleName == Code::where("value", $data['pincode'])->get("user_type")->pluck("user_type")[0];
            $code = Code::where("value", $data['pincode'])->first();

            exit(json_encode(Code::where("value", $data['pincode'])->first()->user_type ));

        if($roleName == "user")
            exit("<h4>Invalid registration code!</h4>");


        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_number' => $this->createUserNumber($roleName),
        ];

        $user = User::create($userData);

        // Assigning Role by default user role
        $role = Role::where('name', $roleName)->first();
        //$user->assignRole($role);
        $user->roles()->sync($role->id); // ok

        if( strpos($role, "student") > -1 || strpos($role, "prefect") > -1 || strpos($role, "class-head") > -1)
            $user->roles()->sync(  Role::where('name', "student")->first()->id );
        else if( strpos($role, "parent") )
            $user->roles()->sync(  Role::where('name', "parent")->first()->id );
        else
            $user->roles()->sync(  Role::where('name', "staff")->first()->id );


        //$userNumber = $this->createUserNumber('STA'); // For staff
        //$user->update(['user_number', $userNumber]);

        //redirect()->away("google.com");

       // exit(
           // "<a href='".Tenant::current()->domain.":8000'> www.".Tenant::current()->domain." </a>"
       // );
        
       //$this->redirectTo = "http://" .Tenant::current()->domain.":8000'";
        //generates a url for domain.app.url/login
       // URL::route('employee.login');

        //redirects to domain.app.url/login
        //return Redirect::route("http://" .Tenant::current()->domain.":8000");

        //Redirect::route('registration-finished', ['domain' => Tenant::current()->domain]);
        if($data['role'] == 'school-owner'){
            exit('
                <div class="alert alert-danger col-12 col-md-10 mx-md-5 my-md-5" role="alert">
                    <h2 class="alert-heading">Congratulations!</h2> <hr />
                    <ul style="font-size:1.2em">
                    <li> <div style="font-size:1.2em">To visit your website, type www.'.Tenant::current()->domain.' in the browser address bar. </div></li>
                    <li> <div style="font-size:1.2em"> To logon, type www.'.Tenant::current()->domain.'/login in the browser address bar. 
                        Then, enter the Email: '.$data['email'].'  and the Password that you used in this registration.</div></li>
                    </ul>
                    <br />
                </div>
            ');
        }

        // Assigning Role by default user role

        // $role = Role::where('name', 'User')->first();
        // $user->assignRole($role);

        return $user;
    }


    protected function exitForInvalidCode($code){

        if(!$code)
            exit("<h4>Invalid code!</h4>");

        $expiryDate = new \DateTime($code->expiry_date);
        
        if($code->use_for != "User registration")
            exit("<h4>This code is for ".$code->use_for.", not for registration!</h4>");
        else if(now() > $expiryDate )
            exit("<h4>This code has reached its expiring date: ".$expiryDate->format('Y-m-d H:i:s')."</h4>");
        else if( $code->number_of_use >= $code->maximum_use)
            exit("<h4>This code has reached its maximum use of ".$code->maximum_use." times!</h4>");

    }



    protected function createTenantInfo($dbName){

        $domain =  $dbName.".".Request::getHost();

        $tenant_id = Tenant::insert([
            'name' => $dbName,
            'domain' => $domain,
            'database' => $dbName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get the default connection name, and the database name for that connection from laravel config.
        $connectionName = config('database.default');
        $databaseName = config("database.connections.{$connectionName}.database");
        // Set the database name to null so DB commands connect to raw mysql, not a database.
        config(["database.connections.{$connectionName}.database" => null]);

        // Create the db if it doesn't exist.
        DB::statement("CREATE DATABASE IF NOT EXISTS " . $dbName);

        // Reset database name and purge database-less connection from cache.
        config(["database.connections.{$connectionName}.database" => $dbName ]);
        DB::purge();

        // Migrate
        Artisan::call("tenants:artisan 'migrate --seed' --tenant={$tenant_id}");
        
        //dd(Artisan::output());
        Tenant::latest()->first()->makeCurrent();

    }


    protected function createUserNumber($role) {
        $prefix = "STA";
        if($role == "student")
            $prefix = "STU";
        else if($role == "parent")
            $prefix = "PAR";

        $lastUserNumber = option('lastUserNumber', 0);
        $lastUserNumberYear = option('lastUserNumberYear', date('y'));
        if($lastUserNumberYear < date('y')) // New year. Reset lastUserNumber
            $lastUserNumber = 0;
        
        $lastUserNumber++; // Increase for new user number

        option(['lastUserNumber' => $lastUserNumber]);
        option(['lastUserNumberYear' => date('y')]);
    
        return $prefix.dechex(Tenant::current()->id)."-".str_pad($lastUserNumber, 3, '0', STR_PAD_LEFT);
    }

    




}
