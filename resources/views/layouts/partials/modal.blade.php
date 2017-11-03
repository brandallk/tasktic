<div class=@yield('class-names')>
    <form method=@yield('form_method') action=@yield('form-action')>
        {{ csrf_field() }}
        @yield('form-content')
    </form>
</div>