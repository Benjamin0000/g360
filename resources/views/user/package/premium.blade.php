@extends('user.layout', ['title'=>'Premium Packages'])
@section('content')
@php 
 $cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center text-primary">Premium sign up packages</h2>
                <br>
                <div class="row pricing-plan">
                    @if($packages->count())
                    @foreach($packages as $package)
                    <div class="col-md-4 col-xs-12 col-sm-12 no-padding">
                        <div class="pricing-box">
                            <div class="pricing-body border">
                                <div class="pricing-header">
                                    <h4 class="text-center">{{ucfirst($package->name)}}</h4>
                                    <h2 class="text-center"><span class="price-sign">{{$cur}}</span>{{number_format($package->amount)}}</h2>
                                    <p class="uppercase">one time</p>
                                </div>
                                <div class="price-table-content">
                                    <div class="price-row"><i class="icon-user"></i> {{$package->gen}}th Gen. level earning </div>
                                    <div class="price-row"> <i class="fa fa-stethoscope"></i> Health insurance</div>
                                    <div class="price-row"><i class="ti-shopping-cart"></i> E-store space</div>
                                    <div class="price-row">
                                        <button data-toggle='modal' data-target='#choosem{{$package->id}}'  class="btn btn-success waves-effect waves-light mt-3">Sign up</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('user.package.choose_pkg_modal')
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function selectP(id){
        var xhr_error = "<div class='alert alert-danger'><i class='fa fa-info-circle'></i>Transaction could not be completed</div>";
        $('#pkge'+id).html('');
        var $ele = $('#pkgcbtn'+id);
        $ele.data('text',$ele.text());
        $ele.html(get_loader());
        $ele.prop('disabled',true);
        $.ajax({
            url:'{{route('package.select_premium')}}',
            type:'post',
            data:$('#pkgf'+id).serialize(),
            success:function(data){
                console.log(data);
                if(data.status){
                    $('#pkge'+id).html("<div class='alert alert-success'> Upgrade successfull</div>");
                    setTimeout(function(){
                        window.loaction.href = '{{route('dasbhoard.index')}}';
                    },2000);
                }else{
                    $ele.text($ele.data('text'));
                    $ele.prop('disabled',false);
                    $('#pkge'+id).html("<div class='alert alert-danger'> <i class='alert alert-info-circle'></i> "+data.msg+"</div>");
                }
            },
            error: function(xhr, status, error) {
                console.log('error',xhr.responseText);
                $ele.text($ele.data('text'));
                $ele.prop('disabled',false);
                $('#pkge'+id).html(xhr_error);
            }
        });
    }
</script>
@endsection