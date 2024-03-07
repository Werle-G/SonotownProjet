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

// CollectionType Piste dans album

$(document).ready(function() {
  
  $('#add-piste').click(function(e) {
      e.preventDefault();
      var pisteContainer = $('#pistes');
      var index = pisteContainer.children().length;
       // Obtenez le nombre actuel de champs de formulaire
      var prototype = pisteContainer.data('prototype');
      var newForm = prototype.replace(/__name__/g, index); 
      // Remplacez les placeholders __name__ par l'index actuel
      pisteContainer.append(newForm);
  });
});