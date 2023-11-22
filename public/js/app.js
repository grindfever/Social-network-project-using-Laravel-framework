function addEventListeners() {
    
  let postDeleters = document.querySelectorAll('article.post button.delete-post');
  [].forEach.call(postDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeletePostRequest);
  });

  let postCreator = document.querySelector('article.post form.new_post');
  if (postCreator != null)
    postCreator.addEventListener('submit', sendCreatePostRequest);

  let postEditor = document.querySelector('button.edit-post');
  if (postEditor != null){
    postEditor.addEventListener('click', editablePost);
  }

  let messageCreator = document.querySelector('article.message form.new_message');
  if (messageCreator != null)
    messageCreator.addEventListener('submit', sendCreateMessageRequest);
}
  
function editablePost(event) {
  let content = document.querySelector('div.content');

  if (!content.isContentEditable) {
    content.setAttribute('contenteditable', 'true');
    content.focus();
    content.style.borderColor = 'red';
    // Create a new button element
    let saveButton = document.createElement('button');
    // Set button properties (e.g., text content, attributes, event listeners)
    saveButton.textContent = 'Save'; // Set button text
    saveButton.setAttribute('class', 'save-button'); // Set button class
    saveButton.addEventListener('click', function () {
      // Add functionality for when the button is clicked
      console.log('post saved');
      let updatedContent = content.textContent.trim();

      // Send AJAX request to update post
      sendUpdatePostRequest(updatedContent, content);
    });
    // Find the reference button (the button above which you want to insert the new button)
    let referenceButton = document.querySelector('.edit-post'); // Replace '.reference-button' with your reference button selector

    // Insert the new button below the reference button using insertAdjacentElement
    referenceButton.insertAdjacentElement('afterend', saveButton);
  }
  event.preventDefault();
}

function sendUpdatePostRequest(updatedContent, content) {
  let id = content.closest('article.post').getAttribute('data-id');
  let data = { content: updatedContent };

  sendAjaxRequest('put', '/api/post/' + id, data, function() {
      // Handler for the response after the content is updated
      if (this.status === 200) {
          // Content successfully updated, update the UI
          content.contentEditable = false; // Set content back to non-editable

          // Remove the 'Save' button
          let saveButton = content.nextElementSibling;
          if (saveButton && saveButton.classList.contains('save-button')) {
              saveButton.remove();
          }
      } else {
          // Handle error, for example:
          console.error('Failed to update content.');
      }
  });
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
    console.log(post);
    let new_post = createPost(post);

    let form = document.querySelector('article.post form.new_post');
    form.querySelector('[type=text]').value="";

    let article = form.parentElement;
    let section = article.parentElement;
    section.insertBefore(new_post, article);

    new_post.querySelector('[type=text]').focus();
  }
  
  function createPost(post) {
      let new_post = document.createElement('article');
      new_post.classList.add('post');
      new_post.setAttribute('data-id', post.id);
      new_post.innerHTML = `
      <header>
        <h2><a href="post/${post.id}">  ${post.user.name} </a></h2>
      </header>
      <div class="content">${post.content}</div>
      <button class="delete-post" data-post-id="${post.id}" 
      type="submit">Delete</button>
      `;

      let deleter = new_post.querySelector('button.delete-post');
      deleter.addEventListener('click', sendDeletePostRequest);


      return new_post;
  } 
  
  function sendCreateMessageRequest(event){
    let id = this.closest('article').getAttribute('data-id');
    let name = this.querySelector('input[name=content]').value;
    if (name != '')
      sendAjaxRequest('post', '/messages/'+id, {content: name}, messageAddedHandler);

    event.preventDefault();
  }

  function messageAddedHandler() {
    if (this.status != 200) window.location = '/';
    let message = JSON.parse(this.responseText);
    let new_message = createMessage(message);

    let messagesList = document.querySelector('ul.messages');
    let li = document.createElement('li');
    li.textContent = message.content;

    // Insert the new message after the last <li> element in the messages list
    messagesList.appendChild(li);

    let form = document.querySelector('article.message form.new_message');
    form.querySelector('[type=text]').value = "";

  }



  function createMessage(message) {
    let new_message = document.createElement('article');
    new_message.classList.add('message');

    new_message.innerHTML = `
      <li>${message.content}</li>
    `;
    return new_message;
}

  addEventListeners();
  