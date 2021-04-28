@extends('user.layout', ['title'=>'Support'])
@php
$cur = App\Http\Helpers::LOCAL_CURR_SYMBOL;
@endphp
@section('content')
  <style media="screen">
    .ttb tr{border-top: hidden;}
  </style>
  <div class="card">
    <div class="card-header text-center" style="background:#eee;">
      <h3>Contact admin </h3>
    </div>
    <div class="card-body">
      <h5>Contact admin through the folling means</h5>
      <h5>Email : </h5>
      <h5>Phone : </h5>
        {{-- <form action="{{route('user.support.send')}}" method="post">
           <div class="form-group">
              <label for="">Subject</label>
              <input type="text" name="subject" class="form-control" value="" required>
           </div>
           <div class="form-group">
             <label for="">Message</label>
             <textarea name="message" class="form-control" placeholder="Enter your message"></textarea>
           </div>
           @csrf
           <div class="form-group">
             <button class="btn btn-primary">Submit</button>
           </div>
        </form> --}}
    </div>
  </div>
@endsection
