tinytpl
=======

tinyTpl is a small, robust and reliable templating engine for php5. It's
published under the 3-clause-BSD licence.

Features of tinyTpl
-------------------

tinyTpl is a php5 only templating engine. It is highly configurable and
allows almost anything to be finetuned to suit your needs.
It comes with a simply admin interface. Here are some highlights:

 * Documentation and best usage documents,
 * Statistic module which is independet of server's logging feature,
 * tinyTpl aims to catch all errors (warnings, errors and shutdowns)
   without exposing your server's internal path structure in such
   event
 * Managing plugins at runtime
 * Locking up appalication, thus disabling the entire admin module
 * Checking filesystem where tinyTpl resides, so it gives you hints where
   you may optimize your application and set proper rights in your
   filesystem
 * tinyAdmin allows to view your sourcecode which allows to spot errors

tinyTpl comes with a lightweight on-the-fly css and javascript minifier,
and a set of plugins, which extend it's own functionality.

Technically spoken
------------------

tinyTpl has been designed to be run in a singleton instance, yet being
extensible by custome written modules/hooks.
The hooks have been implemented using the observer pattern and my invoke
most of tinyTpl's master class.
tinyTpl makes use of php5's namespaces so it won't affect any existing
variables. It includes an autoloader which loads files as required, this
gives you the freedom to concentrate on the stuff that matters - your
server logic.

Overview of incorporated frameworks
-----------------------------------

 * HTML5 Boilerplate (V 4)
 * jQuery 1.8.0
 * jQuery UI 1.8.23
 * RaphaelJS 2.0.2
 * g.Raphael 0.51
