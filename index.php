<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music List</title>
    <link rel="stylesheet" href="style.css">
    <script src="music_player.js"></script>

    <!-- Ajoutez ici vos liens vers les fichiers CSS ou d'autres ressources -->
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>MA PLAYLIST</h1>
            <img src="image/spotify.png" alt="Spotify Logo" style="width: 150px;">
            <!-- Remplacez "spotify.png" par le nom de votre image et ajustez le chemin si nécessaire -->
        </div>

        <?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["music_id"]) && isset($_POST["vote"])) {
    $musicId = $_POST["music_id"];
    $vote = $_POST["vote"];

    if ($vote == -1) {
        // Dislike
        $updateQuery = "UPDATE musics SET dislikes = dislikes + 1 WHERE id = $musicId";
    } elseif ($vote == 1) {
        // Like
        $updateQuery = "UPDATE musics SET votes = votes + 1 WHERE id = $musicId";
    }

    $conn->query($updateQuery);
}

// Ajout de la gestion de la réinitialisation globale
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_all"])) {
    $resetAllQuery = "UPDATE musics SET votes = 0, dislikes = 0";
    $conn->query($resetAllQuery);
}

// Modification de la requête SQL pour trier par ordre décroissant de la différence entre likes et dislikes
$query = "SELECT *, (votes - dislikes) AS vote_diff FROM musics ORDER BY vote_diff DESC";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='music-item'>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<audio controls><source src='" . $row['file_path'] . "' type='audio/mp3'></audio>";
            echo "<div class='vote-info'>"; // Ajout de la div vote-info
            echo "<div class='like-dislike-count'>";
            echo "<span class='like-count'>" . $row['votes'] . " Like" . ($row['votes'] != 1 ? "s" : "") . "</span>";
            echo "<span class='dislike-count'>" . $row['dislikes'] . " Dislike" . ($row['dislikes'] != 1 ? "s" : "") . "</span>";
            echo "</div>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='music_id' value='" . $row['id'] . "'>";
            echo "<div class='vote-buttons'>"; // Ajout de la div vote-buttons
            echo "<button class='vote-button' type='submit' name='vote' value='1'>Like</button>";
            echo "<button class='vote-button' type='submit' name='vote' value='-1'>Dislike</button>";
            echo "</div>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
        // Ajoutez le formulaire de réinitialisation en dehors de la boucle
        echo "<form method='post' action=''>";
        echo "<div class='vote-buttons'>";
        echo "<button class='vote-button' type='submit' name='reset_all' value='1'>Reset All</button>";
        echo "<button id='random-play-button'>Lecture aléatoire</button>";
        echo "</div>";
        echo "</form>";
    } else {
        echo "Aucune musique trouvée.";
    }
} else {
    echo "Erreur lors de l'exécution de la requête : " . $conn->error;
}

$conn->close();
?>


    </div>

</body>

</html>