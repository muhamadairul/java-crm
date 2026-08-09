<?php

namespace Webkul\Admin\DataGrids\Settings;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GroupDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $user = auth()->guard('user')->user();

        $queryBuilder = DB::table('groups')
            ->leftJoin('user_groups', 'groups.id', '=', 'user_groups.group_id')
            ->leftJoin('users', function ($join) use ($user) {
                $join->on('user_groups.user_id', '=', 'users.id');
                if ($user->company_id) {
                    $join->where('users.company_id', '=', $user->company_id);
                }
            })
            ->addSelect(
                'groups.id',
                'groups.name',
                'groups.description',
                DB::raw('COUNT(users.id) as user_count')
            )
            ->where('groups.company_id', $this->getCurrentCompanyId())
            ->groupBy('groups.id', 'groups.name', 'groups.description');

        $this->addFilter('id', 'groups.id');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.settings.groups.index.datagrid.id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'type'       => 'string',
            'label'      => trans('admin::app.settings.groups.index.datagrid.name'),
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'    => 'description',
            'label'    => trans('admin::app.settings.groups.index.datagrid.description'),
            'type'     => 'string',
            'sortable' => false,
        ]);

        $this->addColumn([
            'index'      => 'user_count',
            'label'      => trans('admin::app.settings.groups.index.datagrid.user-count'),
            'type'       => 'integer',
            'sortable'   => true,
            'filterable' => false,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('settings.user.groups.edit') || bouncer()->hasPermission('settings.user.groups.view')) {
            $this->addAction([
                'index'  => 'view',
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.settings.groups.index.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.settings.groups.show', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('settings.user.groups.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.settings.groups.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.settings.groups.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('settings.user.groups.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.settings.groups.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.settings.groups.delete', $row->id),
            ]);
        }
    }
}
