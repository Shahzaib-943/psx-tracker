<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSettings;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreSystemSettingRequest;

class SystemSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view system-settings')->only('index');
        $this->middleware('permission:edit system-settings')->only('edit', 'update');
    }

    public function index()
    {
        $settings = SystemSettings::select('id', 'key', 'value')->get();
        $marketOpeningTime = trim($settings->where('key', 'market_opening_time')->first()?->value ?? '');
        $marketClosingTime = trim($settings->where('key', 'market_closing_time')->first()?->value ?? '');
        return view('settings.index', compact('marketOpeningTime', 'marketClosingTime'));
    }

    public function create()
    {
        return view('settings.create');
    }

    public function store(StoreSystemSettingRequest $request)
    {
        $validatedData = $request->validated();
        SystemSettings::set('market_opening_time', $validatedData['market_opening_time']);
        SystemSettings::set('market_closing_time', $validatedData['market_closing_time']);
        flash()->success('System settings updated successfully.');
        return to_route('system-settings.index');
    }

    public function edit($id)
    {
        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('settings.index');
    }
}
