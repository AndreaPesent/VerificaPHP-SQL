<?php
session_start();
if ($_SERVER["REQUEST_METHOD"]=="POST") 
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username==="pesenti" && $password==="verifica") 
    {
        $_SESSION['login']=true;
        $_SESSION['username']=$username;
        header("Location: verifica.php");
        exit();
    } else 
    {
        echo "Credenziali non valide";
    }
}
?>
<form method="POST">
    <input type="text" name="username"></br>
    <input type="password" name="password"></br>
    <button type="submit">Login</button>
</form>
