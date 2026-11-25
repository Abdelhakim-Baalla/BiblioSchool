const form = document.getElementById('book-form');
const list = document.getElementById('books');
function addBook(title, author) {
  const row = document.createElement('li');
  row.textContent = title + ' — ' + author;
  list.append(row);
}
addBook('Le Tour du monde en quatre-vingts jours', 'Jules Verne');
form.addEventListener('submit', event => {
  event.preventDefault();
  const fields = new FormData(form);
  const title = fields.get('title').trim(), author = fields.get('author').trim();
  if (title && author) {
    addBook(title, author);
    form.reset();
  }
});

form.querySelector('button').disabled = false;
