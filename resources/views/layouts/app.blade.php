<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel ={csrfToken:'{{csrf_token()}}'}
        @if(Auth::check())
            window.Store={
                friendNav:0,
                friends:[],
                friendReqs:null,
                reqsNav:false,
                chatMsgs:[],
                msgNav:false,
                msgNavCount:0,
                msgs:[],
                auth:'{{Auth::user()->id}}',
                msgReceived(){
                    setTimeout(function(){
                        let el=$('#msgs');
                        let h=el[0].scrollHeight;
                        el.animate({scrollTop:h});
                    },100)
                }
            };
        @endif
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top" style="color:#50beff;">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>
                @if(Auth::check())
                    <form class="navbar-form navbar-left" method="get" action="{{url('/search')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Find Someone" name="query"
                                   id="search-input" style="width:300px">
                        </div>
                    </form>
                @endif
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <friend-nav></friend-nav>
                        <msg-nav></msg-nav>
                        <li><a href="{{url('/home')}}">{{ Auth::user()->name }}</a></li>
                        <li>
                            <a href="{{ url('/logout') }}" class="glyphicon glyphicon-log-out"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.18.1/algoliasearch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.21.8/autocomplete.min.js"></script>
<script src="/js/app.js"></script>
<script>
    @if(Auth::check())
        var client = algoliasearch('IM1QSP5AR2','44a20bc6323a7d499bf38a58bde6d771');
        var index = client.initIndex('users');
        autocomplete('#search-input', {hint: false}, [
            {
                source: autocomplete.sources.hits(index, {hitsPerPage: 10}),
                templates: {
                    suggestion: function(suggestion) {
                        var src='';
                        if(suggestion.avatar == null){
                            src='{{Storage::disk('local')->url('default.jpg')}}'
                        }else{
                            src='{{url('/storage/')}}'+'/'+suggestion.avatar.replace('public','');
                        }

                        return '<img src="'+src+'" width="50px" height="50px" class="img-circle search-img">'
                                +'<div class="search-info">'+
                        '<span>'+suggestion._highlightResult.name.value+'</span>'+'<br>'+
                                '<span style="font-size:12px">'+suggestion.email+'</span>'+'</div>';
                    },
                    empty:'<h4 style="padding-left: 5px;">not found</h4>',

                }
            }
        ]).on('autocomplete:selected', function(event, suggestion, dataset) {
            document.location='{{url('/user/')}}'+'/'+suggestion.id
        });
    Echo.private('App.User.' + '{{Auth::user()->id}}')
            .notification(function(notification){
                if(notification.type=='App\\Notifications\\FriendRequest'){
                    Store.friendNav++
                } else if(notification.type=='App\\Notifications\\NavFriendRemove'){
                    Store.friendNav--
                }
            }).listen('FriendAccepted',function(e){
                Store.friends=Store.friends.concat([e.user]);
            }).listen('MessageSend',function(e){
                if(window.location.hash == '#/chatwith/'+e.msg.sender){
                    $.ajax({
                        url: '{{url('/message/readmessage/')}}'+'/'+e.msg.id,
                        type: 'GET',
                    });
                    Store.chatMsgs=Store.chatMsgs.concat([e.msg]);
                    Store.msgReceived();
                }else{
                    Store.msgNavCount++;
                    Store.msgNav=true;
                }
            });
    Echo.join('Chat')
            .here(function(users){
                for(var i=0;i<users.length;++i){
                    $('#'+users[i].id).text('online').removeClass('text-danger')
                            .addClass('text-success');
                }
            }).joining(function(user) {
                $('#'+user.id).text('online').removeClass('text-danger')
                            .addClass('text-success');
             }).leaving(function(user){
                $('#'+user.id).text('offline').removeClass('text-success')
                            .addClass('text-danger');
            });
    @endif
</script>
</body>
</html>
