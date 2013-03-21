
![tinyTpl Logo](https://raw.github.com/derRaphael/tinytpl/master/tpl/config_tpl/assets/img/128/tinyTpl.png) tinytpl
=============================================================================================================

tinyTpl is a small, robust and reliable templating engine for php5. It's
published under the 3-clause-BSD licence.

Current stable version is 0.2.7

* * *

Features of tinyTpl
-------------------

tinyTpl is a php5 only templating engine. It is highly configurable and
allows almost anything to be finetuned to suit your needs.

It comes with an easy to use admin interface. Here are some highlights:

 * Documentation and best usage documents,
 * statistic module which is independent of your providers/server's
   logging feature,
 * tinyTpl aims to catch all errors (warnings, errors, exceptions and
   shutdowns) wherever possible, without exposing your server's internal
   path structure in such event,
 * managing plugins at runtime,
 * lock up your application, thus disabling the entire admin module,
 * checking filesystem and permissions where tinyTpl resides, so it gives
   you hints where you may optimize your application and set proper
   rights in your filesystem,
 * tinyAdmin allows to view your sourcecode which allows to spot errors,
 * configuration-files for nginx/lighttpd webserver

tinyTpl comes with a lightweight on-the-fly css and javascript minifier,
and a set of plugins, which extend it's own functionality.

* * *

Technically spoken
------------------

tinyTpl has been designed to be run in a singleton instance, yet being
extensible by custom written modules/hooks.

The hooks have been implemented using the observer pattern and may be
invoked in most of tinyTpl's master class methods.

tinyTpl makes use of php5's namespaces, so it won't affect any other
existing variables.

It includes an autoloader which loads files as required, this gives you
the freedom to concentrate on the stuff that matters - your server logic.

* * *

Overview of well known incorporated frameworks
----------------------------------------------

 * [HTML5 Boilerplate (V 4)](http://html5boilerplate.com)
 * [jQuery 1.8.0](http://jquery.com)
 * [jQuery UI 1.8.23](http://jqueryui.com)
 * [RaphaelJS 2.0.2](http://raphaeljs.com)
 * [g.Raphael 0.51](http://g.raphaeljs.com)

* * *

Screenshots of tinyAdmin mode
-----------------------------

![tinyTpl tinyAdmin](https://raw.github.com/derRaphael/tinytpl/master/doc/artwork/readme-sc/tiny-admin-overview.png)

This is the basic tinyAdmin menu, you'll see, once you initialized
tinyAdmin with your password. From here, you may not only access the
build in documentation, but the admin-only menu items, too.

![tinyTpl tinyStats](https://raw.github.com/derRaphael/tinytpl/master/doc/artwork/readme-sc/tiny-stats.png)

The tinyStats module, gives you an insight of how your page is visited.

![tinyTpl tinyHooks](https://raw.github.com/derRaphael/tinytpl/master/doc/artwork/readme-sc/tiny-hooks.png)

Within the Hook-Manager you may en- or disable the features you need.

![tinyTpl tinySourceview](https://raw.github.com/derRaphael/tinytpl/master/doc/artwork/readme-sc/tiny-sourceview.png)

The sourceview is a simple helper which does as its name suggest. It lets
you view the sourcecode.

![tinyTpl tinySourceview](https://raw.github.com/derRaphael/tinytpl/master/doc/artwork/readme-sc/tiny-error-overview.png)

This is an overview of the different error pages tinyTpl shows.

![tinyTpl tinySourceview](https://raw.github.com/derRaphael/tinytpl/master/doc/artwork/readme-sc/tinyPageAnnotation.png)

tinyPageAnnotation allows you to add annotations to any page within your
sets. It is only accessible when logged in as admin - so regular visitors
won't see your private notes for a page.

* * *

Changelog
=========

v0-2-7
------
* Smaller bugfixes, fixed tinyAdmin template selection
* Fixed spelling error in error_tpl's css

v0-2-6
------
* Fixed annoying bug in tinyLinkBeau to work properly with fragments and get-parameters.
* Added capability to be run directly from php5.4's webserver, 
* Minor Fixups in tinyAdmin, 
* changed hook behaviour, 
* renamed observer interface and added new observer sbtract class.
* Also update of documentation
* Fixed changelog format
* Using Version 4.1 of h5bp, update jQuery to 1.9, 
* renamed /lib/misc to /lib/vendor and updated checks
* Fixed error demo's nonexistent page
* Fixed partial output dump on internal errors
* Changed look and feel to be a bit less dark and a bit more friendly,
* added new buttons,
* If possible, all exceptions will be now stored for latter review

v0-2-5
------
* Fixed bug in non-existent template handling
* Changed behaviour of tinyMongo and it's init hook
* Fixed tpltrigger undefined variable handler
* Improved tiny's ajax error dump to throw a customized Error into console
* Fixed lazy typo in source documentation
* Fixed filenotfound error in hooks.php
* Added php cli script to create nginx config file and bash script to sanitize ownerships of tiny core folders

v0-2-4
------
* Fixed bug in tinySimpleLog's getValidDaysFolderList()
* Fixed behaviour in special.php so minified js wont be minified again
* Fixed mini_css and mini_js functions to check for accessible cache folder
* Fixed autoloader to support less static naming conventions
* Catching Exceptions of JS Minifier
* Added tinyMongo and its hook to invoke mongoDb by triggering a custom hook

v0-2-3
------
Official initial release. This version includes various fixups and an update checker for easier future checks.

v0-2-2
------
pre Release Candidate


Licence
=======

    tinyTpl

    Copyright 2013 derRaphael

    Tiny aims to be a small fast and reliable templating engine.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are
    met:

    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above
      copyright notice, this list of conditions and the following disclaimer
      in the documentation and/or other materials provided with the
      distribution.
    * Neither the name of the  nor the names of its
      contributors may be used to endorse or promote products derived from
      this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
    LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
    A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
    OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
    SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
    LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
    DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
    THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

