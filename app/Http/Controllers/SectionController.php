<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{

    public function index()
    {

        $sections=Section::all();
        return view('sections.sections',compact('sections'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
//        $input=$request->all();
//        $check_if_exists=Section::where('section_name',$input['section_name'])->exists() ;
//        if ($check_if_exists) {
//
////            return redirect()->back()->withErrors(['القسم مسجل بالفعل']);
////            return "القسم مسجل بالفعل";
//             session()->flash('Error','القسم مسجل بالفعل');
//            return redirect('/sections');
//        }
//        else{
        $vaildData=$request->validate([

                'section_name'=>'required|unique:sections|max:255|min:5',
                 'description'=>'required'
            ],[
            'section_name.required'=>'يرجي ادخال اسم القسم ',

            'section_name.unique'=>'هذا القسم موجود مسبقا ',
            'section_name.max'=>'عفوا الحد الاقصي 255 حرف ',
            'section_name.min'=>'عفوا الحد الادني 5 حرووف ',
            'description.required'=>'يرجي ادخال الوصف'


        ]);

            $store=Section::create([

                'section_name'=>$request->section_name,
                'description'=>$request->description,
                'created_by'=>(Auth::user()->name)


            ]);
            session()->flash('Add','تم اضافه القسم بنجاح');
            return redirect('/sections');

    }



    public function show(Section $section)
    {
        //
    }


    public function edit(Section $section)
    {
        return view('curd.edit');
    }


    public function update(Request $request)
    {
        $id=$request->id;


        $this->validate($request,[

            'section_name'=>'required|max:255|unique:sections,section_name,'.$id,
            'description'=>'required'
        ],[
            'section_name.required'=>'يرجي ادخال اسم القسم ',
            'section_name.unique'=>'هذا القسم موجود مسبقا ',
            'section_name.max'=>'عفوا الحد الاقصي 255 حرف ',
            'description.required'=>'يرجي ادخال الوصف'


        ]);

        $sections=Section::find($id);
        $sections->update([
           'section_name'=>$request->section_name,
           'description'=>$request->description,


        ]);
        session()->flash('edit', 'تم تعديل  القسم بنجاح ');
        return redirect('/sections');

    }


    public function destroy(request $request)
    {
      $id = $request->id;
      $sections=Section::find($id);
      $sections->delete();

        session()->flash('delete', 'تم حذف   القسم بنجاح ');
        return redirect('/sections');
    }
}
