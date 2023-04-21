
let container = document.querySelector("#container");
let url = "https://localhost:8000/api/article/all";
// async function getArticles(){
//     const json = await fetch(url);
//     const articles = await json.json();
//     articles.forEach(element => {
//         console.log(element) ;
//         let article = document.createElement("div");
//         article.setAttribute("id",element.id);
//         container.appendChild(article)
//         //const idArticle = document.createElement("p");
//         const titreArticle = document.createElement("h1");
//         titreArticle.innerText = element.titre;
//         const contenueArticle = document.createElement("p");
//         contenueArticle.innerText = element.contenu;
//         const dateArticle = document.createElement("p");
//         dateArticle.innerText = element.date.substring(0,10);
//         article.appendChild(titreArticle);
//         article.appendChild(contenueArticle);
//         article.appendChild(dateArticle);
//     });
//     //console.log(articles) ;

// }

//getArticles();
let charge = false;
const getArticle = fetch(url)
    .then(async response => {
        //vérification du code erreur du serveur (BDD hs)
        if(response.status == 500){
            //affichage de l'erreur
            container.textContent = 'le serveur est en maintenance';
        }
        //test des autres codes erreurs
        else{
            //récupére le json
            const data = await response.json();
            //cas ou tout va bien
            if(response.status==200){
                //parcour du json
                data.forEach(element => {
                    //console.log(element);
                    let article = document.createElement('div');
                    article.setAttribute('class', 'article');
                    container.appendChild(article);
                    const titre = document.createElement('h1');
                    titre.textContent =element.titre;
                    const contenu = document.createElement('p');
                    contenu.textContent =element.contenu ;
                    const date = document.createElement('p');
                    date.textContent = element.date.substring(0,10);
                    const icone = document.createElement('i');
                    icone.setAttribute('class','fa-solid fa-trash-can delete');
                    icone.setAttribute('id',element.id)
                    article.appendChild(titre);
                    article.appendChild(contenu);
                    article.appendChild(date);
                    article.appendChild(icone);
                    charge = true;
                });
            }
            //cas ou il n'y a pas d'article
            if(response.status==206){
                //affichage de l'erreur
                container.textContent = data.erreur;
            }
        }
    });

const btnDelete = document.querySelectorAll('.delete');
btnDelete.forEach(element => {
    element.addEventListener('click',() => {
        console.log(element)
    })
});
