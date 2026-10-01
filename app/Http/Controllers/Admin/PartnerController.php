<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function store(PartnerRequest $request): RedirectResponse
    {
        Partner::create([
            ...$request->partnerData(),
            'logo' => $request->file('logo')->store('partners', 'public'),
        ]);

        return redirect()
            ->to(route('admin.company') . '#partners')
            ->with('success', 'პარტნიორი წარმატებით დაემატა.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.pages.partners.edit', compact('partner'));
    }

    public function update(PartnerRequest $request, Partner $partner): RedirectResponse
    {
        $data = $request->partnerData();

        if ($request->hasFile('logo')) {
            $oldLogo = $partner->logo;
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        if (isset($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        return redirect()
            ->to(route('admin.company') . '#partners')
            ->with('success', 'პარტნიორის ინფორმაცია განახლდა.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->delete();

        Storage::disk('public')->delete($partner->logo);

        return redirect()
            ->to(route('admin.company') . '#partners')
            ->with('success', 'პარტნიორი წაიშალა.');
    }
}
