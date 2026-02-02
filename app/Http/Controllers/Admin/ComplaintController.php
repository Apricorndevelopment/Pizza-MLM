<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('user')->latest();

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by Ticket ID or User Name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhere('subject', 'like', "%$search%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%$search%")
                            ->orWhere('ulid', 'like', "%$search%");
                    });
            });
        }

        $complaints = $query->paginate(10);

        return view('admin.complaints.index', compact('complaints'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'admin_reply' => 'nullable|string|max:2000'
        ]);

        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'status' => $request->status,
            'admin_reply' => $request->admin_reply,
        ]);

        return back()->with('success', 'Ticket #' . $id . ' updated successfully.');
    }
}
