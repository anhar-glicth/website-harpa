<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\ProgramGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Campaign::where('status', 'approved');

        if ($category) {
            $query->where('category', $category);
        }

        $campaigns = $query->latest()->paginate(9);
        $categories = Campaign::where('status', 'approved')->distinct()->pluck('category')->filter();

        return view('campaigns.index', compact('campaigns', 'categories', 'category'));
    }

    public function show($id)
    {
        $campaign = Campaign::with(['donations' => function ($q) {
            $q->where('payment_status', 'Success')->latest();
        }, 'updates' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        $percent = $campaign->target_amount > 0 ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100) : 0;

        return view('campaigns.show', compact('campaign', 'percent'));
    }

    public function create()
    {
        $groups = ProgramGroup::all();
        return view('campaigns.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'target_amount' => 'required|numeric|min:1000',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'category', 'target_amount']);
        $data['author_id'] = Auth::id();
        $data['status'] = Auth::user()->hasAdminAccess() ? 'approved' : 'pending';

        $group = ProgramGroup::where('name', $request->category)->first();
        if ($group) {
            $data['group_id'] = $group->id;
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('campaigns', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        Campaign::create($data);

        if (Auth::user()->hasAdminAccess()) {
            return redirect()->route('admin.campaigns')->with('success', 'Kampanye berhasil dibuat dan langsung disetujui.');
        }

        return redirect()->route('campaigns.index')->with('success', 'Kampanye berhasil diajukan dan sedang menunggu verifikasi.');
    }
}
