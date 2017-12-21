
## About Tasktic

Tasktic is a todo-list app created from scratch with a mobile-first, responsive design and a few custom-built features for advanced usability.

As a registered Tasktic user, you get a private account and can create as many different todo-lists as you want. Your lists will be waiting for you the next time you log in. You manage your lists any way you want: delete them, rename them, create new ones.

If you want to keep it simple, just use the default list that appears in your account when you first register. This list is special in a couple of ways: 1) Its name is and always will stay current with whatever the day's date is (even accounting for your local timezone anywhere in the world). 2) You can rename it or delete it -- but if you do, a new, empty default list named for the day's date will appear in your account. That way, you always have a convenient and fresh place to put your daily tasks without messing around with managing lists in your account.

If you want to use your list to plan out something more sophisticated than a simple flat collection of tasks, you can create categories and subcategories and place tasks within a nested structure. All these structural items are fully editable: Rename them, delete them, create new ones. Build any structure you like.

The task items in your list have additional capabilities. You can drag-and-drop them into any order you wish. You can mark them as completed, and you can mark any of them as a priority. Priority tasks will be highlited. Additionally, you can always access a special view of priority tasks by clicking on the "Priorities" button at the top of the list.

You can also add different kinds of details to a task if desired. These can include a deadline, link URLs, and any number of text details. Again, all these things are editable. If you add details like these to a task, a "carrot" icon will appear next to the task. You can click on this icon to expand the task and view its details, or to collapse it and hide everything but the task name.

## How Tasktic was Created

Tasktic is the todo-list app I wished I had but didn't. I built it so that I could start using it.

I also built it to explore the features of specific web-development technologies. These include some of the following:

On the backend,

* PHP and the PHP MVC framework Laravel 5.5
* Laravel's Eloquent ORM
* PostgreSQL database
* TDD using the PHPUnit testing framework (and extensions to it offered by Laravel)
* Blade templates for the HTML views

On the frontend, the style was hand-crafted (no Bootstrap!) using Sass with a responsive, mobile-first approach. The JavaScript was also custom-built using ES6 classes and modules in an OOP approach, bundling with Webpack via Laravel Mix.

To browse the app's core functionality, try the PHP unit tests in [./tests/Unit/](https://github.com/brandallk/tasktic/tree/master/tests/Unit) , the PHP model classes in [./app/Models/](https://github.com/brandallk/tasktic/tree/master/app/Models) , and the PHP controller classes in [./app/Http/Controllers](https://github.com/brandallk/tasktic/tree/master/app/Http/Controllers) . The front-end assets are located in [./resources/assets/js/](https://github.com/brandallk/tasktic/tree/master/resources/assets/js) and [./resources/assets/sass](https://github.com/brandallk/tasktic/tree/master/resources/assets/sass) .

This app was designed intentionally as a traditional, server-side web app. It is intended to serve as a basis for a future version that will be rebuilt as a more modern SPA, using front-end technology TBD.

## Current Status

Tasktic is currently a work in progress. The core features are all built and working. Remaining work before launching the app includes: a few minor bug fixes, content for the 'about' and 'help' pages, an email contact form, CSS styles for the auth-system views (e.g. the login page) and 'about', 'help', and 'contact' pages, and probably some refactoring of the Blade views as well.

## Selected Screen Shots

<img src="https://c1.staticflickr.com/5/4734/38310374985_c55e65b20f.jpg" alt="Tasktic welcome page" />

<img src="https://c1.staticflickr.com/5/4588/38310375865_d88e94ab0a.jpg" alt="Tasktic list desktop 1" />

<img src="https://c1.staticflickr.com/5/4645/27409412809_4fed71ba42.jpg" alt="Tasktic list desktop 2" />

<img src="https://c1.staticflickr.com/5/4689/38310375705_3500a3b3ff.jpg" alt="Tasktic list desktop 3" />

<img src="https://c1.staticflickr.com/5/4594/27409412719_546e0f524d.jpg" alt="Tasktic list desktop 4" />

<img src="https://c1.staticflickr.com/5/4728/38310375545_c53da8bde6.jpg" alt="Tasktic list desktop 5" />

<img src="https://c1.staticflickr.com/5/4644/27409412609_e3f3309fbf.jpg" alt="Tasktic list desktop 6" />

<img src="https://c1.staticflickr.com/5/4683/38310375375_df6d4b47f0.jpg" alt="Tasktic list desktop 7" />

<img src="https://c1.staticflickr.com/5/4737/27409412409_6ab0d2a024.jpg" alt="Tasktic list mobile 1" />

<img src="https://c1.staticflickr.com/5/4692/38310375195_9932e92ce9.jpg" alt="Tasktic list mobile 2" />

<img src="https://c1.staticflickr.com/5/4643/27409412549_2021bdd328.jpg" alt="Tasktic list mobile 3" />

<img src="https://c1.staticflickr.com/5/4734/38310375205_461e73cf3d.jpg" alt="Tasktic list mobile 4" />
