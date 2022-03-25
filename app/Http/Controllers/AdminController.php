<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

use App\Pet;
use App\User;

class AdminController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function view_client()
    {
        $clients =  User::whereHas('roles', function ($query) {
            $query->where('name','=', 'client');
        })->get();

        return view('admin.clientrecord',compact('clients'));
    }
    
    public function create_client(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|max:255',
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required',
        ]);

        $validatedData['password'] = bcrypt('client2022');


        $user = User::create($validatedData);
        $user->assignRole('client');
        return back()->with('success','Client Added Successfully!');
    }

    public function delete_client(Request $request)
    {
        $find_user = User::where('id',$request->client_id)->first();
        if(!$find_user)
        {
            return back()->with('error','User Do Not Exist');
        }

        $find_user->delete();
        return back()->with('success','User Delete Successfully');
    }

    public function find_client(Request $request)
    {

       return response()->json(User::find($request->client_id)); 
    }

    public function update_client(Request $request)
    {
        $validatedData = $request->validate([
            'email'     => 'required|max:255',
            'name'      => 'required',
            'address'   => 'required',
            'contact'   => 'required',
            'client_id' => 'required'
        ]);

        $find_user = User::find($validatedData['client_id']);

        if( !$find_user )
        {
           return back()->with('error','User Not found '); 
        }

        unset($validatedData['client_id']);

        $find_user->update($validatedData);

        return back()->with('success','User Updated Successfully');

    }

    public function pet_record()
    {
        $pets = Pet::all();

        return view('admin.petrecord',compact('pets'));
    }

    public function create_pet(Request $request)
    {
        $validatedData = $request->validate([
            'name'              => 'required|max:255',
            'age'               => 'required',
            'gender'            => 'required',
            'breed'             => 'required',
            'appointment_date'  => 'required',
            'reason'            => 'required'
        ]);

        Pet::create($validatedData);
       
        return back()->with('success','Pet Added Successfully!');

    }

    public function delete_pet(Request $request)
    {
        $find_pet = Pet::where('id',$request->pet_id)->first();
        if(!$find_pet)
        {
            return back()->with('error','Pet Do Not Exist');
        }

        $find_pet->delete();
        return back()->with('success','Pet Delete Successfully');
    }

    public function find_pet(Request $request)
    {
        return response()->json(Pet::find($request->pet_id)); 
    }

    public function update_pet(Request $request)
    {
        $validatedData = $request->validate([
            'name'              => 'required|max:255',
            'age'               => 'required',
            'gender'            => 'required',
            'breed'             => 'required',
            'appointment_date'  => 'required',
            'reason'            => 'required',
            'pet_id'            => 'required'
        ]);

        $find_user = Pet::find($validatedData['pet_id']);

        if( !$find_user )
        {
           return back()->with('error','Pet Not found '); 
        }

        unset($validatedData['pet_id']);

        $find_user->update($validatedData);

        return back()->with('success','Pet Updated Successfully');
    }

    public function appointment()
    {
        return view('admin.appointment');
    }

    public function announcement()
    {
        return view('admin.announcement');
    }

    public function concern()
    {
        return view('admin.concern');
    }


}