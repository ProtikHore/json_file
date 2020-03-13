<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        return view('file.index');
    }

    public function getImage()
    {
        $getDataImage = file_get_contents('file.json');
        $getDataArrayImage = json_decode($getDataImage);
        return response()->json($getDataArrayImage);
    }

    public function upload(Request $request)
    {
        $fileData['id'] = 1;
        $fileData['title'] = $request->get('title');
        $fileData['image_path'] = $request->file('image')->storeAs('image/file', time() . '.' . $request->file('image')->getClientOriginalExtension(), 'public');
        $dataArray[] = $fileData;
//        $fp = fopen('file.json', 'w');
//        fwrite($fp, json_encode($dataArray));
//        fclose($fp);
//        return response()->json($fileData);

        $inp = file_get_contents('file.json');
        $tempArray = json_decode($inp);
//        return response()->json($tempArray);
        if ($tempArray) {
            array_push($tempArray, $fileData);
        } else {
            $tempArray = $dataArray;
        }
        $jsonData = json_encode($tempArray);
        file_put_contents('file.json', $jsonData);
        return response()->json($tempArray);

    }

    public function remove($id)
    {
        $getDataRemove = file_get_contents('file.json');
        $getDataArrayRemove = json_decode($getDataRemove);
        foreach ($getDataArrayRemove as $key => $value) {
            if ($id == $value->id) {
//                unset($getDataArrayRemove[$key]);
                array_splice($getDataArrayRemove, 0);
//                $newArray[] = $getDataArrayRemove;
                $status = json_encode($getDataArrayRemove);
                file_put_contents('file.json', $status);
                return response()->json($status);
            }
        }
//        $status = json_encode($getDataArray);
        //return response()->json($getDataArray);
    }
}
