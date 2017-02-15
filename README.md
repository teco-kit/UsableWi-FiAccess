UsableWi-FiAccess
=================

This repository contains source code for different tag and context based authentication schemes for open Wi-Fi access.  The idea of those components is to provide usable and accessible WiFi Login elements.

The elements use the web components Web Components standard and can be easily included.
# Installation/Usage


To pull all dependecies call ```bower install``` 

You can use the components using [HTML Imports](https://www.html5rocks.com/en/tutorials/webcomponents/imports/)

```
 <link rel="import" href="elements/login-manager/login-manager.html">
```

The login manager provides a adaptable interfaces that checks for the availability of other login options for the Unify Example. Use it like this

```
 <login-manager logins="simple qr" url="http://captive.portal"></login-manager>
```

Login elements currently supported include:
* QR-Code Login
* Simple Login

The module provides different authentication methods (password, QR-Code, etc) for login. Each of the authentication methods has been encapsulated in a web element that works as a HTML element.
Using the login elements

First, include the webcomponents library

<script src="bower_components/webcomponentsjs/webcomponents-lite.min.js">

Next, you need to import the custom elements into the web page:

<link href="elements/elements.html" rel="import">

Once you've done this, if you need to use a normal login in your web project, you just use the normal login html tag

```
    <normal-login server="http://captive.portal"></normal-login>
````

Or if you want to use some QR code to login.

```
    <qr-login server="http://captive.portal"></qr-login>
```

To simplify the use of more than one authentification method, the module provides a login manager.

```
<body>
    <login-manager server="http://captive.portal" providers="normal nfc qr"></login-manager>
</body>
```

you only need to specify the authentication methods to be used and the module checks which methods are supported by the user's browser.

Normal Login

```
[[{"fid":"60","view_mode":"wysiwyg","fields":{},"type":"media","attributes":{"height":"1056","width":"1920","class":"img-responsive media-element file-wysiwyg"}}]]
```

QR Login
```
[[{"fid":"61","view_mode":"default","fields":{},"type":"media","attributes":{"height":"741","width":"1366","class":"img-responsive media-element file-default"}}]]
```



This is a rewrite of the original code that also included (planned to be ported):
* Audio context login
* WiFi Sheet Login
* NFC Login

For more information read the related paper:
http://www.teco.edu/~budde/publications/HCII2014_budde.pdf

# Contact
mailto:budde@teco.edu

# Use Case
The elements can be used particularly in captive portals. Unify.php has an example how to use the elements with the UniFy WiFi access points

# Licence
Copyright 2014-2015 KIT

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# Acknowledgements

The work was supported by funding from the European Union’s Seventh Framework Programme for research, technological development and demonstration under [grant agreement no 610510](http://www.prosperity4all.eu). Visit [GPII Developerspace](http://developerspace.gpii.net) to find more useful accessibility resources.


# Dependencies/Other Licenses 
- **QR Reader:** [jsqrcode](https://github.com/LazarSoft/jsqrcode) (Apache License V2.0)
- **Web Components:** [Polymer](https://github.com/Polymer/polymer) (BSD-like License)
- **Detect WebRTC features:** [DetectRTC](https://github.com/muaz-khan/DetectRTC) (MIT License)
