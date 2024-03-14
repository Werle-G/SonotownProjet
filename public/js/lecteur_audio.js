//  On récupère tout les éléments necessaire à notre application
const audio = document.querySelector("audio");
const track = document.querySelector("#track");
const elapsed = document.querySelector("#elapsed");
const tracktime = document.querySelector("#track-time");
const playButton = document.querySelector("#play-button");
const pauseButton = document.querySelector("#pause-button");
const volume = document.querySelector("#volume");
const volumeValue = document.querySelector("#volume-value");

// On récupère la durée du mp3
let duration = audio.duration;

tracktime.textContent = buildDuration(duration);

// On gère le bouton play
playButton.addEventListener("click", function(){
    audio.pause();
    pauseButton.computedStyleMap.display = "initial";
    stopButton.computedStyleMap.display = "initial";
    this.style.display = "none";
});

// On gère le boutton pause
pauseButton.addEventListener("click", function(){
    audio.pause();
    playButton.computedStyleMap.display = "initial";
    this.style.display = "none";
})

// On gère le bouton stop
stopButton.addEventListener("click", function(){
    audio.pause();
    audio.currentTime = 0;
    playButton.computedStyleMap.display = "initial";
    pauseButton.computedStyleMap.display = "none";
    this.style.display = "none";
});

function buildDuration(duration){
    let minutes = Math.floor(duration / 60);
    let reste = duration % 60;
    let secondes = Math.floor(reste);
    secondes = String(secondes).padStart(2, "0");
    return minutes + ":" + secondes;
    consol.log(reste);
    
}