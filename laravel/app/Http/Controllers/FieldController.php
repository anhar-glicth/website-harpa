<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TodoList;
use App\Models\Prospect;
use App\Models\ProspectResponse;
use App\Models\PicTarget;
use App\Models\Campaign;
use App\Models\News;
use App\Models\Distribution;
use App\Models\CampaignUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FieldController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        // 1. Attendance status
        $todayAttendance = Attendance::where('user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->get();

        // 2. Todos
        $todos = TodoList::where('user_id', $userId)->latest()->get();
        
        // 3. Target
        $target = PicTarget::where('pic_id', $userId)
            ->whereYear('month_period', now()->year)
            ->whereMonth('month_period', now()->month)
            ->first();

        // 4. Staff-specific lists matching Vue
        $myCampaigns = Campaign::where('author_id', $userId)->latest()->get();
        $myNews = News::where('author_id', $userId)->latest()->get();
        $myDistributions = Distribution::with('campaign')->where('worker_id', $userId)->latest()->get();

        // 5. Calculate effectiveness and other performance stats
        $totalCampaigns = $myCampaigns->count();
        $totalNews = $myNews->count();
        $totalFunds = $myCampaigns->sum('current_amount');
        $presentDays = Attendance::where('user_id', $userId)->where('type', 'check_in')->count();
        $totalTasks = $todos->count();
        $completedTasks = $todos->where('status', 'completed')->count();
        $effectiveness = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $stats = [
            'totalCampaigns' => $totalCampaigns,
            'totalContent' => $totalNews,
            'totalFunds' => $totalFunds,
            'presentDays' => $presentDays,
            'effectiveness' => $effectiveness
        ];

        // All campaigns for dropdown selection in distribution reporting
        $allCampaigns = Campaign::where('status', 'approved')->latest()->get();

        return view('field.dashboard', compact(
            'todayAttendance', 'todos', 'target', 
            'myCampaigns', 'myNews', 'myDistributions', 'stats', 'allCampaigns'
        ));
    }

    public function attendanceStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:check_in,check_out,sakit,izin',
            'location' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'attachment' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['type', 'location', 'latitude', 'longitude']);
        $data['user_id'] = Auth::id();

        // Koordinat dinamis dari browser GPS
        if (!empty($data['latitude']) && !empty($data['longitude'])) {
            if (empty($data['location'])) {
                $data['location'] = 'Lokasi Terdeteksi (' . round($data['latitude'], 4) . ', ' . round($data['longitude'], 4) . ')';
            }
        } else {
            $data['latitude'] = null;
            $data['longitude'] = null;
            if (empty($data['location'])) {
                $data['location'] = 'Lokasi GPS Tidak Terdeteksi';
            }
        }

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attendance', 'public');
            $data['attachment'] = '/storage/' . $path;
        }

        $att = Attendance::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat.',
                'data' => $att
            ]);
        }

        return redirect()->back()->with('success', 'Absensi berhasil dicatat.');
    }

    public function todoStore(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        TodoList::create([
            'user_id' => Auth::id(),
            'task' => $request->task,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function todoUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed',
            'result_attachment' => 'nullable|image|max:2048',
            'result_note' => 'nullable|string',
        ]);

        $todo = TodoList::where('user_id', Auth::id())->findOrFail($id);
        
        $data = ['status' => $request->status];
        if ($request->status === 'completed') {
            $data['completed_at'] = now();
            $data['result_note'] = $request->result_note;
        } else {
            $data['completed_at'] = null;
            $data['result_note'] = null;
        }

        if ($request->hasFile('result_attachment')) {
            $path = $request->file('result_attachment')->store('todos', 'public');
            $data['result_attachment'] = '/storage/' . $path;
        }

        $todo->update($data);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function prospects()
    {
        $prospects = Prospect::with('responses')->latest()->paginate(10);
        return view('field.prospects', compact('prospects'));
    }

    public function storeProspect(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Prospect::create($request->all());

        return redirect()->back()->with('success', 'Prospek baru berhasil ditambahkan.');
    }

    public function storeProspectResponse(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
            'status' => 'required|in:Tertarik,Tidak Tertarik,Follow Up',
        ]);

        ProspectResponse::create([
            'prospect_id' => $id,
            'response' => $request->response,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Respon prospek berhasil dicatat.');
    }

    // Additional features matching Vue
    public function campaignStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'category', 'target_amount']);
        $data['author_id'] = Auth::id();
        $data['current_amount'] = 0;
        $data['status'] = 'pending';

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('campaigns', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        Campaign::create($data);

        return redirect()->back()->with('success', 'Kampanye berhasil diajukan untuk disetujui admin.');
    }

    public function campaignUpdateStore(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'campaign_id' => $id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('updates', 'public');
            $data['image'] = '/storage/' . $path;
        }

        CampaignUpdate::create($data);

        return redirect()->back()->with('success', 'Kabar terbaru berhasil dikirim.');
    }

    public function newsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tag' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'author_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'tag' => $request->tag,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = '/storage/' . $path;
        }

        News::create($data);

        return redirect()->back()->with('success', 'Berita berhasil diterbitkan.');
    }

    public function distributionStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_name' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'maps_link' => 'nullable|url',
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:0',
            'recipient_name' => 'required|string',
            'items_distributed' => 'required|string',
        ]);

        $data = [
            'worker_id' => Auth::id(),
            'program_id' => $request->campaign_id,
            'title' => $request->title,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_name' => $request->location_name,
            'maps_link' => $request->maps_link,
            'amount' => $request->amount,
            'recipient_name' => $request->recipient_name,
            'items_distributed' => $request->items_distributed,
            'distribution_date' => now(),
            'status' => 'Tersalur',
        ];

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('distributions', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        Distribution::create($data);

        return redirect()->back()->with('success', 'Laporan penyaluran berhasil dikirim.');
    }

    public function storeManualDonation(Request $request)
    {
        $request->validate([
            'campaign_id'    => 'required|exists:campaigns,id',
            'donor_name'     => 'required|string|max:255',
            'amount'         => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
        ]);

        \App\Models\Donation::create([
            'campaign_id'    => $request->campaign_id,
            'user_id'        => Auth::id(),
            'worker_id'      => Auth::id(),
            'donor_name'     => $request->donor_name,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method ?? 'Cash (Jemput Donasi)',
            'payment_status' => 'Success',
            'donation_date'  => now(),
            'admin_note'     => 'Di-input oleh Petugas Lapangan: ' . Auth::user()->name,
        ]);

        Campaign::find($request->campaign_id)->increment('current_amount', $request->amount);

        return redirect()->back()->with('success', 'Donasi manual berhasil di-input oleh petugas.');
    }
}
