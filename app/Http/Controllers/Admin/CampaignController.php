<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Campaign::class, 'campaign');
    }

    public function index(Request $request)
    {
        $query = Campaign::with('advertiser');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        return view('admin.campaigns', [
            'campaigns' => $query->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.create-campaign', [
            'advertisers' => Advertiser::active()->get(),
            'statuses' => CampaignStatus::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'advertiser_id' => ['required', 'integer', 'exists:advertisers,id'],
            'name' => ['required', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, CampaignStatus::cases()))],
            'budget' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        Campaign::create($data);

        return redirect()->route('admin.web.campaigns.index')->with('success', 'Campagne créée.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.edit-campaign', [
            'campaign' => $campaign,
            'statuses' => CampaignStatus::cases(),
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', Rule::in(array_map(fn($value) => $value->value, CampaignStatus::cases()))],
            'budget' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $campaign->update($data);

        return redirect()->route('admin.web.campaigns.index')->with('success', 'Campagne mise à jour.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.web.campaigns.index')->with('success', 'Campagne supprimée.');
    }

    public function approve(Campaign $campaign)
    {
        $campaign->update(['status' => CampaignStatus::APPROVED->value]);

        return redirect()->route('admin.web.campaigns.index')->with('success', 'Campagne approuvée.');
    }

    public function reject(Campaign $campaign)
    {
        $campaign->update(['status' => CampaignStatus::CANCELLED->value]);

        return redirect()->route('admin.web.campaigns.index')->with('success', 'Campagne annulée.');
    }
}
