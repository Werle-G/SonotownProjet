//  On récupère tout les éléments necessaire à notre application
const audio = document.querySelector("audio");
const track = document.querySelector("#track");
// elapsed : début du morceau
const elapsed = document.querySelector("#elapsed");
// trackTime : durée du morceau
const trackTime = document.querySelector("#track-time");
const playButton = document.querySelector("#play-button");
const pauseButton = document.querySelector("#pause-button");
const stopButton = document.querySelector("#stop-button");
const volume = document.querySelector("#volume");

const volumeValue = document.querySelector("#volume-value");

// On récupère la durée du mp3
// duration determine en secondes la longueur du support
let duration = audio.duration;

// Affiche la durée en secondes et en minutes
trackTime.textContent = buildDuration(duration);

// On gère le bouton play
playButton.addEventListener("click", function(){
    // démarre le morceau
    audio.play();
    audio.volume = volume.value
    // affiche le bouton pause
    pauseButton.style.display = "initial";
    // affiche le bouton stop
    stopButton.style.display = "initial";
    // n'affiche pas le bouton play
    this.style.display = "none";
});

// On gère le boutton pause
pauseButton.addEventListener("click", function(){
    // met en pause le morceau
    audio.pause();
    // affiche le bouton play
    playButton.style.display = "initial";
    // n'affiche pas le bouton pause
    this.style.display = "none";
});

// On gère le bouton stop
stopButton.addEventListener("click", function(){
    // met en pause le morceau
    audio.pause();
    // revient au début du morceau
    audio.currentTime = 0;
    // affiche bouton play
    playButton.style.display = "initial";
    // n'affiche pas bouton pause
    pauseButton.style.display = "none";
    // n'affiche pas le bouton pause
    this.style.display = "none";
});

// Fonction pour la barre défilement du morceau
audio.addEventListener("timeupdate", function(){

    track.value = this.currentTime;
    elapsed.textContent = buildDuration(this.currentTime);
});

track.addEventListener("input", function(){
    elapsed.textContent = buildDuration(this.value);
    audio.currentTime = this.value;
})

volume.addEventListener("input", function(){
    audio.volume = this.value;
    volumeValue.textContent = this.value * 100 + "%";
})

// Fonction pour afficher la durée du morceau en minutes et secondes
function buildDuration(duration){
    let minutes = Math.floor(duration / 60);
    let reste = duration % 60;
    let secondes = Math.floor(reste);
    secondes = String(secondes).padStart(2, "0");
    return minutes + ":" + secondes;
}


// Multipiste  