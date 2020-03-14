<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        return view('file.index');
    }

    public function getImage($searchKey)
    {
        $temparray = [];
        if ($searchKey !== 'null') {
            $getDataImage = file_get_contents('file.json');
            $getDataArrayImage = json_decode($getDataImage);

            foreach ($getDataArrayImage as $key => $item) {
                if ($item->title == $searchKey) {
                    array_push($temparray, $getDataArrayImage[$key]);
                }
            }
            return response()->json($temparray);

        } else {
            $getDataImage = file_get_contents('file.json');
            $getDataArrayImage = json_decode($getDataImage);
        }
        return response()->json($getDataArrayImage);
    }

    public function upload(Request $request)
    {
        $fileData['id'] = 1;
        $fileData['title'] = $request->get('title');
        $fileData['image_path'] = $request->file('image')->storeAs('image/file', time() . '.' . $request->file('image')->getClientOriginalExtension(), 'public');
        $dataArray[] = $fileData;

        $inp = file_get_contents('file.json');
        $tempArray = json_decode($inp);
        if ($tempArray) {
            $last_item    = end($tempArray);
            $last_item_id = $last_item->id;
            $last_item_id++;
            $fileData['id'] = $last_item_id;
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
                array_splice($getDataArrayRemove, $key, 1);
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
