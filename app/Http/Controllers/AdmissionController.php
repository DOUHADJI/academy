<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdmissionSubmitRequest;
use App\Models\Admission;
use App\Models\Schedule;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdmissionController extends Controller
{
    /**
     * Student routes 
     */
    public function index()
    {
        $has_admissions = Admission::where('user_id', Auth::id())->exists();

        if($has_admissions)
        {
            return view("user.see-admission-infos");       
        }
        
        return view("user.submit-admission");
    }

    public function storeAdmission(AdmissionSubmitRequest $request)
    {
        $admissions = $request -> validated();

        for($i=0; $i<5;$i++)
        {
            array_pop($admissions);
        }

       $user_has_admission = Admission::where('user_id', Auth::id()) -> exists();
       
       if($user_has_admission)
       {
         $former_admissions = Admission::where('user_id', Auth::id()) -> get();
         foreach($former_admissions as $item)
         {
            $item -> delete();
         }
       }

       
        
        foreach ($admissions as $key => $schedule) 
        {
            foreach($admissions as $k => $value)
            {
                if($schedule == $value && $k!= $key)
                {
                    dd($schedule, $value,$key,$k);
                    $duplicate = Schedule::whereId($value) -> first();
                    return redirect() -> back() -> withErrors([
                        "duplication" => "vous avez choisi plusieurs fois le  cours " .$duplicate -> titre_diplome,
                    ]);
                }
            }
          
        }
        
        $files = [
            [
                "name" => "cv",
                "as" => "cv.". $request -> cv -> extension()
            ],

            [
                "name" => "releve_bepc",
                "as" => "releve_bepc.". $request -> releve_bepc -> extension()
            ],

            [
                "name" => "releve_bac_1",
                "as" => "releve_bac_1.". $request -> releve_bac_1 -> extension()
            ],

            [
                "name" => "releve_bac_2",
                "as" => "releve_bac_2.". $request -> releve_bac_2 -> extension()
            ],

            [
                "name" => "lettre_motivation",
                "as" => "lettre_motivation.". $request -> lettre_motivation -> extension()
            ],
            
        ];
        $paths = [];
        
        foreach($files as $file)
        {
            $paths[$file["name"]] = $request -> file($file["name"]) 
            -> storeAs(Auth::user()->id, $file["as"],"public");     
        }
        
       
        
    

      foreach($admissions as $schedule)
      {
        $_admission = $paths;
        
        $_admission['status'] = 'received';
        $_admission["schedule_id"] = $schedule;
        $_admission["user_id"] = Auth::id();      

       $new = Admission::create($_admission);
       
      }

        return view('user.see-admission-infos');
       
    }

    public function cv()
    {
        $pdf = new Dompdf();
        dd($pdf);
        return Storage::url(Auth::id()."/cv.pdf");
    }

    public function seeAdmission(Request $request)
    {
        return view("user.see-admission-infos");   
    }

    /**
     * Admin Routes
     */

     public function show(Request $request)
     {
        $id = $request -> admissionId;
        
        if(isset($id))
        {
            $admission = Admission::whereId($id) -> first();
            
            return view("admin.check-admission", [
                "admission" => $admission
            ]);
        }
        
        return view('admin.show-admissions');
     }

     public function all()
     {
        return view('admin.all-admissions');
     }

    public function treatAdmission(Request $request)
    {
        $request -> validate([
            "decision" => ["required"],
            "admissionId" => ["required"]
        ]);

        $admission = Admission::whereId($request -> admissionId) -> first();
        
        $admission -> update([
            "status" => $request -> decision,
            "treated" => true
        ]);

        return redirect()->route("showAdmissions",[
            "admissionId" => $admission -> id 
        ]);

        
    }
}