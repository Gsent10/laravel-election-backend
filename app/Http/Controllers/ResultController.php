<?php

namespace App\Http\Controllers;

use App\Models\PollingUnit;
use App\Models\PollingUnitResult;
use App\Models\Ward;
use App\Models\Party;
use App\Models\Lga;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Return list of all polling units.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pollingUnits = PollingUnit::where('uniquewardid', '!=', 0)->get();

        return response()->json([
            'pollingunits' => $pollingUnits,
        ], 200);
    }


    /**
     * Returns polling unit result.
     *
     * @return \Illuminate\Http\Response
     */
    public function unitResult($unitId)
    {
        $result = PollingUnitResult::where('polling_unit_uniqueid', $unitId)->get();

        return response()->json([
            'result' => $result,
        ], 200);
    }



    /**
     * Return list of all wards.
     *
     * @return \Illuminate\Http\Response
     */
    public function wards()
    {
        $wards = Ward::orderBy('ward_name', 'ASC')->get();

        return response()->json([
            'wards' => $wards,
        ], 200);
    }


    /**
     * Returns ward result.
     *
     * @return \Illuminate\Http\Response
     */
    public function wardResult($wardId)
    {
        $ward = Ward::where('uniqueid', $wardId)->first();
        $parties = Party::select('partyid')->get();
        $pu = PollingUnit::where('uniquewardid', $wardId)->get();

        foreach ($parties as $party) {
            $result[$party->partyid] = array('party' => $party->partyid, 'result' => 0);
        }

        $total = 0;
        foreach ($pu as $unit) {
            foreach ($parties as $party) {
                $score = PollingUnitResult::select('party_score')->where('polling_unit_uniqueid', $unit->uniqueid)->where('party_abbreviation', $party->partyid)->get();
                if (count($score) > 0) {
                    $result[$party->partyid]["result"] += intval($score[0]->party_score);
                }
                
            }
        }

        return response()->json([
            'ward' => $ward,
            'result' => $result,
        ], 200);
    }


    /**
     * Return list of all lgas
     *
     * @return \Illuminate\Http\Response
     */
    public function lgas()
    {
        $lgas = Lga::orderBy('lga_name', 'ASC')->get();

        return response()->json([
            'lgas' => $lgas,
        ], 200);
    }



    /**
     * Returns ward result.
     *
     * @return \Illuminate\Http\Response
     */
    public function lgaResult($lgaId)
    {
        $lga = Lga::where('lga_id', $lgaId)->first();
        $parties = Party::select('partyid')->get();
        $pu = PollingUnit::where('lga_id', $lgaId)->get();

        foreach ($parties as $party) {
            $result[$party->partyid] = array('party' => $party->partyid, 'result' => 0);
        }

        foreach ($pu as $unit) {
            foreach ($parties as $party) {
                $score = PollingUnitResult::select('party_score')->where('polling_unit_uniqueid', $unit->uniqueid)->where('party_abbreviation', $party->partyid)->get();
                if (count($score) > 0) {
                    $result[$party->partyid]["result"] += intval($score[0]->party_score);
                } 
            }
        }

        return response()->json([
            'lga' => $lga,
            'result' => $result,
        ], 200);
    }

    /**
     * Upload result for a polling unit
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $request->validate([
            'puId' => ['required'],
            'ACN' => ['required'],
            'ANPP' => ['required'],
            'CDC' => ['required'],
            'CPP' => ['required'],
            'DPP' => ['required'],
            'JP' => ['required'],
            'LABOUR' => ['required'],
            'PDP' => ['required'],
            'PPA' => ['required']
        ]);

        $parties = Party::select('partyid')->get();
        $result = PollingUnitResult::where('polling_unit_uniqueid', $request->puId)->get();

        if (count($result) > 0) {
            return response()->json([
                'response' => "Results already uploaded",
            ], 402);
        }

        foreach ($parties as $party) {
            $x = $party->partyid;
            PollingUnitResult::create([
                'polling_unit_uniqueid' => $request->puId,
                'party_abbreviation' => $x,
                'party_score' => $request->$x
            ]);
        }

        

        return response()->json([
            'response' => "successfully created",
        ], 200);
    }
}
