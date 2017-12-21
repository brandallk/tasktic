
## About Tasktic

Tasktic is a todo-list app. But it's a bit more sophisticated than a simple todo-list app. It offers mobile-first, responsive design and custom-built features for advanced usability.

As a registered Tasktic user, you get a private account and can create as many different todo-lists as you want. Your lists will be waiting for you the next time you log in. You manage your lists any way you want: delete them, rename them, create new ones.

If you want to keep it simple, just use the default list that appears in your account when you first register. This list is special in a couple of ways: 1) Its name is and always will stay current with whatever the day's date is (even accounting for your local timezone anywhere in the world). 2) You can rename it or delete it -- but if you do, a new, empty default list named for the day's date will appear in your account. That way, you always have a convenient and fresh place to put your daily tasks without messing around with managing lists in your account.

If you want to use your list to plan out something more sophisticated than a simple flat collection of tasks, you can create categories and subcategories and place tasks within a nested structure. All these structural items are fully editable: Rename them, delete them, create new ones. Build any structure you like.

The task items in your list have additional capabilities. You can drag-and-drop them into any order you wish. You can mark them as completed, and you can mark any of them as a priority. Priority tasks will be highlited. Additionally, you can always access a special view of priority tasks by clicking on the "Priorities" button at the top of the list.

You can also add different kinds of details to a task if desired. These can include a deadline, link URLs, and any number of text details. Again, all these things are editable. If you add details like these to a task, a "carrot" icon will appear next to the task. You can click on this icon to expand the task and view its details, or to collapse it and hide everything but the task name.

## How Tasktic was Created

Tasktic is designed to be the todo-list app I wished I had but didn't. I built it so that I could start using it.

I also built it to explore and demo skills using the features of specific web-development technologies. These include some of the following:

On the backend,
        * PHP and the PHP MVC framework Laravel 5.5
        * Laravel's Eloquent ORM
        * PostgreSQL database
        * TDD using the PHPUnit testing framework (and extensions to it offered by Laravel)
        * Blade templates for the HTML views

On the frontend, the style was hand-crafted (no Bootstrap!) using Sass with a responsive, mobile-first approach. The JavaScript was also custom-built using ES6 classes and modules in an OOP approach, bundling with Webpack via Laravel Mix.

## Current Status

Tasktic is currently a work in progress. The core features are all built and working. Remaining work before launching the app includes: a few minor bug fixes, content for the 'about' and 'help' pages, an email contact form, CSS styles for the auth-system views (e.g. the login page) and 'about', 'help', and 'contact' pages, and probably some refactoring of the Blade views as well.