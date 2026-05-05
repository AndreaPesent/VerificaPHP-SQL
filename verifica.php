<?php
session_start();
if(!isset($_SESSION['login']))
{ 
    header("Location: login.php"); 
    exit(); 
}
require_once __DIR__ . '/db.php';

$flash = '';

if($_SERVER['REQUEST_METHOD']==='POST')
{
    $m = filter_input(INPUT_POST,'m',FILTER_VALIDATE_INT);
    $c = filter_input(INPUT_POST,'c',FILTER_VALIDATE_INT);

    if(!$m || !$c){
        $flash = "Selezione non valida.";
    } else {
        try {
            $q = $conn->prepare("SELECT 1 FROM Iscrizioni_Corsi WHERE id_membro=? AND id_corso=?");
            $q->execute([$m,$c]);

            if($q->fetch()){
                $flash = "Già iscritto.";
            } else {
                $conn->prepare("INSERT INTO Iscrizioni_Corsi (id_corso,id_membro,data_iscrizione) VALUES (?,?,CURDATE())")->execute([$c,$m]);

                $flash = "Iscrizione aggiunta.";
            }
        } catch(PDOException $e){
            $flash = "Errore: ".$e->getMessage();
        }
    }
}

$membri = $conn->query("SELECT id_membro,nome FROM Membri");
$corsi = $conn->query("SELECT c.id_corso, c.nome_corso, i.nome i FROM Corsi c JOIN Istruttori i USING(id_istruttore)");
?>

<?php if($flash): ?>
<p><?= htmlspecialchars($flash) ?></p>
<?php endif; ?>

<form method="post">
<select name="m">
<?php foreach($membri as $x): ?>
<option value="<?= $x['id_membro'] ?>">
<?= htmlspecialchars($x['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<select name="c">
<?php foreach($corsi as $x): ?>
<option value="<?= $x['id_corso'] ?>">
<?= htmlspecialchars($x['nome_corso']." - ".$x['i']) ?>
</option>
<?php endforeach; ?>
</select>

<button>OK</button>
</form>
