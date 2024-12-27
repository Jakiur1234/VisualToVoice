<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome</title>
        <link rel="stylesheet" href="css/style.css">
        <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
        <div class="p-5 my-12">
            <?php if (isset($message)){
                view('partials.alert', ['message' => $message]);
            } ?>
            <?php view('partials.form', [ 'text' => isset($text) ? $text : '']); ?>
            <?php if( $is_speechable ) {
                view('partials.speech', [ 'text' => $text ]);
            }?>
        </div>
    <script src="js/main.js"></script>
</body>
</html>
