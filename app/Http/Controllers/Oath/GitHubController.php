<?php

namespace App\Http\Controllers\Oath;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function redirect(){
        return Socialite::driver('github')->redirect();
    }

    public function callback(){
        //recuperamos el usuario de la red social
        $gitUser=Socialite::driver('github')->user();
        //Si ya existe lo logeamos, si no lo guardamos en la bases de datos
        $usuario = User::where('external_provider', 'github')
            ->where('external_id', $gitUser->getId())->first();
        //Si No existe $usuario lo guardo y me logeo
        if($usuario){
            $usuario->update([
                'github_token'=>$gitUser->token,
                'github_refresh_token'=>$gitUser->refreshToken
            ]);
        }
        else{
            //creamos el usuario
            $usuario=User::create([
                'external_provider'=>'github',
                'external_id'=>$gitUser->getId(),
                'name'=>$gitUser->getName(),
                'email'=>$gitUser->getEmail(),
                'password'=>bcrypt('password'),
                'email_verified_at'=>now(), //solo si lo tenemos en nuestro proyecto
                'github_token'=>$gitUser->token,
                'github_refresh_token'=>$gitUser->refreshToken
            ]);

        }
        //En cualquier caso logeamios en la app
        
        Auth::login($usuario);
        return redirect('dashboard');


    }
}
