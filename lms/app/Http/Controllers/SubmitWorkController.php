<?php

namespace App\Http\Controllers;

use App\Models\ProjectSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubmitWorkController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $submission = ProjectSubmission::with('files')
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        return view('submit-work', [
            'submission' => $submission,
            'trophies' => (int) ($user->trophies ?? 0),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $trophies = (int) ($user->trophies ?? 0);

        // Gate: allow page, deny upload/submit until 80/100
        if ($trophies < 80) {
            return back()->withErrors([
                'gate' => 'За да предадеш проекта си, трябва да имаш поне 80 / 100 трофея.',
            ]);
        }

        // Find or create draft/submission for user
        $submission = ProjectSubmission::where('user_id', $user->id)->latest('id')->first();

        if ($submission && $submission->status === 'approved') {
            return back()->withErrors([
                'gate' => 'Проектът вече е одобрен. Не можеш да качваш нови файлове.',
            ]);
        }

        // Validation
        $data = $request->validate([
            'note' => ['required','string','min:10','max:5000'],
            'link' => ['nullable','url','max:2000'],
            'files' => ['nullable','array','max:10'],
            'files.*' => [
                'file',
                'max:10240', // 10MB in KB
                'mimetypes:'
                    .'image/jpeg,image/png,image/webp,'
                    .'video/mp4,video/webm,'
                    .'application/pdf,'
                    .'application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,'
                    .'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,'
                    .'text/plain,'
                    .'application/octet-stream'
            ],
        ]);

        if (!$submission) {
            $submission = ProjectSubmission::create([
                'user_id' => $user->id,
                'status' => 'draft',
            ]);
        }

        // Save note + link
        $links = $submission->links_json ?? [];
        if (!empty($data['link'])) {
            $links[] = $data['link'];
            // de-dupe
            $links = array_values(array_unique($links));
        }

        $submission->note = $data['note'];
        $submission->links_json = $links;

        // Store files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $mime = $file->getMimeType() ?? '';
                $type = $this->mapType($mime, $file->getClientOriginalExtension());

                $path = $file->store('project-submissions/'.$user->id, 'public');

                $submission->files()->create([
                    'type' => $type,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => (int) $file->getSize(),
                    'mime' => $mime,
                ]);
            }
        }

        // Mark as submitted
        $submission->status = 'submitted';
        $submission->submitted_at = now();
        $submission->save();

        // Trophies jump to 90/100 on submit (minimum)
        $user->trophies = min(100, max((int)($user->trophies ?? 0), 90));
        $user->save();

        return redirect()->route('submit-work.show')->with('status', 'project-submitted');
    }

    private function mapType(string $mime, ?string $ext): string
    {
        $ext = strtolower((string) $ext);

        if (Str::startsWith($mime, 'image/')) return 'image';
        if (Str::startsWith($mime, 'video/')) return 'video';
        if ($mime === 'application/pdf') return 'pdf';
        if (in_array($ext, ['doc','docx'], true)) return 'doc';
        if (in_array($ext, ['xls','xlsx'], true)) return 'excel';
        if ($mime === 'text/plain') return 'txt';

        return 'other';
    }
}
