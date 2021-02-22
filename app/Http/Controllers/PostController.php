<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function getHouses()
    {
        $apiKey = $this->getApiKey();

        $houses = Http::withHeaders([
            'apiKey' => $apiKey
        ])->get('http://us-central1-rh-challenges.cloudfunctions.net/potterApi/houses');

        $data = json_decode($houses->body())->houses;

        $json = "";
        if(file_exists(public_path('json/characters.json'))){
            if(file_get_contents(public_path('json/characters.json')) != ""){
                $json = json_decode(file_get_contents(public_path('json/characters.json')), true);
            }
        }

        return view('welcome')->with('houses', $data)->with('json', $json);
    }

    private function getApiKey()
    {
        $response = Http::post('http://us-central1-rh-challenges.cloudfunctions.net/potterApi/users', [
            'email' => 'sara.novaalianca@hotmail.com',
            'name' => 'Sara Pereira',
            'password' => '1234'
        ]);
    
        $this->apiKey = json_decode($response->body());
    
        return $this->apiKey->user->apiKey;
    }

    public function sendData(Request $request)
    {
        $data = $request->all();
        if(Storage::disk('public_folder')->exists('json/characters.json') && Storage::disk('public_folder')->get('json/characters.json') != ""){
            $json = json_decode(Storage::disk('public_folder')->get('json/characters.json'));
            $id = $data['id'];
            unset($data['id']);
            if($id == ""){
                $json->characters[] = $data;
            }else{
                $json->characters[intval($id)] = $data;
            }
            Storage::disk('public_folder')->put('json/characters.json', json_encode($json));
        }else{
            $data = json_encode($data);
            $json = '{"characters": ['. $data .']}';
            Storage::disk('public_folder')->put('json/characters.json', $json);
        }
        return redirect()->route('home');
    }

    public function searchCharacter($id)
    {
        $search = "";
        if(Storage::disk('public_folder')->exists('json/characters.json')){
            $json = json_decode(Storage::disk('public_folder')->get('json/characters.json'));
            $search = $json->characters[$id];
        }
        return json_encode($search);
    }
    public function deleteCharacter($id)
    {
        if(Storage::disk('public_folder')->exists('json/characters.json')){
            $json = json_decode(Storage::disk('public_folder')->get('json/characters.json'));
            unset($json->characters[$id]);
            Storage::disk('public_folder')->put('json/characters.json', json_encode($json));   
        }
        return redirect()->route('home');
    }
}
