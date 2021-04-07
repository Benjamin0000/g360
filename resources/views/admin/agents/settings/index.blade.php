@extends('admin.layout', ['title'=>'AGENTS SETTINGS'])
@section('content')
@php
use App\Http\Helpers;
use Carbon\Carbon;
use App\Models\AgentSetting;
$set = AgentSetting::first();
@endphp
<style>tr{text-align:center;}</style>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">AGENT SETTINGS</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{route('admin.agents.index')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-settings"></i>Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-40 align-items-center no-block">
                    <h5 class="card-title">SETTINGS</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                       <h4 class="text-center">AGENT</h4>
                       <br>
                       <form action="{{route('admin.agents.settings.update')}}" method="post">
                         <div class="row">
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per RGold coin</label>
                               <input type="text" class="form-control" name="prgc" value="{{$set?$set->ag_prgc:0}}">
                             </div>
                           </div>
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Ref. Reward Amount</label>
                               <input type="text" class="form-control" name="ramt" value="{{$set?$set->ag_sra:0}}">
                             </div>
                           </div>
                         </div>
                         <div class="row">
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per FBronze coin</label>
                               <input type="text" class="form-control" name="pfbc" value="{{$set?$set->ag_pfbc:0}}">
                             </div>
                           </div>

                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per GSiver coin</label>
                               <input type="text" class="form-control" name="pgsc" value="{{$set?$set->ag_pgsc:0}}">
                             </div>
                           </div>
                         </div>

                         <div class="row">
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per POS Dimond coin</label>
                               <input type="text" class="form-control" name="posc" value="{{$set?$set->ag_posc:0}}">
                             </div>
                           </div>
                         </div>
                         @csrf
                          <div class="form-group">
                            <button class="btn btn-primary">UPDATE</button>
                          </div>
                       </form>
                    </div>
                    <div class="col-md-6">
                      <h4 class="text-center">SUPER AGENT</h4>
                      <br>
                      <form action="{{route('admin.superagent.settings.update')}}" method="post">
                         <div class="row">
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Total Ref. per RGold coin</label>
                               <input type="text" class="form-control" name="rpg" value="{{$set?$set->sg_trprgc:0}}">
                             </div>
                           </div>
                           @csrf
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per RGold coin</label>
                               <input type="text" class="form-control" name="prgc" value="{{$set?$set->sg_prgc:0}}">
                             </div>
                           </div>
                         </div>
                         <div class="row">
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per FBronze coin</label>
                               <input type="text" class="form-control" name="pfbc" value="{{$set?$set->sg_pfbc:0}}">
                             </div>
                           </div>

                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per GSiver coin</label>
                               <input type="text" class="form-control" name="pgsc" value="{{$set?$set->sg_pgsc:0}}">
                             </div>
                           </div>
                         </div>

                         <div class="row">
                           <div class="col-md-6">
                             <div class="form-group">
                               <label for="">Price per POS Dimond coin</label>
                               <input type="text" class="form-control" name="posc" value="{{$set?$set->sg_posc:0}}">
                             </div>
                           </div>
                         </div>
                         <div class="form-group">
                             <button class="btn btn-primary">UPDATE</button>
                         </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
