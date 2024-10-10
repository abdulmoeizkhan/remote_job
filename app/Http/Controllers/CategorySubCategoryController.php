<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use App\Models\{Country,State};
use App\Models\jobCategory;
use App\Models\Sub_category;
 use DB;
class CategorySubCategoryController extends Controller
{
 
     public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
    public function get_sub_category(Request $request)
    {
        // dd($request->all());
         $sub= Sub_category::where('category_id',$request->id)->get();
        
        foreach($sub as $item){
            // dd($item->id);
            echo '<option value='.$item->id.'>'.$item->sub_category.'</option>';
        }
    }
      
    public function index()
    {
        $data['countries'] = Country::paginate(25);
        return view('admin.country',$data);
    }
    public function country(Request $request){
        $Country=new Country();
        $Country->name=$request->input('name');
        $Country->save();
        return $this->index();
    }
    public function addStates(Request $request){
         $state=new State();
         $state->country_id=$request->input('country_id');
         $state->name=$request->input('name');
         $state->save();
        return $this->states();
    }
    public function getState(Request $request)
    {
        
        $st= State::where("country_id",$request->country_id)
                    ->get(["name","id"]);
                    if(!empty($st)){
                      $data['states']=$st;  
                    }
                    else{
                        $data['states']=array("name"=>"asdf",'id'=>0);
                    }
        return response()->json($data);
    }
    public function states(){
        // $data['states']=State::all();
        $data['states']=DB::select('select states.*,countries.name as coutry from states join countries ON countries.id = states.country_id');
        $data['countries'] = Country::get(["name","id"]);
        return view('admin.states',$data);
    }
    // public function getCity(Request $request)
    // {
    //     $data['cities'] = City::where("state_id",$request->state_id)
    //                 ->get(["name","id"]);
    //     return response()->json($data);
    // }
 
}