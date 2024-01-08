
const addToFavorites = async () => {
    const filmName = "Black Panther Wakanda Forever"; // Sostituisci con il nome corretto del film
    try {
        const response = await fetch('aggiungi_ai_preferiti.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `film_name=${encodeURIComponent(filmName)}`,
        });

        if (response.ok) {
            console.log('Film aggiunto ai preferiti con successo!');
        } else {
            console.error('Errore durante l\'aggiunta ai preferiti:', response.status);
        }
    } catch (error) {
        console.error('Errore durante la richiesta:', error);
    }
};

