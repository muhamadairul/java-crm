<?php

namespace Webkul\Lead\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class PipelineRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected StageRepository $stageRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Lead\Contracts\Pipeline';
    }

    /**
     * Create pipeline.
     *
     * @return \Webkul\Lead\Contracts\Pipeline
     */
    public function create(array $data)
    {
        if ($data['is_default'] ?? false) {
            $this->model->query()->update(['is_default' => 0]);
        }

        $pipeline = $this->model->create($data);

        foreach ($data['stages'] as $stageData) {
            $this->stageRepository->create(array_merge([
                'lead_pipeline_id' => $pipeline->id,
            ], $stageData));
        }

        return $pipeline;
    }

    /**
     * Update pipeline.
     *
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Lead\Contracts\Pipeline
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $pipeline = $this->find($id);

        if ($data['is_default'] ?? false) {
            $this->model->query()->where('id', '<>', $id)->update(['is_default' => 0]);
        }

        $pipeline->update($data);

        $previousStageIds = $pipeline->stages()->pluck('id');

        foreach ($data['stages'] as $stageId => $stageData) {
            if (Str::contains($stageId, 'stage_')) {
                $this->stageRepository->create(array_merge([
                    'lead_pipeline_id' => $pipeline->id,
                ], $stageData));
            } else {
                if (is_numeric($index = $previousStageIds->search($stageId))) {
                    $previousStageIds->forget($index);
                }

                $this->stageRepository->update($stageData, $stageId);
            }
        }

        foreach ($previousStageIds as $stageId) {
            $pipeline->leads()->where('lead_pipeline_stage_id', $stageId)->update([
                'lead_pipeline_stage_id' => $pipeline->stages()->first()->id,
            ]);

            $this->stageRepository->delete($stageId);
        }

        return $pipeline;
    }

    /**
     * Return the default pipeline.
     *
     * @return \Webkul\Lead\Contracts\Pipeline
     */
    public function getDefaultPipeline()
    {
        $pipeline = $this->model->where('is_default', 1)->first();

        if (! $pipeline) {
            $pipeline = $this->first();
        }

        if (! $pipeline) {
            $pipeline = $this->model->withoutGlobalScopes()->where('is_default', 1)->first()
                ?? $this->model->withoutGlobalScopes()->first();
        }

        if (! $pipeline) {
            $user = auth()->guard('user')->user();
            $companyId = $user?->company_id;

            $pipelineName = 'Default Pipeline';
            if ($this->model->withoutGlobalScopes()->where('name', $pipelineName)->exists()) {
                $pipelineName = 'Pipeline Utama (' . Str::random(4) . ')';
            }

            $pipeline = $this->create([
                'name'       => $pipelineName,
                'is_default' => 1,
                'company_id' => $companyId,
                'stages'     => [
                    [
                        'name'        => trans('installer::app.seeders.lead.pipeline.pipeline-stages.new', [], config('app.locale')) ?: 'Lead Baru',
                        'code'        => 'new',
                        'probability' => 100,
                        'sort_order'  => 1,
                    ],
                    [
                        'name'        => trans('installer::app.seeders.lead.pipeline.pipeline-stages.follow-up', [], config('app.locale')) ?: 'Follow Up / Kualifikasi',
                        'code'        => 'follow-up',
                        'probability' => 100,
                        'sort_order'  => 2,
                    ],
                    [
                        'name'        => trans('installer::app.seeders.lead.pipeline.pipeline-stages.prospect', [], config('app.locale')) ?: 'Penawaran Terkirim',
                        'code'        => 'prospect',
                        'probability' => 100,
                        'sort_order'  => 3,
                    ],
                    [
                        'name'        => trans('installer::app.seeders.lead.pipeline.pipeline-stages.negotiation', [], config('app.locale')) ?: 'Negosiasi',
                        'code'        => 'negotiation',
                        'probability' => 100,
                        'sort_order'  => 4,
                    ],
                    [
                        'name'        => trans('installer::app.seeders.lead.pipeline.pipeline-stages.won', [], config('app.locale')) ?: 'Berhasil (Won)',
                        'code'        => 'won',
                        'probability' => 100,
                        'sort_order'  => 5,
                    ],
                    [
                        'name'        => trans('installer::app.seeders.lead.pipeline.pipeline-stages.lost', [], config('app.locale')) ?: 'Gagal (Lost)',
                        'code'        => 'lost',
                        'probability' => 0,
                        'sort_order'  => 6,
                    ],
                ],
            ]);
        }

        return $pipeline;
    }
}
