tinytpl
=======

![tinyTpl Logo](//github.com/derRaphael/tinytpl/blob/master/tpl/config_tpl/assets/img/128/tinyTpl.png)

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
extensible by custom written modules/hooks.

The hooks have been implemented using the observer pattern and may invoke
most of tinyTpl's master class.

tinyTpl makes use of php5's namespaces so it won't affect any other existing
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

Licence
=======

    tinyTpl

    Copyright 2012 derRaphael

    Version 0.2.2

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
