<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() && (!Auth::user()->profile || !Auth::user()->profile->is_complete)){
            if(!$request->is('profile/*')){
                return redirect()->route('profile.edit')
                    ->with('error','আপনার প্রফাইলটি সম্পূর্ন হয়নি। সম্পূর্ন করতে এখানে ক্লিক করুন।');
            }
        }
        return $next($request);
    }
}
