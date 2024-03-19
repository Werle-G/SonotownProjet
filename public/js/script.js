// Navbar

window.addEventListener("scroll", function(){

  var nav = document.querySelector("nav");

  nav.classList.toggle('sticky', window.scrollY > 0);

});

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
