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
                <h1>Client</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table client-table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
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
            var table = $('.client-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.client.list') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone' , name: 'phone'},
                ]
            });

        })()
    
})
</script>
@endsection