Active for Laravel 4/5
======================

[![Build Status](https://travis-ci.org/dwightwatson/active.png?branch=master)](https://travis-ci.org/dwightwatson/active)

Active is a helper package built specifically for Laravel 4.2/5+ that will allow you to recognise the current route, which is helpful for adding 'active' classes (like those used by Bootstrap) and for only performing certain actions on given routes. It also includes helper functions for retrieving the current controller and action names. Inspired by [digithis/activehelper](https://github.com/digithis/activehelper) but written from the ground up to better fit our needs as well as throw in some testing.

## Installation

Simply pop this in your `composer.json` file and run `composer update` (however your Composer is installed).

```
"watson/active": "1.3.*"
```

_If you want to use Active with Laravel 4.0 - 4.1 then specify version `1.0.*` instead._

Now, add the Autologin service provider to your `app/config/app.php` file.

`'Watson\Active\ActiveServiceProvider'`

And finally add this to the aliases array.

`'Active' => 'Watson\Active\Facades\Active'`

## Using Active

There are two ways you can use Active, first by passing in paths and second by passing in named routes. It depends on the structure of your application and how specific you want to be.

### Using paths

What if you want to know whether you're on a path for the purpose of activating a CSS class? Well, you're sorted. By default, it will return the string 'active', so you can use it as a class.

    // On /posts/1
    Active::path('posts/1'); // active

    <a href="posts/1" class="{{ Active::path('posts/1') }}">1st Post</a>

Of course, you can still use the array version if you like.

    // On /posts/1
    Active::path(array('posts/1', 'users/1')); // active

And pass in custom classes.

    // On /posts/1
    Active::path('posts/1', 'chickens'); // chickens

### Using named routes

However, if you're more like me, you can use named routes. Gives you a bit more control if you're not interested exactly which record a user is looking at, just that they are looking at that kind of record. LIke `paths()`, this returns an `active` class if the route is matched.

    // On posts (named posts.index)
    Active::route('posts.index'); // active

And the array version works also.

    // On a post (named posts.show)
    Active::route(array('posts.index', 'posts.show')); // active

And pass in custom classes.

    // On a post (named posts.show)
    Active::route('posts.show', 'chocolate'); // chocolate

## Helper functions

Two helper methods are provided to get the current controller and action, if your routing is being handled by a controller for a request. These functions will return the lowercase controller/action name, without the method of the request. Here is an example for a request that is routed to `FooController@getBar':

    $controller = controller_name(); // foo

    $action = action_name(); // bar