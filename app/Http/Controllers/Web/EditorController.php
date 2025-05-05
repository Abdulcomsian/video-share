<?php

namespace App\Http\Controllers\Web;

use App\DataTables\EditorListDataTable;
use App\Http\Controllers\Controller;

class EditorController extends Controller
{
    public function index(EditorListDataTable $dataTable)
    {
        return $dataTable->render('admin.editors.index');
    }

}
