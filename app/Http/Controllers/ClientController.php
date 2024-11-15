<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Get a list of clients for a combo box.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select(Request $request)
    {
        $search = $request->query('search', '');

        $clients = Client::query()
            ->when($search, function ($query, $search) {
                $query->where('client_name', 'like', "%{$search}%")
                      ->orWhere('client_number', 'like', "%{$search}%");
            })
            ->select('id', DB::raw("CONCAT(client_number, ' - ', client_name) as client_display"))
            ->orderBy('client_name')
            ->get();

        return response()->json($clients);
    }
}
