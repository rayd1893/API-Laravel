<?php

namespace App\Http\Controllers;

use App\Allocator;
use App\Models\AssignationIntent;
use Illuminate\Http\Client\RequestException;
use App\Http\Requests\UpdateAssignationIntentRequest;

class AssignationIntentController extends Controller
{
    public function update(UpdateAssignationIntentRequest $request, $uuid)
    {
        try {
            $intent = AssignationIntent::findOrFailByUuid($uuid);

            $allocator = new Allocator($intent);
            $assigned = $allocator->assign($request->input('accepted'));

            return response()->json(['assigned' => $assigned]);
        } catch (RequestException $e) {
            logger()->error('failed to process asignment: ' . $uuid, [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            return response()->json(['assigned' => false]);
        }
    }
}
