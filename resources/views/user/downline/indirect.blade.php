@extends('user.layout', ['title'=>' Direct Downlines'])
@section('content')
<div class=" clearfix row ">
    <div class="col-sm-12 p-3">
        <a href="{{route('user.downline.indirect')}}" class="btn btn-outline-default float-right">Indirect Downline</a>
    </div>
</div>
<div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">LIST OF ALL INDIRECT DOWNLINE</h4>
                  <div class="table-responsive">
                    <table class="table no-wrap table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Package</th>
                                <th>Join date</th>
                                <th>Activation date</th>
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
