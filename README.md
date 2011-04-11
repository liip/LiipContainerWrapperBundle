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
        $ git submodule add git://github.com/lsmith77/Symfony2-Container-service-wrapper.git vendor/lsmith77

    2. Add the Liip and lsmith77 namespaces to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
            'Liip' => __DIR__.'/../vendor/bundles',
            'lsmith77' => __DIR__.'/../vendor',
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
-------------

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

Example use
-----------

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
