<?php

namespace App\Services;

use App\Models\Participant;

class ResponseService
{
    public function createparticipant($quiz, $email = null, $name = null) {
        if (auth()->check()) {
            $existingParticipant = Participant::where('user_id', auth()->id())->first();
        }else{
            $existingParticipant = Participant::where('email', $email)->first();
        }
        if ($existingParticipant) {
            $participant = $existingParticipant;
        } else {
            $participant = $quiz->participants()->create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'participant_name' => auth()->check() ? auth()->user()->name : $name,
                'email' => auth()->check() ? auth()->user()->email : $email,
                'submitted_at' => now(),
            ]);
        }

        if ($participant->quiz_id != $quiz->id || $participant->email != $email) {
            $participant->update([
                'started_at' => now(),
            ]);
        }

        return $participant;
    }

}
