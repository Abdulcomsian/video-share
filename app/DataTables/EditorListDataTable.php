<?php

namespace App\DataTables;

use App\Http\AppConst;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EditorListDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addIndexColumn();
            // ->addColumn('actions', function ($row) {
            //     return '<a href="'. route('admin:client.show', ['id' => $user->id]) .'" class="btn btn-sm btn-primary">View</a>';
            // })
            // ->rawColumns(['actions']);
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('type', AppConst::EDITOR)->orderByDesc('id');
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
            Column::make('full_name')->title('name'),
            Column::make('email'),
            Column::make('phone_number')->title('phone number'),
            // Column::computed('actions')
            //     ->title('Actions')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(120)
            //     ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Editors_List_' . date('YmdHis');
    }
}
