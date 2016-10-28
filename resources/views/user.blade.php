@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="text-align: center;">
                <profile-image user-id="{{$user->id}}"></profile-image>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="text-align: center">
                <h2>{{$user->name}}</h2>
                <h2>{{$user->email}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="text-align: center">
                <span id="{{$user->id}}"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-4 col-md-4" style="text-align: center">
                <friend-button user-id="{{$user->id}}"></friend-button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-4 col-md-4" style="text-align: center">
            <router-link to="/chatwith/{{$user->id}}">
                <button class="btn btn-default">Send message</button>
            </router-link>
        </div>
    </div>
    <router-view></router-view>
@endsection
