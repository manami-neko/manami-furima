<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    public function createProfile()
    {
        $user = auth()->user();
        $mypage = [
            'image' => $user->image ?? null,
        ];

        return view('users.login', compact('mypage'));
    }

    public function storeProfile(Request $request)
    {
        $user = auth()->user();

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('img', 'public');
            $user->image = $path;
            $user->save();
        }

        return redirect('users.profile');
    }
}
