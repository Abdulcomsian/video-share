<?php

namespace App\DataTables;

use App\Models\PersonalJob;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JobListDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '<a href="'. route('admin:jobs.show', $row->id) .'" class="btn btn-sm btn-primary">View</a>';
            })
            ->addColumn('client_full_name', function ($row) {
                return $row->user->full_name ?? '';
            })
            ->filterColumn('client_full_name', function($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('full_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('editor_full_name', function ($row) {
                return $row->acceptedRequest->editor->full_name ?? '';
            })
            ->filterColumn('editor_full_name', function($query, $keyword) {
                $query->whereHas('acceptedRequest.editor', function ($q) use ($keyword) {
                    $q->where('full_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('awarded_budget', function ($row) {
                return $row->acceptedRequest->proposal->bid_price ?? 0;
            })
            ->rawColumns(['actions']);
    }

    public function query(PersonalJob $model): QueryBuilder
    {
        return $model->newQuery()->with('user','acceptedRequest.proposal','acceptedRequest.editor')->orderByDesc('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('editors-list-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"row mb-3"<"col-md-6"l><"col-md-6 dataTable-search-filter-col"f>>tip') // 'f' is the search box
            ->selectStyleSingle()
            ->buttons([
                Button::make('add'),
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex') // Rename the index column to Serial Number
            ->title('#')
            ->searchable(false) // Disable searching on this column
            ->orderable(false), // Disable ordering on this column
            Column::make('title'),
            Column::make('budget')->title('Budget')
            ->searchable(false)
            ->orderable(false),
            Column::make('awarded_budget')->title('Awarded Budget')
            ->searchable(false)
            ->orderable(false),
            Column::computed('client_full_name')
            ->title('Client')
            ->searchable(true)
            ->orderable(false),
            Column::computed('editor_full_name')
            ->title('Editor')
            ->searchable(true)
            ->orderable(false),
            Column::make('status'),
            Column::computed('actions')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Jobs_List_' . date('YmdHis');
    }
}
