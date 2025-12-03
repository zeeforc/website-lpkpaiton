<?php

namespace App\Http\Controllers;

use App\Models\Sarana;
use App\Models\SaranaSatu;
use Illuminate\Support\Facades\Cache;

class SaranaController extends Controller
{
    public function detail()
    {
        [$saranaDesk, $prasaranaData] = Cache::remember(
            'sarana_prasarana_detail',
            now()->addHours(6),
            function () {
                $saranaDesk = SaranaSatu::query()
                    ->latest('id')
                    ->value('sarana_desk');

                $prasaranaData = Sarana::query()
                    ->select('fasilitas_title', 'fasilitas_image', 'fasilitas_desk')
                    ->get()
                    ->map(function (Sarana $sarana) {
                        return [
                            'title'       => $sarana->fasilitas_title,
                            'image'       => $sarana->image_url,
                            'description' => $sarana->fasilitas_desk,
                        ];
                    })
                    ->values();

                return [$saranaDesk, $prasaranaData];
            }
        );

        return view('sarana', [
            'saranaDesk'    => $saranaDesk,
            'prasaranaData' => $prasaranaData,
        ]);
    }
}
