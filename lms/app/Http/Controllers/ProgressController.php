<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'video_number' => ['required','integer','min:1','max:8'],
            'seconds'      => ['required','integer','min:0'],
            'duration'     => ['nullable','integer','min:0'],
            'completed'    => ['nullable','integer','in:0,1'],
        ]);

        $user = $request->user();

        $videoNumber = (int) $data['video_number'];
        $seconds     = (int) $data['seconds'];
        $duration    = isset($data['duration']) ? (int) $data['duration'] : 0;
        $completed   = !empty($data['completed']);

        // Clamp seconds to duration if provided
        if ($duration > 0) {
            $seconds = min($seconds, $duration);
        }

        // Only allow saving time for the currently unlocked video (MVP linear course)
        $currentUnlocked = (int) ($user->video_progress ?? 1);

        if ($videoNumber > $currentUnlocked) {
            return response()->json([
                'ok' => true,
                'video_progress' => (int) $user->video_progress,
                'trophies' => (int) $user->trophies,
                'trophies_awarded' => 0,
            ]);
        }

        // allow resume time save even if user is watching an older video
        $user->video_time_seconds = $seconds;

        // only allow completion/unlock for the current unlocked video
        if ($videoNumber !== $currentUnlocked) {
            $user->save();

            return response()->json([
                'ok' => true,
                'video_progress' => (int) $user->video_progress,
                'trophies' => (int) $user->trophies,
                'trophies_awarded' => 0,
            ]);
        }

        // Save resume time
        $user->video_time_seconds = $seconds;

        // Decide completion (ended or >=90% if duration known)
        $isComplete = $completed;
        if (!$isComplete && $duration > 0) {
            $isComplete = ($seconds >= (int) floor($duration * 0.90));
        }

        $trophiesAwarded = 0;

        if ($isComplete) {
            // Unlock next video (linear)
            $next = min(8, $currentUnlocked + 1);
            if ($next > $currentUnlocked) {
                $user->video_progress = $next;
                $user->video_time_seconds = 0; // start next at 0
            }

            // Award trophies
            $award = 10;
            $before = (int) ($user->trophies ?? 0);
            $after  = min(100, $before + $award);

            $user->trophies = $after;
            $trophiesAwarded = max(0, $after - $before);
        }

        $user->save();

        return response()->json([
            'ok' => true,
            'video_progress' => (int) $user->video_progress,
            'trophies' => (int) $user->trophies,
            'trophies_awarded' => (int) $trophiesAwarded,
        ]);
    }
}
