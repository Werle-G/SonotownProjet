const trackElements = document.querySelectorAll('.information-piste');

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

let track_list = [];

trackElements.forEach(function(trackElement) {
  
  let track = {
      name: JSON.parse(trackElement.dataset.titre),
      path: JSON.parse(trackElement.dataset.path)
  };
  
  track_list.push(track);
  
});

// Specify globally used values
let track_index = 0;
let isPlaying = false;
let updateTimer;

// Create the audio element for the player
let curr_track = document.createElement('audio');

function loadTrack(track_index) {
  // Clear the previous seek timer
  clearInterval(updateTimer);
  resetValues();
  
  // Load a new track
  curr_track.src = track_list[track_index].path;
  curr_track.load();
  
  // Update details of the track
  track_name.textContent = track_list[track_index].name;
  
  // Set an interval of 1000 milliseconds
  // for updating the seek slider
  updateTimer = setInterval(seekUpdate, 1000);
  
  // Move to the next track if the current finishes playing
  // using the 'ended' event
  curr_track.addEventListener("ended", nextTrack);
  
}
//  
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
  playpause_btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px" height="20px"><path d="M48 64C21.5 64 0 85.5 0 112V400c0 26.5 21.5 48 48 48H80c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zm192 0c-26.5 0-48 21.5-48 48V400c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H240z"/></svg>';
}
  
function pauseTrack() {
  // Pause the loaded track
  curr_track.pause();
  isPlaying = false;
  
  // Replace icon with the play icon
  playpause_btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20px" height="20px"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/ ></svg>';
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

// Album détail add

document.addEventListener("DOMContentLoaded", function() {

  // On stocke les différentes classes dans une variable
  var addPisteDivs = document.querySelectorAll(".add-piste");
  var addPlaylistDivs = document.querySelectorAll(".add-playlist");

  // On parcourt addPisteDivs
  addPisteDivs.forEach(function(addPisteDiv) {

    addPisteDiv.addEventListener("click", function(event) {

        var dataPiste = addPisteDiv.getAttribute("data-piste");

        var addPlaylistDiv = document.querySelector(".add-playlist[data-piste='" + dataPiste + "']");

        if (addPlaylistDiv) {

            addPlaylistDiv.classList.toggle("hidden");
        }
        event.stopPropagation(); // Stopper la propagation de l'événement pour éviter que le clic ne ferme immédiatement la liste
    });

  });

  document.addEventListener("click", function(event) {

      addPlaylistDivs.forEach(function(addPlaylistDiv) {

          if (!addPlaylistDiv.contains(event.target)) { // Vérifier si le clic ne provient pas de l'élément lui-même ou de ses enfants
              addPlaylistDiv.classList.add("hidden");
          }
      });
  });
});
