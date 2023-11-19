function addEventListeners() {
    
  let postDeleters = document.querySelectorAll('article.post button.delete-post');
  [].forEach.call(postDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeletePostRequest);
  });

  let postCreator = document.querySelector('article.post form.new_post');
  if (postCreator != null)
    postCreator.addEventListener('submit', sendCreatePostRequest);
}
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  
  function sendCreateCardRequest(event) {
    let name = this.querySelector('input[name=name]').value;
  
    if (name != '')
      sendAjaxRequest('put', '/api/cards/', {name: name}, cardAddedHandler);
  
    event.preventDefault();
  }
 
  function sendDeletePostRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    
    sendAjaxRequest('delete', '/api/post/' + id, null, postDeletedHandler);
  }

  function sendCreatePostRequest(event) {
    let name = this.querySelector('input[name=content]').value;

    if (name != '')
      sendAjaxRequest('post', '/dashboard', {content: name}, postAddedHandler);

    event.preventDefault();
  }
  
  function postDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let post = JSON.parse(this.responseText);
    let article = document.querySelector('article.post[data-id="'+ post.id + '"]');
    article.remove();
  }

  function postAddedHandler() {
    if (this.status != 200) window.location = '/';
    let post = JSON.parse(this.responseText);

    let new_post = createPost(post);

    let form = document.querySelector('article.post form.new_post');
    form.querySelector('[type=text]').value="";

    let article = form.parentElement;
    let section = article.parentElement;
    section.insertBefore(new_post, article);

    new_post.querySelector('[type=text]').focus();
  }
  
  function createCard(card) {
    let new_card = document.createElement('article');
    new_card.classList.add('card');
    new_card.setAttribute('data-id', card.id);
    new_card.innerHTML = `
  
    <header>
      <h2><a href="cards/${card.id}">${card.name}</a></h2>
      <a href="#" class="delete">&#10761;</a>
    </header>
    <ul></ul>
    <form class="new_item">
      <input name="description" type="text">
    </form>`;
  
    let creator = new_card.querySelector('form.new_item');
    creator.addEventListener('submit', sendCreateItemRequest);
  
    let deleter = new_card.querySelector('header a.delete');
    deleter.addEventListener('click', sendDeleteCardRequest);
  
    return new_card;
  }

  function createPost(post) {
      let new_post = document.createElement('article');
      new_post.classList.add('post');
      new_post.setAttribute('data-id', post.id);
      new_post.innerHTML = `
      <header>
        <h2><a href="post/${post.id}">Nome do user</a></h2>
        ${post.content}
      </header>
      <button class="delete-post" data-post-id="${post.id}" 
      type="submit">Delete</button>
      `;


      let deleter = new_post.querySelector('button.delete-post');
      deleter.addEventListener('click', sendDeletePostRequest);

      return new_post;
  } 
  
  
  
  
  addEventListeners();
  