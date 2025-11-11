<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SupportController extends Controller
{
    /** ✅ Customer – Ticket List */
    public function index()
    {
        $tickets = Support::with('order')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets
        ]);
    }

    /** ✅ Customer – Create Ticket Form */
    public function create()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();

        return Inertia::render('tickets/Create', [
            'orders' => $orders
        ]);
    }

    /** ✅ Customer – Store Ticket */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Support::create([
            'user_id'   => Auth::id(),
            'order_id'  => $request->order_id,
            'subject'   => $request->subject,
            'category'  => $request->category,
            'message'   => $request->message,
            'status'    => 'open',
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /** ✅ Customer – View Single Ticket */
    public function show(Support $ticket)
    {
        if ($ticket->user_id !== Auth::id()) abort(403);

        return Inertia::render('tickets/Show', [
            'ticket' => $ticket
        ]);
    }

    /** ✅ Admin – View All Tickets */
    public function adminIndex()
    {
        $tickets = Support::with(['user', 'order'])->latest()->paginate(10);

        return Inertia::render('support/Index', [
            'tickets' => $tickets
        ]);
    }
    /** ✅ Admin – View Single Ticket */
    public function adminShow(Support $ticket)
    {
        return Inertia::render('support/Show', [
            'ticket' => $ticket->load(['user', 'order'])
        ]);
    }

    /** ✅ Admin – Change Status */
    public function updateStatus(Request $request, Support $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,working,closed'
        ]);

        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }

    /** ✅ Admin – Reply */
    public function adminReply(Request $request, Support $ticket)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $ticket->update([
            'reply' => $request->reply,
            'status' => 'working'
        ]);

        return back()->with('success', 'Reply added.');
    }
}
