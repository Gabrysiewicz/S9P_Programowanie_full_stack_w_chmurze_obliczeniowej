<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Offert;
use Illuminate\Http\Request;

class OffertController extends Controller
{
    // Get & Show all 
    public function index(){
        return view('offerts.index', [
            // 'offerts' => Offert::all()
            'offerts' => Offert::latest()->filter(request(['profession', 'search']))->get()
            // 'offerts' => Offert::latest()->filter(request(['profession', 'search']))->paginate(6)
        ]);
    }
    // Show single
    public function show(Offert $offert){
        return view('offerts.show', [
            'offert' => $offert
        ]);
    }
    // Create new offert
    public function create(){
        return view('offerts.create');
    }
    // Store new offert
    public function store(Request $request){
        // dd($request->all());
        $formFields = $request->validate([
            'name' => 'string|required|max:64',
            'surname' => 'string|required|max:64',
            'voivodeship' => 'string|required|max:64',
            'city' => 'string|required|max:32',
            'company' => 'string|nullable|max:64',
            'profession' => 'string|required|max:64',
            'workplace' => 'string|required|max:64',
            'profile-picture' => 'nullable',
            'youtube' => 'url|nullable|max:128',
            'facebook' => 'url|nullable|max:128',
            'instagram' => 'url|nullable|max:128',
            'tiktok' => 'url|nullable|max:128',
            'twitter' => 'url|nullable|max:128',
            'description' => 'string|nullable|max:512'
        ]);

        if($request->hasFile('profile-picture')){
            $formFields['profile-picture'] = $request->file('profile-picture')->store('profile-pictures', 'public');
        }
        $formFields['user_id'] = auth()->id();
        $formFields['description'] = nl2br(e($request->description));

        // dd($formFields);

        Offert::create($formFields);

        return redirect('/')->with('message', 'Your offert has been added.');
    }
    // Show edit form
    public function edit(Offert $offert){
        return view('offerts.edit', ['offert' => $offert]);
    }

    // Update the offert
    public function update(Request $request, Offert $offert){
        // dd($request->all());
        $formFields = $request->validate([
            'name' => 'string|required|max:64',
            'surname' => 'string|required|max:64',
            'voivodeship' => 'string|required|max:64',
            'city' => 'string|required|max:32',
            'company' => 'string|nullable|max:64',
            'profession' => 'string|required|max:64',
            'workplace' => 'string|required|max:64',
            'profile-picture' => 'nullable',
            'youtube' => 'url|nullable|max:128',
            'facebook' => 'url|nullable|max:128',
            'instagram' => 'url|nullable|max:128',
            'tiktok' => 'url|nullable|max:128',
            'twitter' => 'url|nullable|max:128',
            'description' => 'string|nullable|max:512'
        ]);

        if($request->hasFile('profile-picture')){
            $formFields['profile-picture'] = $request->file('profile-picture')->store('profile-pictures', 'public');
        }
        // dd($formFields);
        $formFields['description'] = nl2br(e($request->description));

        
        $offert->update($formFields);

        return redirect('/offerts/'. $offert->id)->with('message', 'Updated successfully');
    }
    public function delete(Offert $offert){
        $offert->delete();
        return redirect('/offerts/manage')->with('message', 'Deleted Succesfully');
    }
    public function manage(){
        return view('offerts.manage', ['offerts' => auth()->user()->offerts()->get()]);
    }
}
