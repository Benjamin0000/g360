@php 
use App\Http\Helpers;
@endphp
<div class="card">
    <div class="card-header">
        <div class="cart-title">More Settings</div>
    </div>
    <div class="card-body">
        <form  action="{{route('admin.gsteam.setting')}}" method="post">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">H-Token%</label>
                        <input type="text" value="{{Helpers::getRegData('gs_h_token_percent')}}" name="gs_h_token_percent" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">GsTeam Fee %</label>
                        <input type="text" value="{{Helpers::getRegData('gs_fee_percent')}}" name="gs_fee_percent" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">Associate %</label>
                        <input type="text" value="{{Helpers::getRegData('gs_assoc_percent')}}" name="gs_assoc_percent" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">Min Cashout</label>
                        <input type="text" value="{{Helpers::getRegData('gs_min_cashout')}}" name="gs_min_cashout" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">Ref. com percent eg: 2, 4, 1</label>
                        <input type="text" value="{{Helpers::getRegData('gs_ref_com_percent')}}" name="gs_ref_com_percent" class="form-control">
                    </div>
                </div>
            </div>
            @csrf
            <div class="form-group text-center">
                <button class="btn btn-info">UPDATE</button>
            </div>
        </form>
    </div>
</div>

