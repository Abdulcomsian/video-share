<?php

namespace App\Http\Controllers\Web;

use App\DataTables\ClientListDataTable;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index(ClientListDataTable $dataTable)
    {
        return $dataTable->render('admin.clients.index');
    }

}
