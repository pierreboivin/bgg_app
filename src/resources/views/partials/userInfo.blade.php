@if(isset($userinfo['username']))
    <h1>{{ $userinfo['firstname'] }} {{ $userinfo['lastname'] }} ({{ $userinfo['username'] }})</h1>
@elseif(isset($name))
    <h1>{{ $name }}</h1>
@endif