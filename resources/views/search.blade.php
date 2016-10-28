@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            @foreach($users as $user)
                <div>
                    @if($user->avatar==null)
                        <img src="{{Storage::disk('local')->url('default.jpg')}}"
                             class="img-circle" width="100" height="100" style="margin-right: 20px">
                    @else
                        <img src="{{Storage::disk('local')->url($user->avatar)}}"
                             class="img-circle" width="100" height="100" style="margin-right: 20px">
                    @endif
                    <div class="search-info">
                        <a href="{{url('/user/'.$user->id)}}">{{$user->name}}</a>
                        <br>
                        {{$user->email}}
                    </div>

                </div>
                <hr>
            @endforeach
                {{$users->links()}}
        </div>
    </div>


@endsection