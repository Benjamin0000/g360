@extends('user.layout', ['title'=>'Gfund'])
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <br/>
                <div class="card-body">
                    <h4 class="card-title">Transfer Funds</h4>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Local Bank</span></a> </li>
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Other Members</span></a> </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane p-3 pt-5" id="home" role="tabpanel">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label>Recipient G-Number </label>
                                    <input type="text" class="form-control" name="pin" />
                                </div>
                                <div class="form-group">
                                    <label>Current Password </label>
                                    <input type="text" class="form-control" name="pin" />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary ml-2">Request Transfer </button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane p-3 active pt-5" id="profile" role="tabpanel">


                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="card-title">Recipient G-Number  </h4>
                                    <form class="form-inline" method="post" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="ident" />
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary ml-2">Continue</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
