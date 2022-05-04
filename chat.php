<form action="/messenger/" method="POST">
    <input name="login", placeholder="login">
    <input name="password", placeholder="password">
    <input name="message", placeholder="message">
    <button>Send Message</button>
</form>

<?php
$requestedPath = explode('?', $_SERVER['REQUEST_URI'])[0];

$filePath = __DIR__ . '/messages.json';
$login = $_POST['login'];
$password = $_POST['password'];
$message = $_POST['message'];

SendMessage($login, $password, $message, $filePath);
PrintMessagesFromFile($filePath);

function SendMessage($login, $password, $message, $messages_filePath)
{
    if ($login != '' && $password != '' && $message != '')
    {
        $users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);
        $input_password = $users[$login];

        if ($input_password === $password)
        {
            $json_message =
            [
                    "date" => date("Y-m-d h:i",time()),
                    "login" => $login,
                    "message" => $message
            ];
            LoadMessageToFile($json_message, $messages_filePath);
        }
        else
        {
            print("Wrong Login/Password");
        }
    }
}

function LoadMessageToFile($json_message, $filePath)
{
    $messages_file = json_decode(file_get_contents($filePath));
    $messages_file->messages[] = $json_message;
    file_put_contents($filePath, json_encode($messages_file));
}

function PrintMessagesFromFile($filePath)
{
    $messages_file = json_decode(file_get_contents($filePath));
        foreach($messages_file->messages as $message)
        {
            echo "<p>$message->date $message->login: $message->message</p>";
        }
}
?>

