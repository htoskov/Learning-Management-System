<?php
namespace App\Http\Controllers;

use App\Models\ProjectSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $registeredUsers = User::count();

        // Paid users = users who have at least one payment row with is_paid = 1
        $paidUsers = User::query()
            ->whereHas('payments', fn($q) => $q->where('is_paid', 1))
            ->count();

        $unpaidUsers = $registeredUsers - $paidUsers;

        $totalSales = DB::table('payments')->where('is_paid', 1)->count();

        $moneyGenerated = (int) DB::table('payments')
            ->where('is_paid', 1)
            ->sum('amount_total');

        $q = trim((string) $request->get('q', ''));
        $usersList = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get(['id','name','email','trophies','video_progress','updated_at']);

        $selectedUserId = $request->integer('user_id') ?: ($usersList->first()->id ?? null);

        $selectedUser = null;
        $selectedPayment = null;
        $selectedSubmission = null;

        if ($selectedUserId) {
            $selectedUser = User::query()->find($selectedUserId);

            // latest payment row (works even if you later allow many)
            $selectedPayment = DB::table('payments')
                ->where('user_id', $selectedUserId)
                ->latest('id')
                ->first();

            $selectedSubmission = ProjectSubmission::with('files')
                ->where('user_id', $selectedUserId)
                ->latest('id')
                ->first();
        }

        return view('admin.dashboard', compact(
            'registeredUsers',
            'paidUsers',
            'unpaidUsers',
            'totalSales',
            'moneyGenerated',
            'q',
            'usersList',
            'selectedUserId',
            'selectedUser',
            'selectedPayment',
            'selectedSubmission',
        ));
    }
}
