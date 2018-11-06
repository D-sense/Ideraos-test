<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;
use App\City;
use Validator;

class CountryAPIController extends Controller
{

    /**
     * Store a new country in Country model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCountry(Request $request)
    {
        $data = $request->all();
        $countryModel = new Country();

        $validator = Validator::make($data, [
            'name' => 'required|string|unique:countries',
        ]);

        $countryModel->name = $data['name'];

        if($validator->passes()){

            $countryModel->save();
            return response()->json(["status" => "success", "message" => "Country has been saved"], 201);

        }else{

            return $validator->errors();
        }
    }


    /**
     * Displays a new country in Country model.
     *
     * @return $countryModel
     */
    public function fetchCountries()
    {
        $countryModel = Country::all();
        return $countryModel;
    }




    /**
     * Store a new state in State model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON Object
     */
    public function storeState(Request $request)
    {
        // store a state
        $data = $request->all();
        $stateModel = new State();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'country_id' => 'required|integer',
        ]);

        $stateModel->name = $data['name'];

        $check_country_for_id = Country::find($data['country_id']);
        $stateModel->country_id = $data['country_id'];


        if($validator->passes()){
            $stateModel->save();
            return response()->json(["status" => "success", "message" => "State has been saved"], 201);
        }else{
            return $validator->errors();
        }
    }


    /**
     * Displays states.
     *
     * @return $stateModel
     */
    public function fetchStates()
    {
        // store a states
        $stateModel = State::all();
        return $stateModel;
    }



    /**
     * Store a new city in City model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON Object
     */
    public function storeCity(Request $request)
    {
        // store a city
        $data = $request->all();
        $cityModel = new City();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'state_ids' => 'required|string',
        ]);

        $cityModel->name = $data['name'];
        $cityModel->state_ids = json_encode($data['state_ids']);

        if($validator->passes()){
            $cityModel->save();
            return response()->json(["status" => "success", "message" => "City has been saved"], 201);
        }else{
            return $validator->errors();
        }
    }


    /**
     * Display cities found in the supplied country (the logic takes country's name,
     * finds its states, and check for relevant cities in the each state.
     *
     * @param  int  $country_name
     * @return array
     */
    public function searchForCity($country_name)
    {
        // look through Country model.
        $countryModel = Country::where('name', '=', $country_name)->first();
        if(!$countryModel){
            return response()->json(["status" => "error", "message" => "Country name does not exist."], 201);
        }
        $country_id = $countryModel->id;
        //return $country_id;

        // look through State model.
        $country_states = State::where('country_id', '=', $country_id)->get();
        //return  $country_states;

        // array variable to store the cities found in the specified country
        $citiesContainer = [];

        foreach ($country_states as $country_state) {
            // look through City model.
            $cityModels = City::all();

            foreach ($cityModels as $city){
                //singling out state_ids column for inspection
                $state_ids = $city->state_ids;
                $result = $this->cleanData($state_ids);
                //return $result;

                if(in_array($country_state->id, $result)){
                    // insert the current city in a "$citiesContainer" array.
                    array_push($citiesContainer, $city->name);
                }
            }
        }

        return $citiesContainer;
    }


    /**
     * Converts string to array.
     */
    function cleanData($string) {
        $string = str_replace('"', '', $string); // Replaces all double-quotes with nothing.
        //$string = str_replace('" "', '', $string); // Replaces all double-quotes with nothing.
        //return $string;
        $string = preg_replace('/^\[/', '', $string); // Removes special chars.
        $string = preg_replace('/\]$/', '', $string); // Removes special chars.
        return explode(',', $string);
    }


}
