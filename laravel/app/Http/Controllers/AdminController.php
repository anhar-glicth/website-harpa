<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Distribution;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Volunteer;
use App\Models\ContactMessage;
use App\Models\Zakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDonationAmount = Donation::where('payment_status', 'Success')->sum('amount');
        $totalZakatAmount = Zakat::where('payment_status', 'Success')->sum('amount');
        $activeCampaigns = Campaign::where('status', 'approved')->count();
        $pendingCampaigns = Campaign::where('status', 'pending')->count();
        $totalDonors = User::where('role', 'user')->count();
        $pendingDonations = Donation::where('payment_status', 'Pending')->whereNotNull('proof_image')->count();

        // Data for Charts
        // 1. Monthly Donation Chart
        $monthlyData = Donation::where('payment_status', 'Success')
            ->select(DB::raw('MONTH(donation_date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy(DB::raw('MONTH(donation_date)'))
            ->orderBy('month')
            ->get();

        // 2. Category Donation Chart
        $categoryData = DB::table('donations')
            ->join('campaigns', 'donations.campaign_id', '=', 'campaigns.id')
            ->where('donations.payment_status', 'Success')
            ->select('campaigns.category', DB::raw('SUM(donations.amount) as total'))
            ->groupBy('campaigns.category')
            ->get();

        $recentTransactions = Donation::with('campaign')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalDonationAmount',
            'totalZakatAmount',
            'activeCampaigns',
            'pendingCampaigns',
            'totalDonors',
            'pendingDonations',
            'monthlyData',
            'categoryData',
            'recentTransactions'
        ));
    }

    public function campaigns()
    {
        $campaigns = Campaign::with('author')->latest()->paginate(10);
        return view('admin.campaigns', compact('campaigns'));
    }

    public function approveCampaign($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Kampanye berhasil disetujui.');
    }

    public function rejectCampaign($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Kampanye ditolak.');
    }

    public function donations()
    {
        $donations = Donation::with(['campaign', 'paymentVia'])->latest()->paginate(15);
        return view('admin.donations', compact('donations'));
    }

    public function verifyDonation(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Success,Failed',
            'admin_note' => 'nullable|string',
        ]);

        $donation = Donation::findOrFail($id);
        $oldStatus = $donation->payment_status;

        $donation->update([
            'payment_status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // If verified successfully, update the campaign current_amount
        if ($request->status === 'Success' && $oldStatus !== 'Success') {
            $campaign = $donation->campaign;
            $campaign->increment('current_amount', $donation->amount);
        }

        return redirect()->back()->with('success', 'Verifikasi donasi berhasil disimpan.');
    }

    public function distributions()
    {
        $distributions = Distribution::with('campaign')->latest()->paginate(10);
        $campaigns = Campaign::where('status', 'approved')->get();
        return view('admin.distributions', compact('distributions', 'campaigns'));
    }

    public function storeDistribution(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:campaigns,id',
            'recipient_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'distribution_date' => 'required|date',
            'notes' => 'nullable|string',
            'asnaf' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
        ]);

        Distribution::create($request->all());

        return redirect()->back()->with('success', 'Distribusi bantuan berhasil dicatat.');
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function settings()
    {
        $settings = SiteSetting::first();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string',
            'hero_subtitle' => 'required|string',
            'about_mission' => 'required|string',
            'impact_title' => 'required|string',
            'hero_image' => 'nullable|image|max:2048',
            'about_image' => 'nullable|image|max:2048',
            'impact_image' => 'nullable|image|max:2048',
        ]);

        $settings = SiteSetting::first() ?? new SiteSetting();
        $settings->hero_title = $request->hero_title;
        $settings->hero_subtitle = $request->hero_subtitle;
        $settings->about_mission = $request->about_mission;
        $settings->impact_title = $request->impact_title;

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('settings', 'public');
            $settings->hero_image = '/storage/' . $path;
        }
        if ($request->hasFile('about_image')) {
            $path = $request->file('about_image')->store('settings', 'public');
            $settings->about_image = '/storage/' . $path;
        }
        if ($request->hasFile('impact_image')) {
            $path = $request->file('impact_image')->store('settings', 'public');
            $settings->impact_image = '/storage/' . $path;
        }

        $settings->save();

        return redirect()->back()->with('success', 'Pengaturan tampilan berhasil diperbarui.');
    }

    public function userMonitoring($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'field_worker') {
            return redirect()->route('admin.users')->with('error', 'Hanya petugas lapangan yang dapat dipantau.');
        }

        $attendances = \App\Models\Attendance::where('user_id', $id)->latest()->paginate(10, ['*'], 'attendance_page');
        $todos = \App\Models\TodoList::where('user_id', $id)->latest()->paginate(10, ['*'], 'todo_page');
        $myCampaigns = Campaign::where('author_id', $id)->latest()->get();
        $myDistributions = Distribution::with('campaign')->where('worker_id', $id)->latest()->get();

        $totalCampaigns = $myCampaigns->count();
        $totalFunds = $myCampaigns->sum('current_amount');
        $presentDays = \App\Models\Attendance::where('user_id', $id)->where('type', 'check_in')->count();
        $totalTasks = \App\Models\TodoList::where('user_id', $id)->count();
        $completedTasks = \App\Models\TodoList::where('user_id', $id)->where('status', 'completed')->count();
        $effectiveness = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $stats = [
            'totalCampaigns' => $totalCampaigns,
            'totalFunds' => $totalFunds,
            'presentDays' => $presentDays,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'effectiveness' => $effectiveness
        ];

        return view('admin.monitoring', compact('user', 'attendances', 'todos', 'myCampaigns', 'myDistributions', 'stats'));
    }

    public function assignTask(Request $request, $id)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $user = User::findOrFail($id);

        \App\Models\TodoList::create([
            'user_id' => $user->id,
            'assigned_by' => auth()->id(),
            'task' => $request->task,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil diberikan kepada petugas.');
    }

    public function attendanceList()
    {
        $attendances = \App\Models\Attendance::with('user')->latest()->paginate(15);
        return view('admin.attendance', compact('attendances'));
    }

    public function taskList()
    {
        $todos = \App\Models\TodoList::with(['user', 'assigner'])->latest()->paginate(15);
        $workers = User::where('role', 'field_worker')->get();
        return view('admin.tasks', compact('todos', 'workers'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'task' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        \App\Models\TodoList::create([
            'user_id' => $request->user_id,
            'assigned_by' => auth()->id(),
            'task' => $request->task,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil diberikan.');
    }

    public function performanceList()
    {
        $workers = User::where('role', 'field_worker')->get();
        
        $workerPerformance = [];
        foreach ($workers as $worker) {
            $myCampaigns = Campaign::where('author_id', $worker->id)->latest()->get();
            $myDistributions = Distribution::where('worker_id', $worker->id)->latest()->get();
            $todos = \App\Models\TodoList::where('user_id', $worker->id)->get();
            
            $totalCampaigns = $myCampaigns->count();
            $totalFunds = $myCampaigns->sum('current_amount');
            $presentDays = \App\Models\Attendance::where('user_id', $worker->id)->where('type', 'check_in')->count();
            $totalTasks = $todos->count();
            $completedTasks = $todos->where('status', 'completed')->count();
            $effectiveness = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            
            $workerPerformance[] = [
                'worker' => $worker,
                'totalCampaigns' => $totalCampaigns,
                'totalFunds' => $totalFunds,
                'presentDays' => $presentDays,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'effectiveness' => $effectiveness,
                'latest_attendance' => \App\Models\Attendance::where('user_id', $worker->id)->latest()->first()
            ];
        }

        return view('admin.performance', compact('workerPerformance'));
    }
}
