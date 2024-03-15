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


// Define the list of tracks that have to be played

document.addEventListener('DOMContentLoaded', function() {
    
    var trackElements = document.querySelectorAll('.information-piste');
    
    
    trackElements.forEach(function(trackElement) {
        var track = {
            name: JSON.parse(trackElement.dataset.titre),
            path: JSON.parse(trackElement.dataset.path)
        };
        
        track_list.push(track);
    });
    
});
// Select all the elements in the HTML page
// and assign them to a variable
let now_playing = document.querySelector(".now-playing");
let track_art = document.querySelector(".track-art");
let track_name = document.querySelector(".track-name");
let track_artist = document.querySelector(".track-artist");

let playpause_btn = document.querySelector(".playpause-track");
let next_btn = document.querySelector(".next-track");
let prev_btn = document.querySelector(".prev-track");

let seek_slider = document.querySelector(".seek_slider");
let volume_slider = document.querySelector(".volume_slider");
let curr_time = document.querySelector(".current-time");
let total_duration = document.querySelector(".total-duration");

// Specify globally used values
let track_index = 0;
let isPlaying = false;
let updateTimer;

// Create the audio element for the player
let curr_track = document.createElement('audio');
// var track_list = [];

var track_list = [{}];



console.log(track_list)



// let track_list = track_lists;

let track_lists = [
    {
        name: "Night Owl",
        artist: "Broke For Free",
        image: "Image URL",
        path: "Night_Owl.mp3"
    }
    // {
    //     name: "Enthusiast",
    //     artist: "Tours",
    //     image: "Image URL",
    //     path: "Enthusiast.mp3"
    // },
    // {
    //     name: "Shipping Lanes",
    //     artist: "Chad Crouch",
    //     image: "Image URL",
    //     path: "Shipping_Lanes.mp3",
    // },
];

console.log(track_lists)

function loadTrack(track_index) {
    // Clear the previous seek timer
    clearInterval(updateTimer);
    resetValues();
   
    // Load a new track
    curr_track.src = track_list[track_index].path;
    curr_track.load();
   
    // Update details of the track
    // track_art.style.backgroundImage = 
    //    "url(" + track_list[track_index].image + ")";
    track_name.textContent = track_list[track_index].name;
    // track_artist.textContent = track_list[track_index].artist;
    now_playing.textContent = 
       "PLAYING " + (track_index + 1) + " OF " + track_list.length;
   
    // Set an interval of 1000 milliseconds
    // for updating the seek slider
    updateTimer = setInterval(seekUpdate, 1000);
   
    // Move to the next track if the current finishes playing
    // using the 'ended' event
    curr_track.addEventListener("ended", nextTrack);
   
    // Apply a random background color
    random_bg_color();
  }
   
//   function random_bg_color() {
//     // Get a random number between 64 to 256
//     // (for getting lighter colors)
//     let red = Math.floor(Math.random() * 256) + 64;
//     let green = Math.floor(Math.random() * 256) + 64;
//     let blue = Math.floor(Math.random() * 256) + 64;
   
//     // Construct a color with the given values
//     let bgColor = "rgb(" + red + ", " + green + ", " + blue + ")";
   
//     // Set the background to the new color
//     document.body.style.background = bgColor;
//   }
   
  // Function to reset all values to their default
  function resetValues() {
    curr_time.textContent = "00:00";
    total_duration.textContent = "00:00";
    seek_slider.value = 0;
  }

  function playpauseTrack() {
    // Switch between playing and pausing
    // depending on the current state
    if (!isPlaying) playTrack();
    else pauseTrack();
  }
   
  function playTrack() {
    // Play the loaded track
    curr_track.play();
    isPlaying = true;
   
    // Replace icon with the pause icon
    playpause_btn.innerHTML = '<i class="fa fa-pause-circle fa-5x"></i>';
  }
   
  function pauseTrack() {
    // Pause the loaded track
    curr_track.pause();
    isPlaying = false;
   
    // Replace icon with the play icon
    playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x"></i>';
  }
   
  function nextTrack() {
    // Go back to the first track if the
    // current one is the last in the track list
    if (track_index < track_list.length - 1)
      track_index += 1;
    else track_index = 0;
   
    // Load and play the new track
    loadTrack(track_index);
    playTrack();
  }
   
  function prevTrack() {
    // Go back to the last track if the
    // current one is the first in the track list
    if (track_index > 0)
      track_index -= 1;
    else track_index = track_list.length - 1;
     
    // Load and play the new track
    loadTrack(track_index);
    playTrack();
  }

  function seekTo() {
    // Calculate the seek position by the
    // percentage of the seek slider 
    // and get the relative duration to the track
    seekto = curr_track.duration * (seek_slider.value / 100);
   
    // Set the current track position to the calculated seek position
    curr_track.currentTime = seekto;
  }
   
  function setVolume() {
    // Set the volume according to the
    // percentage of the volume slider set
    curr_track.volume = volume_slider.value / 100;
  }
   
  function seekUpdate() {
    let seekPosition = 0;
   
    // Check if the current track duration is a legible number
    if (!isNaN(curr_track.duration)) {
      seekPosition = curr_track.currentTime * (100 / curr_track.duration);
      seek_slider.value = seekPosition;
   
      // Calculate the time left and the total duration
      let currentMinutes = Math.floor(curr_track.currentTime / 60);
      let currentSeconds = Math.floor(curr_track.currentTime - currentMinutes * 60);
      let durationMinutes = Math.floor(curr_track.duration / 60);
      let durationSeconds = Math.floor(curr_track.duration - durationMinutes * 60);
   
      // Add a zero to the single digit time values
      if (currentSeconds < 10) { currentSeconds = "0" + currentSeconds; }
      if (durationSeconds < 10) { durationSeconds = "0" + durationSeconds; }
      if (currentMinutes < 10) { currentMinutes = "0" + currentMinutes; }
      if (durationMinutes < 10) { durationMinutes = "0" + durationMinutes; }
   
      // Display the updated duration
      curr_time.textContent = currentMinutes + ":" + currentSeconds;
      total_duration.textContent = durationMinutes + ":" + durationSeconds;
    }
  }

  loadTrack(track_index);