<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMicrosaasRequest;
use App\Models\Microsaas;
use App\Services\MicrosaasDeploymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MicrosaasController extends Controller
{
    public function __construct(private readonly MicrosaasDeploymentService $deploymentService)
    {
    }

    public function index(): View
    {
        return view('admin.microsaas.index', [
            'microsaasList' => Microsaas::latest()->get(),
        ]);
    }

    public function store(StoreMicrosaasRequest $request): RedirectResponse
    {
        $microsaas = $this->deploymentService->deploy(
            attributes: $request->safe()->except('frontend_build'),
            uploadedZip: $request->file('frontend_build'),
        );

        return back()->with('status', "Micro-SaaS {$microsaas->name} berhasil diunggah.");
    }

    public function activate(Microsaas $microsaas): RedirectResponse
    {
        $this->deploymentService->activate($microsaas);

        return back()->with('status', "Release {$microsaas->slug} diaktifkan.");
    }

    public function destroy(Microsaas $microsaas): RedirectResponse
    {
        $this->deploymentService->remove($microsaas);

        return back()->with('status', "Micro-SaaS {$microsaas->slug} dihapus.");
    }
}
