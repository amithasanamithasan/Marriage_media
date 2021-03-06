<?php

namespace App\Http\Controllers;
// use Symfony\Component\HttpFoundation\Request;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
	public function __construct()
    {
        $this->middleware('role:superadministrator');
    }

     public function index()
     {
  	return view('admin.index');
  }


  public function AllCategory()
  {
  	$category =DB::table("registers")->get();
  	//return response()->json($category );
  	return view('admin.all_category', compact('category'));

  }
  
  public function Viewcategory($id)
  {
     $category=DB::table('registers')->WHERE('id',$id)->first();
    // return response()->json($category );

    return view('admin.categoryview',['cat'=>$category]);
  }

public function Deletecategory($id)
{
  $delete=DB::table('registers')->where ('id',$id)->delete();

  $notification=array(
'messege'=>'Sucessfully category Deleted',
'atert-type'=>'danger');
  return Redirect()->back()->with($notification);
}



public function Editcategory($id)
{
$category=DB::table('registers')->WHERE('id',$id)->first();
      return view('admin.editcategory',compact('category'));
}


public function Updatecategory(Request $request, $id)
{
  $validatedData = $request->validate([
  
    ]);
 $data = array();
 $data ['name']=$request->name;
 $data ['age']=$request->age;
$data ['phone']=$request->phone;
 $data ['gender']=$request->gender;
 $data ['dateofbirth']=$request->dateofbirth;
$data ['religion']=$request->religion;
 $data ['email']=$request->email;
 $data ['occupation']=$request->occupation;
$data ['country']=$request->country;
 $data ['bloodgroup']=$request->bloodgroup;
 $data ['hight']=$request->hight;
$data ['address']=$request->address;
$image=$request->file('image');


  if ($image) {

    $image_name=hexdec(uniqid());
  $ext=strtolower($image->getClientOriginalExtension());
   // $request->file('image')->getClientOriginalExtension();
    $image_full_name=$image_name.'.'.$ext;
    $upload_path='public/fontend/image/';
    $image_url=$upload_path.$image_full_name;
    $success=$image->move($upload_path,$image_full_name);

            $data['image']=$image_url;
      DB::table('registers')->where('id',$id)->update($data);
     $notification=array(
      'massage'=>'successfully category update',
      'alert-type'=>'success'

    );
    return Redirect()->route('all.category')->with($notification);
    
  }
  else
  {
             // $data['image']=$request->old_photo;
             DB::table('registers')->where('id',$id)->update($data);
             $notification=array(
                'messege'=>'Successfully Post Updated',
                'alert-type'=>'success'
                 );
             return Redirect()->route('all.category')->with($notification);
        }
    }


public function addmember()
{


return view('admin.addmember');

}
 public function search(Request $request)
{
  $search = $request->get('search');
   $member =DB::table('registers')->where('name','like','%'.$search.'%')->paginate(5);
    return view('admin.all_category',['category'=>$member]);


}



public function Addpost(Request $request)
 {
 $data = [
 'name'=>$request->name,
 'age'=>$request->age,
'phone'=>$request->phone,
'gender'=>$request->gender,
 'dateofbirth'=>$request->dateofbirth,
'religion'=>$request->religion,
 'email'=>$request->email,
 'occupation'=>$request->occupation,
'country'=>$request->country,
 'bloodgroup'=>$request->bloodgroup,
 'hight'=>$request->hight,
'address'=>$request->address,
'image' => $request->image,
];
  $image=$request->image;
// dd($data);

 if ($request->hasFile('image')) {
    $imageName = rand(11111, 99999) . '.' . $request->file('image')->getClientOriginalExtension();
    $destinationPath = 'public/profile/images/';
    $upload_success = $request->file('image')->move($destinationPath, $imageName);

     DB::table('addmembers')->insert($data);

    $notification=array(
      'massage'=>'successfully category insert',
      'alert-type'=>'success'

    );
    return redirect()->back()->with($notification);
    
  }
  else
  {

    DB::table('addmembers')->insert($data);
    $notification=array(
      'massage'=>'successfully category insert',
      'alert-type'=>'success'

    );
    return redirect()->back()->with($notification);
     }
    }




}


