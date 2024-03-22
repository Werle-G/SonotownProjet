// Navbar

window.addEventListener("scroll", function(){

  var nav = document.querySelector("nav");

  nav.classList.toggle('sticky', window.scrollY > 0);

});

// addplaylist

// const addPiste = document.querySelector('.add-piste');
// const addPlaylist = document.querySelector('#add-playlist');

// const burger = document.querySelector('.add-piste')
// const menu = document.querySelector('#add-playlist')
// const navLinks = document.querySelectorAll('.navigation li')

// burger.addEventListener('click', () => {
//   burger.classList.toggle('active');
//   menu.classList.toggle('active')
// }) 


// navLinks.forEach(link => {
//   link.addEventListener('click', ()=> {
//     menu.classList.remove("active");
//     burger.classList.remove("active");
//   })
// })





































// $(document).ready(function() {
  
//   $('#add-piste').click(function(e) {
//       e.preventDefault();
//       var pisteContainer = $('#pistes');
//       var index = pisteContainer.children().length;
//        // Obtenez le nombre actuel de champs de formulaire
//       var prototype = pisteContainer.data('prototype');
//       var newForm = prototype.replace(/__name__/g, index); 
//       // Remplacez les placeholders __name__ par l'index actuel
//       pisteContainer.append(newForm);
//   });
// });
