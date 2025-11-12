<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userId = auth('sanctum')->id();
        if ($userId === null) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }
    }
}
