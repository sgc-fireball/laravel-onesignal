<?php

namespace HRDNS\LaravelPackages\OneSignal\Http\Controllers;

use HRDNS\LaravelPackages\OneSignal\Models\OneSignalDevice;
use Illuminate\Http\Request;

class OneSignalController
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function oneSignalStatus(Request $request)
    {
        if (csrf_token() !== $request->get('csrf_token')) {
            abort(403);
        }
        $oneSignalId = $request->get('one_signal_id');
        $status = $request->get('status');

        if ($status) {
            if (!OneSignalDevice::where('one_signal_id','=',$oneSignalId)->first()) {
                OneSignalDevice::create([
                    'user_id' => auth()->user()->id,
                    'one_signal_id' => $oneSignalId,
                ]);
            }
        } elseif ($token = OneSignalDevice::where('one_signal_id','=',$oneSignalId)->first()) {
            $token->delete();
        }

        return response()->json(['success' => true]);
    }

}
