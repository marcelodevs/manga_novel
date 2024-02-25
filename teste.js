document.querySelector('.toggle-capitulos').addEventListener("click", () => {
  const cap = document.querySelector('.capitulos');
  const showCap = document.querySelector('.show-cap');

  cap.classList.toggle('show');
  showCap.classList.toggle('show-capitulos');
});

function toggleCommentsTab() {
  var commentsTab = document.querySelector('.comments-tab');
  var main = document.querySelector('main');
  var link = document.querySelector('.external-link');

  commentsTab.classList.toggle('show');
  link.classList.toggle('show');
  main.classList.toggle('show-comments');
}

function stopPropagation(event) {
  event.stopPropagation();
}
