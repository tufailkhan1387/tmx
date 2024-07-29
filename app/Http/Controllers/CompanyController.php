<?php

namespace App\Http\Controllers;

use App\Mail\CompanyWelcomeMail;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view-companies|create-companies|update-companies|delete-companies', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-companies', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-companies', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-companies', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data['user'] = Auth::user();
        $data['companies'] = Company::orderBy('id', 'DESC')->get();
        return view('companies.list', $data);
    }

    public function create()
    {
        $data['user'] = Auth::user();
        return view('companies.create', $data);
    }

    public function store(Request $request)
    {
        // check for spatie role id instead of name
        $this->validate($request, [
            'name'          => 'required',
            'email'         => 'required|email|unique:companies,email',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'joining_date'  => 'nullable|date_format:Y-m-d',
            'expiry_date'   => 'nullable|date_format:Y-m-d',
            'phone'         => 'nullable|numeric',
            'whatsapp'      => 'nullable|numeric',
        ]);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $image_name = time() . '_' . uniqid('', true) . '.' . $image->getClientOriginalExtension();
            $org_name = $image->getClientOriginalName();
            $request->file('logo')->storeAs('public/companies_logo/', $image_name);
        }

        $company = new Company();
        $company->name         = $request->name;
        $company->email        = $request->email;
        $company->joining_date = $request->joining_date;
        $company->expiry_date  = $request->expiry_date;
        $company->phone        = $request->phone;
        $company->whatsapp     = $request->whatsapp;
        $company->created_by   = Auth::id();

        if ($request->hasFile('logo')) {
            $company->logo = $image_name;
        }
        $response = $company->save();
        if ($response) {
            try {
                Mail::to($company->email)->send(new CompanyWelcomeMail($company));
            } catch (\Exception $e) {
                // return redirect()->route('companies.list')->with('warning', 'Company created successfully, but the welcome email could not be sent.');
            }
            return redirect()->route('companies.list')->with('success', 'Company created successfully');
        } else {
            return redirect()->back()->with('error', 'Company was not created.');
        }
    }

    public function edit($id)
    {
        $data['company'] = Company::find($id);
        return view('companies.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'email'         => [
                'required',
                'email',
                Rule::unique('companies')->ignore($request->id),
            ],

            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'joining_date'  => 'nullable|date_format:Y-m-d',
            'expiry_date'   => 'nullable|date_format:Y-m-d',
            'phone'         => 'nullable|numeric',
            'whatsapp'      => 'nullable|numeric',
        ]);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $image_name = time() . '_' . uniqid('', true) . '.' . $image->getClientOriginalExtension();
            $request->file('logo')->storeAs('public/companies_logo/', $image_name);
        }

        $post_data['name']         = $request->name;
        $post_data['email']        = $request->email;
        $post_data['joining_date'] = $request->joining_date;
        $post_data['expiry_date']  = $request->expiry_date;
        $post_data['phone']        = $request->phone;
        $post_data['whatsapp']     = $request->whatsapp;
        $post_data['updated_by']   = Auth::id();

        if ($request->hasFile('logo')) {
            $post_data['logo'] = $image_name;
        }

        $company = Company::find($request->id);
        $response = $company->update($post_data);

        if ($response) {
            return redirect()->route('companies.list')->with('success', 'Company updated successfully');
        } else {
            return redirect()->back()->with('error', 'Company does not updated.');
        }
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete(); // Soft delete

        return redirect()->route('companies.list')->with('success', 'Record deleted successfully.');
    }
}
