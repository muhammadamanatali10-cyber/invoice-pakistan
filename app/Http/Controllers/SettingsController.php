<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        
        if (!$user) {
            return redirect()->route('login');
        }

        $company = CompanySetting::where('user_id', $user->id)->first();

        return view('settings.index', compact('user', 'company'));
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => 'nullable|confirmed|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        
        if ($request->hasFile('profile_picture')) {
            
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return back()->with('success', 'Account settings updated successfully.');
    }

    public function updateCompany(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'country'      => 'required|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $company = CompanySetting::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('company_logos', 'public');
            $company->company_logo = $path;
        }

        $company->company_name = $request->company_name;
        $company->phone = $request->phone;
        $company->country = $request->country;
        $company->state = $request->state;
        $company->city = $request->city;
        $company->zip = $request->zip;
        $company->address_line1 = $request->address_line1;
        $company->address_line2 = $request->address_line2;

        $company->save();

        return back()->with('success', 'Company details updated successfully.');
    }
}