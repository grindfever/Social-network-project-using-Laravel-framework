function addEventListeners() {
    
  let postDeleters = document.querySelectorAll('article.post button#delete-post');
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

  function sendDeletePostRequest() {
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
    console.log(post.id);
    
    let div = document.getElementById(post.id);
    div.remove();
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


    let content = document.getElementById('content');
    content.scrollTop = content.scrollHeight;
  }
  
  function createPost(post) {
      let new_post = document.createElement('article');
      console.log(post);
      new_post.classList.add('post');
      new_post.setAttribute('data-id', post.id);
      new_post.innerHTML = `
      <div class="card-header"><a href="post/${post.id}">  ${post.user.name} </a></div>
      <div class="card-body">
        <p class="card-text">
          <div class="content">${post.content}</div>
        </p>
      </div>
      <div class="like-post">
      <button type="submit" class="like-count">
        <span class="far fa-heart"></span> 0
      </button>
      <button class="delete-post" data-post-id="${post.id}" type="submit">Delete</button>

  
      <form action="api/post/${post.id}/comment" method="POST">
        <div class="mb-3">
          <textarea class="fs-6 form-control" name="content" rows="1" placeholder="Whats on your mind?"></textarea>
        </div>
      <button type="submit" class="btn btn-primary btn-sm"> Post Comment </button>
      </form>
      `;

      let deleter = new_post.querySelector('button.delete-post');
      deleter.addEventListener('click', sendDeletePostRequest);
      
      // Create a div element, add a class to it, and append new_post to it
      let wrapper = document.createElement('div');
      wrapper.classList.add('card', 'border-dark', 'mb-3');
      wrapper.id = post.id;
      wrapper.style.maxWidth = '20rem'; // Replace 'your-class-name' with the actual class name
      wrapper.appendChild(new_post);

      return wrapper;

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

  // ########## LIKE BUTTON ##############
  
  document.addEventListener('DOMContentLoaded', function() {
    var likeButton = document.querySelector('.like-post');

    if (likeButton) {
        likeButton.addEventListener('click', function(event) {
            var postId = event.target.getAttribute('data-post-id');
            likePost(postId);
        });
    }
  });

  document.querySelectorAll('.like-count').forEach(function(button) {
    button.addEventListener('click', function(event) {
        //event.preventDefault();
        this.classList.toggle('liked');
        var icon = this.querySelector('span'); // Select the span element
        if (this.classList.contains('liked')) {
            // Perform like action
            icon.classList.remove('far');
            icon.classList.add('fas', 'fa-heart');
        } else {
            // Perform unlike action
            icon.classList.remove('fas', 'fa-heart');
            icon.classList.add('far', 'fa-heart');
        }
    });
  });
 
  function likePost(postId) {
    console.log(2);
    let data = { id: postId };
    sendAjaxRequest('post', '/post/like/' + postId, data, handleLikeResponse);
  }

  function handleLikeResponse() {
    
    if (this.status >= 200 && this.status < 400) {
      let data = JSON.parse(this.responseText);
    
      var likeCountElement = document.querySelector('.like-count[data-post-id="' + data.id + '"]');
      if (likeCountElement) {
        likeCountElement.textContent = data.likeCount;
      }
      console.log(data.message);
    } else {
      console.error('Error:', this.status, this.statusText);
    }
  }

  function scrollToTop() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }

  window.addEventListener('scroll', function() {
    var button = document.getElementById('scrollToTopButton');
    if (window.scrollY > 100) { // Show the button after 100px of scrolling
      button.style.display = "block";
    } else {
      button.style.display = "none";
    }
  });


  addEventListeners();
  