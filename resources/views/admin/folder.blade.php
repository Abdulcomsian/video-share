@extends('layout.dashboard-master')
@section('css')
    <style>
        td img{
            width: 50px;
        }
    </style>
@endsection
@section('script-cdn')
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Folder</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table folder-table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Client</th>
                        <th scope="col">Email</th>
                        <th scope="col">Folder</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        (function(){
            var table = $('.folder-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.folder.list') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'client_name', name: 'client_name'},
                    {data: 'client_email', name: 'client_email'},
                    {data: 'folder_name' , name: 'folder_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        })()
    
})
</script>
@endsection