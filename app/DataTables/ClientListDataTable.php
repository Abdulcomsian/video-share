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

class ClientListDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addIndexColumn();
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('type', AppConst::CLIENT)->orderByDesc('id');
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
            // Column::make('phone_number')->title('phone number')
        ];
    }

    protected function filename(): string
    {
        return 'Clients_List_' . date('YmdHis');
    }
}
