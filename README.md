prails
======

You can call it Php On Rails. Php Rails. PHPoR. But all those name are taken somehow. So I decided to come
with a closer, but unused approach: PRAILS.

Prails is a Rails inspired PHP MVC Framework. Instead of bringing a heavyweight
set of classes and assets, this framework is very simple but works just as any other one.
You can start with your own models or you can create your own. Support for MySQL and fixtures
come out of the box. Support for other databases is coming in the near future. Full
support for Fat Controllers or Fat Models. You choose. Support for private and public pages is 
included, so you don't have to mix everything under one view directory. Templates support is
included, so you can rely on a single page to do your UI.

Since it runs over PHP 5, the compatibility is only limited by PHP itself. That means it runs in Linux, Windows, Mac, etc.

It comes with its own test class to avoid the installation of additional test frameworks. The results
are jUnit compatible if you want to integrate the project with automated builders. Just point your
parser to tests/results and your done. 

GETTING STARTED:

The easiest way to get started with PRAILS is to write the basic “Hello World”, the “Prails” way.

Step 1: Create your model.<br />

In the directory “/app/models” lets create the file hello.model.php (The naming is optional):

class hello_model{
}

We will only need a basic variable and no methods. 

class hello_model{
	var $name;
}

Step 2: Create your controller

In the directory “/app/controllers” lets create the file hello.controller.php (The naming is optional):

class hello_controller{

public function __construct() {
    		$this->DynamicCall();
  	}
}

All controllers must be initialized with this construction method in order to inherit the full variable capture of the framework.
Now lets create an action called “world” that sets a variable “response” and then calls the Prails RenderView method:

class hello_controller{

public function __construct() {
    		$this->DynamicCall();
  	}

public function world() {
    		$this->response = “Hello world”;
return $this->RenderView();
  	}
}

Step 3: Create your view

In the directory “/app/views” lets create the file world.php (The naming is strict, and matches the action if you expect Prails to render the action result):

<html>
<body>
	<? print $this->response?>
</body>
</html>

Step 4: Run your application

If you have the application running in your server, go to the following URL:

http://localhost/application_path/?hello/world

You must see:

Hello world

TODO LIST:

a) Self creating command: create the structure of the application with a PHP command.
b) Model and Controller generation from existing databases.
c) Scaffolding.
d) Database migrations.
e) Online documentation.
f) PRQL: PRails Query Language.
