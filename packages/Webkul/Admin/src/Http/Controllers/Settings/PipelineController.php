<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\PipelineDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\PipelineForm;
use Webkul\Lead\Repositories\PipelineRepository;

class PipelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PipelineRepository $pipelineRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(PipelineDataGrid::class)->process();
        }

        return view('admin::settings.pipelines.index');
    }

    /**
     * Display detail of the specified pipeline.
     */
    public function show(int $id): View
    {
        $pipeline = $this->pipelineRepository->with(['stages'])->findOrFail($id);

        $user = auth()->guard('user')->user();
        if ($user->company_id && $pipeline->company_id && $pipeline->company_id != $user->company_id) {
            abort(403, 'Akses ditolak.');
        }

        // Get total leads count and total lead value in this pipeline for tenant company
        $leadsStats = \Illuminate\Support\Facades\DB::table('leads')
            ->where('lead_pipeline_id', $pipeline->id)
            ->where('company_id', $user->company_id)
            ->selectRaw('COUNT(id) as total_leads, SUM(lead_value) as total_value')
            ->first();

        $leadsCount = $leadsStats->total_leads ?? 0;
        $totalPipelineValue = $leadsStats->total_value ?? 0;

        // Get breakdown per stage
        $stageStats = \Illuminate\Support\Facades\DB::table('stages')
            ->where('lead_pipeline_id', $pipeline->id)
            ->leftJoin('leads', function ($join) use ($user) {
                $join->on('stages.id', '=', 'leads.lead_pipeline_stage_id')
                    ->where('leads.company_id', '=', $user->company_id);
            })
            ->select(
                'stages.id',
                'stages.name',
                'stages.code',
                'stages.probability',
                'stages.sort_order',
                \Illuminate\Support\Facades\DB::raw('COUNT(leads.id) as leads_count'),
                \Illuminate\Support\Facades\DB::raw('SUM(leads.lead_value) as total_value')
            )
            ->groupBy('stages.id', 'stages.name', 'stages.code', 'stages.probability', 'stages.sort_order')
            ->orderBy('stages.sort_order', 'asc')
            ->get();

        return view('admin::settings.pipelines.show', compact('pipeline', 'leadsCount', 'totalPipelineValue', 'stageStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::settings.pipelines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PipelineForm $request): RedirectResponse
    {
        $request->validated();

        $request->merge([
            'is_default' => request()->has('is_default') ? 1 : 0,
        ]);

        Event::dispatch('settings.pipeline.create.before');

        $pipeline = $this->pipelineRepository->create($request->all());

        Event::dispatch('settings.pipeline.create.after', $pipeline);

        session()->flash('success', trans('admin::app.settings.pipelines.index.create-success'));

        return redirect()->route('admin.settings.pipelines.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $pipeline = $this->pipelineRepository->findOrFail($id);

        return view('admin::settings.pipelines.edit', compact('pipeline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PipelineForm $request, int $id): RedirectResponse
    {
        $request->validated();

        $isDefault = request()->has('is_default') ? 1 : 0;

        if (! $isDefault) {
            $defaultCount = $this->pipelineRepository->findWhere(['is_default' => 1])->count();

            $pipeline = $this->pipelineRepository->find($id);

            if (
                $defaultCount === 1
                && $pipeline->is_default
            ) {
                session()->flash('error', trans('admin::app.settings.pipelines.index.default-required'));

                return redirect()->back();
            }
        }

        $request->merge(['is_default' => $isDefault]);

        Event::dispatch('settings.pipeline.update.before', $id);

        $pipeline = $this->pipelineRepository->update($request->all(), $id);

        Event::dispatch('settings.pipeline.update.after', $pipeline);

        session()->flash('success', trans('admin::app.settings.pipelines.index.update-success'));

        return redirect()->route('admin.settings.pipelines.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $pipeline = $this->pipelineRepository->findOrFail($id);

        if ($pipeline->is_default) {
            return response()->json([
                'message' => trans('admin::app.settings.pipelines.index.default-delete-error'),
            ], 400);
        } else {
            $defaultPipeline = $this->pipelineRepository->getDefaultPipeline();

            $pipeline->leads()->update([
                'lead_pipeline_id'       => $defaultPipeline->id,
                'lead_pipeline_stage_id' => $defaultPipeline->stages()->first()->id,
            ]);
        }

        try {
            Event::dispatch('settings.pipeline.delete.before', $id);

            $this->pipelineRepository->delete($id);

            Event::dispatch('settings.pipeline.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.settings.pipelines.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.settings.pipelines.index.delete-failed'),
            ], 400);
        }

        return response()->json([
            'message' => trans('admin::app.settings.pipelines.index.delete-failed'),
        ], 400);
    }
}
