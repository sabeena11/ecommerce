<!DOCTYPE html>
<html>
<head>
    <title>Verification Message</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


<h2>Hello, {{ $data['name'] }}!!</h2> 
<br>
    <p>Thank you for registering at our application. We are excited to have you with us.</p>
    
<strong>Your Details: </strong><br>
<strong>Name: </strong>{{ $data['name']}} <br>
<strong>Email: </strong>{{ $data['email'] }} <br>
<strong>Phone: </strong>{{ $data['phone'] }} <br>
<strong>Subject: </strong>{{ $data['subject'] }} <br>

  
Thank you!!!


</body>
</html>