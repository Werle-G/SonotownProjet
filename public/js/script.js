// const hamburger = document.querySelector(".hamburger");
// const navMenu = document.querySelector(".nav-menu");

// hamburger.addEventListener("click", () => {
//   hamburger.classList.toggle("active");
//   navMenu.classList.toggle("active"); 
// })

// document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
//   hamburger.classList.remove("active");
//   navMenu.classList.remove("active");
// }))

// Slider Home

// Déclare une variable slideIndex
// let slideIndex = 1;
// showSlides(slideIndex);

// // Next/previous controls
// function plusSlides(n) {
//   showSlides(slideIndex += n);
// }

// // Vignette image 
// function currentSlide(n) {
//   showSlides(slideIndex = n);
// }

// //  Fonction qui permet d'afficher les images et de les faire défiler
// function showSlides(n) {

//   let i;
//   let slides = document.getElementsByClassName("mySlides");
//   let dots = document.getElementsByClassName("dot");

// // Si le nombre d'image est supérérieur , l'index s'initialise à 1.
//   if (n > slides.length) {slideIndex = 1}

// // Si la longueur de slides est < 1 , le nombre de slides = slideIndex
//   if (n < 1) {slideIndex = slides.length}

// // À chaque itération de la boucle, cette ligne accède à l'élément à l'indice i dans le tableau slides et définit sa propriété de style display sur "none". 
//   for (i = 0; i < slides.length; i++) {
//     slides[i].style.display = "none";
//   }


//   for (i = 0; i < dots.length; i++) {
//     dots[i].className = dots[i].className.replace(" active", "");
//   }
//   //  i est égale à 0 , tant que i est < la longueur de dots(les points), i est incrémenté de 1



// //   dots[i]: pointe un élément du tableau dots

// // .className: accède à la classe

// // .replace(" active", ""): Remplace les occurences de la chaine activer par une chaine vide.

// // dots[i].className = ...: Enfin, cette partie réaffecte la classe CSS modifiée à l'élément, mettant ainsi à jour ses classes.

// // Cette ligne de code enlève la classe "active" de l'élément dots[i] s'il en possède une. Desactive un élément lorsqu'il n'est pas selectionné.

//   slides[slideIndex-1].style.display = "block";


//   dots[slideIndex-1].className += " active";


// }

// 
const imgs = document.querySelector('#imgs')
const leftBtn = document.querySelector('#left')
const rightBtn = document.querySelector('#right')
const img = imgs.querySelectorAll('img')
const IMG_CONTAINER_WIDTH = 500
const DELAY = 4000

let idx = 0
let interval = setInterval(run, DELAY)

function run() {
  idx++
  changeImage()
}

function changeImage() {
  if (idx > img.length - 1) idx = 0
  else if (idx < 0) idx = img.length - 1

  imgs.style.transform = `translateX(${-idx * IMG_CONTAINER_WIDTH}px)`
}

function resetInterval() {
  clearInterval(interval)
  interval = setInterval(run, DELAY)
}

function handleButtonClick(direction) {
  idx += direction
  changeImage()
  resetInterval()
}

rightBtn.addEventListener('click', () => handleButtonClick(1))
leftBtn.addEventListener('click', () => handleButtonClick(-1))
