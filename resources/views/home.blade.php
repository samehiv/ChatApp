@extends('layouts.app')
<script>
    window.UserId='{{Auth::user()->id}}';
</script>
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-1" style="font-size: 20px;display:inline-block">
                <profile-image auth user-id="{{Auth::user()->id}}"></profile-image>
                <span>{{Auth::user()->name}}</span>
            </div>
            <friend-bar></friend-bar>
        </div>
        <router-view></router-view>
    </div>

@endsection
