EDITIZE 2.1.1 README
====================

CONTENTS

1 Introduction
2 Where's the Manual?
3 Known Problems

1 Introduction
--------------

Editize is designed as a drop-in replacement for the HTML <textarea> tag.
Instead of plain text, it allows your users to edit richly formatted
documents. When the form is submitted, Editize fields are submitted as
simple HTML documents. Like <textarea>, Editize fields can also be passed an
initial document to display when the page loads.

This distribution will allow you to install Editize on your local machine
for evaluation purposes. If you want to use Editize on a Web site, or on
some other machine on your network, you'll also need to obtain a FREE trial
license file from our Web site.

For more information about Editize in general, to obtain a FREE trial
license, or to see a demonstration of it in action, please visit us at:

  http://editize.com/

Some other URLs that may be of use:

  Obtain a Demo license to trial Editize on your Web site:
    http://demo.editize.com/

  Order Editize:
    http://register.editize.com/

  Manage your license files:
    http://login.editize.com/

2 Where's the Manual?
---------------------

Product documentation is in HTML format and may be found in in the 'manual'
directory of this distribution. Open manual/index.html in a Web browser to
view it.

Licensing information may be found in license.txt.

The history of changes in this release may be found in history.txt.

3 Known Problems
----------------

The following known problems exist in this version of Editize. We believe
they are all relatively minor. They will be corrected, where possible, in a
future release.

If any of these issues affects the usability of Editize in your application,
please notify our support staff so that a fix can be assigned greater
priority.

 - Editize cannot download its license file through recent versions of
   Microsoft Proxy Server, as this server uses a non-standard authentication
   mechanism that Java does not support. See the 'Troubleshooting' secton of
   the Editize manual for details and a possible work-around.

 - The popup menu behaves strangely when multiple instances of Editize
   are open on the same page. To reproduce, right-click in one instance,
   then another, then click in the second instance. The popup should
   go away, but it doesn't until you've clicked at least once in the first
   instance.

 - With no selection, click Bold. The button reflects the new state. Move
   the cursor. The button no longer reflects the state, even though typing
   will still produce bold text now. Java maintains 'cursor-only' attributes
   even when the caret moves, even though the button states do not.

                                - THE END -