<?php

namespace App\Http\Controllers\Api;

use App\UserType;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Host;
use Illuminate\Http\Request;

class HostController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showHostData()
    {
        $user = Auth::user();

        if ($user->typeuser === UserType::Host) {
            $hostsData = $user->hosts()
                ->select('id', 'userid', 'eventdate', 'closelist', 'shownames', 'userrevealid')
                ->with([
                    'hostnames' => function($query) {
                        $query->select('id', 'hostid', 'name');
                    },
                    'products' => function($query) {
                        $query->select('id', 'hostid', 'name', 'link', 'productimg');
                    },
                    'products.chooseproducts' => function($query) {
                        $query->select('id', 'productid');
                    },
                    'products.chooseproducts.guestnames' => function($query) {
                        $query->select('id', 'chooseproductid', 'name');
                    }
                ])
                ->first();

            if (!$hostsData) {
                return response()->json(['message' => 'Nenhum dado encontrado para este anfitrião.'], 200);
            }
            return response()->json($hostsData, 200);

        } elseif ($user->typeuser === UserType::Other) {
            $hostsData = Host::where('userrevealid', $user->id)
            ->select('id', 'userid', 'eventdate', 'closelist', 'shownames', 'userrevealid')
            ->with([
                'hostnames' => function($query) {
                    $query->select('id', 'hostid', 'name');
                },
                'products' => function($query) {
                    $query->select('id', 'hostid', 'name', 'link', 'productimg');
                },
                'products.chooseproducts' => function($query) {
                    $query->select('id', 'productid', 'userid');
                },
                'products.chooseproducts.guestnames' => function($query) {
                    $query->select('id', 'chooseproductid', 'name');
                }
            ])
            ->first();

            if (!$hostsData) {
                return response()->json(['message' => 'Você não está associado a nenhum anfitrião.'], 200);
            }

            return response()->json($hostsData, 200);

        } elseif ($user->typeuser === UserType::Guest) {
            $shareToken = session('active_share_token');

            $hostsData = Host::where('share_token', $shareToken)
            ->with([
                'hostnames' => function($query) {
                    $query->select('id', 'hostid', 'name');
                },
                'products' => function($query) {
                    $query->select('id', 'hostid', 'name', 'link', 'productimg');
                },
                'products.chooseproducts' => function($query) {
                    $query->select('id', 'productid', 'userid');
                },
                'products.chooseproducts.guestnames' => function($query) {
                    $query->select('id', 'chooseproductid', 'name');
                }
            ])
            ->first();

            if (!$hostsData) {
                return response()->json(['message' => 'Você não está associado a nenhum anfitrião.'], 200);
            }

            return response()->json($hostsData, 200);
        } else {
            return response()->json(['message' => 'Acesso negado. Tipo de usuário não autorizado.'], 403);
        }
    }

    public function storeGuestToken(Request $request, string $shareToken){

        $user = Auth::user();

        $host = Host::where('share_token', $shareToken)->select('id', 'share_token')->first();

        if ($user && $user->typeuser == UserType::Guest) {
            $host->guests()->syncWithoutDetaching([$user->id]);
            session(['active_share_token' => $host->share_token]);
        }

        if ($request->isMethod('post')) {
            return response()->json(['success' => true, 'redirect' => route('homepage')]);
        } else {
            return redirect()->route('homepage');
        }
    }

    public function showChooseHostData(){

        $user = Auth::user();

        if ($user && $user->typeuser == UserType::Guest) {
            $hosts = $user->participatedHosts()
            ->with([
                'hostnames' => function($query) {
                    $query->select('id', 'hostid', 'name');
                }
            ])
            ->get();

            return view("invitation", ['invitations' => $hosts]);
        } else {
            return redirect()->route('index');
        }

    }
}
