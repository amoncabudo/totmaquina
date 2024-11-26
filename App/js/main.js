document.querySelector('button').addEventListener('click', function() {
  const dropdown = this.nextElementSibling;
  dropdown.classList.toggle('hidden');
});