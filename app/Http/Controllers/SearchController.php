<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $request = $request->all(); // Get request
            $search = strtolower($request['term']); // Transform Str
            $url = base_path('resources/keyword.json'); // JSON file URL
            $rows = file_get_contents($url);
            $rows = json_decode($rows, true); // Get content & decode
            $rows = array_filter($rows);
            $rows = collect($rows)->sortBy('Name'); // Collect & Order
            $data = $rows->filter(function($value) use ($search) {
                return false !== (  stristr($value['ID'], $search) ||
                                    stristr(strtolower($value['name']), $search) ||
                                    stristr(strtolower($value['city']), $search) ||
                                    stristr(strtolower($value['state']), $search)) ;
            }); // Filter elements
            return response()->json(['success'=> true, 'data' => $data->values()]);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()]);
        }
    }
}
