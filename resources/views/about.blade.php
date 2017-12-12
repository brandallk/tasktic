@extends('layouts.app')

@section('pageTitle')
    About Tasktic
@endsection

@section('nav')
    <nav>
        <ul>
            <li><a href="{{ route('welcome') }}">Welcome Page</a></li>
            <li><a href="{{ route('help') }}">Help</a></li>
            <li><a href="{{ route('contact') }}">Contact Me</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <section>
        <h1>About Tasktic</h1>
        <p>Tasktic is a todo-list app. It is, however, not just a simple todo-list app. It offers sleek, responsive design and custom-built features for advanced usability.</p>
        <p>As a registered Tasktic user, you get a private account and can create as many different todo-lists as you want. Your lists will be waiting for you the next time you log in. You manage your lists any way you want: delete them, rename them, create new ones.</p>
        <p>If you want to keep it simple, just use the default list that appears in your account when you first register. This list is special in a couple of ways: 1) Its name is and always will stay current with whatever the day's date is (even accounting for your local timezone anywhere in the world). 2) You can rename it or delete it -- but if you do, a new, empty default list named for the day's date will appear in your account. That way, you always have a convenient and fresh place to put your daily tasks without messing around with managing lists in your account.</p>
        <p>If you want to use your list to plan out something more sophisticated than a simple flat collection of tasks, you can create categories and subcategories and place tasks within a nested structure. All these structural items are fully editable: Rename them, delete them, create new ones. Build any structure you like.</p>
        <p>The task items in your list have additional capabilities. You can drag-and-drop them into any order you wish. You can mark them as completed, and you can mark any of them as a priority. Priority tasks will be highlited. Additionally, you can always access a special view of priority tasks by clicking on the "Priorities" button at the top of the list.</p>
        <p>You can also add different kinds of details to a task if desired. These can include a deadline, link URLs, and any number of text details. Again, all these things are editable. If you add details like these to a task, a "carrot" icon will appear next to the task. You can click on this icon to expand the task and view its details, or to collapse it and hide everything but the task name.</p>        
        <p>For a more granular description of how to use the app, check out the Help page.</p>
        <p>If you'd like to contact me about the app, try the Contact page.</p>

    </section>

    <section>
        <h2>How Tasktic was Created</h2>
        <p>Tasktic is designed to be, frankly, the todo-list app I wished I had but didn't. I built it so that I could start using it.</p>
        <p>I also built it to explore and demo skills using the features of specific web-development technologies. These include some of the following:
        <p>On the backend,
            <ul>
                <li><strong>PHP</strong> and the PHP MVC framework <strong>Laravel 5.5</strong></li>
                <li>Laravel's <strong>Eloquent</strong> ORM</li>
                <li><strong>PostgreSQL</strong> database</li>
                <li><strong>TDD</strong> using the <strong>PHPUnit</strong> testing framework (and extensions to it offered by Laravel)</li>
                <li><strong>Blade</strong> templates for the HTML views</li>
            </ul>
        </p>
        <p>On the frontend, the style was hand-crafted (no Bootstrap!) using <strong>Sass</strong> with a <strong>responsive, mobile-first</strong> approach. The JavaScript was also custom-built using <strong>ES6</strong> classes and modules in an <strong>OOP</strong> approach, bundling with <strong>Webpack</strong> via Laravel Mix.</p>
        <p>The app was deployed live using <strong>Heroku</strong> via <strong>Github</strong>. An open-source repo with the source-code can be found here.</p>
    </section>

    <footer>
        <ul>
            <li><a href="#">Github Repo</a></li>
            <li><a href="#">BenjamenKing.com</a></li>
            <li><a href="{{ route('contact') }}">Contact Me</a></li>
        </ul>
    </footer>

@endsection
