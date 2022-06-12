<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;

use App\Image;

use App\Status;

use App\Info;

class PagesController extends Controller
{
    public function registration() {
        return view('page_register');
    }

    public function login() {
        /*
        if( Auth::check() ) {
            return redirect('users');
        }
        */
        
        return view('page_login');
    }

    public function index() { 
        
        $users = User::all();

        $current_user = Auth::user();
    
        return view('users', ['users' => $users, 'current_user' => $current_user]);
    }

    public function security($id) {
        $cur_user = Auth::user();

        if( $cur_user->id != $id ){
            return back()
                    ->with('error', 'Можно изменять только свои данные');
        }
        
        $user = User::where('id', $id)->first();
        
        return view('security', ['user' => $user]);
    }

    public function profile($id) {
        $cur_user = Auth::user();

        if( $cur_user->id != $id ){
            return back()
                    ->with('error', 'Можно изменять только свои данные');
        }
        
        $user = User::where('id', $id)->first();
    
        return view('page_profile', ['user' => $user]);
    }

    public function media($id) {
        $cur_user = Auth::user();

        if( $cur_user->id != $id ){
            return back()
                    ->with('error', 'Можно изменять только свои данные');
        }
        
        $user = User::where('id', $id)->first();
    
        return view('media', ['user' => $user]);
    }

    public function status($id) {
        $user = Auth::user();

        if( $user->id != $id ){
            return back()
                    ->with('error', 'Можно изменять только свои данные');
        }
    
        $status = Status::where('user_id', $id)->first();
    
        return view('status', ['status' => $status]);
    }

    public function create() {
        
        
        return view('create_user');
    }

    public function edit($id) {
        $user = Auth::user();

        if( $user->id != $id ){
            return back()
                    ->with('error', 'Можно изменять только свои даные');
        }
        
        $info = Info::where('user_id', $id)->first();

        return view('edit', ['info' => $info]);
    }

    

}
