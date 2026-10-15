/**
 * SINEMA TEGAL - Persistent Audio Streaming Engine (Spotify / YT Music Style)
 */

(function () {
  const STORAGE_KEY = 'sinema_tegal_audio_state';
  const audio = new Audio();
  window.SinemaAudio = audio;

  let playlistQueue = [];
  let currentTrackIndex = -1;
  let currentTrackData = null;
  let isDraggingSeek = false;

  // DOM Elements
  const bottomPlayer = document.getElementById('bottomPlayer');
  const playBtn = document.getElementById('playerPlayBtn');
  const playIcon = document.getElementById('playerPlayIcon');
  const prevBtn = document.getElementById('playerPrevBtn');
  const nextBtn = document.getElementById('playerNextBtn');
  const thumbImg = document.getElementById('playerThumb');
  const titleEl = document.getElementById('playerTitle');
  const artistEl = document.getElementById('playerArtist');
  const currentTimeEl = document.getElementById('playerCurrentTime');
  const totalTimeEl = document.getElementById('playerTotalTime');
  const seekBar = document.getElementById('playerSeekBar');
  const seekFill = document.getElementById('playerSeekFill');
  const volumeSlider = document.getElementById('playerVolume');
  const lyricsDrawer = document.getElementById('lyricsDrawer');
  const lyricsContent = document.getElementById('lyricsContent');
  const lyricsTitle = document.getElementById('lyricsTitle');
  const lyricsToggleBtn = document.getElementById('lyricsToggleBtn');
  const paywallModal = document.getElementById('paywallModal');

  // Format MM:SS
  function formatTime(seconds) {
    if (isNaN(seconds)) return '00:00';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins < 10 ? '0' : ''}${mins}:${secs < 10 ? '0' : ''}${secs}`;
  }

  // Restore state from LocalStorage on page load
  function restoreState() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved) {
        const state = JSON.parse(saved);
        if (state.track) {
          setTrackUI(state.track);
          audio.src = state.track.audio_url;
          audio.currentTime = state.currentTime || 0;
          if (state.volume !== undefined) {
            audio.volume = state.volume;
            if (volumeSlider) volumeSlider.value = state.volume * 100;
          }
          if (state.isPlaying) {
            // Autoplay may require user gesture on first page load
            audio.play().then(() => updatePlayIcon(true)).catch(() => updatePlayIcon(false));
          }
        }
      }
    } catch (e) {
      console.warn('Audio state restore failed:', e);
    }
  }

  function saveState(isPlaying) {
    if (!currentTrackData) return;
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify({
        track: currentTrackData,
        currentTime: audio.currentTime,
        volume: audio.volume,
        isPlaying: isPlaying ?? !audio.paused
      }));
    } catch (e) {}
  }

  function setTrackUI(track) {
    currentTrackData = track;
    if (bottomPlayer) bottomPlayer.classList.remove('is-hidden');
    if (thumbImg) thumbImg.src = track.cover_url || '/images/default-track.jpg';
    if (titleEl) titleEl.textContent = track.title;
    if (artistEl) artistEl.textContent = track.artist + (track.album ? ` • ${track.album}` : '');
    if (lyricsTitle) lyricsTitle.textContent = `${track.title} - ${track.artist}`;
    if (lyricsContent) {
      lyricsContent.textContent = track.lyrics || 'Lirik tembang kiye durung kasedia.';
    }

    // Highlight track card in current DOM if any
    document.querySelectorAll('.track-card').forEach(el => {
      if (el.dataset.trackId == track.id) {
        el.classList.add('is-playing');
      } else {
        el.classList.remove('is-playing');
      }
    });

    // Update MediaSession API for OS player
    if ('mediaSession' in navigator) {
      navigator.mediaSession.metadata = new MediaMetadata({
        title: track.title,
        artist: track.artist,
        album: track.album || 'Sinema Tegal Music',
        artwork: [{ src: track.cover_url, sizes: '512x512', type: 'image/jpeg' }]
      });
    }
  }

  function updatePlayIcon(isPlaying) {
    if (!playIcon || !bottomPlayer) return;
    if (isPlaying) {
      playIcon.innerHTML = '&#10074;&#10074;'; // Pause icon
      bottomPlayer.classList.add('is-playing');
    } else {
      playIcon.innerHTML = '&#9654;'; // Play icon
      bottomPlayer.classList.remove('is-playing');
    }
  }

  // Play a specific track via ID
  window.playTrackById = async function (trackId) {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const response = await fetch(`/api/tracks/${trackId}/play`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (!response.ok || !data.allowed) {
        // Show Paywall / VIP Lock modal
        if (paywallModal) {
          const msgEl = document.getElementById('paywallMessage');
          if (msgEl) msgEl.textContent = data.message || 'Tembang kiye butuh Akses VIP.';
          paywallModal.classList.add('is-open');
        } else {
          alert(data.message || 'Tembang kiye khusus Anggota VIP Dulur Wasgitel.');
        }
        return;
      }

      setTrackUI(data.track);
      audio.src = data.track.audio_url;
      audio.load();
      await audio.play();
      updatePlayIcon(true);
      saveState(true);

    } catch (err) {
      console.error('Error playing track:', err);
    }
  };

  // Play entire playlist
  window.playPlaylist = async function (playlistId) {
    try {
      const res = await fetch(`/api/playlists/${playlistId}`);
      const data = await res.json();
      if (data.tracks && data.tracks.length > 0) {
        playlistQueue = data.tracks;
        currentTrackIndex = 0;
        window.playTrackById(playlistQueue[0].id);
      }
    } catch (e) {
      console.error('Failed to load playlist:', e);
    }
  };

  // Audio Event Listeners
  audio.addEventListener('play', () => {
    updatePlayIcon(true);
    saveState(true);
  });

  audio.addEventListener('pause', () => {
    updatePlayIcon(false);
    saveState(false);
  });

  audio.addEventListener('timeupdate', () => {
    if (!isDraggingSeek) {
      const current = audio.currentTime;
      const duration = audio.duration || 1;
      const pct = (current / duration) * 100;
      if (seekFill) seekFill.style.width = `${pct}%`;
      if (currentTimeEl) currentTimeEl.textContent = formatTime(current);
      if (totalTimeEl && !isNaN(audio.duration)) totalTimeEl.textContent = formatTime(audio.duration);
    }
  });

  audio.addEventListener('ended', () => {
    if (playlistQueue.length > 0 && currentTrackIndex < playlistQueue.length - 1) {
      currentTrackIndex++;
      window.playTrackById(playlistQueue[currentTrackIndex].id);
    } else {
      updatePlayIcon(false);
      saveState(false);
    }
  });

  // UI Controls Bindings
  if (playBtn) {
    playBtn.addEventListener('click', () => {
      if (!audio.src) return;
      if (audio.paused) {
        audio.play();
      } else {
        audio.pause();
      }
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      if (audio.currentTime > 3) {
        audio.currentTime = 0;
      } else if (playlistQueue.length > 0 && currentTrackIndex > 0) {
        currentTrackIndex--;
        window.playTrackById(playlistQueue[currentTrackIndex].id);
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      if (playlistQueue.length > 0 && currentTrackIndex < playlistQueue.length - 1) {
        currentTrackIndex++;
        window.playTrackById(playlistQueue[currentTrackIndex].id);
      } else {
        audio.currentTime = 0;
      }
    });
  }

  // Seekbar click & drag
  if (seekBar) {
    seekBar.addEventListener('click', (e) => {
      if (!audio.duration) return;
      const rect = seekBar.getBoundingClientRect();
      const clickPos = (e.clientX - rect.left) / rect.width;
      audio.currentTime = clickPos * audio.duration;
    });
  }

  // Volume slider
  if (volumeSlider) {
    volumeSlider.addEventListener('input', (e) => {
      const vol = e.target.value / 100;
      audio.volume = vol;
      saveState();
    });
  }

  // Lyrics drawer toggle
  if (lyricsToggleBtn && lyricsDrawer) {
    lyricsToggleBtn.addEventListener('click', () => {
      lyricsDrawer.classList.toggle('is-open');
    });
  }

  const closeLyricsBtn = document.getElementById('closeLyricsBtn');
  if (closeLyricsBtn && lyricsDrawer) {
    closeLyricsBtn.addEventListener('click', () => {
      lyricsDrawer.classList.remove('is-open');
    });
  }

  // Close modals
  document.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.modal-backdrop').forEach(m => m.classList.remove('is-open'));
    });
  });

  // Init
  document.addEventListener('DOMContentLoaded', () => {
    restoreState();
  });
})();
