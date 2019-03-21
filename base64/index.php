<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Base64</title>
  </head>
  <style>
    body {
      font-family: Helvetica Neue;
      font-size: 16px;
      line-height: 165%;
    }
    .box-style {
      border-radius: 5px;
      border: 1px solid rgba(0,0,0,.1);
      background: rgb(250,249,251);
    }
    .box-layout {
      margin: 100px auto;
      max-width: 600px;
      position: relative;
      padding: 1em 2em;
    }
  </style>
  <body>

    <div id="output" class="box-style box-layout"></div>

    <script type="text/javascript">

      var url_params = new URLSearchParams(window.location.search);
      var base64_string = decodeURIComponent( url_params.get('decode') );
      base64_string = base64_string.replace("b'", "");
      base64_string = base64_string.replace("'", "");
      base64_string = base64_string.replace(/ /g, "+");
      document.getElementById('output').innerHTML = b64DecodeUnicode( base64_string ) //

      function b64DecodeUnicode(str) {
          // Going backwards: from bytestream, to percent-encoding, to original string.
          return decodeURIComponent(atob(str).split('').map(function(c) {
              return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
          }).join(''));
      }

    </script>

  </body>
</html>