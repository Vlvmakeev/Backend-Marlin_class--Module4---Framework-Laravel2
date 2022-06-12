<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Validator;

use Illuminate\Database\Eloquent\Builder;



use App\User;

use App\Info;

use App\Status;

use App\Image;

use App\Media;


class UserController extends Controller
{
    public function register(Request $request){
        $validateFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if( User::where('email', $validateFields['email'])->exists() ){
            return redirect('registration')
                            ->with('error', 'Пользователь с таким email уже существует');
        }
    
        $user = User::create($validateFields);
    
        if( $user ){
    
            Auth::login($user);
            return redirect('/')
                        ->with('success', 'Вы успешно зарегистрировались');
        }
    
        return redirect('/registration')
                        ->with('error', 'Регистрация не удалась');
    }

    public function login(Request $request) {
        if( Auth::check() ){
            return redirect('/users')
                                ->with('warning', 'Вы уже авторизированы');
        }
        
        $formFields = $request->only(['email', 'password']);
    
        if( Auth::attempt($formFields) ){
            return redirect('/users');
        }
    
        return redirect('/')
                            ->with('error', 'Авторизация не удалась');
    }

    public function logout() {
        if( Auth::check() ){
            Auth::logout();
    
            return redirect('/')
                        ->with('info', 'Вы вышли из системы');
        }
        return redirect('/')
                    ->with('error', 'Вы не авторизированы');
    }

    public function add(Request $request) {
        if( !Auth::check() ){
            return redirect('/')
                        ->with('error', 'Вам необходимо авторизоваться в системе');
        }

        $validateFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if( User::where('email', $validateFields['email'])->exists() ){
            return redirect('/registration')
                            ->with('error', 'Пользователь с таким email уже существует');
        }
    
        $user = User::create($validateFields);
        
        $user_id = $user->id;

        Info::create([
            'name' => $request->name,
            'job' => $request->job,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => $user_id
        ]);

        Status::create([
            'status' => $request->status,
            'user_id' => $user_id
        ]);

        $image = $request->file('image');

        Image::create([
            'image' => $image->store('uploads'),
            'user_id' => $user_id
        ]);

        Media::create([
            'vkontakte' => $request->vkontakte,
            'telegram' => $request->telegram,
            'instagram' => $request->instagram,
            'user_id' => $user_id
        ]);

        if( $user ){
    
        
            return redirect('/users')
                        ->with('success', 'Пользователь успешно добавлен');
        }
    
        return redirect('/registration')
                        ->with('error', 'Регистрация не удалась');
    }

    public function update_info(Request $request) {
        $info = Info::where('user_id', $request->id)->first();

        $info->update([
            'name' => $request->name,
            'job' => $request->job,
            'phone' => $request->phone,
            'address' => $request->address 
        ]);
        
        return redirect('/users')
                        ->with('success', 'Информация успешно изменена');
    }

    public function change_image(Request $request) {
        $image = Image::where('user_id', $request->id)->first();

        Storage::delete($image->image);

        $newImage = $request->file('image');

        $filename = $newImage->store('uploads');

        $image->update(['image' => $filename]);
        
        return redirect('/users')
                        ->with('success', 'Аватар успешно изменен');
    }

    public function credentials(Request $request) {

        $validateFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if( User::where('email', $validateFields['email'])->exists() ){
            return redirect('/users')
                            ->with('error', 'Пользователь с таким email уже существует');
        }
    
        $user = User::where('id', $request->id)->first();
        
        $user->update($validateFields);
    
        if( $user ){
            Auth::login($user);
        
            return redirect('/users')
                        ->with('success', 'Вы успешно изменили данные');
        }
    
        return redirect('users')
                        ->with('error', 'Изменение данных не удалось');
    }

    public function set_status(Request $request) {
        $status = Status::where('user_id', $request->id)->first();

        $status->update([
            'status' => $request->status
        ]);

        return redirect('/users')
                        ->with('success', 'Статус успешно установлен');
    }

    public function delete($id) {
        $current_user = Auth::user();
        
        $user = User::where('id', $id)->first();

        $user->delete();

        $image = Image::where('user_id', $id)->first();

        Storage::delete($image->image);

        $image->delete();

        $info = Info::where('user_id', $id)->first();

        $info->delete();

        $status = Status::where('user_id', $id)->first();

        $status->delete();

        $media = Media::where('user_id', $id)->first();

        $media->delete();

        if( $user->id == $current_user->id ){
            Auth::logout();
            return redirect('/')->with('warning', 'Вы удалили свой профиль');;
        }

        return redirect('/users');
    }
}
