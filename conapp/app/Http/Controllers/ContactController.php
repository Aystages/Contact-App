<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\Company;

class ContactController extends Controller
{
    public function index()
    {
        $companies= Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        $contacts = Contact::orderBy('id','desc')->where(function ($query){
            if($companyId = request('company_id')){
                // $company = Company::query()->where('id', $companyId)->get();
                $query->where('company_id',$companyId);
                // dd($company);
            }
        })->paginate(10);

        return view ('contacts.index', compact('contacts','companies'));
    }

    public function create(){

        $companies= Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        return view('contacts.create', compact('companies'));
    }

    public function store(Request $request){

        // dd($request->all());

        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
            'company_id'=>'required|exists:companies,id'
        ]);

        Contact::create($request->all());

        return redirect()->route('contacts.index')->with('message','Contact has been added successfully');
    }

    public function show($id){
    $contact = Contact::find($id);
    return view ('contacts.show', compact('contact')); //['contact'=>$contact]
    }
}
