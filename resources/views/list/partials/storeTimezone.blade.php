<!-- Report the user's timezone offset so it can be saved to his/her User model -->
<form id="storeUserTimeZone" class="hidden" method="post" action="{{ route('user.timezone', ['user' => $user, 'list' => $list]) }}" data-storedTZOffset="{{ $offsetMinutes }}">

    {{ csrf_field() }}

    <input id="tzOffset" type="hidden" name="tzOffsetMinutes" value="">

</form>