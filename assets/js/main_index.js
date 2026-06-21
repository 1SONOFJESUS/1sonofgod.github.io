/* ---------- MENU BURGER ---------- */
function toggleMenu(){
  document.getElementById('menu').classList.toggle('open');
}

/* ---------- LOGIN DROPDOWN ---------- */
function toggleLogin(){
  document.getElementById('loginMenu').classList.toggle('open');
}

// Fermer le menu login en cliquant ailleurs
document.addEventListener('click', function(e){
  const dropdown = document.getElementById('loginDropdown');
  const menu = document.getElementById('loginMenu');
  if (!dropdown.contains(e.target)) {
    menu.classList.remove('open');
  }
});

/* ---------- VIDÉO ---------- */
function playVideo(){
  const video = document.getElementById('presentationVideo');
  const overlay = document.getElementById('videoOverlay');
  if(video.paused){
    video.play();
    overlay.style.opacity = '0';
    overlay.style.pointerEvents = 'none';
  }
}

// Réafficher l'overlay quand la vidéo est en pause
document.getElementById('presentationVideo').addEventListener('pause', function(){
  const overlay = document.getElementById('videoOverlay');
  overlay.style.opacity = '1';
  overlay.style.pointerEvents = 'auto';
});

/* ---------- REVEAL ON SCROLL ---------- */
function revealInit(){
  const els = document.querySelectorAll('section, .card, .quote');
  if(!('IntersectionObserver' in window)){
    els.forEach(e => e.classList.add('in'));
    return;
  }
  const io = new IntersectionObserver(es => es.forEach(en => {
    if(en.isIntersecting){
      en.target.classList.add('in');
      io.unobserve(en.target);
    }
  }), {threshold: .06});
  els.forEach(e => {e.classList.add('reveal'); io.observe(e);});
  setTimeout(() => els.forEach(e => e.classList.add('in')), 700);
}
revealInit();