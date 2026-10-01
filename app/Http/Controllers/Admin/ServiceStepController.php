<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceStepRequest;
use App\Models\ServiceStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServiceStepController extends Controller
{
    /**
     * სერვისების გვერდი: "როგორ ვმუშაობთ" ნაბიჯების სია და დამატების ფორმა.
     */
    public function index(): View
    {
        return view('admin.pages.services', [
            'steps' => ServiceStep::with('images')->ordered()->get(),
        ]);
    }

    public function store(ServiceStepRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $step = ServiceStep::create($request->stepData());
            $this->storeImages($step, $request->file('images', []));
        });

        return redirect()
            ->route('admin.services')
            ->with('success', 'ნაბიჯი წარმატებით დაემატა.');
    }

    public function edit(ServiceStep $step): View
    {
        return view('admin.pages.service-steps.edit', [
            'step' => $step->load('images'),
        ]);
    }

    public function update(ServiceStepRequest $request, ServiceStep $step): RedirectResponse
    {
        $removed = $step->images()->whereIn('id', $request->removeImageIds())->get();

        DB::transaction(function () use ($request, $step, $removed) {
            $step->update($request->stepData());
            $step->images()->whereKey($removed->modelKeys())->delete();
            $this->storeImages($step, $request->file('images', []));
        });

        // ფაილები იშლება მხოლოდ ბაზის წარმატებით განახლების შემდეგ.
        Storage::disk('public')->delete($removed->pluck('path')->all());

        return redirect()
            ->route('admin.services')
            ->with('success', 'ნაბიჯი განახლდა.');
    }

    public function destroy(ServiceStep $step): RedirectResponse
    {
        $paths = $step->images()->pluck('path')->all();

        $step->delete();

        Storage::disk('public')->delete($paths);

        return redirect()
            ->route('admin.services')
            ->with('success', 'ნაბიჯი წაიშალა.');
    }

    /**
     * @param  array<UploadedFile>  $files
     */
    private function storeImages(ServiceStep $step, array $files): void
    {
        $order = (int) $step->images()->max('sort_order');

        foreach ($files as $file) {
            $step->images()->create([
                'path' => $file->store('service-steps', 'public'),
                'sort_order' => ++$order,
            ]);
        }
    }
}
