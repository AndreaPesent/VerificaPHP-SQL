<?php
session_start();
if(!isset($_SESSION['logged'])){ header("Location: login.php"); exit(); }
require_once __DIR__ . '/db.php';

$flash = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
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
                $conn->prepare("INSERT INTO Iscrizioni_Corsi (id_corso,id_membro,data_iscrizione) VALUES (?,?,CURDATE())")
                     ->execute([$c,$m]);

                $info = $conn->prepare("SELECT m.nome, c.nome_corso, i.nome istr FROM Membri m JOIN Corsi c ON c.id_corso=? JOIN Istruttori i ON i.id_istruttore=c.id_istruttore WHERE m.id_membro=?");
                $info->execute([$c,$m]);
                $r = $info->fetch();

                $flash = $r
                    ? "Iscrizione: {$r['nome']} → {$r['nome_corso']} ({$r['istr']})"
                    : "Iscrizione aggiunta.";
            }
        } catch(PDOException $e){
            $flash = "Errore: ".$e->getMessage();
        }
    }
}

$membri = $conn->query("SELECT id_membro,nome FROM Membri");
$corsi = $conn->query("
    SELECT c.id_corso, c.nome_corso, i.nome i
    FROM Corsi c JOIN Istruttori i USING(id_istruttore)
");

$top = $conn->query("
    SELECT i.nome,i.cognome,c.nome_corso,COUNT(ic.id_iscrizione) n
    FROM Corsi c
    JOIN Istruttori i USING(id_istruttore)
    JOIN Iscrizioni_Corsi ic USING(id_corso)
    GROUP BY c.id_corso
    HAVING n>=5 AND n = (
        SELECT MAX(cnt) FROM (
            SELECT COUNT(*) cnt
            FROM Corsi c2
            JOIN Iscrizioni_Corsi ic2 USING(id_corso)
            WHERE c2.id_istruttore = c.id_istruttore
            GROUP BY c2.id_corso
        ) x
    )
    ORDER BY i.cognome,i.nome
")->fetchAll();
?>

<?php if($flash): ?>
<p><?= htmlspecialchars($flash) ?></p>
<?php endif; ?>

<form method="post">
<select name="m">
<?php foreach($membri as $x): ?>
<option value="<?= $x['id_membro'] ?>"><?= htmlspecialchars($x['nome']) ?></option>
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

<h3>Top corso per istruttore (min 5)</h3>

<?php if($top): ?>
<ul>
<?php foreach($top as $r): ?>
<li><?= htmlspecialchars($r['nome']." ".$r['cognome']." → ".$r['nome_corso']." (".$r['n'].")") ?></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>Nessun risultato.</p>
<?php endif; ?>
