// Navbar

window.addEventListener("scroll", function(){

  var nav = document.querySelector("nav");

  nav.classList.toggle('sticky', window.scrollY > 0);

});



// Contenair padding off





let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
  let slides = document.getElementsByClassName('slides');
  let dots = document.getElementsByClassName('dot');
  
  if(n > slides.length) { slideIndex = 1 }
  
  if(n < 1 ) { slideIndex = slides.length }
  
  // Cacher toutes les slides
  for(let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  
  // Retirer "active" de tous les points
  for(let i = 0; i < dots.length; i++) {
    dots[i].classList.remove('active');
  }
  
  // Afficher la slide demandée
  slides[slideIndex - 1].style.display = 'block';
  
  // Ajouter "active" sur le point cliqué
  dots[slideIndex - 1].classList.add('active');
}

// const IMG_CONTAINER_WIDTH = 500
// const DELAY = 4000

// let idx = 0
// let interval = setInterval(run, DELAY)

// function run() {
//   idx++
//   changeImage()
// }

// function changeImage() {
//   if (idx > img.length - 1) idx = 0
//   else if (idx < 0) idx = img.length - 1

//   imgs.style.transform = `translateX(${-idx * IMG_CONTAINER_WIDTH}px)`
// }

// function resetInterval() {
//   clearInterval(interval)
//   interval = setInterval(run, DELAY)
// }

// function handleButtonClick(direction) {
//   idx += direction
//   changeImage()
//   resetInterval()
// }