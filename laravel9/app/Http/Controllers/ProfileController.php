<?php
namespace App\Http\Controllers;

use App\Models\MailingAddress;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user=$request->user()->load('mailingAddress');
        return view('profile.show',compact('user'));
    }

    public function update(Request $request)
    {
        $user=$request->user();
        $data=$request->validate([
            'full_name'=>['required','string','max:255'],
            'email'=>['nullable','email','max:255','unique:users,email,'.$user->id],
            'phone'=>['nullable','string','max:50'],
            'postal_code'=>['nullable','string','max:20'],
            'region'=>['nullable','string','max:255'],
            'district'=>['nullable','string','max:255'],
            'city'=>['nullable','string','max:255'],
            'street'=>['nullable','string','max:255'],
            'house'=>['nullable','string','max:50'],
            'apartment'=>['nullable','string','max:50'],
            'address_comment'=>['nullable','string','max:1000'],
        ]);

        $user->update([
            'full_name'=>$data['full_name'],
            'email'=>$data['email']??null,
            'phone'=>$data['phone']??null,
        ]);

        MailingAddress::updateOrCreate(
            ['user_id'=>$user->id],
            [
                'postal_code'=>$data['postal_code']??null,
                'region'=>$data['region']??null,
                'district'=>$data['district']??null,
                'city'=>$data['city']??null,
                'street'=>$data['street']??null,
                'house'=>$data['house']??null,
                'apartment'=>$data['apartment']??null,
                'comment'=>$data['address_comment']??null,
            ]
        );

        return back()->with('ok','Профиль сохранён.');
    }
}
