

window.onload = () => {
    const FiltersForm = document.querySelector("#filters");

    // On cherche les input, on bloucle dessus et sur je mets sur chacun un addEvenListener
    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("change" , () =>{
            // On intercepte les clics
            // On récupère les données du formulaire
            const Form = new FormData(FiltersForm);
            // console.log(Form.entries);

            // On fabrique la "queryString"
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                // On ajoute tout les paramètres au fur et à mesure qu'on les reçoit
                Params.append(key, value);
                // console.log(Params.toString());
            })

            // On récupère l'url active (url sur laquelle on se trouve)
            const Url = new URL(window.location.href);
            // console.log(Url);

            // On lance la requête Ajax
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }

            }).then(response => {
                console.log(response);
            }).catch(e => alert(e));

        })
    })
}