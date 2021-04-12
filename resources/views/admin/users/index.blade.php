@extends('admin.layout', ['title'=>'USERS'])
@section('content')
    @php
        use App\Http\Helpers;
        $cur = Helpers::LOCAL_CURR_SYMBOL;
    @endphp
    <style>tr{text-align:center;}</style>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex m-b-40 align-items-center no-block">
                        <h5 class="card-title">USERS</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>PV</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
