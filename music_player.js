document.addEventListener("DOMContentLoaded", function () {
    const audioPlayer = document.getElementById("audio-player");
    const randomPlayButton = document.getElementById("random-play-button");

    let playlist = []; // Tableau pour stocker les musiques
    let currentIndex = 0; // Index de la musique en cours

    // Remplissez le tableau de playlist avec les données de la base de données
    // Assurez-vous d'avoir les informations nécessaires comme les ID, les chemins de fichiers, etc.
    <?php
    $query = "SELECT * FROM musics";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "playlist.push({ id: " . $row['id'] . ", title: '" . $row['title'] . "', filePath: '" . $row['file_path'] . "' });";
        }
    }
    ?>

    // Fonction pour jouer une musique aléatoire avec fondu
    function playRandomMusicWithFade() {
        const randomIndex = Math.floor(Math.random() * playlist.length);

        // Mettez à jour l'index actuel
        currentIndex = randomIndex;

        // Commencez le fondu en diminuant progressivement le volume
        let volume = 1;
        const fadeOutInterval = setInterval(function () {
            if (volume > 0) {
                volume -= 0.1;
                audioPlayer.volume = volume;
            } else {
                // Mettez à jour la source audio et commencez à jouer la nouvelle musique
                audioPlayer.src = playlist[currentIndex].filePath;
                audioPlayer.play();

                // Commencez le fondu en augmentant progressivement le volume
                let fadeInVolume = 0;
                const fadeInInterval = setInterval(function () {
                    if (fadeInVolume < 1) {
                        fadeInVolume += 0.1;
                        audioPlayer.volume = fadeInVolume;
                    } else {
                        // Arrêtez le fondu une fois que le volume atteint 1
                        clearInterval(fadeInInterval);
                    }
                }, 100);

                // Arrêtez le fondu sortant une fois que la nouvelle musique commence
                clearInterval(fadeOutInterval);
            }
        }, 100);
    }

    // Ajoutez un gestionnaire d'événements pour le bouton de lecture aléatoire
    randomPlayButton.addEventListener("click", function () {
        playRandomMusicWithFade();
    });

    // Ajoutez un gestionnaire d'événements pour l'événement de fin de lecture
    audioPlayer.addEventListener("ended", function () {
        // Jouez la musique suivante avec fondu
        playRandomMusicWithFade();
    });
});