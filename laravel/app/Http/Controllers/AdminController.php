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
use App\Models\Karyawan;
use App\Models\Office;
use App\Models\DonorChannel;
use App\Models\ProgramGroup;
use App\Models\Program;
use App\Models\PaymentVia;
use App\Models\ProspectMedia;
use App\Models\ProspectMethod;
use App\Models\ProspectType;
use App\Models\Fundraiser;
use App\Models\MasterProspectResponse;
use App\Models\MasterTarget;
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

    public function distributionDashboard(Request $request)
    {
        $totalNominal       = Distribution::sum('amount');
        $totalRecords       = Distribution::count();
        $thisMonthNominal   = Distribution::whereMonth('distribution_date', now()->month)
                                         ->whereYear('distribution_date', now()->year)
                                         ->sum('amount');
        $totalBeneficiaries = Distribution::sum('pm_count');

        $statusTersalur     = Distribution::where('status', 'Tersalur')->count();
        $statusProses       = Distribution::where('status', 'Proses')->count();

        $recentDistributions = Distribution::with('campaign')->latest()->take(8)->get();

        $topAsnaf = Distribution::select('asnaf', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as total_count'))
                                ->whereNotNull('asnaf')
                                ->groupBy('asnaf')
                                ->orderBy('total_amount', 'desc')
                                ->get();

        return view('admin.distribution_dashboard', [
            'totalNominal'        => $totalNominal,
            'totalRecords'        => $totalRecords,
            'thisMonthNominal'    => $thisMonthNominal,
            'totalBeneficiaries'  => $totalBeneficiaries,
            'statusTersalur'      => $statusTersalur,
            'statusProses'        => $statusProses,
            'recentDistributions' => $recentDistributions,
            'topAsnaf'            => $topAsnaf,
            'active'              => 'distribution_dashboard',
        ]);
    }

    public function distributions(Request $request)
    {
        $query = Distribution::with('campaign');

        // Search: ID Penyaluran / Nama / Kontak PIC / Program / Alamat / City / Province
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('pic_phone', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orWhereHas('campaign', function ($cq) use ($search) {
                      $cq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Tgl Penyaluran range
        if ($request->filled('start_date')) {
            $query->whereDate('distribution_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('distribution_date', '<=', $request->end_date);
        }

        // Advance search extra filters
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        if ($request->filled('province')) {
            $query->where('province', 'like', "%{$request->province}%");
        }
        if ($request->filled('asnaf')) {
            $query->where('asnaf', $request->asnaf);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalFilteredAmount = (clone $query)->sum('amount');
        $distributions = $query->orderBy('distribution_date', 'desc')->paginate(10)->withQueryString();
        $campaigns = Campaign::where('status', 'approved')->get();

        return view('admin.distributions', [
            'distributions'       => $distributions,
            'campaigns'           => $campaigns,
            'totalFilteredAmount' => $totalFilteredAmount,
            'active'              => 'distributions',
        ]);
    }

    public function storeDistribution(Request $request)
    {
        $request->validate([
            'distribution_code' => 'nullable|string|max:50',
            'program_id'        => 'required|exists:campaigns,id',
            'recipient_name'    => 'required|string|max:255',
            'pic_name'          => 'nullable|string|max:255',
            'pic_phone'         => 'nullable|string|max:50',
            'amount'            => 'required|numeric|min:1000',
            'distribution_date' => 'required|date',
            'notes'             => 'nullable|string',
            'asnaf'             => 'nullable|string',
            'city'              => 'nullable|string',
            'province'          => 'nullable|string',
            'status'            => 'nullable|in:Proses,Tersalur',
        ]);

        $data = $request->all();
        if (empty($data['distribution_code'])) {
            $data['distribution_code'] = 'DIS-' . date('Ym') . '-' . rand(100, 999);
        }
        if (empty($data['pic_name'])) {
            $data['pic_name'] = $data['recipient_name'];
        }

        Distribution::create($data);

        return redirect()->back()->with('success', 'Distribusi bantuan berhasil dicatat.');
    }

    public function updateDistribution(Request $request, $id)
    {
        $dis = Distribution::findOrFail($id);

        $request->validate([
            'distribution_code' => 'nullable|string|max:50',
            'program_id'        => 'required|exists:campaigns,id',
            'recipient_name'    => 'required|string|max:255',
            'pic_name'          => 'nullable|string|max:255',
            'pic_phone'         => 'nullable|string|max:50',
            'amount'            => 'required|numeric|min:1000',
            'distribution_date' => 'required|date',
            'notes'             => 'nullable|string',
            'asnaf'             => 'nullable|string',
            'city'              => 'nullable|string',
            'province'          => 'nullable|string',
            'status'            => 'nullable|in:Proses,Tersalur',
        ]);

        $dis->update($request->all());

        return redirect()->back()->with('success', 'Data penyaluran berhasil diperbarui.');
    }

    public function deleteDistribution($id)
    {
        $dis = Distribution::findOrFail($id);
        $dis->delete();

        return redirect()->back()->with('success', 'Data penyaluran berhasil dihapus.');
    }

    public function exportDistributions(Request $request)
    {
        $query = Distribution::with('campaign');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('pic_phone', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
            });
        }
        if ($request->filled('start_date')) {
            $query->whereDate('distribution_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('distribution_date', '<=', $request->end_date);
        }

        $distributions = $query->orderBy('distribution_date', 'desc')->get();

        $filename = 'List_Penyaluran_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($distributions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No', 'ID Penyaluran', 'Tgl Penyaluran', 'Program', 'Nominal', 'Nama PIC', 'Kontak PIC', 'Tgl Insert', 'Kota/Kab', 'Provinsi', 'Status'
            ]);

            $total = 0;
            foreach ($distributions as $index => $row) {
                $total += $row->amount;
                fputcsv($file, [
                    $index + 1,
                    $row->distribution_code ?: ('DIS-' . $row->id),
                    $row->distribution_date ? $row->distribution_date->format('Y-m-d') : '',
                    $row->campaign ? $row->campaign->title : 'Program Umum',
                    $row->amount,
                    $row->pic_name ?: $row->recipient_name,
                    $row->pic_phone ?: '-',
                    $row->created_at ? $row->created_at->format('Y-m-d H:i') : '',
                    $row->city ?: '-',
                    $row->province ?: '-',
                    $row->status ?: 'Proses',
                ]);
            }

            fputcsv($file, ['TOTAL', '', '', '', $total, '', '', '', '', '', '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importDistributions(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Header row
        $header = fgetcsv($handle);
        
        $importedCount = 0;
        $firstCampaign = Campaign::where('status', 'approved')->first();
        $defaultCampaignId = $firstCampaign ? $firstCampaign->id : 1;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row) || count($row) < 2) continue;

            $code  = !empty($row[1]) ? $row[1] : ('DIS-' . date('Ym') . '-' . rand(100,999));
            $tgl   = !empty($row[2]) ? date('Y-m-d', strtotime($row[2])) : date('Y-m-d');
            $nom   = isset($row[4]) && is_numeric($row[4]) ? floatval($row[4]) : 100000;
            $pic   = $row[5] ?? 'PIC Penyaluran';
            $phone = $row[6] ?? '-';
            $city  = $row[8] ?? '-';
            $prov  = $row[9] ?? '-';

            if ($code === 'TOTAL' || $code === 'No' || $code === 'ID Penyaluran') continue;

            Distribution::create([
                'distribution_code' => $code,
                'program_id'        => $defaultCampaignId,
                'recipient_name'    => $pic,
                'pic_name'          => $pic,
                'pic_phone'         => $phone,
                'amount'            => $nom,
                'distribution_date' => $tgl,
                'city'              => $city,
                'province'          => $prov,
                'status'            => 'Tersalur',
            ]);

            $importedCount++;
        }

        fclose($handle);

        return redirect()->back()->with('success', "Berhasil mengimpor {$importedCount} data penyaluran.");
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

    // ==========================================
    // MASTER KARYAWAN MANAGEMENT
    // ==========================================

    public function karyawanList(Request $request)
    {
        $query = Karyawan::with(['office', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('karyawan_code', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawans = $query->latest()->paginate(10)->withQueryString();
        $offices = Office::orderBy('name')->get();
        $parentOptions = Karyawan::orderBy('nama_lengkap')->get();

        return view('admin.karyawan', compact('karyawans', 'offices', 'parentOptions'));
    }

    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'karyawan_code' => 'required|string|max:50|unique:karyawans,karyawan_code',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif',
            'office_id' => 'nullable|exists:offices,id',
            'parent_id' => 'nullable|exists:karyawans,id',
        ]);

        Karyawan::create($request->all());

        return redirect()->back()->with('success', 'Data Karyawan berhasil ditambahkan.');
    }

    public function updateKaryawan(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'karyawan_code' => 'required|string|max:50|unique:karyawans,karyawan_code,' . $id,
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif',
            'office_id' => 'nullable|exists:offices,id',
            'parent_id' => 'nullable|exists:karyawans,id',
        ]);

        $karyawan->update($request->all());

        return redirect()->back()->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    public function deleteKaryawan($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->back()->with('success', 'Data Karyawan berhasil dihapus.');
    }

    public function exportKaryawan(Request $request)
    {
        $query = Karyawan::with(['office', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('karyawan_code', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawans = $query->latest()->get();

        $fileName = 'data_karyawan_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID Karyawan', 'Nama Lengkap', 'Jabatan', 'No. HP', 'Email', 'Alamat', 'Kantor/Kota', 'Karyawan Parent (Atasan)', 'Status', 'Tanggal Input'];

        $callback = function () use ($karyawans, $columns) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Microsoft Excel
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($karyawans as $item) {
                fputcsv($file, [
                    $item->karyawan_code,
                    $item->nama_lengkap,
                    $item->jabatan,
                    $item->no_hp ?? '-',
                    $item->email ?? '-',
                    $item->alamat ?? '-',
                    $item->office ? $item->office->name : '-',
                    $item->parent ? $item->parent->nama_lengkap : '-',
                    $item->status,
                    $item->created_at ? $item->created_at->format('Y-m-d H:i') : '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==========================================
    // MASTER CHANNEL DONASI MANAGEMENT
    // ==========================================

    public function donorChannelsList(Request $request)
    {
        $query = DonorChannel::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $channels = $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();

        return view('admin.donor_channels', compact('channels'));
    }

    public function storeDonorChannel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        DonorChannel::create($request->all());

        return redirect()->back()->with('success', 'Channel Donasi berhasil ditambahkan.');
    }

    public function updateDonorChannel(Request $request, $id)
    {
        $channel = DonorChannel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $channel->update($request->all());

        return redirect()->back()->with('success', 'Channel Donasi berhasil diperbarui.');
    }

    public function deleteDonorChannel($id)
    {
        $channel = DonorChannel::findOrFail($id);
        $channel->delete();

        return redirect()->back()->with('success', 'Channel Donasi berhasil dihapus.');
    }

    // ===================== MASTER RUMPUN PROGRAM =====================

    public function programGroupsList(Request $request)
    {
        $query = ProgramGroup::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programGroups = $query->latest()->paginate(10)->withQueryString();

        return view('admin.program_groups', [
            'programGroups' => $programGroups,
            'active' => 'program_groups',
        ]);
    }

    public function storeProgramGroup(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50|unique:program_groups,code',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|in:Aktif,Non-Aktif',
        ]);

        ProgramGroup::create($request->only('name', 'code', 'description', 'status'));

        return redirect()->back()->with('success', 'Rumpun Program berhasil ditambahkan.');
    }

    public function updateProgramGroup(Request $request, $id)
    {
        $group = ProgramGroup::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50|unique:program_groups,code,' . $id,
            'description' => 'nullable|string|max:500',
            'status'      => 'required|in:Aktif,Non-Aktif',
        ]);

        $group->update($request->only('name', 'code', 'description', 'status'));

        return redirect()->back()->with('success', 'Rumpun Program berhasil diperbarui.');
    }

    public function deleteProgramGroup($id)
    {
        $group = ProgramGroup::findOrFail($id);
        $group->delete();

        return redirect()->back()->with('success', 'Rumpun Program berhasil dihapus.');
    }

    // ===================== MASTER PROGRAM =====================

    public function programList(Request $request)
    {
        $query = Program::with('programGroup');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('programGroup', fn($g) => $g->where('name', 'like', "%{$keyword}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('program_group_id')) {
            $query->where('program_group_id', $request->program_group_id);
        }

        $programs      = $query->latest()->paginate(10)->withQueryString();
        $programGroups = ProgramGroup::orderBy('name')->get();

        return view('admin.programs', [
            'programs'      => $programs,
            'programGroups' => $programGroups,
            'active'        => 'programs',
        ]);
    }

    public function storeProgram(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'program_group_id' => 'nullable|exists:program_groups,id',
            'description'      => 'nullable|string|max:500',
            'status'           => 'required|in:Aktif,Non-Aktif',
        ]);

        Program::create($request->only('name', 'program_group_id', 'description', 'status'));

        return redirect()->back()->with('success', 'Program berhasil ditambahkan.');
    }

    public function updateProgram(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'program_group_id' => 'nullable|exists:program_groups,id',
            'description'      => 'nullable|string|max:500',
            'status'           => 'required|in:Aktif,Non-Aktif',
        ]);

        $program->update($request->only('name', 'program_group_id', 'description', 'status'));

        return redirect()->back()->with('success', 'Program berhasil diperbarui.');
    }

    public function deleteProgram($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->back()->with('success', 'Program berhasil dihapus.');
    }

    // ===================== MASTER VIA BAYAR =====================

    public function paymentViaList(Request $request)
    {
        $query = PaymentVia::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('provider', 'like', "%{$keyword}%")
                  ->orWhere('account_no', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $paymentVias = $query->latest()->paginate(10)->withQueryString();

        return view('admin.payment_via', [
            'paymentVias' => $paymentVias,
            'active'      => 'payment_via',
        ]);
    }

    public function storePaymentVia(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'provider'   => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:100',
            'status'     => 'required|in:Aktif,Non-Aktif',
        ]);

        PaymentVia::create($request->only('name', 'provider', 'account_no', 'status'));

        return redirect()->back()->with('success', 'Via Bayar berhasil ditambahkan.');
    }

    public function updatePaymentVia(Request $request, $id)
    {
        $via = PaymentVia::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'provider'   => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:100',
            'status'     => 'required|in:Aktif,Non-Aktif',
        ]);

        $via->update($request->only('name', 'provider', 'account_no', 'status'));

        return redirect()->back()->with('success', 'Via Bayar berhasil diperbarui.');
    }

    public function deletePaymentVia($id)
    {
        $via = PaymentVia::findOrFail($id);
        $via->delete();

        return redirect()->back()->with('success', 'Via Bayar berhasil dihapus.');
    }

    // ===================== MASTER MEDIA PROSPEK =====================

    public function prospectMediaList(Request $request)
    {
        $query = ProspectMedia::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prospectMedia = $query->latest()->paginate(10)->withQueryString();

        return view('admin.prospect_media', [
            'prospectMedia' => $prospectMedia,
            'active'        => 'prospect_media',
        ]);
    }

    public function storeProspectMedia(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        ProspectMedia::create($request->only('name', 'status'));

        return redirect()->back()->with('success', 'Media Prospek berhasil ditambahkan.');
    }

    public function updateProspectMedia(Request $request, $id)
    {
        $media = ProspectMedia::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $media->update($request->only('name', 'status'));

        return redirect()->back()->with('success', 'Media Prospek berhasil diperbarui.');
    }

    public function deleteProspectMedia($id)
    {
        $media = ProspectMedia::findOrFail($id);
        $media->delete();

        return redirect()->back()->with('success', 'Media Prospek berhasil dihapus.');
    }

    // ===================== MASTER METODE PROSPEK =====================

    public function prospectMethodsList(Request $request)
    {
        $query = ProspectMethod::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prospectMethods = $query->latest()->paginate(10)->withQueryString();

        return view('admin.prospect_methods', [
            'prospectMethods' => $prospectMethods,
            'active'          => 'prospect_methods',
        ]);
    }

    public function storeProspectMethod(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|in:Aktif,Non-Aktif',
        ]);

        ProspectMethod::create($request->only('name', 'description', 'status'));

        return redirect()->back()->with('success', 'Metode Prospek berhasil ditambahkan.');
    }

    public function updateProspectMethod(Request $request, $id)
    {
        $method = ProspectMethod::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|in:Aktif,Non-Aktif',
        ]);

        $method->update($request->only('name', 'description', 'status'));

        return redirect()->back()->with('success', 'Metode Prospek berhasil diperbarui.');
    }

    public function deleteProspectMethod($id)
    {
        $method = ProspectMethod::findOrFail($id);
        $method->delete();

        return redirect()->back()->with('success', 'Metode Prospek berhasil dihapus.');
    }

    // ===================== MASTER JENIS PROSPEK =====================

    public function prospectTypesList(Request $request)
    {
        $query = ProspectType::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prospectTypes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.prospect_types', [
            'prospectTypes' => $prospectTypes,
            'active'        => 'prospect_types',
        ]);
    }

    public function storeProspectType(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'priority' => 'nullable|in:Tinggi,Sedang,Rendah',
            'status'   => 'required|in:Aktif,Non-Aktif',
        ]);

        ProspectType::create($request->only('name', 'priority', 'status'));

        return redirect()->back()->with('success', 'Jenis Prospek berhasil ditambahkan.');
    }

    public function updateProspectType(Request $request, $id)
    {
        $type = ProspectType::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'priority' => 'nullable|in:Tinggi,Sedang,Rendah',
            'status'   => 'required|in:Aktif,Non-Aktif',
        ]);

        $type->update($request->only('name', 'priority', 'status'));

        return redirect()->back()->with('success', 'Jenis Prospek berhasil diperbarui.');
    }

    public function deleteProspectType($id)
    {
        $type = ProspectType::findOrFail($id);
        $type->delete();

        return redirect()->back()->with('success', 'Jenis Prospek berhasil dihapus.');
    }

    // ===================== MASTER FUNDRAISER =====================

    public function fundraisersList(Request $request)
    {
        $query = Fundraiser::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fundraisers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.fundraisers', [
            'fundraisers' => $fundraisers,
            'active'      => 'fundraisers',
        ]);
    }

    public function storeFundraiser(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        Fundraiser::create([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Fundraiser berhasil ditambahkan.');
    }

    public function updateFundraiser(Request $request, $id)
    {
        $fundraiser = Fundraiser::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $fundraiser->update([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Fundraiser berhasil diperbarui.');
    }

    public function deleteFundraiser($id)
    {
        $fundraiser = Fundraiser::findOrFail($id);
        $fundraiser->delete();

        return redirect()->back()->with('success', 'Fundraiser berhasil dihapus.');
    }

    // ===================== MASTER RESPON PROSPEK =====================

    public function prospectResponsesList(Request $request)
    {
        $query = MasterProspectResponse::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prospectResponses = $query->latest()->paginate(10)->withQueryString();

        return view('admin.prospect_responses', [
            'prospectResponses' => $prospectResponses,
            'active'            => 'prospect_responses',
        ]);
    }

    public function storeProspectResponseMaster(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        MasterProspectResponse::create([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Respon Prospek berhasil ditambahkan.');
    }

    public function updateProspectResponseMaster(Request $request, $id)
    {
        $item = MasterProspectResponse::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $item->update([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Respon Prospek berhasil diperbarui.');
    }

    public function deleteProspectResponseMaster($id)
    {
        $item = MasterProspectResponse::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Respon Prospek berhasil dihapus.');
    }

    // ===================== MASTER TARGET =====================

    public function targetsList(Request $request)
    {
        $query = MasterTarget::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('target_code', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('target_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('target_date', '<=', $request->end_date);
        }

        // Calculate total nominal target for filtered dataset
        $totalNominalTarget = (clone $query)->sum('target_amount');

        $targets = $query->orderBy('target_date', 'desc')->paginate(10)->withQueryString();

        return view('admin.targets', [
            'targets'            => $targets,
            'totalNominalTarget' => $totalNominalTarget,
            'active'             => 'targets',
        ]);
    }

    public function storeTarget(Request $request)
    {
        $request->validate([
            'target_code'   => 'required|string|max:50|unique:master_targets,target_code',
            'target_date'   => 'required|date',
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        MasterTarget::create($request->only('target_code', 'target_date', 'target_amount', 'notes'));

        return redirect()->back()->with('success', 'Master Target berhasil ditambahkan.');
    }

    public function updateTarget(Request $request, $id)
    {
        $target = MasterTarget::findOrFail($id);

        $request->validate([
            'target_code'   => 'required|string|max:50|unique:master_targets,target_code,' . $id,
            'target_date'   => 'required|date',
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        $target->update($request->only('target_code', 'target_date', 'target_amount', 'notes'));

        return redirect()->back()->with('success', 'Master Target berhasil diperbarui.');
    }

    public function deleteTarget($id)
    {
        $target = MasterTarget::findOrFail($id);
        $target->delete();

        return redirect()->back()->with('success', 'Master Target berhasil dihapus.');
    }

    public function exportTargets(Request $request)
    {
        $query = MasterTarget::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('target_code', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('target_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('target_date', '<=', $request->end_date);
        }

        $targets = $query->orderBy('target_date', 'desc')->get();

        $filename = 'Master_Target_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($targets) {
            $file = fopen('php://output', 'w');

            // Write BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($file, ['No', 'ID Target', 'Bulan Target', 'Nominal Target', 'Keterangan']);

            $totalNominal = 0;
            foreach ($targets as $index => $row) {
                $totalNominal += $row->target_amount;
                fputcsv($file, [
                    $index + 1,
                    $row->target_code,
                    $row->target_date ? $row->target_date->format('Y-m-d') : '',
                    $row->target_amount,
                    $row->notes ?? '-',
                ]);
            }

            // Total summary row
            fputcsv($file, ['TOTAL', '', '', $totalNominal, '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
