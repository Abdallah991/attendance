<?php



namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\SP;
use App\Models\Student;




class ImagesController extends Controller
{


    public function getImage(Request $request)
    {
        $platformId = $request->platformId;

        $filename = 'aaffoune.jpg';

        $imageUrl = asset('images/' . $filename);

        return response()->json(['image_url' => $imageUrl]);
    }


    public function saveImage($id, $url)
    {
        $filename = $id . '.jpg'; // Desired filename for the saved image

        $imageContents = file_get_contents($url);

        Storage::disk('public')->put('images/' . $filename, $imageContents);
    }


    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            // Save the file to the public "images" directory
            $path = $image->move(public_path('images'), $imageName);
            // Storage::disk('public')->put('images/' . $imageName, $image);
            // Get the public URL of the saved file
            $url = Storage::url($path);
            $imageUrl = asset('images/' . $imageName);
            // ! have a control to see if request from sp or student
            $existingApplicant = SP::where('platformId', $imageName)->first();

            $existingApplicant->pictureChanged = 1;
            $existingApplicant->profilePicture = $imageUrl;
            $existingApplicant->save();


            // Optionally, you can save the file path or URL to your database
            return response()->json(
                [
                    'message' => 'Image uploaded successfully',
                    'url' => $imageUrl
                ]
            );
        }
        return response()->json(['message' => 'No image file found'], 400);
    }

    public function uploadStudent(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            // Save the file to the public "images" directory
            $path = $image->move(public_path('images'), $imageName);
            // Storage::disk('public')->put('images/' . $imageName, $image);
            // Get the public URL of the saved file
            $url = Storage::url($path);
            $imageUrl = asset('images/' . $imageName);
            // ! have a control to see if request from sp or student
            $existingStudent = Student::where('id', $imageName)->first();

            $existingStudent->pictureChanged = 1;
            $existingStudent->profilePicture = $imageUrl;
            $existingStudent->save();


            // Optionally, you can save the file path or URL to your database
            return response()->json(
                [
                    'message' => 'Image uploaded successfully',
                    'url' => $imageUrl
                ]
            );
        }
        return response()->json(['message' => 'No image file found'], 400);
    }
}
