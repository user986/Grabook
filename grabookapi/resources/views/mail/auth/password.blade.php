<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Password Reset</h2>

    <div>
      To reset your password, <a href="{{ URL::to('resetpassword', array($token)) }}" > Click Here</a>.
    </div>
  </body>
</html>