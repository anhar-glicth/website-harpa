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
use App\Models\Prospect;
use App\Models\ProspectResponse;
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

        // 1. Analisa Pegawai & Kegiatan
        $totalStaff = User::where('role', 'field_worker')->count();
        $totalTasks = \App\Models\TodoList::count();
        $completedTasks = \App\Models\TodoList::where('status', 'completed')->count();
        $presentToday = \App\Models\Attendance::where('type', 'check_in')
            ->whereDate('created_at', today())
            ->distinct('user_id')
            ->count('user_id');

        // 2. Analisa Berita
        $totalNews = \App\Models\News::count();

        // 3. Analisa Penyaluran
        $totalDistributionsCount = Distribution::count();
        $totalFundsDistributed = Distribution::sum('amount');

        // 4. Analisa Pegawai Chart Data (Kinerja/Efektivitas Tugas)
        $workers = User::where('role', 'field_worker')->get();
        $staffChartData = [];
        foreach ($workers as $worker) {
            $totalWTasks = \App\Models\TodoList::where('user_id', $worker->id)->count();
            $completedWTasks = \App\Models\TodoList::where('user_id', $worker->id)->where('status', 'completed')->count();
            $eff = $totalWTasks > 0 ? round(($completedWTasks / $totalWTasks) * 100) : 0;
            $staffChartData[] = [
                'name' => $worker->name,
                'effectiveness' => $eff
            ];
        }

        return view('admin.dashboard', compact(
            'totalDonationAmount',
            'totalZakatAmount',
            'activeCampaigns',
            'pendingCampaigns',
            'totalDonors',
            'pendingDonations',
            'monthlyData',
            'categoryData',
            'recentTransactions',
            'totalStaff',
            'totalTasks',
            'completedTasks',
            'presentToday',
            'totalNews',
            'totalDistributionsCount',
            'totalFundsDistributed',
            'staffChartData'
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
            'stats_beneficiaries' => 'nullable|string|max:100',
            'stats_projects' => 'nullable|string|max:100',
            'stats_volunteers' => 'nullable|string|max:100',
        ]);

        $settings = SiteSetting::first() ?? new SiteSetting();
        $settings->hero_title = $request->hero_title;
        $settings->hero_subtitle = $request->hero_subtitle;
        $settings->about_mission = $request->about_mission;
        $settings->impact_title = $request->impact_title;
        $settings->stats_beneficiaries = $request->stats_beneficiaries;
        $settings->stats_projects = $request->stats_projects;
        $settings->stats_volunteers = $request->stats_volunteers;

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

    public function analytics()
    {
        // 1. RFM KPI & Segmentation Statistics
        $totalDonors = User::where('role', 'user')->count();
        $maleDonors = User::where('role', 'user')->where('gender', 'L')->count();
        $femaleDonors = User::where('role', 'user')->where('gender', 'P')->count();
        $unknownDonors = User::where('role', 'user')->whereNotIn('gender', ['L', 'P'])->count();

        // RFM All Time Segmentation Chart Data
        $rfmSegmentCounts = User::where('role', 'user')
            ->select('rfm_alltime', \DB::raw('count(*) as count'))
            ->whereNotNull('rfm_alltime')
            ->groupBy('rfm_alltime')
            ->get();

        // Top 20 Donors based on transaction amount in Laravel
        $top20Donors = User::where('role', 'user')
            ->withSum(['donations' => function($q) {
                $q->where('payment_status', 'Success');
            }], 'amount')
            ->withCount(['donations' => function($q) {
                $q->where('payment_status', 'Success');
            }])
            ->orderByDesc('donations_sum_amount')
            ->take(20)
            ->get();

        // Calculate global totals for donor contribution percentages
        $totalGlobalDonations = Donation::where('payment_status', 'Success')->sum('amount');

        // 2. CRM Prospects Statistics
        $totalProspects = Prospect::count();
        
        // Count prospects converted (has at least one 'Tertarik' response)
        $convertedProspects = Prospect::whereHas('responses', function($q) {
            $q->where('status', 'Tertarik');
        })->count();

        // Count prospects follow-up (has latest response status as 'Follow Up')
        $followUpProspects = Prospect::whereHas('responses', function($q) {
            $q->where('status', 'Follow Up');
        })->count();

        // Total potential fund fallback
        $totalPotentialFunds = 125000000; // standard potential funds aggregate fallback
        
        // Retrieve prospects log list
        $recentProspectResponses = ProspectResponse::with('prospect')
            ->latest()
            ->take(15)
            ->get();

        // Top 20 prospects
        $top20Prospects = Prospect::with('responses')->latest()->take(20)->get();

        // 3. Distributions and Campaigns Analytics
        $totalDistributions = Distribution::sum('amount');
        $distributionCount = Distribution::count();
        $recentDistributions = Distribution::with('campaign')->latest()->take(10)->get();

        return view('admin.analytics', compact(
            'totalDonors', 'maleDonors', 'femaleDonors', 'unknownDonors', 'rfmSegmentCounts', 'top20Donors', 'totalGlobalDonations',
            'totalProspects', 'convertedProspects', 'followUpProspects', 'totalPotentialFunds', 'recentProspectResponses', 'top20Prospects',
            'totalDistributions', 'distributionCount', 'recentDistributions'
        ));
    }
}
