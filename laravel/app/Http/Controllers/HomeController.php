<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\News;
use App\Models\Prayer;
use App\Models\ContactMessage;
use App\Models\Volunteer;
use App\Models\SiteSetting;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::first();
        $campaigns = Campaign::where('status', 'approved')->latest()->take(3)->get();
        $news = News::latest()->take(3)->get();
        $prayers = Prayer::latest()->take(5)->get();
        $distributions = \App\Models\Distribution::with(['campaign', 'worker'])->whereNotNull('latitude')->whereNotNull('longitude')->get();

        return view('home', compact('settings', 'campaigns', 'news', 'prayers', 'distributions'));
    }

    public function about()
    {
        $settings = SiteSetting::first();
        return view('about', compact('settings'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->all());

        return redirect()->back()->with('success', 'Pesan Anda berhasil dikirim. Terima kasih!');
    }

    public function volunteer()
    {
        return view('volunteer');
    }

    public function submitVolunteer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'skills' => 'required|string',
            'reason' => 'required|string',
        ]);

        Volunteer::create($request->all());

        return redirect()->back()->with('success', 'Pendaftaran relawan berhasil dikirim. Kami akan menghubungi Anda segera!');
    }

    public function news()
    {
        $news = News::latest()->paginate(6);
        return view('news', compact('news'));
    }

    public function newsDetail($id)
    {
        $item = News::findOrFail($id);
        $recent = News::where('id', '!=', $id)->latest()->take(3)->get();
        return view('news-detail', compact('item', 'recent'));
    }

    public function submitNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create(['email' => $request->email]);

        return redirect()->back()->with('success', 'Terima kasih telah berlangganan newsletter kami!');
    }
}
