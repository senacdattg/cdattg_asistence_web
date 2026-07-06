<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Biogjgas\StoreBiogjgasBannerRequest;
use App\Models\Biogjgas\BiogjgasBanner;
use App\Services\Biogjgas\BiogjgasAdminService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BannerAdminController extends Controller
{
    public function __construct(
        private readonly BiogjgasAdminService $adminService
    ) {
        $this->middleware('can:GESTIONAR BANNER BIOGJGAS');
    }

    public function index(): View
    {
        return view('biogjgas.admin.banners.index', [
            'banners' => $this->adminService->bannersOrdenados(),
        ]);
    }

    public function create(): View
    {
        return view('biogjgas.admin.banners.create');
    }

    public function store(StoreBiogjgasBannerRequest $request): RedirectResponse
    {
        $this->adminService->crearBanner($request->validated(), Auth::id());

        return redirect()
            ->route('biogjgas.admin.banners.index')
            ->with('success', 'Banner creado correctamente.');
    }

    public function edit(BiogjgasBanner $banner): View
    {
        return view('biogjgas.admin.banners.edit', compact('banner'));
    }

    public function update(StoreBiogjgasBannerRequest $request, BiogjgasBanner $banner): RedirectResponse
    {
        $this->adminService->actualizarBanner($banner, $request->validated(), Auth::id());

        return redirect()
            ->route('biogjgas.admin.banners.index')
            ->with('success', 'Banner actualizado correctamente.');
    }

    public function destroy(BiogjgasBanner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()
            ->route('biogjgas.admin.banners.index')
            ->with('success', 'Banner eliminado correctamente.');
    }
}
