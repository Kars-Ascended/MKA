function show(element) {
    const content = element.nextElementSibling;
    content.style.display = content.style.display === 'block' ? 'none' : 'block';
}