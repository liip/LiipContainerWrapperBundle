ContainerWrapperBundle
======================

Because mommy taught you to not screw DI by just injecting everything.

    1. It provides an abstract ``liip_container_wrapper.service`` service to extend from.

    2. It provides a way to easy set default service and parameters to map

    3. It can replace itself with an alias to ``service_container`` via a config
       option as long as no service/parameter is mapped to a different id/name

Installation
============

    1. Add this bundle to your project as a Git submodule:

        $ git submodule add git://github.com/liip/ContainerWrapperBundle.git vendor/bundles/Liip/ContainerWrapperBundle

    2. Add the Liip namespace to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
            'Liip' => __DIR__.'/../vendor/bundles',
            // your other namespaces
        ));

    3. Add this bundle to your application's kernel:

        // application/ApplicationKernel.php
        public function registerBundles()
        {
          return array(
              // ...
              new Liip\ContainerWrapperBundle\LiipContainerWrapperBundle(),
              // ...
          );
        }

Configuration
=============

Default services and parameters maybe configured inside the application configuration.
Setting ``disable_optimization`` to true will remove the ContainerWrapper service in favor of an
alias to ``service_container`` in all cases where no mapping is used:

    # app/config.yml
    liip_container_wrapper:
        services:
            templating: acme_hello.templating
        parameters:
            kernel.debug: true
        disable_optimization: %kernel.debug%

Both ``services`` and ``parameters`` are configured as key value pairs. The key is the id/name
that is reachable from this specific ``ContainerWrapper`` instance. The value may either be
``true`` or an id/name of a different service or parameter. In case of a non ``true`` value
the id/name will be mapped to this other id/name.

Take the above example:

    // will return an instance of the 'acme_hello.templating' service
    $container->get('templating');

    // will return an the value of the 'kernel.debug' parameter
    $container->getParameter('kernel.debug');

Note that because ``templating`` is mapped to a different service id, setting
``disable_optimization`` to ``false`` would have no effect, since a normal
``Container`` instance would not be able to support setting different alias's
for ``templating``.

Example use
===========

The following YAML configuration extends  the ``liip_container_wrapper.service`` abstract
service to define an ``acme_hello.container`` service that can be injected in place of
a ``Container`` instance that limits access to the services and parameters defined
in the bundle configuration as well as the ones defined in this configuration:

    acme_hello.foo.controller:
        class: Acme\HelloBundle\Controller\FooController
        calls:
            - ['setContainer', [ @acme_hello.container ] ]

    acme_hello.container:
        parent: liip_container_wrapper.service
        arguments:
            index_0:
                some_service: true

The story of saved kittens
==========================

Why oh why?
-----------

Yes, why oh why would someone bother to setup a nice dependency injection container
and then waste all its goodness by just injecting the entire container, thereby
effectively making their code dependent on essentially everything configured in the
DI container? I am sure god kills more than a few kittens whenever ..

Aside from the kittens, injecting the container also prevents granular adjustments
to your dependencies. Aka controller Blabla needs a different templating service
injected than controller DingDing, but how do you do that if your code uses
``$this->container->get('templating')`` in both? Praying to god is not the answer,
he is busy killing kittens anyway.

And those insane enough to bother with unit testing will also quickly realize that
its even less fun to have to wrap everything they want to inject into a container
mock object.

Oh and no IDE auto completion support without jumping through hoops is also a major
let down of injecting the container or is there an IDE yet that can parse your DIC
to figure out wtf ``$this->container->get('i_hate_kittens')`` returns?

But ok, there are many crappy answers like lazyness and such to still inject the DIC,
but there are three semi acceptable reasons:

1) someone else wrote useful code, but thought it was a great idea to require injecting
   the entire DIC

2) there are a fair bit of optional dependencies which do not really solve themselves
   by splitting up the service (actually 2) is often a reason why 1) happens even for
   code written by good people).

3) you need to inject a service before the service actually can exist, like the
   request service in Symfony2

But wait there is hope!
-----------------------

In those cases you now have a way to prevent little kittens from being slain!

Instead you can use the ContainerWrapper to explicitly configure your dependencies
again and to map hardcoded service id's to regain the flexibility that was forsaking
by not injecting the dependencies explicitly.

But parameters!
---------------

Yeah, parameters are also handled by the wrapper, though they don't really benefit
from the lazy loading argument all that much, but I guess once a developer has gone
the path of darkness, he might just keep using the DI container instead of explicitly
injecting the parameters, so yeah, probably parameter support should be added too. Evil
is just so resourceful at being evil.
