<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Host;
use App\Models\HostName;
use App\UserType;


class FirstAccessController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showFirstAccessForm()
    {
        $user = Auth::user();


        if ($user) {
            if ($user->typeuser->label() === 'Convidado') {
                $hosts = $user->participatedHosts()->select('share_token')->get();

                if ($hosts->count() > 1) {
                    return redirect()->route('host.choose');

                } elseif ($hosts->count() === 1) {
                    session(['active_share_token' => $hosts->first()->share_token]);
                    return redirect()->route('homepage');
                } else {
                    return redirect()->route('index');
                }
            }

            if ($user->firstaccess === false || $user->typeuser->label() !== 'Anfitrião') {
                return redirect()->route('homepage');
            }
        }

        return view('firstAccess');
    }

    public function createFirstAccessForm(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'hostNames' => 'required|array|min:1',
            'hostNames.*.name' => 'required|string|max:255',
            'eventDate' => 'required|date|after_or_equal:today',
            'listEndDate' => 'required|date|before:eventDate|after_or_equal:today',
            'showNames' => 'required|boolean',
            'revealNames' => 'required|string|in:ninguem,todos,outro',

            'newUser.name' => 'nullable|string|max:255',
            'newUser.email' => 'nullable|email|unique:users,email',
            'newUser.pass' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $userRevealId = null;

            if ($request->input('revealNames') === 'outro' && $request->boolean('showNames')) {
                $newUserData = $request->input('newUser');

                if (empty($newUserData['name']) || empty($newUserData['email']) || empty($newUserData['pass'])) {
                     throw ValidationException::withMessages([
                        'newUser' => ['Preencha todos os campos do usuário!'],
                    ]);
                }

                $newUserReveal = User::create([
                    'name' => $newUserData['name'],
                    'email' => $newUserData['email'],
                    'pass' => $newUserData['pass'],
                    'typeuser' => UserType::Other,
                    'firstaccess' => false,
                ]);
                $userRevealId = $newUserReveal->id;

            }

            $host = Host::create([
                'userid' => $user->id,
                'eventdate' => $request->input('eventDate'),
                'closelist' => $request->input('listEndDate'),
                'shownames' => $request->boolean('showNames'),
                'userrevealid' => $userRevealId,
            ]);

            foreach ($request->input('hostNames') as $hostNameData) {
                HostName::create([
                    'hostid' => $host->id,
                    'name' => $hostNameData['name'],
                ]);
            }

            $user->firstaccess = false;
            $user->save();

            DB::commit();

            return response()->json(['message' => 'Configurações de primeiro acesso salvas com sucesso!'], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ocorreu um erro interno ao salvar as configurações.'], 500);
        }
    }
}
