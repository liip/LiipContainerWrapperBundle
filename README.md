ContainerWrapperBundle
======================

Because mommy taught you to not screw DI by just injecting everything.

Installation
============

    1. Add this bundle to your project as a Git submodule:

        $ git submodule add git://github.com/liip/ContainerWrapperBundle.git vendor/bundles/Liip/ContainerWrapperBundle
        $ git submodule add git://github.com/lsmith77/Symfony2-Container-service-wrapper.git vendor/lsmith77

    2. Add the FOS namespace to your autoloader:

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
            my_templating: templating
        parameters:
            kernel.debug: true
        disable_optimization: %kernel.debug%

