(() => {
  const links = [...document.querySelectorAll('[data-gallery]')];
  const dialog = document.getElementById('gallery-dialog');
  if (!dialog || typeof dialog.showModal !== 'function') return;
  const image = document.getElementById('gallery-image');
  const stage = document.getElementById('gallery-stage');
  const zoom = document.getElementById('gallery-zoom');
  let index = 0;
  let opener;
  let previousOverflow;
  function setZoom(expanded) {
    stage.classList.toggle('is-zoomed', expanded);
    zoom.setAttribute('aria-pressed', String(expanded));
    zoom.textContent = expanded ? 'Perkecil −' : 'Perbesar +';
    stage.scrollTop = stage.scrollLeft = 0;
  }
  function show(next) {
    index = (next + links.length) % links.length;
    image.src = links[index].href;
    image.alt = links[index].querySelector('img').alt;
    document.getElementById('gallery-caption').textContent = `${index + 1} / ${links.length} — ${image.alt}`;
    setZoom(false);
  }
  links.forEach((link, position) => link.addEventListener('click', event => {
    if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    event.preventDefault();
    opener = link;
    show(position);
    previousOverflow = document.body.style.overflow;
    dialog.showModal();
    document.body.style.overflow = 'hidden';
  }));
  document.getElementById('gallery-prev').addEventListener('click', () => show(index - 1));
  document.getElementById('gallery-next').addEventListener('click', () => show(index + 1));
  document.getElementById('gallery-close').addEventListener('click', () => dialog.close());
  const toggleZoom = () => setZoom(!stage.classList.contains('is-zoomed'));
  zoom.addEventListener('click', toggleZoom);
  image.addEventListener('click', toggleZoom);
  dialog.addEventListener('keydown', event => {
    if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
      event.preventDefault();
      show(index + (event.key === 'ArrowLeft' ? -1 : 1));
    }
  });
  dialog.addEventListener('click', event => {
    const bounds = dialog.getBoundingClientRect();
    if (event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) dialog.close();
  });
  dialog.addEventListener('close', () => {
    document.body.style.overflow = previousOverflow;
    setZoom(false);
    opener?.focus({ preventScroll: true });
  });
})();
